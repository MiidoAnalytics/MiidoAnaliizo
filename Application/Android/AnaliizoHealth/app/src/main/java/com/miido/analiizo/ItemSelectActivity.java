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
import android.support.v4.view.MenuItemCompat;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.SearchView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.ActionMode;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.util.PropertyReader;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;

import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Properties;

import io.socket.client.Ack;
import io.socket.client.IO;
import io.socket.client.Socket;
import io.socket.emitter.Emitter;

/**
 * Activity que muestra un listado de elementos consultados a la base de datos de servicios
 * @author Miguel Angel Urango Blanco MIIDO S.A.S 28/03/2016
 * @see android.widget.AdapterView.OnItemClickListener
 * @see ActionBarActivity
 * @see android.widget.AbsListView.MultiChoiceModeListener
 * @see android.support.v4.widget.SwipeRefreshLayout.OnRefreshListener
 */
public class ItemSelectActivity extends ActionBarActivity implements AdapterView.OnItemClickListener,AbsListView.MultiChoiceModeListener,SwipeRefreshLayout.OnRefreshListener{

    private SqlHelper sqlHelper;
    private Constants constants = new Constants();
    private String tableName;
    private String selectedItem;
    private ArrayList<String> items = new ArrayList<>();
    private Properties properties;

    private SwipeRefreshLayout refreshLayout;
    private ListView listview;
    private Toolbar toolbar;
    private ArrayAdapter<String> adapter;

    private ConnectivityManager connectivityManager;

    private Socket socket;

    public static final String GET_ITEMS_SOCKET_SERVICE = "item.select.socket.service";

    public static final String TITLE_EXTRA = "title_extra";
    public static final String SERVICE_EXTRA = "service_extra";
    public static final String RESULT_EXTRA = "result_extra";
    public static final String SELECTED_ITEM_EXTRA = "item_extra";

    private boolean CONNECTION_ESTABLISHED = true;

