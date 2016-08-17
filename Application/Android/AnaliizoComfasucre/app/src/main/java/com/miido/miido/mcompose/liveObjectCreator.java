package com.miido.miido.mcompose;

import android.content.Context;
import android.database.Cursor;
import android.graphics.Color;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewParent;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TextView;

import com.miido.miido.R;
import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * *******************************
 * Created by Alvaro on 27/07/2015.
 * *******************************
 */
public class liveObjectCreator {

    protected constants constants;
    protected Context context;
    protected composeTools tools;

    protected JSONArray dynamicJoiner;
    protected String[][] structure;
    protected String[][] forms;
    protected String[][] handlers;
    protected String[][] options;
    protected int target;
    protected int handler;
    protected int idPrefix = 0;

    public liveObjectCreator(Context context){
        this.context = context;
        this.constants = new constants();
    }

    public Boolean joinHandler(int id, Object parent, int type) throws Exception{
        target = 0;
        handler = 0;
        if(findDynamicJoiner(id)){
            if(findDynamicTarget()){
                createHandler(id, parent, type);
            }
        }
        return false;
    }

    private Boolean findDynamicJoiner(int id) throws Exception{
        for (int a = 0; this.dynamicJoiner.length() > a; a++){
            if(dynamicJoiner.getJSONObject(a).getString("field").equals(""+id)){
                target = Integer.parseInt(dynamicJoiner.getJSONObject(a).getString("formJoined"));
                handler= Integer.parseInt(dynamicJoiner.getJSONObject(a).getString("handler"));
                return true;
            }
        }
        return false;
    }

    private Boolean findDynamicTarget(){
        for (String[] tmp_forms : this.forms){
            if(tmp_forms[0].equals(this.target+"")){
                return true;
            }
        }
        return false;
    }

