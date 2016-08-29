package com.miido.analiizoOBRAS.util;

import android.content.Context;
import android.graphics.Color;
import android.text.InputType;
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

import com.miido.analiizoOBRAS.R;
import com.miido.analiizoOBRAS.mcompose.Constants;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * valida los campos vacios de la encuesta.
 * @author Alvaro Salagado MIIDO S.A.S 03/08/2015.
 * @version 1.0
 */
public class RequestValidator {

    private Constants constants;
    Context context;
    Boolean checkBox = false;
    int cBc = 0;
    JSONObject tmpData;
    JSONObject tmp2;
    JSONArray diseasesData;

    JSONObject response;
    JSONObject structure;
    JSONObject forms;

    int reuseCounter = 0;
    Boolean lastCompatibleLvl = false;
    Boolean medicamentOn = false;

    /**
     * Constructor
     * @param context contexto de la aplicación.
     */
    public RequestValidator(Context context,JSONObject estructure){
        this.constants = new Constants();
        this.context = context;
        this.tmpData = new JSONObject();
        tmp2 = new JSONObject();
        this.diseasesData = new JSONArray();
        response = new JSONObject();
        this.forms = new JSONObject();
        this.structure = estructure;
    }

    public JSONObject getTmpResponse(){
        return tmp2;
    }

    public JSONObject getForms(){
        try {
            configureForms();
        }catch (JSONException ex){
            Log.e("Error json", ex.getMessage());
        }
        return forms;
    }

