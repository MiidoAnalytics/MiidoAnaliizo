package com.miido.analiizo;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.graphics.Color;
import android.graphics.Typeface;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.AdapterView;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.miido.analiizo.mcompose.ComposeTools;
import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.mcompose.LiveObjectCreator;
import com.miido.analiizo.mcompose.InterfaceBuilder;
import com.miido.analiizo.mcompose.Structure;
import com.miido.analiizo.util.RequestValidator;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

/**
 * Genéra la interface gráfica de la encuesta y valida los campos obligatorios.
 * @author Alvaro Salagado MIIDO S.A.S
 * @version 1.0
 */

public class Main extends ActionBarActivity{

    private InterfaceBuilder interfaceBuilder;
    private Structure structure;
    private Constants constants;
    private ComposeTools composeTools;
    private SqlHelper sqlHelper;
    protected LiveObjectCreator liveObjectCreator;
    private ProgressDialog pd;
    private Bundle bundle;
    private TableLayout tLayout;
    private TableLayout[] tLayoutForms;
    private TableLayout[] homeL;
    private LinearLayout rlNBButton;
    private ArrayList<Object[]>  checkBoxValidatorO;
    private ArrayList<Boolean> checkBoxValidatorB;
    private String checkBoxValidatorF;
    private Button nButton;
    private Button bButton;
    private Button cButton;
    private Button pButton;
    private Object[][] object;
    private EditText dateObject;

    private String[][] structure_l;

    private int displayWidth;
    //private int displayHeight;
    private int y, m, d;
    private int currentForm;
    //private String pSubFormIndex;
    private int[] subFormCounter;
    private int bof;
    private int isEdit;
    private String catchInfo;
    private String[] foundedPerson;
    private Boolean[] showedForms;
    private int[] pi;

    private JSONObject dataToMerge;

    private long STRUCTURE_ID;

    public final static int ITEM_SELECT_REQUEST_CODE = 1000;

    private LocationManager locationManager;

    private Toolbar toolbar;

    private JSONObject response = new JSONObject();
    public static final String RESPONSE_EXTRA = "RESPONSE";
    public static final String TITLE_EXTRA = "title";

    private int POSITION;

    private ArrayList<CheckBoxs> checkBoxses;

    //Additional Objects
    private DatePickerDialog.OnDateSetListener datePickerListener = new DatePickerDialog.OnDateSetListener() {
        public void onDateSet(DatePicker view, int selectedYear, int selectedMonth, int selectedDay) {
            calculateDatePickerResults(selectedYear, selectedMonth, selectedDay);
        }
    };

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        checkBoxses = new ArrayList<>();

        //requestWindowFeature(Window.FEATURE_NO_TITLE);
        //getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,WindowManager.LayoutParams.FLAG_FULLSCREEN);

        setContentView(R.layout.activity_main);
        locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        createLocationService();
        this.bundle = getIntent().getExtras();

        toolbar = (Toolbar) findViewById(R.id.tool_bar);
        toolbar.setTitle(getIntent().getStringExtra(TITLE_EXTRA));
        setSupportActionBar(toolbar);
        toolbar.setLogo(R.drawable.ic_action_report);

        STRUCTURE_ID = getIntent().getLongExtra("structureid", 0);
        POSITION = getIntent().getIntExtra(PollRecordActivity.POSITION_EXTRA,-1);

