package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
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

import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.masterTools;
import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;
import org.json.JSONObject;

import java.lang.reflect.Field;
import java.text.SimpleDateFormat;
import java.util.Date;

public class master extends Activity {

    ArrayAdapter<String> adapter;
    AlertDialog.Builder ad;
    private sqlHelper sqlHelper;
    private ScrollView fSv;
    private ScrollView sv;
    private ScrollView hSv;
    private LinearLayout ll;
    private AutoCompleteTextView et;
    private masterTools tools;
    private constants constants;
    private ProgressDialog pd;
    private RelativeLayout masterRL;
    private LinearLayout[] llOptions;
    private ImageButton sOptions;
    private Button[] bOptions;
    private Boolean toggle;
    private LocationManager l_m;
    private LocationListener l_l;
    private Boolean i_c;
    private String id = "";
    private int action = 0;

    private int resume;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_master);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        Bundle bundle;
        Boolean resuming;
        i_c = false;
        l_m = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        getLocationData();
        bundle = getIntent().getExtras();
        try {
            this.resume = bundle.getInt("resume");
            //resuming = true; //only for developer mode
            resuming = bundle.getBoolean("resuming");
        } catch (Exception e) {
            resuming = false;
        }
        sqlHelper = new sqlHelper(getApplicationContext());
        sqlHelper.OOCDB();
        this.constants = new constants();
        try {
            setInstances();
            tools.setUserData(bundle.getString(constants.user_id), bundle.getString(constants.username));
            if (!(resuming)) {
                this.tools.setResuming(false);
                if (this.tools.init()) {
                    //Log.e("HomePrefix", tools.cHomePrefix + "");
                    createIntent(tools.cHomePrefix + "." + constants.home + "." + tools.constants.localFile_name, 0);
                    createProgressDialog();
                    createAlertDialogPropForSearch();
                }
            } else {
                this.tools.setResuming(true);
                this.tools.setResumingPrefix(this.resume);
                init();
            }
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
        }
    }

    private void getLocationData() {
        Log.i("gpsData", "Starting");
        l_l = new LocationListener() {
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
                                    JSONObject tmpFM = tools.getFormMaster();
                                    try {
                                        JSONObject locate = new JSONObject();
                                        locate.put(constants.latitude, location.getLatitude());
                                        locate.put(constants.longitude, location.getLongitude());
                                        locate.put(constants.accuracy, location.getAccuracy());
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.location, locate);
                                        tools.setFormMaster(tmpFM);
                                        if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                                            Log.i("gpsData", "location defined and saved");
                                            l_m.removeUpdates(l_l);
                                            break;
                                        } else {
                                            Log.i("gpsData", "location could not saved");
                                            try {
                                                Thread.sleep(2000);
                                            } catch (InterruptedException ie) {
                                                Log.i("gpsData", "InterruptedException::" + ie.getMessage());
                                                l_m.removeUpdates(l_l);
                                                break;
                                            }
                                        }
                                    } catch (Exception e) {
                                        Log.i("gpsData", "Error while saving data::" + e.getMessage());
                                        try {
                                            Thread.sleep(2000);
                                        } catch (InterruptedException ie) {
                                            Log.i("gpsData", "InterruptedException::" + ie.getMessage());
                                            l_m.removeUpdates(l_l);
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
        l_m.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, l_l);
    }

    private void createAlertDialogPropForSearch() {
        showProgressDialog();
        android.os.Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                AlertDialogProp(1, constants._EM001, "");
            }
        }, 1000);
    }

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

    private void showProgressDialog() {
        this.pd.show();
    }

    private void dismissProgressDialog() {
        this.pd.dismiss();
    }

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
            if (this.tools.init()) {
                info = "family";
                family = this.tools.FamilyDescription();
                setLayoutParams(family);
                this.fSv.addView(family);
                info = "person";
                person = this.tools.PersonDescription(this.id, this.action);
                if(!tools.duplicated) {
                    info = "addperson";
                    this.ll.addView(person);
                    this.sv.addView(ll);
                    info = "home";
                    home = this.tools.HomeDescription();
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
            Toast.makeText(this, info + "\n" + e.toString(), Toast.LENGTH_LONG).show();
            Toast.makeText(this, info + "\n" + e.toString(), Toast.LENGTH_LONG).show();
            Toast.makeText(this, info + "\n" + e.toString(), Toast.LENGTH_LONG).show();
        }
    }

    private void createOptionsMenu() {
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

        bOptions[1] = new Button(getApplicationContext());
        bOptions[1].setText(constants.pButton);
        bOptions[1].setTextColor(Color.BLACK);
        bOptions[1].setBackgroundResource(R.drawable.button);

        bOptions[2] = new Button(getApplicationContext());
        bOptions[2].setText(constants.rButton);
        bOptions[2].setTextColor(Color.BLACK);
        bOptions[2].setBackgroundResource(R.drawable.button);

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
        llOptions[0].setX(getResources().getDisplayMetrics().widthPixels - 150);
        llOptions[0].setY(getResources().getDisplayMetrics().heightPixels - 85);

        createHandlerForOptions();
    }

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
                        JSONObject jo = tools.getHomeMaster();
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
                                if (tools.getObjects().length >= 2) {
                                    JSONObject tmpFM = tools.getFormMaster();
                                    String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                                    try {
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.finished, currentDateandTime);
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedStructureVersion, tools.structure.documentInfo[0]);
                                        tmpFM.getJSONObject(constants.doc_info).put(constants.FinishedAppVersion, constants.version_name + "." + constants.version_subname);
                                        try {
                                            tools.setFormMaster(tmpFM);
                                            if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                                                tools.cHomePrefix++;
                                                if (tools.sPrefixes()) {
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
                    //AlertDialogProp(3, constants.atention, constants._ADV016);
                    if ((tools.getFoundedPersons() == 0) && (validateIfTemp())) {
                        AlertDialogProp(2, constants.atention, constants._ADV017);
                    } else if (validatePausedPersons()) {
                        AlertDialogProp(3, constants.atention, constants._ADV016);
                    } else {
                        if (tools.getObjects().length > 2) {
                            AlertDialogProp(2, constants.error, constants._ADV015);
                        } else {
                            AlertDialogProp(2, constants.atention, constants._ADV017);
                        }
                    }
                } catch (Exception e) {
                    Toast.makeText(getApplicationContext(), e.getMessage(), Toast.LENGTH_LONG).show();
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
                        if (tools.getFoundedPersons() != 0) {
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

    @Override
    public void onBackPressed() {
    }

    private Boolean validatePausedPersons() throws Exception {
        Object[][] objects = tools.getObjects();
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

    private Boolean validateIfTemp() throws Exception {
        Object[][] objects = tools.getObjects();
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

    private void slideToggle() {
        if (!toggle) {
            sOptions.animate().setDuration(1100).rotation(90).start();
            llOptions[0].animate().translationY(getResources().getDisplayMetrics().heightPixels - 250).setDuration(1000).start();
            llOptions[2].animate().alpha(1).setDuration(1100).start();
            llOptions[3].animate().alpha(1).setDuration(1100).start();
            llOptions[4].animate().alpha(1).setDuration(1100).start();
            llOptions[2].animate().translationX(0).setDuration(1000).start();
            llOptions[3].animate().translationX(0).setDuration(1250).start();
            llOptions[4].animate().translationX(0).setDuration(1500).start();
            toggle = true;
        } else {
            sOptions.animate().setDuration(550).rotation(-90).start();
            llOptions[0].animate().translationY(getResources().getDisplayMetrics().heightPixels - 85).setDuration(500).start();
            llOptions[2].animate().alpha(0).setDuration(475).start();
            llOptions[3].animate().alpha(0).setDuration(475).start();
            llOptions[4].animate().alpha(0).setDuration(475).start();
            llOptions[2].animate().translationX(150).setDuration(700).start();
            llOptions[3].animate().translationX(150).setDuration(1000).start();
            llOptions[4].animate().translationX(150).setDuration(1300).start();
            toggle = false;
        }
    }

    private void createObjectsListener() {
        Object object;
        object = this.tools.getObject();
        try {
            //createHandler(object, tools.cHomePrefix + "." + constants.home + "." + tools.constants.localFile_name, 0);
            createHandler(tools.getHome(), tools.cHomePrefix + "." + constants.home + "." + tools.constants.localFile_name, 2);
        } catch (Exception e) {
            AlertDialogProp(2, constants.error, e.getMessage());
        }
        Object[][] objects;
        objects = this.tools.getObjects();
        for (Object[] object_tmp : objects) {
            try {
                createHandler(object_tmp[0], ((String) object_tmp[1]), 1);
            } catch (Exception e) {
                AlertDialogProp(2, constants.error, e.getMessage());
            }
        }
    }

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
                    if ((tools.cPersonPrefix + ".PERSON" + "." + tools.constants.localFile_name).equals(v.getContentDescription())) {
                        fSv.removeAllViews();
                        sv.removeAllViews();
                        hSv.removeAllViews();
                        masterRL.removeView(llOptions[0]);
                        createProgressDialog();
                        createAlertDialogPropForSearch();
                    } else {
                        createIntent(v.getContentDescription() + "", event);
                    }
                }
            });
        }
    }

    private void setLayoutParams(TableLayout tl_tmp) {
        tl_tmp.setBackgroundResource(R.drawable.layoutlv);
    }

    private void setPaddingContent() {
        this.fSv.setPadding(10, 10, 10, 10);
        this.sv.setPadding(10, 10, 10, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 50, 50, 10);
        this.hSv.setPadding(10, 10, 120, 10);
    }

    private void setInstances() throws Exception {
        this.fSv = ((ScrollView) findViewById(R.id.familyScroll));
        this.sv = ((ScrollView) findViewById(R.id.parentScrollView));
        this.hSv = ((ScrollView) findViewById(R.id.homeScrollView));
        this.ll = new LinearLayout(getApplicationContext());
        this.tools = new masterTools(getApplicationContext());
        this.pd = new ProgressDialog(this);
    }

    private void createIntent(String target, int event) {
        try {
            Intent i = new Intent(this.getApplicationContext(), main.class);
            try {
                i.removeExtra(constants.target);
                i.removeExtra(constants.case_id);
            } catch (Exception e2) {
                e2.printStackTrace();
            }
            i.putExtra(constants.target, target);
            i.putExtra(constants.case_id, event);
            this.startActivityForResult(i, 1);
        } catch (Exception e) {
            Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG).show();
        }
    }

    private void setProperties() {
        this.sv.setVerticalScrollBarEnabled(true);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                int result = data.getIntExtra(constants.result, 0);
                if (result == 1) {
                    try {
                        try {
                            tools.personMaster.length();
                        } catch (Exception e2) {
                            tools.personMaster = new JSONArray();
                        }
                        String[] target = data.getStringExtra(constants.target).split("~");
                        if (target.length > 2) {
                            this.id = "";
                        }
                        tools.personMaster.put(target[0]);
                        if (tools.personMaster.length() % 2 != 0) {
                            tools.personMaster.put(false);
                        }
                        if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                            if (tools.closePersonForm()) {
                                fSv.removeAllViews();
                                sv.removeAllViews();
                                hSv.removeAllViews();
                                masterRL.removeView(llOptions[0]);
                                init();
                            }
                        }
                    } catch (Exception e) {
                        Toast.makeText(this, e.getMessage(), Toast.LENGTH_LONG).show();
                    }
                } else if (result == 2) {
                    if (!(data.getBooleanExtra(constants.open, false))) {
                        Boolean f = false;
                        try {
                            String[] target = data.getStringExtra(constants.target).split("~");
                            if (target.length > 2) {
                                this.id = "";
                            }
                            for (int c = 0; c < tools.personMaster.length(); c++) {
                                try {
                                    String person = tools.personMaster.getString(c);
                                    if (person.length() > 0) {
                                        if (target[0].equals(person)) {
                                            f = true;
                                            tools.personMaster.put(c + 1, false);
                                            if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                                                tools.closePersonForm();
                                            }
                                        }
                                    }
                                } catch (Exception e) {
                                    AlertDialogProp(2, constants.error, e.getMessage());
                                }
                            }
                            if (!(f)) {
                                tools.personMaster.put(target[0]);
                                tools.personMaster.put(false);
                                if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                                    tools.closePersonForm();
                                }
                            }
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
                } else if (result == 3) {
                    Log.e("","");
                    /*createProgressDialog();
                    createAlertDialogPropForSearch();*/
                } else if (result == 4) {
                    Log.e("caseBack", "BackToParentOnHome");
                } else {
                    Toast.makeText(this, "Error al guardar la informacion "+result, Toast.LENGTH_LONG).show();
                }
            } else if (resultCode == RESULT_CANCELED) {
                if ((data.getBooleanExtra(constants.open, true))) {
                    String info = "";
                    try {
                        try {
                            info = "personMasterLength";
                            tools.personMaster.length();
                        } catch (Exception e2) {
                            info = "newPersonMaster";
                            tools.personMaster = new JSONArray();
                        }
                        info = "personMasterPut";
                        String[] target = data.getStringExtra(constants.target).split("~");
                        if (target.length > 2) {
                            this.id = "";
                        }

                        Boolean f = false;
                        try {
                            for (int c = 0; c < tools.personMaster.length(); c++) {
                                try {
                                    String person = tools.personMaster.getString(c);
                                    if (person.length() > 0) {
                                        if (target[0].equals(person)) {
                                            f = true;
                                            tools.personMaster.put(c + 1, true);
                                        }
                                    }
                                } catch (Exception e) {
                                    AlertDialogProp(2, constants.error, e.getMessage());
                                }
                            }
                            if (!(f)) {
                                tools.personMaster.put(target[0]);
                                tools.personMaster.put(true);
                            }
                        } catch (Exception e) {
                            AlertDialogProp(2, constants.error, e.getMessage());
                        }

                        if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                            info = "closePersonForm";
                            if (tools.closePersonForm()) {
                                fSv.removeAllViews();
                                sv.removeAllViews();
                                hSv.removeAllViews();
                                masterRL.removeView(llOptions[0]);
                                init();
                            }
                        }
                    } catch (Exception e) {
                        Toast.makeText(this, info + "\n" + e.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }
            }
        }
    }

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

    @SuppressWarnings("All")
    private void createAdapter() {
        //int option = 6;
        Cursor cursor;
        try {
            sqlHelper.setQuery(constants.QUERY_1.replace("[FIELDS]", "*").replace("[TABLE]", "persons"));
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
                dismissProgressDialog();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

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
                            createAdapter();
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
                        createIntent(tools.cPersonPrefix + ".PERSON" + "." + tools.constants.localFile_name, 1);
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
                        createIntent(tools.cPersonPrefix + ".PERSON" + "." + tools.constants.localFile_name, 1);
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
                        JSONObject tmpFM = tools.getFormMaster();
                        String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                        try {
                            tmpFM.getJSONObject(constants.doc_info).put(constants.paused, currentDateandTime);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedStructureVersion, tools.structure.documentInfo[0]);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedAppVersion, constants.version_name + "." + constants.version_subname);
                        } catch (Exception e) {
                        }
                        tools.setFormMaster(tmpFM);
                        if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                            tools.cHomePrefix++;
                            if (tools.sPrefixes()) {
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
                        JSONObject tmpFM = tools.getFormMaster();
                        String currentDateandTime = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
                        try {
                            tmpFM.getJSONObject(constants.doc_info).put(constants.paused, currentDateandTime);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedStructureVersion, tools.structure.documentInfo[0]);
                            tmpFM.getJSONObject(constants.doc_info).put(constants.PausedAppVersion, constants.version_name + "." + constants.version_subname);
                        } catch (Exception e) {
                        }
                        tools.setFormMaster(tmpFM);
                        if (tools.wLocalFile(tools.cHomePrefix + "." + constants.home, 1)) {
                            tools.cHomePrefix++;
                            if (tools.sPrefixes()) {
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

    private void close() {
        finish();
    }
}