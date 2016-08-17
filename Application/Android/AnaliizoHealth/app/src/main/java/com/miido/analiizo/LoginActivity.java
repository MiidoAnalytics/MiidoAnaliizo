package com.miido.analiizo;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.ConnectivityManager;
import android.net.wifi.WifiManager;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.StrictMode;
import android.provider.Settings;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.Interviewer;
import com.miido.analiizo.util.Encryptor;
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
import java.util.Properties;

import io.socket.client.IO;
import io.socket.client.Socket;
import io.socket.emitter.Emitter;

/**
 * Activity que muestra el formulario de acceso al sistema para encuestadores.
 * @author Ing. Miguel Angel Urango Blanco 11/01/2016
 * @version 1.0
 * @see android.view.View.OnClickListener
 */

public class LoginActivity extends ActionBarActivity implements View.OnClickListener {

    private EditText editTexUser;
    private EditText editTextPass;
    private Button buttonLogin;

    private SqlHelper sqlHelper;
    private Constants constants;
    private Properties properties;
    private Interviewer authenticatedUser;

    private ProgressDialog progressDialog;

    private io.socket.client.Socket socket;
    private final String UPDATE_USERS_SERVICE = "login.update.socket.service";
    private final String LOGIN_SOCKET_SERVICE = "login.authenticate.socket.service";

    private final String LOGIN_SOCKET_SERVICE_KEY = "requestInterviewers";
    public static final String SOCKET_SERVER_HOST = "http://52.27.125.67:3000";
    //public static final String SOCKET_SERVER_HOST = "http://192.168.0.2:3000";// local.

    public static final String INTERVIEWER_EXTRA = "interviewer";

    private boolean CONNECTION_ESTABLISHED = true;

    private ConnectivityManager connectivityManager;

    int i = 0;

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        constants = new Constants();
        properties = new PropertyReader(this).getMyProperties("application.properties");

        editTexUser = (EditText) findViewById(R.id.userEditText);
        editTextPass = (EditText) findViewById(R.id.passwordEditText);

        buttonLogin = (Button) findViewById(R.id.loginButton);
        buttonLogin.setOnClickListener(this);

        configureSocket();

        connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
    }

    /**
     * configura el socket para la comunicación con el servidor.
     */
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
                        Log.i(getClass().getName(),getString(R.string.app_socket_connect_message_i));
                    }
                });
                this.socket.on(Socket.EVENT_CONNECT_ERROR, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                //launchConnectionRefusedDialog();
                                CONNECTION_ESTABLISHED = false;
                                Log.e(getClass().getName(),getString(R.string.app_socket_connect_message_e));
                            }
                        });
                    }
                });
                this.socket.on(properties.getProperty(LOGIN_SOCKET_SERVICE), new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        final String structure = args[0].toString();
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                try {
                                    String usersStructure = structure;
                                    storeUsersStructureToDB(usersStructure);
                                    loginProcess(usersStructure);
                                } catch (Exception ex) {
                                    Log.e(getClass().getName(),ex.getMessage());
                                }
                            }
                        });
                    }
                });
                this.socket.on(properties.getProperty(UPDATE_USERS_SERVICE), new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        try{
                            storeUsersStructureToDB(args[0].toString());
                            Log.i(getClass().getName(),getString(R.string.login_socket_update_users_service_i));
                        }catch (Exception ex){
                            Log.e(getClass().getName(),ex.getMessage());
                        }
                    }
                });
            }catch (URISyntaxException ex){
                Log.e(getClass().getName(), ex.getMessage());
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
     * Muestra un dialogo de alerta informando al usuario con no se puede establecer conexión con el servidor.
     */
    private void launchConnectionRefusedDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.Alert(this, R.string.app_connection_refused_title,
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
     * Muestra un cuadro de dialogo de confirmación para informar que no hay conexión a internet.
     * y poder ingresar a las preferencias del dispositivo.
     */
    private void launchActiveNetworkDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.confirm(this, R.string.app_disconnected_title,
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

    /**
     * Método que que responde al evento click de una vista.
     * @param view vista que lanzó el evento
     */
    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.loginButton:
                String userStructure;
                if((userStructure = getUsersStructureFromDB()) == null){
                    if(isNetworkAvailable()) {
                        if(CONNECTION_ESTABLISHED) {
                            socket.emit(properties.getProperty(LOGIN_SOCKET_SERVICE), LOGIN_SOCKET_SERVICE_KEY);
                        }else{
                            launchConnectionRefusedDialog();
                            socket.disconnect();
                            socket.connect();
                        }
                    }else{
                        launchActiveNetworkDialog();
                    }
                }else{
                    loginProcess(userStructure);
                }
                ;break;
        }
    }

    /**
     * Consulta los datos de seguridad del usuario en la base de datos local
     * @return cadena de texto de la estructura de los usuarios disponibles para el acceso a la aplicación
     * @see SqlHelper
     */
    private String getUsersStructureFromDB(){
        try{
            /*sqlHelper.setQuery("CREATE TABLE IF NOT EXISTS 'security' (" +
                    "'iSecurityId' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, " +
                    "'vchSecurityStructure' TEXT NOT NULL, 'iSecurityStatus' INTEGER NOT NULL);");*/
            sqlHelper.setQuery(constants.CREATE_USERS_DATA_TABLE_QUERY);
            sqlHelper.execQuery();
            //sqlHelper.setQuery("SELECT vchSecurityStructure FROM security WHERE iSecurityStatus = 1");
            sqlHelper.setQuery(constants.SELECT_USERS_DATA_BY_STATUS_QUERY);
            sqlHelper.execQuery();
            Cursor cursor = sqlHelper.getCursor();
            if (cursor.getCount() > 0) {
                cursor.moveToFirst();
                return cursor.getString(0);
            }
            cursor.close();
        }catch (Exception ex){
            return null;
        }
        return null;
    }

    /**
     * Almacena la estructura de seguridad de los usuarios en base de datos local.
     * @param usersStructure cadena de texto con los datos de seguridad de los usuarios
     * @throws Exception lanza una ecepción si ocurre algún error en la manipulacion de los de datos de usuario o en la ejecución de la consulta.
     * @see SqlHelper
     */
    private void storeUsersStructureToDB(String usersStructure)throws Exception{
        sqlHelper.setQuery(constants.CREATE_USERS_DATA_TABLE_QUERY);
        sqlHelper.execQuery();
        sqlHelper.setQuery(constants.UPDATE_USERS_DATA_QUERY);
        sqlHelper.execUpdate();
        sqlHelper.setQuery(String.format(constants.INSERT_USERS_DATA_QUERY, usersStructure));
        sqlHelper.execInsert();
    }

    /**
     * Valida el acceso del usuario a el sistema
     * @param usersStructure cadena de texto con la estructura de los datos de usuario
     * @return una cadena de texto con un mensaje del estado de la validación
     * @throws JSONException se lanza si ocurre algún error al convertir la estructura a JSONArray
     * @see JSONArray
     * @see JSONObject
     */
    private int authenticateUser(String usersStructure)throws JSONException{
        String tabletKey = (Build.SERIAL != Build.UNKNOWN
                ? Build.SERIAL : Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID)).toLowerCase() +
                ((WifiManager) getSystemService(Context.WIFI_SERVICE)).getConnectionInfo().getMacAddress().replace(":", "").toLowerCase();
        String usr = this.editTexUser.getText().toString();
        String pwdUTF = Encryptor.md5_UTF(this.editTextPass.getText().toString());
        String pwdHEX = Encryptor.md5_HEX(this.editTextPass.getText().toString());
        JSONArray jUsersStructure = new JSONArray(usersStructure);
        int result = -1;
        if(jUsersStructure.length() == 0)
            return 3;//"Estrutura de seguridad vacia";
        for (int i = 0; i < jUsersStructure.length(); i++) {
            JSONObject tmp = jUsersStructure.getJSONObject(i);
            //Log.e("Structure", tmp.toString());
            if (usr.equals(tmp.getString("strusername"))) {
                if ((pwdUTF.equals(tmp.getString("strhashpassword"))) || (pwdHEX.equals(tmp.getString("strhashpassword")))) {
                    if(tabletKey.equals(tmp.getString("strtabletkey")) || tmp.getString("strtabletkey").equals("(o-o¬)")) {
                        authenticatedUser = new Interviewer(Integer.parseInt(tmp.getString("intidinterviewer")),usr,"");
                        result = 0;//"SUCCESS:"+tmp.getString("intidinterviewer")+","+usr;
                        break;
                    }else{
                        result = 1;//result = "Usted no está autorizado para ingresar en este dispositivo";
                    }
                } else
                    result = 2;//result = "Usuario y/o contraseña inválidos";
            } else
                result = 2;//result = "Usuario y/o contraseña inválidos";
        }
        return result;
    }

    /**
     * Inicia la actividad de inicio de sesión de el encuestador
     * @see Intent
     * @see PollsActivity
     */
    private void startSession(){
        this.editTexUser.setText("");
        this.editTextPass.setText("");
        Intent intent = new Intent(this, ProjectsActivity.class);// antes main_menu.class
        intent.putExtra(INTERVIEWER_EXTRA, authenticatedUser);
        startActivity(intent);
    }

    /**
     * Muestra un dialogo de progreso indeterminado indicarle a el usuario que se la aplicación esta trabajando
     * @see ProgressDialog
     */
    private void launchProgressDialog(){
        progressDialog = ProgressDialog.show(LoginActivity.this,
                getString(R.string.app_progress_dialog_title),
                getString(R.string.login_progress_dialog_authenticate_message),true);
    }

    /**
     * Utiliza un objeto Asynctask para ejecutar el proceso de inicio de sesión del encuestador
     * @param usersStructure estructura con los datos de acceso del encuestdor
     * @see AsyncTask
     * @see #authenticateUser(String)
     */
    private void loginProcess(String usersStructure){
        /**
         * clase encargada de hacer el proceso de autenticación de usuario en segundo plano
         * @see AsyncTask
         */
        class LoginProcess extends AsyncTask<String,Boolean,Integer>{

            /**
             * Método que se ejecuta antes de hacer la utenticación en segundo plano
             */
            @Override
            protected void onPreExecute(){
                launchProgressDialog();
            }

            /**
             * Método que ejecuta la tarea de autenticación de usuarien segundo plano
             * @param params estructura de seguridad de los usuarios registrados en el servidor
             * @return un valor númerico 0 si la uetenticación es exitosa,1 si el dispositivo no está autorizado
             * 2 si el usuario o contraseña proporcionados no existen, 3 si no hay usuarios registrados en el servidor
             * -1 y -2 si ocurre un error inesperado.
             */
            @Override
            protected Integer doInBackground(String... params) {
                try{
                    //downloadDataBase();
                    return authenticateUser(params[0]);
                }catch (Exception ex){
                    Log.e(getClass().getName(), ex.getMessage());
                    return -2;
                }
            }

            /**
             * Método que se ejecuta una vez terminada la tarea de autenticación en segundo plano.
             * muestra mensajes de alerta con el estado de la utenticación.
             * @param result resultado de el metodo doInBackgroud(params)
             */
            @Override
            protected void onPostExecute(Integer result){
                int resultMessage = R.string.login_authentication_message_e;
                switch (result){
                    case 0:startSession();break;
                    case 1:resultMessage = R.string.login_unauthorized_device_message_i;break;
                    case 2:resultMessage = R.string.login_bad_authentication_message_i;break;
                    case 3:resultMessage = R.string.login_empty_users_message_i;break;
                }
                if(result != 0){
                    Toast.makeText(LoginActivity.this, resultMessage, Toast.LENGTH_LONG).show();
                    if(isNetworkAvailable()) {
                        socket.emit(properties.getProperty(UPDATE_USERS_SERVICE), LOGIN_SOCKET_SERVICE_KEY);
                    }
                }
                progressDialog.dismiss();
            }
        }
        // Ejcuta el proceso de autenticación.
        new LoginProcess().execute(usersStructure);
    }

    /**
     * Dispatch onStart() to all fragments.  Ensure any created loaders are
     * now started.
     */
    @Override
    protected void onStart() {
        super.onStart();
        // Inicialización de la clase utilitaria para hecer operaciones en la base de datos local.
        sqlHelper = new SqlHelper(this);
        sqlHelper.databaseName = "SECURITY_DB";
        sqlHelper.OOCDB();
        this.socket.connect();// Conexión del socket
    }

    /**
     * Este metodo reponde al evento de detención en el ciclo de vida de la actividad.
     */
    @Override
    protected void onStop() {
        super.onStop();
        sqlHelper.close();// Cierra la conexión a la base de datos local.
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        socket.disconnect();// Se desconecta el socket.
        socket.close();// Se cierra la conexión al socket.
    }
}
