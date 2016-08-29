package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.net.ConnectivityManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.view.MenuItemCompat;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.SearchView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.miido.analiizoOBRAS.mcompose.Constants;
import com.miido.analiizoOBRAS.model.Interviewer;
import com.miido.analiizoOBRAS.model.Project;
import com.miido.analiizoOBRAS.util.PropertyReader;
import com.miido.analiizoOBRAS.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Properties;

import io.socket.client.Ack;
import io.socket.client.IO;
import io.socket.client.Socket;
import io.socket.emitter.Emitter;

public class ProjectsActivity extends ActionBarActivity implements SwipeRefreshLayout.OnRefreshListener,AdapterView.OnItemClickListener,View.OnClickListener{

    private Toolbar toolbar;
    private SwipeRefreshLayout refreshLayout;
    private ListView listView;
    private ProjectAdapter projectAdapter;
    private ImageButton refreshButton;

    private ArrayList<Project> projects = new ArrayList<>();

    private final String GET_PROJECTS_SERVICE_SOCKET = "projects.select.socket.service";
    private Socket socket;
    private ConnectivityManager connectivityManager;

    private Interviewer interViewer;

    private Constants constants;
    private SqlHelper sqlHelper;
    private Properties properties;

    private final int THRESHOLD = 3;

    public static final String PROJECT_EXTRA = "project";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_projects);

        this.constants = new Constants();
        properties = new PropertyReader(this).getMyProperties("application.properties");

        this.interViewer = getIntent().getParcelableExtra(LoginActivity.INTERVIEWER_EXTRA);

        this.toolbar = (Toolbar) findViewById(R.id.project_tool_bar);
        this.toolbar.setTitle(R.string.projects_toolbar_title);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        this.toolbar.setNavigationOnClickListener(this);

        this.listView = (ListView) findViewById(R.id.projectList);
        this.listView.setAdapter(projectAdapter = new ProjectAdapter(this, projects));
        this.listView.setOnItemClickListener(this);

        this.refreshLayout = (SwipeRefreshLayout) findViewById(R.id.projects_swipe_refresh_layout);
        this.refreshLayout.setColorSchemeColors(
                getResources().getColor(R.color.ColorRefresh),
                getResources().getColor(R.color.ColorPrimaryDark),
                getResources().getColor(R.color.ColorPrimary));
        this.refreshLayout.setOnRefreshListener(this);

        this.refreshButton = (ImageButton) findViewById(R.id.updateButton);
        this.refreshButton.setOnClickListener(this);

        this.connectivityManager = (ConnectivityManager) getSystemService(CONNECTIVITY_SERVICE);

        this.sqlHelper = new SqlHelper(this);
        this.sqlHelper.databaseName = constants.pollDatabase;
        this.sqlHelper.OOCDB();

        configureSocket();

        if(isNetworkAvailable()) {
            getProjectsFromServer();
        }else{
            getProjectsProcess(null);
        }
    }

    private void configureSocket(){
        if(this.socket == null){
            try {
                String property = (properties.getProperty("app.local.test","false").toUpperCase().equals("TRUE")
                        ? properties.getProperty("socket.local.host") : properties.getProperty("socket.remote.host"));
                this.socket = IO.socket(property);
                this.socket.on(Socket.EVENT_CONNECT, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.i(getClass().getName(), getString(R.string.app_socket_connect_message_i));
                    }
                });
                this.socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.e(getClass().getName(), getString(R.string.app_socket_connect_message_e));
                    }
                });
            }catch (URISyntaxException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
    }

    private void getProjectsFromServer(){
        refreshLayout.post(new Runnable() {
            @Override
            public void run() {
                refreshLayout.setRefreshing(true);
            }
        });
        socket.emit(properties.getProperty(GET_PROJECTS_SERVICE_SOCKET), interViewer.getId(), new Ack() {
            @Override
            public void call(Object... args) {
                try {
                    storeProjectsToDB(args[0].toString());
                    getProjectsProcess(null);
                } catch (JSONException ex) {
                    Log.e(getClass().getName(), ex.getMessage());
                } catch (SQLiteException ex) {
                    Log.e(getClass().getName(), ex.getMessage());
                }
            }
        });
    }

    private void initAdapter(){
        ArrayList<Project> projectsTmp = (ArrayList<Project>) projects.clone();
        for( int i = 0; i < projectsTmp.size(); i++){
            final Project tmpProject = projectsTmp.get(i);
            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    projectAdapter.remove(tmpProject);
                }
            });
        }
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                projectAdapter.notifyDataSetChanged();
            }
        });
    }

    private boolean isNetworkAvailable(){
        return connectivityManager != null &&
                connectivityManager.getActiveNetworkInfo() != null &&
                connectivityManager.getActiveNetworkInfo().isConnected();
    }

    private void getProjectsProcess(String key){

        class ProjectProcess extends AsyncTask<String,Integer, Integer>{

            @Override
            protected void onPreExecute() {
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(true);
                    }
                });
                initAdapter();
            }

            @Override
            protected Integer doInBackground(String... args) {
                try{
                    getProjectsFromDB(args[0]);
                }catch (SQLiteException ex){
                    Log.e(getClass().getName(), ex.getMessage());
                    return -1;
                }
                Cursor cursor = sqlHelper.getCursor();
                for(int i = 0; i < cursor.getCount(); i++){
                    Project project = new Project(
                            cursor.getInt(0),
                            cursor.getString(2),
                            cursor.getString(3)
                    );
                    cursor.moveToNext();
                    projects.add(project);
                    publishProgress(0);
                }
                cursor.close();
                return 0;
            }

            @Override
            protected void onPostExecute(Integer result) {
                if(result == 0){
                    Log.i(getClass().getName(),"Proyectos sincronizados con exito");
                }else{
                    if(result == -1){
                        Log.i(getClass().getName(),"no se pudo sincronizar los proyectos");
                    }
                }
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(false);
                    }
                });
            }

            @Override
            protected void onProgressUpdate(Integer... values) {
                if(values[0] == 0){
                    projectAdapter.notifyDataSetChanged();
                }
            }

        }

        new ProjectProcess().execute(key);

    }

    private void getProjectsFromDB(String key)throws SQLiteException{
        String tableName = "projects";
        String fields = "*";
        if(key == null || key.equals("")){
            sqlHelper.setQuery(constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.replace("[FIELDS]",fields).replace("[TABLE]", tableName)
                    .replace("[CONDITIONS]", "userid = " + interViewer.getId()));
        }else {
            sqlHelper.setQuery(constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.replace("[FIELDS]",fields).replace("[TABLE]",tableName)
                    //.replace("[CONDITIONS]", " (name LIKE '" + key + "%' OR description LIKE '" + key + "%' )"));
                    .replace("[CONDITIONS]", " userid = " + interViewer.getId() + " AND (name LIKE '" + key + "%' OR description LIKE '" + key + "%' )"));
        }
        sqlHelper.execQuery();
    }

    private void storeProjectsToDB(String projects)throws SQLiteException,JSONException{
        sqlHelper.setQuery(constants.CREATE_PROJECTS_TABLE_QUERY);
        sqlHelper.execQuery();
        JSONArray jsonProjects = new JSONArray(projects);
        for(int i = 0; i < jsonProjects.length(); i++){
            JSONObject project = jsonProjects.getJSONObject(i);
            sqlHelper.setQuery(String.format(constants.INSERT_PROJECT_DATA_QUERY,
                    project.getInt("id"),
                    interViewer.getId(),
                    project.getString("name"),
                    project.getString("description"))
            );
            try {
                sqlHelper.execQuery();
            }catch (SQLiteException ex){
                sqlHelper.setQuery(String.format(constants.UPDATE_PROJECT_DATA_QUERY,
                        project.getString("name"), project.getString("description"), project.getInt("id")));
                sqlHelper.execUpdate();
                Log.i(getClass().getName(), "Registro modificado");
            }
        }
    }

    private void startPolls(int position){
        Intent intent = new Intent(this, PollsActivity.class);
        intent.putExtra(PROJECT_EXTRA, projectAdapter.getItem(position));
        intent.putExtra(LoginActivity.INTERVIEWER_EXTRA, this.interViewer);
        //intent.putExtra("userId", "1");
        startActivity(intent);
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        startPolls(position);
    }

    @Override
    public void onRefresh() {
        if(isNetworkAvailable()) {
            getProjectsFromServer();
        }else{
            getProjectsProcess(null);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        getMenuInflater().inflate(R.menu.projects_menu, menu);
        MenuItem searchItem = menu.findItem(R.id.menuSearch);
        SearchView searchView = (SearchView) MenuItemCompat.getActionView(searchItem);
        searchView.setQueryHint(getString(R.string.projects_toolbar_menuSearch_hint));

        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String key) {
                if(key.equals("")){
                    Log.i("SUBMIT","VACIO");
                }else{
                    getProjectsProcess(key);
                }
                return false;
            }

            @Override
            public boolean onQueryTextChange(String key) {
                if(key.equals("")){
                    Log.i("CHANGE","VACIO");
                }else{
                    if(key.length() >= THRESHOLD){
                        getProjectsProcess(key);
                    }
                }
                return false;
            }
        });

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case R.id.menuQuit: showQuitConfirmDialog();break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.updateButton:
                if(isNetworkAvailable()){
                    getProjectsFromServer();
                }else{
                    getProjectsProcess(null);
                }
                ;break;
            default:onBackPressed();
        }
    }

    /**
     * Muestra un dialogo de confirmación con dos opciones (aceptar y cancelar) para permitirle escojer al usuario si desea salir de la sesión o no.
     * @see AlertDialog
     */
    private void showQuitConfirmDialog(){
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.app_confirm_dialog_title);
        builder.setMessage(R.string.app_confirm_quit_message);
        builder.setNegativeButton(R.string.app_negative_button_text, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        }).setPositiveButton(R.string.app_affirmative_button_text, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                finish();
            }
        });
        builder.create().show();
    }

    /**
     * Método que responde al ciclo de vida de la actividad cuando es destenida.
     */
    @Override
    protected void onStop() {
        super.onStop();
        //sqlHelper.close();
    }

    /**
     * Método que responde al ciclo de vida de la actividad cuando es destruida.
     */
    @Override
    protected void onDestroy() {
        super.onDestroy();
        socket.disconnect();
        socket.close();
    }

    /**
     * Método que responde al ciclo de vida de la actividad cuando es inciada.
     */
    @Override
    protected void onStart() {
        super.onStart();
        socket.connect();
    }

    @Override
    public void onBackPressed() {
        showQuitConfirmDialog();
    }

    class ProjectAdapter extends ArrayAdapter<Project>{

        private Context context;
        private ArrayList<Project> items;

        public ProjectAdapter(Context context, ArrayList<Project> items){
            super(context, -1, items);
            this.context = context;
            this.items = items;
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent) {
            View rowView = convertView;

            if(rowView == null){
                LayoutInflater layoutInflater = (LayoutInflater) this.context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                rowView = layoutInflater.inflate(R.layout.project_item_layout_new, parent, false);
            }

            ImageView imageView = (ImageView) rowView.findViewById(R.id.projectIcon);
            TextView textViewName = (TextView) rowView.findViewById(R.id.projectName);
            TextView textViewDesc = (TextView) rowView.findViewById(R.id.projectDescription);

            imageView.setImageResource(R.drawable.ic_action_building);
            Project project = items.get(position);
            textViewName.setText(project.getName());
            textViewDesc.setText(project.getDescription());

            return rowView;
        }
    }

}