    private JSONObject getForm(String formId) throws JSONException {
        JSONArray forms = this.structure.getJSONArray("forms");
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getString("Id").equals(formId)){
                return form;
            }
        }
        return null;
    }

    private JSONArray getFormFields(String formId) throws JSONException{
        JSONArray fields = this.structure.getJSONArray("fields_structure");
        JSONArray tmpFields = new JSONArray();
        for( int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString("Form").equals(formId)){
                tmpFields.put(field);
            }
        }
        return tmpFields;
    }

    private JSONObject getSubForm(String fieldId) throws JSONException{
        JSONArray forms = this.structure.getJSONArray("forms");
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(!form.getString("Inside").equals("0") && form.getString("Parent").equals(fieldId)){
                return form;
            }
        }
        return null;
    }

    private JSONObject getFieldByName(String fieldName)throws JSONException{
        JSONArray fields = this.structure.getJSONArray("fields_structure");
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString("Name").equals(fieldName)){
                return field;
            }
        }
        return null;
    }

    private boolean hasDynamicJoiner(String fieldId) throws JSONException{
        JSONArray joiners = this.structure.getJSONArray("dynamicJoiner");
        for(int i = 0; i < joiners.length(); i++){
            JSONObject joiner = joiners.getJSONObject(i);
            if(joiner.getString("field").equals(fieldId)){
                return true;
            }
        }
        return false;
    }

    private JSONObject getFieldById(String fieldId)throws JSONException{
        JSONArray fields = this.structure.getJSONArray("fields_structure");
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString("Id").equals(fieldId)){
                return field;
            }
        }
        return null;
    }

    private int findForm(String formId) throws JSONException{
        if(forms.has("Forms")) {
            Object object = forms.get("Forms");
            if(object instanceof JSONObject){
                JSONObject field = (JSONObject) object;
                if(field.getString("Id").equals(formId)){
                    return 0;
                }
            }else{
                JSONArray fields = (JSONArray) object;
                for(int i = 0; i < fields.length(); i++){
                    JSONObject field = fields.getJSONObject(i);
                    if(field.getString("Id").equals(formId)){
                        return i;
                    }
                }
            }
        }
        return -1;
    }

    private JSONObject configureForms()throws JSONException{

        Object object = this.forms.get("Forms");
        if(object instanceof JSONArray){
            JSONObject frontForm = new JSONObject();
            JSONArray backForms = new JSONArray();
            JSONArray forms = (JSONArray) object;
            for(int i = 0; i < forms.length(); i++){
                JSONObject form = forms.getJSONObject(i);
                if(form.getString("Inside").equals("0")){
                    frontForm = new JSONObject(form.toString());
                }else{
                    backForms.put(form);
                }
            }
            object = frontForm.get("Field");
            if(object instanceof JSONObject){
                for(int i = 0; i < backForms.length(); i++){
                    JSONObject backForm = backForms.getJSONObject(i);
                    if(backForm.getString("Parent").equals(frontForm.getJSONObject("Field").getString("Id"))){
                        frontForm.getJSONObject("Field").put("SubForm",backForm);
                        break;
                    }
                }
            }else{
                JSONArray tmpFrontForms = frontForm.getJSONArray("Field");
                for(int i = 0; i < tmpFrontForms.length(); i++){
                    JSONObject tmpfrontForm = tmpFrontForms.getJSONObject(i);
                    for(int j = 0; j < backForms.length(); j++){
                        JSONObject backForm = backForms.getJSONObject(j);
                        if(backForm.getString("Parent").equals(tmpfrontForm.getString("Id"))){
                            frontForm.getJSONArray("Field").getJSONObject(i).put("SubForm",backForm);
                        }
                    }
                }
            }
            this.forms = new JSONObject().put("Forms",frontForm);
        }
        //Log.e("VALIDATE", forms.toString());
        return this.forms;
    }

    private JSONObject accumulateFields(String name, String value) throws JSONException{
        JSONObject field = getFieldByName(name);

        if(!hasDynamicJoiner(field.getString("Id"))) {
            JSONObject form = getForm(field.getString("Form"));

            JSONObject tmpField = new JSONObject();
            tmpField.put("Id", field.getString("Id"));
            tmpField.put("Name", field.getString("Name"));
            tmpField.put("Category", field.getString("Type"));
            tmpField.put("Type", field.getString("Rules"));
            tmpField.put("Label", field.getString("Label"));
            tmpField.put("Value", value);

            int position = findForm(form.getString("Id"));
            if(position != -1){
                Object object = this.forms.get("Forms");
                if(object instanceof JSONObject){
                    this.forms.getJSONObject("Forms").accumulate("Field",tmpField);
                }else{
                    this.forms.getJSONArray("Forms").getJSONObject(position).accumulate("Field",tmpField);
                }
            }else {

                JSONObject tmpForm = new JSONObject();
                tmpForm.put("Id", form.getString("Id"));
                tmpForm.put("Name", form.getString("Name"));
                tmpForm.put("Header", form.getString("Header"));
                tmpForm.put("Parent", form.getString("Parent"));
                tmpForm.put("Inside", form.getString("Inside"));

                tmpForm.put("Field", tmpField);

                this.forms.accumulate("Forms", tmpForm);
            }
        }

        //Log.e("VALIDATE", forms.toString());

        return this.forms;
    }

    /**
     * valida los campos o las vistas contenidas en un contenedor viewGroup de tal manera que controle que no se permitan campos vacios obligatorios.
     * Los campos obligatorios son bordeados con color rojo para mostrar al encuestador que campos faltan por llenar
     * @param v contenedor de las vistas
     * @return true si los campos de la encuesta han sido validados, false en caso contrario
     */
    public Boolean validate(ViewGroup v, int currentform) {
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
                            String name = ((CheckBox) o).getTag().toString();
                            String value = ((CheckBox) o).isChecked()+"";

                            /**
                             * INICIO RESTRUCTURACIÓN DE LA RESPUESTA
                             */

                            accumulateFields(name,value);
                            //configureForms();

                            /**
                             * FIN RESTRUCTURACIÓN
                             */

                            //(+)
                            /*String formName = "Form"+currentform;
                            String name = ((CheckBox) o).getTag().toString();
                            boolean value = ((CheckBox) o).isChecked();
                            try{
                                JSONArray forms = tmp2.getJSONArray(formName);
                                boolean finded = true;
                                for(int i = 0; i < forms.length(); i++){
                                    try {
                                        forms.getJSONObject(i).getJSONArray(name).put(value);
                                    }catch (JSONException ex){
                                        finded = false;
                                    }
                                }
                                if(!finded)
                                    forms.put(new JSONObject().put(name, new JSONArray().put(value)));
                            }catch (JSONException ex){
                                tmp2.put(formName,new JSONArray().put(new JSONObject().put(name,new JSONArray().put(value))));
                            }*/

                            //Log.e("Structure", tmp2.toString());
                            //(+)
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
                        //(+)

                        String name = ((EditText) o).getTag().toString();
                        String value = ((EditText) o).getText().toString();

                        /**
                         * INICIO RESTRUCTURACIÓN DE LA RESPUESTA
                         */

                        accumulateFields(name,value);
                        //configureForms();

                        /**
                         * FIN RESTRUCTURACIÓN
                         */


                        int type = ((EditText) o).getInputType();
                        try{
                            if(type == (InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL)){
                                value = Float.parseFloat(value) + "";
                            }else{
                                if(type == InputType.TYPE_CLASS_NUMBER){
                                    value = Integer.parseInt(value) + "";
                                }
                            }
                        }catch (NumberFormatException ex){

                        }

                        tmpData.put(name, value);

                        String formName = "Form"+currentform;
                        try{
                            JSONArray form = tmp2.getJSONArray(formName);
                            int finded = 0;
                            for(int i = 0; i < form.length(); i++){
                                try {
                                    form.getJSONObject(i).getJSONArray(name).put(value);
                                }catch (JSONException ex){
                                    finded++;
                                }
                            }
                            if(finded == form.length()) {
                                form.put(new JSONObject().put(name, new JSONArray().put(value)));
                            }
                        }catch (JSONException ex){
                            tmp2.put(formName,new JSONArray().put(new JSONObject().put(name,new JSONArray().put(value))));
                        }
                        //Log.e("Structure", tmp2.toString());
                        //(+)

                        ((EditText) o).setBackgroundResource(R.drawable.focus_border_style);
                        try {
                            lastTvReaded.setTextColor(Color.BLACK);
                        } catch (Exception e) {
                            Log.e("Error 2", e.getMessage());
                            e.printStackTrace();
                        }
                    }
                } else if (o.getClass().equals(Spinner.class)) {
                    if (((Spinner) o).getSelectedItem().toString().equals("Seleccione ...")) {
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
                                    //Log.e("Error", e.getMessage());
                                }
                            }
                        } else {
                            tmpData.put(((Spinner) o).getTag().toString(), ((Spinner) o).getSelectedItem().toString());

                            //(+)
                            String name = ((Spinner) o).getTag().toString();
                            String value = ((Spinner) o).getSelectedItem().toString();

                            /**
                             * INICIO RESTRUCTURACIÓN DE LA RESPUESTA
                             */

                            accumulateFields(name,value);
                            //configureForms();

                            /**
                             * FIN RESTRUCTURACIÓN
                             */

                            String formName = "Form"+currentform;
                            try{
                                JSONArray form = tmp2.getJSONArray(formName);
                                int  finded = 0;
                                for(int i = 0; i < form.length(); i++){
                                    try {
                                        form.getJSONObject(i).getJSONArray(name).put(value);
                                    }catch (JSONException ex){
                                        finded++;
                                    }
                                }
                                if(finded == form.length()) {
                                    form.put(new JSONObject().put(name, new JSONArray().put(value)));
                                }
                            }catch (JSONException ex){
                                tmp2.put(formName,new JSONArray().put(new JSONObject().put(name,new JSONArray().put(value))));
                            }
                            //Log.e("Structure", tmp2.toString());
                            //(+)
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

                                        //(+)
                                        String name = ((RadioGroup) o).getTag().toString();
                                        String value = ((RadioButton) rb).getText().toString();

                                        /**
                                         * INICIO RESTRUCTURACIÓN DE LA RESPUESTA
                                         */

                                        accumulateFields(name,value);
                                        //configureForms();

                                        /**
                                         * FIN RESTRUCTURACIÓN
                                         */

                                        String formName = "Form"+currentform;
                                        try{
                                            JSONArray form = tmp2.getJSONArray(formName);
                                            int finded = 0;
                                            for(int i = 0; i < form.length(); i++){
                                                try {
                                                    form.getJSONObject(i).getJSONArray(name).put(value);
                                                }catch (JSONException ex){
                                                    finded++;
                                                }
                                            }
                                            if(finded == form.length()) {
                                                form.put(new JSONObject().put(name, new JSONArray().put(value)));
                                            }
                                        }catch (JSONException ex){
                                            tmp2.put(formName,new JSONArray().put(new JSONObject().put(name,new JSONArray().put(value))));
                                        }
                                        //Log.e("Structure", tmp2.toString());
                                        //(+)
                                    }else{

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

                            //(+) agrega las respuestas agrupadas por formulario
                            AutoCompleteTextView currentField = (AutoCompleteTextView) o;
                            String name = currentField.getTag().toString();
                            String value = currentField.getText().toString();

                            /**
                             * INICIO RESTRUCTURACIÓN DE LA RESPUESTA
                             */

                            accumulateFields(name,value);
                            //configureForms();

                            /**
                             * FIN RESTRUCTURACIÓN
                             */

                            String formName = "Form"+currentform;
                            try{
                                JSONArray form = tmp2.getJSONArray(formName);
                                int finded = 0;
                                for(int i = 0; i <form.length(); i++){
                                    try {
                                        form.getJSONObject(i).getJSONArray(name).put(value);
                                    }catch (JSONException ex){
                                        finded++;
                                    }
                                }
                                if(finded == form.length()){
                                    form.put(new JSONObject().put(name, new JSONArray().put(value)));
                                }
                            }catch (JSONException ex){
                                tmp2.put(formName,new JSONArray().put(new JSONObject().put(name,new JSONArray().put(value))));
                            }
                            //Log.e("Structure", tmp2.toString());
                            //(+)
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
                                wrong += (validate((ViewGroup) o,currentform) ? 0 : 1);
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
            Log.e(getClass().getName(), je.getMessage());
            return false;
        }
    }

    /**
     * Obtiene la estructura con los datos de la encuesta llena en formato.
     * @return el JSONObjet con los datos de la estructura de la encuesta llena.
     */
    public JSONObject JSONGetter(){
        return tmpData;
    }
}