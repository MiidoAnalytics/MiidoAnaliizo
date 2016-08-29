package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.app.Activity;
import android.provider.MediaStore;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;
import com.miido.analiizoOBRAS.model.Person;
import com.miido.analiizoOBRAS.model.Poll;
import com.miido.analiizoOBRAS.util.SqlHelper;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;

/**
 * Contiene un formulario donde se cargan o gestionan datos basicos de la persona a encuestar.
 * Los datos se pueden diligenciar manualmente o cargarse automaticamente a través de la lectura del
 * código PDF417 mostrado en la parte posterior de la cédula de ciudadanía.
 * El registro fotográfico se carga a través de la cámara del dispositivo.
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S 12/11/2015
 * @version 1.0
 * @see android.view.View.OnClickListener
 */

public class PersonDataBasicActivity extends Activity implements View.OnClickListener,LocationListener,View.OnKeyListener{

    //botón que iniciará la actividad de escaneo de códigos PDF417 de la cc de la persona a encuestar
    private ImageButton scanButton;
    //botón que iniciará la actividad de captura fotográfica de la persona a encuestar
    private ImageButton photoButton;
    //botón que iniciará la actividad de realización de encuesta
    private ImageButton initPullButton;
    //entrada de texto que contendrá la identificación de la persona
    private EditText id;
    //entrada de texto que contendrá el primer nombre de la persona
    private EditText firstname1;
    //entrada de texto que contendrá el segundo nombre de la persona
    private EditText firstname2;
    //entrada de texto que contendrá el primer apellido de la persona
    private EditText lastname1;
    //entrada de texto que contendrá el segundo apellido de la persona
    private EditText lastname2;
    //entrada de texto que contendrá la fecha de nacimiento de la persona
    private EditText birthday;
    //lista desplegable que contiene los géneros disponibles
    private Spinner gender;
    //lista desplegable que contiene los grupos sanguineos disponibles
    private Spinner bloodgroup;
    //lista desplegable que contiene los rh (+) y (-)
    private Spinner rh;
    //vista de imagen que contendrá la foto de la persona a encuestar
    private ImageView photo;
    //Objeto que contendra los datos basicos de l persona a encuestar.
    private Person person;
    //Objeto que contiene la encuesta actual seleccionada anteriormente
    private Poll currentPoll;

    private Bitmap photography;

    private LocationManager locationManager;
    private boolean IS_GSP_ENABLED = false;

    // variable primitiva entera que contiene un número unico que identifica la actividad ScanPersonActivity
    // para la obtención posterior de una respuesta y de datos gestionados por ella.
    // su valor no se puede modificar en tiempo de ejecución
    private final int SCAN_CAMERA_REQUEST_CODE = 1001;

    // variable primitiva entera que contiene un número unico que identifica la actividad de captura fotografica
    // para la obtención posterior de una respuesta y de datos gestionados por ella.
    // su valor no se puede modificar en tiempo de ejecución
    private final int PHOTO_CAMERA_REQUEST_CODE = 1002;

    //variable primitiva entera que contiene un número unico que identifica la actividad de llenado de la encuesta
    // para la obtención de una respuesta de que que la encuesta se llenó y guardó con exito
    // su valor no puede ser modificado en tiempo de ejecución.
    private final int FILL_POLL_REQUEST_CODE = 1003;

