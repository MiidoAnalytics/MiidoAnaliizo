package com.miido.analiizoOBRAS;

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
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.util.Base64;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.analiizoOBRAS.mcompose.Constants;
import com.miido.analiizoOBRAS.mcompose.MasterTools;
import com.miido.analiizoOBRAS.model.Person;
import com.miido.analiizoOBRAS.model.Poll;
import com.miido.analiizoOBRAS.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import java.lang.reflect.Field;
import java.net.URISyntaxException;
import java.text.SimpleDateFormat;
import java.util.Date;

import io.socket.client.IO;
import io.socket.emitter.Emitter;

/**
 * Gestiona los estados de la encuesta, si está icompleta,modificada o pausada
 * @author Alvaro Salgado MIIDO S.A.S
 * @version 2.0
 */
public class Master extends Activity implements LocationListener{

    ArrayAdapter<String> adapter;
    AlertDialog.Builder ad;
    private SqlHelper sqlHelper;
    private ScrollView fSv;
    private ScrollView sv;
    private ScrollView hSv;
    private LinearLayout ll;
    private AutoCompleteTextView et;
    private MasterTools masterTools;
    private Constants constants;
    private ProgressDialog pd;
    private RelativeLayout masterRL;
    private LinearLayout[] llOptions;
    private ImageButton sOptions;
    private Button[] bOptions;
    private Boolean toggle;
    private LocationManager locationManager;
    private LocationListener locationListener;
    private Boolean i_c;
    private String id = "";
    private int action = 0;

    private int resume;

    private Poll currentPoll;
    private Person respondent;
    private Bitmap photography;
    private String INTERVIEWER_ID;
    private int POSITION;

    private boolean IS_GPS_ENABLED = false;

