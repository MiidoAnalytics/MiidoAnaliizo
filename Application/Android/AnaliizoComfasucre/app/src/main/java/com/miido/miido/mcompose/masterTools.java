package com.miido.miido.mcompose;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.CursorIndexOutOfBoundsException;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.util.Log;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.miido.R;
import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.FileOutputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.text.SimpleDateFormat;
import java.util.Date;


/**
 * *******************************
 * Created by Alvaro on 06/03/2015.
 * *******************************
 */
public class masterTools {

    public structure structure;
    public constants constants;
    public int sHomePrefix;
    public int cHomePrefix;
    public int sPersonPrefix;
    public int cPersonPrefix;
    public JSONArray personMaster;
    public  Boolean duplicated;
    private Context context;
    private SharedPreferences sp;
    private SharedPreferences.Editor spe;
    private InputStreamReader isrP;
    private BufferedReader br;
    private BufferedReader brP;
    private OutputStreamWriter osw;
    private int resumingPrefix;
    private int tmpPrefix;
    private Boolean resuming;
    private JSONObject formMaster;
    private JSONObject homeMaster;
    private JSONObject personDetail;

    private Object[][] objects;
    private Object object;
    private TableLayout homeTl;

    private String userId;
    private String username;
    private int pQuantity;

    public masterTools(Context context) {
        super();
        this.context = context;
        this.structure = new structure(this.context);
        this.constants = new constants();
        this.structure.setStructure();
        //l_d = false;
    }

    public Boolean init() throws Exception {
        if (corLocalConfig()) {
            if (focPrefixes()) {
                return true;
            }
        }
        return false;
    }