    private final int THRESHOLD = 3;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.item_select_layout_new);

        // Inicialización del ayudante para consultas y creación de tablas en la base de datos local
        sqlHelper = new SqlHelper(this);
        sqlHelper.databaseName = constants.itemsDatabase;
        sqlHelper.OOCDB();

        properties = new PropertyReader(this).getMyProperties("application.properties");

        // Obtención de los extras de la actividad lanzadora
        this.tableName = getIntent().getStringExtra(SERVICE_EXTRA);
        this.selectedItem = getIntent().getStringExtra(SELECTED_ITEM_EXTRA);

        // Inicialización del manejador de conectividad
        connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

        // Inicialización de la barra ade acciones.
        toolbar = (Toolbar) findViewById(R.id.item_tool_bar);
        toolbar.setTitle("Seleccione");
        toolbar.setSubtitle(getIntent().getStringExtra(TITLE_EXTRA));
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        toolbar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                setResult(RESULT_CANCELED);
                finish();
            }
        });

        // Inicialización del SwipeRefreshLayout.
        this.refreshLayout =(SwipeRefreshLayout) findViewById(R.id.swipe_refresh_layout);
        //this.refreshLayout.setColorSchemeColors(Color.parseColor("#256027"), Color.parseColor("#549a50"), Color.parseColor("#5dae58"));
        this.refreshLayout.setColorSchemeColors(
                getResources().getColor(R.color.ColorRefresh),
                getResources().getColor(R.color.ColorPrimaryDark),
                getResources().getColor(R.color.ColorPrimary));
        this.refreshLayout.setOnRefreshListener(this);

        // Inicialización del el ListView
        listview = (ListView) findViewById(R.id.listViewItems);
        listview.setAdapter(adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, items));
        listview.setChoiceMode(ListView.CHOICE_MODE_MULTIPLE);
        listview.setOnItemClickListener(this);

        // Inicialización del socket.

        if(this.socket == null){
            try {
                String property = (properties.getProperty("app.local.test","false").toUpperCase().equals("TRUE")
                        ? properties.getProperty("socket.local.host") : properties.getProperty("socket.remote.host"));
                socket = IO.socket(property);
                // Evento que responde a la conexión del socket
                socket.on(Socket.EVENT_CONNECT, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.i("SOCKET", "Conexión satisfactoria");
                        CONNECTION_ESTABLISHED = true;
                    }
                });
                // Evento que responde a problemas de conexión con el socket
                socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.e("SOCKET", "Conexión fallida");
                        CONNECTION_ESTABLISHED = false;
                    }
                });
                socket.connect();
            } catch (URISyntaxException e) {
                e.printStackTrace();
            }
        }

        /*if(isNetworkAvailable()){
            // Obtiene los elementos desde el servidor
            getItemsFromServer();
        }else {*/
            // Obtine los elementos desde la base de datos local.
            refreshProcess(null);
        //}
    }

    /**
     * Determina si existe conectividad en el dispositivo.
     * @return true si existe conectividad, false de lo contrario.
     */
    private boolean isNetworkAvailable(){
        return connectivityManager != null &&
                connectivityManager.getActiveNetworkInfo() != null &&
                connectivityManager.getActiveNetworkInfo().isConnected();
    }

    /**
     * Muestra un dialogo que informa el usuario una situación de desconexión y abre la actividad de configuración de conexión.
     */
    private void lauchActiveNetworkDialog(){
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

    /**
     * Inicializa el adaptador antesde de hacerce el refresco del listado para prevenir duplicados en la lista
     */
    private void initAdapter(){
        adapter.clear();
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                adapter.notifyDataSetChanged();
                refreshLayout.setRefreshing(true);
            }
        });
    }

    /**
     * Busca un un elemento entre la lista de items
     * @param key palabra clave a buscar
     * @return la posición del objeto si lo encuetra, retorna -1 si el item no fué encontrado
     */
    private int searchItem(String key){
        if(key == null || key.equals("")){
            return -1;
        }

        for(int i = 0; i < items.size(); i++){
            if(items.get(i).equals(key)){
                return i;
            }
        }
        return -1;
    }

    /**
     * Busca un item en la base de datos local
     * @param id identificador unico del item
     * @return el nombre del intem encontrado, null si no enecuentra el item
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta.
     * @see SqlHelper
     */
    private String getItemFromDB(int id) throws SQLiteException{
        String query = constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS
                .replace("[FIELDS]", "*")
                .replace("[TABLE]", this.tableName)
                .replace("[CONDITIONS]", "iId = " + id);
        sqlHelper.setQuery(query);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        if(cursor.getCount() > 0){
            return cursor.getString(1);
        }
        return null;
    }

    /**
     * Inserta un nuevo item en la base de datos local
     * @param id identificador unico del item
     * @param itemName nombre del item
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta.
     * @see SqlHelper
     */
    private void insertItemToDB(int id, String itemName) throws SQLiteException{
        String query = String.format(constants.INSERT_ITEM_QUERY, this.tableName, id, itemName);
        sqlHelper.setQuery(query);
        sqlHelper.execInsert();
    }

    /**
     * Modifica un item en la base de datos local
     * @param id identificador unico del item
     * @param itemName nombre del item a modificar
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta.
     * @see SqlHelper
     */
    private void updateItemFromDB(int id, String itemName) throws SQLiteException {
        String query = String.format(constants.UPDATE_ITEM_QUERY, this.tableName, itemName, id);
        sqlHelper.setQuery(query);
        sqlHelper.execUpdate();
    }

    /**
     * Obtiene una lista de elementos desde el servidor en formato JSON.
     * Si algunos registros no se encuentran en la base de datos son modificados.
     * Si algunos registros han sido actualizados de parte del servidor son actualizados tambien en la base de datos local.
     * @see #insertItemToDB(int, String)
     * @see #updateItemFromDB(int, String)
     * @see io.socket.client.Socket
     * @see org.json.JSONObject
     * @see org.json.JSONArray
     */
    private void getItemsFromServer(){
        socket.emit(properties.getProperty(GET_ITEMS_SOCKET_SERVICE), this.tableName, new Ack() {
            @Override
            public void call(Object... args) {
                try {
                    //Log.i("SERVICE", args[0].toString());
                    JSONArray service = new JSONArray(args[0].toString());
                    if (service.length() > 0) {

                        Iterator<String> iteratorKeys = service.getJSONObject(0).keys();
                        ArrayList<String> keys = new ArrayList<>();
                        while (iteratorKeys.hasNext()) {
                            String key = iteratorKeys.next();
                            keys.add(key);
                        }
                        for (int i = 0; i < service.length(); i++) {
                            try {
                                int id = service.getJSONObject(i).getInt(keys.get(0));//primer elemento del JSONObject
                                String name = service.getJSONObject(i).getString(keys.get(1));
                                if (getItemFromDB(id) != null) {
                                    if (getItemFromDB(id).equals(name)) {
                                        Log.i("EXIST", name);
                                    } else {
                                        Log.i("UPDATE", name);
                                        updateItemFromDB(id, name);
                                    }
                                } else {
                                    Log.i("INSERT", name);
                                    insertItemToDB(id, name);
                                }
                            } catch (SQLiteException ex) {
                                Log.e("SQLITE", ex.getMessage());
                            }
                        }
                        refreshProcess(null);
                    }
                } catch (JSONException ex) {
                    Log.e("JSONEXCEPTION", ex.getMessage());
                }
            }
        });
    }

    /**
     * Inicia el proceso de refresco de la lista
     * @param key palabra a buscar para desplegar resultados en la lista, si se le pasa null muestra todos los elementos.
     */
    public void refreshProcess(String key){
        /**
         * clase encargada de ejecutar la el proceso de refresco de los elementos de la lista en segundo plano.
         * @see AsyncTask
         */
        class RefreshProcess extends AsyncTask<String,String,Integer>{

            /**
             * Se ejecuta antes de iniciar el proceso en segundo plano.
             */
            @Override
            protected  void onPreExecute(){
                initAdapter();
            }

            /**
             * Método que se ejecuta en segundo plano la cual obtiene los elementos de la base de datos para posteriormente mostrarlo en el ListView
             * @param args argumentos que recibe del exterior, en este caso la palabra clave a buscar
             * @return un entero con el resultado que generó el proceso. 0 si la obtención y mostrado de la información fué exitosa.
             */
            @Override
            protected Integer doInBackground(String... args) {
                try {
                    getItemsFromDB(args[0]);
                }catch (SQLiteException ex){
                    Log.e(getClass().getName(),ex.getMessage());
                    return -1;
                }
                try {
                    Cursor cursor = sqlHelper.getCursor();
                    for (int i = 0; i < cursor.getCount(); i++) {
                        if(tableName.equals("ciuo") || tableName.equals("eps")){
                            publishProgress(cursor.getString(2));
                        }else {
                            if(tableName.equals("diseases")){
                                publishProgress(cursor.getString(1) + " - " + cursor.getString(2));
                            }else {
                                if(tableName.equals("departaments") || tableName.equals("cities")){
                                    publishProgress(cursor.getString(0) + " - " + cursor.getString(1));
                                }else {
                                    publishProgress(cursor.getString(1));
                                }
                            }
                        }
                        cursor.moveToNext();
                    }
                    cursor.close();
                }catch (Exception ex){
                    Log.e(getClass().getName(),ex.getMessage());
                    return -2;
                }
                return 0;
            }

            /**
             * Método que se encarga de actualizar los componentes de la interfaz gráfica.
             * @param update parametro de actualización, 0 si es necesario actualizar el componente gráfico.
             */
            @Override
            protected void onProgressUpdate(String... update){
                adapter.add(update[0]);
            }

            /**
             * Método que se ejecuta una vez termindo el proceso en segundo plano, comunmente utilizado para mostrar mensajes de exito.
             * @param result parametro que representa el resultado lanzado por el proceso en segundo plano (valor que retorna el método doInBackground).
             */
            @Override
            protected void onPostExecute(Integer result){
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        adapter.notifyDataSetChanged();
                        refreshLayout.setRefreshing(false);
                    }
                });
            }
        }
        new RefreshProcess().execute(key);
    }

    /**
     * Busca en la base de datos un elemento con mediante una palabra clave
     * @param key palabra clave a buscar en la pase de datos
     * @throws SQLiteException
     * @see SqlHelper
     */
    public void getItemsFromDB(String key) throws SQLiteException{
        String query = constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS.replace("[FIELDS]", "DISTINCT *").replace("[TABLE]", tableName);
        if(key == null || key.equals("")){
            if(tableName.equals("cities")){
                query = query.replace("[CONDITIONS]", "fk_iDepartamentId = "+ selectedItem);
            }else {
                query = query.replace("[CONDITIONS]", "1 = 1");
            }
        }else {
            if(tableName.equals("diseases")){
                query = query.replace("[CONDITIONS]", " vchDiseaseKey LIKE '"+key+"%' OR vchDiseaseDescription LIKE '" + key + "%' OR " +
                        "vchDiseaseDescription LIKE '%" + key + "%'");
            }else {
                if(tableName.equals("cities")){
                    query = query.replace("[CONDITIONS]", "fk_iDepartamentId = "+ selectedItem + " AND " +
                            "vchDescription LIKE '" + key + "%' OR vchDescription LIKE '%" + key + "%'");
                }else {
                    query = query.replace("[CONDITIONS]", "vchDescription LIKE '" + key + "%' OR vchDescription LIKE '%" + key + "%'");
                }
            }
        }
        sqlHelper.setQuery(query);
        sqlHelper.execQuery();
    }

    /**
     * configura la respuesta para la actividad lanzadora
     * @param result resultado de la respuesta
     */
    private void setResponse(String result){
        Intent intent = new Intent();
        intent.putExtra(RESULT_EXTRA, result);
        setResult(RESULT_OK, intent);
        finish();
    }

    private void startSubItemSelectActivity(int position){
        Intent intent = new Intent(getApplicationContext(), ItemSelectActivity.class);
        intent.putExtra(ItemSelectActivity.TITLE_EXTRA, "Municipios");
        intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, "cities");
        intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, adapter.getItem(position).split(" - ")[0]);
        startActivityForResult(intent, FormActivity.ITEM_SELECT_REQUEST_CODE);
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        if(tableName.equals("departaments")){
            startSubItemSelectActivity(position);
        }else {
            setResponse(adapter.getItem(position));
        }
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
            if(requestCode == FormActivity.ITEM_SELECT_REQUEST_CODE){
                setResponse(data.getStringExtra(RESULT_EXTRA));
            }
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.item_selected_menu_new,menu);
        MenuItem searchItem = menu.findItem(R.id.menuSearch);
        SearchView searchView = (SearchView) MenuItemCompat.getActionView(searchItem);
        searchView.setQueryHint("Busqueda");

        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String key) {
                if (key.equals("")) {
                    Log.i("SUBMIT", "VACIO");
                } else {
                    refreshProcess(key);
                }
                return false;
            }

            @Override
            public boolean onQueryTextChange(String key) {
                if (key.equals("")) {
                    Log.i("CHANGE", "VACIO");
                    refreshProcess(null);
                } else {
                    if (key.length() >= THRESHOLD) {
                        refreshProcess(key);
                    } else {
                        refreshProcess(null);
                    }
                }
                return false;
            }
        });

        return true;
    }

    @Override
    public void onRefresh() {
        /*if(isNetworkAvailable()) {
            if(CONNECTION_ESTABLISHED) {
                getItemsFromServer();
            }else{
                socket.disconnect();
                socket.disconnect();
            }
        }else{*/
            refreshProcess(null);
        //}
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onItemCheckedStateChanged(ActionMode actionMode, int i, long l, boolean b) {

    }

    @Override
    public boolean onCreateActionMode(ActionMode actionMode, Menu menu) {
        return false;
    }

    @Override
    public boolean onPrepareActionMode(ActionMode actionMode, Menu menu) {
        return false;
    }

    @Override
    public boolean onActionItemClicked(ActionMode actionMode, MenuItem menuItem) {
        return false;
    }

    @Override
    public void onDestroyActionMode(ActionMode actionMode) {

    }
}
