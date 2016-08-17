package com.miido.miido.util;

import android.content.Context;
import android.graphics.Color;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TextView;

import com.miido.miido.R;
import com.miido.miido.mcompose.constants;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by Alvaro on 03/08/2015.
 *
 **/
public class requestValidator {

    private constants constants;
    Context context;
    Boolean checkBox = false;
    int cBc = 0;
    JSONObject tmpData;
    JSONArray diseasesData;

    int reuseCounter = 0;
    Boolean lastCompatibleLvl = false;
    Boolean medicamentOn = false;

    public requestValidator(Context context){
        this.constants = new constants();
        this.context = context;
        this.tmpData = new JSONObject();
        this.diseasesData = new JSONArray();
    }

    public Boolean validate(ViewGroup v) {
        try {
            TextView lastTvReaded = new TextView(this.context);
            int wrong = 0;
            for (int a = 0; a < v.getChildCount(); a++) {
                Object o = v.getChildAt(a);
                if (o.getClass().equals(CheckBox.class)) {
                    checkBox = true;
                    if (((CheckBox) o).isChecked()) {
                        if ((reuseCounter > 3) && (lastCompatibleLvl)) {
                            String tmpSubName = ((CheckBox) o).getTag().toString().substring(0, 8);
                            if ((medicamentOn) && (tmpSubName.equals(constants.perMedic))) {
                                try {
                                    diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).put(new JSONObject());
                                    medicamentOn = true;
                                } catch (Exception e) {
                                    Log.e("Error", e.getMessage());
                                }
                            }
                        } else {
                            tmpData.put(((CheckBox) o).getTag().toString(), ((CheckBox) o).isChecked());
                        }
                        cBc++;
                    } else {
                        if (Integer.parseInt(((CheckBox) o).getId() + "") != 1)
                            cBc++;
                    }
                    return true;
                } else if (o.getClass().equals(TableLayout.class)) {
                    if (checkBox) {
                        if (cBc == 0) {
                            wrong++;
                        }
                        checkBox = false;
                        cBc = 0;
                    }
                }
                if (o.getClass().equals(EditText.class)) {
                    if (((EditText) o).getText().toString().equals("")) {
                        if (Integer.parseInt(((EditText) o).getId() + "") == 1) {
                            ((EditText) o).setBackgroundResource(R.drawable.invalid);
                            try {
                                lastTvReaded.setTextColor(Color.RED);
                            } catch (Exception e) {
                                Log.e("Error 1", e.getMessage());
                                e.printStackTrace();
                            }
                            wrong++;
                        }
                    } else {
                        tmpData.put(((EditText) o).getTag().toString(), ((EditText) o).getText().toString());
                        ((EditText) o).setBackgroundResource(R.drawable.focus_border_style);
                        try {
                            lastTvReaded.setTextColor(Color.BLACK);
                        } catch (Exception e) {
                            Log.e("Error 2", e.getMessage());
                            e.printStackTrace();
                        }
                    }
                } else if (o.getClass().equals(Spinner.class)) {
                    if (((Spinner) o).getSelectedItem().toString().equals("-")) {
                        if (Integer.parseInt(((Spinner) o).getId() + "") == 1) {
                            ((Spinner) o).setBackgroundResource(R.drawable.invalid);
                            lastTvReaded.setTextColor(Color.RED);
                            wrong++;
                        }
                    } else {
                        if ((reuseCounter > 3) && (lastCompatibleLvl)) {
                            String tmpSubName = ((Spinner) o).getTag().toString().substring(0, 8);
                            if (tmpSubName.equals(constants.perMedic)) {
                                diseasesData.getJSONObject(diseasesData.length() - 1).put(constants.medicaments, new JSONArray());
                                diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).put(new JSONObject());
                                medicamentOn = true;
                            } else if (medicamentOn) {
                                try {
                                    int medL = diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).length() - 1;
                                    if (((Spinner) o).getTag().toString().equals(constants.perProvee))
                                        diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).getJSONObject(medL).put(constants.medProv, ((Spinner) o).getSelectedItem().toString());
                                    if (((Spinner) o).getTag().toString().equals(constants.perEvoluc))
                                        diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).getJSONObject(medL).put(constants.medEvol, ((Spinner) o).getSelectedItem().toString());
                                } catch (Exception e) {
                                    Log.e("Error", e.getMessage());
                                }
                            }
                        } else {
                            tmpData.put(((Spinner) o).getTag().toString(), ((Spinner) o).getSelectedItem().toString());
                        }
                        ((Spinner) o).setBackgroundResource(R.drawable.spinner);
                        lastTvReaded.setTextColor(Color.BLACK);
                    }
                } else if (o.getClass().equals(RadioGroup.class)) {
                    if (((RadioGroup) o).getCheckedRadioButtonId() == -1) {
                        if (Integer.parseInt(((RadioGroup) o).getId() + "") == 1) {
                            lastTvReaded.setTextColor(Color.RED);
                            ((RadioGroup) o).setAlpha(Float.parseFloat("0.2"));
                            wrong++;
                        }
                    } else {
                        String prefix = (((RadioGroup) o).getTag().toString()).substring(0, 3);
                        if ((reuseCounter >= 3) && (prefix.equals(constants.perPrefix))) {
                            diseasesData.put(new JSONObject());
                            (diseasesData.getJSONObject(diseasesData.length() - 1)).put(constants.disName, ((RadioGroup) o).getTag().toString());
                            lastCompatibleLvl = true;
                            medicamentOn = false;
                            for (int c = 0; c < ((RadioGroup) o).getChildCount(); c++) {
                                View rb = ((RadioGroup) o).getChildAt(c);
                                if (rb.getClass().equals(RadioButton.class)) {
                                    if (((RadioButton) rb).isChecked()) {
                                        (diseasesData.getJSONObject(diseasesData.length() - 1)).put(constants.disStat, ((RadioButton) rb).getText().toString());
                                    }
                                }
                            }
                        } else {
                            lastCompatibleLvl = false;
                            for (int c = 0; c < ((RadioGroup) o).getChildCount(); c++) {
                                View rb = ((RadioGroup) o).getChildAt(c);
                                if (rb.getClass().equals(RadioButton.class)) {
                                    if (((RadioButton) rb).isChecked()) {
                                        tmpData.put(((RadioGroup) o).getTag().toString(), ((RadioButton) rb).getText().toString());
                                    }
                                }
                            }
                        }
                        lastTvReaded.setTextColor(Color.BLACK);
                        ((RadioGroup) o).setAlpha(1);
                    }
                } else if (o.getClass().equals(AutoCompleteTextView.class)) {
                    if (((AutoCompleteTextView) o).getText().toString().equals("")) {
                        if (Integer.parseInt(((AutoCompleteTextView) o).getId() + "") == 1) {
                            ((AutoCompleteTextView) o).setBackgroundResource(R.drawable.invalid);
                            try {
                                lastTvReaded.setTextColor(Color.RED);
                            } catch (Exception e) {
                                Log.e("Error 3", e.getMessage());
                                e.printStackTrace();
                            }
                            wrong++;
                        }
                    } else {
                        if ((reuseCounter > 3) && (lastCompatibleLvl)) {
                            if (medicamentOn) {
                                int medL = diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).length() - 1;
                                diseasesData.getJSONObject(diseasesData.length() - 1).getJSONArray(constants.medicaments).getJSONObject(medL).put(constants.medDesc, ((AutoCompleteTextView) o).getText().toString());
                            } else {
                                diseasesData.getJSONObject(diseasesData.length() - 1).put(constants.disCode, ((AutoCompleteTextView) o).getText().toString());
                            }
                        } else {
                            tmpData.put(((AutoCompleteTextView) o).getTag().toString(), ((AutoCompleteTextView) o).getText().toString());
                        }
                        ((AutoCompleteTextView) o).setBackgroundResource(R.drawable.focus_border_style);
                        try {
                            lastTvReaded.setTextColor(Color.BLACK);
                        } catch (Exception e) {
                            Log.e("Error 4", e.getMessage());
                            e.printStackTrace();
                        }
                    }
                } else if (o.getClass().equals(TextView.class)) {
                    lastTvReaded = ((TextView) o);
                } else {
                    try {
                        if (
                            (o.getClass().equals(android.widget.FrameLayout.class)) ||
                            (o.getClass().equals(android.widget.RelativeLayout.class)) ||
                            (o.getClass().equals(android.widget.LinearLayout.class)) ||
                            (o.getClass().equals(android.widget.TableLayout.class)) ||
                            (o.getClass().equals(android.widget.TableRow.class))
                        ) {
                            if (((ViewGroup) o).getLayoutParams().height != 0) {
                                reuseCounter++;
                                wrong += (validate((ViewGroup) o) ? 0 : 1);
                                reuseCounter--;
                            }
                        }
                    } catch (Exception e) {
                        Log.e("Error 5 ", e.getMessage());
                        e.printStackTrace();
                    }
                }
            }
            if (v.getClass().equals(TableLayout.class))
                if (checkBox) {
                    if (cBc == 0)
                        wrong++;
                    checkBox = false;
                    cBc = 0;
                }
            if(diseasesData.length()> 0) {
                tmpData.put(constants.diseases, diseasesData);
            }
            return (wrong == 0);
        } catch (JSONException je) {
            Log.e("Error JSON", je.getMessage());
            return false;
        }
    }

    public JSONObject JSONGetter(){
        return tmpData;
    }
}