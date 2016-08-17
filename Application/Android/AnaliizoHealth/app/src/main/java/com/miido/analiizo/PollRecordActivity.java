package com.miido.analiizo;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.net.ConnectivityManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.PopupMenu;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.Poll;
import com.miido.analiizo.util.PropertyReader;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.URISyntaxException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Properties;

import io.socket.client.Ack;
import io.socket.client.IO;
import io.socket.client.Socket;
import io.socket.emitter.Emitter;

/**
 * Activity que muestra el historial de la encuesta en una lista.
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S 19/01/2016
 * @version 1.0
 * @see ActionBarActivity
 * @see android.view.View.OnClickListener
 * @see android.widget.AdapterView.OnItemClickListener
 * @see android.support.v4.widget.SwipeRefreshLayout.OnRefreshListener
 */

public class PollRecordActivity extends ActionBarActivity implements View.OnClickListener,AdapterView.OnItemClickListener,SwipeRefreshLayout.OnRefreshListener{

    private Toolbar toolbar;
    private SwipeRefreshLayout refreshLayout;
    private ListView listView;
    private PollAdapter pollAdapter;

    private ArrayList<Poll> pollRecords = new ArrayList<>();
    private SqlHelper sqlHelper;
    private Properties properties;

    private Poll currentPoll;
    private Socket socket;
    private boolean IS_POLL_SEND = false;
    private boolean CONNECTION_ESTABLISHED = true;

    private final int SAVED_POLL_REQUEST_CODE = 1000;
    private final int POLL_INFO_REQUEST_CODE = 1001;

    public static final String POSITION_EXTRA = "POSITION";
    public static final String WAS_SENT = "WAS_SENT";

