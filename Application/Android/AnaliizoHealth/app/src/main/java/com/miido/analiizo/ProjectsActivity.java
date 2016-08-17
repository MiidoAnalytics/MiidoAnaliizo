package com.miido.analiizo;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.net.ConnectivityManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.StrictMode;
import android.provider.Settings;
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
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.Interviewer;
import com.miido.analiizo.model.Project;
import com.miido.analiizo.util.PropertyReader;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URISyntaxException;
import java.net.URL;
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

    private ProgressDialog progressDialog;

    private final int THRESHOLD = 3;

    public static final String PROJECT_EXTRA = "project";

    public static final int SELECT_CITY_REQUEST_CODE = 1000;

    private boolean CONNECTION_ESTABLISHED = false;

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
        this.sqlHelper.setQuery(constants.CREATE_PROJECTS_TABLE_QUERY);
        this.sqlHelper.execQuery();

        configureSocket();

        getDataBaseFromServerProcess("","");

        if(thereIsProjects()) {
            getProjectsProcess(null);
        }else{
            if(isNetworkAvailable()){
                getProjectsFromServer();
            }else{
                launchActiveNetworkDialog();
            }
        }
        launchInterviewerInfoDialog();
    }

    private boolean thereIsProjects(){
        getProjectsFromDB(null);
        Cursor cursor = sqlHelper.getCursor();
        int count = cursor.getCount();
        cursor.close();
        return count > 0;
    }

    private void launchInterviewerInfoDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,
                getString(R.string.app_alert_dialog_title),
                interViewer.getUser() + " usted ha sido asignado a el municipio de SINCELEJO del departamento de SUCRE.\n" +
                        "Si este no es el municipio donde usted está operando por favor " +
                        "ingrese al menú ubicado en la parte superior derecha, opción \"seleccinar municipio\"");
        d.show();
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
                        CONNECTION_ESTABLISHED = true;
                    }
                });
                this.socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.e(getClass().getName(), getString(R.string.app_socket_connect_message_e));
                        CONNECTION_ESTABLISHED = false;
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

    /**
     * Muestra un dialogo que informa el usuario una situación de desconexión y abre la actividad de configuración de conexión.
     */
    private void launchActiveNetworkDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.confirm(this, "Verificar Conexión",
                "Esta operación requiere una conexión a internet.\n" +
                        "¿Desea ver sus prefenecias de red?");
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                Intent intent = new Intent(Settings.ACTION_SETTINGS);
                startActivity(intent);
            }
        });
        dialog.show();
    }

    private boolean dataBaseExists(){
        @SuppressLint("SdCardPath")
        File databaseDirectory = new File("/data/data/" + getPackageName() + "/databases/");
        File databaseFile = new File(databaseDirectory, this.constants.itemsDatabase + "");
        return databaseFile.exists();
    }

    private void launchProgressDialog(){
        progressDialog = ProgressDialog.show(ProjectsActivity.this,
                getString(R.string.app_progress_dialog_title),
                "Obteniendo base de datos de afiliados",true);
    }

    private void getDataBaseFromServerProcess(String operation,String databaseName){
        class GetDataBaseProcess extends AsyncTask<String,Integer,Integer>{

            String operation;

            public GetDataBaseProcess(String operation){
                super();
                this.operation = operation;
            }

            @Override
            protected void onPreExecute() {
                if(!dataBaseExists() || operation.toUpperCase().equals("UPDATE")){
                    launchProgressDialog();
                }
            }

            @Override
            protected Integer doInBackground(String... strings) {
                if(!dataBaseExists() || operation.toUpperCase().equals("UPDATE")){
                    try {
                        downloadDataBase();
                        return 1;
                    }catch (Exception ex){
                        Log.e(getClass().getName(), ex.getMessage());
                        return -1;
                    }
                }else{
                    return 2;
                }
            }

            @Override
            protected void onPostExecute(Integer result) {
                if(result != 2) {
                    progressDialog.dismiss();
                }else{
                    if(result == -1){
                        com.miido.analiizo.util.Dialog.Alert(ProjectsActivity.this,
                                R.string.app_connection_refused_title,R.string.app_connection_refused_message).show();
                    }
                }
            }
        }
        new GetDataBaseProcess(operation).execute(databaseName);
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

    private void startSelectCityActivity(){
        Intent intent = new Intent(getApplicationContext(), ItemSelectActivity.class);
        intent.putExtra(ItemSelectActivity.TITLE_EXTRA, "Departamentos");
        intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, "departaments");
        intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, "");
        startActivityForResult(intent, SELECT_CITY_REQUEST_CODE);
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        startPolls(position);
    }

    /**
     * Dispatch incoming result to the correct fragment.
     *
     * @param requestCode
     * @param resultCode
     * @param data
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(resultCode == RESULT_OK){
            if(requestCode == SELECT_CITY_REQUEST_CODE){
                //Toast.makeText(this, data.getStringExtra(ItemSelectActivity.RESULT_EXTRA), Toast.LENGTH_LONG).show();
                getDataBaseFromServerProcess("update","");
            }
        }
    }

    @Override
    public void onRefresh() {
        if(isNetworkAvailable() && CONNECTION_ESTABLISHED){
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

    /**
     * Inicia la actividad de estadisticas de encuesta.
     */
    private void startStatistics(){
        Intent intent = new Intent(this, StatisticsActivity.class);
        startActivity(intent);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case R.id.menuQuit: showQuitConfirmDialog();break;
            case R.id.menuStatistics: startStatistics();break;
            case R.id.menuZone:startSelectCityActivity();break;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.updateButton:
                if(isNetworkAvailable() && CONNECTION_ESTABLISHED){
                    getProjectsFromServer();
                }else{
                    getProjectsProcess(null);
                }
                break;
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

    public String downloadDataBase() throws Exception {
        try {

            @SuppressLint("SdCardPath")
            File databaseDirectory = new File("/data/data/" + getPackageName() + "/databases/");
            databaseDirectory.mkdirs();
            File databaseFile = new File(databaseDirectory, this.constants.itemsDatabase + "");

            if (databaseFile.createNewFile()) {
                databaseFile.createNewFile();
            }

            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            String size;
            URL url = new URL(properties.getProperty("http.items.database.remote.host"));
            HttpURLConnection hrc = (HttpURLConnection) url.openConnection();
            hrc.setRequestMethod("GET");
            hrc.setDoOutput(true);
            hrc.connect();


            FileOutputStream fos = new FileOutputStream(databaseFile);
            InputStream is = hrc.getInputStream();
            size = hrc.getContentLength() + "";
            byte[] buffer = new byte[92048];
            int bufferLength = 0;

            while ((bufferLength = is.read(buffer)) > 0) {
                fos.write(buffer, 0, bufferLength);
            }
            fos.close();
            return size;
        } catch (MalformedURLException e) {
            return "mfwe" + e.getMessage();
        } catch (IOException ie) {
            return "ie" + ie.getMessage();
        }
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