        //Starting environment
        //setOrientation
        setScreenOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        //setScreenOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
        //maxDisplaySize = 20600;
        constants = new Constants();
        pd = new ProgressDialog(this);
        pd.setMessage(constants._ADV009);
        pd.setCancelable(true);
        pd.setCanceledOnTouchOutside(true);
        pd.setIndeterminate(true);
        pd.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                try {
                    init();
                } catch (Exception e) {
                    //AlertDialog("GeneralError", e.getMessage());
                    e.printStackTrace();
                }
            }
        }, 100);
        pd.show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu_new, menu);

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch (id){
            case R.id.action_pause:
                this.onBackPressed();break;
        }
        return true;
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
            if(requestCode == ITEM_SELECT_REQUEST_CODE){
                String item = data.getStringExtra(ItemSelectActivity.RESULT_EXTRA);
                AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getCurrentFocus();
                autoCompleteTextView.setText(item);
            }
        }
    }

    /**
     * Inicializa todos los componentes de la actividad.
     * @throws Exception es lanzada si ocurre algún error inicializando objetos.
     * @see #prepareStorage()
     * @see #getDisplaySize()
     * @see #initializeFLayouts()
     * @see #setStructureData()
     * @see #createInterface2()
     * @see #rulesCreator()
     * @see #createNextPauseBackButton()
     * @see #parseToFButton(int)
     * @see #addNextListener()
     * @see #addBackListener()
     * @see #showForm()
     * @see #AlertDialog(String, String)
     * @see #returnParent(int)
     */
    private void init() throws Exception {
        //Creating objects instances
        isEdit = 0;
        prepareObjects();
        //Prepare AND/OR create storage environment
        if (prepareStorage()) {
            //Obtaining Display Metrics
            getDisplaySize();
            //Obtaining parent object to apply new interface
            tLayout = getInterfaceComponents();
            //initialize layouts
            initializeFLayouts();
            //Gathering form Structure
            setStructureData();
            //Starting objects generator
            interfaceBuilder.starInterfaceBuilder();
            //pasting objects to scrollView container
            if (interfaceBuilder.getBuildResults()) {
                try {

                    createInterface2();
                    rulesCreator();
                } catch (Exception e) {
                    Log.e("ErrorInterfaceBuilder", "."+e.getMessage());
                    e.printStackTrace();
                }
                currentForm = 0;
                if (bundle.getInt("case") == 0) {
                    createNextPauseBackButton();
                    tLayout.addView(homeL[0]);
                    homeL[0].setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                    tLayout.setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                    homeL[0].addView(this.rlNBButton);
                    //addNextListener();
                    //addPauseListener();
                    //addBackListener();
                    rlNBButton.removeView(bButton);
                    rlNBButton.removeView(pButton);
                    currentForm = 0;
                    parseToFButton(3);
                } else if (bundle.getInt("case") == 1) {
                    showForm();
                } else if (bundle.getInt("case") == 2) {
                    createNextPauseBackButton();
                    addNextListener();
                    addBackListener();
                    currentForm = 0;
                    rlNBButton.removeView(pButton);
                    showForm();
                }

                //(+)
                for(int i = 0; i < checkBoxses.size(); i++){
                    CheckBoxs ch = checkBoxses.get(i);
                    try {
                        liveObjectCreator.joinHandler(ch.cId,ch.checkBox,ch.type);
                    } catch (Exception e) {
                        Log.e(getClass().getName(), e.getMessage());
                    }
                }
                //(+)

            } else {
                AlertDialog("Builder Error", "No interface Found" + catchInfo);
                returnParent(0);
                Log.e("MainExceptionI::", "Couldn't generate interface objects");
            }
            tLayout.setVerticalScrollBarEnabled(true);
            tLayout.setPadding(0, 0, 0, 300);
            pd.dismiss();
        }
    }

    /**
     * establece el LocationManager para obtener la ubicacción del dispositivo.
     * @see LocationManager
     * @see LocationListener
     */
    private void createLocationService() {
        LocationListener l_l = new LocationListener() {
            @SuppressWarnings("All")
            @Override
            public void onLocationChanged(final Location location) {
            }

            @Override
            public void onStatusChanged(String provider, int status, Bundle extras) {
            }

            @Override
            public void onProviderEnabled(String provider) {
                Log.i("gpsData", "GPSEnabled");
            }

            @Override
            public void onProviderDisabled(String provider) {
                close();
            }
        };
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 10, l_l);
    }

    /**
     * Establece la orientación de la pantalla
     * @param i tipo de orientación Postrait o Landscape
     */
    @SuppressWarnings("unused")
    private void setScreenOrientation(int i) {
        setRequestedOrientation(i);
    }

    /**
     * prepara los objetos de la estructura de la encuesta.
     * @throws Exception si ocurre un error en la conversion de la estructura.
     * @see InterfaceBuilder
     * @see ComposeTools
     * @see Structure
     * @see JSONObject
     * @see LiveObjectCreator
     */
    private void prepareObjects() throws Exception {
        interfaceBuilder = new InterfaceBuilder(getApplicationContext());
        composeTools = new ComposeTools(getApplicationContext(),STRUCTURE_ID);
        structure = new Structure(getApplicationContext(),STRUCTURE_ID);
        structure.setStructure();

        dataToMerge = new JSONObject();

        liveObjectCreator = new LiveObjectCreator(getApplicationContext(),this);
        liveObjectCreator.setDynamicJoiner(this.structure.dynamicJoiner);
        liveObjectCreator.setStructure(this.structure.structure);
        liveObjectCreator.setForms(this.structure.forms);
        liveObjectCreator.setHandlers(this.structure.handlerEvent);
        liveObjectCreator.setOptions(this.structure.options);
        liveObjectCreator.setTools(this.composeTools);

        tLayoutForms = new TableLayout[(this.structure.forms.length) + 1];
        showedForms = new Boolean[this.structure.forms.length + 1];
        pi = new int[this.structure.forms.length + 1];
        //pSubFormIndex = "";
        subFormCounter = new int[this.structure.forms.length + 1];
        homeL = new TableLayout[4];
        homeL[0] = new TableLayout(this);
        homeL[1] = new TableLayout(this);
        homeL[2] = new TableLayout(this);
        homeL[3] = new TableLayout(this);
        checkBoxValidatorO = new ArrayList<>();
        checkBoxValidatorB = new ArrayList<>();
        composeTools.startJoinHandler();
    }

    /**
     * Prepara la estructura del archivo de propiedades de la aplicación.
     * @return true si está preparado, false en caso contrario
     * @throws Exception es lanzada si ocurre un error en la preparación del archivo de propiedades.
     * @see ComposeTools
     * @see Bundle
     */
    @SuppressWarnings("Unused")
    private Boolean prepareStorage() throws Exception {
        //Create or read local file
        composeTools.setEvent(bundle.getInt("case"));
        //For second home scene
        if (bundle.getInt("case") == 2){
            composeTools.setEvent(0);
        }
        try {
            this.foundedPerson = bundle.getString("target").split("~");
        } catch (Exception e) {
            e.printStackTrace();
        }
        composeTools.setTarget(this.foundedPerson[0]);
        if (foundedPerson.length > constants.filteredCount) {
            Log.e("MainExceptionPS", foundedPerson.length + "");
            //ComposeTools.setAction(Integer.parseInt(foundedPerson[foundedPerson.length - 1]));
            composeTools.setAction(0);
        }
        if (composeTools.readLocalFile()) {
            composeTools.setPerson();
            isEdit = 1;
            return true;
        } else {
            if (composeTools.focLocalFile()) {
                //if file isn't closed, resume it
                if (composeTools.fioLocalFile()) {
                    isEdit = 0;
                    return true;
                }
            }
        }

        return false;
        //AlertDialog("Error case ", caseError+"");
    }

    /**
     * obtiene el TableLayout de los componentes de la encuesta.
     * @return el TableLayout donde se encuentran los controles de la encuesta.
     * @throws Exception es lanzada al generarse un error al obtener el componente.
     */
    private TableLayout getInterfaceComponents() throws Exception {
        //this.displayHeight = getResources().getDisplayMetrics().heightPixels;
        this.displayWidth = getResources().getDisplayMetrics().widthPixels;
        return ((TableLayout) findViewById(R.id.parentFLayout));
    }

    /**
     * Obtiene la estructura de la encuesta
     * @throws Exception es lanzada si se genera un error al obtener la estructura.
     * @see ComposeTools
     * @see InterfaceBuilder
     */
    private void setStructureData() throws Exception {
        try {
            //AlertDialog("notice", Structure.Structure[0][0]);
            structure_l = composeTools.orderStructureLogical();
            composeTools.structure.structure = structure_l;
        } catch (Exception e) {
            AlertDialog("setStructureDataOrderStructureError", e.getMessage());
        }
        interfaceBuilder.setArrayHeight(structure_l.length);
        interfaceBuilder.setArrayWidht(structure_l[0].length);
        interfaceBuilder.setArrayValue(structure_l);
        interfaceBuilder.setOptionsList(structure.options);
    }

    /**
     * obtiene el tamaño de la pantalla del dispositivo.
     * @throws Exception si ocurre un error al obtener el tamaño de la pantalla del dispositivo.
     */
    private void getDisplaySize() throws Exception {
        DisplayMetrics dm = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(dm);
        //this.displayHeight = dm.heightPixels;
        this.displayWidth = dm.widthPixels;
    }

    /**
     * Crea la interface de la encuesta.
     * @throws Exception si un error ocurre al crear la interface de la encuesta.
     * @see InterfaceBuilder
     * @see ComposeTools
     * @see #addListenerOnObject(Object, int, int, int, int)
     * @see #addFieldJoinerHandler(Object, String)
     * @see #createNextPauseBackButton()
     * @see #isEditStage()
     */
    @SuppressWarnings("All")
    private void createInterface2() throws Exception { ///v2
        this.object = interfaceBuilder.getObjects();
        int c = 1;   //Math.round(displayWidth/40000);
        int f = ((int) (Math.ceil(object.length / c)));
        int i = 0;
        int hl = 0;
        String info = "";
        TableRow[] tr = new TableRow[f];
        this.composeTools.setPreloaded(this.foundedPerson);
        for (final Object[] object_tmp : object) {
            try {
                info = "creator";
                int p = 10;
                int id = Integer.parseInt((String) object_tmp[3]);
                String name = (String) object_tmp[4];
                int form = Integer.parseInt((String) object_tmp[2]);
                String label = (String) object_tmp[1];//+" - "+name;

                info = "setting Initial Params";
                tLayoutForms[getFormIndex(form)].setShrinkAllColumns(true);
                //tLayoutForms[getFormIndex(form) - 1].setStretchAllColumns(true);
                tr[i] = new TableRow(getApplicationContext());

                LinearLayout ll = new LinearLayout(getApplicationContext());
                TextView tv = new TextView(getApplicationContext());

                info = "setting objects configuration";
                tr[i].setGravity(Gravity.CENTER_HORIZONTAL);
                tv.setSingleLine(false);
                tv.setMaxLines(5);
                tv.setText(label);//.substring(0, 1).toUpperCase() + label.substring(1).toLowerCase());//+" - "+ object_tmp[3]);
                tv.setTextColor(Color.BLACK);
                tv.setTextSize(16);
                int w;
                if (displayWidth < 600) {
                    w = displayWidth - 110;
                } else {
                    w = 500;
                }
                tv.setWidth(w);

                //tv.setBackgroundResource(R.drawable.textview_1);

                ll.setPadding(0, p, 0, p);
                info = "adding views";
                try {
                    object_tmp[0] = composeTools.ObjectCreator(Integer.parseInt((String) object_tmp[3]), object_tmp[0], /*isEdit, */name/*, w*/);
                    if (composeTools.filteredValue) {
                        tv.setText(tv.getText() + " - " + constants.eButton);
                        tv.setContentDescription(i + "");
                        tv.setTextColor(Color.BLUE);
                        tv.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                ((View) object_tmp[0]).setEnabled(true);
                                ((View) object_tmp[0]).setBackgroundResource(R.drawable.focus_border_style);
                                composeTools.setAction(Integer.parseInt(foundedPerson[foundedPerson.length - 1]));
                            }
                        });
                        //ll.addView(edit);
                    }
                    if (object_tmp[0].getClass().equals(CheckBox.class)) {
                        ll.setOrientation(LinearLayout.HORIZONTAL);
                        ((CheckBox) object_tmp[0]).setText(tv.getText());
                        ll.addView((View) object_tmp[0]);
                        //(+)
                        CheckBox ch = (CheckBox) object_tmp[0];
                        checkBoxses.add(new CheckBoxs(id, ch, composeTools.type));
                        //(+)
                    } else if (object_tmp[0].getClass().equals(TextView.class)) {
                        ll.setOrientation(LinearLayout.HORIZONTAL);
                        ll.addView(tv);
                    } else {
                        ll.setOrientation(LinearLayout.VERTICAL);
                        ll.addView(tv);
                        ll.addView((View) object_tmp[0]);
                    }

                } catch (Exception e) {
                    Log.e("ObjectCreatorException", e.getMessage());
                }
                addListenerOnObject(object_tmp[0], composeTools.type, composeTools.hType, 0, (Integer.parseInt(((String) object_tmp[3]))));
                addFieldJoinerHandler(object_tmp[0], ((String) object_tmp[3]));
                info = "adding linearLayout";
                tr[i].addView(ll);
                info = "Adding object parent index info";
                object[i][6] = ((getFormIndex(form) - 1) + ""); //FormCount
                object[i][7] = tv;
                info = "validating subForm";
                int sf = composeTools.validateSubForm(id);
                if (sf >= 0) {
                    //Recursivos o que se repiten
                    info = "adding subForm";
                    int pi = composeTools.getParentIndex();
                    if (composeTools.getFormInsider() == 1) {
                        info = "Create Inner SubForm";
                        tLayoutForms[getFormIndex(form) - 1].setContentDescription("1");
                        tLayoutForms[getFormIndex(form) - 1].addView(tr[i]);
                        try {
                            if (pi > 0) {
                                subFormCounter[pi]++;
                            }
                        } catch (Exception e) {
                            if (pi > 0) {
                                subFormCounter[pi] = 0;
                            }
                        }
                        tLayoutForms[getFormIndex(form) - 1].setContentDescription("2");
                        if (pi >= 0) {
                            try {
                                if (pi != (getFormIndex(form) - 1)) {
                                    tLayoutForms[pi].addView(tLayoutForms[getFormIndex(form) - 1]);
                                    this.pi[getFormIndex(form) - 1] = pi;
                                    tLayoutForms[pi].setGravity(Gravity.CENTER_HORIZONTAL);
                                }
                            } catch (Exception e) {
                                info = "catch obtaining tLayout";
                            }
                        } else {
                            //Secundario
                            try {
                                object[i][6] = ("homeL"); //FormCount
                                object[i][8] = ((getFormIndex(form) - 1) + "");
                                int location = -1;
                                Boolean f2 = false;
                                for (int a = 0; a < homeL.length; a++) {
                                    try {
                                        if (Integer.parseInt(homeL[a].getContentDescription().toString()) == (getFormIndex(form))) {
                                            location = a;
                                            f2 = true;
                                        }
                                    } catch (Exception e) {
                                    }
                                }
                                if (location == -1) {
                                    info = "AddingHomeSubForm";
                                    location = hl;
                                    homeL[hl].setContentDescription((getFormIndex(form)) + "");
                                    hl++;
                                    f2 = false;
                                }
                                if (!(f2))
                                    homeL[location].addView(tLayoutForms[getFormIndex(form) - 1]);
                                homeL[location].setGravity(Gravity.CENTER_HORIZONTAL);
                                homeL[location].removeView(tLayoutForms[getFormIndex(form) - 1]);
                                if(!processStructureType()) {
                                    homeL[location].removeView(tLayoutForms[getFormIndex(form) - 1]);
                                    homeL[0].addView(tLayoutForms[getFormIndex(form) - 1]);
                                }
                                this.pi[getFormIndex(form) - 1] = -1;
                                //pSubFormIndex += (object_tmp[2] + "~");
                            } catch (Exception e) {
                                info = "nHomeLayout";
                                AlertDialog("Exception" + object_tmp[2] + "." + object_tmp[3], info + "__" + e.getMessage());
                            }
                        }
                        tLayoutForms[getFormIndex(form) - 1].setVisibility(View.INVISIBLE);
                        tLayoutForms[getFormIndex(form) - 1].getLayoutParams().height = 0;
                    } else {
                        info = "Create Owner SubForm";
                        tLayoutForms[getFormIndex(form) - 1].addView(tr[i]);
                        this.pi[getFormIndex(form) - 1] = 0;
                        tLayoutForms[getFormIndex(form) - 1].setContentDescription("1");
                        tLayoutForms[getFormIndex(form) - 1].setVisibility(View.INVISIBLE);
                    }
                } else if (form == 0) {
                    //General
                    int location = -1;
                    for (int a = 0; a < homeL.length; a++) {
                        try {
                            if (Integer.parseInt(homeL[a].getContentDescription().toString()) == (getFormIndex(form))) {
                                location = a;
                            }
                        } catch (Exception e) {
                        }
                    }
                    if (location == -1) {
                        info = "AddingHomeForm";
                        location = hl;
                        homeL[hl].setContentDescription("0");
                        hl++;
                    }
                    homeL[location].addView(tr[i]);
                    object[i][6] = ("homeL");
                    object[i][8] = ((getFormIndex(form) - 1) + "");
                    //this.pi[getFormIndex(form) - 1] = -1;
                } else {
                    info = "adding forms";
                    tLayoutForms[getFormIndex(form) - 1].setContentDescription("0");
                    tLayoutForms[getFormIndex(form) - 1].addView(tr[i]);
                    this.pi[getFormIndex(form) - 1] = 0;
                    tLayoutForms[getFormIndex(form) - 1].setGravity(Gravity.CENTER_HORIZONTAL);
                }
                i++;
            } catch (Exception e) {
                Log.e("infoxxx", info);
                //AlertDialog("CreateInterface2Exception_" + object_tmp[3] + "." + object_tmp[0].getClass(), info + "\n" + e.getMessage());
                i++;
            }
        }
        createNextPauseBackButton();
        //createClonateButton();
        isEditStage();
    }

    class CheckBoxs{
        int cId;
        CheckBox checkBox;
        int type;

        public CheckBoxs(int cId, CheckBox checkBox, int type){
            this.cId = cId;
            this.checkBox = checkBox;
            this.type = type;
        }
    }

    /**
     * crea las reglas para cada control de la encuesta.
     * @see Structure
     */
    private void rulesCreator() {
        for (Object[] object_tmp : object) {
            if(this.structure.aditionalFieldsRules.length >0) {
                for (String[] rules_tmp : this.structure.aditionalFieldsRules) {
                    String[] field = rules_tmp[0].split("~");
                    for (String f_tmp : field) {
                        if (f_tmp.equals(object_tmp[3])) {
                            switch (rules_tmp[1]) { //reparar validacion de caso
                                default:
                                    try {
                                        ((View) object_tmp[0]).setOnFocusChangeListener(new View.OnFocusChangeListener() {
                                            @Override
                                            public void onFocusChange(View v, boolean hasFocus) {
                                                if (!hasFocus) {
                                                    String txt1 = ((EditText) v).getText().toString();
                                                    if (txt1.length() > 0) {
                                                        String[] txt2 = txt1.split(" ");
                                                        String txt4 = "";
                                                        for (String txt3 : txt2) {
                                                            txt3 = txt3.substring(0, 1).toUpperCase() + txt3.substring(1, txt3.length()).toLowerCase();
                                                            txt4 += txt3 + " ";
                                                        }
                                                        txt4 = txt4.substring(0, txt4.length() - 1);
                                                        ((EditText) v).setText(txt4);
                                                    }
                                                }
                                            }
                                        });
                                    } catch (Exception e) {
                                        catchInfo = "rulesCreatorException";
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * deshabilita los controles de la encuesta que no se pueden editar
     * @see #calculateCurrentDate()
     * @see #calculateDatePickerResults(int, int, int)
     * @see #showSubForm(String)
     * @see #showSubForm2()
     * @see #hideSubForm2()
     */
    private void isEditStage() {
        //if(isEdit==1){
        for (Object[] object_tmp : object) {
            final int id = Integer.parseInt(object_tmp[3] + "");
            final Object v = object_tmp[0];
            try {
                if (v.getClass().equals(EditText.class)) {
                    if (!((EditText) v).getText().toString().equals("") || bundle.getInt("case") == 0) {
                        try {
                            String txt = ((EditText) v).getText().toString();
                            String[] date = txt.split("/");
                            if (date.length == 3) {
                                if ((date[0].length() == 2) && (date[1].length() == 2) && (date[2].length() == 4)) {
                                    dateObject = (EditText) v;
                                    dateObject.setContentDescription(id + "");
                                    calculateCurrentDate();
                                    calculateDatePickerResults(
                                            Integer.parseInt(date[2]),
                                            Integer.parseInt(date[1]),
                                            Integer.parseInt(date[0])
                                    );
                                    break;
                                }
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                        try {
                            //if (ComposeTools.findJoinMatchEvents(id, ((EditText) v).getText().toString())) {
                            composeTools.findJoinMatchEvents(id, ((EditText) v).getText().toString());
                            showSubForm(composeTools.getFormJoinedHandled());
                                /*} else {
                                    hideSubForm(ComposeTools.getFormJoinedHandled());
                                }*/
                            if (composeTools.findEventMatch(id, ((EditText) v).getText().toString())) {
                                showSubForm2();
                            } else {
                                hideSubForm2();
                            }
                        } catch (Exception e) {
                            catchInfo = "isEditStageException1";
                        }
                    }
                } else if (v.getClass().equals(Spinner.class)) {
                    if (!(((Spinner) v).getSelectedItem().equals("-"))) {
                        try {
                            Spinner parent = (Spinner) v;
                            int position = parent.getSelectedItemPosition();
                            //if (ComposeTools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString())) {
                            composeTools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString());
                            showSubForm(composeTools.getFormJoinedHandled());
                                /*} else {
                                    hideSubForm(ComposeTools.getFormJoinedHandled());
                                }*/
                            if (composeTools.findEventMatch(id, parent.getItemAtPosition(position).toString())) {
                                showSubForm2();
                            } else {
                                hideSubForm2();
                            }

                        } catch (Exception e) {
                            catchInfo = "isEditStageException2";
                        }
                    }
                } else if (v.getClass().equals(CheckBox.class)) {
                    try {
                        String checked;
                        Boolean isChecked = ((CheckBox) v).isChecked();
                        if (isChecked) {
                            checked = "on";
                        } else {
                            checked = "off";
                        }
                        composeTools.findJoinMatchEvents(id, checked);
                        showSubForm(composeTools.getFormJoinedHandled());
                        if (composeTools.findEventMatch(id, checked)) {
                            showSubForm2();
                        } else {
                            hideSubForm2();
                        }
                    } catch (Exception e) {
                        //AlertDialog("addListenerOnObjectException::(CheckBox)case3", e.getMessage());
                    }
                } else if (v.getClass().equals(RadioGroup.class)) {
                    for (int c = 0; c < ((RadioGroup) v).getChildCount(); c++) {
                        View rb = ((RadioGroup) v).getChildAt(c);
                        try {
                            if (rb.getClass().equals(RadioButton.class)) {
                                if (((RadioButton) rb).isChecked()) {
                                    String value = ((RadioButton) rb).getText().toString();
                                    composeTools.findJoinMatchEvents(id, value);
                                    showSubForm(composeTools.getFormJoinedHandled());
                                    if (composeTools.findEventMatch(id, value)) {
                                        showSubForm2();
                                    } else {
                                        hideSubForm2();
                                    }
                                }
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }/* else if (v.getClass().equals(AutoCompleteTextView.class)) {
                    ComposeTools.findJoinMatchEvents(id, ((AutoCompleteTextView) v).getText().toString());
                    showSubForm(ComposeTools.getFormJoinedHandled());
                    if (ComposeTools.findEventMatch(id, ((AutoCompleteTextView) v).getText().toString())) {
                        showSubForm2();
                    } else {
                        hideSubForm2();
                    }
                }*/
            } catch (Exception ex) {
                catchInfo = "isEditStageException3";
            }
        }
        //}
    }

    /**
     * crea los botones siguiente,atras y pausa en la encuesta.
     * @throws Exception si ocurre un error en la creación de los botones.
     */
    private void createNextPauseBackButton() throws Exception {
        this.rlNBButton = new LinearLayout(this);
        this.rlNBButton.setGravity(Gravity.CENTER_HORIZONTAL);

        nButton = new Button(getApplicationContext());
        pButton = new Button(getApplicationContext());
        //(+) volver el botón de pausado invisible//
        pButton.setVisibility(View.GONE);
        //(+)//
        bButton = new Button(getApplicationContext());

        nButton.setText(constants.nButton);
        nButton.setCompoundDrawablesWithIntrinsicBounds(null, null, getResources().getDrawable(R.drawable.ic_action_next), null);
        pButton.setText(constants.pButton);
        bButton.setText(constants.bButton);
        bButton.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(R.drawable.ic_action_back), null, null, null);
        nButton.setTextColor(Color.WHITE);
        pButton.setTextColor(Color.WHITE);
        bButton.setTextColor(Color.WHITE);
        nButton.setBackgroundResource(R.drawable.button);
        pButton.setBackgroundResource(R.drawable.button);
        bButton.setBackgroundResource(R.drawable.button);
        bButton.setX(-5);
        nButton.setX(5);

        this.rlNBButton.addView(bButton);
        this.rlNBButton.addView(pButton);
        this.rlNBButton.addView(nButton);
    }

    /**
     * Inicializa los layouts para los forms
     * @throws Exception si courre un error de inicialización de los forms
     */
    @SuppressWarnings("All")
    private void initializeFLayouts() throws Exception {
        try {
            int i = 0;
            for (int c = 0; c < showedForms.length; c++) {
                try {
                    showedForms[c] = new Boolean(false);
                    pi[c] = -2;
                } catch (Exception e) {
                }
            }
            for (int c = 0; i < tLayoutForms.length; c++) {
                try {
                    this.structure.forms[i][0].toString();
                } catch (Exception e) {
                    tLayoutForms[c] = new TableLayout(getApplicationContext());
                    tLayoutForms[c].setVisibility(View.INVISIBLE);
                    break;
                }
                if (this.structure.forms[i][0].equals("0")) {
                    c--;
                } else {
                    tLayoutForms[c] = new TableLayout(getApplicationContext());
                    tLayoutForms[c].setVisibility(View.INVISIBLE);
                    tLayoutForms[c].setPadding(5,0,5,0);
                    if (!(this.structure.forms[i][1].equals("")))
                        tLayoutForms[c].addView(this.createHeader(this.structure.forms[i][1], this.structure.forms[i][3]));
                }
                i++;
            }
        } catch (Exception e) {
            //AlertDialog("initializeFLayouts", e.getMessage().toString());
        }

    }

    /**
     * muestra el form de la encuesta.
     * @see #addNextListener()
     * @see #addBackListener()
     * @see #addPauseListener()
     * @see #showForm()
     * @see #parseToFButton(int)
     * @see #AlertDialog(String, String)
     */
    @SuppressWarnings("All")
    private void showForm() {
        for (TableLayout tl_temp : tLayoutForms) {
            try {
                tl_temp.removeView(rlNBButton);
            } catch (Exception e) {
                catchInfo = "showFormException1";
            }
            try {
                tl_temp.removeView(cButton);
            } catch (Exception e) {
            }
        }
        for (TableLayout tl_temp : homeL) {
            try {
                tl_temp.removeView(rlNBButton);
            } catch (Exception e) {
                catchInfo = "showFormException1";
            }
            try {
                tl_temp.removeView(cButton);
            } catch (Exception e) {
            }
        }
        int editableInfo = 0;
        ((ScrollView) findViewById(R.id.ParentSView)).fullScroll(View.FOCUS_UP);
        try {
            switch (bundle.getInt("case")) {
                case 2:
                    if(currentForm == 0){
                        currentForm++;
                    }
                    if (currentForm < homeL.length - 1) {
                        homeL[currentForm].setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                        tLayout.setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                        try {
                            homeL[currentForm].addView(rlNBButton);
                            tLayout.addView(homeL[currentForm]);
                            addNextListener();
                            addPauseListener();
                            addBackListener();
                        } catch (Exception e2) {
                        }
                    } else {
                        try {
                            try {
                                homeL[currentForm].removeAllViews();
                            } catch (Exception ex) {
                            }
                            homeL[currentForm].addView(this.createHeader(constants.summary, "0"));
                            homeL[currentForm] = (composeTools.showSummary(homeL[currentForm], true));
                            parseToFButton(1);
                            homeL[currentForm].addView(rlNBButton);
                            tLayout.addView(homeL[currentForm]);
                            //tLayout.setBackgroundColor(Color.WHITE);
                        } catch (Exception ex) {
                            AlertDialog("ShowFormSummaryException", ex.getMessage().toString());
                        }
                    }
                    break;
                case 1:
                    if (currentForm == 0)
                        pButton.setText(constants.rButton);
                    else
                        pButton.setText(constants.pButton);
                    if (currentForm < structure.forms.length) {
                        try {
                            tLayoutForms[currentForm].getContentDescription().toString();
                        } catch (Exception e2) {
                            if (bof == 1) {
                                currentForm++;
                            } else if (bof == -1) {
                                currentForm--;
                            }
                            showForm();
                        }
                        int tForm = 0;
                        tLayoutForms[currentForm + tForm].setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                        tLayout.setBackgroundColor(Color.parseColor("#ffdcdcdc"));
                        int cvc = 0;
                        try {
                            for (int c = 0; c < tLayoutForms[currentForm + tForm].getChildCount(); c++) {
                                if (tLayoutForms[currentForm + tForm].getChildAt(c).getVisibility() == View.VISIBLE) {
                                    cvc++;
                                }
                            }
                        } catch (Exception ne) {
                        }
                        if (cvc < 2) {
                            if (bof == 1) {
                                currentForm++;
                            } else if (bof == -1) {
                                currentForm--;
                            }
                            showForm();
                        }
                        switch (tLayoutForms[currentForm + tForm].getContentDescription().toString()) {
                            case "0":
                                try {
                                    //ComposeTools.toast(""+tLayoutForms[currentForm + tForm].getChildCount(),1);
                                    tLayoutForms[currentForm + tForm].addView(rlNBButton);
                                } catch (Exception e) {
                                }
                                try {
                                    tLayoutForms[currentForm + tForm].setVisibility(View.VISIBLE);
                                    showedForms[currentForm + tForm] = true;
                                    tLayout.addView(tLayoutForms[currentForm + tForm]);
                                } catch (Exception e2) {
                                }
                                tLayoutForms[currentForm + tForm].setLayoutParams(
                                        new LinearLayout.LayoutParams(-2, -2));
                                addNextListener();
                                addPauseListener();
                                addBackListener();
                                break;
                            case "1":
                                if (tLayoutForms[currentForm + tForm].getVisibility() == View.VISIBLE) {
                                    try {
                                        tLayoutForms[currentForm + tForm].addView(rlNBButton);
                                    } catch (Exception e) {
                                    }
                                    showedForms[currentForm + tForm] = true;
                                    try {
                                        tLayout.addView(tLayoutForms[currentForm + tForm]);
                                    } catch (Exception e2) {
                                    }
                                } else {
                                    if (bof == 1) {
                                        currentForm++;
                                    } else if (bof == -1) {
                                        currentForm--;
                                    }
                                    showForm();
                                }
                                break;
                            default:
                                if (bof == 1) {
                                    currentForm++;
                                } else if (bof == -1) {
                                    currentForm--;
                                }
                                showForm();
                                break;
                        }
                    } else {
                        try {
                            try {
                                tLayoutForms[currentForm].removeAllViews();
                            } catch (Exception ex) {
                            }
                            tLayoutForms[currentForm] = new TableLayout(getApplicationContext());
                            tLayoutForms[currentForm].addView(this.createHeader(constants.summary, "0"));
                            tLayoutForms[currentForm] = composeTools.showSummary(tLayoutForms[currentForm], false);
                            parseToFButton(1);
                            tLayoutForms[currentForm].addView(rlNBButton);
                            tLayout.addView(tLayoutForms[currentForm]);
                            //tLayout.setBackgroundColor(Color.WHITE);
                        } catch (Exception ex) {
                            AlertDialog("ShowFormSummaryException", ex.getMessage().toString());
                        }
                    }
                    break;
            }
        } catch (Exception e) {
            AlertDialog("ShowFormException::" + this.structure.forms[currentForm][0], e.getMessage());
        }
    }

    /**
     * Agrega listner a un control de la encuesta dependiendo su tipo.
     * @param object objeto
     * @param type tipo de objeto o control
     * @param hType subtipo
     * @param sf subform
     * @param id identificador
     * @throws Exception
     * @see #showSubForm(String)
     * @see #showSubForm2()
     * @see #hideSubForm2()
     * @see ComposeTools
     * @see SqlHelper
     */
    private void addListenerOnObject(final Object object, final int type, final int hType, final int sf, final int id) throws Exception {
        switch (type) {
            case 1:
                break;
            case 2:
                if (hType == 0) {
                    ((EditText) object).setOnFocusChangeListener(new View.OnFocusChangeListener() {
                        @Override
                        @SuppressWarnings("ALL")
                        public void onFocusChange(View v, boolean hasFocus) {
                            if (hasFocus) {
                                dateObject = (EditText) v;
                                dateObject.setContentDescription(id + "");
                                showDialog('1');
                            }
                        }
                    });
                    ((EditText) object).setOnClickListener(new View.OnClickListener() {
                        @Override
                        @SuppressWarnings("ALL")
                        public void onClick(View v) {
                            dateObject = (EditText) v;
                            dateObject.setContentDescription(id + "");
                            showDialog(1);
                        }
                    });
                    ((EditText) object).setOnKeyListener(new View.OnKeyListener() {
                        @Override
                        public boolean onKey(View v, int keyCode, KeyEvent event) {
                            dateObject = (EditText) v;
                            dateObject.setContentDescription(id + "");
                            showDialog(1);
                            return true;
                        }
                    });
                } else {
                    ((EditText) object).addTextChangedListener(new TextWatcher() {
                        @Override
                        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                        }

                        @Override
                        public void onTextChanged(CharSequence s, int start, int before, int count) {
                            composeTools.findJoinMatchEvents(id, ((EditText) object).getText().toString());
                            //ComposeTools.toast(((EditText) v).getText().toString(), 1);
                            showSubForm(composeTools.getFormJoinedHandled());
                            if (composeTools.findEventMatch(id, ((EditText) object).getText().toString())) {
                                showSubForm2();
                            } else {
                                hideSubForm2();
                            }
                        }

                        @Override
                        public void afterTextChanged(Editable s) {
                        }
                    });
                }
                break;
            case 3:
                ((CheckBox) object).setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                    @Override
                    public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                        ((CheckBox) object).setContentDescription(sf + "");
                        try {
                            String checked;
                            if (isChecked) {
                                checked = "on";
                            } else {
                                checked = "off";
                            }

                            composeTools.findJoinMatchEvents(id, checked);
                            showSubForm(composeTools.getFormJoinedHandled());
                            if (composeTools.findEventMatch(id, checked)) {
                                showSubForm2();
                            } else {
                                hideSubForm2();
                            }
                        } catch (Exception e) {
                            //AlertDialog("addListenerOnObjectException::(CheckBox)case3", e.getMessage());
                        }
                    }
                });
                //(+) agregado versión interventoria//
                try {
                    liveObjectCreator.joinHandler(id, object, type);
                } catch (Exception e){
                    Log.e(getClass().getName(), e.getMessage());
                }
                //(+)
                break;
            case 4:
                ((RadioGroup) object).setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                    @Override
                    public void onCheckedChanged(RadioGroup group, int checkedId) {
                        for (int c = 0; c < group.getChildCount(); c++) {
                            View rb = group.getChildAt(c);
                            try {
                                if (rb.getClass().equals(RadioButton.class)) {
                                    if (((RadioButton) rb).isChecked()) {
                                        String value = ((RadioButton) rb).getText().toString();
                                        composeTools.findJoinMatchEvents(id, value);
                                        try {
                                            showSubForm(composeTools.getFormJoinedHandled());
                                        } catch (NullPointerException er) {
                                            Log.e("ERT", er.getCause().toString());
                                        }
                                        if (composeTools.findEventMatch(id, value)) {
                                            showSubForm2();
                                        } else {
                                            hideSubForm2();

                                            //(+) agregado para versión interventoria
                                            // Borra todos los elementos dinamicos generados por un radiobutton.
                                            try {
                                                ViewParent vp = group.getParent().getParent();
                                                ViewParent vp2 = vp.getParent();
                                                Boolean deleting = false;
                                                for(int index = 0; index < ((LinearLayout) vp2).getChildCount()-1; index++) {
                                                    if(((LinearLayout) vp2).getChildAt(index) == vp) {
                                                        deleting = true;
                                                        View defaultView = ((LinearLayout) vp2).getChildAt(index + 1);
                                                        LinearLayout questionContainer = (LinearLayout) ((LinearLayout) ((LinearLayout) defaultView).getChildAt(0)).getChildAt(0);
                                                        for( int i = 0; i < questionContainer.getChildCount(); i++){
                                                            View child = questionContainer.getChildAt(i);
                                                            if( child.getClass().equals(EditText.class)){
                                                                ((EditText) child).setText("");
                                                            }else{
                                                                if(child.getClass().equals(AutoCompleteTextView.class)){
                                                                    ((AutoCompleteTextView) child).setText("");
                                                                }
                                                            }
                                                        }
                                                        LinearLayout recursiveQuestionContainer = (LinearLayout) ((LinearLayout)((LinearLayout) defaultView).getChildAt(1)).getChildAt(0);
                                                        for(int i = 0; i < recursiveQuestionContainer.getChildCount(); i++){
                                                            View child = recursiveQuestionContainer.getChildAt(i);
                                                            if(child.getClass().equals(CheckBox.class)){
                                                                ((CheckBox) child).setChecked(false);
                                                            }
                                                        }
                                                        index+=2;
                                                    }
                                                    if(deleting){
                                                        Log.e("REMOVE", ((LinearLayout) vp2).getChildAt(index).getTag().toString());
                                                        ((LinearLayout) vp2).removeViewAt(index);
                                                        //((LinearLayout) vp2).getChildAt(index).setVisibility(View.INVISIBLE);
                                                        index--;
                                                    }
                                                }

                                                //Log.e("HIDE",((LinearLayout) vp.).getChildCount()+"");
                                            } catch (Exception e) {
                                                e.printStackTrace();
                                            }
                                            // (+)
                                        }
                                    }
                                }
                            } catch (Exception e) {
                                Log.e("ErrorRg", e.toString());
                                e.printStackTrace();
                            }
                        }
                    }
                });
                ((RadioGroup) object).setOnFocusChangeListener(new View.OnFocusChangeListener() {
                    @Override
                    public void onFocusChange(View v, boolean hasFocus) {
                        if (hasFocus) {
                            (v).performClick();
                        }
                    }
                });
                try {
                    liveObjectCreator.joinHandler(id, object, type);
                } catch (Exception e){
                    //Log.e("ErrorLiveObjectCreator", e.getMessage());
                }
                break;
            case 5:
                LinearLayout parent = (LinearLayout) ((View) object).getParent();
                TextView label = (TextView) parent.getChildAt(0);
                final String questionLabel = label.getText().toString();
                ((AutoCompleteTextView) object).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent intent = new Intent(getApplicationContext(), ItemSelectActivity.class);
                    AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getCurrentFocus();
                    String serviceName = autoCompleteTextView.getContentDescription().toString();

                    intent.putExtra(ItemSelectActivity.TITLE_EXTRA, questionLabel);
                    intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, serviceName);
                    intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, autoCompleteTextView.getText().toString());
                    startActivityForResult(intent, ITEM_SELECT_REQUEST_CODE);
                }
            });
                ((AutoCompleteTextView) object).setOnFocusChangeListener(new View.OnFocusChangeListener() {
                    @Override
                    public void onFocusChange(View view, boolean hasFocus) {
                        if (hasFocus) {
                            Intent intent = new Intent(getApplicationContext(), ItemSelectActivity.class);
                            AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getCurrentFocus();
                            String serviceName = autoCompleteTextView.getContentDescription().toString();
                            intent.putExtra(ItemSelectActivity.TITLE_EXTRA, questionLabel);
                            intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, serviceName);
                            intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, autoCompleteTextView.getText().toString());
                            startActivityForResult(intent, ITEM_SELECT_REQUEST_CODE);
                        }
                    }
                });
                /*((AutoCompleteTextView) object).setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(getApplicationContext(), ItemSelectActivity.class);
                        AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getCurrentFocus();
                        String serviceName = autoCompleteTextView.getContentDescription().toString();
                        intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, serviceName);
                        startActivityForResult(intent, ITEM_SELECT_REQUEST_CODE);
                    }
                });*/
                //Log.e("EVENT",((AutoCompleteTextView) object).getTag().toString());
                /*((AutoCompleteTextView) object).addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {
                        SqlHelper = new SqlHelper(getApplicationContext());
                        AutoCompleteTextView ac = (AutoCompleteTextView) getCurrentFocus();
                        String auct = ac.getContentDescription().toString();
                        if (s.length() > 2) {
                            SqlHelper.OOCDB();
                            String query = Constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS;
                            query = query.replace("[FIELDS]", "*");
                            query = query.replace("[TABLE]", auct);

                            for (int c = 0; c < Constants.fieldsToFilter.length; c++) {
                                if (Constants.fieldsToFilter[c][0].equals(auct)) {
                                    String conditions = Constants.fieldsToFilter[c][1];
                                    conditions = conditions.replace(",", " LIKE '" + s + "%' OR ");
                                    conditions += " LIKE '" + s + "%' LIMIT 20";
                                    query = query.replace("[CONDITIONS]", conditions);
                                }
                            }

                            //Log.e("QUERY", query);

                            SqlHelper.setQuery(query);
                            SqlHelper.execQuery();
                            Cursor cursor = SqlHelper.getCursor();
                            int col = cursor.getColumnCount();
                            int l = cursor.getCount() - 1;
                            String content;
                            List<String> empty = new ArrayList<>();
                            cursor.moveToFirst();
                            for (int i = 0; i <= l; i++) {
                                content = "";
                                for (int a = 1; a < col; a++) {
                                    content += cursor.getString(a);
                                    if (a < col - 1) {
                                        content += " - ";
                                    }
                                }
                                empty.add(content);
                                cursor.moveToNext();
                            }
                            ArrayAdapter<String> adapter = new ArrayAdapter<>(getApplicationContext(), R.layout.dropdown, empty);
                            adapter.setNotifyOnChange(true);
                            adapter.notifyDataSetChanged();
                            adapter.getFilter().filter(s, ac);
                            ac.setAdapter(adapter);
                            ac.setThreshold(s.length() - 1);
                            cursor.close();
                        }

                        //(+) agregado para versión de interventoria
                        String value = ((AutoCompleteTextView) object).getText().toString();
                        try {
                            showSubForm(ComposeTools.getFormJoinedHandled());
                        } catch (NullPointerException er) {
                            Log.e("ERT", er.getCause().toString());
                        }
                        if (ComposeTools.findEventMatch(id, value)) {
                            showSubForm2();
                        } else {
                            hideSubForm2();
                        }
                        //(+)
                    }

                    @Override
                    public void afterTextChanged(Editable s) {
                    }
                });*/
                break;
            case 8:
                ((Spinner) object).setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> parent, View view, int position, long id2) {
                        switch (parent.getItemAtPosition(position).toString()) {
                            default:
                                ((Spinner) object).hasFocus();
                                try {
                                    composeTools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString());
                                    showSubForm(composeTools.getFormJoinedHandled());

                                    if (composeTools.findEventMatch(id, parent.getItemAtPosition(position).toString())) {
                                        showSubForm2();
                                    } else {
                                        hideSubForm2();
                                    }

                                } catch (Exception e) {
                                    //AlertDialog("addListenerOnObjectException::(Spinner)case8", e.getMessage());
                                }
                                break;
                        }
                    }

                    @Override
                    public void onNothingSelected(AdapterView<?> parent) {
                    }
                });
                ((Spinner) object).setOnFocusChangeListener(new View.OnFocusChangeListener() {
                    @Override
                    public void onFocusChange(View v, boolean hasFocus) {
                        if (hasFocus) {
                            (v).performClick();
                        }
                    }
                });
                break;
        }
    }

    /**
     * Agrega un crontrolador de evento a un objeto o control de la encuesta.
     * @param oTmp control de la encuesta
     * @param id identificador del control
     * @see ComposeTools
     * @see #showSubForm(String)
     * @see #showSubForm2()
     * @see #hideSubForm2()
     * @see #fieldJoinerExecutor(String[], Object[])
     */
    @SuppressWarnings("All")
    private void addFieldJoinerHandler(Object oTmp, String id) {
        try {
            final String join = composeTools.calculateFieldJoiner(id);
            if (!(join.equals(""))) {
                final String[] jHandler = join.split(",");
                if (oTmp.getClass().equals(EditText.class)) {
                    if (jHandler[1].equals("Y")) {
                        ((EditText) oTmp).addTextChangedListener(new TextWatcher() {
                            @Override
                            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                            }

                            @Override
                            public void onTextChanged(CharSequence s, int start, int before, int count) {
                                String[] values = s.toString().split("/");
                                int selectedDay = Integer.parseInt(values[0]);
                                int selectedMonth = Integer.parseInt(values[1]) - 1;
                                int selectedYear = Integer.parseInt(values[2]);
                                try {
                                    String[] result = join.split(",");
                                    switch (result[1]) {
                                        case "Y":
                                            if (y != selectedYear) {
                                                if (selectedMonth == m) {
                                                    if ((d - selectedDay) >= 0) {
                                                        selectedMonth--;
                                                    } else {
                                                        selectedYear++;
                                                    }
                                                }
                                                if ((m - selectedMonth) < 0) {
                                                    selectedYear++;
                                                }
                                            }
                                            ((EditText) object[Integer.parseInt(result[0])][0]).setText("" + (y - selectedYear));
                                            if (composeTools.findJoinMatchEvents(Integer.parseInt("" + object[Integer.parseInt(result[0])][3]),
                                                    ((EditText) object[Integer.parseInt(result[0])][0]).getText().toString())) {
                                                showSubForm(composeTools.getFormJoinedHandled());
                                            }
                                            if (composeTools.findEventMatch(Integer.parseInt("" + object[Integer.parseInt(result[0])][3]),
                                                    ((EditText) object[Integer.parseInt(result[0])][0]).getText().toString())) {
                                                showSubForm2();
                                            } else {
                                                hideSubForm2();
                                            }
                                            break;
                                    }
                                } catch (Exception e) {
                                    catchInfo = "datePickerDialogException1";
                                }
                            }

                            @Override
                            public void afterTextChanged(Editable s) {
                            }
                        });
                    } else if (jHandler[1].equals("ADR")) {
                        ((EditText) oTmp).addTextChangedListener(new TextWatcher() {
                            @Override
                            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                            }

                            @Override
                            public void onTextChanged(final CharSequence s, int start, int before, int count) {
                                Object[] tSend = new Object[1];
                                tSend[0] = s;
                                fieldJoinerExecutor(jHandler, tSend);
                                ((Spinner) object[Integer.parseInt(jHandler[0])][0]).setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                                    @Override
                                    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                                        fieldJoinerExecutor(jHandler, new Object[]{s});
                                    }

                                    @Override
                                    public void onNothingSelected(AdapterView<?> parent) {
                                        ((Spinner) object[Integer.parseInt(jHandler[0])][0]).hasFocus();
                                    }
                                });
                            }

                            @Override
                            public void afterTextChanged(Editable s) {
                            }
                        });
                    }
                }
            }
        } catch (NullPointerException ne) {
            return;
        }
    }

    /**
     * ejecuta un controlador de evento de un control de la encuesta
     * @param jHandler nombre del handler a ejecutar.
     * @param v vista o control de la encuesta.
     */
    private void fieldJoinerExecutor(final String[] jHandler, final Object[] v) {
        switch (jHandler[1]) {
            case "ADR":
                try {
                    String result = "";
                    CharSequence s = (CharSequence) v[0];
                    int value = Integer.parseInt(s.toString());
                    Spinner spTmp = ((Spinner) object[Integer.parseInt(jHandler[0])][0]);
                    for (String[] adrTmp : constants.ageDocRules) {
                        if ((value >= Integer.parseInt(adrTmp[0])) && (value <= Integer.parseInt(adrTmp[1]))) {
                            if ((spTmp.getSelectedItem().toString().equals(adrTmp[2]))) {
                                result = "";
                                spTmp.setRight(1);
                                break;
                            } else {
                                result += adrTmp[2] + ", ";
                            }
                        }
                    }
                    if (result.length() > 1) {
                        Log.e("ChangedListener2", "Cambio a otra vaina esta erda");
                        if (spTmp.getRight() == 1) {
                            AlertDialog(constants.atention, constants._ADV012 + "\n" + result.substring(0, result.length() - 2));
                        }
                        spTmp.setSelection(0);
                        spTmp.setRight(0);
                        spTmp.requestFocus();
                    }
                    spTmp.setOnFocusChangeListener(new View.OnFocusChangeListener() {
                        @Override
                        public void onFocusChange(View v2, boolean hasFocus) {
                            Log.e("ChangedListener", "Cambio a otra vaina esta erda");
                            fieldJoinerExecutor(jHandler, v);
                        }
                    });
                } catch (NumberFormatException nfe) {
                    Log.e("NumberFormatException", nfe.getMessage());
                }
                break;
        }

    }

    /**
     * Muestra un subform dependiendo si la pregunta tiene un joiner asignado
     * @param forms forms separados con el caracter "~"
     */
    private void showSubForm(String forms) {
        try {
            if (forms.length() > 0) {
                forms = forms.substring(0, forms.length() - 1);
                String[] tmp = forms.split("~");
                //ComposeTools.toast(forms,1);
                for (String form_tmp : tmp) {
                    String[] v = form_tmp.split(";");
                    try {
                        if (v.length > 1) {
                            int form = Integer.parseInt(v[0]);
                            //ComposeTools.toast(form_tmp,1);
                            if (v[1].equals("s")) {
                                tLayoutForms[getFormIndex(form) - 1].setVisibility(View.VISIBLE);
                                if (this.pi[getFormIndex(form) - 1] != 0) {
                                    showedForms[getFormIndex(form) - 1] = true;
                                }
                                tLayoutForms[getFormIndex(form) - 1].setLayoutParams(new LinearLayout.LayoutParams(-2, -2));
                            } else {
                                tLayoutForms[getFormIndex(form) - 1].setVisibility(View.INVISIBLE);
                                if (this.pi[getFormIndex(form) - 1] != 0) {
                                    showedForms[getFormIndex(form) - 1] = false;
                                }
                                tLayoutForms[getFormIndex(form) - 1].setLayoutParams(new LinearLayout.LayoutParams(-2, 0));
                            }
                        }
                    } catch (Exception e) {
                        catchInfo = "showSubFormException";
                    }
                }
            }
        } catch (Exception e) {
            //ComposeTools.toast(e.getMessage(), 1);
        }
    }

    /**
     * Muestra un subform dependiendo si la pregunta tiene un joiner asignado
     */
    private void showSubForm2() {
        ArrayList index_tmp = composeTools.getIndexHiddenSubForms();
        for (int c = 0; c < index_tmp.size(); c++) {
            try {
                int index = getFormIndex(Integer.parseInt((String) index_tmp.get(c))) - 1;
                tLayoutForms[index].setVisibility(View.VISIBLE);
                if (this.pi[index] != 0) {
                    showedForms[index] = true;
                }
                tLayoutForms[index].setLayoutParams(new LinearLayout.LayoutParams(-2, -2));
            } catch (Exception e) {
                AlertDialog("showSubFormException__" + index_tmp.get(c), e.getMessage());
            }
        }
    }

    /**
     * esconde un subform dependiendo si la pregunta tiene un joiner asignado
     */
    private void hideSubForm2() {
        ArrayList index_tmp = composeTools.getIndexHiddenSubForms();
        for (int c = 0; c < index_tmp.size(); c++) {
            try {
                int index = getFormIndex(Integer.parseInt((String) index_tmp.get(c))) - 1;
                tLayoutForms[index].setVisibility(View.INVISIBLE);
                if (this.pi[index] != 0) {
                    showedForms[index] = false;
                }
                tLayoutForms[index].setLayoutParams(new LinearLayout.LayoutParams(-2, 0));
            } catch (Exception e) {
                AlertDialog("hideSubFormException__" + index_tmp.get(c), e.getMessage());
            }
        }
    }

    /**
     * valida los controles de la encuesta que son obligatorios.
     * @return true si la validación se dió con exito, false en caso contrario.
     */
    private Boolean validateObjectsRequired() {
        //tLayoutForms[currentForm]
        RequestValidator requestValidator = new RequestValidator(getApplicationContext(),this.structure.getjStructure());
        try {
            if (!requestValidator.validate(tLayout, currentForm)) {
                AlertDialog(constants.atention, constants._ADV010);
                return false;
            } else {
                composeTools.started = true;
                dataToMerge = (requestValidator.JSONGetter());

                JSONObject responseTmp = requestValidator.getTmpResponse();
                if(responseTmp.length()>0){
                    try{
                        this.response.accumulate("RESPONSE", /*responseTmp*/ requestValidator.getForms().getJSONObject("Forms"));
                        //Log.e("Structure", this.response.toString());
                    }catch (JSONException ex){

                    }
                }
                return true;
            }
        } catch (Exception je) {
            Log.e("JError", je.getMessage());
            return false;
        }
    }

    /**
     * agrega un evento click al botón siguiente de la encuesta.
     * @throws Exception si ocurre un error al asignar el evento
     * @see #removeCurrentForm()
     * @see #validateObjectsRequired()
     * @see #AlertDialog(String, String)
     */
    private void addNextListener() throws Exception {
        this.nButton.setText(this.constants.nButton);
        this.nButton.setCompoundDrawablesWithIntrinsicBounds(null,null,getResources().getDrawable(R.drawable.ic_action_next),null);
        this.nButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") == 1) {
                    if (validateObjectsRequired()) {
                        composeTools.saveAllData(null, false, dataToMerge);
                        if (bof < tLayoutForms.length - 1) {
                            bof = 1;
                            try {
                                removeCurrentForm();
                            } catch (Exception e) {
                                AlertDialog("addNextListenerExceptionRCF-ICF", e.getMessage());
                            }
                        }
                    }
                } else {
                    if (validateObjectsRequired()) {
                        composeTools.saveAllData(object, true, null);
                        if (currentForm < homeL.length) {
                            bof = 1;
                            try {
                                removeCurrentForm();
                            } catch (Exception e) {
                                AlertDialog("addNextListenerExceptionRCF-ICF", e.getMessage());
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * agrega un evento click al botón atras de la encuesta.
     * @throws Exception si ocurre un error al asignar el evento
     * @see #validateObjectsRequired()
     * @see #removeCurrentForm()
     * @see #returnParent(int)
     */
    private void addBackListener() throws Exception {
        this.bButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") == 1) {
                    if (validateObjectsRequired()) {
                        composeTools.saveAllData(null, false, dataToMerge);
                        if (currentForm > 0) {
                            bof = -1;
                            try {
                                removeCurrentForm();
                            } catch (Exception e) {
                                catchInfo = "addBackListenerException1";
                            }
                        } else {
                            //(+) agregado para retornar al listado de encuestas y no a agregar personas
                            onBackPressed();
                            //(+)
                            //returnParent(-1);
                        }
                    }
                } else {
                    if (validateObjectsRequired()) {
                        composeTools.saveAllData(object, true, null);
                        if (currentForm > 1) {
                            bof = -1;
                            try {
                                removeCurrentForm();
                            } catch (Exception e) {
                                AlertDialog("addNextListenerExceptionRCF-ICF", e.getMessage());
                            }
                        } else {
                            Log.e("returnParent", "true");
                            returnParent(1);
                        }
                    }
                }
            }
        });
    }

    /**
     * agrega un evento click al botón pausa de la encuesta.
     * @throws Exception si ocurre un error al asignar el evento
     * @see #returnParent(int)
     */
    private void addPauseListener() throws Exception {
        this.pButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") != 0) {
                    Log.e("returning", "-1");
                    returnParent(-1);
                } else {
                    Log.e("returning", "1");
                    returnParent(1);
                }
            }
        });
    }

    /**
     * modifica la acción del botón guardar dependiendo la llamada
     * @param call guarda todo o guardado parcial de la encuesta
     * @throws Exception es lanzada si se genera algun error en el método.
     * @see #validateObjectsRequired()
     * @see #returnParent(int)
     * @see #AlertDialog(String, String)
     */
    private void parseToFButton(final int call) throws Exception {
        nButton.setText(this.constants.sButton);
        nButton.setCompoundDrawablesWithIntrinsicBounds(null, null, getResources().getDrawable(R.drawable.ic_action_content_save), null);
        nButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    if (call == 3) {
                        if (validateObjectsRequired()) {
                            composeTools.saveAllData(object, true, null);
                            returnParent(2);
                        }
                    } else
                        returnParent(1);
                } catch (Exception e) {
                    AlertDialog("ErrorGuardando", e.getMessage());
                    returnParent(0);
                }

            }
        });
    }

    /**
     * Estable los datos para retornar a la actividad padre.
     * @param s tipo de respuesta a devolver a la actividad lanzadora.
     */
    private void returnParent(int s) {
        Intent i = new Intent();
        Bundle b = new Bundle();
        if (s == 1) {
            b.putInt("RESULT", (s + isEdit) * this.bundle.getInt("case"));
            b.putString("target", this.bundle.getString("target"));
            b.putBoolean("OPEN", false);
            b.putString(RESPONSE_EXTRA, this.response.toString());
            i.putExtras(b);
            setResult(RESULT_OK, i);
        } else if (s == -1) {
            b.putBoolean("OPEN", composeTools.started);
            b.putString("target", this.bundle.getString("target"));
            b.putString(RESPONSE_EXTRA, this.response.toString());
            i.putExtras(b);
            setResult(RESULT_CANCELED, i);
        } else if (s == 2) {
            if(processStructureType()){
                b.putInt("RESULT", 3);
            } else {
                b.putInt("RESULT", 5);
            }
            b.putString(RESPONSE_EXTRA, this.response.toString());
            i.putExtras(b);
            setResult(RESULT_OK, i);
        }
        resetVariables();
        finish();
        super.onStop();
    }

    /**
     * procesa los tipos de control de la estructura de la encuesta
     * @return true si todos los controles de la encuesta fueron procesados, false en caso contrario.
     */
    protected Boolean processStructureType() {
        try {
            JSONObject structureTmp = structure.jStructure;
            JSONArray fieldsStructure = structureTmp.getJSONArray("fields_structure");
            JSONArray formsStructure = structureTmp.getJSONArray("forms");
            for(int index = 0; index < fieldsStructure.length(); index++) {
                if(!fieldsStructure.getJSONObject(index).getString("Form").equals("0")) {
                    for (int index2 = 0; index2 < formsStructure.length(); index2++) {
                        if (formsStructure.getJSONObject(index2).getString("Id").equals(fieldsStructure.getJSONObject(index).getString("Form"))){
                            if ((formsStructure.getJSONObject(index2).getString("Inside").equals("0"))) return true;
                        }
                    }
                }
            }
        } catch (JSONException je) {
            Log.e(je.getLocalizedMessage(), je.getMessage());
        }
        return false;
    }

    /**
     * establece todos los atributos y controles de la actividad en null.
     */
    private void resetVariables() {
        interfaceBuilder = null;
        structure = null;
        constants = null;
        composeTools = null;
        pd = null;
        bundle = null;
        tLayout = null;
        tLayoutForms = null;
        homeL = null;
        rlNBButton = null;
        nButton = null;
        bButton = null;
        cButton = null;
        pButton = null;
        object = null;
        dateObject = null;
        structure_l = null;
        //pSubFormIndex = null;
        subFormCounter = null;
        catchInfo = null;
        foundedPerson = null;
        showedForms = null;
        pi = null;
    }

    /**
     * Obtiene el indice de un formulario
     * @param formId indentificador del formulario
     * @return el indice del formulario
     */
    private int getFormIndex(int formId) {
        int index = 0;
        for (String[] forms_tmp : this.structure.forms) {
            if (Integer.parseInt(forms_tmp[0]) == formId) {
                return index;
            }
            index++;
        }
        return index;
    }

    /**
     * Elimina el formulario actual para mostrar otro
     * @throws Exception si se genera algun error al eliminar el formulario actual.
     * @see #incrementCurrentForm()
     * @see #decrementCurrentForm()
     * @see #showForm()
     */
    private void removeCurrentForm() throws Exception {
        try {
            if (bundle.getInt("case") == 1) {
                tLayout.removeView(tLayoutForms[currentForm]);
            } else {
                tLayout.removeView(homeL[currentForm]);
            }
            showedForms[currentForm] = false;
            if (bof == 1) {
                incrementCurrentForm();
            } else {
                decrementCurrentForm();
            }
        } catch (Exception e) {
            Log.e("RCF-ICF_Exception", e.getMessage());
        }
        try {
            showForm();
        } catch (Exception e) {
            Log.e("rcfException", e.getMessage());
        }
    }

    /**
     * decrementa el indice de el formulario actual
     * @throws Exception si ocurre algún error al decrementar el formulario actual
     */
    private void decrementCurrentForm() {
        this.currentForm--;
    }

    /**
     * Incrementa el indice de el formulario actual
     * @throws Exception si ocurre algún error al incrementar el formulario actual
     */
    private void incrementCurrentForm() throws Exception {
        this.currentForm++;
    }

    /**
     * crea un objeto Linearlayout
     * @param tittle título del layout
     * @param parent padre
     * @return retorna el objeto Linearlayout creado
     * @throws Exception si ocurre algún error en la creacion del layout
     */
    private LinearLayout createHeader(String tittle, String parent) throws Exception {
        LinearLayout rl = new LinearLayout(getApplicationContext());
        rl.setLayoutParams(new LinearLayout.LayoutParams(displayWidth, ViewGroup.LayoutParams.WRAP_CONTENT));
        rl.setOrientation(LinearLayout.VERTICAL);
        TextView tv = new TextView(getApplicationContext());
        RelativeLayout separator = new RelativeLayout(this);
        Typeface tf = Typeface.createFromAsset(getAssets(), "NotoSans-Regular.ttf");
        //tittle = (tittle.substring(0,1).toUpperCase()+tittle.substring(1).toLowerCase());
        tv.setText(tittle.toUpperCase());
        tv.setTextColor(Color.parseColor("#5dae58"));
        //tv.setTextSize(22);
        tv.setGravity(Gravity.CENTER);
        tv.setTypeface(tf);
        tv.setBackgroundResource(R.drawable.textview_1);
        separator.setBackgroundColor(Color.parseColor("#ff00c134"));
        separator.setLayoutParams(new LinearLayout.LayoutParams(displayWidth - 50, 2));
        separator.setGravity(Gravity.TOP);
        //tv.setPadding(0, 0, 0, 0);
        //separator.setPadding(0, 0, 0, 25);
        if (!tittle.equals("~")) {
            rl.addView(tv);
        }
        rl.addView(separator);
        rl.setGravity(Gravity.CENTER_HORIZONTAL);
        //Log.e("Inside", parent);
        if (parent.equals("0")) {
            //rl.setBackgroundColor(Color.WHITE);
            //rl.setPadding(0, 20, 0, 30);
        }
        return rl;
    }

    /**
     * crea el dialogo de selección de fechas
     * @param id identificador del dialogo
     * @return un DatePickerDialog o null si el identificador no es encontrado
     * @see #calculateCurrentDate()
     */
    @SuppressWarnings("All")
    @Override
    protected Dialog onCreateDialog(int id) {
        switch (id) {
            case 1:
                calculateCurrentDate();
                return new DatePickerDialog(this, datePickerListener, y, m, d);
        }
        return null;
    }

    /**
     * muestra un dialogo de alerta
     * @param Tittle título del dialogo
     * @param Message mensaje que muestra el dialogo.
     */
    private void AlertDialog(String Tittle, String Message) {
        AlertDialog.Builder ad = new AlertDialog.Builder(this);
        ad.setTitle(Tittle);
        ad.setMessage(Message);
        ad.setPositiveButton("ok", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                //finish();
                dialog.cancel();
            }
        });
        ad.show();
    }

    /**
     * se ejecuta cuando se preciona el botón atras del dispositivo.
     */
    @Override
    public void onBackPressed() {
        launchConfirmDialogPause();
    }

    private void launchConfirmDialogPause(){
        AlertDialog confirmDialog = com.miido.analiizo.util.Dialog.confirm(this,"Confirmación","¿Desea pausar este informe?\n" +
                "Puede reanudarlo ingresando nuevamente al informe.\n" );
        confirmDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                setResult(RESULT_CANCELED, null);
                finish();
            }
        });
        confirmDialog.show();
    }

    /**
     * obtiene el resulltado de una vista DatePicker
     * @param selectedYear año seleccionado
     * @param selectedMonth mes seleccionado
     * @param selectedDay día seleccionado
     */
    private void calculateDatePickerResults(int selectedYear, int selectedMonth, int selectedDay) {
        EditText et;
        et = dateObject;
        if (selectedYear > y) {
            AlertDialog("Error en la fecha seleccionada", "La fecha seleccionada no puede ser mayor a la fecha actual");
        } else if ((selectedYear == y) && (selectedMonth > m)) {
            AlertDialog("Error en la fecha seleccionada", "La fecha seleccionada no puede ser mayor a la fecha actual");
        } else if ((selectedYear == y) && (selectedMonth == m) && (selectedDay > d)) {
            AlertDialog(constants._ADV026, constants._ADV025);
        } else {
            et.setText(new StringBuilder().append(selectedDay).append("/").append(selectedMonth + 1).append("/").append(selectedYear));
        }
    }

    /**
     * Obtiene la fecha actual en formato "dd/MM/yyyy"
     * @see Date
     * @see SimpleDateFormat
     */
    private void calculateCurrentDate() {
        Date date = new Date();
        @SuppressLint("SimpleDateFormat")
        SimpleDateFormat df = new SimpleDateFormat("dd/MM/yyyy");
        String formattedHour = df.format(date.getTime());
        String[] dt = formattedHour.split("/");
        this.y = Integer.parseInt(dt[2]);
        this.m = Integer.parseInt(dt[1]) - 1;
        this.d = Integer.parseInt(dt[0]);
    }

    /**
     * se ejecuta cuando la actividad se destruye
     */
    private void close() {
        finish();
    }

    /**
     * se ejecut cuando l actividad es detenida.
     */
    @Override
    protected void onStop() {
        super.onStop();
    }
}