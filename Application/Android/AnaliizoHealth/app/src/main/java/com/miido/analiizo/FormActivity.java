package com.miido.analiizo;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.DynamicJoiner;
import com.miido.analiizo.model.Field;
import com.miido.analiizo.model.Form;
import com.miido.analiizo.model.FormLayout;
import com.miido.analiizo.model.FormsLayouts;
import com.miido.analiizo.model.Handler;
import com.miido.analiizo.model.HandlerFieldJoiner;
import com.miido.analiizo.model.Poll;
import com.miido.analiizo.model.QuestionLayout;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;

/**
 * Actividad donde se muestran los formularios de la encuesta.
 * @author Ing. Miguel Angel Urango Blanco. Miido S.A.S 19/05/2016.
 * @see android.view.View.OnClickListener
 */

public class FormActivity extends ActionBarActivity implements View.OnClickListener{

    private Toolbar toolbar;

    private LinearLayout formContainer;
    private FormsLayouts forms;

    private ArrayList<FormLayout> subForms = new ArrayList<>();
    private ArrayList<DynamicJoiner> dynamicJoiners = new ArrayList<>();

    private Button backButton;
    private Button nextButton;

    private ProgressDialog progressDialog;

    private int navigateIndex = 0;

    private Poll currentPoll;
    private int pollType;
    private int homeId;

    private String ageValue = null;
    private String genderValue = null;

    private JSONObject group = new JSONObject();
    private JSONObject person = new JSONObject();
    private JSONArray diseases = new JSONArray();
    private JSONObject home = new JSONObject();

    public static final String CURRENT_POLL_EXTRA = "poll";//"current_poll";
    public static final String POLL_TYPE_EXTRA = "poll_type";
    public static final String HOME_ID_EXTRA = "home_id";
    public static final String PERSON_ID_EXTRA = "person_id";
    public static final String PERSON_CONTENT_EXTRA = "person_content";

    public static final int ITEM_SELECT_REQUEST_CODE = 1000;
    public static final int RESUME_REQUEST_CODE = 1001;
    public static  final int DATE_PICKER_REQUEST_CODE = 1002;

    /**
     * Se ejecuta cuando la actividad es creada.
     * @param savedInstanceState contiene datos guardados que usualmente se guardan para recuperar el estado de la actividad
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.form_container_layout);

        currentPoll = getIntent().getParcelableExtra(CURRENT_POLL_EXTRA);
        pollType = getIntent().getIntExtra(POLL_TYPE_EXTRA, 1);
        homeId = getIntent().getIntExtra(HOME_ID_EXTRA, 0);

        toolbar = (Toolbar) findViewById(R.id.formsToolBar);
        toolbar.setTitle(currentPoll != null ? currentPoll.getTitle() : "Título de la encuesta");
        setSupportActionBar(toolbar);
        toolbar.setLogo(R.drawable.ic_action_report);

        this.forms = new FormsLayouts();

        this.formContainer = (LinearLayout) findViewById(R.id.formContainer);

        this.backButton = (Button) findViewById(R.id.backButton);
        this.backButton.setOnClickListener(this);
        this.backButton.setEnabled(false);
        this.nextButton = (Button) findViewById(R.id.nextButton);
        this.nextButton.setOnClickListener(this);


        if(savedInstanceState != null){
            navigateIndex = savedInstanceState.getInt("INDEX");
            forms = savedInstanceState.getParcelable("FORMS");
            //forms.setContext(this);
        }else {
            try {
                this.forms = new FormsLayouts(this, currentPoll.getStructureId(), pollType);
            } catch (Exception ex) {
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
        navigate(navigateIndex);
    }

    private FormLayout getCurrentForm(){
        return forms.getForm(navigateIndex);
    }

    /**
     * Muestra un dialogo de progreso indeterminado indicarle a el usuario que se la aplicación esta trabajando
     * @see ProgressDialog
     */
    private void launchProgressDialog(){
        progressDialog = ProgressDialog.show(FormActivity.this,
                getString(R.string.app_progress_dialog_title),
                getString(R.string.form_progress_dialog_validating_data_message),true);
    }