    private double lat = 0.0d;
    private double lng = 0.0d;
    private float accuracy = 0.0f;

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_master);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        Bundle bundle;
        Boolean resuming;

        this.currentPoll = getIntent().getParcelableExtra("poll");
        this.respondent = getIntent().getParcelableExtra("person");
        this.photography = getIntent().getParcelableExtra("photography");
        INTERVIEWER_ID = getIntent().getStringExtra("userId");
        POSITION = getIntent().getIntExtra(PollRecordActivity.POSITION_EXTRA, -1);

        i_c = false;

        locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, this);
        if(locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
            IS_GPS_ENABLED=true;
        }

        getLocationData();
        bundle = getIntent().getExtras();
        try {
            this.resume = bundle.getInt("resume");

            //resuming = true; //only for developer mode
            resuming = bundle.getBoolean("resuming");
            Log.e("RESUME",resuming+"");
        } catch (Exception e) {
            resuming = false;
        }
        sqlHelper = new SqlHelper(getApplicationContext());
        sqlHelper.OOCDB();
        this.constants = new Constants();
        try {
            setInstances();
            masterTools.setUserData(bundle.getString(constants.user_id), bundle.getString(constants.username));
            if (!(resuming)) {
                this.masterTools.setResuming(false);
                if (this.masterTools.init()) {
                    //Log.e("HomePrefix", MasterTools.cHomePrefix + "");
                    this.masterTools.cPersonPrefix = POSITION;
                    if(getIntent().getBooleanExtra(PollsActivity.NEW_POLL_EXTRA, false)) {
                        //createIntent(MasterTools.cHomePrefix + "." + Constants.home + "." + MasterTools.Constants.localFile_name, 0);
                        createIntent(masterTools.cPersonPrefix + "." + currentPoll.getStructureId() + ".PERSON" + "." + masterTools.constants.localFile_name, 1);
                    }else {
                        createIntent(masterTools.cPersonPrefix + "." + currentPoll.getStructureId() + ".PERSON" + "." + masterTools.constants.localFile_name, 1);
                    }
                    //createProgressDialog();
                    //createAlertDialogPropForSearch();
                    init();
                }
            } else {
                this.masterTools.setResuming(true);
                this.masterTools.setResumingPrefix(this.resume);
                init();
            }
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
        }
    }

    /**
     * obtiene la ubicación del dispositivo utilizando el LocationManager
     * @see LocationManager
     * @see LocationListener
     * @see JSONObject
     */
    private void getLocationData() {
        Log.i("gpsData", "Starting");
        locationListener = new LocationListener() {
            @Override
            public void onLocationChanged(final Location location) {
                Log.i("gpsData", "current accuracy: " + location.getAccuracy());
                if (Math.ceil(location.getAccuracy()) < 10) {
                    Log.i("gpsData", "Location defined with accuracy on 10 or less");
                    new Thread(new Runnable() {
                        @Override
                        public void run() {
                            do {
                                if (i_c) {
                                    JSONObject tmpFM = masterTools.getFormMaster();
                                    try {
                                        JSONObject locate = new JSONObject();
                                        locate.put(constants.latitude, location.getLatitude());
                                        locate.put(constants.longitude, location.getLongitude());
                                        locate.put(constants.accuracy, location.getAccuracy());
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.location, locate);
                                        masterTools.setFormMaster(tmpFM);
                                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                                            Log.i("gpsData", "location defined and saved");
                                            locationManager.removeUpdates(locationListener);
                                            break;
                                        } else {
                                            Log.i("gpsData", "location could not saved");
                                            try {
                                                Thread.sleep(2000);
                                            } catch (InterruptedException ie) {
                                                Log.i("gpsData", "InterruptedException::" + ie.getMessage());
                                                locationManager.removeUpdates(locationListener);
                                                break;
                                            }
                                        }
                                    } catch (Exception e) {
                                        Log.i("gpsData", "Error while saving data::" + e.getMessage());
                                        try {
                                            Thread.sleep(2000);
                                        } catch (InterruptedException ie) {
                                            Log.i("gpsData", "InterruptedException::" + ie.getMessage());
                                            locationManager.removeUpdates(locationListener);
                                            break;
                                        }
                                    }
                                } else {
                                    try {
                                        Thread.sleep(2000);
                                    } catch (InterruptedException ie) {
                                        Log.i("gpsData", "InterruptedException::" + ie.getMessage());
                                        break;
                                    }
                                }
                            } while (true);
                        }
                    }).start();
                }
            }

            @Override
            public void onStatusChanged(String provider, int status, Bundle extras) {
                Log.i("gpsData", "StatusChanged::" + status + "-----" + extras);
            }

            @Override
            public void onProviderEnabled(String provider) {
            }

            @Override
            public void onProviderDisabled(String provider) {
                close();
            }
        };
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, locationListener);
    }

    /**
     * muestra un dialogo de alerta para mostrarle al encuestador que se está haciendo la busqueda de un afiliado.
     */
    private void createAlertDialogPropForSearch() {
        showProgressDialog();
        Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                AlertDialogProp(1, constants._EM001, "");
            }
        }, 1000);
    }

    /**
     * Crea y Muestra un dialogo de progreso indicandole al usuario a que espere.
     */
    private void createProgressDialog() {
        this.pd = new ProgressDialog(this);
        this.pd.setMessage(this.constants._ADV013);
        this.pd.setCancelable(false);
        this.pd.setCanceledOnTouchOutside(false);
        this.pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                et.setAdapter(adapter);
                et.setDropDownHeight(300);
                et.setThreshold(1);
                ad.show();
            }
        });
    }

    /**
     * Muestra un dialogo de progreso ProgressDialog
     * @see ProgressDialog
     */
    private void showProgressDialog() {
        this.pd.show();
    }

    /**
     * Deja de mostrar el ProgressDialog
     * @see ProgressDialog
     */
    private void dismissProgressDialog() {
        this.pd.dismiss();
    }

    /**
     * Inicializa los componentes de la actividad
     * @see #createObjectsListener()
     * @see #setLayoutParams(TableLayout)
     * @see #createOptionsMenu()
     * @see #setProperties()
     * @see #setPaddingContent()
     * @see #AlertDialogProp(int, String, String)
     */
    private void init() {
        String info = "";
        TableLayout family;
        TableLayout person;
        TableLayout home;
        try {
            ll.removeAllViews();
            info = "propierties";
            setProperties();
            info = "padding";
            setPaddingContent();
            info = "Orientation";
            ll.setOrientation(LinearLayout.VERTICAL);
            info = "init";
            if (this.masterTools.init()) {
                info = "family";
                family = this.masterTools.FamilyDescription();
                setLayoutParams(family);
                this.fSv.addView(family);
                info = "person";
                person = this.masterTools.PersonDescription(this.id, this.action);
                if(!masterTools.duplicated) {
                    info = "addperson";
                    this.ll.addView(person);
                    this.sv.addView(ll);
                    info = "home";
                    home = this.masterTools.HomeDescription();
                    setLayoutParams(home);
                    this.hSv.addView(home);
                    //LinearLayout.LayoutParams llP = new ActionMenuView.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                    //llP.setMargins(0,0,0,150);
                    //home.setLayoutParams(llP);
                    createObjectsListener();
                    createOptionsMenu();
                } else {
                    fSv.removeAllViews();
                    sv.removeAllViews();
                    hSv.removeAllViews();
                    AlertDialogProp(4, constants.atention, constants._ADV027);
                }
            }
            i_c = true;
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /**
     * crea un menú de opciones para las preguntas de selección multiple.
     * @see #createHandlerForOptions()
     */
    private void createOptionsMenu() {
        Log.w("width", getResources().getDisplayMetrics().widthPixels+"");
        Log.w("height", getResources().getDisplayMetrics().heightPixels+"");
        masterRL = (RelativeLayout) findViewById(R.id.masterRL);
        llOptions = new LinearLayout[5];
        sOptions = new ImageButton(getApplicationContext());
        bOptions = new Button[3];
        TextView tv = new TextView(getApplicationContext());
        toggle = false;

        //SettingObjects
        sOptions.setImageResource(R.drawable.options);
        sOptions.setRotation(-90);
        sOptions.setBackgroundResource(R.drawable.button_options);

        tv.setText(constants.oButton);
        tv.setTextColor(Color.BLACK);
        tv.setWidth(150);
        tv.setGravity(Gravity.CENTER_HORIZONTAL);

        //CreatingOptionsMenu
        llOptions[0] = new LinearLayout(getApplicationContext());
        llOptions[0].setOrientation(LinearLayout.VERTICAL);
        llOptions[0].setGravity(Gravity.CENTER_HORIZONTAL);

        llOptions[1] = new LinearLayout(getApplicationContext());
        llOptions[1].setOrientation(LinearLayout.VERTICAL);
        llOptions[1].setGravity(Gravity.CENTER_HORIZONTAL);

        llOptions[2] = new LinearLayout(getApplicationContext());
        llOptions[2].setOrientation(LinearLayout.HORIZONTAL);
        llOptions[2].setPadding(0, 0, 0, 5);
        llOptions[2].setAlpha(0);
        llOptions[2].setX(150);

        llOptions[3] = new LinearLayout(getApplicationContext());
        llOptions[3].setOrientation(LinearLayout.HORIZONTAL);
        llOptions[3].setPadding(0, 0, 0, 5);
        llOptions[3].setAlpha(0);
        llOptions[3].setX(150);

        llOptions[4] = new LinearLayout(getApplicationContext());
        llOptions[4].setOrientation(LinearLayout.HORIZONTAL);
        llOptions[4].setPadding(0, 0, 0, 5);
        llOptions[4].setAlpha(0);
        llOptions[4].setX(150);

        bOptions[0] = new Button(getApplicationContext());
        bOptions[0].setText(constants.fButton);
        bOptions[0].setTextColor(Color.BLACK);
        bOptions[0].setBackgroundResource(R.drawable.button);
        bOptions[0].setWidth(constants.buttonWidth);

        bOptions[1] = new Button(getApplicationContext());
        bOptions[1].setText(constants.pButton);
        bOptions[1].setTextColor(Color.BLACK);
        bOptions[1].setBackgroundResource(R.drawable.button);
        bOptions[1].setWidth(constants.buttonWidth);


        bOptions[2] = new Button(getApplicationContext());
        bOptions[2].setText(constants.rButton);
        bOptions[2].setTextColor(Color.BLACK);
        bOptions[2].setBackgroundResource(R.drawable.button);
        bOptions[2].setWidth(constants.buttonWidth);

        llOptions[1].addView(sOptions);
        llOptions[1].addView(tv);
        llOptions[2].addView(bOptions[0]);
        llOptions[3].addView(bOptions[1]);
        llOptions[4].addView(bOptions[2]);
        llOptions[0].addView(llOptions[1]);
        llOptions[0].addView(llOptions[2]);
        llOptions[0].addView(llOptions[3]);
        llOptions[0].addView(llOptions[4]);

        //AddButtonToStage
        masterRL.addView(llOptions[0]);
        llOptions[0].setX(getResources().getDisplayMetrics().widthPixels - (constants.buttonWidth+50));
        llOptions[0].setY(getResources().getDisplayMetrics().heightPixels - 130);

        createHandlerForOptions();
    }

    /**
     * crea un evento que se desencadenará cuando se responda o se seleccione una opción en particular
     * @see #AlertDialogProp(int, String, String)
     * @see JSONObject
     * @see MasterTools
     * @see #slideToggle()
     * @see #validatePausedPersons()
     * @see #validateIfTemp()
     */
    @SuppressWarnings("All")
    private void createHandlerForOptions() {
        sOptions.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                slideToggle();
            }
        });
        bOptions[0].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    try {
                        JSONObject jo = masterTools.getHomeMaster();
                        //Log.e("texto", jo.toString());
                        Log.e("Home", jo.length() + "");
                        if (jo.length() < 21) {
                            AlertDialogProp(2, constants.atention, constants._ADV023);
                        } else {
                            if ((validatePausedPersons())) {
                                AlertDialogProp(2, constants.atention, constants._ADV014);
                            } else if (validateIfTemp()) {
                                AlertDialogProp(2, constants.atention, constants._ADV017);
                            } else {
                                //increment homprefix
                                if (masterTools.getObjects().length >= 2) {
                                    JSONObject tmpFM = masterTools.getFormMaster();
                                    String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                                    try {
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.finished, currentDateandTime);
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedStructureVersion, masterTools.structure.documentInfo[0]);
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedAppVersion, constants.version_name + "." + constants.version_subname);
                                        try {
                                            masterTools.setFormMaster(tmpFM);
                                            if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                                                masterTools.cHomePrefix++;
                                                if (masterTools.sPrefixes()) {
                                                    finish();
                                                } else {
                                                    AlertDialogProp(2, constants.error, constants._ADV007);
                                                }
                                            }
                                        } catch (Exception e) {
                                            AlertDialogProp(2, constants.error, e.getMessage());
                                        }
                                    } catch (Exception e) {
                                        AlertDialogProp(2, constants.error, e.getMessage());
                                    }
                                } else {
                                    AlertDialogProp(2, constants.atention, constants._ADV017);
                                }
                            }
                       }
                    } catch (NullPointerException ne) {
                        AlertDialogProp(2, constants.atention, constants._ADV023);
                    }
                } catch (Exception e) {
                    AlertDialogProp(2, constants.error, e.getMessage());
                }
            }
        });
        bOptions[1].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    //AlertDialogProp(3, Constants.atention, Constants._ADV016);
                    if ((masterTools.getFoundedPersons() == 0) && (validateIfTemp())) {
                        Log.e("getfoundedpersons", masterTools.getFoundedPersons()+"");
                        AlertDialogProp(2, constants.atention, constants._ADV017);
                    } else if (validatePausedPersons()) {
                        AlertDialogProp(3, constants.atention, constants._ADV016);
                    } else {
                        if (masterTools.getObjects().length > 2) {
                            AlertDialogProp(2, constants.error, constants._ADV015);
                        } else {
                            AlertDialogProp(2, constants.atention, constants._ADV017);
                        }
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    //Toast.makeText(getApplicationContext(), e.getMessage(), Toast.LENGTH_LONG).show();
                }
            }
        });
        bOptions[2].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    if (validatePausedPersons()) {
                        AlertDialogProp(2, constants.error, constants._ADV015);
                    } else {
                        if (masterTools.getFoundedPersons() != 0) {
                            AlertDialogProp(2, constants.atention, constants._ADV018);
                        } else {
                            finish();
                        }
                    }
                } catch (Exception e) {
                    AlertDialogProp(2, constants.error, e.getMessage());
                }
            }
        });
    }

    /**
     * Este método es llamado cuando se pulsa el boton Atras del dispositivo
     */
    @Override
    public void onBackPressed() {

    }

    /**
     * valida si algunas personas en la encuesta han sido pausadas
     * @return true si la alguna persona en la encuesta ha sido pausada
     * @throws Exception es lanzada si ocurre algún error en la verificación de la encuesta.
     * @see MasterTools
     */
    private Boolean validatePausedPersons() throws Exception {
        Object[][] objects = masterTools.getObjects();
        try {
            for (Object[] tmp : objects) {
                if ((tmp[2]).equals("1")) {
                    return true;
                }
            }
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
            return true;
        }
        return false;
    }

    /**
     * valida si la encuesta ha sido pausada.
     * @return true si la encuesta ha sido pausada, false en caso contrario
     * @throws Exception es lanzada si ocurre algún error en la verificación de la encuesta.
     * @see MasterTools
     */
    private Boolean validateIfTemp() throws Exception {
        Object[][] objects = masterTools.getObjects();
        try {
            for (Object[] tmp : objects) {
                if ((tmp[2]).equals("3")) {
                    return true;
                }
            }
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
            return true;
        }
        return false;
    }

    /**
     * crea una animación y cuando se selecciona una opción o para desplegar una pregunta que se deriva de otra.
     */
    private void slideToggle() {
        if (!toggle) {
            sOptions.animate().setDuration(1100).rotation(90).start();
            llOptions[0].animate().translationY(getResources().getDisplayMetrics().heightPixels - ((bOptions[0].getHeight()*3)+85+40)).setDuration(1000).start();
            llOptions[2].animate().alpha(1).setDuration(1100).start();
            llOptions[3].animate().alpha(1).setDuration(1100).start();
            llOptions[4].animate().alpha(1).setDuration(1100).start();
            llOptions[2].animate().translationX(0).setDuration(1000).start();
            llOptions[3].animate().translationX(0).setDuration(1250).start();
            llOptions[4].animate().translationX(0).setDuration(1500).start();
            toggle = true;
        } else {
            sOptions.animate().setDuration(550).rotation(-90).start();
            llOptions[0].animate().translationY(getResources().getDisplayMetrics().heightPixels - 130).setDuration(500).start();
            llOptions[2].animate().alpha(0).setDuration(475).start();
            llOptions[3].animate().alpha(0).setDuration(475).start();
            llOptions[4].animate().alpha(0).setDuration(475).start();
            llOptions[2].animate().translationX(150).setDuration(700).start();
            llOptions[3].animate().translationX(150).setDuration(1000).start();
            llOptions[4].animate().translationX(150).setDuration(1300).start();
            toggle = false;
        }
    }

    /**
     * crea un evento para un objeto u opción
     * @see #createHandler(Object, String, int)
     * @see #AlertDialogProp(int, String, String)
     * @see MasterTools
     */
    private void createObjectsListener() {
        Object object;
        object = this.masterTools.getObject();
        try {
            //Home
            //createHandler(object, MasterTools.cHomePrefix + "." + Constants.home + "." + MasterTools.Constants.localFile_name, 0);
            createHandler(masterTools.getHome(), masterTools.cHomePrefix + "." + constants.home + "." + masterTools.constants.localFile_name, 2);
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
        }
        Object[][] objects;
        objects = this.masterTools.getObjects();
        for (Object[] object_tmp : objects) {
            try {
                //Persons 3rd part
                createHandler(object_tmp[0], ((String) object_tmp[1]), 1);
            } catch (Exception e) {
                /* (+) deshablilitando error */
                //AlertDialogProp(2, Constants.error, e.getMessage());
                Log.e("Error deshabilitado", e.getMessage());
                /*(+)*/
            }
        }
    }

    /**
     * crea un controlador de eventos para un componente de la encuesta.
     * @param object vista View que genera la llamada al evento
     * @param target identificador del componente
     * @param event tipo de evento
     * @throws Exception es lanazada si algo va mal con la creación del controlador
     * @see #createIntent(String, int)
     * @see #createProgressDialog()
     * @see #createAlertDialogPropForSearch()
     */
    private void createHandler(Object object, final String target, final int event) throws Exception {
        if ((event == 0) || (event == 2)) {
            ((TableLayout) object).setContentDescription(target);
            ((TableLayout) object).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    createIntent(v.getContentDescription() + "", event);
                }
            });
        } else {
            ((LinearLayout) object).setContentDescription(target);
            ((LinearLayout) object).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if ((masterTools.cPersonPrefix + ".PERSON" + "." + masterTools.constants.localFile_name).equals(v.getContentDescription())) {
                        createIntent(v.getContentDescription() + "", event);
                        //fSv.removeAllViews();
                        //sv.removeAllViews();
                        //hSv.removeAllViews();
                        //masterRL.removeView(llOptions[0]);
                        //createProgressDialog();
                        //createAlertDialogPropForSearch();
                    } else {
                        createIntent(v.getContentDescription() + "", event);
                    }
                }
            });
        }
    }

    /**
     * establece los parametros de ubicación Layout de la vista
     * @param tl_tmp TableLayout en donde estan contenidos los componentes de la encuesta.
     */
    private void setLayoutParams(TableLayout tl_tmp) {
        tl_tmp.setBackgroundResource(R.drawable.layoutlv);
    }

    /**
     * establece los parametros de margen de los ScrollView
     */
    private void setPaddingContent() {
        this.fSv.setPadding(10, 10, 10, 10);
        this.sv.setPadding(10, 10, 10, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 10, 120, 10);
    }

    /**
     * define las instancias de los atributos desde sus respectivos archivos layouts
     * @throws Exception es lanzada si ocurre un error al instanciar los atributos.
     * @see MasterTools
     */
    private void setInstances() throws Exception {
        this.fSv = ((ScrollView) findViewById(R.id.familyScroll));
        this.sv = ((ScrollView) findViewById(R.id.parentScrollView));
        this.hSv = ((ScrollView) findViewById(R.id.homeScrollView));
        this.ll = new LinearLayout(getApplicationContext());
        this.masterTools = new MasterTools(getApplicationContext(),this.currentPoll.getStructureId());
        this.pd = new ProgressDialog(this);
    }

    /**
     * crea un Intent y lanza la actividad Main con información encapsulada en los Extras
     * @param target indica si la encuesta se va a reanudar.
     * @param event parámetro de la encuesta a reanudar
     */
    private void createIntent(String target, int event) {
        try {
            Intent i = new Intent(this.getApplicationContext(), Main.class);
            try {
                i.removeExtra(constants.target);
                i.removeExtra(constants.case_id);
                i.removeExtra(PollRecordActivity.POSITION_EXTRA);
                i.removeExtra(Main.TITLE_EXTRA);
                i.removeExtra(PollRecordActivity.WAS_SENT);
            } catch (Exception e2) {
                e2.printStackTrace();
            }
            i.putExtra(constants.target, target);
            i.putExtra(constants.case_id, event);
            i.putExtra("structureid", this.currentPoll.getStructureId());
            i.putExtra(PollRecordActivity.POSITION_EXTRA, POSITION);
            i.putExtra(PollRecordActivity.WAS_SENT,getIntent().getBooleanExtra(PollRecordActivity.WAS_SENT, false));
            i.putExtra(Main.TITLE_EXTRA, currentPoll.getTitle());
            this.startActivityForResult(i, 1);
        } catch (Exception e) {
            e.printStackTrace();
            //Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG).show();
        }
    }

    /**
     * habilita el scroll vertical.
     */
    private void setProperties() {
        this.sv.setVerticalScrollBarEnabled(true);
    }

    private JSONObject getPollResponse()throws Exception{
        JSONArray person = masterTools.getFormMaster().getJSONArray("PERSON");
        StringBuilder buffer = new StringBuilder();
        if(person.length()>0){
            String personFileName = person.getString(person.length() - 2);
            BufferedReader reader = new BufferedReader(new InputStreamReader(openFileInput(personFileName)));
            String line = "";
            while((line = reader.readLine()) != null){
                buffer.append(line);
            }
            reader.close();
        }
        JSONObject response = new JSONObject("{}");
        response.put("HOME", new JSONObject(buffer.toString()));
        response.put("PERSON", new JSONArray("[]"));
        response.put("DOCUMENTINFO", masterTools.getFormMaster().getJSONObject("DOCUMENTINFO"));
        return response;
    }

    private void updatePollStructureFromDB(String pollStructure) throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        sql.databaseName = "POLLDATA_DB";
        sql.OOCDB();
        String saveDate = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
        sql.setQuery(String.format("UPDATE poll SET polldata = '%s', savedate = '%s' WHERE id = %s ;", pollStructure, saveDate, POSITION));
        sql.execUpdate();
        Toast.makeText(this, "Información actualizada con exito",Toast.LENGTH_LONG).show();
    }

    private void launchQuitConfirmDialog(){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.confirm(this, "Confirmación", "Este informe ya ha sido enviado.\n" +
                "¿Desea salir del informe");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                setResult(RESULT_CANCELED, null);
                finish();
            }
        });
    }

    /**
     * Método que que se ejecuta cuando una actividad lanzada con #startActivityForResult,la actividad es terminada y se captura su resultado
     * @param requestCode identificador de la actividad que se lanzó
     * @param resultCode codigo de la respuesta RESULT_OK o RESULT_CACELED
     * @param data intent con la información proporcionada por la actividad finalizada.
     * @see #storePollToDB(String)
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                int result = data.getIntExtra(constants.result, 0);
                if (result == 1) {
                    try {
                        try {
                            masterTools.personMaster.length();
                        } catch (Exception e2) {
                            masterTools.personMaster = new JSONArray();
                        }
                        String[] target = data.getStringExtra(constants.target).split("~");
                        if (target.length > 2) {
                            this.id = "";
                        }
                        masterTools.personMaster.put(target[0]);
                        if (masterTools.personMaster.length() % 2 != 0) {
                            masterTools.personMaster.put(false);
                        }

                        //(+) agregado para guardar en el resultado de la encuesta el nombre del informe
                        //Log.e("RESPONSE", data.getStringExtra(Main.RESPONSE_EXTRA));
                        /*JSONObject report = MasterTools.getHOME();
                        Iterator<String> keys = report.keys();
                        String key = null,value = null;
                        if(keys.hasNext()){
                            key = keys.next();
                            value = report.getString(key);
                        }*/
                        //Log.e("REPORT", MasterTools.getHOME().toString());
                        //(+)

                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                            /* (+)Finaliza la encuesta y omite la parte de seguir agregando personas */
                            try {
                                JSONObject poll = new JSONObject("{}");
                                poll.put("HOME",new JSONObject(data.getStringExtra(Main.RESPONSE_EXTRA)));
                                poll.put("PERSON", new JSONArray("[]"));
                                //try {
                                //  poll.put("DOCUMENTINFO", MasterTools.getFormMaster().getJSONObject("DOCUMENTINFO"));
                                //}catch (JSONException ex){
                                poll.put(constants.doc_info, new JSONObject());
                                //}
                                //JSONObject poll = getPollResponse();

                                /*if(key != null && value !=null){
                                    poll.getJSONObject("HOME").put(key, value);
                                }*/

                                String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                                poll.getJSONObject(constants.doc_info).put("Created",currentDateandTime);
                                poll.getJSONObject(constants.doc_info).put(constants.finished, currentDateandTime);
                                poll.getJSONObject(constants.doc_info).put("projectid", this.currentPoll.getProjectId());
                                poll.getJSONObject(constants.doc_info).put("projectname", this.currentPoll.getProjectName());
                                poll.getJSONObject(constants.doc_info).put("clientid", this.currentPoll.getClientId());
                                poll.getJSONObject(constants.doc_info).put("structureid", this.currentPoll.getStructureId());
                                poll.getJSONObject(constants.doc_info).put("interviewerid", INTERVIEWER_ID);
                                poll.getJSONObject(constants.doc_info).put("Lat",this.lat);
                                poll.getJSONObject(constants.doc_info).put("Lng",this.lng);
                                poll.getJSONObject(constants.doc_info).put("Accuracy",this.accuracy);

                                String response = poll.toString();
                                //Log.e("response", response);
                                if(getIntent().getBooleanExtra(PollsActivity.NEW_POLL_EXTRA, false)) {
                                    storePollToDB(response);
                                    updateDataPausedFromDB(currentPoll.getStructureId());
                                    Log.e(getClass().getName(), "Encuesta guardada");
                                }else{
                                    updatePollStructureFromDB(poll.toString());
                                    Log.e(getClass().getName(), "Encuesta Actualizada");
                                }
                            }catch (Exception ex){
                                Log.e("ErrorRespuestaEncuesta", ex.getMessage());
                            }
                            setResult(RESULT_OK);
                            finish();
                            /* (+) */
                            if (masterTools.closePersonForm()) {
                                fSv.removeAllViews();
                                sv.removeAllViews();
                                hSv.removeAllViews();
                                masterRL.removeView(llOptions[0]);
                                init();
                            }
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                        //Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG).show();
                    }
                } else if (result == 2) {
                    if (!(data.getBooleanExtra(constants.open, false))) {
                        Boolean f = false;
                        try {
                            String[] target = data.getStringExtra(constants.target).split("~");
                            if (target.length > 2) {
                                this.id = "";
                            }
                            //(+) agregado para guardar en el resultado de la encuesta el nombre del informe
                            //Log.e("RESPONSE", data.getStringExtra(Main.RESPONSE_EXTRA));
                            /*JSONObject report = MasterTools.getHOME();
                            Iterator<String> keys = report.keys();
                            String key = null,value = null;
                            if(keys.hasNext()){
                                key = keys.next();
                                value = report.getString(key);
                            }*/
                            //Log.e("REPORT", MasterTools.getHOME().toString());
                            //(+)

                            /* (+)Finaliza la encuesta y omite la parte de seguir agregando personas */
                            //Log.e("Respuesta", MasterTools.getFormMaster().toString());
                            try {
                                JSONObject poll = new JSONObject("{}");
                                poll.put("HOME",new JSONObject(data.getStringExtra(Main.RESPONSE_EXTRA)));
                                poll.put("PERSON", new JSONArray("[]"));
                                //try {
                                  //  poll.put("DOCUMENTINFO", MasterTools.getFormMaster().getJSONObject("DOCUMENTINFO"));
                                //}catch (JSONException ex){
                                    poll.put(constants.doc_info, new JSONObject());
                                //}
                                //JSONObject poll = getPollResponse();

                                /*if(key != null && value !=null){
                                    poll.getJSONObject("HOME").put(key, value);
                                }*/

                                String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                                poll.getJSONObject(constants.doc_info).put("Created",currentDateandTime);
                                poll.getJSONObject(constants.doc_info).put(constants.finished, currentDateandTime);
                                poll.getJSONObject(constants.doc_info).put("projectid", this.currentPoll.getProjectId());
                                poll.getJSONObject(constants.doc_info).put("projectname", this.currentPoll.getProjectName());
                                poll.getJSONObject(constants.doc_info).put("clientid", this.currentPoll.getClientId());
                                poll.getJSONObject(constants.doc_info).put("structureid", this.currentPoll.getStructureId());
                                poll.getJSONObject(constants.doc_info).put("interviewerid", this.currentPoll.getInterviewerId());
                                poll.getJSONObject(constants.doc_info).put("Lat",this.lat);
                                poll.getJSONObject(constants.doc_info).put("Lng",this.lng);
                                poll.getJSONObject(constants.doc_info).put("Accuracy",this.accuracy);

                                String response = poll.toString();
                                //Log.e("response", response);
                                if(getIntent().getBooleanExtra(PollsActivity.NEW_POLL_EXTRA, false)) {
                                    storePollToDB(response);
                                    updateDataPausedFromDB(currentPoll.getStructureId());
                                    Log.e(getClass().getName(), "Encuesta guardada");
                                }else{
                                    if(!getIntent().getBooleanExtra(PollRecordActivity.WAS_SENT, false)) {
                                        updatePollStructureFromDB(poll.toString());
                                        Log.e(getClass().getName(), "Encuesta Actualizada");
                                    }
                                }
                            }catch (Exception ex){
                                Log.e("ErrorRespuestaEncuesta", ex.getMessage());
                            }
                            setResult(RESULT_OK);
                            finish();
                             /* (+) */

                           /* for (int c = 0; c < MasterTools.personMaster.length(); c++) {
                                try {
                                    String person = MasterTools.personMaster.getString(c);
                                    if (person.length() > 0) {
                                        if (target[0].equals(person)) {
                                            f = true;
                                            MasterTools.personMaster.put(c + 1, false);
                                            if (MasterTools.wLocalFile(MasterTools.cHomePrefix + "." + Constants.home, 1)) {

                                                *//* (+)Finaliza la encuesta y omite la parte de seguir agregando personas *//*
                                                //Log.e("Respuesta", MasterTools.getFormMaster().toString());
                                                *//*try {
                                                    JSONObject poll = getPollResponse();
                                                    String currentDateandTime = new SimpleDateFormat(Constants.SimpleDateFormat).format(new Date());

                                                    poll.getJSONObject(Constants.doc_info).put(Constants.finished, currentDateandTime);
                                                    poll.getJSONObject(Constants.doc_info).put("projectid", this.currentPoll.getProjectId());
                                                    poll.getJSONObject(Constants.doc_info).put("projectname",this.currentPoll.getProjectName());
                                                    poll.getJSONObject(Constants.doc_info).put("clientid",this.currentPoll.getClientId());
                                                    poll.getJSONObject(Constants.doc_info).put("structureid", this.currentPoll.getStructureId());
                                                    poll.getJSONObject(Constants.doc_info).put("interviewerid", INTERVIEWER_ID);
                                                    storePollToDB(poll.toString());
                                                }catch (Exception ex){
                                                    Log.e("ErrorRespuestaEncuesta",ex.getMessage());
                                                }*//*
                                                setResult(RESULT_OK);
                                                finish();
                                                *//* (+) *//*

                                                MasterTools.closePersonForm();
                                            }
                                        }
                                    }
                                } catch (Exception e) {
                                    AlertDialogProp(2, Constants.error, e.getMessage());
                                }
                            }
                            if (!(f)) {
                                MasterTools.personMaster.put(target[0]);
                                MasterTools.personMaster.put(false);
                                if (MasterTools.wLocalFile(MasterTools.cHomePrefix + "." + Constants.home, 1)) {
                                    MasterTools.closePersonForm();
                                }
                            }*/
                        } catch (Exception e) {
                            AlertDialogProp(2, constants.error, e.getMessage());
                        }
                    }
                    fSv.removeAllViews();
                    sv.removeAllViews();
                    hSv.removeAllViews();
                    masterRL.removeView(llOptions[0]);
                    init();
                } else if (result == 0) {
                    fSv.removeAllViews();
                    sv.removeAllViews();
                    hSv.removeAllViews();
                    masterRL.removeView(llOptions[0]);
                    init();
                } else if ((result == 4) ||(result == 3)) {
                    Log.e("caseBack", "BackToParentOnHome");
                    createIntent(masterTools.cPersonPrefix + ".PERSON" + "." + masterTools.constants.localFile_name,1);
                } else if (result == 5) {
                    //init();
                    try {
                        this.masterTools.init();
                        this.masterTools.FamilyDescription();
                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                            final JSONObject tmpFM = masterTools.getFormMaster();
                            String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());

                            try {
                                tmpFM.getJSONObject(constants.doc_info).put(constants.finished, currentDateandTime);
                                tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedStructureVersion, masterTools.structure.jStructure.getJSONObject("Document_info").getString("structureVersion"));
                                tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedAppVersion, constants.version_name + "." + constants.version_subname);
                                tmpFM.getJSONObject(constants.doc_info).put("projectid",this.currentPoll.getProjectId());
                                tmpFM.getJSONObject(constants.doc_info).put("projectname",this.currentPoll.getProjectName());
                                tmpFM.getJSONObject(constants.doc_info).put("clientid",this.currentPoll.getClientId());
                                tmpFM.getJSONObject(constants.doc_info).put("interviewerid", INTERVIEWER_ID);
                                if(respondent != null) {
                                    tmpFM.getJSONObject(constants.doc_info).put("respondentid", this.respondent.getId());
                                }
                                tmpFM.getJSONObject(constants.doc_info).put("structureid", this.currentPoll.getStructureId());
                                if(this.photography!=null){
                                    image();
                                }
                                try {
                                    masterTools.setFormMaster(tmpFM);
                                    if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                                        masterTools.cHomePrefix++;
                                        if (masterTools.sPrefixes()) {
                                            try{
                                                storePollToDB(tmpFM.toString());
                                            }catch (SQLiteException ex){
                                                Toast.makeText(this,"Ocurrio un error al guardar la encuesta",Toast.LENGTH_LONG).show();
                                            }
                                            setResult(RESULT_OK);
                                            finish();
                                        } else {
                                            AlertDialogProp(2, constants.error, constants._ADV007);
                                        }
                                    }
                                } catch (Exception e) {
                                    AlertDialogProp(2, constants.error, e.getMessage());
                                }
                            } catch (Exception e) {
                                AlertDialogProp(2, constants.error, e.getMessage() + ".Error");
                            }
                        }
                    } catch (Exception er){ Log.e("Error aquii", er.getMessage()); }
                } else {
                    Toast.makeText(this, "Error al guardar la informacion "+ result, Toast.LENGTH_LONG).show();
                }
            } else if (resultCode == RESULT_CANCELED && data!= null) {
                if ((data.getBooleanExtra(constants.open, true))) {
                    String info = "";
                    try {
                        try {
                            info = "personMasterLength";
                            masterTools.personMaster.length();
                        } catch (Exception e2) {
                            info = "newPersonMaster";
                            masterTools.personMaster = new JSONArray();
                        }
                        info = "personMasterPut";
                        String[] target = data.getStringExtra(constants.target).split("~");
                        if (target.length > 2) {
                            this.id = "";
                        }

                        Boolean f = false;
                        try {
                            for (int c = 0; c < masterTools.personMaster.length(); c++) {
                                try {
                                    String person = masterTools.personMaster.getString(c);
                                    if (person.length() > 0) {
                                        if (target[0].equals(person)) {
                                            f = true;
                                            masterTools.personMaster.put(c + 1, true);
                                        }
                                    }
                                } catch (Exception e) {
                                    AlertDialogProp(2, constants.error, e.getMessage());
                                }
                            }
                            if (!(f)) {
                                masterTools.personMaster.put(target[0]);
                                masterTools.personMaster.put(true);
                            }
                        } catch (Exception e) {
                            AlertDialogProp(2, constants.error, e.getMessage());
                        }

                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                            info = "closePersonForm";
                            if (masterTools.closePersonForm()) {
                                fSv.removeAllViews();
                                sv.removeAllViews();
                                hSv.removeAllViews();
                                masterRL.removeView(llOptions[0]);
                                init();
                            }
                        }
                    } catch (Exception e) {
                        //Toast.makeText(this, info + "\n" + e.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }
            }else{
                finish();
            }
        }
    }

    public void image(){
        try {
            File outDir = this.getCacheDir();
            File outFile = File.createTempFile("img_", ".png", outDir);
            //FileOutputStream out = new FileOutputStream(outFile);
            ByteArrayOutputStream out = new ByteArrayOutputStream();
            //this.photography.compress(Bitmap.CompressFormat.PNG, 100, out);
            Bitmap bitmap = BitmapFactory.decodeResource(getResources(), R.drawable.ic_camera);
            String encodeString = Base64.encodeToString(out.toByteArray(), Base64.DEFAULT);
            out.close();
            try {
                io.socket.client.Socket socket = IO.socket(LoginActivity.SOCKET_SERVER_HOST);
                socket.on(io.socket.client.Socket.EVENT_CONNECT, new Emitter.Listener() {
                    @Override
                    public void call(Object... args) {
                        Log.e(this.getClass().getName(), "Socket: Conexión satisfactoria");
                    }
                });
                socket.connect();
                //socket.emit("pollresource",outFile,"{name: prueba.png}");
                socket.emit("prueba", "hola");
            }catch (URISyntaxException ex){
                Log.e(this.getClass().getName(), ex.getMessage());
            }
            //return encodeString;
            //StringBuilder buffer = new StringBuilder();
            //BufferedReader bufferedReader = new BufferedReader(new FileReader(outFile));
            //String line = "";
            //while((line = bufferedReader.readLine()) != null){
              //  buffer.append(line);
            //}
            //bufferedReader.close();
            //return buffer.toString();
        }catch (IOException ex){
            Log.e("Error de I/O", ex.getMessage());
            //return null;
        }
    }

    public void storeImageToDB(long pollid, String path)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        sql.databaseName = "POLL_RESOURCES_DB";
        sql.OOCDB();
        sql.setQuery("CREATE TABLE IF NOT EXISTS 'pollresources' (" +
                        "'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " +
                        "'pollid' INTEGER NOT NULL, " +
                        "'mimetype' TEXT NOT NULL, " +
                        "'path' TEXT NOT NULL, " +
                        "'status' INTEGER NOT NULL, " +
                        "'savedate' TEXT NOT NULL, " +
                        "'senddate' TEXT);"
        );
        sql.execQuery();
        String saveDate = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
        sql.setQuery(String.format("INSERT INTO pollresources VALUES(null,%s,'%s','%s',%s,'%s',null);"));
    }

    /**
     * Inserta los datos de le encuesta respondida en la base de datos local
     * @param pollDataStructure número identificador de la estructura de la encuesta.
     * @throws SQLiteException es lanzada si ocurre algún error en la ejecución de la consulta.
     */
    private int storePollToDB(String pollDataStructure)throws SQLiteException{
        SqlHelper sqlHelper = new SqlHelper(getApplicationContext());
        sqlHelper.databaseName = "POLLDATA_DB";
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_POLL_DATA_TABLE_QUERY);
        sqlHelper.execQuery();
        String saveDate = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
        sqlHelper.setQuery(String.format("INSERT INTO poll VALUES(null,%s,'%s','%s',null);", this.currentPoll.getStructureId(), pollDataStructure, saveDate));
        sqlHelper.execInsert();

        sqlHelper.setQuery("SELECT id FROM poll ORDER BY id DESC LIMIT 1;");
        int lastId = 0;
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        if(cursor.getCount()>0){
            lastId = cursor.getInt(0);
        }
        sqlHelper.close();
        Toast.makeText(this,"Información Guardada con exito",Toast.LENGTH_LONG).show();
        return lastId;
    }

    private void updateDataPausedFromDB(long structureid) throws SQLiteException{
        sqlHelper.databaseName = "POLLDATA_DB";
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_DATA_PAUSED_TABLE_QUERY);
        sqlHelper.execQuery();
        sqlHelper.setQuery(String.format(constants.UPDATE_DATA_PAUSED_QUERY, structureid));
        sqlHelper.execUpdate();
    }

    /**
     * Establece un campo cursor.
     * @param object vista
     * @return la vista
     */
    private View setCursorDrawable(View object) {
        try {
            Field f = TextView.class.getDeclaredField(constants.mCursorDrawableRes);
            f.setAccessible(true);
            f.set((object), R.drawable.cursor);
            return (object);
        } catch (Exception ignored) {
        }
        return (object);
    }

    /**
     * Hace una busqueda de un afiliado en la base de datos local
     * @param action filtro
     * @see #AlertDialogProp(int, String, String)
     * @see #createAdapter()
     */
    private void makeSearch(int action) {
        if (et.getText().length() > 0) {
            try {
                String[] value = et.getText().toString().split("-");
                if (value.length > 1) {
                    String id_tmp = value[0].trim();
                    Cursor cursor = sqlHelper.getCursor();
                    if (cursor.moveToFirst()) {
                        do {
                            String docto = "";
                            try {
                                if (!(cursor.getString(2) == null)) docto = cursor.getString(2);
                            } catch (Exception error) {
                                error.printStackTrace();
                            }
                            try {
                                if (!(cursor.getString(3) == null)) docto = cursor.getString(3);
                            } catch (Exception error) {
                                error.printStackTrace();
                            }
                            try {
                                if (!(cursor.getString(4) == null)) docto = cursor.getString(4);
                            } catch (Exception error) {
                                error.printStackTrace();
                            }
                            try {
                                if (!(cursor.getString(5) == null)) docto = cursor.getString(5);
                            } catch (Exception error) {
                                error.printStackTrace();
                            }
                            if (docto.equals(id_tmp)) {
                                id = cursor.getString(0);
                            }
                        } while (cursor.moveToNext());
                    }
                    this.action = action;
                    init();
                } else {
                    pd.show();
                    AlertDialogProp(1, constants._EM001, "");
                }
            } catch (Exception e) {
                pd.show();
                AlertDialogProp(1, constants._EM001, "");
            }
        } else {
            pd.show();
            AlertDialogProp(1, constants._EM001, "");
        }
    }

    /**
     * ejecuta la consulta de busqueda de afiliados y los establece en un ArrayAdapter para desplegarlos en un Listview.
     */
    @SuppressWarnings("All")
    private void createAdapter() {
        //int option = 6;
        Cursor cursor;
        try {
            sqlHelper.setQuery(constants.GENERIC_SELECT_QUERY_WITHOUT_CONDITIONS.replace("[FIELDS]", "*").replace("[TABLE]", "persons"));
            sqlHelper.execQuery();
            cursor = sqlHelper.getCursor();
            if (cursor.getCount() > 0) {
                int i = 0;
                int l = cursor.getCount() - 1;
                String[] alOptions = new String[l];
                while (cursor.moveToNext()) {
                    if (i == l)
                        break;
                    try {
                        if (!(cursor.getString(2).equals(null))) {
                            alOptions[i] = cursor.getString(2) + constants.separator;
                        }
                    } catch (Exception ex) {
                    }
                    try {
                        if (!(cursor.getString(3).equals(null))) {
                            alOptions[i] = cursor.getString(3) + constants.separator;
                        }
                    } catch (Exception ex) {
                    }
                    try {
                        if (!(cursor.getString(4).equals(null))) {
                            alOptions[i] = cursor.getString(4) + constants.separator;
                        }
                    } catch (Exception ex) {
                    }
                    try {
                        if (!(cursor.getString(5).equals(null))) {
                            alOptions[i] = cursor.getString(5) + constants.separator;
                        }
                    } catch (Exception ex) {
                    }
                    try {
                        alOptions[i] += cursor.getString(6).trim() + " " + cursor.getString(7).trim();
                        i++;
                    } catch (Exception e) {
                    }
                }
                adapter = new ArrayAdapter<>(this, R.layout.dropdown, alOptions);
                //dismissProgressDialog();
            }
        } catch (Exception e) {
            //e.printStackTrace();
            Log.e(this.getClass().getName(),"ERRRRRRRRROOOOOOR:"+ e.getMessage());
        }
    }

    /**
     * crea un dialogo dinámico para mostrar información a el encuestador
     * @param CASE caso o tipo de mensaje a mostrar
     * @param tittle título del dialogo
     * @param body mensaje del dialogo
     */
    @SuppressWarnings("All")
    public void AlertDialogProp(int CASE, String tittle, String body) {
        ad = new AlertDialog.Builder(this);
        ad.setTitle(tittle);
        if (body.length() > 0) {
            ad.setMessage(body);
        }

        switch (CASE) {
            case 1:
                LayoutInflater inflater = (LayoutInflater) this.getSystemService(getApplicationContext().LAYOUT_INFLATER_SERVICE);
                View search_View = inflater.inflate(R.layout.activity_master, null);
                ad.setView(search_View);
                TableLayout tl = new TableLayout(getApplicationContext());
                TableRow tr1 = new TableRow(getApplicationContext());
                TableRow tr2 = new TableRow(getApplicationContext());
                et = new AutoCompleteTextView(getApplicationContext());
                TextView tv1 = new TextView(getApplicationContext());

                new Thread(new Runnable() {
                    @Override
                    public void run() {
                        if (adapter != null) {
                            pd.dismiss();
                        } else {
                            //createAdapter();
                        }
                    }
                }).start();

                tv1.setText(constants._EM002 + "\n" + constants._EM003);

                tr1.addView(tv1, 0);
                tr2.addView(et, 0);

                tr1.setPadding(0, 15, 0, 15);
                tr2.setPadding(0, 15, 0, 15);

                tl.addView(tr1);
                tl.addView(tr2);

                tv1.setTextColor(Color.BLACK);
                et.setTextColor(Color.BLACK);
                et.setSingleLine(true);
                et = ((AutoCompleteTextView) setCursorDrawable(et));
                tl.setStretchAllColumns(true);
                tl.setShrinkAllColumns(true);
                tl.setPadding(10, 0, 10, 0);


                et.setBackgroundResource(R.drawable.edit_text_1);

                LinearLayout.LayoutParams lllp = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                lllp.setMargins(15, 15, 15, 15);
                tl.setLayoutParams(lllp);
                //tl.setBackgroundResource(R.drawable.bordered_layout);
                ad.setView(tl);
                //ad.setTitle(" \n");
                ad.setIcon(R.drawable.analiizo_l_header);
                //ad.setCustomTitle(new LinearLayout(new ContextThemeWrapper(getApplicationContext(), R.drawable.bordered_layout)));
                ad.setCancelable(false);

                ad.setNeutralButton(constants.erButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        //makeSearch(1);
                        createIntent(masterTools.cPersonPrefix + ".PERSON" + "." + masterTools.constants.localFile_name, 1);
                        init();
                    }
                });
                ad.setPositiveButton(constants.afButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        makeSearch(2);
                    }
                });
                ad.setNegativeButton(constants.naButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        createIntent(masterTools.cPersonPrefix + ".PERSON" + "." + masterTools.constants.localFile_name, 1);
                        init();
                    }
                });
                break;
            case 2:
                ad.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                });
                ad.show();
                break;
            case 3:
                ad.setOnCancelListener(new DialogInterface.OnCancelListener() {
                    @Override
                    public void onCancel(DialogInterface dialog) {
                        JSONObject tmpFM = masterTools.getFormMaster();
                        String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                        try {
                            tmpFM.getJSONObject(constants.doc_info).put(constants.paused, currentDateandTime);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedStructureVersion, masterTools.structure.documentInfo[0]);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedAppVersion, constants.version_name + "." + constants.version_subname);
                        } catch (Exception e) {
                        }
                        masterTools.setFormMaster(tmpFM);
                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                            masterTools.cHomePrefix++;
                            if (masterTools.sPrefixes()) {
                                finish();
                            } else {
                                AlertDialogProp(2, constants.error, constants._ADV007);
                            }
                        } else {
                            AlertDialogProp(2, constants.error, constants._ADV007);
                        }
                    }
                });
                ad.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        JSONObject tmpFM = masterTools.getFormMaster();
                        String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                        try {
                            tmpFM.getJSONObject(constants.doc_info).put(constants.paused, currentDateandTime);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedStructureVersion, masterTools.structure.documentInfo[0]);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedAppVersion, constants.version_name + "." + constants.version_subname);
                        } catch (Exception e) {
                        }
                        masterTools.setFormMaster(tmpFM);
                        if (masterTools.wLocalFile(masterTools.cHomePrefix + "." + constants.home, 1)) {
                            masterTools.cHomePrefix++;
                            if (masterTools.sPrefixes()) {
                                finish();
                            } else {
                                AlertDialogProp(2, constants.error, constants._ADV007);
                            }
                        } else {
                            AlertDialogProp(2, constants.error, constants._ADV007);

                        }
                    }
                });
                ad.show();
                break;
            case 4:
                ad.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        createProgressDialog();
                        createAlertDialogPropForSearch();
                    }
                });
                ad.show();
                break;
        }
    }

    /**
     * destruye la actividad.
     */
    private void close() {
        finish();
    }

    @Override
    public void onLocationChanged(Location location) {
        this.lat = location.getLatitude();
        this.lng = location.getLongitude();
        this.accuracy = location.getAccuracy();
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
        IS_GPS_ENABLED = false;
    }
}