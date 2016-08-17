package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.graphics.Typeface;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.Gravity;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.GridLayout;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.amazonaws.auth.BasicAWSCredentials;
import com.amazonaws.services.sqs.AmazonSQSClient;
import com.amazonaws.services.sqs.model.SendMessageRequest;
import com.amazonaws.services.sqs.model.SendMessageResult;
import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.masterTools;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public class synchronizer extends Activity {

    private constants constants;
    private masterTools tools;
    private AmazonSQSClient sqs;
    private ProgressDialog pd;
    private AlertDialog.Builder adb;
    private JSONObject formMaster;
    private JSONArray prefix;
    private JSONArray results;
    private ScrollView sv;
    private Object[][] object;
    private int status;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_synchronizer);/*
        requestWindowFeature(Window.FEATURE_NO_TITLE);*/
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        constants = new constants();
        initProgressDialog();
        setProgressDialogMessage(constants._ADV024 + "\n" + constants._ADV013);
        setOnDismissListener();
        //showProgressDialog();
        adb = new AlertDialog.Builder(this);
        showProgressDialog();
        new Thread(new Runnable() {
            @Override
            public void run() {
                init();
            }
        }).start();
    }

    private void setPolicy() {
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
    }

    private void initProgressDialog() {
        this.pd = new ProgressDialog(this);
        this.pd.setCancelable(false);
        this.pd.setCanceledOnTouchOutside(false);
        results = new JSONArray();
    }

    private void setProgressDialogMessage(String message) {
        this.pd.setMessage(message);
    }

    private void showProgressDialog() {
        this.pd.show();
    }

    private void dismissProgressDialog() {
        this.pd.dismiss();
    }

    private void setOnDismissListener() {
        this.pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                switch (status) {
                    case -1:
                        AlertDialogBuilder(constants.error, "Something is wrong, please contact support.");
                        break;
                    case 0:
                        AlertDialogBuilder(constants.atention, "Could not found data pending to synchronize on this device.");
                        break;
                    case 1:
                        if (results.length() > 0) {
                            //showResults();
                            AlertDialogBuilder("Finalizado", "Se enviaron " + results.length() + " encuestas");
                        }
                        break;
                }

            }
        });
    }

    private void init() {
        try {
            setPolicy();
            createInstances();
            setSqsEndpoint(constants.__SQS);
            loadIndex();
        } catch (Exception e) {
            //AlertDialogBuilder("Error", e.getMessage());
            Log.e("SynchronizerE", e.getMessage());
        }
    }

    private void loadIndex() {
        tools.setResuming(false);
        if (tools.corLocalConfig()) {
            if (tools.focPrefixes()) {
                int count = 0;
                for (int a = tools.sHomePrefix; a < tools.cHomePrefix; a++) {
                    try {
                        if (tools.getJSONFile(a + ".HOME", true)) {
                            JSONArray ja_tmp = tools.getFormMaster().getJSONArray("PERSON");
                            Boolean o = false;
                            //int tmp = 0;
                            for (int b = 1; b < ja_tmp.length(); b += 2) {
                                if (ja_tmp.getBoolean(b)) {
                                    o = true;
                                }
                            }
                            if (!(o)) {
                                //AlertDialogBuilder("open=", tools.getFormMaster().toString());
                                formMaster.put("MASTER" + count, tools.getFormMaster());
                                prefix.put(a);
                                count++;
                            }
                        }
                    } catch (Exception e) {
                        Log.e("SynchronizerE", e.getMessage());
                    }
                }
                if (count > 0) {
                    this.status = 1;
                } else {
                    status = 0;
                }
            } else {
                status = -2;
            }
        } else {
            status = -1;
        }
        doWithResults();
    }

    private void doWithResults() {
        switch (status) {
            case 1:
                sendPolls();
                break;
            default:
                dismissProgressDialog();
                break;
        }
    }

    private void sendPolls() {
        adb.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                finish();
            }
        });
        for (int a = 0; a < formMaster.length(); a++) {
            try {
                JSONObject master = new JSONObject();
                JSONArray persons = new JSONArray();

                JSONArray person_tmp = formMaster.getJSONObject("MASTER" + a).getJSONArray("PERSON");
                int b = 0;
                do {
                    tools.getJSONFilePerson(person_tmp.getString(b));
                    JSONObject personD_tmp = tools.getPersonDetail();
                    persons.put(personD_tmp);
                    b += 2;
                } while (b < person_tmp.length());
                master.put("PERSONS", persons);
                master.put("HOME", formMaster.getJSONObject("MASTER" + a).getJSONObject("HOME"));
                master.put("DOCUMENTINFO", formMaster.getJSONObject("MASTER" + a).getJSONObject("DOCUMENTINFO"));
                sendMessageToQueue(constants.__SQS, master.toString());
                results.put(master);
            } catch (JSONException je) {
                AlertDialogBuilder("Error", je.getMessage() + "\n" + formMaster.toString());
            } catch (Exception e) {
                AlertDialogBuilder("Error", e.getMessage());
            }
        }
        //AlertDialogBuilder("Notice", "finish");
        dismissProgressDialog();
    }

    private void showResults() {
        Log.e("SynchronizerE", "Length " + results.toString());
        //DeveloperTesting
        for (int a = 0; a < 30; a++) {
            try {
                results.put(results.getJSONObject(a));
            } catch (JSONException je) {
                Log.e("SynchronizerE", je.getMessage());
            }
        }
        createObjects();
        for (int a = 0; a < results.length(); a++) {
            try {
                object[a] = new Object[results.getJSONObject(a).getJSONArray("PERSONS").length() + 1];
                String[][] structureH = tools.structure.getHome(3);
                String[][] structureC = tools.structure.getPerson(3);

                LinearLayout llH = new LinearLayout(this);
                GridLayout glH = new GridLayout(this);
                TextView contentH1 = new TextView(this);
                TextView contentH2 = new TextView(this);
                TextView contentH3 = new TextView(this);

                llH.setOrientation(LinearLayout.VERTICAL);
                llH.setBackgroundResource(R.drawable.layoutlv);
                llH.setGravity(Gravity.CENTER_HORIZONTAL);
                glH.setColumnCount(2);

                contentH1.setText(constants.fHeader);
                contentH2.setText(
                        structureH[0][2] + ": " + results.getJSONObject(a).getJSONObject("HOME").getString(structureH[0][1]) + "\n" +
                                structureH[1][2] + ": " + results.getJSONObject(a).getJSONObject("HOME").getString(structureH[1][1]) + "\n" +
                                structureH[2][2] + ": " + results.getJSONObject(a).getJSONObject("HOME").getString(structureH[2][1]));
                contentH3.setText(
                        structureH[3][2] + ": " + results.getJSONObject(a).getJSONObject("HOME").getString(structureH[3][1]) + "\n" +
                                structureH[4][2] + ": " + results.getJSONObject(a).getJSONObject("HOME").getString(structureH[4][1]));

                llH.addView(contentH1);
                llH.addView(glH);
                glH.addView(contentH2);
                glH.addView(contentH3);
                llH.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, -2));
                contentH1.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, -2));
                contentH1.setGravity(Gravity.CENTER_HORIZONTAL);
                contentH1.setTypeface(Typeface.DEFAULT_BOLD);
                object[a][0] = llH;
                for (int b = 0; b < results.getJSONObject(a).getJSONArray("PERSONS").length(); b++) {
                    LinearLayout llC = new LinearLayout(this);
                    TextView contentC = new TextView(this);
                    contentC.setText("" +
                            structureC[0][2] + ": " + results.getJSONObject(a).getJSONArray("PERSONS").getJSONObject(b).getString(structureC[0][1]) + "\n" +
                            structureC[1][2] + ": " + results.getJSONObject(a).getJSONArray("PERSONS").getJSONObject(b).getString(structureC[1][1]) + "\n" +
                            structureC[2][2] + ": " + results.getJSONObject(a).getJSONArray("PERSONS").getJSONObject(b).getString(structureC[2][1]));
                    llC.addView(contentC);
                    llC.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, -2));
                    object[a][b + 1] = llC;
                }
            } catch (JSONException je) {
                Log.e("SynchronizerE", "Error::::" + je.getMessage());
            }
        }
        addToStage();
    }

    private void addToStage() {
        try {
            GridLayout rl = new GridLayout(this);
            sv.addView(rl);
            rl.setRowCount(object.length);
            rl.setColumnCount(1);
            int a = 0;
            for (Object[] tmp1 : object) {
                rl.addView((View) tmp1[0]);
                setListener(tmp1, a);
                a++;
            }
            sv.setOnTouchListener(new View.OnTouchListener() {
                @Override
                public boolean onTouch(View v, MotionEvent event) {
                    return false;
                }
            });
        } catch (Exception e) {
            Log.e("SynchronizerE", e.getMessage());
        }
    }

    private void setListener(Object[] tmp, final int index) {
        ((View) tmp[0]).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                removeFromStage(index);
            }
        });
    }

    private void removeFromStage(int index) {
        try {
            for (int a = 0; a < object.length; a++) {
                if (a != index) {
                    ((LinearLayout) object[a][0]).animate().setDuration((150)).setStartDelay(40 * a).alpha(0).start();
                }
            }
            sv.setOnTouchListener(new View.OnTouchListener() {
                @Override
                public boolean onTouch(View v, MotionEvent event) {
                    // TODO Auto-generated method stub
                    return true;
                }
            });
        } catch (Exception e) {
            Log.e("SynchronizerE", e.getMessage());
        }
    }

    private void createObjects() {
        this.sv = (ScrollView) findViewById(R.id.parentScrollView);
        this.object = new Object[this.results.length()][];
    }

    private void createInstances() throws Exception {
        this.tools = new masterTools(getApplicationContext());
        this.formMaster = new JSONObject();
        this.prefix = new JSONArray();
        this.sqs = new AmazonSQSClient(new BasicAWSCredentials(constants.__KEY, constants.__SKEY));
        this.status = 0;
    }

    private void setSqsEndpoint(String sqsEndpoint) throws Exception {
        sqs.setEndpoint(sqsEndpoint);
    }

    public void sendMessageToQueue(String queueUrl, String message) {
        try {
            SendMessageResult messageResult = this.sqs.sendMessage(new SendMessageRequest(queueUrl, message));
            //System.out.p_rintln(messageResult.toString());
        } catch (Exception e) {
            Log.e("smtqError", e.getMessage());
        }
    }

    private void AlertDialogBuilder(String title, String message) {
        adb.setTitle(title);
        adb.setMessage(message);
        adb.setPositiveButton("aceptar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                finish();
            }
        });
        adb.show();
    }
}