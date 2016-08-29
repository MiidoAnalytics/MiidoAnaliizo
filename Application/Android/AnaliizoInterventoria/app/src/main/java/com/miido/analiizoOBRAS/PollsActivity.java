package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.os.AsyncTask;
import android.provider.Settings;
import android.support.v4.view.MenuItemCompat;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.SearchView;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.support.v7.widget.PopupMenu;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageButton;
import android.widget.ListView;
import android.widget.Toast;

import com.miido.analiizoOBRAS.mcompose.Constants;
import com.miido.analiizoOBRAS.model.Interviewer;
import com.miido.analiizoOBRAS.model.Poll;
import com.miido.analiizoOBRAS.model.Project;
import com.miido.analiizoOBRAS.util.PropertyReader;
import com.miido.analiizoOBRAS.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.URISyntaxException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.Properties;
import java.util.StringTokenizer;

import io.socket.client.Ack;
import io.socket.client.IO;
import io.socket.client.Socket;
import io.socket.emitter.Emitter;

/**
 * Activity que muestra todas las encuestas asignadas a un encuestador en una lista ListView
 * @author Ing. Miguel Angel Urango Blanco 11/01/2016
 * @version 1.0
 * @see ActionBarActivity
 * @see android.view.View.OnClickListener
 * @see android.widget.AdapterView.OnItemClickListener
 * @see android.support.v4.widget.SwipeRefreshLayout.OnRefreshListener
 */

public class PollsActivity extends ActionBarActivity implements View.OnClickListener,AdapterView.OnItemClickListener,SwipeRefreshLayout.OnRefreshListener,LocationListener{

    private Constants constants;
    private SqlHelper sqlStructure;
    private SqlHelper sqlPolldata;
    private Properties properties;

    private Toolbar toolbar;
    private SwipeRefreshLayout refreshLayout;
    private ListView listView;
    private PollAdapter adapter;
    private ImageButton updateButton;
    private ProgressDialog progressDialog;

    private io.socket.client.Socket socket;
    private final String  GET_POLLS_SOCKET_SERVICE = "polls.select.socket.service";
    public static  final String ENQUEUE_POLLS_SOCKET_SERVICE = "polls.enqueue.socket.service";


    private final int SAVED_POLL_REQUEST_CODE = 1000;
    private final int SENT_POLL_REQUEST_CODE = 1001;

    private ArrayList<Poll> polls = new ArrayList<>();
    private Project currentProject;
    private Interviewer currentInterviewer;

    private ConnectivityManager connectivityManager;
    private LocationManager locationManager;

    private boolean IS_GPS_ENABLED = false;
    private boolean CONNECTION_ESTABLISHED = true;

