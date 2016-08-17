package com.miido.analiizo;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.ConnectivityManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.provider.Settings;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.PopupMenu;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.Resource;
import com.miido.analiizo.util.PropertyReader;
import com.miido.analiizo.util.SqlHelper;

import org.apache.http.HttpVersion;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ContentBody;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Properties;

/**
 * Actividad en donde se seleccionan los registros fotográficos para adjuntar a la encuesta.
 * @author Ing. Miguel Angel Urango Blanco /25/04/2016 Encryptor S.A.S
 * @version 1.0
 * @see ActionBarActivity
 * @see android.widget.AdapterView.OnItemClickListener
 * @see android.view.View.OnClickListener
 */
public class ResourcesActivity extends ActionBarActivity implements AdapterView.OnItemClickListener,View.OnClickListener{

    private GridView gridView;// GridView donde se despliegan los recursos aduntados
    private GridViewAdapter gridAdapter;// Adaptador del GridView
    private Toolbar toolbar;// Barra de herramientas posterior.
    private ImageButton saveResourcesButton;// Botón de guardado y sincronización de recursos.
    private ProgressBar progressBar;// Barra de progreso para la sincronización de imagenes.

    private SqlHelper sqlHelper;// Clase utilitaria para ejecutar consultas SQLIte
    private Constants constants;// Clase que contiene las constantes globales de la aplicación.
    private Properties properties;

    private ArrayList<Resource> images = new ArrayList<>();// lista de recursos
    private ArrayList<Resource> sentImages = new ArrayList<>(); // lista de imágenes enviadas al servidor.

    private String IMAGE_NAME = "%s_%s_IMAGE.jpg";// Nombre de los recursos
    private long CURRENT_POLL_ID;// Identificador de la encuesta a la cual se le adjuntan los recursos

    private final int NUMBER_OF_IMAGES = 6;// Número maximo de imágenes que se deben adjuntar.

    private final int PHOTOGRAPHIC_REQUEST_CODE = 1000;// Identificador de actividad de toma de fotográfias
    private final int GALLERY_REQUEST_CODE = 1001; // Identificador de actividad de selección de imágen en la galería.
    private final int IMAGE_PREVIEW_REQUEST_CODE = 1002;// Identificador de actividad de vista previa de imágen seleccionada.

    public static final String POLL_ID_EXTRA = "POLL_ID";// almacena el identificador el extra para obtener el identificador de la encuesta a travez del Intent.

    private int CURRENT_POSITION = -1;// Almacena la posición actual del recurso seleccionado.

    private final String MIME_TYPE = "image/jpeg";// Tipo de mime de los recursos.

    private ConnectivityManager connectivityManager;// verifica la conexión a internet.

