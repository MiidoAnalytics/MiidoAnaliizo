package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Color;
import android.graphics.Typeface;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.miido.mcompose.constants;
import com.miido.miido.util.ConnectorHttpJSON;
import com.miido.miido.util.miido;
import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * *******************************
 * Created by Alvaro on 03/03/2015.
 * *******************************
 */
public class login extends Activity {

    private constants constants;
    private sqlHelper sql;
    private ConnectorHttpJSON connectorHttpJSON;
    private miido miido;

    private Button login;
    private EditText userT, passT;
    private JSONArray jSecurity;
    private ProgressDialog pd;
    private int result;
    private int started;

    private Boolean dwUsers;
    private Boolean fdw;

    private int Times;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        //requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        dwUsers = true;
        setContentView(R.layout.login);
        Times = 0;
        Log.e("Width", getResources().getDisplayMetrics().widthPixels+"");
        Log.e("Height", getResources().getDisplayMetrics().heightPixels+"");
        try {
            createInstances();
            createLocationService();
            (findViewById(R.id.messages)).setAlpha(0);
            if (validateSecurity()) {
                init();
            } else {
                progressDialog();
                new Thread(new Runnable() {
                    @Override
                    public void run() {
                        if (getSecurity()) {
                            dwUsers = false;
                            fdw = true;
                            pd.dismiss();
                        } else {
                            pd.dismiss();
                        }
                    }
                }).start();
            }
        } catch (Exception e) {
            Toast.makeText(this, "Error feo parcero " + e.getMessage(), Toast.LENGTH_LONG).show();
        }
    }

    private Boolean validateSecurity() {
        try {
            sql.databaseName = constants.securityDatabase;
            sql.OOCDB();
            sql.setQuery(constants.QUERY_8);
            sql.execQuery();
            Cursor cursor = sql.getCursor();
            if (cursor.getCount() > 0) {
                cursor.moveToFirst();
                jSecurity = new JSONArray(cursor.getString(0));
                return true;
            }
            return false;
        } catch (Exception e) {
            return false;
        }
    }

    private void progressDialog() {
        pd = new ProgressDialog(this);
        pd.setMessage(constants._ADV000 + "\n" + constants._ADV013);
        pd.setCancelable(false);
        pd.setCanceledOnTouchOutside(false);
        pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                try {
                    if (result == 1) {
                        if (dwUsers)
                            AlertDialog(constants.atention, constants._ADV021, 2);
                        dwUsers = false;
                        if (fdw) {
                            android.os.Handler handler = new android.os.Handler();
                            handler.postDelayed(new Runnable() {
                                @Override
                                public void run() {
                                    dwUsers = true;
                                }
                            }, 5000);
                        }
                        init();
                    } else {
                        toast(result + "", 0);
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });
        pd.show();
    }

    private void init() throws Exception {
        //createInstances();
        //if(validateRequeriments()) {
        setInterface();
        setListeners();
        //} else
        //    finish();
    }

    private Boolean getSecurity() {
        connectorHttpJSON.setUrl(constants.__SS);
        result = connectorHttpJSON.downloadSecurityStructure();
        if (result == 1) {
            sql.databaseName = constants.securityDatabase;
            sql.OOCDB();
            sql.execQuery();
            Cursor cursor = sql.getCursor();
            if (cursor.getCount() > 0) {
                cursor.moveToFirst();
                try {
                    jSecurity = new JSONArray(cursor.getString(0));
                } catch (JSONException je) {
                    return false;
                }
                return true;
            }
            return false;
        }
        return false;
    }

    private void setInterface() {
        this.userT.setBackgroundResource(R.drawable.focus_border_style);
        this.passT.setBackgroundResource(R.drawable.focus_border_style);
        this.login.setBackgroundResource(R.drawable.button);
        this.login.setTextColor(Color.WHITE);

        Typeface tf = Typeface.createFromAsset(getAssets(), "NotoSans-Regular.ttf");
        ((TextView) findViewById(R.id.AccessToSystem)).setText(constants.ats);
        ((TextView) findViewById(R.id.AccessToSystem)).setTypeface(tf);
    }

    private void createInstances() throws Exception {
        sql = new sqlHelper(getApplicationContext());
        sql.OOCDB();
        connectorHttpJSON = new ConnectorHttpJSON(getApplicationContext());
        this.constants = new constants();
        this.miido = new miido();
        this.login = ((Button) findViewById(R.id.login));
        this.userT = ((EditText) findViewById(R.id.userTxt));
        this.passT = ((EditText) findViewById(R.id.passwordTxt));
        started = 0;
    }

    private void setListeners() {
        addEventListener(0);
    }

    private void addEventListener(int element) {
        switch (element) {
            case 0:
                this.login.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        //toast("er");
                        sessionStart();
                    }
                });
                break;
        }
    }

    private void sessionStart() {
        Times++;
        String errorsF = "";
        if ((this.userT.getText() + "").equals("")) {
            errorsF = this.constants._ADV001;
        } else if ((this.passT.getText() + "").equals("")) {
            errorsF = this.constants._ADV002;
        } else {
            try {
                String usr = userT.getText().toString();
                String pwdUTF = miido.md5_UTF(passT.getText().toString());
                String pwdHEX = miido.md5_HEX(passT.getText().toString());
                for (int a = 0; a < jSecurity.length(); a++) {
                    JSONObject tmp = jSecurity.getJSONObject(a);
                    //Log.e("Structure", tmp.toString());
                    if (usr.equals(tmp.getString("strusername"))) {
                        if ((pwdUTF.equals(tmp.getString("strhashpassword"))) ||
                                (pwdHEX.equals(tmp.getString("strhashpassword")))) {
                            Intent i = new Intent(this, main_menu.class);
                            i.putExtra("userId", tmp.getString("intidinterviewer"));
                            i.putExtra("username", usr);
                            userT.setText("");
                            passT.setText("");
                            started = 1;
                            startActivity(i);
                        } else
                            errorsF = this.constants._ERROR001;
                    } else
                        errorsF = this.constants._ERROR001;
                }
            } catch (Exception e) {
                toast(e.getMessage(), 0);
            }
        }
        if (!(errorsF.equals(""))) {
            toast(errorsF, 0);
            this.userT.setText("");
            this.passT.setText("");
            this.userT.requestFocus();
            if (errorsF.equals(constants._ERROR001)) {
                if (Times >= 3) {
                    if (dwUsers) {
                        dwUsers = false;
                        android.os.Handler handler = new android.os.Handler();
                        handler.postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                AlertDialog(constants.atention, constants._ADV022, 1);
                            }
                        }, 1500);
                    }
                }
            }
        }

    }

    private void toast(String text, int c) {
        switch (c) {
            case 0:
                ((TextView) findViewById(R.id.messages)).setTextColor(Color.RED);
                break;
        }
        ((TextView) findViewById(R.id.messages)).setText(text);
        (findViewById(R.id.messages)).animate().alpha(1).setDuration(500).start();
        new android.os.Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                (findViewById(R.id.messages)).animate().setDuration(2500).alpha(0).start();
            }
        }, 1500);
    }

    private void AlertDialog(String tittle, String message, int c) {
        AlertDialog.Builder adb = new AlertDialog.Builder(this);
        adb.setTitle(tittle);
        adb.setMessage(message);
        switch (c) {
            case 1:
                adb.setCancelable(false);
                adb.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        connectorHttpJSON.setUrl(constants.__SS);
                        int result = connectorHttpJSON.downloadSecurityStructure();
                        if (result == 1) {
                            try {
                                progressDialog();
                                new Thread(new Runnable() {
                                    @Override
                                    public void run() {
                                        if (getSecurity()) {
                                            dwUsers = true;
                                            pd.dismiss();
                                        } else {
                                            pd.dismiss();
                                        }
                                    }
                                }).start();
                            } catch (Exception e) {
                                AlertDialog(constants.error, constants._ADV007, 0);
                            }
                        } else if (result == 0)
                            AlertDialog(constants.error, constants._ADV011, 0);
                        else AlertDialog(constants.error, constants._ADV007, 0);
                    }
                });
                adb.setNegativeButton(constants.cButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                });
                break;
            case 2:
                adb.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                });
                break;
            case 3:
                adb.setPositiveButton(constants.okButton, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        finish();
                    }
                });
                break;
        }
        adb.show();
    }

    private void createLocationService() {
        LocationManager lm;
        LocationListener ll;
        lm = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        ll = new LocationListener() {
            @Override
            public void onLocationChanged(Location location) {
            }

            @Override
            public void onStatusChanged(String provider, int status, Bundle extras) {
            }

            @Override
            public void onProviderEnabled(String provider) {
            }

            @Override
            public void onProviderDisabled(String provider) {
                if (started == 0) {
                    AlertDialog(constants.error, constants._ADV020, 3);
                } else {
                    finish();
                }
            }
        };
        lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, ll);
    }
}