    public static final String NEW_POLL_EXTRA = "NEW_POLL_EXTRA";

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.polls_layout);

        this.toolbar = (Toolbar) findViewById(R.id.tool_bar);
        this.toolbar.setTitle(R.string.polls_toolbar_title);
        this.toolbar.setSubtitle(R.string.polls_toolbar_subtitle);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        this.listView = (ListView) findViewById(R.id.pollList);
        this.refreshLayout =(SwipeRefreshLayout) findViewById(R.id.swipe_refresh_layout);
        this.refreshLayout.setColorSchemeColors(
                getResources().getColor(R.color.ColorRefresh),
                getResources().getColor(R.color.ColorPrimaryDark),
                getResources().getColor(R.color.ColorPrimary));
        this.refreshLayout.setOnRefreshListener(this);

        this.listView.setAdapter(this.adapter = new PollAdapter(this, polls, this));
        this.listView.setOnItemClickListener(this);

        this.updateButton = (ImageButton) findViewById(R.id.updateButton);
        this.updateButton.setOnClickListener(this);

        this.constants = new Constants();
        this.properties = new PropertyReader(this).getMyProperties("application.properties");

        this.currentProject = getIntent().getParcelableExtra(ProjectsActivity.PROJECT_EXTRA);
        this.currentInterviewer = getIntent().getParcelableExtra(LoginActivity.INTERVIEWER_EXTRA);

        connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

        locationManager=(LocationManager) getSystemService(Context.LOCATION_SERVICE);
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, this);
        if(locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
            IS_GPS_ENABLED=true;
        }

        this.sqlStructure = new SqlHelper(this);
        this.sqlStructure.databaseName = constants.structureDatabase;
        this.sqlStructure.OOCDB();

        this.sqlPolldata = new SqlHelper(this);
        this.sqlPolldata.databaseName = constants.pollDatabase;
        this.sqlPolldata.OOCDB();

        configureSocket();

    }

    private void configureSocket(){
        if(this.socket == null){
            try {
                String property = (properties.getProperty("app.local.test","false").toUpperCase().equals("TRUE")
                        ? properties.getProperty("socket.local.host") : properties.getProperty("socket.remote.host"));
                this.socket = IO.socket(property);
                this.socket.on(io.socket.client.Socket.EVENT_CONNECT, new Emitter.Listener(){
                    @Override
                    public void call(Object... args){
                        CONNECTION_ESTABLISHED = true;
                        Log.i(getClass().getName(), getString(R.string.app_socket_connect_message_i));
                    }
                });

                this.socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                refreshLayout.setRefreshing(false);
                                //launchConnectionRefusedDialog();
                                CONNECTION_ESTABLISHED = false;
                                Log.e(getClass().getName(),getString(R.string.app_socket_connect_message_i));
                            }
                        });
                    }
                });

                this.socket.on(properties.getProperty(GET_POLLS_SOCKET_SERVICE), new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        try {
                            String pollsStructure = modifyStructure(args[0].toString());
                            storePollsStructureToDB(pollsStructure);
                            refresh(pollsStructure);
                        }catch (Exception ex){
                            ex.printStackTrace();
                        }
                    }
                });

                this.socket.connect();
                String pollsStructure;
                if((pollsStructure = getPollsStructureFromDB()) == null){
                    if(isNetworkAvailable()) {
                        this.socket.emit(properties.getProperty(GET_POLLS_SOCKET_SERVICE), currentInterviewer.getId(), currentProject.getId());
                    }else{
                        launchActiveNetworkDialog();
                    }
                }else{
                    refresh(pollsStructure);
                }
            }catch (URISyntaxException ex){
                ex.printStackTrace();
            }
        }
    }

    private boolean isNetworkAvailable(){
        return connectivityManager != null &&
                connectivityManager.getActiveNetworkInfo() != null &&
                connectivityManager.getActiveNetworkInfo().isConnected();
    }

    private void launchActiveNetworkDialog(){
        AlertDialog dialog = com.miido.analiizoOBRAS.util.Dialog.confirm(this, R.string.app_disconnected_title,
                R.string.app_disconnected_message);
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                Intent intent = new Intent(Settings.ACTION_SETTINGS);
                startActivity(intent);
            }
        });
        dialog.show();
    }

    private void launchConnectionRefusedDialog(){
        AlertDialog dialog = com.miido.analiizoOBRAS.util.Dialog.Alert(this, R.string.app_connection_refused_title,
                R.string.app_connection_refused_message);
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                dialogInterface.dismiss();
            }
        });
        dialog.show();
    }

    /**
     * Consulta en la base de datos local las estructuras de las encuestas que estan disponibles
     * @return el string de la estructura de las encuestas disponibles.
     * @see SqlHelper
     */
    private String getPollsStructureFromDB(){
        try{
            sqlStructure.setQuery(constants.CREATE_STRUCTURE_DATA_TABLE_QUERY);
            sqlStructure.execQuery();
            sqlStructure.setQuery(constants.SELECT_STRUCTURE_DATA_QUERY);
            sqlStructure.execQuery();
            Cursor cursor = sqlStructure.getCursor();
            if(cursor.getCount() > 0){
                cursor.moveToFirst();
                String result = cursor.getString(0);
                cursor.close();
                return result;
            }
        }catch (Exception ex){
            return null;
        }
        return null;
    }

    private int getPollsCount(long pollId) throws SQLiteException{
        sqlPolldata.setQuery(constants.CREATE_POLL_DATA_TABLE_QUERY);
        sqlPolldata.execQuery();
        if(pollId <= 0){
            //sqlPolldata.setQuery("SELECT COUNT(id) FROM poll");
            sqlPolldata.setQuery(constants.GENERIC_SELECT_QUERY_WITHOUT_CONDITIONS.
                    replace("[FIELDS]","COUNT(id)").replace("[TABLE]","poll"));
        }else {
            //sqlPolldata.setQuery(String.format("SELECT COUNT(id) FROM poll where idstructure = %s", pollId));
            sqlPolldata.setQuery(constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.
                    replace("[FIELDS]","COUNT(id)").replace("[TABLE]","poll").
                    replace("[CONDITIONS]","idstructure = " + pollId));
        }
        sqlPolldata.execQuery();
        int count = 0;
        try {
            Cursor cursor = sqlPolldata.getCursor();
            for (int i = 0; i < cursor.getCount(); i++) {
                count = cursor.getInt(0);
            }
            cursor.close();
        }catch (SQLiteException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
        return count;
    }

    private int getPollsSavedCount(long pollid)throws SQLiteException{
        SqlHelper sqlHelper = new SqlHelper(this);
        sqlHelper.databaseName = constants.pollDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_POLL_DATA_TABLE_QUERY);
        sqlHelper.execQuery();
        if(pollid <= 0){
            //sqlPolldata.setQuery("SELECT COUNT(id) FROM poll");
            sqlHelper.setQuery(constants.GENERIC_SELECT_QUERY_WITHOUT_CONDITIONS.
                    replace("[FIELDS]","COUNT(id)").replace("[TABLE]","poll"));
        }else{
            //sqlPolldata.setQuery(String.format("SELECT COUNT(id) FROM poll WHERE idstructure = %s AND senddate is null " , pollid));
            sqlHelper.setQuery(constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.
                    replace("[FIELDS]","COUNT(id)").replace("[TABLE]","poll").
                    replace("[CONDITIONS]","senddate is null AND idstructure = " + pollid));
        }
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        int count = 0;
        for( int i = 0; i < cursor.getCount(); i++){
            count = cursor.getInt(0);
        }
        cursor.close();
        sqlHelper.close();
        return count;
    }

    public int getPollDataPausedSizeFromDB(long structureid) throws SQLiteException{
        //sqlPolldata.setQuery(String.format("SELECT COUNT(id) FROM pollpause WHERE idstructure = %s ;",structureid));
        sqlPolldata.setQuery(constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.
                replace("[FIELDS]","COUNT(id)").replace("[TABLE]","pollpause").
                replace("[CONDITIONS]","idstructure = " + structureid));
        sqlPolldata.execQuery();
        Cursor cursor = sqlPolldata.getCursor();
        if(cursor.getCount() > 0){
            int count = cursor.getInt(0);
            cursor.close();
            return count;
        }
        return 0;
    }

    public String getPausedPollFromDB(long structureid) throws SQLiteException{
        sqlPolldata.setQuery(constants.CREATE_DATA_PAUSED_TABLE_QUERY);
        sqlPolldata.execQuery();
        sqlPolldata.setQuery(String.format(constants.SELECT_DATA_PAUSED_QUERY, structureid));
        sqlPolldata.execQuery();
        String target = null;
        Cursor cursor = sqlPolldata.getCursor();
        if(cursor.getCount() > 0){
            target = cursor.getString(2);
        }
        cursor.close();
        return target;
    }

    public void insertPollDataPausedToDB(long structureid,String target) throws SQLiteException{
        sqlPolldata.setQuery(String.format(constants.INSERT_DATA_PAUSED_QUERY, structureid, target));
        sqlPolldata.execInsert();
    }

    /**
     * Inserta las estructuras de las encuestas en la base de datos local del dispositivo.
     * @param pollsStructure las estructuras de las encuestas en forma de texto
     * @return un arreglo de strings en donde se almacenan los mensajes del estado de la consulta
     * @throws JSONException se lanza si ocurre algun error de coversión del texto de las estructuras a JSON
     * @see #storePollsStructureToDB(String, int)
     * @see JSONArray
     * @see JSONObject
     */
    private String[] storePollsStructureToDB(String pollsStructure) throws JSONException {
        JSONArray jsonPolls = new JSONArray(pollsStructure);
        if (jsonPolls.length() > 0) {
            JSONObject documentInfo = jsonPolls.getJSONObject(0).getJSONObject("Document_info");
            StringTokenizer st = new StringTokenizer(documentInfo.getString("minVersionName"), ".");
            int version_name = Integer.parseInt(st.nextToken());
            int version_subName = Integer.parseInt(st.nextToken());
            if (this.constants.version_name >= version_name) {
                if (this.constants.version_subname >= version_subName) {
                    if (documentInfo.getInt("structureStatus") == this.constants.appStatus) {
                        storePollsStructureToDB(pollsStructure, documentInfo.getInt("structureVersion"));
                        return (new String[]{this.constants.downOk, this.constants.uParamsOk});
                    }
                } else {
                    return (new String[]{this.constants.downFail, this.constants.uParamsFail});
                }
            } else {
                return (new String[]{this.constants.downFail, this.constants.uParamsFail});
            }
        } else {
            return new String[]{"DONE", "EMPTY"};
        }
        return new String[]{"DONE", "OK"};
    }

    /**
     * Metodo ayudante de storePollsStructureToDB(String), ejecuta la consulta para el insertado de la estructura en la base de datos local.
     * @param structure las estructuras de las encuestas en forma de texto
     * @param structureVersion versión de la estructura
     * @see SqlHelper
     * @see #storePollsStructureToDB(String)
     */
    private void storePollsStructureToDB(String structure, int structureVersion){
        this.sqlStructure.setQuery(this.constants.UPDATE_STRUCTURE_DATA_QUERY);
        this.sqlStructure.execUpdate();
        //this.sqlStructure.setQuery("INSERT INTO Structure VALUES (NULL, '" + structure + "', 1, " + structureVersion + ");");
        this.sqlStructure.setQuery(String.format(constants.INSERT_STRUCTURE_DATA_QUERY, structure, 1, structureVersion));
        this.sqlStructure.execInsert();
    }

    /**
     * Inicializa el adaptador antesde de hacerce el refresco del listado para prevenir duplicados en la lista
     * @see Poll
     */
    private void initAdapter(){
        ArrayList<Poll> tmpPolls =(ArrayList<Poll>) adapter.items().clone();
        for (final Poll poll : tmpPolls) {
            adapter.items().remove(poll);
        }
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                adapter.notifyDataSetChanged();
            }
        });
    }

    /**
     * Utiliza un objeto AsyncTask para ejecutar el proceso de refresco de las estructuras de las encuestas asignadas a un encuestador
     * y mostrarlas en la lista.
     * @param pollsStructure estructuras de las encuestas
     * @see AsyncTask
     */
    private void refresh(String pollsStructure){
        class RefreshProcess extends AsyncTask<String,Poll,Boolean>{

            @Override
            protected void onPreExecute(){
                refreshLayout.post(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(true);
                    }
                });
                initAdapter();
            }

            @Override
            protected Boolean doInBackground(String... params) {
                try {
                    prueba(new JSONArray(params[0]));
                    JSONArray polls = new JSONArray(params[0]);
                    for(int i=0; i<polls.length();i++){
                        JSONObject documentInfo = polls.getJSONObject(i).getJSONObject("Document_info");
                        JSONArray forms = polls.getJSONObject(i).getJSONArray("forms");
                        JSONArray fields = polls.getJSONObject(i).getJSONArray("fields_structure");
                        if(isNetworkAvailable()) {
                            getServices(fields);
                        }
                        Poll poll = new Poll(
                                0,
                                documentInfo.getLong("structureid"),
                                documentInfo.getLong("interviewerid"),
                                documentInfo.getLong("clientid"),
                                documentInfo.getLong("projectid"),
                                documentInfo.getString("projectname"),
                                forms.getJSONObject(0).getString("Name"),
                                forms.getJSONObject(0).getString("Header"),
                                "","",""
                        );
                        poll.setProjectName("Informes sin enviar: "+getPollsSavedCount(poll.getStructureId()));
                        adapter.items().add(poll);
                        publishProgress(poll);
                    }
                } catch (JSONException e) {
                    Log.e(getClass().getName(),e.getMessage());
                    return false;
                }
                return true;
            }

            @Override
            protected void onProgressUpdate(Poll... progress) {

                adapter.notifyDataSetChanged();
            }

            @Override
            protected void onPostExecute(Boolean result){
                if(result){
                    Log.i(getClass().getName(),"Encuestas Obtenidas con exito");
                }else{
                    Log.e(getClass().getName(),"Ocurrió un error al obtener las encuestas");
                }
                refreshLayout.post(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(false);
                    }
                });
            }
        }
        new RefreshProcess().execute(pollsStructure);
    }

    private void prueba(JSONArray jarray){
        try{
            JSONObject jresponse = new JSONObject();
            for(int i = 0; i < jarray.length(); i++){
                JSONArray forms = jarray.getJSONObject(i).getJSONArray("forms");
                JSONArray fields = jarray.getJSONObject(i).getJSONArray("fields_structure");
                //Log.e("FORMS", forms.toString());
                //Log.e("FIELDS", fields.toString());
                JSONObject jobject = new JSONObject("{}");
                for(int j = 0; j < forms.length(); j++){
                    JSONObject form = forms.getJSONObject(j);
                    if(form.getString("Inside").equals("0")) {
                        for (int k = 0; k < fields.length(); k++) {
                            JSONObject field = fields.getJSONObject(k);
                            if (field.getString("Form").equals(form.getString("Id"))) {
                                JSONObject tmpField = new JSONObject();
                                tmpField.put("Id",field.getString("Id"));
                                tmpField.put("Type", field.getString("Type"));
                                tmpField.put("Label",field.getString("Label"));
                                tmpField.put("Values","res");
                                if(!field.getString("Handler").equals("0")){
                                    for ( int l = 0 ; l < forms.length(); l++){
                                        if(field.getString("Id").equals(forms.getJSONObject(l).getString("Parent"))){
                                            JSONObject t = new JSONObject("{}");
                                            for( int m = 0; m < fields.length(); m++){
                                                if(forms.getJSONObject(l).getString("Id").equals(fields.getJSONObject(m).getString("Form"))) {
                                                    JSONObject subTmpField = new JSONObject();
                                                    subTmpField.put("Id", fields.getJSONObject(m).getString("Id"));
                                                    subTmpField.put("Type", fields.getJSONObject(m).getString("Type"));
                                                    subTmpField.put("Label", fields.getJSONObject(m).getString("Label"));
                                                    subTmpField.put("Values", "res");
                                                    t.accumulate(forms.getJSONObject(l).getString("Name"), new JSONObject().put(fields.getJSONObject(m).getString("Name"),subTmpField));
                                                    tmpField.put("SubForms",t);
                                                }

                                            }

                                        }
                                    }
                                }
                                jobject.accumulate(form.getString("Name"), new JSONObject().put(field.getString("Name"),tmpField));
                            }
                        }
                    }
                }
                //Log.e("RESPONSE", jobject.toString());
            }
        }catch (JSONException ex){

        }
    }

    private void getServices(JSONArray fields)throws JSONException{
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString("Type").equals("ac")){
                final String serviceName = field.getString("AutoComplete");
                if(!serviceName.equals("0")){
                    if(!tableExists(serviceName) && isNetworkAvailable() && CONNECTION_ESTABLISHED) {
                        socket.emit(properties.getProperty(ItemSelectActivity.GET_ITEMS_SOCKET_SERVICE),serviceName, new Ack() {
                            @Override
                            public void call(Object... args) {
                                final String arg = args[0].toString();
                                runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        try {
                                            createServiceDB(serviceName, new JSONArray(arg));
                                        } catch (JSONException ex) {
                                            Log.e(this.getClass().getName(),ex.getMessage());
                                        } catch (SQLiteException ex) {
                                            Log.e(this.getClass().getName(),ex.getMessage());
                                        }
                                    }
                                });
                            }
                        });
                    }else{
                        if(!isNetworkAvailable()){
                            runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    launchActiveNetworkDialog();
                                }
                            });
                        }else{
                            if(!CONNECTION_ESTABLISHED){
                                runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        launchConnectionRefusedDialog();
                                    }
                                });
                            }else{
                                Log.i(this.getClass().getName(), "La tabla "+serviceName+" ya existe o no hay conexión con el servidor");
                            }
                        }
                    }
                }
            }
        }
    }

    public boolean tableExists(String tableName){
        sqlStructure = new SqlHelper(this);
        sqlStructure.databaseName = constants.structureDatabase;
        sqlStructure.OOCDB();
        sqlStructure.setQuery(String.format("SELECT count(name) FROM sqlite_master where name = '%s' ;", tableName));
        sqlStructure.execQuery();
        Cursor cursor = sqlStructure.getCursor();
        if(cursor.getCount()>0){
            return cursor.getInt(0) > 0;
        }
        cursor.close();
        return false;
    }

    public void createServiceDB(String table,JSONArray service)throws JSONException,SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        sql.databaseName = constants.structureDatabase;
        sql.OOCDB();

        if(service.length() > 0){
            Iterator<String> keys = service.getJSONObject(0).keys();
            String createQuery = "CREATE TABLE IF NOT EXISTS '"+table+"' (";
            int i =0;
            ArrayList<String> ks = new ArrayList<>();
            while (keys.hasNext()){
                String key = keys.next();
                ks.add(key);
                if(i == 0){
                    createQuery += "id INTEGER NOT NULL PRIMARY KEY,";
                }else{
                    createQuery += "item TEXT NOT NULL,";
                }
                i++;
            }
            createQuery = createQuery.substring(0, createQuery.length() - 1);
            createQuery += ");";

            sql.setQuery(createQuery);
            sql.execQuery();

            String insertQuery = "INSERT INTO "+table+" VALUES";
            for(int j = 0; j < service.length(); j++){
                insertQuery += "(";
                for(int k = 0; k < ks.size(); k++) {
                    if (k == 0) {
                        insertQuery += service.getJSONObject(j).getInt(ks.get(k)) + ",";
                    } else {
                        insertQuery += "'" + service.getJSONObject(j).getString(ks.get(k)) + "',";
                    }
                }
                insertQuery = insertQuery.substring(0, insertQuery.length() - 1);
                insertQuery += "),";
            }
            insertQuery = insertQuery.substring(0, insertQuery.length() - 1);
            insertQuery += ";";

            sql.setQuery(insertQuery);
            sql.execInsert();
        }
    }

    private String modifyStructure(String pollsStructure)throws JSONException{
        JSONArray polls = new JSONArray(pollsStructure);
        for(int i = 0; i < polls.length(); i++) {
            JSONObject poll = polls.getJSONObject(i);
            JSONArray fieldStructure = poll.getJSONArray("fields_structure");
            JSONArray options = poll.getJSONArray("options");
            JSONArray tmpFieldStructure = new JSONArray("[]");
            for (int f = 0; f < fieldStructure.length(); f++) {
                JSONObject field = fieldStructure.getJSONObject(f);
                int id = fieldStructure.getJSONObject(f).getInt("Id");
                //field.put("Id", tmpFieldStructure.length() + 1);
                tmpFieldStructure.put(field);
                if (field.getString("Type").equals("tv")) {
                    for (int o = 0; o < options.length(); o++) {
                        JSONObject option = options.getJSONObject(o);
                        if (option.getJSONArray("Field").getInt(0) == id) {
                            for (int ops = 0; ops < option.getJSONArray("Options").length(); ops++) {
                                if (!option.getJSONArray("Options").getString(ops).equals("-")) {
                                    JSONObject fieldTmp = new JSONObject("{}");
                                    fieldTmp.put("Id", /*tmpFieldStructure.length() + 1*/fieldStructure.length() + ops);
                                    fieldTmp.put("Form", fieldStructure.getJSONObject(f).getString("Form"));
                                    fieldTmp.put("Hint", "0");
                                    fieldTmp.put("Name", field.getString("Name") + "_opcion" + ops);
                                    fieldTmp.put("Type", "cb");
                                    fieldTmp.put("Label", option.getJSONArray("Options").getString(ops));
                                    fieldTmp.put("Order", "0");
                                    fieldTmp.put("Rules", "0");
                                    fieldTmp.put("Length", "0");
                                    fieldTmp.put("Parent", "0");
                                    fieldTmp.put("FreeAdd", "0");
                                    fieldTmp.put("Handler", "0");
                                    fieldTmp.put("ReadOnly", "false");
                                    fieldTmp.put("Required", "true");
                                    fieldTmp.put("AutoComplete", "0");
                                    fieldTmp.put("ReferenceQuestion", id);
                                    tmpFieldStructure.put(fieldTmp);
                                }
                            }
                        }
                    }
                }
            }
            poll.put("fields_structure",tmpFieldStructure);
        }
        return polls.toString();
    }

    /**
     * Ejecuta un ProgressDialog indeterminado para informar al usuario que la aplicación está trabajando actualmente.
     * @see ProgressDialog
     */
    private void launchProgressDialog(){
        progressDialog = ProgressDialog.show(this,getString(R.string.app_progress_dialog_title)
                , getString(R.string.polls_progress_dialog_sendpolls_message),true);
    }

    /**
     * Utiliza un objeto AsyncTask para ejecutar el proceso de envio de las encuestas diligenciadas hacia el servidor
     * @param pollid el identificador de la encuesta que se enviará.
     * @see AsyncTask
     */
    private void sendPollsProcess(long pollid){
        class SendPollProcess extends AsyncTask<Long,Long,Integer>{

            private int countPollSent,pollsSize;

            public SendPollProcess(){
                super();
                countPollSent = pollsSize = 0;
            }

            @Override
            protected void onPreExecute(){
                launchProgressDialog();
            }

            @Override
            protected void onProgressUpdate(Long... values) {
                String polls;
                if((polls = getPollsStructureFromDB()) != null){
                    refresh(polls);
                }
            }

            @Override
            protected Integer doInBackground(Long... args) {
                try {
                    JSONArray polls = getPollsDataFromDB(args[0]);
                    for(int i = 0; i < ((this.pollsSize) = polls.length()); i++){
                        String pollData = polls.getString(i);
                        socket.emit(properties.getProperty(ENQUEUE_POLLS_SOCKET_SERVICE), pollData, new Ack() {
                            @Override
                            public void call(Object... args) {
                                if(args[0].equals("OK")){
                                    countPollSent++;
                                    publishProgress();
                                }
                            }
                        });
                        countPollSent++;
                    }
                }catch (JSONException ex){
                    return -1;
                }catch (SQLiteException ex){
                    return -1;
                }
                return countPollSent > 0 ? countPollSent : 0 ;
            }

            @Override
            protected void onPostExecute(Integer result){
                if(result == -1){
                    Toast.makeText(getApplicationContext(),R.string.polls_synchronize_error_message,Toast.LENGTH_LONG).show();
                }else{
                    if(result == 0){
                        Toast.makeText(getApplicationContext(),R.string.polls_empty_info_message, Toast.LENGTH_LONG).show();
                    }else{
                        Toast.makeText(getApplicationContext(),R.string.polls_synchronize_success_message, Toast.LENGTH_LONG).show();
                    }
                }
                progressDialog.dismiss();
            }
        }
        new SendPollProcess().execute(pollid);
    }

    /**
     * Consulta en la base de datos local todas las encuestas diligenciadas que aún no han sido enviadas al servidor
     * @param pollId identificador de la encuesta
     * @return Un objeto JSONArray de todas las encuestas diligenciadas que no han sido enviadas al servidor
     * @throws JSONException se lanza si hay un error de conversion del testo de las encuestas a un objecto JSONArray
     * @throws SQLiteException se lanza si ocurre algún error al ejecutar la consulta a la base de datos local.
     * @see SqlHelper
     * @see JSONArray
     */
    private JSONArray getPollsDataFromDB(long pollId)throws JSONException,SQLiteException{
        JSONArray polls = new JSONArray("[]");
        sqlPolldata.setQuery(String.format(constants.SELECT_POLL_DATA_QUERY, pollId));
        sqlPolldata.execQuery();
        Cursor cursor = sqlPolldata.getCursor();
        for(int i = 0; i < cursor.getCount(); i++){
            String poll = cursor.getString(1);
            polls.put(poll);
            cursor.moveToNext();
        }
        String sendDate = new SimpleDateFormat(new Constants().SimpleDateFormat).format(new Date());
        sqlPolldata.setQuery(String.format(constants.UPDATE_POLL_DATA_QUERY, sendDate, pollId));
        sqlPolldata.execUpdate();
        return polls;
    }

    /**
     * Busca un elemento en la lista ListView para filtrarlo en la misma
     * @param query texto a buscar dentro del la lista
     * @see PollAdapter
     */
    private void searchPoll(String query){
        for(int i=0; i< adapter.items().size(); i++){
            if(!adapter.items().get(i).getTitle().startsWith(query) && !adapter.items().get(i).getProjectName().startsWith(query)){
                Poll tag = adapter.items().get(i);
                adapter.remove(tag);
            }
        }
        adapter.notifyDataSetChanged();
    }

    /**
     * Inicia la actividad PollRecordActivity
     * @param poll los datos de la encuesta seleccionada.
     * @see Intent
     * @see PollRecordActivity
     */
    private void startPollRecord(Poll poll){
        Intent intent = new Intent(this,PollRecordActivity.class);
        intent.putExtra("poll", poll);
        startActivityForResult(intent, SENT_POLL_REQUEST_CODE);
    }

    private void showAlerDialog(String title, String message){
        AlertDialog dialog = com.miido.analiizoOBRAS.util.Dialog.Alert(this, title, message);
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                dialog.dismiss();
            }
        });
        dialog.show();
    }

    private void showAlertDialogTestApp(){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.Alert(this,
                R.string.app_alert_dialog_title,
                R.string.app_alert_dialog_testVersion_message);
        d.show();
    }

    /**
     * Muestra un menú PopupMenu desplegable de opciones asignadas a un botón de cada elemento de la lista.
     * @param view vista que desplegará el menú
     * @see PopupMenu
     * @see #startPoll(Poll)
     * @see #sendPollsProcess(long)
     * @see #startPollRecord(Poll)
     */
    private void showPopupMenu(View view) {
        final Poll poll = (Poll) view.getTag();

        PopupMenu popup = new PopupMenu(this, view);
        popup.getMenuInflater().inflate(R.menu.polls_popup_menu, popup.getMenu());

        popup.getMenu().getItem(0).setEnabled(getPollsSavedCount(poll.getStructureId()) == 0 && getPollsCount(poll.getStructureId()) == 0);
        popup.getMenu().getItem(1).setEnabled(getPollsSavedCount(poll.getStructureId()) > 0);
        popup.getMenu().getItem(2).setEnabled(getPollsCount(poll.getStructureId()) > 0);

        popup.getMenu().getItem(3).setVisible(false);

        popup.setOnMenuItemClickListener(new PopupMenu.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem menuItem) {
                switch (menuItem.getItemId()) {
                    case R.id.menuFillPoll:
                        startPoll(poll);
                        return true;
                    case R.id.menuSendPoll:
                        AlertDialog confirmDialog = com.miido.analiizoOBRAS.util.Dialog.confirm(PollsActivity.this,
                                R.string.app_confirm_dialog_title,
                                R.string.polls_confirm_dialog_sendpolls_message);
                        confirmDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
                            @Override
                            public void onCancel(DialogInterface dialogInterface) {
                                if (getPollsSavedCount(poll.getStructureId()) > 0) {
                                    boolean testVersion = Boolean.parseBoolean(properties.getProperty("app.test.version"));
                                    if(testVersion){
                                        showAlertDialogTestApp();
                                    }else {
                                        if (isNetworkAvailable()) {
                                            sendPollsProcess(poll.getStructureId());
                                        } else {
                                            launchActiveNetworkDialog();
                                        }
                                    }

                                } else {
                                    showAlerDialog(getString(R.string.app_alert_dialog_title), getString(R.string.polls_empty_info_message));
                                }
                            }
                        });
                        confirmDialog.show();

                        return true;
                    case R.id.menuRecordPoll:
                        if (getPollsCount(poll.getStructureId()) > 0) {
                            startPollRecord(poll);
                        } else {
                            showAlerDialog(getString(R.string.app_alert_dialog_title), getString(R.string.polls_empty_saved_info_message));
                        }
                        return true;
                    case R.id.menuImageResources:startImageResourcesActivity(poll.getStructureId());
                        Log.e("POLL1", poll.getStructureId() + "");
                        return true;
                }
                return false;
            }
        });

        popup.show();
    }

    private void startImageResourcesActivity(long pollid){
        Intent intent = new Intent(this, ResourcesActivity.class);
        intent.putExtra(ResourcesActivity.POLL_ID_EXTRA, pollid);
        startActivity(intent);
    }

    /**
     * Método que que responde al evento click de una vista.
     * @param view vista que lanzó el evento
     */
    @Override
    public void onClick(final View view) {
        switch (view.getId()){
            case R.id.updateButton:
                if(isNetworkAvailable()) {
                    if(CONNECTION_ESTABLISHED) {
                        this.socket.emit(properties.getProperty(GET_POLLS_SOCKET_SERVICE), currentInterviewer.getId(), currentProject.getId());
                    }else{
                        launchConnectionRefusedDialog();
                    }
                }else{
                    launchActiveNetworkDialog();
                }
                break;
            default:view.post(new Runnable() {
                @Override
                public void run() {
                    showPopupMenu(view);
                }
            });
        }
    }

    private void launchPollSavedDialog(){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.Alert(this,R.string.app_alert_dialog_title,R.string.polls_filled_out_info_message);
        d.show();
    }

    /**
     * Método que responde al evento cuando se da click a un elemento de la lista ListView
     * @param adapterView Adaptador que contiene los elementos de la lista.
     * @param view La vista del elemento que ejecutó el evento.
     * @param position La posición del elemento que ejecutó el evento.
     * @param id el identificador unico del elemento que ejecutó el evento.
     * @see AdapterView
     * @see View
     * @see Poll
     * @see #startPoll(Poll)
     */
    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        if(IS_GPS_ENABLED) {
            Poll poll = adapter.items().get(position);
            boolean testVersion = Boolean.parseBoolean(properties.getProperty("app.test.version"));
            if(testVersion){
                startPoll(poll);
            }else {
                if (getPollsSavedCount(poll.getStructureId()) == 0 && getPollsCount(poll.getStructureId()) == 0) {
                    startPoll(poll);
                } else {
                    launchPollSavedDialog();
                }
            }
        }else{
            launchActiveGPSConfirmDialog();
        }
    }

    /**
     * Inicia la actividad de llenado de los datos basicos del encuestado.
     * @param currentPoll Objeto que contiene la encuesta seleccionada
     * @see Intent
     * @see PersonDataBasicActivity
     * @see Poll
     */
    private void startPoll(Poll currentPoll){
        /*
        // lanza la actividad de formulario de encuestado
        Intent intent = new Intent(getApplicationContext(),PersonDataBasicActivity.class);
        intent.putExtra("poll",currentPoll);
        startActivityForResult(intent,SAVED_POLL_REQUEST_CODE);
        */
        Intent intent = new Intent(this,FormActivity.class /*Master.class*/);
        // adjunta información por medio de los extras a la actividad posterior
        intent.putExtra("poll", currentPoll);
        intent.putExtra("userId", currentInterviewer.getId()+"");

        try {
            intent.putExtra(NEW_POLL_EXTRA, true);
            int position = 0;
            String target;
            if((target = getPausedPollFromDB(currentPoll.getStructureId())) == null) {
                position = getPollDataPausedSizeFromDB(currentPoll.getStructureId()) + 1;
                insertPollDataPausedToDB(currentPoll.getStructureId(), position + "." + currentPoll.getStructureId() + ".PERSON" + "." + constants.localFile_name);
            }else{
                position = Integer.parseInt(target.substring(0, target.indexOf(".")));
            }
            intent.putExtra(PollRecordActivity.POSITION_EXTRA, position);
        }catch (SQLiteException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }

        //intent.putExtra("person", this.person);
        //intent.putExtra("photography", this.photography);
        //startActivityForResult(intent, FILL_POLL_REQUEST_CODE);
        startActivityForResult(intent, SAVED_POLL_REQUEST_CODE);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(resultCode == RESULT_OK){
            String pollStructure;
            switch (requestCode){
                case SAVED_POLL_REQUEST_CODE:
                    if((pollStructure = getPollsStructureFromDB())!= null){
                        refresh(pollStructure);
                    }
                    break;
                case SENT_POLL_REQUEST_CODE:
                    if((pollStructure = getPollsStructureFromDB())!= null){
                        refresh(pollStructure);
                    }
                    break;
            }
        }
    }

    /**
     * Método que responde a el evento de refresco del SwipeRefreshLayout y envia el identificador de encuestador hacia el servidor
     * para que este reponda con las estructuras de las encuestas asignadas a dicho encuestador.
     * @see io.socket.client.Socket
     */
    @Override
    public void onRefresh() {
        if(isNetworkAvailable()) {
            if(CONNECTION_ESTABLISHED) {
                this.socket.emit(properties.getProperty(GET_POLLS_SOCKET_SERVICE), currentInterviewer.getId(), currentProject.getId());
            }else{
                this.refreshLayout.post(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(false);
                    }
                });
                launchConnectionRefusedDialog();
                socket.disconnect();
                socket.connect();
            }
        }else{
            this.refreshLayout.post(new Runnable() {
                @Override
                public void run() {
                    refreshLayout.setRefreshing(false);
                }
            });
            launchActiveNetworkDialog();
        }
    }

    /**
     * Médoto que responde a el evento de creción de los elementos de un menú
     * @param menu menu creado
     * @return verdadero si se selecciona un menú, falso de lo contrario.
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.polls_menu, menu);

        menu.getItem(1).setEnabled(getPollsCount(-1) > 0);

        MenuItem searchItem = menu.findItem(R.id.menuSearch);
        SearchView searchView = (SearchView) MenuItemCompat.getActionView(searchItem);
        searchView.setQueryHint(getString(R.string.polls_toolbar_menuSearch_hint));
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                //Toast.makeText(getApplicationContext(), query, Toast.LENGTH_LONG).show();
                searchPoll(query);
                return true;
            }

            @Override
            public boolean onQueryTextChange(String s) {
                /*if (s.length() == 0)
                    //refresh(true);
                    Toast.makeText(getApplicationContext(), "Cerrado", Toast.LENGTH_LONG).show();
                else
                    Toast.makeText(getApplicationContext(), s, Toast.LENGTH_LONG).show();*/
                return true;
            }
        });
        return true;
    }

    /**
     * Inicia la actividad de visualización de estadisticas del las encuestas.
     * @see Intent
     * @see Statistics
     */
    private void startStatistics(){
        Intent intent = new Intent(this,Statistics.class);
        intent.putParcelableArrayListExtra("polls", polls);
        startActivity(intent);
    }

    /**
     * Método que responde al evento de selección de una opción de menú
     * @param item opción seleccionada en el menú.
     * @return verdadero si se selecciona un menu, falso de lo contrario.
     * @see #startStatistics()
     */
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch (id){
            case R.id.menuStatistics:
                if(getPollsCount(-1) > 0) {
                    startStatistics();
                }else{
                    showAlerDialog("Información", "No hay registros de encuestas para mostrar estadísticas");
                }
                break;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     * Método que se ejecuta cuando sucede el evento de detención en el ciclo de vida de la actividad
     */
    @Override
    protected void onStop() {
        super.onStop();
        //this.sqlStructure.close();
        //this.sqlPolldata.close();
    }

    /**
     * Dispatch onStart() to all fragments.  Ensure any created loaders are
     * now started.
     */
    @Override
    protected void onStart() {
        super.onStart();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        this.socket.disconnect();
        this.socket.close();
    }

    private void launchActiveGPSConfirmDialog(){
        AlertDialog d=com.miido.analiizoOBRAS.util.Dialog.confirm(this,
                R.string.app_confirm_dialog_activateGps_title,
                R.string.app_confirm_dialog_activateGps_message);
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {

            @Override
            public void onCancel(DialogInterface dialog) {
                Intent intent=new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                startActivity(intent);
            }
        });
        d.show();
    }

    @Override
    public void onLocationChanged(Location location) {

    }

    @Override
    public void onStatusChanged(String s, int i, Bundle bundle) {

    }

    @Override
    public void onProviderEnabled(String s) {
        IS_GPS_ENABLED = true;
    }

    @Override
    public void onProviderDisabled(String s) {
        launchActiveGPSConfirmDialog();
    }
}