    /**
     * Método que hace parte del ciclo de vida de la actividad y es ejecutado cuando la actividad es creada
     * @param savedInstanceState guarda el estado de la actividad
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);// Inicialización de la superclase.
        setContentView(R.layout.photographic_sources_layout_new);// Instancia de la vista contenedora desde los reursos.

        connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);// Inicialización del ConnectivutyManager

        CURRENT_POLL_ID = getIntent().getLongExtra(POLL_ID_EXTRA, 0);// Obtención del identificador de la encuesta a travez de los extras

        // Inicialización de la utilidad SQLIte y apertura de la base de datos
        this.sqlHelper = new SqlHelper(this);
        this.sqlHelper.databaseName = "POLLDATA_DB";// se establece el nombre de la base de datos
        this.sqlHelper.OOCDB();// Apertura o creación de la base de datos.
        this.constants = new Constants();// Instancia de las constantes globales.
        this.properties = new PropertyReader(this).getMyProperties("application.properties");

        createResourceTable();// Creación de la tabla que persistirá los recursos en base de datos local.

        // Inicialización y configuración de la barra de herramientas posterior.
        this.toolbar = (Toolbar) findViewById(R.id.tool_bar);// Obtención de la vista desde los recursos.
        this.toolbar.setTitle("Registros fotográficos");// estable el título de la barra.
        setSupportActionBar(toolbar);// Se estable el soporte de la barra de herramientas.
        this.toolbar.setLogo(R.drawable.ic_action_editor_insert_photo);// Establece el logo de la barra.
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);// Establece el botón de navegación hacia atras.
        this.toolbar.setNavigationOnClickListener(this);// Se estable el evento que responda al click del botón de navegación.

        //Incialización y conficuración del boton de guardado y sincronización de recursos.
        this.saveResourcesButton = (ImageButton) findViewById(R.id.saveResorcesButton);// Obtención de la vista desde los recursos.
        this.saveResourcesButton.setOnClickListener(this);// Establece el evento que responde al click del botón.
        this.saveResourcesButton.setImageResource(R.drawable.ic_action_content_save);// Establece el ícono del botón.
        this.saveResourcesButton.setTag(R.drawable.ic_action_content_save);// Establece la imagen en el Tag de la vista para su posterior identificación.

        // Si la se ha guardado el estado de la actividad.
        if(savedInstanceState != null){
            this.images = savedInstanceState.getParcelableArrayList("RESOURCES");// Se recuperan los recursos guardados.
            CURRENT_POSITION = savedInstanceState.getInt("POSITION");// se recupera la posición actual del recurso seleccionado.
        }else{
            try{
                getResourcesFromDB();// Se obtinen los recursos de la base de datos local
            }catch (SQLiteException ex){
                Log.e("SQLITE", ex.getMessage());
            }
        }

        // Si los recursos ya se han guardado
        if(!images.isEmpty() && getNumberOfSavedResources() > 0){
            int src = R.drawable.ic_action_file_cloud_upload;// Obtención del ícono de subida de archivos
            saveResourcesButton.setImageResource(src);// Se establece el ícono del botón  que representa la subida de recursos.
            saveResourcesButton.setTag(src);// se establece el ícono actual en el Tag para su futura identificación.
        }else{
            // Si lor recursos ya han sido subidos al servidor
            if (!images.isEmpty() && getNumberOfSentResources() > 0) {
                saveResourcesButton.setVisibility(View.INVISIBLE);// el botón de subida se hace invisible.
            }
        }

        // Inicialización y configuración de la barra de progreso.
        this.progressBar = (ProgressBar) findViewById(R.id.progressBar);// Obtención de la vista desde los recursos.
        this.progressBar.setIndeterminate(true);// se estblece el tipo de carga indeterminada.
        progressBar.getIndeterminateDrawable().setColorFilter(
                getResources().getColor(R.color.ColorPrimaryDark), android.graphics.PorterDuff.Mode.SRC_IN);// Se cambia el color por defecto del pulso.

        // Incialización y configuración del GridView.
        this.gridView = (GridView) findViewById(R.id.gridView);// Obtención de la vista desde los recursos.
        this.gridAdapter = new GridViewAdapter(this, R.layout.grid_image_item_new, this.images, this);// Inicialización del adaptador.
        this.gridView.setAdapter(this.gridAdapter);// Establece el adaptador a el GridView.
        this.gridView.setOnItemClickListener(this);// Establece ele evento que responderá a el click de un elemento del GridView.

        // Si el estado de la actividad no se guardó.
        if(savedInstanceState == null) {
            // Si no hay recursos en l base de datos.
            if (images.isEmpty()) {
                showAddMoreResources();// Se muestra un elemento en el GridView para adjuntar recursos
            } else {
                // Si el número de recursos adjuntados aún no ha llegado al limite
                if (images.size() < NUMBER_OF_IMAGES && getNumberOfSavedResources() == 0 && getNumberOfSentResources() == 0) {
                    showAddMoreResources();// Se muestra un elemento en el GridView para adjuntar recursos
                }
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
    private void launchActiveNetworkDialog(){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.confirm(this, "Verificar Conexión",
                "Esta opereción requiere una conexión a internet.\n"+
                        "¿Desea ver sus preferencias de red?");
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
     * Muestra una vista dentro del GridView para segir adjunatando mas registros fotográficos
     */
    private void showAddMoreResources() {
        Bitmap bitmap = BitmapFactory.decodeResource(getResources(), R.drawable.ic_take_photo);
        images.add(new Resource("Imagen", "", "", MIME_TYPE, "", "", bitmap));
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                gridAdapter.notifyDataSetChanged();
            }
        });
    }

    /**
     * hace un upload de un registro en el servidor
     * @param resource recurso a subir
     * @throws IOException es lanzada si ocurre un error relacionado a la subida del recurso
     * @see Resource
     */
    private void uploadImage(Resource resource) throws IOException{
        // Inicialización y configuranción del cliente http
        HttpClient httpClient = new DefaultHttpClient();
        httpClient.getParams().setParameter(CoreProtocolPNames.PROTOCOL_VERSION, HttpVersion.HTTP_1_1);
        String property = properties.getProperty("app.local.test","false").toUpperCase().equals("TRUE")
                ? properties.getProperty("http.image.upload.local.host")
                : properties.getProperty("http.image.upload.remote.host");
        HttpPost httpPost = new HttpPost(property);
        MultipartEntity multipartEntity = new MultipartEntity();
        ContentBody image = new FileBody(new File(resource.getDirectory() + resource.getName()), MIME_TYPE);
        multipartEntity.addPart("pollid", new StringBody(CURRENT_POLL_ID + ""));// Agrega el parámetro identificador de la encuesta
        multipartEntity.addPart("description",new StringBody(resource.getDescription()));// Agrega el parámetro descripción del recurso
        multipartEntity.addPart("UpImage",image);// Agrega la imágen del recurs a el multipartEntity.
        httpPost.setEntity(multipartEntity);
        httpClient.execute(httpPost);// Envia la solicitud al servidor.
        httpClient.getConnectionManager().shutdown();
    }

    @Override
    protected void onStop() {
        super.onStop();

    }

    /**
     * Ejecuta un proceso en segundo plano para subir todos los recursos adjuntados al servidor
     */
    private void uploadImagesProcess(){
        /**
         * Clase interna que se encarga de crear un proceso en segundo plano para subir todos los registros fotográficos al servidor
         * @see AsyncTask
         */
        class UploadImage extends AsyncTask<Resource,String,Integer>{

            private ProgressDialog progressDialog;

            /**
             * Médoto que es ejecutado antes de iniciar el proceso en segundo plano
             */
            @Override
            protected void onPreExecute() {
                progressBar.setVisibility(View.VISIBLE);
                this.progressDialog = com.miido.analiizo.util.Dialog.progressDialog(
                        ResourcesActivity.this,
                        R.string.app_progress_dialog_title,
                        R.string.app_progress_dialog_upload_resource_message, images.size());
                this.progressDialog.show();
                saveResourcesButton.setEnabled(false);
            }

            /**
             * Método que ejecuta las tareas en segundo plano, es este caso la subida de los recursos al servidor
             * @param resources recursos a subir al servidor
             * @return en este caso se retorna un valor entero, cero si la subida de los recursos es exitosa,
             * -1 si se da un error de entrada o salida y -2 si se da un error de almacenamiento en la base de datos local
             */
            @Override
            protected Integer doInBackground(Resource... resources) {
                try {
                    for(int i = 0; i < resources.length; i++) {
                        if(!resources[i].getDirectory().equals("")) {
                            publishProgress(resources[i].getName());
                            uploadImage(resources[i]);
                            publishProgress("1");
                        }
                    }
                    updateResourcesFromDB();
                }catch (IOException ex){
                    Log.e("UPLOADERROR", ex.getMessage());
                    return -1;
                }catch (SQLiteException ex){
                    Log.e("SQLITEERROR", ex.getMessage());
                    return -2;
                }
                return 0;
            }

            /**
             * Método que es ejecutado en el hilo principal de la aplicación,
             * comunmente utilizado para reflejar el proceso actual de las tareas en segundo plano para ser mostrado por un componente de la interface gráfica.
             * este método es ejecutado cuando se hace llamada a el método publishProgress(progress)
             * @param progress progreso de la tarea en segundo plano
             */
            @Override
            protected void onProgressUpdate(String... progress) {
                try{
                    this.progressDialog.incrementProgressBy(Integer.parseInt(progress[0]));
                }catch (NumberFormatException ex){
                    this.progressDialog.setMessage("Cargando " + progress[0] + " ...");
                }
            }

            /**
             * Método que se ejecuta despues de terminada la activiadad en segundo plano.
             * @param result el resultado de el método doInBackgroud(params)
             */
            @Override
            protected void onPostExecute(Integer result) {
                if(result == 0){
                    com.miido.analiizo.util.Dialog.Alert(ResourcesActivity.this,
                            "Información","Registros sincroniados con exito").show();
                    saveResourcesButton.setVisibility(View.INVISIBLE);
                }else{
                    if(result == -1){
                        com.miido.analiizo.util.Dialog.Alert(ResourcesActivity.this,
                                "Error", "Ocuurrió un error al tratar de sincronizar los registros").show();
                    }
                }
                this.progressDialog.dismiss();
                progressBar.setVisibility(View.INVISIBLE);
                saveResourcesButton.setEnabled(true);
            }
        }

        // Ejecución del proceso de subida de recursos al servidor.
        new UploadImage().execute(images.toArray(new Resource[images.size()]));
    }

    /**
     * Método que almacena referencias a los registros en la base de datos local.
     * @throws SQLiteException es lanzada si ocurre algun error al insertar los registros.
     * @see SqlHelper
     * @see Constants
     */
    private void storeImagesReferencesToDB() throws SQLiteException{
        StringBuilder buffer = new StringBuilder();
        String pattern = "(%s,'%s','%s','%s','%s','%s')";
        for(int i = 0; i < images.size(); i++){
            Resource src = images.get(i);
            buffer.append(i == 0 ? "" : ",");
            String date = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
            images.get(i).setSavedDate(date);
            buffer.append(String.format(pattern, CURRENT_POLL_ID, src.getDirectory(), src.getName(),src.getDescription(), src.getMimeType(), date));
        }

        this.sqlHelper.setQuery(String.format(this.constants.INSERT_MULTIPLE_RESOURCE_DATA_QUERY, buffer.toString()));
        this.sqlHelper.execInsert();
        Toast.makeText(this, "Imagenes Guardadas con exito", Toast.LENGTH_LONG).show();
    }

    /**
     * Obtiene el número de registros enviados almacenados en la base de datos local.
     * @return el número de registros subidos a el servidor
     */
    private int getNumberOfSentResources(){
        try{
            sqlHelper.setQuery(String.format(constants.SELECT_RESOURCE_DATA_SENT_QUERY, CURRENT_POLL_ID));
            sqlHelper.execQuery();
            Cursor cursor = sqlHelper.getCursor();
            if(cursor.getCount() > 0){
                int count = cursor.getInt(0);
                cursor.close();
                return count;
            }
        }catch (SQLiteException ex){
            Log.e("SQLITEERROR", ex.getMessage());

        }
        return 0;
    }

    /**
     * Obtiene el número de registros guardados localmente en el dispositivo.
     * @return el número de registros guardados.
     */
    private int getNumberOfSavedResources(){
        try{
            sqlHelper.setQuery(String.format(constants.SELECT_RESOURCE_DATA_SAVED_QUERY, CURRENT_POLL_ID));
            sqlHelper.execQuery();
            Cursor cursor = sqlHelper.getCursor();
            if(cursor.getCount() > 0){
                int count = cursor.getInt(0);
                cursor.close();
                return count;
            }
        }catch (SQLiteException ex){
            Log.e("SQLITEERROR", ex.getMessage());

        }
        return 0;
    }

    /**
     * Método que se ejecuta cuando la actividad crea elementos de menú
     * @param menu referencia el menú creado
     * @return
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.phographic_menu_new, menu);
        return true;
    }

    /**
     * Método que responde al evento click sobre un elemento del menú
     * @param item referencia a el objeto que desencadena el evento
     * @return false para continuar normalmente con el procesamiento del menú
     */
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case R.id.actionGallery:startGalleryActivity();break;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     * Método que responde al evento click sobre un elemento del ListView
     * @param adapterView referencia al adaptador del ListView
     * @param view referencia a el View que desencadena el evento
     * @param position pocición del elemento que lo desencadena
     * @param id identificador del elemento que lo desencadena.
     */
    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        CURRENT_POSITION = position;// se establece la posición actual.
        Resource imageItem = (Resource) gridAdapter.getItem(position);// Se obtiene el recurso actual.
        Bitmap bitmap1 = imageItem.getImage();// Se obtiene la imágen del recurso.
        Bitmap bitmap2 = BitmapFactory.decodeResource(getResources(), R.drawable.ic_take_photo);// se obtiene la imagen predeterminada.
        // Si el ícono actual del recurso es el predeterminado.
        if(bitmap1.sameAs(bitmap2)){
            startPhotoActivity();// Se lanza la actividad de toma de registro fotográfico.
        }else {
            startImagePreviewActivity(imageItem);// Se lanza la actividad de vista previa.
        }
    }

    /**
     * Agrega un nuevo recurso a la lista de recursos adjuntados
     * @param fileName nombre del recurso.
     * @param fileDirectory directorio de guardado.
     * @param tmpPath ruta de guardado temporal.
     * @param image imágen del recurso.
     */
    private void addResourceToList(String fileName,String fileDirectory,String tmpPath,Bitmap image){
        // Se iicializa un nuevo recurso con el nombre,directorio,tipo de mime y la imagen obtenida
        Resource resource = new Resource(fileName, "",fileDirectory, MIME_TYPE,"","", image);
        resource.setTmpPath(tmpPath);// se establece la ruta a la imagen en caché.
        images.set(CURRENT_POSITION, resource);// se agrega el nuevo recurso a la lista.
        // en el hilo principal.
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                gridAdapter.notifyDataSetChanged();// se notifica al Adaptador que los datos han cambiado para que los muestre visualmente.
            }
        });

        startImagePreviewActivity(resource);// Se inicia la actividad de vista previa de la imágen.

        // si la lista de recursos no ha llegado al limite establecido.
        if(images.size() < NUMBER_OF_IMAGES) {
            showAddMoreResources();// muestra el elemento predeterminado para seguir adjuntando recursos.
        }
    }

    /**
     * Procesa el resultado de la Actividad de captura fotográfica
     * @param data datos proporcionados por la actividad.
     */
    private void processCameraResult(Intent data){
        String fileTmpDirectory = this.getExternalCacheDir() + "/";// Obtiene la ruta del directorio caché de la aplicación.
        String fileDirectory = Environment.getExternalStorageDirectory() + "/";// Obtiene la ruta de el almacenamiento externo del sipositivo.
        String fileName = String.format(IMAGE_NAME, CURRENT_POLL_ID, CURRENT_POSITION);// Se establece el nombre del recurso ([id_encuesta]_[posición]_IMAGE.jpg
        String filePath = fileTmpDirectory + fileName;// Se establece la ruta de guardado de la imágen.

        Bitmap currentPhotography;
        // Si la actividad proporciona datos
        if(data != null ) {
            currentPhotography = (Bitmap) data.getExtras().get("data");// Se obtiene el tumbnail de la imágen tomada.
        }else{
            currentPhotography = BitmapFactory.decodeFile(filePath);// Se obtiene la imágen desde la ruta especificada al lanzar la actividad.
        }

        // Agrega el nuevo recurso a la lista
        addResourceToList(fileName,fileDirectory,Uri.fromFile(new File(filePath)).toString(),currentPhotography);
    }

    /**
     * Procesa el resultado de la actividad de selección de imágen desde la galería.
     * @param data datos proporcionados por la actividad.
     * @throws IOException es lanzada si ocurre un error al procesar el flujo de bytes de la imágen seleccionada.
     */
    private void processGalleryResult(Intent data)throws IOException{
        BitmapFactory.Options options = new BitmapFactory.Options();// Obtiene las opciones predeterminadas del BitmapFactory.
        Uri fileUri = data.getData();// Obtiene el Uri de la imágen seleccionada en la galería
        InputStream in = getContentResolver().openInputStream(fileUri);// Obtiene el flujo bytes de la imágen
        Bitmap currentPhotography = BitmapFactory.decodeStream(in, null, options);// decodifica la el flujo de bytes de la imágen y obtiene un Bitmap
        in.close();// Cierra el flujo.
        String fileDirectory = Environment.getExternalStorageDirectory() + "/";// Obtiene la ruta de el almacenamiento externo del sipositivo.
        String fileName = String.format(IMAGE_NAME, CURRENT_POLL_ID, CURRENT_POSITION);// Se establece el nombre del recurso ([id_encuesta]_[posición]_IMAGE.jpg

        // Agrega el nuevo recurso a la lista.
        addResourceToList(fileName,fileDirectory,fileUri.toString(),currentPhotography);
    }

    /**
     * Obtiene los resultados de la aactividades lanzadas.
     * @param requestCode código de la actividad solicitada.
     * @param resultCode código del resultado proporcionado por la actividad lanzada.
     * @param data datos proporcionados por la actividad lanzada.
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        // Si el resultado de la actividad lanzada es OK.
        if(resultCode == RESULT_OK){
            // Si la actividad lanzada fué la toma fotográfica
            if(requestCode == PHOTOGRAPHIC_REQUEST_CODE){
                processCameraResult(data);
                /*
                final String fileTmpDirectory = this.getExternalCacheDir() + "/";// Obtiene la ruta del directorio caché de la aplicación.
                final String fileDirectory = Environment.getExternalStorageDirectory() + "/";// Obtiene la ruta de el almacenamiento externo del sipositivo.
                final String fileName = String.format(IMAGE_NAME, CURRENT_POLL_ID, CURRENT_POSITION);// Se establece el nombre del recurso ([id_encuesta]_[posición]_IMAGE.jpg
                String filePath = fileTmpDirectory + fileName;// Se establece la ruta de guardado de la imágen.

                Bitmap currentPhotography;
                // Si la actividad proporciona datos
                if(data != null ) {
                    currentPhotography = (Bitmap) data.getExtras().get("data");// Se obtiene el tumbnail de la imágen tomada.
                }else{
                    currentPhotography = BitmapFactory.decodeFile(filePath);// Se obtiene la imágen desde la ruta especificada al lanzar la actividad.
                }


                // Se iicializa un nuevo recurso con el nombre,directorio,tipo de mime y la imagen obtenida
                Resource resource = new Resource(fileName, "",fileDirectory, MIME_TYPE,"","", currentPhotography);
                resource.setTmpPath(Uri.fromFile(new File(filePath)).toString());// se establece la ruta a la imagen en caché.
                images.set(CURRENT_POSITION, resource);// se agrega el nuevo recurso a la lista.
                // en el hilo principal.
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        gridAdapter.notifyDataSetChanged();// se notifica al Adaptador que los datos han cambiado para que los muestre visualmente.
                    }
                });

                startImagePreviewActivity(resource);// Se inicia la actividad de vista previa de la imágen.

                // si la lista de recursos no ha llegado al limite establecido.
                if(images.size() < NUMBER_OF_IMAGES) {
                    showAddMoreResources();// muestra el elemento predeterminado para seguir adjuntando recursos.
                }
                */
            }else{
                // Si la actividad lanzada fué la selección de imágen desde la galería.
                if(requestCode == GALLERY_REQUEST_CODE){
                    try {
                        processGalleryResult(data);
                        /*
                        BitmapFactory.Options options = new BitmapFactory.Options();
                        Uri fileUri = data.getData();
                        InputStream in = getContentResolver().openInputStream(fileUri);
                        Bitmap currentPhotography = BitmapFactory.decodeStream(in, null, options);
                        in.close();
                        final String fileDirectory = Environment.getExternalStorageDirectory() + "/";
                        final String fileName = String.format(IMAGE_NAME, CURRENT_POLL_ID, CURRENT_POSITION);


                        Resource resource = new Resource(fileName, "",fileDirectory, MIME_TYPE,"","", currentPhotography);
                        resource.setTmpPath(fileUri.toString());
                        images.set(CURRENT_POSITION, resource);
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                gridAdapter.notifyDataSetChanged();
                            }
                        });

                        startImagePreviewActivity(resource);

                        if(images.size() < NUMBER_OF_IMAGES) {
                            showAddMoreResources();
                        }
                        */
                    }catch (IOException ex){
                        Log.e("IO", ex.getMessage());
                    }
                }else{
                    // Si la actividad lanzada fué la vista previa de imágen
                    if(requestCode == IMAGE_PREVIEW_REQUEST_CODE){
                        // se Obtiene la descripción de ls imágen a traves del el extra del intent
                        String description = data.getStringExtra(ImagePreviewActivity.DESCRIPTION_EXTRA);
                        ((Resource)gridAdapter.getItem(CURRENT_POSITION)).setDescription(description);// se establece la descripción a el recurso seleccionado.
                    }
                }
            }
        }
    }

    /**
     * Inicia la actividad de captura fotográfico.
     */
    private void startPhotoActivity(){
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
            String imagePath = this.getExternalCacheDir() + "/" + String.format(IMAGE_NAME, CURRENT_POLL_ID, CURRENT_POSITION);
            takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT,Uri.fromFile(new File(imagePath)));// Agrega el extra con la ruta de almacenamiento de la imágen.
            startActivityForResult(takePictureIntent, PHOTOGRAPHIC_REQUEST_CODE);// Lanza la actvidad.
        }
    }

    /**
     * Inicia la actividad de selección de imágen desde la galería.
     */
    private void startGalleryActivity(){
        Intent intent = new Intent(Intent.ACTION_PICK, android.provider.MediaStore.Images.Media.INTERNAL_CONTENT_URI);
        startActivityForResult(intent, GALLERY_REQUEST_CODE);
    }

    /**
     * Inicia la actividad de Visor de imágen.
     * @param imageItem el recurso a visualizar.
     */
    private void startImagePreviewActivity(Resource imageItem){
        Intent intent = new Intent(ResourcesActivity.this, ImagePreviewActivity.class);
        intent.putExtra(ImagePreviewActivity.TITLE_EXTRA, imageItem.getName());
        String path;
        // Si el recurso aún no ha sido guardado
        if(imageItem.getSavedDate().equals("")){
            path = imageItem.getTmpPath();// obtiene la ruta temporal de la imágen.
        }else{
            path = imageItem.getDirectory() + imageItem.getName();// Obtiene la ruta donde se encuentra guardada la imágen.
            path = Uri.fromFile(new File(path)).toString();// convierte la ruta a Uri
            // Establece un extra para que la actividad lanzada identifique si el recurso fué guardao.
            intent.putExtra(ImagePreviewActivity.IS_RESOURCE_SAVE_EXTRA, true);
        }
        intent.putExtra(ImagePreviewActivity.IMAGE_EXTRA, path);// Establece el extra con la Uri de la imágen
        intent.putExtra(ImagePreviewActivity.DESCRIPTION_EXTRA, imageItem.getDescription());// Establece un extra con la descripción de la imágen.
        startActivityForResult(intent, IMAGE_PREVIEW_REQUEST_CODE);
    }

    /**
     * Almacena la imagen el almacenamiento local.
     */
    private void saveImagesToLocalStorage(){
        for( int i = 0; i < images.size(); i++){
            Resource resource = images.get(i);
            try {
                FileOutputStream out = new FileOutputStream(resource.getDirectory() + resource.getName());
                resource.getImage().compress(Bitmap.CompressFormat.JPEG, 100, out);
                out.flush();
                out.close();
            }catch (FileNotFoundException ex){
                Log.e("FILENOTFOUND", ex.getMessage());
            }catch (IOException ex){
                Log.e("IO", ex.getMessage());
            }
        }
    }

    /**
     * Crea la tabla que almacena los registros en la base de datos local.
     */
    private void createResourceTable(){
        sqlHelper.setQuery(constants.CREATE_RESOURCES_DATA_QUERY);
        sqlHelper.execQuery();
    }

    /**
     * Obtiene los registros desde la base de datos local
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta sql.
     */
    private void getResourcesFromDB()throws SQLiteException{
        sqlHelper.setQuery(String.format(constants.SELECT_RESOURCE_DATA_QUERY, CURRENT_POLL_ID));
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        for(int i = 0; i < cursor.getCount(); i++){
            Resource src = new Resource(
                    cursor.getString(0), cursor.getString(1), cursor.getString(2),
                    cursor.getString(3), cursor.getString(4), cursor.getString(5), null
            );
            src.setImage(BitmapFactory.decodeFile(src.getDirectory() + src.getName()));
            images.add(src);
            cursor.moveToNext();
        }
        cursor.close();
    }

    /**
     * Actualiza la fecha de envio de los recursos
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta sql.
     */
    private void updateResourcesFromDB()throws SQLiteException{
        String date = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
        sqlHelper.setQuery(String.format(constants.UPDATE_RESOURCE_DATA_QUERY, date, CURRENT_POLL_ID));
        sqlHelper.execUpdate();
    }

    /**
     * Actualiza los nombres de los recursos.
     */
    private void refreshImageNames(){
        for(int i = 0; i < images.size(); i++){
            if(i < images.size() - 1) {
                images.get(i).setName(String.format(IMAGE_NAME, CURRENT_POLL_ID, i));
            }else{
                Resource src = images.get(i);
                if(src.getImage().sameAs(BitmapFactory.decodeResource(getResources(), R.drawable.ic_take_photo))) {
                    images.get(i).setName("Imagen");
                }else{
                    images.get(i).setName(String.format(IMAGE_NAME,CURRENT_POLL_ID, i));
                }
            }
        }
    }

    /**
     * Muestra un PopupMenu con opciones.
     * @param view referencia a la vista que despliega el PopupMenu.
     */
    private void showPopupMenu(final View view){
        final GridViewAdapter.ViewHolder viewHolder = (GridViewAdapter.ViewHolder) view.getTag();
        PopupMenu popupMenu = new PopupMenu(this, view);
        popupMenu.getMenuInflater().inflate(R.menu.resource_popup_menu, popupMenu.getMenu());

        Bitmap b1 = BitmapFactory.decodeResource(getResources(), R.drawable.ic_take_photo);
        Bitmap b2 = ((Resource) gridAdapter.getItem(viewHolder.position)).getImage();

        popupMenu.getMenu().getItem(0).setEnabled(!b1.sameAs(b2));

        popupMenu.setOnMenuItemClickListener(new PopupMenu.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem menuItem) {
                switch (menuItem.getItemId()) {
                    case R.id.deleteResource:
                        launchConfirmRemoveResourceDialog(viewHolder.position);
                        break;
                    case R.id.replaceResource:
                        CURRENT_POSITION = viewHolder.position;
                        startPhotoActivity();
                        break;
                    case R.id.galleryResource:
                        CURRENT_POSITION = viewHolder.position;
                        startGalleryActivity();
                        break;
                }
                return false;
            }
        });
        popupMenu.show();
    }

    /**
     * Elimina un recurso de la base de datos local
     * @param position posición del recurso a eliminar
     * @throws SQLiteException es lanzada si ocurre un error al ejecutar la consulta.
     */
    private void deleteImageFromDB(int position)throws SQLiteException{
        Resource resource = (Resource) gridAdapter.getItem(position);
        this.sqlHelper.setQuery(String.format(constants.DELETE_RESOURCE_DATA_QUERY, resource.getName()));
        this.sqlHelper.execUpdate();
    }

    /**
     * Borra un recurso mostrado en el GridView.
     * @param position
     */
    private void deleteResourceFromList(final int position){
        // en el hilo principal
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                images.remove(position);// Elimina el recurso de la lista.
                refreshImageNames();// refresca el nombre de los recursos.
                gridAdapter.notifyDataSetChanged();// Notifica a el dapatador para que lo elimine visualmente.
            }
        });
    }

    /**
     * Elminia un recurso de el almacenamiento local del dispositivo.
     * @param position posición del recurso a eliminar.
     * @throws Exception es lanzada si ocurre un error al ejecutar la consulta.
     * @deprecated
     */
    private void deleteImageFromLocalStorage(int position) throws Exception{
        Resource resource = (Resource) gridAdapter.getItem(position);
        String filePath = resource.getDirectory() + resource.getName();
        if(!new File(filePath).delete()){
            throw new Exception("No se pudo borrar "+ filePath);
        }
    }

    /**
     * Muestra un dialogo de confirmación antes de subir los recursos al servidor.
     * @see com.miido.analiizo.util.Dialog
     */
    private void launchConfirmSynchronizeResourcesDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this,
                R.string.app_confirm_dialog_title,
                R.string.app_confirm_dialog_upload_resource_message);
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                //saveImagesToLocalStorage();
                uploadImagesProcess();
            }
        });
        d.show();
    }

    /**
     * Muestra un dialogo de confirmación antes de guardar los recursos en almacenamiento interno y a la base de datos local del dispositivo.
     * @see com.miido.analiizo.util.Dialog
     */
    private void launchConfirmSaveResourcesDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this, "Confirmación", "¿Desea guardar los registros?");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                if(!images.isEmpty()){
                    int position = images.size() - 1;
                    Resource resource = images.get(position);
                    if(resource.getImage().sameAs(BitmapFactory.decodeResource(getResources(),R.drawable.ic_take_photo))){
                        deleteResourceFromList(position);
                    }
                }
                saveImagesToLocalStorage();
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        gridAdapter.notifyDataSetChanged();
                    }
                });
                storeImagesReferencesToDB();
                saveResourcesButton.setImageResource(R.drawable.ic_action_file_cloud_upload);
                saveResourcesButton.setTag(R.drawable.ic_action_file_cloud_upload);
            }
        });
        d.show();
    }

    /**
     * Muestra un dialogo de confirmación antes de borrar un recurso adjuntado.
     * @param position posicion del recurso a eliminar.
     * @see com.miido.analiizo.util.Dialog
     */
    private void launchConfirmRemoveResourceDialog(final int position){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this, "Confirmación", "¿Desea Eliminar el recurso?");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                try {
                    deleteImageFromDB(position);
                    Resource resource = (Resource) gridAdapter.getItem(images.size() - 1);
                    if(!resource.getDirectory().equals("")){
                        showAddMoreResources();
                    }
                    //deleteImageFromLocalStorage(position);
                }catch (Exception ex){
                    Log.e("DELETEERROR", ex.getMessage());
                }
                deleteResourceFromList(position);
            }
        });
        d.show();
    }

    private void showAlertDialogTestApp(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,"Información", "Esta acción no se puede ejecutar. versión de prueba");
        d.show();
    }

    /**
     * Método que responde al evento click de un componente gráfico de la actividad.
     * @param view referencia a la vista que desencadena el evento.
     */
    @Override
    public void onClick(final View view) {
        // Evalua el id de la vista que desencadena el evento.
        switch (view.getId()){
            case R.id.buttonAdd:// botón de opciones de cada elemento del GridView [...]
                showPopupMenu(view);// Muestra el popup de opciones
                break;
            case R.id.saveResorcesButton:// Botón de guardado y sincronización de recursos.
                // Si no hay recursos adjuntos en la lista
                if(!images.isEmpty()){
                    Integer resource = (Integer) this.saveResourcesButton.getTag();// Obtiene el ícono actual del botón desde la propiedad Tag de la vista.
                    // Si el ícono actual del botón es igual al ícono de subidad de recursos.
                    if(resource == R.drawable.ic_action_file_cloud_upload) {
                        boolean testVersion = Boolean.parseBoolean(properties.getProperty("app.test.version"));
                        if (testVersion) {
                            showAlertDialogTestApp();
                        } else{
                            // Si se esta conectado a una red.
                            if (isNetworkAvailable()) {
                                launchConfirmSynchronizeResourcesDialog();// Muestra el dialogo de confirmación de sincronización de recursos
                            } else {
                                launchActiveNetworkDialog();// Muestra el dialogo de confirmación paa acceder a ñas configuraciones de red.
                            }
                        }
                    }else{
                        launchConfirmSaveResourcesDialog();// Muestra el dialogo de confirmación de guardado de recursos.
                    }
                }
                break;
            default:onBackPressed();// responde al botón de navegación de la barra de herramientas.
        }
    }

    /**
     * Guarda el estado de de la variables CURRENT_POSITION e images para ser recuperadas al ser recreada la actividad.
     * @param outState guarda el estado de la actividad
     */
    @Override
    protected void onSaveInstanceState(Bundle outState) {
        outState.putInt("POSITION",CURRENT_POSITION);// Guarda la posición actual del recurso seleccionado
        outState.putParcelableArrayList("RESOURCES", images);// Guarda los recursos adjuntados.
        super.onSaveInstanceState(outState);// Llamado al método de la superclase.
    }

    /**
     * Muestra un dialogo de confirmación antes de salir de la actividad.
     * @see com.miido.analiizo.util.Dialog
     */
    private void launchQuitConfirmDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this, "Confirmación", "¿Seguro que desea salir?");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                setResult(RESULT_OK);
                finish();
            }
        });
        d.show();
    }

    /**
     * Método que responde al precionar el botón de retroceso de actividad.
     */
    @Override
    public void onBackPressed() {
        launchQuitConfirmDialog();
    }

    /**
     * Adaptdor del GridView, adapta objetos personalizados a el GridView
     * @see ArrayAdapter
     */
    public class GridViewAdapter extends ArrayAdapter {
        private Context context;
        private int layoutResourceId;
        private ArrayList data = new ArrayList();
        private View.OnClickListener onClickListener;

        /**
         * contructor de la calse GridAdapter.
         * @param context contexto de la aplicación.
         * @param layoutResourceId identificador del layout adaptado.
         * @param data lista de elementos a mostrar en el GridView.
         * @param onClickListener evento que responde al click del botón opciones del recurso.
         */
        public GridViewAdapter(Context context, int layoutResourceId, ArrayList data, View.OnClickListener onClickListener) {
            super(context, layoutResourceId, data);
            this.layoutResourceId = layoutResourceId;
            this.context = context;
            this.data = data;
            this.onClickListener = onClickListener;
        }

        /**
         * Obtiene una referencia una vista del adaptador en una posición especifica.
         * @param position posicion de la vista.
         * @param convertView vista referenciada.
         * @param parent contenedor de la vista
         * @return vista referenciada.
         */
        @Override
        public View getView(int position, View convertView, ViewGroup parent) {
            View row = convertView;
            ViewHolder holder = null;

            // Si la vista es igual a null
            if (row == null) {
                LayoutInflater inflater = ((Activity) context).getLayoutInflater();// Se obtiene un LayoutInflater.
                row = inflater.inflate(layoutResourceId, parent, false); // Se obtiene la vista desde los recursos a travez del LayoutInflater
            }

            holder = new ViewHolder();// Instanciación de la clase contenedora de los elementos Gráficos
            holder.imageTitle = (TextView) row.findViewById(R.id.imageName);// Obtención de la vista del título de la imagen desde los recursos.
            holder.image = (ImageView) row.findViewById(R.id.image);// Obtención de la vista de la imágen desde los recursos.
            ImageButton imageButton = (ImageButton) row.findViewById(R.id.buttonAdd);// obtención del botón de opciones desde los recursos.
            imageButton.setOnClickListener(onClickListener);// establece el evento click del botón de opciones del recurso.
            holder.imageButton = imageButton;// Establece el botón de opciones en el ViewHolder.
            holder.position = position;// Establece la posición en el ViewHolder.
            imageButton.setTag(holder);// Establece el ViewHolder en el Tag del boton de opciones para obtener los elementos gráficos en el futuro.
            row.setTag(holder);// Establece el ViewHolder en el Tag de la vista actual para obtener los elementos gráficos en el futuro.

            Resource item = (Resource) data.get(position);// Obtiene el recurso en la posición actual.
            // Si el recurso en la posición actual ya fué guardado
            if(!item.getSavedDate().equals("")){
                imageButton.setEnabled(false);// se deshabilita el boton de opciones.
            }
            holder.imageTitle.setText(item.getName());//Establece el título del recurso.
            holder.image.setImageBitmap(item.getImage());// Establece la imágen del recurso
            return row;// retorna la vista.
        }

        /**
         * contiene elementos de interface relacionados a los recursos
         */
        class ViewHolder {
            int position;// Posición del la vista
            TextView imageTitle;// Título del recurso
            ImageView image; // Imágen del recurso
            ImageButton imageButton;// botón de opciones.
        }
    }

}
