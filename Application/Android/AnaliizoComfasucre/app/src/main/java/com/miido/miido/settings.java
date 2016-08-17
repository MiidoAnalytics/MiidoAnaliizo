package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import com.miido.miido.mcompose.constants;
import com.miido.miido.util.ConnectorHttpJSON;

public class settings extends Activity {

    private ConnectorHttpJSON ConnectorHttpJSON;
    private constants constants;

    private LinearLayout[] trOptions;
    private ImageView[] ivOptions;
    private ProgressDialog pd;

    private String size;
    private String[] response;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        init();
    }

    private void init() {
        initializeElements();
        getLayoutElements();
        //setInitialLocation();
        //initialAnimation();
        createButtonsListener();
    }

    private void initializeElements() {
        int optionsQuantity = 5;
        this.constants = new constants();
        this.trOptions = new LinearLayout[optionsQuantity];
        this.ivOptions = new ImageView[optionsQuantity];
    }

    private void getLayoutElements() {
        trOptions[0] = (LinearLayout) findViewById(R.id.syncData);
        trOptions[1] = (LinearLayout) findViewById(R.id.loadData);
        trOptions[2] = (LinearLayout) findViewById(R.id.updateApp);
        trOptions[3] = (LinearLayout) findViewById(R.id.syncGeneralParams);
        trOptions[4] = (LinearLayout) findViewById(R.id.back);
        ivOptions[0] = (ImageView) findViewById(R.id.isyncData);
        ivOptions[1] = (ImageView) findViewById(R.id.iloadData);
        ivOptions[2] = (ImageView) findViewById(R.id.iupdateApp);
        ivOptions[3] = (ImageView) findViewById(R.id.isyncGeneralParams);
        ivOptions[4] = (ImageView) findViewById(R.id.iBack);
    }

    private void createButtonsListener() {
        trOptions[0].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                downloadDataBase();
            }
        });
        ivOptions[0].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                downloadDataBase();
            }
        });

        trOptions[1].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                uploadPolls();
            }
        });
        ivOptions[1].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                uploadPolls();
            }
        });
        trOptions[2].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                toast(constants._ADV003);
            }
        });
        trOptions[3].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                downloadStructure();
            }
        });
        ivOptions[3].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                downloadStructure();
            }
        });
        trOptions[3].setOnLongClickListener(new View.OnLongClickListener() {
            @Override
            public boolean onLongClick(View v) {
                Boolean allowed = false;
                //WifiManager manager = (WifiManager) getSystemService(Context.WIFI_SERVICE);
                //WifiInfo info = manager.getConnectionInfo();
                //String address = info.getMacAddress();
                //Log.e("MAC", address+" vs ");
                for (String allowedMac : constants.developer_devices) {
                    //if (allowedMac.toLowerCase().equals(address.toLowerCase())) {
                    allowed = true;
                    break;
                    //}
                }
                if (allowed) {
                    createProgressDialog("Iniciando " + constants.__ST_developer);
                    pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
                        @Override
                        public void onDismiss(DialogInterface dialog) {
                            if (size.equals("1")) {
                                AlertDialog(constants.atention, "Modo desarrollo activado");
                            } else
                                AlertDialog(response[0], response[1]);
                            createButtonsListener();
                        }
                    });
                    new Thread(new Runnable() {
                        @Override
                        public void run() {
                            removeButtonListener();
                            syncSurvey(constants.__ST_developer, true);
                        }
                    }).start();
                }
                return false;
            }
        });
        trOptions[4].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        ivOptions[4].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }

    private void downloadDataBase() {
        createProgressDialog(constants._ADV004);
        pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                try {
                    AlertDialog(constants.finish, constants._ADV021);
                    createButtonsListener();
                } catch (Exception e) {
                    AlertDialog(constants.error, constants._ADV011);
                }
            }
        });
        new Thread(new Runnable() {
            @Override
            public void run() {
                removeButtonListener();
                downloadFiles(constants.__DB);
            }
        }).start();
    }

    private void uploadPolls() {
        try {
            com.miido.miido.util.sqlHelper sqlHelper = new com.miido.miido.util.sqlHelper(getApplicationContext());
            sqlHelper.OOCDB();
            sqlHelper.setQuery(constants.QUERY_6);
            sqlHelper.execQuery();
            Cursor cursor = sqlHelper.getCursor();
            if (cursor.getCount() > 0) {
                Intent i = new Intent(getApplicationContext(), synchronizer.class);
                startActivity(i);
            } else {
                AlertDialog(constants.atention, constants._ADV008);
            }
        } catch (SQLiteException sqe) {
            AlertDialog(constants.atention, constants._ADV008);
        }
    }

    private void downloadStructure() {
        createProgressDialog(constants._ADV005);
        pd.setOnDismissListener(new DialogInterface.OnDismissListener() {
            @Override
            public void onDismiss(DialogInterface dialog) {
                try {
                    if (size.equals("1")) {
                        AlertDialog(constants.atention, "Modo desarrollo activado");
                    } else {
                        AlertDialog(response[0], response[1]);
                    }
                    createButtonsListener();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });
        new Thread(new Runnable() {
            @Override
            public void run() {
                removeButtonListener();
                syncSurvey(constants.__ST, false);
            }
        }).start();
    }

    private void removeButtonListener() {
        trOptions[0].setOnClickListener(null);
        trOptions[1].setOnClickListener(null);
        trOptions[2].setOnClickListener(null);
        trOptions[3].setOnClickListener(null);
    }

    private void createProgressDialog(String message) {
        pd = new ProgressDialog(this);
        pd.setMessage(message);
        pd.setCanceledOnTouchOutside(false);
        pd.setCancelable(false);
        pd.show();
    }

    private void dismissProgressDialog() {
        pd.dismiss();
    }

    private void downloadFiles(String url) {
        try {
            ConnectorHttpJSON = new ConnectorHttpJSON(getApplicationContext());
            ConnectorHttpJSON.setUrl(url);
            ConnectorHttpJSON.createUrl();
            size = ConnectorHttpJSON.downloadDataBase();
            dismissProgressDialog();
        } catch (Exception e) {
            dismissProgressDialog();
        }
    }

    private void syncSurvey(String url, Boolean developerMode) {
        try {
            ConnectorHttpJSON = new ConnectorHttpJSON(getApplicationContext());
            ConnectorHttpJSON.setUrl(url);
            response = ConnectorHttpJSON.downloadStructure(developerMode);
            size = ConnectorHttpJSON.dCase + "";
            dismissProgressDialog();
        } catch (Exception e) {
            size = "0";
            response[0] = constants.error;
            response[1] = e.getMessage();
            dismissProgressDialog();
        }
    }

    private void AlertDialog(String header, String body) {
        AlertDialog.Builder ad = new AlertDialog.Builder(this);
        ad.setTitle(header);
        ad.setMessage(body);
        ad.setPositiveButton("Aceptar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

            }
        });
        ad.show();
    }

    private void toast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_LONG).show();
    }
}