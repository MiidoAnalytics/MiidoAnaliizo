package com.miido.miido;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.database.Cursor;
import android.graphics.Color;
import android.graphics.Typeface;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
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

import com.miido.miido.mcompose.composeTools;
import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.interfaceBuilder;
import com.miido.miido.mcompose.liveObjectCreator;
import com.miido.miido.mcompose.structure;
import com.miido.miido.util.requestValidator;
import com.miido.miido.util.sqlHelper;

import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

//Miido Classes

public class main extends Activity {

    private interfaceBuilder ibClass;
    private structure structureObject;
    private constants constants;
    private composeTools tools;
    private sqlHelper sqlHelper;
    protected liveObjectCreator liveObjectCreator;
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

    private LocationManager l_m;
    //Additional Objects
    private DatePickerDialog.OnDateSetListener datePickerListener = new DatePickerDialog.OnDateSetListener() {
        public void onDateSet(DatePicker view, int selectedYear, int selectedMonth, int selectedDay) {
            calculateDatePickerResults(selectedYear, selectedMonth, selectedDay);
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_main);
        l_m = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        createLocationService();
        this.bundle = getIntent().getExtras();
        //Starting environment
        //setOrientation
        setScreenOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        //setScreenOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
        //maxDisplaySize = 20600;
        constants = new constants();
        pd = new ProgressDialog(this);
        pd.setMessage(constants._ADV009);
        pd.setCancelable(false);
        pd.setCanceledOnTouchOutside(false);
        pd.setIndeterminate(true);
        pd.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                try {
                    init();
                } catch (Exception e) {
                    AlertDialog("GeneralError", e.getMessage());
                }
            }
        }, 100);
        pd.show();
    }

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
            //Gathering form structure
            setStructureData();
            //Starting objects generator
            ibClass.starInterfaceBuilder();
            //pasting objects to scrollView container
            if (ibClass.getBuildResults()) {
                try {
                    createInterface2();
                    rulesCreator();
                } catch (Exception e) {
                    AlertDialog("Error", e.getMessage());
                }
                currentForm = 0;
                if (bundle.getInt("case") == 0) { //Grupo Familiar
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
                } else if (bundle.getInt("case") == 1) { //Personas
                    showForm();
                } else if (bundle.getInt("case") == 2) { //Vivienda y satisfaccion
                    createNextPauseBackButton();
                    addNextListener();
                    addBackListener();
                    currentForm = 0;
                    rlNBButton.removeView(pButton);
                    showForm();
                }
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
        l_m.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 10, l_l);
    }

    @SuppressWarnings("unused")
    private void setScreenOrientation(int i) {
        setRequestedOrientation(i);
    }

    private void prepareObjects() throws Exception {
        ibClass = new interfaceBuilder(getApplicationContext());
        tools = new composeTools(getApplicationContext());
        structureObject = new structure(getApplicationContext());
        structureObject.setStructure();

        dataToMerge = new JSONObject();

        liveObjectCreator = new liveObjectCreator(getApplicationContext());
        liveObjectCreator.setDynamicJoiner(structureObject.dynamicJoiner);
        liveObjectCreator.setStructure(this.structureObject.structure);
        liveObjectCreator.setForms(this.structureObject.forms);
        liveObjectCreator.setHandlers(this.structureObject.handlerEvent);
        liveObjectCreator.setOptions(this.structureObject.options);
        liveObjectCreator.setTools(this.tools);

        tLayoutForms = new TableLayout[(this.structureObject.forms.length) + 1];
        showedForms = new Boolean[this.structureObject.forms.length + 1];
        pi = new int[this.structureObject.forms.length + 1];
        //pSubFormIndex = "";
        subFormCounter = new int[this.structureObject.forms.length + 1];
        homeL = new TableLayout[4];
        homeL[0] = new TableLayout(this);
        homeL[1] = new TableLayout(this);
        homeL[2] = new TableLayout(this);
        homeL[3] = new TableLayout(this);
        checkBoxValidatorO = new ArrayList<>();
        checkBoxValidatorB = new ArrayList<>();
        tools.startJoinHandler();
    }

    @SuppressWarnings("Unused")
    private Boolean prepareStorage() throws Exception {
        //Create or read local file
        tools.setEvent(bundle.getInt("case"));
        //For second home scene
        if (bundle.getInt("case") == 2){
            tools.setEvent(0);
        }
        try {
            this.foundedPerson = bundle.getString("target").split("~");
        } catch (Exception e) {
            e.printStackTrace();
        }
        tools.setTarget(this.foundedPerson[0]);
        if (foundedPerson.length > constants.filteredCount) {
            Log.e("MainExceptionPS", foundedPerson.length + "");
            //tools.setAction(Integer.parseInt(foundedPerson[foundedPerson.length - 1]));
            tools.setAction(0);
        }
        if (tools.readLocalFile()) {
            tools.setPerson();
            isEdit = 1;
            return true;
        } else {
            if (tools.focLocalFile()) {
                //if file isn't closed, resume it
                if (tools.fioLocalFile()) {
                    isEdit = 0;
                    return true;
                }
            }
        }

        return false;
        //AlertDialog("Error case ", caseError+"");
    }

    private TableLayout getInterfaceComponents() throws Exception {
        //this.displayHeight = getResources().getDisplayMetrics().heightPixels;
        this.displayWidth = getResources().getDisplayMetrics().widthPixels;
        return ((TableLayout) findViewById(R.id.parentFLayout));
    }

    private void setStructureData() throws Exception {
        try {
            //AlertDialog("notice", structureObject.structure[0][0]);
            structure_l = tools.orderStructureLogical();
            tools.structure.structure = structure_l;
        } catch (Exception e) {
            AlertDialog("setStructureDataOrderStructureError", e.getMessage());
        }
        ibClass.setArrayHeight(structure_l.length);
        ibClass.setArrayWidht(structure_l[0].length);
        ibClass.setArrayValue(structure_l);
        ibClass.setOptionsList(structureObject.options);
    }

    private void getDisplaySize() throws Exception {
        DisplayMetrics dm = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(dm);
        //this.displayHeight = dm.heightPixels;
        this.displayWidth = dm.widthPixels;
    }

    @SuppressWarnings("All")
    private void createInterface2() throws Exception { ///v2
        this.object = ibClass.getObjects();
        int c = 1;   //Math.round(displayWidth/40000);
        int f = ((int) (Math.ceil(object.length / c)));
        int i = 0;
        int hl = 0;
        String info = "";
        TableRow[] tr = new TableRow[f];
        this.tools.setPreloaded(this.foundedPerson);
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
                tv.setMaxLines(2);
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

                ll.setPadding(p, p, p, p);
                info = "adding views";
                try {
                    object_tmp[0] = tools.ObjectCreator(object_tmp[0], /*isEdit, */name/*, w*/);
                    if (tools.filteredValue) {
                        tv.setText(tv.getText() + " - " + constants.eButton);
                        tv.setContentDescription(i + "");
                        tv.setTextColor(Color.BLUE);
                        tv.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                ((View) object_tmp[0]).setEnabled(true);
                                ((View) object_tmp[0]).setBackgroundResource(R.drawable.focus_border_style);
                                tools.setAction(Integer.parseInt(foundedPerson[foundedPerson.length - 1]));
                            }
                        });
                        //ll.addView(edit);
                    }
                    if (object_tmp[0].getClass().equals(CheckBox.class)) {
                        ll.setOrientation(LinearLayout.HORIZONTAL);
                        ((CheckBox) object_tmp[0]).setText(tv.getText());
                        ll.addView((View) object_tmp[0]);
                    } else if (object_tmp[0].getClass().equals(TextView.class)) {
                        ll.setOrientation(LinearLayout.HORIZONTAL);
                        ll.addView(tv);
                    } else {
                        ll.setOrientation(LinearLayout.VERTICAL);
                        ll.addView(tv);
                        ll.addView((View) object_tmp[0]);
                    }

                } catch (Exception e) {
                    AlertDialog("ObjectCreatorException", e.getMessage());
                }
                addListenerOnObject(object_tmp[0], tools.type, tools.hType, 0, (Integer.parseInt(((String) object_tmp[3]))));
                addFieldJoinerHandler(object_tmp[0], ((String) object_tmp[3]));
                info = "adding linearLayout";
                tr[i].addView(ll);
                info = "Adding object parent index info";
                object[i][6] = ((getFormIndex(form) - 1) + ""); //FormCount
                object[i][7] = tv;
                info = "validating subForm";

                int sf = tools.validateSubForm(id); //sf = SubFormulario
                if (sf >= 0) {
                    info = "adding subForm";
                    int pi = tools.getParentIndex(); //Parent index
                    if (tools.getFormInsider() == 1) {
                        info = "Create Inner SubForm";
                        tLayoutForms[getFormIndex(form) - 1].setContentDescription("1");
                        tLayoutForms[getFormIndex(form) - 1].addView(tr[i]);
                        try {
                            if (pi > 0)
                                subFormCounter[pi]++;
                        } catch (Exception e) {
                            if (pi > 0)
                                subFormCounter[pi] = 0;
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
                        } else { //Si Formulario es vivienda pi = -1
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
                                this.pi[getFormIndex(form) - 1] = -1;
                                //pSubFormIndex += (object_tmp[2] + "~");
                            } catch (Exception e) {
                                info = "nHomeLayout";
                                AlertDialog("Exception" + object_tmp[2] + "." + object_tmp[3], info + "__" + e.getMessage());
                            }
                        }
                        tLayoutForms[getFormIndex(form) - 1].setVisibility(View.INVISIBLE);
                        tLayoutForms[getFormIndex(form) - 1].getLayoutParams().height = 0;
                    } else { //Si no es subFormulario
                        info = "Create Owner SubForm";
                        tLayoutForms[getFormIndex(form) - 1].addView(tr[i]);
                        this.pi[getFormIndex(form) - 1] = 0;
                        tLayoutForms[getFormIndex(form) - 1].setContentDescription("1");
                        tLayoutForms[getFormIndex(form) - 1].setVisibility(View.INVISIBLE);
                    }
                } else if (form == 0) {
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
                AlertDialog("CreateInterface2Exception_" + object_tmp[3] + "." + object_tmp[0].getClass(), info + "\n" + e.getMessage());
                i++;
            }
        }
        createNextPauseBackButton();
        //createClonateButton();
        isEditStage();
    }

    private void rulesCreator() {
        for (Object[] object_tmp : object) {
            for (String[] rules_tmp : this.structureObject.aditionalFieldsRules) {
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
                            //if (tools.findJoinMatchEvents(id, ((EditText) v).getText().toString())) {
                            tools.findJoinMatchEvents(id, ((EditText) v).getText().toString());
                            showSubForm(tools.getFormJoinedHandled());
                                /*} else {
                                    hideSubForm(tools.getFormJoinedHandled());
                                }*/
                            if (tools.findEventMatch(id, ((EditText) v).getText().toString())) {
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
                            //if (tools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString())) {
                            tools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString());
                            showSubForm(tools.getFormJoinedHandled());
                                /*} else {
                                    hideSubForm(tools.getFormJoinedHandled());
                                }*/
                            if (tools.findEventMatch(id, parent.getItemAtPosition(position).toString())) {
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
                        tools.findJoinMatchEvents(id, checked);
                        showSubForm(tools.getFormJoinedHandled());
                        if (tools.findEventMatch(id, checked)) {
                            showSubForm2();
                        } else {
                            hideSubForm2();
                        }
                    } catch (Exception e) {
                        AlertDialog("addListenerOnObjectException::(CheckBox)case3", e.getMessage());
                    }
                } else if (v.getClass().equals(RadioGroup.class)) {
                    for (int c = 0; c < ((RadioGroup) v).getChildCount(); c++) {
                        View rb = ((RadioGroup) v).getChildAt(c);
                        try {
                            if (rb.getClass().equals(RadioButton.class)) {
                                if (((RadioButton) rb).isChecked()) {
                                    String value = ((RadioButton) rb).getText().toString();
                                    tools.findJoinMatchEvents(id, value);
                                    showSubForm(tools.getFormJoinedHandled());
                                    if (tools.findEventMatch(id, value)) {
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
                    tools.findJoinMatchEvents(id, ((AutoCompleteTextView) v).getText().toString());
                    showSubForm(tools.getFormJoinedHandled());
                    if (tools.findEventMatch(id, ((AutoCompleteTextView) v).getText().toString())) {
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

    private void createNextPauseBackButton() throws Exception {
        this.rlNBButton = new LinearLayout(this);
        this.rlNBButton.setGravity(Gravity.CENTER_HORIZONTAL);

        nButton = new Button(getApplicationContext());
        pButton = new Button(getApplicationContext());
        bButton = new Button(getApplicationContext());

        nButton.setText(constants.nButton);
        pButton.setText(constants.pButton);
        bButton.setText(constants.bButton);
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
                    this.structureObject.forms[i][0].toString();
                } catch (Exception e) {
                    tLayoutForms[c] = new TableLayout(getApplicationContext());
                    tLayoutForms[c].setVisibility(View.INVISIBLE);
                    break;
                }
                if (this.structureObject.forms[i][0].equals("0")) {
                    c--;
                } else {
                    tLayoutForms[c] = new TableLayout(getApplicationContext());
                    tLayoutForms[c].setVisibility(View.INVISIBLE);
                    if (!(this.structureObject.forms[i][1].equals("")))
                        tLayoutForms[c].addView(this.createHeader(this.structureObject.forms[i][1], this.structureObject.forms[i][3]));
                }
                i++;
            }
        } catch (Exception e) {
            //AlertDialog("initializeFLayouts", e.getMessage().toString());
        }

    }

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
                            homeL[currentForm] = (tools.showSummary(homeL[currentForm], true));
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
                    if (currentForm < structureObject.forms.length) {
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
                                    //tools.toast(""+tLayoutForms[currentForm + tForm].getChildCount(),1);
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
                            tLayoutForms[currentForm] = tools.showSummary(tLayoutForms[currentForm], false);
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
            AlertDialog("ShowFormException::" + this.structureObject.forms[currentForm][0], e.getMessage());
        }
    }

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
                            tools.findJoinMatchEvents(id, ((EditText) object).getText().toString());
                            //tools.toast(((EditText) v).getText().toString(), 1);
                            showSubForm(tools.getFormJoinedHandled());
                            if (tools.findEventMatch(id, ((EditText) object).getText().toString())) {
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
                            tools.findJoinMatchEvents(id, checked);
                            showSubForm(tools.getFormJoinedHandled());
                            if (tools.findEventMatch(id, checked)) {
                                showSubForm2();
                            } else {
                                hideSubForm2();
                            }
                        } catch (Exception e) {
                            AlertDialog("addListenerOnObjectException::(CheckBox)case3", e.getMessage());
                        }
                    }
                });
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
                                        tools.findJoinMatchEvents(id, value);
                                        showSubForm(tools.getFormJoinedHandled());
                                        if (tools.findEventMatch(id, value)) {
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
                ((AutoCompleteTextView) object).addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {
                        sqlHelper = new sqlHelper(getApplicationContext());
                        AutoCompleteTextView ac = (AutoCompleteTextView) getCurrentFocus();
                        String auct = ac.getContentDescription().toString();
                        if (s.length() > 2) {
                            sqlHelper.OOCDB();
                            String query = constants.QUERY_2;
                            query = query.replace("[FIELDS]", "*");
                            query = query.replace("[TABLE]", auct);
                            for (int c = 0; c < constants.fieldsToFilter.length; c++) {
                                if (constants.fieldsToFilter[c][0].equals(auct)) {
                                    String conditions = constants.fieldsToFilter[c][1];
                                    conditions = conditions.replace(",", " like '" + s + "%' OR ");
                                    conditions += " like '" + s + "%' LIMIT 20";
                                    query = query.replace("[CONDITIONS]", conditions);
                                }
                            }
                            sqlHelper.setQuery(query);
                            sqlHelper.execQuery();
                            Cursor cursor = sqlHelper.getCursor();
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
                    }
                    @Override
                    public void afterTextChanged(Editable s) {}
                });
                break;
            case 8:
                ((Spinner) object).setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> parent, View view, int position, long id2) {
                        switch (parent.getItemAtPosition(position).toString()) {
                            default:
                                ((Spinner) object).hasFocus();
                                try {
                                    tools.findJoinMatchEvents(id, parent.getItemAtPosition(position).toString());
                                    showSubForm(tools.getFormJoinedHandled());

                                    if (tools.findEventMatch(id, parent.getItemAtPosition(position).toString())) {
                                        showSubForm2();
                                    } else {
                                        hideSubForm2();
                                    }

                                } catch (Exception e) {
                                    AlertDialog("addListenerOnObjectException::(Spinner)case8", e.getMessage());
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

    @SuppressWarnings("All")
    private void addFieldJoinerHandler(Object oTmp, String id) {
        try {
            final String join = tools.calculateFieldJoiner(id);
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
                                            if (tools.findJoinMatchEvents(Integer.parseInt("" + object[Integer.parseInt(result[0])][3]),
                                                    ((EditText) object[Integer.parseInt(result[0])][0]).getText().toString())) {
                                                showSubForm(tools.getFormJoinedHandled());
                                            }
                                            if (tools.findEventMatch(Integer.parseInt("" + object[Integer.parseInt(result[0])][3]),
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

    private void showSubForm(String forms) {
        try {
            if (forms.length() > 0) {
                forms = forms.substring(0, forms.length() - 1);
                String[] tmp = forms.split("~");
                //tools.toast(forms,1);
                for (String form_tmp : tmp) {
                    String[] v = form_tmp.split(";");
                    try {
                        if (v.length > 1) {
                            int form = Integer.parseInt(v[0]);
                            //tools.toast(form_tmp,1);
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
            tools.toast(e.getMessage(), 1);
        }
    }

    private void showSubForm2() {
        ArrayList index_tmp = tools.getIndexHiddenSubForms();
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

    private void hideSubForm2() {
        ArrayList index_tmp = tools.getIndexHiddenSubForms();
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

    private Boolean validateObjectsRequired() {
        //tLayoutForms[currentForm]
        requestValidator requestValidator = new requestValidator(getApplicationContext());
        try {
            if (!requestValidator.validate(tLayout)) {
                AlertDialog(constants.atention, constants._ADV010);
                return false;
            } else {
                dataToMerge = (requestValidator.JSONGetter());
                return true;
            }
        } catch (Exception je) {
            Log.e("JError", je.getMessage());
            return false;
        }
    }

    private void addNextListener() throws Exception {
        this.nButton.setText(this.constants.nButton);
        this.nButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") == 1) {
                    if (validateObjectsRequired()) {
                        tools.saveAllData(null, false, dataToMerge);
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
                        tools.saveAllData(object, true, null);
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

    private void addBackListener() throws Exception {
        this.bButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") == 1) {
                    if (validateObjectsRequired()) {
                        tools.saveAllData(null, false, dataToMerge);
                        if (currentForm > 0) {
                            bof = -1;
                            try {
                                removeCurrentForm();
                            } catch (Exception e) {
                                catchInfo = "addBackListenerException1";
                            }
                        } else {
                            returnParent(-1);
                        }
                    }
                } else {
                    if (validateObjectsRequired()) {
                        tools.saveAllData(object, true, null);
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

    private void addPauseListener() throws Exception {
        this.pButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (bundle.getInt("case") != 0) {
                    returnParent(-1);
                } else {
                    returnParent(1);
                }
            }
        });
    }

    private void parseToFButton(final int call) throws Exception {
        nButton.setText(this.constants.sButton);
        nButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    if (call == 3) {
                        if (validateObjectsRequired()) {
                            tools.saveAllData(object, true, null);
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

    private void returnParent(int s) {
        Intent i = new Intent();
        Bundle b = new Bundle();
        if (s == 1) {
            b.putInt("RESULT", (s + isEdit) * this.bundle.getInt("case"));
            b.putString("target", this.bundle.getString("target"));
            b.putBoolean("OPEN", false);
            i.putExtras(b);
            setResult(RESULT_OK, i);
        } else if (s == -1) {
            b.putBoolean("OPEN", tools.started);
            b.putString("target", this.bundle.getString("target"));
            i.putExtras(b);
            setResult(RESULT_CANCELED, i);
        } else if (s == 2) {
            b.putInt("RESULT", 3);
            i.putExtras(b);
            setResult(RESULT_OK, i);
        }
        resetVariables();
        finish();
        super.onStop();
    }

    private void resetVariables() {
        ibClass = null;
        structureObject = null;
        constants = null;
        tools = null;
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

    private int getFormIndex(int formId) {
        int index = 0;
        for (String[] forms_tmp : this.structureObject.forms) {
            if (Integer.parseInt(forms_tmp[0]) == formId) {
                return index;
            }
            index++;
        }
        return index;
    }

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

    private void decrementCurrentForm() {
        this.currentForm--;
    }

    private void incrementCurrentForm() throws Exception {
        this.currentForm++;
    }

    private LinearLayout createHeader(String tittle, String parent) throws Exception {
        LinearLayout rl = new LinearLayout(getApplicationContext());
        rl.setLayoutParams(new LinearLayout.LayoutParams(displayWidth, ViewGroup.LayoutParams.WRAP_CONTENT));
        rl.setOrientation(LinearLayout.VERTICAL);
        TextView tv = new TextView(getApplicationContext());
        RelativeLayout separator = new RelativeLayout(this);
        Typeface tf = Typeface.createFromAsset(getAssets(), "NotoSans-Regular.ttf");
        //tittle = (tittle.substring(0,1).toUpperCase()+tittle.substring(1).toLowerCase());
        tv.setText(tittle.toUpperCase());
        tv.setTextColor(Color.parseColor("#ff00c134"));
        tv.setTextSize(22);
        tv.setGravity(Gravity.CENTER);
        tv.setTypeface(tf);
        tv.setBackgroundResource(R.drawable.textview_1);
        separator.setBackgroundColor(Color.parseColor("#ff00c134"));
        separator.setLayoutParams(new LinearLayout.LayoutParams(displayWidth - 50, 2));
        separator.setGravity(Gravity.TOP);
        tv.setPadding(0, 0, 0, 0);
        separator.setPadding(0, 0, 0, 25);
        if (!tittle.equals("~")) {
            rl.addView(tv);
        }
        rl.addView(separator);
        rl.setGravity(Gravity.CENTER_HORIZONTAL);
        //Log.e("Inside", parent);
        if (parent.equals("0")) {
            rl.setBackgroundColor(Color.WHITE);
            rl.setPadding(0, 20, 0, 30);
        }
        return rl;
    }

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

    @Override
    public void onBackPressed() {
    }

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

    private void close() {
        finish();
    }

    @Override
    protected void onStop() {
        super.onStop();
    }
}