    protected void createHandler(int id, Object object, int type) throws Exception {
        switch (type){
            case 4:
                this.idPrefix++;
                ((RadioGroup) object).setContentDescription(id + "," + target + "," + handler);
                ((RadioGroup) object).setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                    @Override
                    public void onCheckedChanged(RadioGroup group, int checkedId) {
                        String tmp = (group.getContentDescription().toString());
                        String[] params = tmp.split(",");
                        for (int c = 0; c < group.getChildCount(); c++) {
                            View rb = group.getChildAt(c);
                            try {
                                if (((RadioButton) rb).isChecked()) {
                                    String value = ((RadioButton) rb).getText().toString();
                                    if (matchHandlerValidator(value, params[2])) {
                                        LinearLayout vp = ((LinearLayout) group.getParent());
                                        LinearLayout result = liveObjectsCreator(Integer.parseInt(params[0]), Integer.parseInt(params[1]));
                                        try {
                                            for (int index = 0; index < vp.getChildCount(); index++) {
                                                if (vp.getChildAt(index).getClass().equals(TextView.class)) {
                                                    try {
                                                        if (vp.getChildAt(index).getTag().equals("temporalDataParsing")) {
                                                            JSONObject joTmp = new JSONObject(((TextView) vp.getChildAt(index)).getText().toString());
                                                            for (int index2 = 0; index2 < result.getChildCount(); index2++) {
                                                                if (!(result.getChildAt(index2).getClass().equals(TextView.class))) {
                                                                    try {
                                                                        String tagName = result.getChildAt(index2).getTag().toString();
                                                                        Log.e("TagName", tagName);
                                                                        if (tagName.indexOf(constants.pCodEnf) == 0) {
                                                                            ((AutoCompleteTextView) result.getChildAt(index2)).setText(joTmp.getString("disCode"));
                                                                        } else if (tagName.indexOf(constants.perMedic) == 0){
                                                                            joTmp.getJSONArray("medicaments");
                                                                            SpinnerAdapter sa = ((Spinner) result.getChildAt(index2)).getAdapter();
                                                                            for (int index3 = 0; index3 < sa.getCount(); index3++) {
                                                                                if(sa.getItem(index3).toString().equals("SI")) {
                                                                                    ((Spinner) result.getChildAt(index2)).setSelection(index3);
                                                                                }

                                                                            }
                                                                        }
                                                                    } catch (Exception exc) {
                                                                        exc.printStackTrace();
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } catch (Exception ex ) {
                                                        Log.e(((TextView) vp.getChildAt(index)).getClass().toString(), ex.getMessage());
                                                    }
                                                }
                                            }
                                        } catch (Exception exception) {
                                            Log.e("Founded Exception:::"+exception.getCause(), exception.getMessage());
                                        }
                                        vp.addView(result);
                                        break;
                                    }
                                } else {
                                    ViewParent vp2 = group.getParent();
                                    for (int a = 0; a < ((LinearLayout) vp2).getChildCount(); a++) {
                                        if (((LinearLayout) vp2).getChildAt(a).getClass().equals(LinearLayout.class)) {
                                            ((LinearLayout) ((LinearLayout) vp2).getChildAt(a)).removeAllViews();
                                            ((LinearLayout) vp2).getChildAt(a).destroyDrawingCache();
                                            ((LinearLayout) vp2).removeViewAt(a);
                                        }
                                    }
                                }
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                        }
                    }
                });
                String name = ((View) object).getTag().toString();
                String subName = name.substring(0, 3);
                if (subName.equals(constants.disPrefix)){
                    JSONObject dataRecovery = findJObject(name);
                    TextView tvTmp = new TextView(context);
                    tvTmp.setText(dataRecovery.toString());
                    tvTmp.setTag("temporalDataParsing");
                    ViewParent vpTmp = ((View) object).getParent();
                    tvTmp.setVisibility(View.INVISIBLE);
                    ((LinearLayout) vpTmp).addView(tvTmp);
                    String val = dataRecovery.getString("disStat");
                    for (int c = 0; c < ((RadioGroup) object).getChildCount(); c++) {
                        View rb = ((RadioGroup) object).getChildAt(c);
                        try {
                            if (rb.getClass().equals(RadioButton.class)) {
                                if (((RadioButton) rb).getText().equals(val)) {
                                    ((RadioButton) rb).setChecked(true);
                                }
                            }
                        } catch (Exception e) {
                            Log.e("ErrorSettingCheckBox", e.getMessage());
                            e.printStackTrace();
                        }
                    }
                }
            break;
            case 8:
                this.idPrefix++;
                ((Spinner) object).setContentDescription(id + "," + target + "," + handler);
                ((Spinner) object).setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> parent, View view, int position, long id2) {
                        String tmp = (parent.getContentDescription().toString());
                        String[] params = tmp.split(",");
                        switch (parent.getItemAtPosition(position).toString()) {
                            default:
                                view.hasFocus();
                                try {
                                    if (matchHandlerValidator(parent.getItemAtPosition(position).toString(), params[2])) {
                                        ViewParent vp = view.getParent();
                                        ViewParent vp2 = vp.getParent();
                                        LinearLayout ll = new LinearLayout(context);
                                        ll.addView(liveObjectsCreator(Integer.parseInt(params[0]), Integer.parseInt(params[1])));
                                        ((LinearLayout) vp2).addView(ll);
                                    } else {
                                        ViewParent vp = view.getParent();
                                        ViewParent vp2 = vp.getParent();
                                        for (int a = 0; a < ((LinearLayout) vp2).getChildCount(); a++) {
                                            if (((LinearLayout) vp2).getChildAt(a).getClass().equals(LinearLayout.class)) {
                                                ((LinearLayout) ((LinearLayout) vp2).getChildAt(a)).removeAllViews();
                                                ((LinearLayout) vp2).getChildAt(a).destroyDrawingCache();
                                                ((LinearLayout) vp2).removeViewAt(a);
                                            }
                                        }
                                    }
                                } catch (Exception e) {
                                    Log.e("error", e.getMessage());
                                }
                                break;
                        }
                    }

                    @Override
                    public void onNothingSelected(AdapterView<?> parent) {
                    }
                });
            break;
            case 3:
                ((CheckBox) object).setContentDescription(id + "," + target + "," + handler);
                ((CheckBox) object).setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                    @Override
                    public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                        String tmp = (buttonView.getContentDescription().toString());
                        String[] params = tmp.split(",");
                        try {
                            String checked;
                            if (isChecked) {
                                checked = "on";
                            } else {
                                checked = "off";
                            }
                            ViewParent vp2 = buttonView.getParent();
                            ViewParent vp = vp2.getParent();
                            if (matchHandlerValidator(checked, params[2])) {
                                ((LinearLayout) vp).setOrientation(LinearLayout.VERTICAL);
                                LinearLayout ll = new LinearLayout(context);
                                ll.addView(liveObjectsCreator(Integer.parseInt(params[0]), Integer.parseInt(params[1])));
                                ll.setTag(buttonView.getTag());
                                ((LinearLayout) vp).addView(ll);
                            } else {
                                Boolean deleteFront = false;
                                for (int a = 0; a < ((LinearLayout) vp).getChildCount(); a++) {
                                    if (!(deleteFront)) {
                                        if (((LinearLayout) vp).getChildAt(a) == vp2) {
                                            deleteFront = true;
                                        }
                                    } else {
                                        if (((LinearLayout) vp).getChildAt(a).getClass().equals(LinearLayout.class)) {
                                            ((LinearLayout) ((LinearLayout) vp).getChildAt(a)).removeAllViews();
                                            ((LinearLayout) vp).getChildAt(a).destroyDrawingCache();
                                            ((LinearLayout) vp).removeViewAt(a);
                                        }
                                    }
                                }
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                });
            break;
        }
    }