    /**
     *
     * @param buttonId
     */
    private void navigateProcess(int buttonId){
        class NavigateProcess extends AsyncTask<Integer,Integer,Integer>{

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                launchProgressDialog();
            }

            @Override
            protected Integer doInBackground(Integer... buttonId) {
                publishProgress(buttonId);
                return 0;
            }

            @Override
            protected void onProgressUpdate(Integer... values) {
                super.onProgressUpdate(values);
                if(values[0] == R.id.backButton){
                    navigateBack();
                }else{
                    if(values[0] == R.id.nextButton){
                        navigateFront();
                    }
                }
            }

            @Override
            protected void onPostExecute(Integer result) {
                super.onPostExecute(result);
                progressDialog.dismiss();
            }
        }

        new NavigateProcess().execute(buttonId);
    }

    /**
     * Muestra un formulario ubicado en una posición especifica.
     * @param index posición o puntero del formualrio a mostrar.
     */
    private void showForm(int index){
        FormLayout currentForm = forms.getForm(index);
        //toolbar.setSubtitle((index + 1) + "/" + forms.length());
        toolbar.setSubtitle("Formulario: " + (index + 1) + " Preguntas: " + currentForm.questionsLength());
        if(currentForm.getProperties().getInside() == 1){
            Toast.makeText(this, "Form " + currentForm.getProperties().getId() + " No debería mostrarse", Toast.LENGTH_LONG).show();
        }
        if(formContainer.getChildCount() > 0){
            formContainer.removeAllViews();
            formContainer.addView(currentForm.getFormLayout());
        }else{
            try {
                formContainer.removeAllViews();
                LinearLayout formLayout = currentForm.getFormLayout();
                if(formLayout.getParent() != null){
                    ((LinearLayout) formLayout.getParent()).removeView(formLayout);
                }
                formContainer.addView(formLayout);
            }catch (IllegalStateException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }

        try{
            fillForm(index);
        }catch (Exception ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
    }

    private JSONArray getContentFromPreferencesFile()throws JSONException{
        SharedPreferences preferences = getSharedPreferences(getPackageName(),MODE_PRIVATE);
        JSONArray content = null;
        String preferenceName = pollType + "." + currentPoll.getStructureId() + ".Content";
        if(preferences.contains(preferenceName)){
            String strContent = preferences.getString(preferenceName, null);
            if(strContent != null) {
                content = new JSONArray(strContent);
            }
        }
        return content;
    }

    private boolean saveContentToPreferencesFile(String content)throws JSONException{
        SharedPreferences preferences = getSharedPreferences(getPackageName(),MODE_PRIVATE);
        String preferenceName = pollType + "." + currentPoll.getStructureId() + ".Content";
        SharedPreferences.Editor editor = preferences.edit();
        if(preferences.contains(preferenceName)){
            String strContent = preferences.getString(preferenceName, null);
            if(strContent != null) {
                JSONArray jsonArray = new JSONArray(strContent);
                jsonArray.put(new JSONObject(content));
                editor.putString(preferenceName, jsonArray.toString());
                return editor.commit();
            }else{
                editor.putString(preferenceName, new JSONArray().put(new JSONObject(content)).toString());
                return editor.commit();
            }
        }else{
            editor.putString(preferenceName, new JSONArray().put(new JSONObject(content)).toString());
            return editor.commit();
        }
    }

    private JSONObject getFormData(JSONArray forms, int formId)throws JSONException{
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getInt(Form.JForm.ID.code()) == formId){
                return form;
            }
        }
        return null;
    }

    private void fillForm(int index) throws JSONException,SQLiteException{
        FormLayout currentForm = forms.getForm(index);
        //fillFormTmpData(index);
        if(!currentForm.isFilled()) {
            if (pollType == FormsLayouts.PollType.PERSON.code() && getIntent().getStringExtra(PERSON_CONTENT_EXTRA) == null) {
                int personId = getIntent().getIntExtra(PERSON_ID_EXTRA, 0);
                if(personId > 0) {
                    //JSONObject formData = getPersonDataFromDB(personId).getJSONObject(index);
                    JSONObject formData = getFormData(getPersonDataFromDB(personId), currentForm.getProperties().getId());
                    if(formData != null) {
                        fillForm(currentForm, formData);
                        forms.getForm(index).setFilled(true);
                    }
                }
            }else{
                if(pollType == FormsLayouts.PollType.SATISFACTION.code()){
                    if(homeId > 0) {
                        //JSONObject satisfactionData = getSatisfactionDataFromDB(homeId).getJSONObject(index);
                        JSONObject satisfactionData = getFormData(getSatisfactionDataFromDB(homeId),currentForm.getProperties().getId());
                        if(satisfactionData != null) {
                            fillForm(currentForm, satisfactionData);
                            forms.getForm(index).setFilled(true);
                        }
                    }
                }else{
                    if(getIntent().getStringExtra(PERSON_CONTENT_EXTRA) != null && currentForm.getProperties().getId() == 2){
                        JSONObject jPerson = new JSONObject(getIntent().getStringExtra(PERSON_CONTENT_EXTRA));
                        fillForm(currentForm, jPerson);
                        getIntent().removeExtra(PERSON_CONTENT_EXTRA);
                    }
                }
            }
        }
    }

    private void fillFormTmpData(int index)throws JSONException{
        JSONArray formContent = getContentFromPreferencesFile();
        if(formContent != null && formContent.length() > 0) {
            FormLayout currentForm = forms.getForm(index);
            fillForm(currentForm, formContent.getJSONObject(index));
        }
    }

    private void fillForm(FormLayout form, JSONObject formData)throws JSONException{
        if(form.getProperties().getId() == formData.getInt(Form.JForm.ID.code())) {
            JSONArray jQuestions = formData.getJSONArray(Form.JForm.FIELDS.code());
            for (int i = 0; i < jQuestions.length(); i++) {
                JSONObject jQuestion = jQuestions.getJSONObject(i);
                QuestionLayout questionLayout = form.getQuestion(i);
                if (jQuestion.has(Field.JField.NAME.code()) && questionLayout.getProperties().getName().equals(jQuestion.getString(Field.JField.NAME.code()))) {
                    if (jQuestion.has(Field.JField.VALUE.code())) {
                        String value = jQuestion.getString(Field.JField.VALUE.code());
                        questionLayout.getProperties().setValue(value);
                        questionLayout.fill();
                        if (jQuestion.has(Field.JField.SUBFORM.code())) {
                            JSONArray jSubForms = jQuestion.getJSONArray(Field.JField.SUBFORM.code());
                            for (int j = 0; j < jSubForms.length(); j++) {
                                //if(questionLayout.getProperties().getHandlerEvent().compareTo(value)) {
                                //  questionLayout.showSubForm(subForm.getFormLayout());
                                fillForm(questionLayout.getSubForms().get(j), jSubForms.getJSONObject(j));
                                //}
                            }
                        }
                    }
                }
            }
        }
    }

    private JSONObject getHomeDataFromBD(int homeId)throws SQLiteException, JSONException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(String.format(constants.SELECT_HOME_INFO_BY_ID_QUERY, homeId,0));
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        JSONObject personData = null;
        if(cursor.getCount() > 0){
            String data = cursor.getString(3);
            personData = new JSONObject(data);
        }
        cursor.close();
        return personData;
    }

    private JSONArray getPersonDataFromDB(long personId)throws SQLiteException, JSONException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(String.format(constants.SELECT_PERSON_INFO_BY_ID_QUERY, personId));
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        JSONArray personData = null;
        if(cursor.getCount() > 0){
            String data = cursor.getString(3);
            personData = new JSONArray(data);
        }
        cursor.close();
        return personData;
    }

    private JSONArray getSatisfactionDataFromDB(int homeId)throws SQLiteException, JSONException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(String.format(constants.SELECT_SATISFACTION_INFO_BY_ID_QUERY, homeId));
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        JSONArray personData = null;
        if(cursor.getCount() > 0){
            String data = cursor.getString(3);
            personData = new JSONArray(data);
        }
        cursor.close();
        return personData;
    }

    private void startResumeActivity(){
        Intent intent = new Intent(this, ResumeActivity.class);
        try{
            intent.putExtra(ResumeActivity.RESPONSE_EXTRA, forms.toJsonArray().toString());
            intent.putExtra(ResumeActivity.POLL_TYPE_EXTRA, pollType);
            intent.putExtra(ResumeActivity.CURRENT_POLL_EXTRA, currentPoll);
            intent.putExtra(ResumeActivity.EDIT_MODE_EXTRA, forms.getForm(0).isFilled());
            if(pollType == FormsLayouts.PollType.PERSON.code()){
                intent.putExtra(ResumeActivity.PERSON_ID_EXTRA,getIntent().getIntExtra(PERSON_ID_EXTRA, 0));
            }
            if(homeId != 0){
                intent.putExtra(ResumeActivity.HOME_ID_EXTRA, homeId);
            }
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
        startActivityForResult(intent, RESUME_REQUEST_CODE);
    }

    /**
     * Valida el formulario visible.
     * @return true si los datos se validaron con exito, false en caso contrario.
     */
    private boolean validateForm(){
        boolean validated = true;
        validated &= validateForm(forms.getForm(navigateIndex));
        //ArrayList<FormLayout> dynamicForms = forms.getForm(navigateIndex).getDynamicForms();;
        //validated &= validateDynamicForms(dynamicForms);
        return validated;
    }

    private boolean validateTas(int tasValue){
        return tasValue > 79 && tasValue < 181;
    }

    private boolean validateTad(int tadValue){
        return tadValue > 39 && tadValue < 121;
    }

    private boolean validatePerAbd(float value){
        return value > 49 && value < 161;
    }

    private boolean validateWeight(int age, float peso){
        return ((age == 0 && peso >= 2.0 && peso <= 13.1) ||
                (age == 1 && peso >= 6.3 && peso <= 16.7) ||
                (age == 2 && peso >= 8.1 && peso <= 20.6) ||
                (age == 3 && peso >= 9.6 && peso <= 24.8) ||
                (age == 4 && peso >= 10.9 && peso <= 29.2) ||
                (age == 5 && peso >= 12.1 && peso <= 29.5) ||
                (age > 5 && age <= 15 && peso > 29.5 && peso < 54.12) ||
                (age > 15 && peso > 54.12 && peso <= 300)) ;
    }

    private boolean validateHeight(int age, float height){
        return ((age == 0 && height >= 44.2 && height <= 81.5) ||
                (age == 1 && height >= 68.6 && height <= 95.9) ||
                (age == 2 && height >= 78.7 && height <= 106.4) ||
                (age == 3 && height >= 85 && height <= 115.2) ||
                (age == 4 && height >= 90.7 && height <= 123.2) ||
                (age == 5 && height >= 96.1 && height <= 123.9) ||
                (age > 5 && age <= 15 && height > 123.9 && height <= 167.1) ||
                (age > 15 && height > 167.1 && height <= 250));
    }

    private boolean validateOximetry(int oximetryValue){
        return oximetryValue > 79 && oximetryValue < 100;
    }

    /**
     * Valida los elementos del formulario actual
     * @param form formulario a validar
     * @return true si los campos estan validados, false en caso contrario.
     */
    private boolean validateForm(FormLayout form){
        boolean validated = true;
        for(int i = 0; i < form.questionsLength(); i++){
            QuestionLayout question = form.getQuestion(i);
            boolean fieldValidated = question.validate();
            if(fieldValidated){
                if(question.getProperties().getName().equals("peso") || question.getProperties().getName().equals("pesoNacer")){
                    int age = Integer.parseInt(ageValue);
                    float weight = Float.parseFloat(question.getProperties().getValue());
                    if(question.getProperties().getName().equals("pesoNacer")){
                        weight = weight / (float) 1000;
                    }
                    fieldValidated = validateWeight(age, weight);
                    if(!fieldValidated) {
                        //Toast.makeText(this, "Peso inválido", Toast.LENGTH_LONG).show();
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor del peso no está dentro del rango establecido para una persona de género " +
                                        genderValue + " y " + age + " años de edad").show();
                    }
                }
                if(question.getProperties().getName().equals("talla") || question.getProperties().getName().equals("tallaNacer")){
                    int age = Integer.parseInt(ageValue);
                    float height = Float.parseFloat(question.getProperties().getValue());
                    fieldValidated = validateHeight(age, height);
                    if(!fieldValidated) {
                        //Toast.makeText(this, "Talla inválida", Toast.LENGTH_LONG).show();
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor de la talla no está dentro del rango establecido para una persona de género " +
                                        genderValue + " y " + age + " años de edad").show();
                    }
                }
                String value = question.getProperties().getValue();
                if(question.getProperties().getName().equals("Oximetria")){
                    int oximetryValue = Integer.parseInt(value);
                    fieldValidated = validateOximetry(oximetryValue);
                    if(!fieldValidated){
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor del la oximetría no está dentro del rango establecido").show();
                    }
                }
                if(question.getProperties().getName().equals("TAS")){
                    int tasValue = Integer.parseInt(value);
                    fieldValidated = validateTas(tasValue);
                    if(!fieldValidated){
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor de la tensión arterial sistólica no está dentro del rango establecido").show();
                    }
                }
                if(question.getProperties().getName().equals("TAD")){
                    int tadValue = Integer.parseInt(value);
                    fieldValidated = validateTad(tadValue);
                    if(!fieldValidated){
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor de la tensión arterial diastólica no está dentro del rango establecido").show();
                    }
                }
                if(question.getProperties().getName().equals("PerAbd")){
                    float perAbdValue = Float.parseFloat(value);
                    fieldValidated = validatePerAbd(perAbdValue);
                    if(!fieldValidated){
                        com.miido.analiizo.util.Dialog.Alert(this,getString(R.string.app_alert_dialog_title),
                                "El valor del perímetro abdominal no está dentro del rango establecido para una persona de género " +
                                        genderValue + " y " + ageValue + " años de edad").show();
                    }
                }
            }
            if(fieldValidated){
                Handler questionHandler = question.getProperties().getHandlerEvent();
                if(questionHandler != null && questionHandler.compareTo(question.getProperties().getValue())){
                    for(FormLayout subForm : question.getSubForms()){
                        fieldValidated &= validateForm(subForm);
                    }
                }
            }
            validated &= fieldValidated;
        }

        if(validated) {
            enableInvisibleForms();
        }
        return validated;
    }

    /**
     * Busca un formulario dentro de la estructura de formularios visibles.
     * @param idForm identificador del formulario a buscar.
     * @return la posición del formulario encontrado, -1 si el formulario buscado no se encuntra dentro de la estructura.
     */
    private int findFrontForm(int idForm){
        for(int i = 0; i < forms.length(); i++){
            FormLayout form = forms.getForm(i);
            if(form.getProperties().getId() == idForm){
                return i;
            }
        }
        return -1;
    }

    /**
     * habilita formularios ocultos sengún el cumplimiento de preguntas repondidas.
     */
    private void enableInvisibleForms(){
        subForms = new ArrayList<>();
        ArrayList<HandlerFieldJoiner> handlersFieldJoiner = forms.getHandlersFieldJoiner(navigateIndex);
        for(int i = 0; i < handlersFieldJoiner.size(); i++){
            HandlerFieldJoiner handlerFieldJoiner = handlersFieldJoiner.get(i);
            boolean isValid = true;
            for(int j = 0; j < handlerFieldJoiner.getFieldsIds().size(); j++){
                int fieldId = handlerFieldJoiner.getFieldsIds().get(j);
                LinearLayout formLayout = (LinearLayout) formContainer.getChildAt(0);
                View view = findView(formLayout, fieldId);
                if(view != null){
                    String value = getQuestionValue(view);
                    if(fieldId == 13){
                        ageValue = value;
                    }
                    if(fieldId == 15){
                        genderValue = value;
                    }
                    isValid &= handlerFieldJoiner.getHandler(fieldId).compareTo(value);
                }else{
                    Log.e("NUll", fieldId + "");
                }
            }
            int pos = findFrontForm(handlerFieldJoiner.getTargetForm());
            if (pos != -1) {
                int inside = isValid ? 0 : 1;
                forms.getForm(pos).getProperties().setInside(inside);
            } else {
                if (isValid) {
                    ArrayList<FormLayout> subForms = new ArrayList<>();
                    FormLayout form = handlerFieldJoiner.getForm();
                    form.getProperties().setInside(0);
                    subForms.add(form);
                    int genderPos = findFrontForm(7);
                    pos = navigateIndex < genderPos ? genderPos + 1 : navigateIndex + 1;
                    ArrayList<FormLayout> subForms2 = arrayCopy(forms.getForms(), pos, subForms);
                    forms.setForms(subForms2);
                }
            }
        }
    }

    /**
     * Agrega un formulario oculto en la siguiente posición para que sea visible posteriormente.
     * @param dest Arreglo destino
     * @param desPos posición del arreglo destino donde se agragará el formulario.
     * @param src formularios que se agregaran.
     * @return El Arreglo con los nuevos formularios agragados.
     */
    private ArrayList<FormLayout> arrayCopy(ArrayList<FormLayout> dest,int desPos, ArrayList<FormLayout> src){
        ArrayList<FormLayout> tmp1 = new ArrayList<>();
        for(int i = 0; i < desPos; i++){
            tmp1.add(dest.get(i));
        }
        ArrayList<FormLayout> tmp2 = new ArrayList<>();
        for(int i = desPos; i < dest.size(); i++){
            tmp2.add(dest.get(i));
        }
        dest = new ArrayList<>();
        for(int i = 0; i < tmp1.size(); i++){
            dest.add(tmp1.get(i));
        }
        for(int i = 0; i < src.size(); i++){
            dest.add(src.get(i));
        }
        for(int i = 0; i < tmp2.size(); i++){
            dest.add(tmp2.get(i));
        }
        return dest;
    }

    /**
     * Obtiene el valor de la respuesta de una pregunta
     * @param questionView vista de la pregunta a la cual se le obtentrá el valor.
     * @return valor de la respuesta.
     */
    private String getQuestionValue(View questionView){
        if(questionView instanceof EditText){
            EditText editText = (EditText) questionView;
            return editText.getText().equals("") ? null : editText.getText().toString();
        }else{
            if(questionView instanceof RadioGroup){
                RadioGroup radioGroup = (RadioGroup) questionView;
                if(radioGroup.getCheckedRadioButtonId() > -1 ){
                    for(int r = 0; r < radioGroup.getChildCount(); r++){
                        RadioButton radioButton = (RadioButton) radioGroup.getChildAt(r);
                        if(radioButton.isChecked()){
                            return radioButton.getText().toString();
                        }
                    }
                }
            }else{
                if(questionView instanceof CheckBox){
                    CheckBox checkBox = (CheckBox) questionView;
                    return checkBox.isChecked() ? QuestionLayout.CbResponse.ON.code() : QuestionLayout.CbResponse.OFF.code();
                }else{
                    if(questionView instanceof Spinner){
                        Spinner spinner = (Spinner) questionView;
                        return spinner.getSelectedItemPosition() == 0 ? null : spinner.getSelectedItem().toString();
                    }
                }
            }
        }
        return null;
    }

    /**
     * Lanza un dialogo de alerta para informar que faltan datos por llenar.
     */
    private void launchUnValidateFormDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,
                R.string.app_alert_dialog_title, R.string.form_fields_validate_error_message);
        d.show();
    }

    /**
     * Agrega el resultado de los subformularios dinámicos a a la pregunta que lo desencadena.
     * @param form formulario actual.
     */
    /*private void addSubForms(FormLayout form){
        for (int i = 0; i < form.questionsLength(); i++) {
            QuestionLayout question = form.getQuestion(i);
            if (question.getSubForm() == null && question.getProperties().getDynamicJoiner() != null) {
                if (!subForms.isEmpty()) {
                    question.setSubForm(subForms.get(0));
                    subForms.remove(0);
                    addSubForms(question.getSubForm());// llamada recursiva para agregar subformularios a preguntas anidadas.
                }
            }
        }
    }*/

    /**
     * Se utiliza para navegar entre los formularios
     * @param index posición del formulario a mostrar
     * @see #showForm(int)
     */
    private void navigate(int index){
        showForm(index);
        backButton.setEnabled(index > 0);
        nextButton.setEnabled(index < forms.length());
    }

    /**
     * Muestra el formulario anterior
     * @see #navigate(int)
     */
    private void navigateBack(){
        if(navigateIndex > 1){
            FormLayout form = forms.getForm(navigateIndex - 1);
            if(form.getProperties().getInside() == 1){
                navigateIndex--;
                navigateBack();
            }else{
                navigate(--navigateIndex);
            }
        }else {
            navigate(--navigateIndex);
        }
    }

    /**
     * Muestra el formulario siguiente
     * @see #navigate(int)
     */
    private void navigateFront(){
        if(validateForm()){
            if(navigateIndex == forms.length() - 1){
                startResumeActivity();
            }else {
                FormLayout form = forms.getForm(navigateIndex + 1);
                // TODO: 24/07/2016  Validación formularios de papiloma y relaciones sexuales.
                if(form.getProperties().getId() == 23 || form.getProperties().getId() == 34) {
                    if (genderValue != null && genderValue.toUpperCase().equals("MASCULINO")) {
                        form.getProperties().setInside(1);
                    }
                }
                if(form.getProperties().getInside() == 1) {
                    navigateIndex++;
                    navigateFront();
                }else {
                    navigate(++navigateIndex);
                }
            }
        }else{
            launchUnValidateFormDialog();
        }
    }

    /**
     * Menejador del evento OnClick de una vista
     * @param view vista que lanza el evento
     */
    @Override
    public void onClick(View view) {
        if(view.getId() == R.id.backButton || view.getId() == R.id.nextButton){
            navigateProcess(view.getId());
        }
    }

    /**
     * Se ejecuta cuando el menú de la actividad es creado.
     * @param menu
     * @return
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu_new, menu);
        return true;
    }

    /**
     * responde al evento de selección de un menú
     * @param item menú que es seleccionado
     * @return true si el menu ha sido seleccionado, false en caso contrario.
     */
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch (id){
            case R.id.action_pause:/*this.onBackPressed()*/;break;
        }
        return true;
    }

    /**
     * maneja la respuesta de una actividad lanzada con startActivityForResult
     * @param requestCode código del intent lanzado
     * @param resultCode código de respuesta de la actividad RESULT_OK o RESULT_CANCELED
     * @param data datos proporcionados por la actividad lanzada.
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(resultCode == RESULT_OK){
            if(requestCode == ITEM_SELECT_REQUEST_CODE){
                String item = data.getStringExtra(ItemSelectActivity.RESULT_EXTRA);
                AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getCurrentFocus();
                autoCompleteTextView.setText(item);
            }else{
                if(requestCode == RESUME_REQUEST_CODE){
                    setResult(RESULT_OK);
                    finish();
                }else{
                    if(requestCode == DATE_PICKER_REQUEST_CODE){
                        TextView dateTextView = (TextView) getCurrentFocus();
                        dateTextView.setText(data.getStringExtra(DatePickerActivity.DATE_EXTRA));
                        LinearLayout questionLayout = (LinearLayout) dateTextView.getParent().getParent();
                        Field properties = (Field) questionLayout.getTag();
                        if(properties.getFieldJoiner() != null) {
                            LinearLayout formLayout = (LinearLayout) formContainer.getChildAt(0);
                            View view = findView(formLayout, properties.getFieldJoiner().getInTo());
                            if(view instanceof EditText){
                                String value = fieldJoiner(properties.getFieldJoiner().getMethod(), dateTextView.getText().toString());
                                ((EditText) view).setText(value);
                            }
                        }
                        toString();
                    }
                }
            }
        }
    }

    /**
     * Aplica un valor a una pregunta derivada de la respuesta de otra.
     * @param method metodo que se ejecuta.
     * @param value valor de la pregunta que derivará la respuesta.
     * @return valor de la pregunta ya derivada.
     */
    private String fieldJoiner(String method, String value){
        switch (method){
            case "Y":
                SimpleDateFormat dateFormat = new SimpleDateFormat(new Constants().DATE_FORMAT);
                Calendar calendar = Calendar.getInstance();
                int cy = calendar.get(Calendar.YEAR),cm = calendar.get(Calendar.MONTH),cd = calendar.get(Calendar.DAY_OF_MONTH);
                try {
                    calendar.setTime(dateFormat.parse(value));
                    int by = calendar.get(Calendar.YEAR),bm = calendar.get(Calendar.MONTH),bd = calendar.get(Calendar.DAY_OF_MONTH);
                    int yearsOld = cy - by - (cm <= bm && cd < bd ? 1 : 0);
                    return String.valueOf(yearsOld);
                }catch (ParseException ex){
                    Log.e(getClass().getName(), ex.getMessage());
                }
                break;
        }
        return null;
    }

    /**
     * Busca una vista dentro de un formulario
     * @param formLayout formulario donde se va a buscar una vista
     * @param fieldId identificador unico de la vista
     * @return las vista encontrada.
     */
    private View findView(LinearLayout formLayout, int fieldId){
        LinearLayout questionsContainer = (LinearLayout) formLayout.getChildAt(formLayout.getChildCount() == 2 ? 1 : 0);
        for (int j = 0; j < questionsContainer.getChildCount(); j++) {
            LinearLayout questionLayout = (LinearLayout) questionsContainer.getChildAt(j);
            Field properties = (Field) questionLayout.getTag();
            if (fieldId == properties.getId()) {
                LinearLayout viewContainer = (LinearLayout) questionLayout.getChildAt(questionLayout.getChildCount() == 3 ? 1 : 0);
                return viewContainer.getChildAt(0);
            }
            LinearLayout subFormContainer = (LinearLayout) questionLayout.getChildAt(questionLayout.getChildCount() == 3 ? 2 : 1);
            if(subFormContainer.getChildCount() > 0){
                LinearLayout subFormLayout = (LinearLayout) subFormContainer.getChildAt(0);
                return findView(subFormLayout, fieldId);
            }
        }
        return null;
    }

    /**
     * Guarda variables o el estado de la actividad para su proxima reconstrucción
     * @param outState gurada los datos de la actividad.
     */
    @Override
    protected void onSaveInstanceState(Bundle outState) {
        outState.putInt("INDEX", navigateIndex);
        //forms.getForm(navigateIndex).setDynamicForms(getDynamicForms());
        outState.putParcelable("FORMS", forms);
        super.onSaveInstanceState(outState);
    }

    private void launchConfirmExitDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this,
                getString(R.string.app_confirm_dialog_title), "¿Desea salir de la encuesta?");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                finish();
            }
        });
        d.show();
    }

    /**
     * se ejecuta cuando se preciona el botón atras del dispositivo.
     */
    @Override
    public void onBackPressed() {
        if(navigateIndex > 0){
            navigateBack();
        }else {
            launchConfirmExitDialog();
        }
    }

}
