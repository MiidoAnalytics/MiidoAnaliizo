package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.miido.miido.mcompose.composeTools;
import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.masterTools;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class find_search extends Activity {

    private constants constants;
    private masterTools tools;
    private composeTools tools2;
    private ScrollView master;
    private ProgressDialog pd;
    private AlertDialog.Builder adb;
    private JSONObject formMaster;
    private JSONArray prefix;
    private JSONArray paused;
    private JSONArray index;
    private Intent i;

    private TableLayout masterTl;
    private TableRow[] masterTR;
    private LinearLayout container;

    private int Status;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_find_search);

        startInstances();
        progressDialogSetText(this.constants._ADV013);
        setProgressDialog();
        showProgressDialog();
        Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                init();
            }
        }, 1000);
    }

    private void init() {
        try {
            master.addView(masterTl);
            tools.setResuming(false);
        } catch (Exception e) {
            alertDialogBuilderSetTittle(constants.error);
            alertDialogBuilderSetMessage(e.getMessage());
            showAlertDialogBuilder();
        }
        resultListener();
        new Thread(new Runnable() {
            @Override
            public void run() {
                if (tools.corLocalConfig()) {
                    if (tools.focPrefixes()) {
                        int index = 0;
                        for (int a = tools.sHomePrefix; a < tools.cHomePrefix; a++) {
                            try {
                                if (tools.getJSONFile(a + ".HOME", true)) {
                                    if (tools.getFormMaster().getJSONObject("DOCUMENTINFO").getBoolean("Usable")) {
                                        formMaster.put("MASTER" + index, tools.getFormMaster());
                                        prefix.put(a);
                                        index++;
                                    }
                                }
                            } catch (Exception e) {
                                Log.e("threadGetJsonListenerEx", e.getMessage());
                            }
                        }
                        if (index > 0) {
                            Status = 1;
                        } else {
                            Status = 0;
                        }
                    } else {
                        Status = -1;
                    }
                } else {
                    Status = -1;
                }
                dismissProgressDialog();
            }
        }).start();
    }

    @SuppressWarnings("All")
    private void createInterface() {
        LinearLayout[] polls;
        for (int a = 0; a < prefix.length(); a++) {
            try {
                this.index.put(a);
                JSONArray tmp_person = formMaster.getJSONObject("MASTER" + a).getJSONArray("PERSON");
                Log.e("Objects", formMaster.getJSONObject("MASTER" + a).toString());
                for (int b = 0; b < tmp_person.length(); b++) {
                    Boolean c = false;
                    if (b % 2 != 0) {
                        c = tmp_person.getBoolean(b);
                    }
                    if (c) {
                        this.paused.put(a);
                        break;
                    }
                }
            } catch (Exception e) {
                Log.e("createInterfaceEx1", e.getMessage());
            }
        }
        if (this.index.length() > 0) {
            masterTl.setStretchAllColumns(true);
            masterTl.setShrinkAllColumns(true);
            masterTR = new TableRow[this.index.length() * 2];
            polls = new LinearLayout[this.index.length()];
            int aux = 0;
            String info = "";
            for (int a = 0; a < this.index.length(); a++) {
                try {
                    int index = this.index.getInt(a);
                    JSONObject document = formMaster.getJSONObject("MASTER" + index).getJSONObject("DOCUMENTINFO");
                    JSONObject home = formMaster.getJSONObject("MASTER" + index).getJSONObject("HOME");
                    masterTR[aux] = new TableRow(getApplicationContext());
                    polls[a] = new LinearLayout(getApplicationContext());
                    TextView tv = new TextView(getApplicationContext());
                    try {
                        String familyName = home.getString(tools.structure.structure[constants.familyLastNameIndex][1]);
                        String dateTimeC = document.getString("Created");
                        String dateTimeP = "";
                        String dateTimeF = "";
                        try {
                            dateTimeP = document.getString(constants.paused);
                        } catch (Exception e) {
                            dateTimeF = document.getString(constants.finished);
                        }
                        tv.setText("" +
                                constants._EM004 + " : " + familyName + "\n" +
                                constants._EM005 + " : " + dateTimeC + "\n" +
                                ((dateTimeF.equals("")) ? (constants._EM006 + " : " + dateTimeP) : (constants._EM007 + " : " + dateTimeF)) + "\n" +
                                "");
                    } catch (Exception ee) {
                        Log.e("NoticeErrorEE", ee.getMessage());
                        Status = 0;
                        //doWithResults();
                    }
                    tv.setTextColor(Color.BLACK);
                    polls[a].setPadding(10, 10, 10, 10);
                    polls[a].addView(tv);
                    polls[a].setContentDescription("" + index);
                    polls[a].setOrientation(LinearLayout.HORIZONTAL);
                    masterTR[aux].addView(polls[a]);
                    masterTR[aux].setBackgroundResource(R.drawable.layoutlv);
                    masterTl.addView(masterTR[aux]);
                    Boolean p = false;
                    for (int b = 0; b < this.paused.length(); b++) {
                        if (this.paused.getInt(b) == a) {
                            p = true;
                            ImageButton ibDelete = new ImageButton(getApplicationContext());
                            ibDelete.setImageResource(R.drawable.ic_delete_);
                            ibDelete.setBackgroundColor(Color.TRANSPARENT);
                            ibDelete.setLayoutParams(new LinearLayout.LayoutParams(50, 50));
                            LinearLayout ll_tmp = new LinearLayout(getApplicationContext());
                            ll_tmp.addView(ibDelete);
                            ll_tmp.setGravity(Gravity.RIGHT);
                            ll_tmp.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, -2));
                            ll_tmp.setPadding(0, 0, 15, 0);
                            ibDelete.setContentDescription(index + "");
                            ibDelete.setTag(aux + "");
                            ibDelete.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    try {
                                        if (tools.getJSONFile(v.getContentDescription() + ".HOME", true)) {
                                            JSONObject tmp = formMaster.getJSONObject("MASTER" + v.getContentDescription());
                                            Log.e("address", v.getContentDescription() + ".HOME");
                                            Log.e("before", tmp.toString());
                                            tmp.getJSONObject("DOCUMENTINFO").put("Usable", false);
                                            tools.setFormMaster(tmp);
                                            Log.e("after", tools.getFormMaster().toString());
                                            tools.wLocalFile(v.getContentDescription() + ".HOME", 2);
                                            //masterTR[Integer.parseInt(v.getTag().toString())].setLayoutParams(new LinearLayout.LayoutParams(0,0));
                                            master.removeView(masterTl);
                                            startInstances();
                                            progressDialogSetText(constants._ADV013);
                                            setProgressDialog();
                                            showProgressDialog();
                                            Handler handler = new Handler();
                                            handler.postDelayed(new Runnable() {
                                                @Override
                                                public void run() {
                                                    init();
                                                }
                                            }, 1000);

                                        }
                                    } catch (Exception je) {
                                        Log.e("JE", je.getMessage());
                                    }
                                }
                            });
                            polls[a].addView(ll_tmp);
                            masterTR[aux].setBackgroundResource(R.drawable.layourlv_paused);
                            polls[a].setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    i.setClass(getApplicationContext(), master.class);
                                    i.putExtra("resume", Integer.parseInt(v.getContentDescription().toString()));
                                    i.putExtra("resuming", true);
                                    startActivity(i);
                                }
                            });
                        }
                    }
                    if (!(p)) {
                        masterTR[aux].setContentDescription("0");
                        masterTR[aux].setTag("" + aux);
                        masterTR[aux].setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                int t = Integer.parseInt(v.getContentDescription().toString());
                                if (t == 0) {
                                    //masterTR[Integer.parseInt(v.getTag().toString()) + 1].animate().scaleY(1).setDuration(0).start();
                                    masterTR[Integer.parseInt(v.getTag().toString()) + 1].setVisibility(View.VISIBLE);
                                    v.setContentDescription("1");
                                } else {
                                    masterTR[Integer.parseInt(v.getTag().toString()) + 1].setVisibility(View.GONE);
                                    //masterTR[Integer.parseInt(v.getTag().toString()) + 1].animate().scaleY(0).setDuration(0).start();
                                    v.setContentDescription("0");
                                }
                            }
                        });
                    }
                    aux++;
                    masterTR[aux] = new TableRow(getApplicationContext());
                    //masterTR[aux].animate().scaleY(0).setDuration(0).start();
                    masterTR[aux].setVisibility(View.GONE);
                    if (!(p)) {
                        JSONArray tmp_person = formMaster.getJSONObject("MASTER" + index).getJSONArray("PERSON");

                        LinearLayout[] ll;
                        int maxCol = Math.round(getResources().getDisplayMetrics().widthPixels / 300);
                        if (tmp_person.length() > maxCol) {
                            int col = (int) (Math.round(tmp_person.length() / maxCol));
                            ll = new LinearLayout[col];
                        } else {
                            maxCol = 1;
                            ll = new LinearLayout[1];
                        }
                        int c = 0;
                        int w = 0;
                        ll[w] = new LinearLayout(this);
                        ll[w].setOrientation(LinearLayout.HORIZONTAL);
                        String[][] personS = tools.structure.getPerson(3);
                        while (c < tmp_person.length()) {
                            try {
                                if (tools.getJSONFilePerson(tmp_person.getString(c))) {
                                    if (ll[w].getChildCount() == maxCol) {
                                        w++;
                                        ll[w] = new LinearLayout(this);
                                        ll[w].setOrientation(LinearLayout.HORIZONTAL);
                                        ll[w].setLayoutParams(new LinearLayout.LayoutParams(getResources().getDisplayMetrics().widthPixels - 50, -2));
                                    }
                                    JSONObject curPerson = tools.getPersonDetail();
                                    LinearLayout l_l = new LinearLayout(this);
                                    //ImageView i_v = new ImageView(this);
                                    TextView t_v = new TextView(this);

                                    l_l.setOrientation(LinearLayout.VERTICAL);
                                    l_l.setBackgroundResource(R.drawable.layoutlv);
                                    l_l.setTag(tmp_person.getString(c));
                                    //i_v.setImageResource(R.drawable.user);
                                    try {
                                        t_v.setText(
                                                personS[0][2] + " : " +
                                                        curPerson.getString(personS[0][1]) + "\n" +
                                                        personS[1][2] + " : " +
                                                        curPerson.getString(personS[1][1]) + "\n" +
                                                        personS[2][2] + " : " +
                                                        curPerson.getString(personS[2][1]) + "\n" +
                                                        personS[3][2] + " : " +
                                                        curPerson.getString(personS[3][1])
                                        );
                                    } catch (JSONException je) {
                                        Log.e("NoticeErrorJE", je.getMessage());
                                    }
                                    l_l.addView(t_v);
                                    l_l.setLayoutParams(new LinearLayout.LayoutParams(-2, -2));
                                    ll[w].addView(l_l);
                                    l_l.setOnClickListener(new View.OnClickListener() {
                                        @Override
                                        public void onClick(View v) {
                                            String url = v.getTag().toString();
                                            tools2.setTarget(url);
                                            tools2.setEvent(1);
                                            if (tools2.readLocalFile()) {
                                                try {
                                                    tools2.setPerson();
                                                    TableLayout rl = tools2.showSummary(new TableLayout(getApplicationContext()), false);
                                                    showIt(rl);
                                                } catch (Exception e) {
                                                    e.printStackTrace();
                                                }
                                            }
                                        }
                                    });
                                }
                                c += 2;
                            } catch (NullPointerException npe) {
                                Log.e("Error", npe.getMessage());
                            } catch (JSONException je) {
                                Log.e("JError", je.getMessage());
                            } catch (Exception e) {
                                Log.e("GError", e.getMessage());
                            }
                        }
                        for (LinearLayout ll_tmp : ll) {
                            try {
                                if (ll_tmp != null)
                                    masterTR[aux].addView(ll_tmp);
                            } catch (NullPointerException e) {
                                Log.e("npe", e.getMessage());
                            }
                        }
                    }
                    masterTl.addView(masterTR[aux]);
                    aux++;
                } catch (Exception e) {
                    Log.e("NoticeErrorE2__" + info, e.getMessage());
                    Status = 0;
                    doWithResults();
                }
            }
        } else {
            Log.e("else", "");
            Status = 0;
            doWithResults();
        }
    }

    private void showIt(TableLayout tl) {
        container = new LinearLayout(getApplicationContext());
        Button close = new Button(getApplicationContext());
        close.setText("X");
        container.setOrientation(LinearLayout.VERTICAL);
        container.setPadding(0, 20, 0, 20);
        close.setBackgroundResource(R.drawable.close_button);
        close.setLayoutParams(new LinearLayout.LayoutParams(50, 50));
        container.setGravity(Gravity.RIGHT);
        container.addView(close);
        container.addView(tl);
        master.removeView(masterTl);
        master.addView(container);
        close.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                container.removeAllViews();
                master.removeAllViews();
                master.addView(masterTl);
            }
        });
    }

    private void resultListener() {
        adb.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                finish();
            }
        });
        pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                doWithResults();
            }
        });
    }

    private void doWithResults() {
        switch (Status) {
            case -1:

                //alertDialogBuilderSetTittle(constants.error);
                alertDialogBuilderSetMessage(constants._ADV007);
                adb.setPositiveButton(constants.goBack, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        finish();
                    }
                });
                showAlertDialogBuilder();
                break;
            case 0:
                //alertDialogBuilderSetTittle(constants.atention);
                alertDialogBuilderSetMessage(constants._ADV019);
                adb.setPositiveButton(constants.goBack, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        finish();
                    }
                });
                showAlertDialogBuilder();
                break;
            case 1:
                createInterface();
                break;
        }
    }

    private void startInstances() {
        Status = 0;
        constants = new constants();
        tools = new masterTools(this.getApplicationContext());
        tools2 = new composeTools(this.getApplicationContext());
        i = new Intent();
        master = (ScrollView) findViewById(R.id.parentScrollView);
        formMaster = new JSONObject();
        prefix = new JSONArray();
        paused = new JSONArray();
        index = new JSONArray();
        pd = new ProgressDialog(this);
        adb = new AlertDialog.Builder(this);
        masterTl = new TableLayout(this);
    }

    private void alertDialogBuilderSetTittle(String header) {
        this.adb.setTitle(header);
    }

    private void alertDialogBuilderSetMessage(String message) {
        this.adb.setMessage(message);
    }

    private void showAlertDialogBuilder() {
        adb.show();
    }

    private void progressDialogSetText(String text) {
        pd.setMessage(text);
    }

    private void setProgressDialog() {
        pd.setCancelable(false);
        pd.setCanceledOnTouchOutside(false);
    }

    private void showProgressDialog() {
        pd.show();
    }

    private void dismissProgressDialog() {
        pd.dismiss();
    }
}