    public JSONObject findJObject(String name) throws JSONException{
        JSONArray jaTmp = tools.person.getJSONArray("diseases");
        for (int index = 0; index < jaTmp.length(); index++){
            if (name.equals(jaTmp.getJSONObject(index).getString("disName"))) {
                return jaTmp.getJSONObject(index);
            }
        }
        return null;
    }

    protected final Boolean matchHandlerValidator(String value, String handler){
        int types = 0;
        int parameters = 0;
        int matchs = 0;
        for (String[] tmp_handler : handlers){
            if (tmp_handler[0].equals(handler)){
                String[] tmp_parameters = tmp_handler[1].split(",");
                String[] tmp_types = tmp_handler[2].split(",");
                types = tmp_types.length;
                parameters = tmp_parameters.length;
                for (int a = 0; a < tmp_parameters.length; a++){
                    switch (tmp_parameters[a]){
                        case "=":
                            try {
                                if(Integer.parseInt(tmp_types[a]) == Integer.parseInt(value))
                                    matchs++;
                            } catch (NumberFormatException nfe){
                                if(tmp_types[a].equals(value))
                                    matchs++;
                            }
                        break;
                        case "!=":
                            try {
                                if(Integer.parseInt(tmp_types[a]) != Integer.parseInt(value))
                                    matchs++;
                            } catch (NumberFormatException nfe){
                                if(!tmp_types[a].equals(value))
                                    matchs++;
                            }
                        break;
                        case "<":
                            try {
                                if(Integer.parseInt(tmp_types[a]) < Integer.parseInt(value))
                                    matchs++;
                            } catch (NumberFormatException nfe){
                                break;
                            }
                        break;
                        case ">":
                            try {
                                if(Integer.parseInt(tmp_types[a]) > Integer.parseInt(value))
                                    matchs++;
                            } catch (NumberFormatException nfe){
                                break;
                            }
                        break;
                    }
                }
                break;
            }
        }
        return ((matchs == parameters) && (matchs == types));
    }

    protected LinearLayout liveObjectsCreator(int id, int target) throws Exception{
        utilities utilities = new utilities(this.context, this.structure, this.forms, this.options);
        String[][] elements = utilities.getElements(target);
        try{
            utilities.dynamicJoiner = this.dynamicJoiner;
            utilities.handlers = this.handlers;
            utilities.options = this.options;
            return utilities.liveCreation(target, elements, idPrefix);
        } catch (Exception e){
            Log.e("error", e.getMessage()+id);
            return null;
        }
    }