    private ConnectivityManager connectivityManager;

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.record_layout_new);

        this.currentPoll = getIntent().getParcelableExtra("poll");

        this.toolbar = (Toolbar) findViewById(R.id.record_tool_bar);
        this.toolbar.setTitle(this.currentPoll.getTitle());
        this.toolbar.setSubtitle("Historial del informe");
        setSupportActionBar(this.toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        toolbar.setNavigationOnClickListener(this);

        this.listView = (ListView) findViewById(R.id.recordList);
        refreshLayout = (SwipeRefreshLayout) findViewById(R.id.record_swipe_refresh_layout);
        this.refreshLayout.setColorSchemeColors(
                getResources().getColor(R.color.ColorRefresh),
                getResources().getColor(R.color.ColorPrimaryDark),
                getResources().getColor(R.color.ColorPrimary));
        this.refreshLayout.setOnRefreshListener(this);


        this.listView.setOnItemClickListener(this);

        this.sqlHelper = new SqlHelper(this);
        this.properties = new PropertyReader(this).getMyProperties("application.properties");

        getPollRecordFromDBProcess(this.currentPoll.getStructureId());

        connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

        if(this.socket == null){
            try {
                String property = (properties.getProperty("app.local.test","false").toUpperCase().equals("TRUE")
                        ? properties.getProperty("socket.local.host") : properties.getProperty("socket.remote.host"));
                this.socket = IO.socket(property);
                this.socket.on(Socket.EVENT_CONNECT, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        CONNECTION_ESTABLISHED = true;
                        Log.i(getPackageName(), "Socket: Conexión satisfactoria");
                    }
                });
                this.socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        CONNECTION_ESTABLISHED = false;
                        Log.e("Error","Error de conexión");
                    }
                });
                this.socket.connect();
            } catch (URISyntaxException e) {
                e.printStackTrace();
            }
        }

    }

    /**
     * Determina si existe una conexión a internet
     * @return true si existe conexión, false en caso contrario.
     */
    private boolean isNetworkAvailable(){
        return connectivityManager != null &&
                connectivityManager.getActiveNetworkInfo() != null &&
                connectivityManager.getActiveNetworkInfo().isConnected();
    }

    /**
     * Muestra un cuadro de dialogo de confirmación para informar que no hay conexión a internet.
     * y poder ingresar a las preferencias del dispositivo.
     */
    private void lauchActiveNetworkDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.confirm(this, "Verificar Conexión",
                "Esta operación requiere una conexión a internet.\n"+
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

    /**
     * Muestra un dialogo de alerta informando que no se puede establecer conexión con el servidor.
     */
    private void launchConnectionRefusedDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.Alert(this, "Error de conexión",
                "No se ha podido establecer conexión con el servidor");
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                dialogInterface.dismiss();
            }
        });
        dialog.show();
    }

    /**
     * Consulta información de las encuestas diligenciadas en la base de datos local del dispositivo
     * @param structureId identificador de la estructura de la encuesta a consultar
     * @return Un arreglo dinamico de tipo Poll que contiene las encuestas consultadas
     * @throws SQLiteException Es lanzada si alguna incosistencia pasa al momento de ejecutar la consulta
     * @see Poll
     * @see ArrayList
     * @see SqlHelper
     */
    private ArrayList<Poll> getPollRecordsFromDB(long structureId)throws SQLiteException{
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;//"POLLDATA_DB";
        sqlHelper.OOCDB();
        //sqlHelper.setQuery(String.format("SELECT id,idstructure,polldata,savedate,senddate FROM poll where idstructure = %s", structureid));
        sqlHelper.setQuery(String.format(constants.SELECT_HOME_INFO_QUERY, structureId));
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        ArrayList<Poll> polls = new ArrayList<>();
        for(int i = 0; i < cursor.getCount(); i++){
            Poll poll = new Poll();
            poll.setId(cursor.getInt(0));
            poll.setStructureId(cursor.getLong(1));
            poll.setContent(cursor.getString(3));// AUXILIAR OR ORIGINAL
            poll.setStatus(cursor.getInt(4));
            poll.setSavedDate(cursor.getString(5));
            poll.setSentDate(cursor.getString(6));

            poll.setTitle((poll.getStatus() == 0 ? "Pausada" : poll.getStatus() == 1 ? "Enviada "+poll.getSentDate() : "Guardada "+poll.getSavedDate()));
            poll.setProjectName((poll.getSentDate() == null ? "Fecha :"+poll.getSavedDate() : "Pausada: "+poll.getSavedDate()));
            polls.add(poll);
            cursor.moveToNext();
        }
        return polls;
    }

    /**
     * Busca encuestas que aún no han sido enviadas.
     * @param pollId identificador de la encuesta cuyos registros no enviados se desea buscar
     * @return el reegistro que aún no ha sido enviado, null si no encuentra ningun registro.
     */
    private Poll findNoSentPoll(int pollId){
        ArrayList<Poll> polls = getPollRecordsFromDB(this.currentPoll.getStructureId());
        for (Poll poll : polls){
            if(poll.getId() == pollId && poll.getStatus() == 0 /*poll.getSentDate() == null*/){
                return poll;
            }
        }
        return null;
    }

    /**
     * Actualiza un registro en la base de datos local del dispositivo.
     * @param pollId identificador de la encuesta a actualizaar
     * @throws SQLiteException es lanzada si ocurre algún error al ejecutar la consulta.
     */
    private void updatePoll(int pollId) throws SQLiteException{
        Constants constants = new Constants();
        //this.sqlHelper.databaseName = "POLLDATA_DB";
        this.sqlHelper.databaseName = constants.responseDatabase;
        this.sqlHelper.OOCDB();
        String sendDate = new SimpleDateFormat(new Constants().SimpleDateFormat).format(new Date());
        //this.sqlHelper.setQuery(String.format("UPDATE poll SET senddate = '%s' WHERE id = %s;", sendDate, pollId));
        this.sqlHelper.setQuery(String.format(constants.UPDATE_HOME_RESPONSE_QUERY, sendDate, pollId));
        this.sqlHelper.execUpdate();
    }

    private String getFamilyGroupContentFromDB(int id)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(constants.CREATE_HOME_RESPONSE_QUERY);
        sql.execQuery();
        sql.setQuery(String.format(constants.SELECT_HOME_INFO_BY_ID_QUERY, id, 0));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        String content = null;
        if(cursor.getCount() > 0){
            content = cursor.getString(3);

        }
        cursor.close();
        return content;
    }

    private ArrayList<String> getPersonsContentFromDB(int homeId)throws SQLiteException{
        ArrayList<String> persons = new ArrayList<>();
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(constants.CREATE_PERSON_RESPONSE_QUERY);
        sql.execQuery();
        sql.setQuery(String.format(constants.SELECT_PERSONS_INFO_QUERY, homeId));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        for(int i = 0; i < cursor.getCount(); i++){
            String content = cursor.getString(3);
            persons.add(content);
            cursor.moveToNext();
        }
        return persons;
    }

    private String getHomeContentFromBD(int homeId) throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(constants.CREATE_SATISFACTION_RESPONSE_QUERY);
        sql.execQuery();
        sql.setQuery(String.format(constants.SELECT_SATISFACTION_RESPONSE_QUERY, homeId));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        String content = null;
        if(cursor.getCount() > 0){
            content = cursor.getString(3);

        }
        cursor.close();
        return content;
    }

    private JSONObject getResponse(String familyContent, ArrayList<String> personsContent, String homeContent)throws JSONException{
        JSONObject response = new JSONObject();
        response.put("GROUP", new JSONObject(familyContent));
        response.put("HOUSE", new JSONObject(homeContent));
        JSONArray persons = new JSONArray();
        for(String person : personsContent){
            persons.put(new JSONObject(person));
        }
        response.put("PERSONS", persons);
        return response;
    }

    /**
     * Envia los informes diligenciados a el servidor.
     * @param pollsIds identificadores de los reportes o encuestas
     */
    private void sendPollProcess(Integer... pollsIds){
        /**
         * Clase que envia los resportes al servidor en segundo plano.
         * @see AsyncTask
         */
        class SendPollProcess extends AsyncTask<Integer,String,Integer>{
            /**
             * Médoto que es ejecutado antes de iniciar el proceso en segundo plano
             */
            @Override
            protected void onPreExecute() {
                super.onPreExecute();
            }

            /**
             * se ejecuta una vez terminado el proceso en segundo plano de subida de reportes al servidor.
             * @param result resultado del método doInBackgroud(Object[])
             */
            @Override
            protected void onPostExecute(Integer result) {
                if(result == 0){
                    getPollRecordFromDBProcess(currentPoll.getStructureId());
                    IS_POLL_SEND = true;
                    Toast.makeText(getApplicationContext(), "Encuesta sincronizada con exito", Toast.LENGTH_LONG).show();
                }else{
                    if(result == -2){
                        Toast.makeText(getApplicationContext(), "Aún no ha llenado la encuesta de vivienda y satisfacción de Eps", Toast.LENGTH_LONG).show();
                    }else {
                        Toast.makeText(getApplicationContext(), "No se pudo sincronizar la encuesta debido a un error", Toast.LENGTH_LONG).show();
                    }
                }
            }

            /**
             * Método que es ejecutado en el hilo principal de la aplicación,
             * comunmente utilizado para reflejar el proceso actual de las tareas en segundo plano para ser mostrado por un componente de la interface gráfica.
             * este método es ejecutado cuando se hace llamada a el método publishProgress(progress)
             * @param values progreso de la tarea en segundo plano
             */
            @Override
            protected void onProgressUpdate(String... values) {
                getPollRecordFromDBProcess(currentPoll.getStructureId());
            }

            /**
             * Ejecuta tareas en segundo plano, en este caso la subida de reportes al servidor.
             * @param ids identificadores de los reportes
             * @return 0 si la subidad de reportes se ejecuta con exito, -1 si ocurre un error al parsear el JSON de los resultados del reporte
             * o si ocurre un error al obtener los reportes desde la base de datos local.
             */
            @Override
            protected Integer doInBackground(Integer... ids) {
                for(Integer id : ids){
                    final Poll poll = findNoSentPoll(id);
                    try {
                        String homeContent = getHomeContentFromBD(id);
                        if(homeContent != null) {
                            //JSONObject response = getResponse(poll.getContent(), getPersonsContentFromDB(id), getHomeContentFromBD(id));
                            socket.emit(properties.getProperty(PollsActivity.ENQUEUE_POLLS_SOCKET_SERVICE),
                                    poll.getContent(), new Ack() {
                                        @Override
                                        public void call(Object... args) {
                                            if (args[0].equals("OK")) {
                                                updatePoll(poll.getId());
                                                publishProgress();
                                            }
                                        }
                                    });
                        }else{
                            return -2;
                        }
                    }/*catch (JSONException ex){
                        Log.e("JSON_ERROR", ex.getMessage());
                        return -1;
                    }*/
                    catch (SQLiteException ex){
                        Log.e("SQLITE_ERROR", ex.getMessage());
                        return -1;
                    }
                }
                return 0;
            }
        }
        // Ejcución de la tarea en segndo plano.
        new SendPollProcess().execute(pollsIds);
    }

    /**
     * Inicializa el adaptador de la lista para que no se repitan los datos desplegados en ella al momento de refrescarlos
     * @see PollAdapter
     */
    private void initAdapter(){
        this.listView.setAdapter(pollAdapter = new PollAdapter(this, pollRecords = new ArrayList<Poll>(), this));
    }

    /**
     * Utiliza un objeto AsyncTask para ejecutar el proceso de obtencion del historial de la encuesta en segundo plano.
     * @param structureid identificador de la estrucutura de la encuesta.
     * @see AsyncTask
     */
    private void getPollRecordFromDBProcess(long structureid){
        /**
         * Clase encargada obtener el historial de la encuesta en segundo plano
         * @see AsyncTask
         */
        class getPollRecordProcess extends AsyncTask<Long,Boolean,Integer>{

            /**
             * Método que se ejecuta antes de iniciar las tareas en segundo plano.
             */
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

            /**
             * Métdod que se ejecuta las tareas en segundo plano
             * @param Args identificador de la estructura de la encuesta.
             * @return dato numerico que representa el estado en que termina las tareas, -1 si ocurre una excepción SQLite,
             * null si las actividades se ejecutan sin ningún error.
             */
            @Override
            protected Integer doInBackground(Long... Args) {
                try {
                    ArrayList<Poll> polls = getPollRecordsFromDB(Args[0]);
                    for(Poll poll : polls){
                        pollAdapter.items().add(poll);
                        publishProgress(true);
                    }

                }catch (SQLiteException ex){
                    return -1;
                }
                return null;
            }

            /**
             * Métdodo que es llamado por publishProgress, utilizado para actualizar elementos de UI.
             * @param values valores de actualización
             * @see #publishProgress(Object[])
             */
            @Override
            protected void onProgressUpdate(Boolean... values) {
                pollAdapter.notifyDataSetChanged();
            }

            /**
             * Método que es llamado una vez se ha terminado de ejecutar as tareas en segundo plano.
             * @param result resultado del metodo doInBackground(Object[]).
             */
            @Override
            protected void onPostExecute(Integer result){
                refreshLayout.post(new Runnable() {
                    @Override
                    public void run() {
                        refreshLayout.setRefreshing(false);
                    }
                });
            }

        }
        // Ejecución del proceso en segundo plano
        new getPollRecordProcess().execute(structureid);
    }

    /**
     * Busca la ubicación en la lista de un informe o encuesta
     * @param poll la encuesta a buscar
     * @return la posición de la encuesta, -1 si no se encontró la enecuesta.
     */
    private int getPollPosition(Poll poll){
        for( int i = 0; i < pollRecords.size(); i++){
            if(poll.equals(pollRecords.get(i))){
                return i;
            }
        }
        return -1;
    }

    private void showAlertDialogTestApp(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,"Información", "Esta acción no se puede ejecutar. versión de prueba");
        d.show();
    }

    /**
     * Muestra un PopupMenu con opciones del reporte
     * @param view referencia  a la vista que despliega el PopupMenu.
     */
    private void showPopupMenu(View view) {
        final Poll poll = (Poll) view.getTag();

        PopupMenu popup = new PopupMenu(this, view);
        popup.getMenuInflater().inflate(R.menu.polls_popup_menu, popup.getMenu());

        popup.getMenu().getItem(0).setVisible(false);
        popup.getMenu().getItem(1).setEnabled(poll.getStatus() == 2);
        popup.getMenu().getItem(2).setVisible(false);
        popup.getMenu().getItem(3).setVisible(false);

        popup.setOnMenuItemClickListener(new PopupMenu.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem menuItem) {
                switch (menuItem.getItemId()) {
                    case R.id.menuSendPoll:
                        if(isNetworkAvailable()) {
                            if(CONNECTION_ESTABLISHED) {
                                sendPollProcess(poll.getId());
                            }else{
                                launchConnectionRefusedDialog();
                            }
                        }else{
                            lauchActiveNetworkDialog();
                        }
                        //showAlertDialogTestApp();
                        return true;
                    case R.id.menuViewPoll:
                        startPoll(getPollPosition(poll) + 1);
                        return true;
                    case R.id.menuImageResources:
                        startImageResourcesActivity(poll.getStructureId());
                        return true;
                }
                return false;
            }
        });

        popup.show();
    }

    /**
     * Inicia la actividad de agregación de registros fotográficos.
     * @param pollid identificador de la encuesta o reporte.
     */
    private void startImageResourcesActivity(long pollid){
        Intent intent = new Intent(this, ResourcesActivity.class);
        intent.putExtra(ResourcesActivity.POLL_ID_EXTRA, pollid);
        startActivity(intent);
    }

    /**
     * Este método es un evento que se ejecuta cada vez que se le da click a una vista View.
     * @param view vista que ejecutó el evento.
     * @see View
     */
    @Override
    public void onClick(final View view) {
        if(view.getContentDescription().toString().equals("Navigate up")) {
            onBackPressed();
        }else{
            view.post(new Runnable() {
                    @Override
                    public void run() {
                        showPopupMenu(view);
                    }
                });
        }
    }

    /**
     * Dialogo de alerta que informa que el reporte ya ha sido diligenciado.
     * @param position posición del informe seleccionado.
     */
    private void launchAlertDialogSentPoll(final int position){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this, "Información",
                "Este reporte ya ha sido enviado, cualquier cambio realizado no será reflejado.");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                startPoll(position);
            }
        });
        d.show();
    }

    /**
     * Incia la actividad de visualización de la encuesta.
     * @param position posición del la encuesta o reporte seleccionado.
     */
    private void startPoll(int position){
        /*
        // lanza la actividad de formulario de encuestado
        Intent intent = new Intent(getApplicationContext(),PersonDataBasicActivity.class);
        intent.putExtra("poll",currentPoll);
        startActivityForResult(intent,SAVED_POLL_REQUEST_CODE);
        */
        Poll poll = pollAdapter.getItem(position - 1);
        Intent intent = new Intent(this, Master.class);
        // adjunta información por medio de los extras a la actividad posterior
        intent.putExtra("poll", currentPoll);
        intent.putExtra("userId", currentPoll.getInterviewerId());
        if(poll.getSentDate() != null && !poll.getSentDate().equals("")){
            intent.putExtra(WAS_SENT, true);
        }
        intent.putExtra(POSITION_EXTRA, position);
        //intent.putExtra("person", this.person);
        //intent.putExtra("photography", this.photography);
        //startActivityForResult(intent, FILL_POLL_REQUEST_CODE);
        startActivityForResult(intent, SAVED_POLL_REQUEST_CODE);
    }

    /**
     * Este metodo es un evento que se ejecuta cada vez que es un elemento de la vista es clickeado.
     * @param adapterView Adaptador que contiene los elementos de la lista.
     * @param view La vista del elemento que ejecutó el evento.
     * @param position La posición del elemento que ejecutó el evento.
     * @param id el identificador unico del elemento que ejecutó el evento.
     * @see AdapterView
     * @see View
     */
    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        /*Poll poll = pollAdapter.getItem(position);
        if(poll.getSentDate() != null && !poll.getSentDate().equals("")){
            launchAlertDialogSentPoll(position + 1);
        }else {
            startPoll(position + 1);
        }*/
        startPollInfo(pollAdapter.getItem(position));
    }

    private void startPollInfo(Poll poll){
        Intent intent = new Intent(this, PollInfoActivity.class);
        intent.putExtra(PollInfoActivity.CURRENT_POLL_EXTRA, poll);
        intent.putExtra(PollInfoActivity.HOME_ID_EXTRA, poll.getId());
        intent.putExtra(PollInfoActivity.POLL_ID_EXTRA, poll.getStructureId());
        startActivityForResult(intent, POLL_INFO_REQUEST_CODE);
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
            if(requestCode == SAVED_POLL_REQUEST_CODE || requestCode == POLL_INFO_REQUEST_CODE){
                getPollRecordFromDBProcess(currentPoll.getStructureId());
            }
        }
    }

    /**
     * Este método es un eveto que se ejecuta cada vez que se hace un refresco a el SwipeRefreshLayout.
     * Es decir que se hace escroll aún cuando la lista ha llegado al final.
     * @see SwipeRefreshLayout
     * @see #getPollRecordFromDBProcess(long)
     */
    @Override
    public void onRefresh() {
        getPollRecordFromDBProcess(this.currentPoll.getStructureId());
    }

    /**
     * Responde al evento click del boton de navegación hacia atras.
     */
    @Override
    public void onBackPressed() {
        if(IS_POLL_SEND){
            setResult(RESULT_OK);
        }else{
            setResult(RESULT_CANCELED);
        }
        finish();
    }
}
