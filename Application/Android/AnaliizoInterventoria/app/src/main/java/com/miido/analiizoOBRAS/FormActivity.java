package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
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

import com.miido.analiizoOBRAS.mcompose.Constants;
import com.miido.analiizoOBRAS.model.Field;
import com.miido.analiizoOBRAS.model.Form;
import com.miido.analiizoOBRAS.model.FormLayout;
import com.miido.analiizoOBRAS.model.FormsLayouts;
import com.miido.analiizoOBRAS.model.Handler;
import com.miido.analiizoOBRAS.model.Poll;
import com.miido.analiizoOBRAS.model.QuestionLayout;
import com.miido.analiizoOBRAS.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import lecho.lib.hellocharts.model.Line;

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

    private Button backButton;
    private Button nextButton;

    private int navigateIndex = 0;

    private Poll currentPoll;

    public static final String CURRENT_POLL_EXTRA = "poll";//"current_poll";

    public static final int ITEM_SELECT_REQUEST_CODE = 1000;
    public static final int RESUME_REQUEST_CODE = 1001;

    public static final String FORM_SHARED_PREFERENCES = "com.miido.analiizo.v2";

    /**
     * Se ejecuta cuando la actividad es creada.
     * @param savedInstanceState contiene datos guardados que usualmente se guardan para recuperar el estado de la actividad
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.form_container_layout);

        currentPoll = getIntent().getParcelableExtra(CURRENT_POLL_EXTRA);

        toolbar = (Toolbar) findViewById(R.id.formsToolBar);
        toolbar.setTitle("Título encuesta");
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
                this.forms = new FormsLayouts(this, new JSONObject(new Constants().structure));
                //this.forms = new FormsLayouts(this, getPollStructure(currentPoll.getStructureId()));
            } catch (JSONException ex) {
                Log.e(getClass().getName(), ex.getMessage());
            }catch (SQLiteException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
        navigate(navigateIndex);
    }

    /**
     * Muestra un formulario ubicado en una posición especifica.
     * @param index posición o puntero del formualrio a mostrar.
     */
    private void showForm(int index){
        toolbar.setSubtitle((index + 1) + "/" + forms.length());
        if(formContainer.getChildCount() > 0){
            formContainer.removeAllViews();
            formContainer.addView(forms.getForm(index).getFormLayout());
            showDynamicForms();
        }else{
            try {
                //forms.getForm(index).setFormLayout(null);
                formContainer.removeAllViews();
                LinearLayout formLayout = forms.getForm(index).getFormLayout();
                if(formLayout.getParent() != null){
                    ((LinearLayout) formLayout.getParent()).removeView(formLayout);
                }
                //formContainer.addView(forms.getForm(index).getFormLayout());
                formContainer.addView(formLayout);
                showDynamicForms();
            }catch (IllegalStateException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
    }

    private void showDynamicForms(){
        try{
            ArrayList<FormLayout> dynamicForms = forms.getForm(navigateIndex).getDynamicForms();
            for(int i = 0; i < dynamicForms.size(); i++){
                LinearLayout formLayout = dynamicForms.get(i).getFormLayout();
                if(formLayout.getParent() != null){
                    ((LinearLayout) formLayout.getParent()).removeView(formLayout);
                }
                formContainer.addView(dynamicForms.get(i).getFormLayout());
            }
        }catch (IllegalStateException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
    }

    private void startResumeActivity(){
        Intent intent = new Intent(this, ResumeActivity.class);
        try{
            intent.putExtra(ResumeActivity.RESPONSE_EXTRA, forms.toJsonArray().toString());
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
        startActivityForResult(intent, RESUME_REQUEST_CODE);
    }

    private boolean validateForm(){
        boolean validated = true;
        validated &= validateForm(forms.getForm(navigateIndex));
        ArrayList<FormLayout> dynamicForms = forms.getForm(navigateIndex).getDynamicForms();;
        validated &= validateDynamicForms(dynamicForms);
        return validated;
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
                Handler questionHandler = question.getProperties().getHandlerEvent();
                if(questionHandler != null && questionHandler.compareTo(question.getProperties().getValue())){
                    if(question.getSubForm() != null) {
                        fieldValidated &= validateForm(question.getSubForm());// llamada recursiva para validar subformularios.
                    }
                }
            }
            validated &= fieldValidated;
        }
        // TODO: 10/06/2016 to debug
        if(navigateIndex == 4 ){
            //ArrayList<FormLayout> dynamicForms = getDynamicForms();
            //toString();
            //return validated;
        }
        //getSubForms();
        //addSubForms(form);
        /*try {
            Log.e("RESPONSE", form.toJsonObject().toString());
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }*/
        return validated;
    }

    private boolean validateDynamicForms(ArrayList<FormLayout> dynamicForms){
        boolean validated = true;
        for(int i = 0; i < dynamicForms.size(); i++){
            FormLayout dynamicForm = dynamicForms.get(i);
            boolean formValidated = validateDynamicForm(dynamicForm, false);
            if(!formValidated){
                validateDynamicForm(dynamicForm, true);
            }
            validated &= formValidated;
        }
        return validated;
    }

    private boolean validateDynamicForm(FormLayout dynamicForm, boolean showInvalidFields){
        boolean validated = true;
        for(int j = 0; j < dynamicForm.getQuestions().size(); j++){
            QuestionLayout questionLayout = dynamicForm.getQuestion(j);
            if(showInvalidFields && !questionLayout.getProperties().isRequired()){
                questionLayout.getProperties().setRequired(true);
                questionLayout.validate();
                questionLayout.getProperties().setRequired(false);
            }else {
                validated &= questionLayout.validate();
            }
        }
        return validated;
    }

    private ArrayList<FormLayout> getDynamicForms(){
        ArrayList<FormLayout> dynamicForms = new ArrayList<>();
        if(formContainer.getChildCount() > 1){
            for(int i = 1; i < formContainer.getChildCount(); i++){
                LinearLayout formLayout = (LinearLayout) formContainer.getChildAt(i);
                Form formProperties = (Form) formLayout.getTag();
                FormLayout form = new FormLayout(this, formProperties);
                int questionsContainerPosition = formLayout.getChildCount() == 2 ? 1 : 0;
                LinearLayout questionsContainer = (LinearLayout) formLayout.getChildAt(questionsContainerPosition);
                form.setQuestions(getFormQuestions(questionsContainer));
                dynamicForms.add(form);
            }
        }
        return dynamicForms;
    }

    private ArrayList<QuestionLayout> getFormQuestions(LinearLayout questionsContainer){
        ArrayList<QuestionLayout> questionLayouts = new ArrayList<>();
        for(int i = 0; i < questionsContainer.getChildCount(); i++){
            LinearLayout questionLayout = (LinearLayout) questionsContainer.getChildAt(i);
            Field fieldProperties = (Field) questionLayout.getTag();
            int questionViewPosition = questionLayout.getChildCount() == 3 ? 1 : 0;
            LinearLayout questionViewContainer =(LinearLayout) questionLayout.getChildAt(questionViewPosition);
            View questionView = questionViewContainer.getChildAt(0);
            //if(fieldProperties.getValue() == null){
                fieldProperties.setValue(getQuestionValue(questionView));
            //}
            QuestionLayout question = new QuestionLayout(this, fieldProperties);
            question.setView(questionView);
            if(questionLayout.getChildCount() == 3){
                TextView labelView = (TextView) questionLayout.getChildAt(0);
                question.setLabelView(labelView);
            }
            questionLayouts.add(question);
        }
        return questionLayouts;
    }

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
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.Alert(this,
                R.string.app_alert_dialog_title, R.string.form_fields_validate_error_message);
        d.show();
    }

    /**
     * Agrega el resultado de los subformularios dinámicos a a la pregunta que lo desencadena.
     * @param form formulario actual.
     */
    private void addSubForms(FormLayout form){
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
    }

    /**
     * Obtiene los subformualrios dinámicos desde el Layout contenedor del formulario.
     * @see #getSubForms(LinearLayout)
     */
    private void getSubForms(){
        subForms = new ArrayList<>();
        LinearLayout formLayout = (LinearLayout) this.formContainer.getChildAt(0);
        LinearLayout questionContainer = (LinearLayout) formLayout.findViewById(R.id.questionsContainer);
        if(questionContainer != null) {
            for (int i = 0; i < questionContainer.getChildCount(); i++) {
                LinearLayout questionLayout = (LinearLayout) questionContainer.getChildAt(i);
                LinearLayout subFormLayout = (LinearLayout) questionLayout.findViewById(R.id.subFormViewContainer);
                getSubForms(subFormLayout);// llamada recursiva para obtener formularios anidados.
            }
        }

    }

    /**
     * Se utiliza para navegar entre los formularios
     * @param index posición del formulario a mostrar
     * @see #showForm(int)
     */
    private void navigate(int index){
        showForm(index);
        // TODO: 10/06/2016 to debug
        if(index == 4){
            toString();
        }
        backButton.setEnabled(index> 0);
        nextButton.setEnabled(index < forms.length());
    }

    /**
     * Muestra el formulario anterior
     * @see #navigate(int)
     */
    private void navigateBack(){
        forms.getForm(navigateIndex).setDynamicForms(getDynamicForms());
        navigate(--navigateIndex);
    }

    /**
     * Muestra el formulario siguiente
     * @see #navigate(int)
     */
    private void navigateFront(){
        forms.getForm(navigateIndex).setDynamicForms(getDynamicForms());
        //if(validateForm(forms.getForm(navigateIndex))){
        if(validateForm()){
            if(navigateIndex == forms.length() - 1){
                startResumeActivity();
            }else {
                navigate(++navigateIndex);
            }
        }else{
            launchUnValidateFormDialog();
        }
    }

    /**
     * Obtiene desde la base de datos local del dispositivo el objeto JSon que representa la estructura de la encuesta.
     * @param structureId identificador unico de la encuesta.
     * @return objeto Json con la estructura de la encuesta.
     * @throws JSONException es lanzada si ocurre algun error al obtener llaves del JSONObject
     * @throws SQLiteException es lanzada si ocurre algun error al ejecutar la consulta sql.
     */
    private JSONObject getPollStructure(long structureId) throws JSONException,SQLiteException {
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.SELECT_STRUCTURE_DATA_QUERY);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        JSONArray polls = new JSONArray(cursor.getString(0));
        for(int i=0; i<polls.length(); i++){
            long id = polls.getJSONObject(i).getJSONObject("Document_info").getLong("structureid");
            if(structureId == id){
                return polls.getJSONObject(i);
            }
        }
        return new JSONObject("{}");
    }

    /**
     * Menejador del evento OnClick de una vista
     * @param view vista que lanza el evento
     */
    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.backButton:
                navigateBack();
                break;
            case R.id.nextButton:
                navigateFront();
                break;
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
            case R.id.action_pause:this.onBackPressed();break;
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
                    // TODO: 08/06/2016 implementación de salida de la encuesta.
                }
            }
        }
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

    /**
     * se ejecuta cuando se preciona el botón atras del dispositivo.
     */
    @Override
    public void onBackPressed() {
        if(navigateIndex > 0){
            navigateBack();
        }
    }

    /**
     * Obtiene los subformularios dinámicos desde el Layout contenedor del formulario visible.
     * @param formLayout contenedor del formulario visible.
     */
    private void getSubForms(LinearLayout formLayout){
        for(int j = 0; j < formLayout.getChildCount(); j++) {
            Form formProperties = (Form) formLayout.getChildAt(j).getTag();
            LinearLayout questionContainer = (LinearLayout) formLayout.getChildAt(j).findViewById(R.id.questionsContainer);
            if (questionContainer != null) {
                for (int i = 0; i < questionContainer.getChildCount(); i++) {
                    LinearLayout questionLayout = (LinearLayout) questionContainer.getChildAt(i);
                    Field fieldProperties = (Field) questionLayout.getTag();
                    if(fieldProperties.getDynamicJoiner() != null && fieldProperties.getValue() == null) {
                        LinearLayout formParent = (LinearLayout) questionLayout.getParent();
                        FormLayout form = new FormLayout(this,formProperties);
                        for(int l = 0; l < formParent.getChildCount(); l++){
                            LinearLayout ql = (LinearLayout) formParent.getChildAt(l);
                            QuestionLayout question = new QuestionLayout(this, (Field) ql.getTag());
                            LinearLayout viewContainer = (LinearLayout) ql.findViewById(R.id.questionViewContainer);
                            View view = viewContainer.getChildAt(0);
                            if (view instanceof EditText) {
                                String value = ((EditText) view).getText().toString();
                                question.getProperties().setValue(value.equals("") ? null : value);
                            } else {
                                if (view instanceof CheckBox) {
                                    question.getProperties().setValue(((CheckBox) view).isChecked()
                                            ? QuestionLayout.CbResponse.ON.code() : QuestionLayout.CbResponse.OFF.code());
                                }else{
                                    if(view instanceof Spinner){
                                        String value = ((Spinner)view).getSelectedItem().toString();
                                        question.getProperties().setValue(((Spinner) view).getSelectedItemPosition() == 0 ? null : value);
                                    }
                                }
                            }
                            form.addQuestion(question);
                        }
                        subForms.add(form);
                    }

                    LinearLayout subFormLayout = (LinearLayout) questionLayout.findViewById(R.id.subFormViewContainer);
                    getSubForms(subFormLayout);// Llamada recursiva para obtener formularios anidados.

                }
            }
        }
    }

}