    public void setDynamicJoiner(JSONArray dynamicJoiner){this.dynamicJoiner = dynamicJoiner;}

    public void setStructure(String[][] structure){this.structure = structure;}

    public void setForms(String[][] forms){this.forms = forms;}

    public void setOptions(String[][] options){
        this.options = options;
    }

    public void setHandlers(String[][] handlers){this.handlers = handlers;}

    public void setTools(composeTools tools) {
        this.tools = tools;
    }
}

class utilities {

    protected volatile objectCreator objectCreator;
    protected volatile interfaceBuilder interfaceBuilder;
    protected final Context context;
    protected final String[][] structure;
    protected final String[][] forms;
    protected JSONArray dynamicJoiner;
    protected String[][] handlers;
    protected String[][] options;

    public utilities(Context context, String[][] structure, String[][] forms, String[][] options){
        this.context = context;
        this.structure = structure;
        this.forms = forms;
        this.objectCreator = new objectCreator(this.context);
        this.interfaceBuilder = new interfaceBuilder(this.context);
        this.interfaceBuilder.setOptionsList(options);
        }

    public String[][] getElements(int target) throws Exception{
        String[][] results;
        ArrayList<String[]> results2 = new ArrayList<>();
        for (String[] tmpStructure : this.structure) {
            if (Integer.parseInt(tmpStructure[9]) == target){
                results2.add(tmpStructure);
                //Creacion incluyendo subformularios -- no admitiria recursividad en eventos -- INESTABLE SI HAY HANDLERS HEREDADOS
                /*for (String[] tmpForms : this.forms){
                    if (tmpStructure[0].equals(tmpForms[2])){
                        String[][] rs = getElements(Integer.parseInt(tmpForms[0]));
                        Collections.addAll(results2, rs);
                    }
                }*/
            }
        }
        results = new String[results2.size()][];
        for (int a = 0; a < results2.size(); a++){
            results[a] = results2.get(a);
        }
        return results;
    }