    /**
     * se ejecuta cuando la ctividad es creada
     * @param savedInstanceState guarda datos para utilizar al momento de recrear la actividad.
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_scan_result_person);

        currentPoll = getIntent().getParcelableExtra("poll");

        // Instaciación de los botones desde el xml
        scanButton = (ImageButton) findViewById(R.id.scanButton);
        photoButton = (ImageButton) findViewById(R.id.photoButton);
        initPullButton = (ImageButton) findViewById(R.id.initPollButton);

        // agregación de eventos a los botones
        scanButton.setOnClickListener(this);
        photoButton.setOnClickListener(this);
        initPullButton.setOnClickListener(this);

        // instaciación de los entradas de testo desde el xml
        id = (EditText) findViewById(R.id.ccEditTex);
        firstname1 = (EditText) findViewById(R.id.firstName1EditText);
        firstname2 = (EditText) findViewById(R.id.firstName2EditText);
        lastname1 = (EditText) findViewById(R.id.lastName1EditText);
        lastname2 = (EditText) findViewById(R.id.lastName2EditText);
        birthday = (EditText) findViewById(R.id.birthDateEditText);

        birthday.setOnClickListener(this);

        id.setOnKeyListener(this);
        firstname1.setOnKeyListener(this);
        firstname2.setOnKeyListener(this);
        lastname1.setOnKeyListener(this);
        lastname2.setOnKeyListener(this);
        birthday.setOnKeyListener(this);

        // instanciación de las listas desplegables desde el xml
        gender = (Spinner) findViewById(R.id.genderSpinner);
        bloodgroup = (Spinner) findViewById(R.id.bloodGroupSpinner);
        rh = (Spinner) findViewById(R.id.rhSpinner);

        // instanciación de la vista de imagen desde el xml
        photo = (ImageView) findViewById(R.id.personImageView);

        locationManager=(LocationManager) getSystemService(Context.LOCATION_SERVICE);
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, this);
        if(locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
           IS_GSP_ENABLED=true;
        }
    }

    /**
     * se ejecuta cuando de da click en una vista
     * @param view vista que desencadena el evento
     * @see #launchScanCamera()
     * @see #launchPhotoCamera()
     * @see #launchPull()
     */
    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.scanButton:launchScanCamera();break;
            case R.id.photoButton:launchPhotoCamera();break;
            case R.id.initPollButton:launchPull();break;
            case R.id.birthDateEditText:
                launchDatePickerDialog();break;
        }
    }

    /**
     * Inicia la actividad de scaneo de código PDF417
     * @see Intent
     * @see ScanPersonActivity
     */
    private void launchScanCamera(){
        startActivityForResult(new Intent(this, ScanPersonActivity.class), SCAN_CAMERA_REQUEST_CODE);
    }

    /**
     * Inicia la actividad de captura fotográfica
     * @see Intent
     * @see MediaStore
     */
    private void launchPhotoCamera(){
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
            startActivityForResult(takePictureIntent, PHOTO_CAMERA_REQUEST_CODE);
        }
    }

    /**
     * Inicia la actividad de diligenciamiento de encuesta
     * @see #validateFields()
     * @see Intent
     * @see Master
     */
    private void launchPull(){
        //if(validateFields()) {
        if(true) {
            if(IS_GSP_ENABLED) {
                Intent intent = new Intent(this, Master.class);
                // adjunta información por medio de los extras a la actividad posterior
                intent.putExtra("poll", this.currentPoll);
                intent.putExtra("person", this.person);
                intent.putExtra("photography", this.photography);
                startActivityForResult(intent, FILL_POLL_REQUEST_CODE);
            }else{
                launchActiveGPSConfirmDialog();
            }
        }
    }

    private void launchDatePickerDialog(){
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        LayoutInflater inflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View layout = inflater.inflate(R.layout.datepicker_layout,null);
        builder.setView(layout);
        final DatePicker datePicker = (DatePicker) layout.findViewById(R.id.datePicker);
        builder.setPositiveButton("Aceptar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                Calendar calendar = Calendar.getInstance();
                calendar.set(datePicker.getYear(), datePicker.getMonth(), datePicker.getDayOfMonth());
                String date = new SimpleDateFormat("yyyy-MM-dd").format(calendar.getTime());
                birthday.setText(date);
            }
        });
        builder.setNegativeButton("Cancelar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
        builder.create().show();
    }

    /**
     * manejador de eventos cuando una actividad es lanzada para esperar una respuesta de ella.
     * @param requestCode código que identifica la actividad lanzada
     * @param resultCode código que identica el resultado de la actividad (RESULT_OK o RESULT_CANCELED)
     * @param data datos que envia la actividad lanzada
     * @see #poblateFields(Person)
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(resultCode == RESULT_OK){
            switch (requestCode){
                case SCAN_CAMERA_REQUEST_CODE:
                    this.person = data.getParcelableExtra(ScanPersonActivity.PERSON_EXTRA);
                    if(this.person != null){
                        poblateFields(this.person);
                    }else{
                        Toast.makeText(this, "No se pudo leer ningún dato del código de barras", Toast.LENGTH_LONG).show();
                    }
                    break;
                case PHOTO_CAMERA_REQUEST_CODE:
                    this.photography = (Bitmap) data.getExtras().get("data");
                    this.photo.setImageBitmap(this.photography);
                    break;
                case FILL_POLL_REQUEST_CODE:
                    setResult(RESULT_OK);
                    finish();
                    break;
            }
        }
    }

    /**
     * llena todos los datos del formulario de datos basicos de la persona a encuestar
     * @param person objeto Person que contiene los datos basicos de la persona
     * @see Person
     */
    private void poblateFields(Person person){
        this.id.setText(String.valueOf(person.getId()));
        this.firstname1.setText(person.getFirstname1());
        this.firstname2.setText(person.getFisrtname2());
        this.lastname1.setText(person.getLastname1());
        this.lastname2.setText(person.getLastname2());

        String birthday = person.getBirthday()+"";

        if(birthday.length() == 8){
            String year,month,day;
            year = birthday.substring(0,4);
            month = birthday.substring(4,6);
            day = birthday.substring(6);
            this.birthday.setText(year+"-"+month+"-"+day);
        }

        switch (person.getGender()){
            case 'M':this.gender.setSelection(1);break;
            case 'F':this.gender.setSelection(2);break;
        }

        String bloodgroups[] = getResources().getStringArray(R.array.bloodgroup);
        for(int i = 0; i < bloodgroups.length; i++){
            if(bloodgroups[i].equals(person.getBloodgroup())){
                this.bloodgroup.setSelection(i);
                break;
            }
        }

        switch (person.getRH()){
            case '+':this.rh.setSelection(1);break;
            case '-':this.rh.setSelection(2);break;
        }
    }

    private boolean validateNumericCharacters(String value){
        return value.matches("(.*)[0-9](.*)");
    }

    private boolean validateAlphabeticalCharacters(String value){
        return value.matches("(.*)[A-Z a-z](.*)");
    }

    private boolean validateDateFormat(String date){
        return date.matches("(.*)[1-9][0-9][0-9][0-9]-[0-1][0-2]-[0-3][0-9](.*)");
    }

    /**
     * valida los campos no diligenciados del formulario de la actividad y determina si la persona ha sido encuestada anteriormente.
     * @return boolean true si los campos estan llenos y la persona aún no ha sido encuestads, false si no se cumplen las condiciones.
     * @see #findRespondent(long)
     * @see #showConfirmDialog()
     */
    private boolean validateFields(){
        EditText[] fields = new EditText[]{id,firstname1,firstname2,lastname1,lastname2, birthday};
        boolean isDone = true;
        boolean isValidate = true;
        this.person = new Person();
        for ( int i =0 ; i<fields.length; i++){
            EditText field = fields[i];
            if(field.getText().length() == 0){
                field.setHintTextColor(Color.RED);
                isDone = false;
            }else{
                switch (i){
                    case 0:
                        if(validateAlphabeticalCharacters(field.getText().toString())){
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }else {
                            this.person.setId(Long.parseLong(field.getText().toString()));
                        }
                        break;
                    case 1:
                        if(validateNumericCharacters(field.getText().toString())){
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }else {
                            this.person.setFirstname1(field.getText().toString());
                        }
                        break;
                    case 2:
                        if(validateNumericCharacters(field.getText().toString())){
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }else {
                            this.person.setFirstname2(field.getText().toString());
                        }
                        break;
                    case 3:
                        if(validateNumericCharacters(field.getText().toString())){
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }else {
                            this.person.setLastname1(field.getText().toString());
                        }
                        break;
                    case 4:
                        if(validateNumericCharacters(field.getText().toString())){
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }else {
                            this.person.setFirstname2(field.getText().toString());
                        }
                        break;
                    case 5:
                        if(validateDateFormat(field.getText().toString())) {
                            this.person.setBirthday(Long.parseLong(field.getText().toString().replace("-","")));
                        }else{
                            field.setTextColor(Color.RED);
                            isValidate = false;
                        }
                        break;
                }
            }
        }
        Spinner[] spinners = new Spinner[]{gender,bloodgroup,rh};
        for( int i = 0; i< spinners.length; i++){
            Spinner spinner = spinners[i];
            if(spinner.getSelectedItemPosition() == 0){
                isDone = false;
            }else{
                switch (i){
                    case 0:
                        switch (spinner.getSelectedItemPosition()){
                            case 1:this.person.setGender('M');break;
                            case 2:this.person.setGender('F');break;
                        };break;
                    case 1:person.setBloodgroup(spinner.getSelectedItem().toString());break;
                    case 2:
                        switch (spinner.getSelectedItemPosition()){
                            case 1:this.person.setRH('+');break;
                            case 2:this.person.setRH('-');break;
                        };break;
                }
            }
        }
        try {
            if (findRespondent(this.person.getId()) != null) {
                showConfirmDialog();
                return false;
            }
        }catch (JSONException ex){
            Toast.makeText(getApplicationContext(), "Error aquí: "+ex.getMessage(),Toast.LENGTH_LONG).show();
            return false;
        }catch (SQLiteException ex){
            Toast.makeText(getApplicationContext(), "Error: "+ex.getMessage(),Toast.LENGTH_LONG).show();
            return false;
        }

        if(!isValidate){
            Toast.makeText(this, "Algunos campos contienen dats erroneos",Toast.LENGTH_LONG).show();
            return isValidate;
        }

        if(isDone) {
            return isDone;
        }else{
            Toast.makeText(this, "Falta llenar campos", Toast.LENGTH_LONG).show();
            return isDone;
        }
    }

    /**
     * Muestra un dialogo de confirmación, anunciando al encuestador que la persona a encuestar ya ha sido encuestada anteriormente.
     * @see AlertDialog
     */
    private void showConfirmDialog(){
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Información");
        builder.setMessage("La persona identificada con CC. " + person.getId() + " fué encuestada anteriormente.\n¿Desea cancelar esta encuesta?");
        builder.setNegativeButton("No", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
        builder.setPositiveButton("Si", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                finish();
            }
        });
        builder.create().show();
    }

    /**
     * Obtiene un listado de todas las encuestas llenas almacenadas en la base de datos
     * @return ArrayList con todas las encuestas repondidas en el dispositivo
     * @throws SQLiteException es lanzada si ocurre algún error al momento de ejecutar la consulta a la base de datos
     * @see SqlHelper
     * @see ArrayList
     * @see Poll
     */
    private ArrayList<Poll> getAnswersPolls()throws SQLiteException{
        SqlHelper sql = new SqlHelper(getApplicationContext());
        sql.databaseName = "POLLDATA_DB";
        sql.OOCDB();
        sql.setQuery("CREATE TABLE IF NOT EXISTS 'poll' ( " +
                "'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " +
                "'idstructure' INTEGER NOT NULL, " +
                "'polldata' TEXT NOT NULL, " +
                "'savedate' TEXT NOT NULL, " +
                "'senddate' TEXT);");
        sql.execQuery();
        sql.setQuery(String.format("SELECT * FROM poll WHERE idstructure = %s", currentPoll.getStructureId()));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        ArrayList<Poll> polls = new ArrayList<>();
        for (int i = 0; i< cursor.getCount(); i++){
            Poll poll = new Poll();
            poll.setId(cursor.getInt(0));
            poll.setStructureId(cursor.getLong(1));
            poll.setContent(cursor.getString(2));
            poll.setSavedDate(cursor.getString(3));
            poll.setSentDate(cursor.getString(4));
            polls.add(poll);
            cursor.moveToNext();
        }
        return polls;
    }

    /**
     * Busca una encuesta entre el listado de enecuestas respondidas para determinar si la persona a encuestar ya ha sido encuestada
     * @param respondentId identificador de la persona encuestada
     * @return retorna la encuesta respondida o null si no se encontró ninguna encuesta relacionada.
     * @throws JSONException es lanzada si ocurre un error de conversión en el JSON de la estructura de la encuesta.
     * @see JSONObject
     * @see ArrayList
     * @see Poll
     * @see #getAnswersPolls()
     */
    private Poll findRespondent(long respondentId)throws JSONException{
        ArrayList<Poll> answersPolls = getAnswersPolls();
        for(Poll poll : answersPolls){
            JSONObject answers = new JSONObject(poll.getContent());
            long tmpRespondentId = answers.getJSONObject("DOCUMENTINFO").getLong("respondentid");
            if(tmpRespondentId == respondentId)
                return poll;
        }
        return null;
    }

    private void launchActiveGPSConfirmDialog(){
        AlertDialog d=com.miido.analiizoOBRAS.util.Dialog.confirm(this,"Activar GPS",
                "Esta aplicación requiere obtener su posición\n¿desea activar su dispositivo GPS?");
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
        IS_GSP_ENABLED = true;
    }

    @Override
    public void onProviderDisabled(String s) {
        launchActiveGPSConfirmDialog();
    }

    @Override
    public boolean onKey(View view, int i, KeyEvent keyEvent) {
        EditText editText = (EditText) view;
        editText.setTextColor(Color.BLACK);
        return false;
    }
}