    @SuppressLint("CommitPrefEdits")
    public Boolean corLocalConfig() {
        try {
            sp = this.context.getSharedPreferences(this.constants.localSharedPreferences_name, Context.MODE_PRIVATE);
            spe = sp.edit();
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean focPrefixes() {
        try {
            sHomePrefix = sp.getInt("sHomePrefix", 0);
            if (resuming) {
                cHomePrefix = resumingPrefix;
                tmpPrefix = sp.getInt("cHomePrefix", 0);
            } else {
                cHomePrefix = sp.getInt("cHomePrefix", 0);
            }
            sPersonPrefix = sp.getInt("sPersonPrefix", 0);
            cPersonPrefix = sp.getInt("cPersonPrefix", 0);
            return this.sPrefixes();
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean sPrefixes() {
        try {
            spe.putInt("sHomePrefix", sHomePrefix);
            if (resuming) {
                spe.putInt("cHomePrefix", tmpPrefix);
            } else {
                spe.putInt("cHomePrefix", cHomePrefix);
            }
            spe.putInt("sPersonPrefix", sPersonPrefix);
            spe.putInt("cPersonPrefix", cPersonPrefix);
            spe.commit();
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean focLocalFile(String target) {
        try {
            FileOutputStream fos = (this.context.openFileOutput(target + "." + this.constants.localFile_name, Context.MODE_PRIVATE));
            this.osw = new OutputStreamWriter(fos);
            //this.osw.close();
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean readLocalFile(String target, Boolean creating) {
        InputStreamReader isr;
        try {
            isr = new InputStreamReader(this.context.openFileInput(target + "." + this.constants.localFile_name));
            this.br = new BufferedReader(isr);
            return true;
        } catch (Exception e) {
            if (creating) {
                if (focLocalFile(target)) {
                    try {
                        isr = new InputStreamReader(this.context.openFileInput(target + "." + this.constants.localFile_name));
                        this.br = new BufferedReader(isr);
                        return true;
                    } catch (Exception e2) {
                        return false;
                        //toast(e.getMessage());
                    }
                }
            }
            return false;
        }
    }

    public Boolean readLocalFilePerson(String target) {
        try {
            this.isrP = new InputStreamReader(this.context.openFileInput(target));
            this.brP = new BufferedReader(this.isrP);
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean getJSONFile(String target, Boolean creating) throws Exception {
        try {
            if (readLocalFile(target, creating)) {
                this.formMaster = new JSONObject(decodeLocalFile(br));
                try {
                    this.homeMaster = formMaster.getJSONObject("HOME");
                } catch (JSONException je_h) {
                    if (!je_h.getMessage().equals("No value for HOME")) {
                        return false;
                    }
                    this.homeMaster = new JSONObject();
                }
                try {
                    this.personMaster = formMaster.getJSONArray("PERSON");
                } catch (JSONException je_p) {
                    if (!je_p.getMessage().equals("No value for PERSON")) {
                        return false;
                    }
                    this.personMaster = new JSONArray();
                }
                return true;
            } else {
                toast("ReadLocalFileException");
            }
        } catch (JSONException je) {
            //toast(je.getMessage());
            return false;
        } catch (NullPointerException npe) {
            //toast(npe.getMessage());
            return false;
        }
        return false;
    }

    public Boolean getJSONFilePerson(String target) throws Exception {
        if (readLocalFilePerson(target)) {
            try {
                this.personDetail = new JSONObject(decodeLocalFile(brP));
                return true;
            } catch (JSONException je) {
                //toast(je.getMessage());
            }
        }
        return false;
    }

    public String decodeLocalFile(BufferedReader br_tmp) throws Exception {
        String line;
        StringBuilder sb = new StringBuilder();
        while ((line = br_tmp.readLine()) != null) {
            sb.append(line.trim());
        }
        return sb.toString();
    }

    public TableLayout FamilyDescription() throws Exception {
        String[][] homeS = this.structure.getHome(3);
        if (homeS.length > 0) {
            int fpr = Math.round(this.context.getResources().getDisplayMetrics().widthPixels / 300);
            TableLayout tl_tmp = new TableLayout(this.context);
            TableRow[] tr_tmp = new TableRow[(int) Math.ceil(6 / fpr)];
            TextView[] tv_tmp = new TextView[6];
            int tc = 0;

            tl_tmp.setShrinkAllColumns(true);
            tl_tmp.setStretchAllColumns(true);

            TextView tv_header = new TextView(this.context);
            TableRow tr_header = new TableRow(this.context);
            tv_header.setText(this.constants.FHeader);
            tv_header.setTextColor(Color.BLACK);
            tv_header.setHeight(30);
            tr_header.addView(tv_header, 0);
            tr_header.setGravity(Gravity.CENTER_HORIZONTAL);
            tl_tmp.addView(tr_header);

            object = tl_tmp;
            try {
                //Log.e("nowHomePrefixIs", cHomePrefix+"");
                if (getJSONFile(this.cHomePrefix + ".HOME", true) && (this.homeMaster.length() > 0)) {
                    int l = ((homeS.length > 6) ? 6 : homeS.length);
                    for (int c = 0; c < l; c++) {
                        if (c % fpr == 0) {
                            if (c > 0)
                                tc++;
                            tr_tmp[tc] = new TableRow(this.context);
                            tl_tmp.addView(tr_tmp[tc]);
                        }
                        tv_tmp[c] = new TextView(this.context);
                        try {
                            tv_tmp[c].setText(homeS[c][2] + " : " + this.homeMaster.getString(homeS[c][1]));
                        } catch (Exception e) {
                            tv_tmp[c].setText(homeS[c][2] + " : ");
                        }

                        tv_tmp[c].setTextColor(Color.BLACK);
                        tr_tmp[tc].addView(tv_tmp[c]);
                    }
                } else {
                    int l = ((homeS.length > 6) ? 6 : homeS.length);
                    for (int c = 0; c < l; c++) {
                        if (c % fpr == 0) {
                            if (c > 0)
                                tc++;
                            tr_tmp[tc] = new TableRow(this.context);
                            tl_tmp.addView(tr_tmp[tc]);
                        }
                        tv_tmp[c] = new TextView(this.context);
                        tv_tmp[c].setText(homeS[c][2] + " : ");
                        tv_tmp[c].setHeight(25);
                        tv_tmp[c].setTextColor(Color.BLACK);
                        tr_tmp[tc].addView(tv_tmp[c], (c) - (tc * fpr));

                    }
                }
            } catch (Exception e) {
                toast(e.getMessage());
            }
            return tl_tmp;
        }
        return null;
    }

    public TableLayout PersonDescription(String id, int action) throws Exception {
        duplicated = false;
        pQuantity = 0;
        String[][] personS = this.structure.getPerson(4);
        //Log.e("Persons", personS[0][2]+" -- "+personS[1][2]+" -- "+personS[2][2]+" -- "+personS[4][2]+" -- ");
        if (personS.length > 0) {
            int fpr = Math.round(this.context.getResources().getDisplayMetrics().widthPixels / 400);
            TableLayout tl_tmp = new TableLayout(this.context);

            TableRow[] tr_tmp;
            LinearLayout[] ln_tmp;
            ImageView[] im_tmp;
            TextView[] tv_tmp;
            tl_tmp.setShrinkAllColumns(true);
            if (fpr == 1) {
                tl_tmp.setStretchAllColumns(true);
            }

            TableRow tr_header = new TableRow(this.context);
            TextView tv_header = new TextView(this.context);
            tr_header.addView(tv_header);
            tr_header.setGravity(Gravity.CENTER_HORIZONTAL);
            tv_header.setText(this.constants.pHeader);
            tv_header.setTextSize(30);
            tv_header.setHeight(60);
            tv_header.setTextColor(Color.BLACK);
            tv_header.setGravity(Gravity.CENTER_HORIZONTAL);
            tl_tmp.addView(tr_header, 0);
            int tc = 0;
            int c = 0;
            Cursor cursor = null;
            if (id.length() > 0) {
                sqlHelper sqlHelper = new sqlHelper(this.context);
                sqlHelper.OOCDB();
                String query = this.constants.QUERY_2;
                query = query.replace("[FIELDS]", "*");
                query = query.replace("[TABLE]", "persons");
                query = query.replace("[CONDITIONS]", "iId = " + id);
                sqlHelper.setQuery(query);
                sqlHelper.execQuery();
                cursor = sqlHelper.getCursor();
            }
            if (getJSONFile(this.cHomePrefix + "." + constants.home, true)) {
                if (this.personMaster.length() > 0) {
                    if (this.personMaster.length() % 2 == 0) {
                        tr_tmp = new TableRow[(Math.round(this.personMaster.length() / 2) / fpr) + 2];
                    } else {
                        tr_tmp = new TableRow[Math.round(Math.round(this.personMaster.length() / 2) / fpr) + 2];
                    }
                    if (id.length() > 0) {
                        objects = new Object[Math.round(this.personMaster.length() / 2) + 2][3];
                    } else {
                        objects = new Object[Math.round(this.personMaster.length() / 2) + 1][3];
                    }

                    ln_tmp = new LinearLayout[Math.round(this.personMaster.length() / 2)];
                    im_tmp = new ImageView[Math.round(this.personMaster.length() / 2)];
                    tv_tmp = new TextView[Math.round(this.personMaster.length() / 2)];
                    try {
                        int content = 0;
                        for (c = 0; c < Math.round(this.personMaster.length() / 2); c++) {
                            if (getJSONFilePerson(personMaster.getString(content))) {
                                //Log.e("Person ::::::", this.personDetail.toString());
                                if (c % fpr == 0) {
                                    if (c != 0)
                                        tc++;
                                    tr_tmp[tc] = new TableRow(this.context);
                                    tl_tmp.addView(tr_tmp[tc]);
                                }
                                ln_tmp[c] = new LinearLayout(this.context);
                                im_tmp[c] = new ImageView(this.context);
                                tv_tmp[c] = new TextView(this.context);

                                im_tmp[c].setImageResource(R.drawable.user);
                                im_tmp[c].setLayoutParams(new LinearLayout.LayoutParams(70, 70));
                                if(cursor != null) {
                                    String docto = (
                                            ((cursor.getString(2) == null) ? "" : cursor.getString(2)) +
                                            ((cursor.getString(3) == null) ? "" : cursor.getString(3)) +
                                            ((cursor.getString(4) == null) ? "" : cursor.getString(4)) +
                                            ((cursor.getString(5) == null) ? "" : cursor.getString(5))
                                    );
                                    //Log.e("equals", docto+" equals "+this.personDetail.getString(personS[1][1]));
                                    if (docto.equals(this.personDetail.getString(personS[1][1]))) {
                                        duplicated = true;
                                        //Log.e("R", "true");
                                    }
                                }
                                pQuantity++;
                                String tx_tmp =
                                        personS[0][2] + " : " + this.personDetail.getString(personS[0][1]) + "\n" +
                                                personS[1][2] + " : " + this.personDetail.getString(personS[1][1]) + "\n" +
                                                personS[2][2] + " y " + personS[4][2] + " : " + this.personDetail.getString(personS[2][1]) + " " + this.personDetail.getString(personS[4][1]);
                                try {
                                    brP.close();
                                    isrP.close();
                                } catch (Exception e) {
                                    e.printStackTrace();
                                }

                                tv_tmp[c].setText(tx_tmp);
                                tv_tmp[c].setTextColor(Color.BLACK);
                                tv_tmp[c].setPadding(10, 0, 0, 0);

                                ln_tmp[c].setBackgroundResource(R.drawable.layoutlv);
                                ln_tmp[c].setGravity(Gravity.LEFT);

                                ln_tmp[c].addView(im_tmp[c]);
                                ln_tmp[c].addView(tv_tmp[c]);
                                tr_tmp[tc].addView(ln_tmp[c], ((c % fpr == 0) ? 0 : 1));

                                objects[c][0] = ln_tmp[tc];
                                objects[c][1] = personMaster.getString(content);
                                content++;
                                if (personMaster.getBoolean(content)) {
                                    im_tmp[c].setColorFilter(Color.LTGRAY, PorterDuff.Mode.LIGHTEN);
                                    objects[c][2] = ("1");
                                } else {
                                    objects[c][2] = ("0");
                                }
                                content++;
                            }
                        }
                    } catch (Exception e) {
                        toast("e1 " + e.getMessage());
                    }
                } else {
                    if (id.length() > 0) {
                        tr_tmp = new TableRow[2];
                        objects = new Object[2][3];
                    } else {
                        tr_tmp = new TableRow[1];
                        objects = new Object[1][3];
                    }
                }
            } else {
                if (id.length() > 0) {
                    tr_tmp = new TableRow[2];
                    objects = new Object[2][3];
                } else {
                    tr_tmp = new TableRow[1];
                    objects = new Object[1][3];
                }
            }

            if ((id.length() > 0) && (!duplicated)) {
                if (c % fpr == 0) {
                    if (c != 0)
                        tc++;
                    tr_tmp[tc] = new TableRow(this.context);
                    tl_tmp.addView(tr_tmp[tc]);
                }

                LinearLayout ln_found = new LinearLayout(this.context);
                ImageView im_found = new ImageView(this.context);
                TextView tv_found = new TextView(this.context);
                TextView tv_action = new TextView(this.context);
                try {
                    tr_tmp[tc].addView(ln_found, ((c % fpr == 0) ? 0 : 1));
                } catch (Exception e) {
                    //toast(e.getMessage());
                }
                ln_found.addView(im_found);
                ln_found.addView(tv_found);
                ln_found.addView(tv_action);
                try {
                    ln_found.setBackgroundResource(R.drawable.layoutlv);
                    //ln_add.setLayoutParams(new LinearLayout.LayoutParams(150, 30));
                } catch (Exception e) {
                    //toast(e.getMessage());
                }
                im_found.setImageResource(R.drawable.user);
                im_found.setColorFilter(Color.LTGRAY, PorterDuff.Mode.LIGHTEN);
                try {
                    im_found.setLayoutParams(new LinearLayout.LayoutParams(70, 70));
                } catch (Exception e) {
                    e.printStackTrace();
                }
                String[] pLData; //Person Loaded Data
                if (id.length() > 0) {
                    try {
                        assert cursor != null;
                        if (cursor.getCount() > 0) {
                            pLData = new String[10];
                            pLData[0] = decodeDocType(cursor.getString(1));
                            pLData[1] =
                                    (
                                            ((cursor.getString(2) == null) ? "" : cursor.getString(2)) +
                                            ((cursor.getString(3) == null) ? "" : cursor.getString(3)) +
                                            ((cursor.getString(4) == null) ? "" : cursor.getString(4)) +
                                            ((cursor.getString(5) == null) ? "" : cursor.getString(5))
                                    );
                            pLData[2] = cursor.getString(6);
                            pLData[3] = cursor.getString(7);
                            String[] dtTmp = cursor.getString(8).split("-");
                            pLData[4] = dtTmp[2] + "/" + dtTmp[1] + "/" + dtTmp[0];
                            tv_found.setText(
                                    personS[0][2] + " : " + pLData[0] + "\n" +
                                            personS[1][2] + " : " + pLData[1] + "\n" +
                                            personS[2][2] + " : " + pLData[2]);
                            tv_action.setText(
                                    ((action == 1) ? constants._STATUS002 : ((action == 2) ? constants._STATUS003 : ""))
                            );
                            tv_action.setTextColor((action == 1) ? Color.RED : Color.BLUE);
                            tv_action.setGravity(Gravity.RIGHT);
                            tv_action.getLayoutParams().width = ViewGroup.LayoutParams.MATCH_PARENT;
                            objects[c][0] = ln_found;
                            objects[c][1] = this.cPersonPrefix + ".PERSON" + "." + this.constants.localFile_name +
                                    "~" + pLData[0] + "~" + pLData[1] + "~" + pLData[2] + "~" + pLData[3] + "~" + pLData[4] + "~" + action;
                        }
                    } catch (CursorIndexOutOfBoundsException cibe) {
                        cibe.printStackTrace();
                    }
                    tv_found.setPadding(10, 0, 0, 0);
                } else {
                    tv_found.setText(this.constants.aButton);
                    tv_found.setTextSize(50);
                }
                tv_found.setTextColor(Color.BLACK);

                tr_tmp[tc].setGravity(Gravity.LEFT);
                //c++;
                objects[c][2] = "3";
            }

            if (c % fpr == 0) {
                if (c != 0)
                    tc++;
                tr_tmp[tc] = new TableRow(this.context);
                tl_tmp.addView(tr_tmp[tc]);
            }

            LinearLayout ln_add = new LinearLayout(this.context);
            ImageView im_add = new ImageView(this.context);
            TextView tv_add = new TextView(this.context);
            try {
                tr_tmp[tc].addView(ln_add, ((c % fpr == 0) ? 0 : 1));
            } catch (Exception e) {
                //toast(e.getMessage());
            }
            ln_add.addView(im_add);
            ln_add.addView(tv_add);
            try {
                ln_add.setBackgroundResource(R.drawable.layoutlv);
                //ln_add.setLayoutParams(new LinearLayout.LayoutParams(150, 30));
            } catch (Exception e) {
                //toast(e.getMessage());
            }
            im_add.setImageResource(R.drawable.user);
            try {
                im_add.setLayoutParams(new LinearLayout.LayoutParams(70, 70));
            } catch (Exception e) {
                e.printStackTrace();
            }
            tv_add.setText(this.constants.aButton);
            tv_add.setTextSize(50);

            tv_add.setTextColor(Color.BLACK);

            tr_tmp[tc].setGravity(Gravity.LEFT);
            objects[objects.length - 1][2] = ("0");
            objects[objects.length - 1][0] = ln_add;
            objects[objects.length - 1][1] = this.cPersonPrefix + ".PERSON" + "." + this.constants.localFile_name;

            return tl_tmp;
        }
        return null;
    }

    public TableLayout HomeDescription() throws Exception{
        homeTl = new TableLayout(context);
        TextView tv = new TextView(context);
        tv.setText(constants.hHeader);
        tv.setTextColor(Color.BLACK);
        tv.setGravity(Gravity.CENTER);
        tv.setWidth(LinearLayout.LayoutParams.MATCH_PARENT);
        tv.setHeight(50);
        homeTl.setGravity(Gravity.CENTER_HORIZONTAL);
        homeTl.addView(tv);
        return homeTl;
    }

    private String decodeDocType(String dt) {
        for (String[] dType_tmp : this.constants.docTypesForDecoder) {
            if (dType_tmp[0].equals(dt)) {
                return dType_tmp[1];
            }
        }
        return "OTHER";
    }

    @SuppressLint("SimpleDateFormat")
    public Boolean wLocalFile(String target, int event) {
        try {
            if (!fioLocalFile(target)) {
                if (!focLocalFile(target)) {
                    return false;
                }
            }
            switch (event) {
                case 1:
                    JSONObject documentInfo;
                    try {
                        formMaster.length();
                    } catch (Exception e) {
                        formMaster = new JSONObject();
                    }
                    try {
                        documentInfo = formMaster.getJSONObject("DOCUMENTINFO");
                    } catch (JSONException je) {
                        documentInfo = new JSONObject();
                    }
                    try {
                        try {
                            documentInfo.getString("Created");
                        } catch (JSONException je) {
                            documentInfo.put("Created", new SimpleDateFormat(constants.SimpleDateFormat).format(new Date()));
                        }
                        documentInfo.put("AppVersion", constants.version_name + "." + constants.version_subname);
                        documentInfo.put("StructureVersionCreated", this.structure.jStructure.getJSONObject("Document_info").getString("structureVersion"));
                        documentInfo.put("UserId", this.userId);
                        documentInfo.put("Username", this.username);
                        documentInfo.put("Usable", true);
                        formMaster.put("DOCUMENTINFO", documentInfo);
                    } catch (JSONException je) {
                        je.printStackTrace();
                    }
                    try {
                        homeMaster.length();
                    } catch (Exception e) {
                        homeMaster = new JSONObject();
                    }
                    try {
                        personMaster.length();
                    } catch (Exception e) {
                        personMaster = new JSONArray();
                    }
                    formMaster.put("HOME", homeMaster);
                    formMaster.put("PERSON", personMaster);
                    //formMaster = new JSONObject(); //FILE RESET
                    osw.write(formMaster.toString());
                    osw.close();
                    return true;
                case 2:
                    osw.write(formMaster.toString());
                    osw.close();
                    break;
            }
            return false;
        } catch (Exception e) {
            toast(e.getMessage());
            return false;
        }
    }

    public Boolean closePersonForm() {
        cPersonPrefix++;
        return sPrefixes();
    }

    public Boolean fioLocalFile(String target) {
        try {
            osw.append("");
            return true;
        } catch (Exception e) {
            return focLocalFile(target);
        }
    }

    public Object getObject() {
        return this.object;
    }

    public Object[][] getObjects() {
        return this.objects;
    }

    public TableLayout getHome(){
        return this.homeTl;
    }

    public JSONObject getFormMaster() {
        return this.formMaster;
    }

    public void setFormMaster(JSONObject formMaster) {
        this.formMaster = formMaster;
    }

    public JSONObject getHomeMaster() throws Exception{
        if(getJSONFile(this.cHomePrefix + ".HOME", true)){
            return this.formMaster.getJSONObject("HOME");
        } return null;
    }

    public JSONObject getPersonDetail() {
        return this.personDetail;
    }

    public void setUserData(String userId, String username) {
        this.userId = userId;
        this.username = username;
    }

    public int getFoundedPersons(){
        return pQuantity;
    }

    public void setResuming(Boolean resuming) {
        this.resuming = resuming;
    }

    /*public void setLotacionData(Location location){
        this.location = location;
        l_d = true;
    }*/

    public void setResumingPrefix(int resumingPrefix) {
        this.resumingPrefix = resumingPrefix;
    }

    private void toast(String txt) {
        Toast.makeText(this.context, txt, Toast.LENGTH_LONG).show();
    }
}
