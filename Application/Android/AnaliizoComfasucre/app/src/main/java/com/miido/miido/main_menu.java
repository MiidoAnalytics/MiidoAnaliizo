package com.miido.miido;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import com.miido.miido.mcompose.composeTools;
import com.miido.miido.mcompose.constants;
import com.miido.miido.mcompose.structure;
import com.miido.miido.util.sqlHelper;

public class main_menu extends Activity {

    private constants constants;
    private structure structure;
    private sqlHelper sqlHelper;
    private LinearLayout cNew;
    private LinearLayout fPen;
    private LinearLayout vCha;
    private LinearLayout cSet;
    private ImageView icNew;
    private ImageView ifPen;
    private ImageView ivCha;
    private ImageView icSet;
    private Bundle bundle;
    private Cursor cursor;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_menu);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        init();
    }

    private void init() {
        this.constants = new constants();
        this.sqlHelper = new sqlHelper(this);
        this.bundle = getIntent().getExtras();
        createLocationService();
        getPhysicalInstances();
        setEventListener();
    }

    private void createLocationService() {
        LocationManager lm = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        LocationListener ll = new LocationListener() {
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
                AlertDialog(constants.error, constants._ADV020, 1);
            }
        };
        lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, ll);
    }

    private void getPhysicalInstances() {
        cNew = (LinearLayout) findViewById(R.id.create);
        fPen = (LinearLayout) findViewById(R.id.search);
        vCha = (LinearLayout) findViewById(R.id.graphChart);
        cSet = (LinearLayout) findViewById(R.id.settings);
        icNew = (ImageView) findViewById(R.id.icreate);
        ifPen = (ImageView) findViewById(R.id.isearch);
        ivCha = (ImageView) findViewById(R.id.igraphChart);
        icSet = (ImageView) findViewById(R.id.isettings);
    }

    private void setEventListener() {
        setCreate();
        setSearch();
        setChart();
        setSettings();
    }

    private void setCreate() {
        icNew.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
        this.cNew.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startNew();
            }
        });
        this.icNew.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startNew();
            }
        });
    }

    private void startNew() {
        try {
            if (structureFinder()) {
                int count = (cursor.getInt(0));
                if (count == 1) {
                    Intent i = new Intent(getApplicationContext(), master.class);
                    i.putExtra("userId", bundle.getString("userId"));
                    i.putExtra("username", bundle.getString("username"));
                    startActivity(i);
                } else if (count == 0) {
                    AlertDialog(constants.error, constants._ADV006, 0);
                } else {
                    AlertDialog(constants.error, constants._ADV007, 0);
                }
            } else {
                AlertDialog(constants.error, constants._ADV008, 0);
            }
        } catch (SQLiteException sqe) {
            AlertDialog(constants.error, constants._ADV008, 0);
        } catch (Exception e) {
            AlertDialog(constants.error, constants._ADV007 + "\n\n" + e.getMessage(), 0);
        }
    }

    private Boolean structureFinder() {
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.QUERY_7);
        sqlHelper.execQuery();
        cursor = sqlHelper.getCursor();
        return (cursor != null) && (cursor.getCount() > 0);
    }

    private void setSearch() {
        ifPen.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
        this.fPen.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startFindSearch();
            }
        });
        this.ifPen.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startFindSearch();
            }
        });
    }

    private void startFindSearch() {
        try {
            if (structureFinder()) {
                int count = (cursor.getInt(0));
                if (count == 1) {
                    Intent i = new Intent(getApplicationContext(), find_search.class);
                    startActivity(i);
                } else if (count == 0) {
                    AlertDialog(constants.error, constants._ADV006, 0);
                } else {
                    AlertDialog(constants.error, constants._ADV007, 0);
                }
            } else {
                AlertDialog(constants.error, constants._ADV008, 0);
            }
        } catch (SQLiteException sqe) {
            AlertDialog(constants.error, constants._ADV008, 0);
        } catch (Exception e) {
            AlertDialog(constants.error, constants._ADV007 + "\n\n" + e.getMessage(), 0);
        }
    }

    private void setChart() {
        ivCha.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
        this.vCha.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), statistics.class);
                //i.putExtra("userId", bundle.getString("userId"));
                i.putExtra("username", bundle.getString("username"));
                startActivity(i);
            }
        });
        this.ivCha.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), statistics.class);
                //i.putExtra("userId", bundle.getString("userId"));
                i.putExtra("username", bundle.getString("username"));
                startActivity(i);
            }
        });
        this.vCha.setOnLongClickListener(new View.OnLongClickListener() {
            @Override
            public boolean onLongClick(View v) {
                //validateOrderStructure();
                return false;
            }
        });
    }

    private void setSettings() {
        icSet.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
        this.cSet.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), settings.class);
                startActivity(i);
                //validateStructure();
            }
        });
        this.icSet.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(getApplicationContext(), settings.class);
                startActivity(i);
            }
        });
        this.cSet.setOnLongClickListener(new View.OnLongClickListener() {
            @Override
            public boolean onLongClick(View v) {
                validateStructure();
                return false;
            }
        });
    }

    private void validateStructure() {
        this.structure = new structure(getApplicationContext());
        try {
            this.structure.setStructure();
            AlertDialog("Búsqueda de Errores en la Estructura", this.structure.validateStructure(), 0);
        } catch (Exception e) {
            toast(e.getMessage());
        }

    }

    private void validateOrderStructure() {
        this.structure = new structure(getApplicationContext());
        composeTools composeTools = new composeTools(getApplicationContext());
        try {
            this.structure.setStructure();
            composeTools.orderFields();
            AlertDialog("Result", composeTools.info, 0);
        } catch (Exception e) {
            //toast(e.getMessage());
        }
    }

    @Override
    public void onBackPressed() {
    }

    private void toast(String msj) {
        Toast.makeText(this, msj, Toast.LENGTH_LONG).show();
    }

    private void AlertDialog(String Tittle, String Message, int caseA) {
        AlertDialog.Builder ad = new AlertDialog.Builder(this);
        ad.setTitle(Tittle);
        ad.setMessage(Message);
        switch (caseA) {
            case 0:
                ad.setPositiveButton("ok", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                    }
                });
                break;
            case 1:
                ad.setPositiveButton("ok", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        close();
                    }
                });
                break;
        }
        ad.show();
    }

    private void close() {
        finish();
    }
}