    public LinearLayout liveCreation(int target, String[][] elements, int idPrefix) throws Exception{
        ArrayList<LinearLayout> layouts = new ArrayList<>();
        JSONObject map = new JSONObject();
        for (String[] tmpElement : elements){
            int location;
            try {
                location = map.getInt(tmpElement[9]);
            } catch (JSONException je) {
                location = layouts.size();
                map.put(tmpElement[9], location);
                layouts.add(new LinearLayout(this.context));
                layouts.get(location).setOrientation(LinearLayout.VERTICAL);
                if(Integer.parseInt(tmpElement[9]) != target){
                    layouts.get(map.getInt(getParentForm(tmpElement[9], elements))).addView(layouts.get(location));
                    //layouts.get(location).getLayoutParams().height = 0;
                }
            }
            ArrayList<Object> v = new ArrayList<>();
            objectCreator.setName(tmpElement[1]);
            objectCreator.setHint(tmpElement[5]);
            objectCreator.setReadOnly(tmpElement[11]);
            objectCreator.setAutoCompleteTable(tmpElement[12]);
            objectCreator.setRequired(Boolean.parseBoolean(tmpElement[4]));

            objectCreator.setType(interfaceBuilder.decodeObjectType(tmpElement[3]));
            objectCreator.setInputRules(interfaceBuilder.decodeObjectRules(tmpElement[6]));
            objectCreator.setMaxLength(Integer.parseInt(tmpElement[7]));
            objectCreator.setOptionsList(interfaceBuilder.findOptionsAvailable(Integer.parseInt(tmpElement[0])));

            v.add(objectCreator.buildObject());

            TextView tv = new TextView(this.context);
            tv.setTextColor(Color.BLACK);
            tv.setText(tmpElement[2]);
            layouts.get(location).addView(tv);
            layouts.get(location).addView((View) v.get(v.size() - 1));
            if ((v.get(v.size()-1)).getClass().equals(EditText.class)){
            } else if ((v.get(v.size()-1)).getClass().equals(DatePicker.class)){
                ((DatePicker) v.get(v.size() - 1)).setSpinnersShown(true);
                ((DatePicker) v.get(v.size() - 1)).setCalendarViewShown(false);
            } else if ((v.get(v.size()-1)).getClass().equals(Spinner.class)){
                ((Spinner) v.get(v.size()-1)).setBackgroundResource(R.drawable.spinner);
                liveObjectCreator liveObjectCreator = initializeRecursiveVariables();
                liveObjectCreator.joinHandler(Integer.parseInt(tmpElement[0]), (v.get(v.size() - 1)), 8);
            } else if ((v.get(v.size()-1)).getClass().equals(AutoCompleteTextView.class)){
                ((AutoCompleteTextView) v.get(v.size()-1)).setMaxHeight(50);
                ((AutoCompleteTextView) v.get(v.size()-1)).setBackgroundResource(R.drawable.focus_border_style);
                ((AutoCompleteTextView) v.get(v.size()-1)).setTextColor(Color.BLACK);
                AutoCompleteTextViewListener(v.get(v.size()-1));
            } else if ((v.get(v.size()-1)).getClass().equals(CheckBox.class)){
                ((CheckBox) v.get(v.size()-1)).setButtonDrawable(R.drawable.checkbox);
                ((CheckBox) v.get(v.size()-1)).setTextColor(Color.DKGRAY);
                ((CheckBox) v.get(v.size()-1)).setText(tmpElement[2]);
                tv.setText("");
                liveObjectCreator liveObjectCreator = initializeRecursiveVariables();
                liveObjectCreator.joinHandler(Integer.parseInt(tmpElement[0]), (v.get(v.size() - 1)), 3);
            }
        }
        LinearLayout result = new LinearLayout(this.context);
        result.setOrientation(LinearLayout.VERTICAL);
        result = layouts.get(map.getInt(target+""));
        result.setContentDescription(layouts.size() + "ss");
        return result;
    }

    private liveObjectCreator initializeRecursiveVariables(){
        liveObjectCreator liveObjectCreator = new liveObjectCreator(this.context);
        liveObjectCreator.setDynamicJoiner(this.dynamicJoiner);
        liveObjectCreator.setStructure(this.structure);
        liveObjectCreator.setForms(this.forms);
        liveObjectCreator.setHandlers(this.handlers);
        liveObjectCreator.setOptions(this.options);
        return liveObjectCreator;
    }

    private void AutoCompleteTextViewListener(Object object){
        ((AutoCompleteTextView) object).setOnKeyListener(new View.OnKeyListener() {
            @Override
            public boolean onKey(View v, int keyCode, KeyEvent event) {
                sqlHelper sqlHelper = new sqlHelper(context);
                final constants constants = new constants();
                final List<String> options = new ArrayList<>();
                try {
                    AutoCompleteTextView ac = (AutoCompleteTextView) v;
                    String s = ac.getText().toString();
                    String auct = ac.getContentDescription().toString();
                    if (s.length() > 2) {
                        try {
                            options.clear();
                        } catch (Exception e) {
                            e.printStackTrace();
                        }

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
                        ArrayAdapter<String> adapter = new ArrayAdapter<>(context, R.layout.dropdown, empty);
                        adapter.setNotifyOnChange(true);
                        adapter.notifyDataSetChanged();
                        adapter.getFilter().filter(s, ac);
                        ac.setAdapter(adapter);
                        ac.setThreshold(s.length() - 1);
                        cursor.close();
                    }
                } catch (Exception e){}
                return false;
            }
        });
    }

    private String getParentForm(String parent, String[][] elements){
        for (String[] tmpForm : this.forms){
            if (tmpForm[0].equals(parent)){
                for (String[] tmpElements: elements){
                    if (tmpForm[2].equals(tmpElements[0])){
                        return tmpElements[9];
                    }
                }
            }
        }
        return null;
    }
}