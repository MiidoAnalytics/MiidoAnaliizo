package com.miido.miido;

import android.app.Activity;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.WindowManager;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TabHost;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.masterTools;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

public class statistics extends Activity {

    constants constants;
    masterTools tools;
    TabHost th;
    private JSONObject formMaster;
    private JSONArray prefix;

    private JSONObject interviewers;

    protected Bundle bundle;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_statistics);
        if(init()){}
    }

    protected Boolean init(){
        try {
            this.initInstances();
        } catch (Exception e){
            Log.e("Error1", e.getMessage());
            return false;
        }
        try {
            this.createTabs();
        } catch (Exception e){
            Log.e("Error2", e.getMessage());
            return false;
        }
        try {
            this.deployResults();
        } catch (Exception e){
            Log.e("Error3", e.getMessage());
            return false;
        }
        try {
            this.fill();
        } catch (Exception e){
            Log.e("Error4", e.getMessage());
            return false;
        }
        try {
            this.fillGroups();
        } catch (Exception e){
            Log.e("Error5", e.getMessage());
            return false;
        }
        try {
            this.fillPersons();
        } catch (Exception e){
            Log.e("Error6", e.getMessage());
            return false;
        }
        return true;
    }

    private void initInstances() throws Exception {
        this.constants = new constants();
        this.tools = new masterTools(getApplicationContext());
        this.th = (TabHost) findViewById (R.id.tabHost);
        this.formMaster = new JSONObject();
        this.prefix = new JSONArray();
        this.bundle = getIntent().getExtras();
        this.interviewers = new JSONObject();
        th.setup();
    }

    private void createTabs() throws Exception {
        TabHost.TabSpec specs = this.th.newTabSpec(this.constants.persons);
        specs.setContent(R.id.persons);
        specs.setIndicator("", getResources().getDrawable(R.drawable.personsdrawable));
        this.th.addTab(specs);
        specs = this.th.newTabSpec(this.constants.groupo);
        specs.setContent(R.id.groups);
        specs.setIndicator("", getResources().getDrawable(R.drawable.ic_people_black_48dp));
        this.th.addTab(specs);
    }

    protected void deployResults(){
        loadData();
    }

    private void loadData() {
        tools.setResuming(false);
        if (tools.corLocalConfig()) {
            if (tools.focPrefixes()) {
                int count = 0;
                for (int a = tools.sHomePrefix; a < tools.cHomePrefix; a++) {
                    try {
                        if (tools.getJSONFile(a + ".HOME", true)) {
                            formMaster.put("MASTER" + count, tools.getFormMaster().getJSONObject("DOCUMENTINFO"));
                            JSONArray ja_tmp = tools.getFormMaster().getJSONArray("PERSON");
                            int b;
                            int p = 0;
                            for ( b = 1; b < ja_tmp.length(); b += 2) {
                                if(ja_tmp.getBoolean(b)){
                                    p++;
                                }
                            }
                            formMaster.getJSONObject("MASTER" + count).put("PERSONPAUSED", p);
                            formMaster.getJSONObject("MASTER" + count).put("PERSONCOUNTER", ((b-1)/2));
                            prefix.put(a);
                            count++;
                        }
                    } catch (Exception e) {
                    }
                }
                if (count > 0) {
                    //Vacio todo
                } else {
                    //Datos encontrados
                }
            } else {
                //Error en la extraccion de prefijos
            }
        } else {
            //Error en la carga de informacion local
        }
        //Log.e("formMaster", formMaster.toString());
    }

    private void fill() throws JSONException, IndexOutOfBoundsException{
        String username;
        try {
            username = bundle.getString("username");
        } catch (Exception e){
            username = "";

        }
        interviewers.put("users", new JSONArray());
        for (int a = 0; a < formMaster.length(); a++){
            JSONObject tmp = formMaster.getJSONObject("MASTER"+a);
            if(tmp.getString("Username").equals(username)) {
                try {
                    interviewers.get(tmp.getString("Username"));
                } catch (Exception e) {
                    interviewers.put(tmp.getString("Username"), new JSONObject());
                    interviewers.getJSONObject(tmp.getString("Username")).put("map", new JSONArray());
                    interviewers.getJSONObject(tmp.getString("Username")).put("groups", 0);
                    interviewers.getJSONObject(tmp.getString("Username")).put("paused", 0);
                    interviewers.getJSONObject(tmp.getString("Username")).put("gPaused", 0);
                    interviewers.getJSONObject(tmp.getString("Username")).put("persons", 0);
                    interviewers.getJSONObject(tmp.getString("Username")).put("deleted", 0);
                    interviewers.getJSONArray("users").put(tmp.getString("Username"));
                }
                JSONObject iTmp = interviewers.getJSONObject(tmp.getString("Username"));
                if (!tmp.getBoolean("Usable"))
                    iTmp.put("deleted", iTmp.getInt("deleted") + 1);
                else {
                    iTmp.put("groups", iTmp.getInt("groups") + 1);
                    iTmp.put("paused", iTmp.getInt("paused") + tmp.getInt("PERSONPAUSED"));
                    if (tmp.getInt("PERSONPAUSED") > 0) {
                        iTmp.put("gPaused", iTmp.getInt("gPaused") + 1);
                    }
                    iTmp.put("persons", iTmp.getInt("persons") + tmp.getInt("PERSONCOUNTER"));
                    String[] created = tmp.getString("Created").split(" ");
                    tmp.put("Created", created[0]);
                    try {
                        iTmp.getInt(tmp.getString("Created") + "_P");
                        iTmp.put("map", new JSONArray());
                        iTmp.put(tmp.getString("Created") + "_P", iTmp.getInt(tmp.getString("Created") + "_P") + tmp.getInt("PERSONCOUNTER"));
                        iTmp.put(tmp.getString("Created") + "_G", iTmp.getInt(tmp.getString("Created") + "_G") + 1);
                        iTmp.getJSONArray("map").put(tmp.getString("Created"));
                    } catch (JSONException je) {
                        iTmp.put(tmp.getString("Created") + "_P", tmp.getInt("PERSONCOUNTER"));
                        iTmp.put(tmp.getString("Created") + "_G", 1);
                    }
                }
            }
        }
    }

    private void fillGroups() throws Exception{

        ScrollView parentG = (ScrollView) findViewById(R.id.groups);
        TableLayout tableG = new TableLayout(this);
        final TableRow[]  rowsG  = new TableRow[8];
        ImageView[] imgG = new ImageView[8];

        rowsG[0] = new TableRow(this);
        rowsG[1] = new TableRow(this);
        rowsG[2] = new TableRow(this);
        rowsG[3] = new TableRow(this);
        rowsG[4] = new TableRow(this);
        rowsG[5] = new TableRow(this);
        rowsG[6] = new TableRow(this);
        rowsG[7] = new TableRow(this);

        imgG[0] = new ImageView(this);
        imgG[1] = new ImageView(this);
        imgG[2] = new ImageView(this);
        imgG[3] = new ImageView(this);
        imgG[4] = new ImageView(this);
        imgG[5] = new ImageView(this);
        imgG[6] = new ImageView(this);
        imgG[7] = new ImageView(this);

        LinearLayout llG    = new LinearLayout(getApplicationContext());
        LinearLayout llG1   = new LinearLayout(getApplicationContext());
        LinearLayout llG7   = new LinearLayout(getApplicationContext());
        LinearLayout llG15   = new LinearLayout(getApplicationContext());
        LinearLayout llG30  = new LinearLayout(getApplicationContext());
        LinearLayout llG365 = new LinearLayout(getApplicationContext());
        LinearLayout llPd = new LinearLayout(getApplicationContext());
        LinearLayout llDd = new LinearLayout(getApplicationContext());

        TextView[] tvI = new TextView[8];
        tvI[0] = new TextView(this);
        tvI[1] = new TextView(this);
        tvI[2] = new TextView(this);
        tvI[3] = new TextView(this);
        tvI[4] = new TextView(this);
        tvI[5] = new TextView(this);
        tvI[6] = new TextView(this);
        tvI[7] = new TextView(this);

        TextView[] tvG = new TextView[8];
        tvG[0] = new TextView(this);
        tvG[1] = new TextView(this);
        tvG[2] = new TextView(this);
        tvG[3] = new TextView(this);
        tvG[4] = new TextView(this);
        tvG[5] = new TextView(this);
        tvG[6] = new TextView(this);
        tvG[7] = new TextView(this);

        imgG[0].setImageResource(R.drawable.ic_home_black_48dp);
        imgG[1].setImageResource(R.drawable.ic_pause_circle_filled_black_48dp);
        imgG[2].setImageResource(R.drawable.ic_delete_black_48dp);
        imgG[3].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgG[4].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgG[5].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgG[6].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgG[7].setImageResource(R.drawable.ic_assignment_black_48dp);

        imgG[0].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[1].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[2].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[3].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[4].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[5].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[6].setColorFilter(Color.parseColor("#ff00c134"));
        imgG[7].setColorFilter(Color.parseColor("#ff00c134"));

        rowsG[0].setBackgroundResource(R.drawable.layoutindic);
        rowsG[1].setBackgroundResource(R.drawable.layoutindic);
        rowsG[2].setBackgroundResource(R.drawable.layoutindic);
        rowsG[3].setBackgroundResource(R.drawable.layoutindic);
        rowsG[4].setBackgroundResource(R.drawable.layoutindic);
        rowsG[5].setBackgroundResource(R.drawable.layoutindic);
        rowsG[6].setBackgroundResource(R.drawable.layoutindic);
        rowsG[7].setBackgroundResource(R.drawable.layoutindic);

        llG.setOrientation(LinearLayout.VERTICAL);
        llG1.setOrientation(LinearLayout.VERTICAL);
        llG7.setOrientation(LinearLayout.VERTICAL);
        llG15.setOrientation(LinearLayout.VERTICAL);
        llG30.setOrientation(LinearLayout.VERTICAL);
        llG365.setOrientation(LinearLayout.VERTICAL);
        llPd.setOrientation(LinearLayout.VERTICAL);
        llDd.setOrientation(LinearLayout.VERTICAL);

        llG.addView(tvI[0]);
        llG1.addView(tvI[1]);
        llG7.addView(tvI[2]);
        llG30.addView(tvI[3]);
        llG365.addView(tvI[4]);
        llG15.addView(tvI[5]);
        llPd.addView(tvI[6]);
        llDd.addView(tvI[7]);

        llG.addView(tvG[0]);
        llG1.addView(tvG[1]);
        llG7.addView(tvG[2]);
        llG30.addView(tvG[3]);
        llG365.addView(tvG[4]);
        llG15.addView(tvG[5]);
        llPd.addView(tvG[6]);
        llDd.addView(tvG[7]);

        rowsG[0].addView(imgG[0], 0);
        rowsG[1].addView(imgG[1], 0);
        rowsG[2].addView(imgG[2], 0);
        rowsG[3].addView(imgG[3], 0);
        rowsG[4].addView(imgG[4], 0);
        rowsG[5].addView(imgG[5], 0);
        rowsG[6].addView(imgG[6], 0);
        rowsG[7].addView(imgG[7], 0);

        rowsG[0].addView(llG, 1);
        rowsG[1].addView(llPd, 1);
        rowsG[2].addView(llDd, 1);
        rowsG[3].addView(llG1, 1);
        rowsG[4].addView(llG7, 1);
        rowsG[5].addView(llG15, 1);
        rowsG[6].addView(llG30, 1);
        rowsG[7].addView(llG365, 1);

        tableG.addView(rowsG[0]);
        tableG.addView(rowsG[1]);
        tableG.addView(rowsG[2]);
        tableG.addView(rowsG[3]);
        tableG.addView(rowsG[4]);
        tableG.addView(rowsG[5]);
        tableG.addView(rowsG[6]);
        tableG.addView(rowsG[7]);

        parentG.addView(tableG);

        tableG.setShrinkAllColumns(true);
        tableG.setStretchAllColumns(true);

        Calendar c = Calendar.getInstance();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd", Locale.US);
        String formattedDate = df.format(c.getTime());
        c.set(Calendar.DAY_OF_WEEK, c.getFirstDayOfWeek());
        String startWeek = df.format(c.getTime());
        c = Calendar.getInstance();
        c.add(Calendar.DAY_OF_YEAR, -15);
        String startFortnight = df.format(c.getTime());
        c.set(Calendar.DAY_OF_MONTH, 1);
        String startMonth = df.format(c.getTime());
        c.set(Calendar.MONTH, 0);
        String startYear = df.format(c.getTime());
        JSONArray users = interviewers.getJSONArray("users");
        int deleted = 0;
        int paused = 0;
        int daily, weekly, fortnight, monthly, yearly, other;
        daily = weekly = fortnight = monthly = yearly = other =0;

        for (int a = 0; users.length() > a; a++){
            JSONArray map = interviewers.getJSONObject(users.getString(a)).getJSONArray("map");
            paused = interviewers.getJSONObject(users.getString(a)).getInt("gPaused");
            deleted = interviewers.getJSONObject(users.getString(a)).getInt("deleted");
            for (int b = 0; map.length() > b; b++){
                if (formattedDate.equals(map.getString(b))){
                    daily += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_G");
                } else if (df.parse(startWeek).before(df.parse(map.getString(b)))){
                    weekly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_G");
                } else if(df.parse(startFortnight).before(df.parse(map.getString(b)))){
                    fortnight += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_G");
                } else if(df.parse(startMonth).before(df.parse(map.getString(b)))){
                    monthly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_G");
                } else if(df.parse(startYear).before(df.parse(map.getString(b)))) {
                    yearly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b) + "_G");
                } else {
                    other += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b) + "_G");
                }
            }
        }

        //other -= deleted;

        c.setTime(df.parse(startWeek));
        c.add(Calendar.DAY_OF_YEAR, 1);
        startWeek = df.format(c.getTime());

        tvI[0].setText("" + (other + yearly + monthly + fortnight + weekly + daily));
        tvI[0].setTextColor(Color.parseColor("#ff00c134"));
        tvI[0].setTextSize(40);
        tvG[0].setText("Grupos Familiares encuestados desde este terminal.");
        tvG[0].setTextColor(Color.GRAY);
        tvI[6].setText("" + paused);
        tvI[6].setTextColor(Color.parseColor("#ff00c134"));
        tvI[6].setTextSize(40);
        tvG[6]  .setText("Encuestas que tienen personas pausadas por el encuestador");
        tvG[6].setTextColor(Color.GRAY);
        tvI[7].setText("" + deleted);
        tvI[7].setTextColor(Color.parseColor("#ff00c134"));
        tvI[7].setTextSize(40);
        tvG[7]  .setText("Grupos familiares eliminados por el encuestador");
        tvG[7].setTextColor(Color.GRAY);

        tvI[1].setText("" + daily);
        tvI[1].setTextColor(Color.parseColor("#ff00c134"));
        tvI[1].setTextSize(40);
        tvG[1].setText("Grupos familiares encuestados hoy " + formattedDate);
        tvG[1].setTextColor(Color.GRAY);
        tvI[2].setText("" + (weekly + daily));
        tvI[2].setTextColor(Color.parseColor("#ff00c134"));
        tvI[2].setTextSize(40);
        tvG[2].setText("Grupos familiares encuestados esta semana del " + startWeek + " hasta " + formattedDate);
        tvG[2].setTextColor(Color.GRAY);
        tvI[3].setText("" + (monthly + weekly + daily));
        tvI[3].setTextColor(Color.parseColor("#ff00c134"));
        tvI[3].setTextSize(40);
        tvG[3].setText("Grupos familiares encuestados este mes, del " + startMonth + " hasta " + formattedDate);
        tvG[3].setTextColor(Color.GRAY);
        tvI[4].setText("" + (yearly + monthly + fortnight + weekly + daily));
        tvI[4].setTextColor(Color.parseColor("#ff00c134"));
        tvI[4].setTextSize(40);
        tvG[4].setText("Grupos familiares encuestados este anio");
        tvG[4].setTextColor(Color.GRAY);
        tvI[5].setText("" + (fortnight + weekly + daily));
        tvI[5].setTextColor(Color.parseColor("#ff00c134"));
        tvI[5].setTextSize(40);
        tvG[5].setText("Grupos familiares encuestados en los ultimos 15 dias");
        tvG[5].setTextColor(Color.GRAY);

        llG.getLayoutParams().height = 110;//200;
        llG.getLayoutParams().width  = getResources().getDisplayMetrics().widthPixels-120;
        llG1.getLayoutParams().height = 110;
        llG7.getLayoutParams().height = 110;
        llG15.getLayoutParams().height = 110;
        llG30.getLayoutParams().height = 110;
        llG365.getLayoutParams().height = 110;
        llPd.getLayoutParams().height = 110;
        llDd.getLayoutParams().height = 110;

        TableLayout.LayoutParams tableRowParams= new TableLayout.LayoutParams(TableLayout.LayoutParams.FILL_PARENT,TableLayout.LayoutParams.WRAP_CONTENT);
        tableRowParams.setMargins(0, 5, 0, 0);

        rowsG[0].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[1].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[2].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[3].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[4].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[5].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[6].setGravity(Gravity.CENTER_VERTICAL);
        rowsG[7].setGravity(Gravity.CENTER_VERTICAL);

        rowsG[0].setLayoutParams(tableRowParams);
        rowsG[1].setLayoutParams(tableRowParams);
        rowsG[2].setLayoutParams(tableRowParams);
        rowsG[3].setLayoutParams(tableRowParams);
        rowsG[4].setLayoutParams(tableRowParams);
        rowsG[5].setLayoutParams(tableRowParams);
        rowsG[6].setLayoutParams(tableRowParams);
        rowsG[7].setLayoutParams(tableRowParams);
    }

    private void fillPersons() throws Exception {
        ScrollView parentP = (ScrollView) findViewById(R.id.persons);

        TableLayout tableP = new TableLayout(this);
        TableRow[] rowT = new TableRow[8];
        ImageView[] imgP = new ImageView[8];
        LinearLayout[] llP = new LinearLayout[8];
        TextView[] quantyP = new TextView[8];
        TextView[] descP = new TextView[8];

        TableLayout.LayoutParams tableRowParams= new TableLayout.LayoutParams(TableLayout.LayoutParams.FILL_PARENT,TableLayout.LayoutParams.WRAP_CONTENT);

        tableP.setStretchAllColumns(true);
        tableP.setShrinkAllColumns(true);

        tableRowParams.setMargins(0, 5, 0, 0);

        rowT[0] = new TableRow(this);
        rowT[1] = new TableRow(this);
        rowT[2] = new TableRow(this);
        rowT[3] = new TableRow(this);
        rowT[4] = new TableRow(this);
        rowT[5] = new TableRow(this);
        rowT[6] = new TableRow(this);
        rowT[7] = new TableRow(this);

        rowT[0].setGravity(Gravity.CENTER_VERTICAL);
        rowT[1].setGravity(Gravity.CENTER_VERTICAL);
        rowT[2].setGravity(Gravity.CENTER_VERTICAL);
        rowT[3].setGravity(Gravity.CENTER_VERTICAL);
        rowT[4].setGravity(Gravity.CENTER_VERTICAL);
        rowT[5].setGravity(Gravity.CENTER_VERTICAL);
        rowT[6].setGravity(Gravity.CENTER_VERTICAL);
        rowT[7].setGravity(Gravity.CENTER_VERTICAL);

        rowT[0].setLayoutParams(tableRowParams);
        rowT[1].setLayoutParams(tableRowParams);
        rowT[2].setLayoutParams(tableRowParams);
        rowT[3].setLayoutParams(tableRowParams);
        rowT[4].setLayoutParams(tableRowParams);
        rowT[5].setLayoutParams(tableRowParams);
        rowT[6].setLayoutParams(tableRowParams);
        rowT[7].setLayoutParams(tableRowParams);

        rowT[0].setBackgroundResource(R.drawable.layoutindic);
        rowT[1].setBackgroundResource(R.drawable.layoutindic);
        rowT[2].setBackgroundResource(R.drawable.layoutindic);
        rowT[3].setBackgroundResource(R.drawable.layoutindic);
        rowT[4].setBackgroundResource(R.drawable.layoutindic);
        rowT[5].setBackgroundResource(R.drawable.layoutindic);
        rowT[6].setBackgroundResource(R.drawable.layoutindic);
        rowT[7].setBackgroundResource(R.drawable.layoutindic);

        imgP[0] = new ImageView(this);
        imgP[1] = new ImageView(this);
        imgP[2] = new ImageView(this);
        imgP[3] = new ImageView(this);
        imgP[4] = new ImageView(this);
        imgP[5] = new ImageView(this);
        imgP[6] = new ImageView(this);
        imgP[7] = new ImageView(this);

        imgP[0].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[1].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[2].setImageResource(R.drawable.ic_pause_circle_filled_black_48dp);
        imgP[3].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[4].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[5].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[6].setImageResource(R.drawable.ic_assignment_black_48dp);
        imgP[7].setImageResource(R.drawable.ic_assignment_black_48dp);

        imgP[0].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[1].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[2].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[3].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[4].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[5].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[6].setColorFilter(Color.parseColor("#ff00c134"));
        imgP[7].setColorFilter(Color.parseColor("#ff00c134"));

        llP[0] = new LinearLayout(this);
        llP[1] = new LinearLayout(this);
        llP[2] = new LinearLayout(this);
        llP[3] = new LinearLayout(this);
        llP[4] = new LinearLayout(this);
        llP[5] = new LinearLayout(this);
        llP[6] = new LinearLayout(this);
        llP[7] = new LinearLayout(this);

        llP[0].setOrientation(LinearLayout.VERTICAL);
        llP[1].setOrientation(LinearLayout.VERTICAL);
        llP[2].setOrientation(LinearLayout.VERTICAL);
        llP[3].setOrientation(LinearLayout.VERTICAL);
        llP[4].setOrientation(LinearLayout.VERTICAL);
        llP[5].setOrientation(LinearLayout.VERTICAL);
        llP[6].setOrientation(LinearLayout.VERTICAL);
        llP[7].setOrientation(LinearLayout.VERTICAL);

        quantyP[0] = new TextView(this);
        quantyP[1] = new TextView(this);
        quantyP[2] = new TextView(this);
        quantyP[3] = new TextView(this);
        quantyP[4] = new TextView(this);
        quantyP[5] = new TextView(this);
        quantyP[6] = new TextView(this);
        quantyP[7] = new TextView(this);

        quantyP[0].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[1].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[2].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[3].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[4].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[5].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[6].setTextColor(Color.parseColor("#ff00c134"));
        quantyP[7].setTextColor(Color.parseColor("#ff00c134"));

        quantyP[0].setTextSize(40);
        quantyP[1].setTextSize(40);
        quantyP[2].setTextSize(40);
        quantyP[3].setTextSize(40);
        quantyP[4].setTextSize(40);
        quantyP[5].setTextSize(40);
        quantyP[6].setTextSize(40);
        quantyP[7].setTextSize(40);

        descP[0] = new TextView(this);
        descP[1] = new TextView(this);
        descP[2] = new TextView(this);
        descP[3] = new TextView(this);
        descP[4] = new TextView(this);
        descP[5] = new TextView(this);
        descP[6] = new TextView(this);
        descP[7] = new TextView(this);

        descP[0].setTextColor(Color.GRAY);
        descP[1].setTextColor(Color.GRAY);
        descP[2].setTextColor(Color.GRAY);
        descP[3].setTextColor(Color.GRAY);
        descP[4].setTextColor(Color.GRAY);
        descP[5].setTextColor(Color.GRAY);
        descP[6].setTextColor(Color.GRAY);
        descP[7].setTextColor(Color.GRAY);

        parentP.addView(tableP);

        tableP.addView(rowT[0]);
        tableP.addView(rowT[1]);
        tableP.addView(rowT[2]);
        tableP.addView(rowT[3]);
        tableP.addView(rowT[4]);
        tableP.addView(rowT[5]);
        tableP.addView(rowT[6]);
        tableP.addView(rowT[7]);

        rowT[0].addView(imgP[0], 0);
        rowT[1].addView(imgP[1], 0);
        rowT[2].addView(imgP[2], 0);
        rowT[3].addView(imgP[3], 0);
        rowT[4].addView(imgP[4], 0);
        rowT[5].addView(imgP[5], 0);
        rowT[6].addView(imgP[6], 0);
        rowT[7].addView(imgP[7], 0);

        rowT[0].addView(llP[0], 1);
        rowT[1].addView(llP[1], 1);
        rowT[2].addView(llP[2], 1);
        rowT[3].addView(llP[3], 1);
        rowT[4].addView(llP[4], 1);
        rowT[5].addView(llP[5], 1);
        rowT[6].addView(llP[6], 1);
        rowT[7].addView(llP[7], 1);

        llP[0].addView(quantyP[0]);
        llP[1].addView(quantyP[1]);
        llP[2].addView(quantyP[2]);
        llP[3].addView(quantyP[3]);
        llP[4].addView(quantyP[4]);
        llP[5].addView(quantyP[5]);
        llP[6].addView(quantyP[6]);
        llP[7].addView(quantyP[7]);

        llP[0].addView(descP[0]);
        llP[1].addView(descP[1]);
        llP[2].addView(descP[2]);
        llP[3].addView(descP[3]);
        llP[4].addView(descP[4]);
        llP[5].addView(descP[5]);
        llP[6].addView(descP[6]);
        llP[7].addView(descP[7]);

        Calendar c = Calendar.getInstance();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd", Locale.US);
        String formattedDate = df.format(c.getTime());
        c.set(Calendar.DAY_OF_WEEK, c.getFirstDayOfWeek());
        String startWeek = df.format(c.getTime());
        c = Calendar.getInstance();
        c.add(Calendar.DAY_OF_YEAR, -15);
        String startFortnight = df.format(c.getTime());
        c.set(Calendar.DAY_OF_MONTH, 1);
        String startMonth = df.format(c.getTime());
        c.set(Calendar.MONTH, 0);
        String startYear = df.format(c.getTime());
        JSONArray users = interviewers.getJSONArray("users");
        int paused, persons;
        paused = persons = 0;
        int daily, weekly, fortnight, monthly, yearly;
        daily = weekly = fortnight = monthly = yearly = 0;

        for (int a = 0; users.length() > a; a++){
            JSONArray map = interviewers.getJSONObject(users.getString(a)).getJSONArray("map");
            paused = interviewers.getJSONObject(users.getString(a)).getInt("paused");
            persons = interviewers.getJSONObject(users.getString(a)).getInt("persons");
            for (int b = 0; map.length() > b; b++){
                if (formattedDate.equals(map.getString(b))){
                    daily += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_P");
                } else if (df.parse(startWeek).before(df.parse(map.getString(b)))){
                    weekly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_P");
                } else if(df.parse(startFortnight).before(df.parse(map.getString(b)))){
                    fortnight += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_P");
                } else if(df.parse(startMonth).before(df.parse(map.getString(b)))){
                    monthly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b)+"_P");
                } else if(df.parse(startYear).before(df.parse(map.getString(b)))) {
                    yearly += interviewers.getJSONObject(users.getString(a)).getInt(map.getString(b) + "_P");
                }
            }
        }

        quantyP[0].setText(""+persons);
        descP[0].setText("Personas encuestadas desde este terminal");
        quantyP[1].setText("" + (persons - paused));
        descP[1].setText("Personas encuestadas completamente");
        quantyP[2].setText("" + paused);
        descP[2].setText("Personas pausadas con encuesta incompleta");

        quantyP[3].setText(""+daily);
        descP[3]  .setText("Personas encuestadas hoy");
        quantyP[4].setText(""+weekly);
        descP[4].setText("Personas encuestadas esta semana");
        quantyP[5].setText("" +fortnight);
        descP[5].setText("Personas encuestadas en los ultimos 15 dias");
        quantyP[6].setText("" + monthly);
        descP[6]  .setText("Personas encuestadas este mes");
        quantyP[7].setText("" + yearly);
        descP[7]  .setText("Personas encuestadas este anho");

        llP[0].getLayoutParams().height = 110;
        llP[0].getLayoutParams().width  = getResources().getDisplayMetrics().widthPixels-115;
        llP[1].getLayoutParams().height = 110;
        llP[2].getLayoutParams().height = 110;
        llP[3].getLayoutParams().height = 110;
        llP[4].getLayoutParams().height = 110;
        llP[5].getLayoutParams().height = 110;
        llP[6].getLayoutParams().height = 110;
        llP[7].getLayoutParams().height = 110;
    }
}
