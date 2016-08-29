package com.miido.analiizoOBRAS.model;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Parcel;
import android.os.Parcelable;
import android.text.InputFilter;
import android.text.InputType;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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
import android.widget.TextView;

import com.miido.analiizoOBRAS.FormActivity;
import com.miido.analiizoOBRAS.ItemSelectActivity;
import com.miido.analiizoOBRAS.R;
import com.miido.analiizoOBRAS.mcompose.Constants;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;

/**
 * Configura un campo de acuerdo a su tipo y configura el Layout contenedor de este.
 * @author Ing. Miguel Angel Unrango Blanco Miido S.A.S 06/05/2016.
 * @see Parcelable
 * @see android.widget.CompoundButton.OnCheckedChangeListener
 * @see android.widget.RadioGroup.OnCheckedChangeListener
 * @see android.view.View.OnFocusChangeListener
 * @see android.view.View.OnClickListener
 */

public class QuestionLayout implements Parcelable,RadioGroup.OnCheckedChangeListener,CompoundButton.OnCheckedChangeListener,View.OnClickListener,View.OnFocusChangeListener{

    private Activity context;// Actividad donde se generan las vistas.
    private Field properties;// propiedades extraidas de la extructura JSON.
    private LinearLayout questionLayout; //LinearLAyout Contenedor de la pregunta.
    private TextView labelView; // TextView enunciado de la pregunta
    private View questionView;// View vista de la pregunta (TextFiel,Autocomplete, etc).
    private FormLayout subForm; // FormLayout que representa un subformulario que se muestra según la respuesta de a pregunta.

    /**************
     * Constantes *
     **************/

    /**
     * Tipos de componentes permitidos.
     */
    public enum Types{
        TEXT_FIELD("tf"),DATE_PICKER("dp"),CHECK_BOX("cb"),RADIO_GROUP("rg"),
        AUTO_COMPLETE("ac"),TEXT_VIEW("tv"),SPINNER("sp");
        private final String typeCode;
        Types(String typeCode){
            this.typeCode = typeCode;
        }
        public String code(){
            return typeCode;
        }
    }

    /**
     * Reglas de campos de texto permitidas.
     */
    public enum Rules{
        TEXT("vch"),INTEGER("int"),EMAIL("eml"),DECIMAL("dec");
        private final String typeCode;
        Rules(String typeCode){
            this.typeCode = typeCode;
        }
        public String code(){
            return typeCode;
        }
    }

    /**
     * Conversión de respuesta se los CheckBox.
     */
    public enum CbResponse{
        ON("on"),OFF("off");
        private final String code;
        CbResponse(String code){
            this.code = code;
        }
        public String code(){
            return code;
        }
    }

    /*****************
     * Constructores *
     *****************/

    /**
     * Constructor
     * @param context Actividad donde se generan las vistas.
     * @param properties propiedades de la pregunta.
     */
    public QuestionLayout(Activity context, Field properties){
        setProperties(properties);
        setContext(context);
    }

    /**
     * Implementación de constructor para que objetos de la clase sean Parcelables
     * @param in Parcel de lectura de datos.
     */
    protected QuestionLayout(Parcel in) {
        properties = in.readParcelable(Field.class.getClassLoader());
        subForm = in.readParcelable(FormLayout.class.getClassLoader());
    }

    /**************************************************
     * Obtención de vistas de acuerdo al tipo de campo *
     **************************************************/

    /**
     * configura y btiene el contenedor de una pregunta en el formulario.
     * @return Un LinearLayout contenedor de la pregunta.
     */
    public LinearLayout getQuestionLayout() {
        LayoutInflater layoutInflater = (LayoutInflater) this.context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.questionLayout = (LinearLayout) layoutInflater.inflate(R.layout.question_layout, null, false);
        this.questionLayout.setTag(getProperties());
        LinearLayout viewContainer = (LinearLayout) this.questionLayout.findViewById(R.id.questionViewContainer);
        this.labelView = (TextView) this.questionLayout.findViewById(R.id.questionLabelTextView);
        this.labelView.setText(getProperties().getLabel());

        if(!getProperties().isRequired()){
            this.labelView.setCompoundDrawablesWithIntrinsicBounds(
                    context.getResources().getDrawable(R.drawable.ic_question_no_required),null,null,null);
        }

        if(getProperties().getType().equals(Types.CHECK_BOX.code())){
            this.questionLayout.removeViewAt(0);
        }

        this.questionView = getQuestionView();
        questionView.setLayoutParams(
                new LinearLayout.LayoutParams(
                        ViewGroup.LayoutParams.MATCH_PARENT,
                        ViewGroup.LayoutParams.WRAP_CONTENT
                )
        );
        viewContainer.addView(this.questionView);
        return this.questionLayout;
    }

    /**
     * configura y obtiene una vista EditText
     * @return un campo editable EditText
     */
    private EditText getEditText(){
        EditText editText = new EditText(context);
        editText.setHint(properties.getHint());
        editText.setBackgroundResource(R.drawable.spinner);
        if(!getProperties().getHint().equals("")){
            editText.setHint(getProperties().getHint());
        }
        if(getProperties().getLength() > 0){
            editText.setFilters(new InputFilter[]{new InputFilter.LengthFilter(getProperties().getLength())});
        }
        if(getProperties().getType().equals(Types.DATE_PICKER.code())){
            editText.setOnClickListener(this);
            editText.setOnFocusChangeListener(this);
        }
        if(getProperties().getValue() != null){
            editText.setText(properties.getValue());
        }
        if(getProperties().getRules().equals(Rules.TEXT.code())){
            editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_text_field),null,null,null);
            editText.setInputType(InputType.TYPE_CLASS_TEXT);
        }else{
            if(getProperties().getRules().equals(Rules.INTEGER.code())){
                editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_numeric_int_field),null,null,null);
                editText.setInputType(InputType.TYPE_CLASS_NUMBER);
            }else{
                if(getProperties().getRules().equals(Rules.DECIMAL.code())){
                    editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_numeric_dec_field),null,null,null);
                    editText.setInputType(InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL);
                }else{
                    if(getProperties().getRules().equals(Rules.EMAIL.code())){
                        editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_email_field),null,null,null);
                        editText.setInputType(InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL);
                    }else{
                        if(getProperties().getType().equals(Types.DATE_PICKER)){
                            editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_date_field),null,null,null);
                        }else{
                            editText.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_text_field),null,null,null);
                            editText.setInputType(InputType.TYPE_CLASS_TEXT);
                        }
                    }
                }
            }
        }
        return editText;
    }

    /**
     * Configura y obtine una lista chequeable RadioGroup
     * @return un RadioGroup de opciones.
     */
    private RadioGroup getRadioGroup(){
        RadioGroup radioGroup = new RadioGroup(context);
        Options options = properties.getOptions();
        String value = getProperties().getValue();
        for(int i = 0; i < options.getOptions().size(); i++){
            String option = options.getOption(i);
            if(!option.equals("-")) {
                RadioButton radioButton = new RadioButton(context);
                radioButton.setText(options.getOption(i));
                if(value != null) {
                    if (radioButton.getText().toString().equals(value)) {
                        radioButton.setChecked(true);
                    }
                }
                radioGroup.addView(radioButton);
            }
        }
        if(radioGroup.getChildCount() == 2){
            radioGroup.setOrientation(LinearLayout.HORIZONTAL);
//            LinearLayout.LayoutParams params = (LinearLayout.LayoutParams) radioGroup.getLayoutParams();
//            params.setMargins(50,0,0,0);
        }
        if(getProperties().getHandlerEvent() != null) {
            radioGroup.setOnCheckedChangeListener(this);
        }
        return radioGroup;
    }

    /**
     * Configura y obtiene un componente CheckBox
     * @return un CheckBox
     */
    private CheckBox getCheckBox(){
        CheckBox checkBox = new CheckBox(context);
        String value = getProperties().getValue();
        if(value != null){
            if(value.equals(CbResponse.ON.code())){
                checkBox.setChecked(true);
            }
        }
        checkBox.setText(properties.getLabel());
        if(getProperties().getHandlerEvent()!= null || getProperties().getDynamicJoiner()!= null){
            checkBox.setOnCheckedChangeListener(this);
        }
        return checkBox;
    }

    /**
     * Configura y obtiene un AutoCompleteTextView
     * @return una vista AutocompleteTextView
     */
    private AutoCompleteTextView getAutoCompleteTextView(){
        AutoCompleteTextView autocomplete = new AutoCompleteTextView(context);
        if(getProperties().getValue() != null){
            autocomplete.setText(getProperties().getValue());
        }
        autocomplete.setHint(properties.getHint());
        autocomplete.setBackgroundResource(R.drawable.spinner);
        autocomplete.setOnClickListener(this);
        autocomplete.setCompoundDrawablesWithIntrinsicBounds(context.getResources().getDrawable(R.drawable.ic_list_field),null,null,null);
        autocomplete.setOnFocusChangeListener(this);
        return autocomplete;
    }

    /**
     * Configura y obtiene un Spinner.
     * @return una lista desplegable Spinner.
     */
    private Spinner getSpinner(){
        Spinner spinner = new Spinner(context);
        Options options = properties.getOptions();
        if(options.getOptions().size() > 0 && options.getOptions().get(0).equals("-")){
            options.getOptions().set(0, "Seleccione...");
            String value = getProperties().getValue();
            if(value != null){
                for(int i = 0; i < options.getOptions().size(); i++){
                    if(options.getOptions().get(i).equals(value)){
                        spinner.setSelection(i);
                        break;
                    }
                }
            }
        }
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(context,R.layout.custom_spinner,options.getOptions());
        spinner.setAdapter(adapter);
        spinner.setBackgroundResource(R.drawable.spinner);
        return spinner;
    }

    /**
     * Obtiene un tipo de componente según el tipo al que pertenece
     * @return Vista segun el tipo de componente que se requiere.
     */
    public View getQuestionView(){
        if (Types.TEXT_FIELD.code().equals(properties.getType())) {
            return getEditText();
        } else {
            if (Types.DATE_PICKER.code().equals(properties.getType())) {
                // TODO: 25/05/2016 Implementación de un componente DatePicker
                return getEditText();
            } else {
                if (Types.CHECK_BOX.code().equals(properties.getType())) {
                    return getCheckBox();
                } else {
                    if (Types.RADIO_GROUP.code().equals(properties.getType())) {
                        return getRadioGroup();
                    } else {
                        if (Types.AUTO_COMPLETE.code().equals(properties.getType())) {
                            return getAutoCompleteTextView();
                        } else {
                            if (Types.TEXT_VIEW.code().equals(properties.getType())) {
                                // TODO: 25/05/2016 implementación de un componente TextView
                            } else {
                                if (Types.SPINNER.code().equals(properties.getType())) {
                                    return getSpinner();
                                }
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    /*******************************
     * Implementación de listeners *
     *******************************/

    /**
     * Responde al evento cuando se selecciona una opción de un componente RadioGroup
     * @param radioGroup componente que desencadena el evento.
     * @param id identificador del la opción que se selecciona.
     */
    @Override
    public void onCheckedChanged(RadioGroup radioGroup, int id) {
        for (int i = 0; i < radioGroup.getChildCount(); i++) {
            RadioButton radioButton = (RadioButton) radioGroup.getChildAt(i);
            if (radioButton.isChecked()) {
                String value = radioButton.getText().toString();
                if(getProperties().getHandlerEvent().compareTo(value)){
                    showSubForm(getSubForm().getFormLayout());
                }else{
                    hideSubForm();
                }
                break;
            }
        }
    }

    /**
     * Responde al evento cuando se selecciona un botón compuesto.
     * @param compoundButton referencia al componente que desencadena el evento.
     * @param isChecked true si el componenete ha sido seleccionado, false si se deselecionó.
     */
    @Override
    public void onCheckedChanged(CompoundButton compoundButton, boolean isChecked) {
        String value = isChecked ? CbResponse.ON.code : CbResponse.OFF.code;
        if(getProperties().getDynamicJoiner().getHandlerEvent().compareTo(value)){
            // TODO: 27/05/2016   Solución a el problema de agregación del FormLayout que ya tiene un padre.
            try {
                getProperties().setValue(value);
                JSONObject structure = new JSONObject(new Constants().structure);
                int dynamicFormId = getProperties().getDynamicJoiner().getForm();
                FormLayout dynamicForm = new FormsLayouts(context,structure).getDynamicForm(dynamicFormId);
                //setSubForm(dynamicForm);
                //this.setSubForm(dynamicForm);
                /*for (int j = 0; j < subForm.questionsLength(); j++) {
                    if (subForm.getQuestion(j).questionView instanceof CheckBox) {
                        subForm.getQuestion(j).setSubForm(dynamicForm);
                    }
                }*/
                showDynamicForm(dynamicForm.getFormLayout());
                //showDynamicForm(subForm.getFormLayout());
            }catch (JSONException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
            // TODO: 27/05/2016
        }else{
            //hideSubForm();
            hideDynamicForm();
        }
    }

    /**
     * Responde al evento click de una vista.
     * @param view vista que desencadena el evento.
     */
    @Override
    public void onClick(View view) {
        if(properties.getType().equals(Types.DATE_PICKER.code())){
            datePickerHandler();
        }else{
            if(properties.getType().equals(Types.AUTO_COMPLETE.code())){
                autocompleteTextHandler();
            }
        }
    }

    /**
     * Responde a un cambio de foco de un componente
     * @param view vista que desencadena el evento.
     * @param isFocus true si la vista gana el foco, false si lo pierde.
     */
    @Override
    public void onFocusChange(View view, boolean isFocus) {
        if(isFocus){
            if(properties.getType().equals(Types.DATE_PICKER.code())){
                datePickerHandler();
            }else{
                if(properties.getType().equals(Types.AUTO_COMPLETE.code())){
                    autocompleteTextHandler();
                }
            }
        }
    }

    /******************************
     * Implementación de Handlers *
     ******************************/

    /**
     * Manejador de evento de la vista DatePicker
     */
    private void datePickerHandler(){
        AlertDialog.Builder builder = new AlertDialog.Builder(this.context);
        LayoutInflater inflater = (LayoutInflater) this.context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View layout = inflater.inflate(R.layout.datepicker_layout,null);
        builder.setView(layout);
        final DatePicker datePicker = (DatePicker) layout.findViewById(R.id.datePicker);
        builder.setPositiveButton("Aceptar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                Calendar calendar = Calendar.getInstance();
                calendar.set(datePicker.getYear(), datePicker.getMonth(), datePicker.getDayOfMonth());
                String date = new SimpleDateFormat("yyyy-MM-dd").format(calendar.getTime());
                ((EditText)getView()).setText(date);
            }
        });
        builder.setNegativeButton("Cancelar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
        builder.create().show();
    }

    /**
     * Manejador de la vista AutocompleTextView
     */
    private void autocompleteTextHandler(){
        Intent intent = new Intent(context, ItemSelectActivity.class);
        AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) getView();
        intent.putExtra(ItemSelectActivity.TITLE_EXTRA, getProperties().getLabel());
        intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, getProperties().getAutocomplete());
        intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, autoCompleteTextView.getText().toString());
        context.startActivityForResult(intent, FormActivity.ITEM_SELECT_REQUEST_CODE);
    }

    /*************************************************
     * Métodos para mostrar y ocultar subformularios *
     *************************************************/

    /**
     * Muestra un formulario dinámico debajo del formulario principal.
     * @param formLayout
     */
    private void showDynamicForm(LinearLayout formLayout){
        LinearLayout questionContainer = (LinearLayout) formLayout.findViewById(R.id.questionsContainer);
        for(int i = 0; i < questionContainer.getChildCount(); i++){
            LinearLayout questionLayout = (LinearLayout) questionContainer.getChildAt(i);
            LinearLayout viewContainer = (LinearLayout) questionLayout.findViewById(R.id.questionViewContainer);
            View view = viewContainer.getChildAt(0);
            if(view instanceof CheckBox){
                /*for(int j = 0; j < getSubForm().questionsLength(); j++){// Recorre todas las preguntas del subformulario
                    if(subForm.getQuestion(j).questionView instanceof CheckBox){
                    //if(getSubForm().getQuestion(j).getProperties().getDynamicJoiner() != null){// si la pregunta tiene un form dinámico le asigna el mismo formualrio.
                        getSubForm().getQuestion(j).setSubForm(getSubForm());
                    }
                }*/
                view.setTag(getProperties());
                ((CheckBox) view).setOnCheckedChangeListener(this);
            }
        }
        //showSubForm(formLayout);
        // TODO: 10/06/2016
        LinearLayout formContainer = (LinearLayout) context.findViewById(R.id.formContainer);
        formContainer.addView(formLayout);
    }

    private void hideDynamicForm(){
        LinearLayout formContainer = (LinearLayout) context.findViewById(R.id.formContainer);
        while(formContainer.getChildCount() > 1){
            formContainer.removeViewAt(1);
        }
    }

    /**
     * Muestra un subformulario dentro del contenedor de subFormularios del QuestionLayout
     * @param formLayout
     */
    private void showSubForm(LinearLayout formLayout){
        //LinearLayout subFormContainer = (LinearLayout) this.questionLayout.findViewById(R.id.subFormViewContainer);
        int position = this.questionLayout.getChildCount() - 1;
        LinearLayout subFormContainer = (LinearLayout) this.questionLayout.getChildAt(position);
        if(formLayout.getParent() != null){// prevee la adición del layout al subFormContainer si este ya tiene padre.
            ((LinearLayout) formLayout.getParent()).removeView(formLayout);
        }
        subFormContainer.addView(formLayout);
    }

    /**
     * Elimina un subformulario de la vista contenedora.
     */
    private void hideSubForm(){
        LinearLayout subFormContainer = (LinearLayout) this.questionLayout.findViewById(R.id.subFormViewContainer);
        if(subFormContainer.getChildCount() > 0){
            subFormContainer.removeAllViews();
        }
    }

    /*********************************
     * Validación de tipos de campos *
     *********************************/

    /**
     * Validate un componente TextView.
     * @return true si se validó el objeto con exito, false en caso contrario.
     */
    private boolean validateEditText(){
        EditText editText = (EditText) getView();
        if(editText.getText().toString().equals("")){
            if(getProperties().isRequired()) {
                this.labelView.setTextColor(context.getResources().getColor(R.color.ColorError));
                editText.setCompoundDrawablesWithIntrinsicBounds(
                        context.getResources().getDrawable(R.drawable.ic_text_field), null,
                        context.getResources().getDrawable(R.drawable.ic_error_validate), null);
                return false;
            } else {
                return true;
            }
        }else{
            editText.setCompoundDrawablesWithIntrinsicBounds(
                    context.getResources().getDrawable(R.drawable.ic_text_field),null,null,null);
            this.labelView.setTextColor(context.getResources().getColor(android.R.color.tertiary_text_light));
            getProperties().setValue(editText.getText().toString());
            return true;
        }
    }

    /**
     * Valida un componente RadioGroup.
     * @return true si se validó el objeto con exito, false en caso contrario.
     */
    private boolean validateRadioGroup(){
        RadioGroup radioGroup = (RadioGroup) getView();
        if(radioGroup.getCheckedRadioButtonId() == -1){
            if(getProperties().isRequired()) {
                this.labelView.setTextColor(context.getResources().getColor(R.color.ColorError));
                return false;
            }else{
                return true;
            }
        }else{
            this.labelView.setTextColor(context.getResources().getColor(android.R.color.tertiary_text_light));
            for(int i = 0; i < radioGroup.getChildCount(); i++){
                RadioButton radioButton = (RadioButton) radioGroup.getChildAt(i);
                if(radioButton.isChecked()){
                    getProperties().setValue(radioButton.getText().toString());
                    break;
                }
            }
            return true;
        }
    }

    /**
     * Valida un objeto Spinner.
     * @return true si se validó el objeto con exito, false en caso contrario.
     */
    private boolean validateSpinner(){
        Spinner spinner = (Spinner) getView();
        if(spinner.getSelectedItemPosition() == 0){
            if(getProperties().isRequired()) {
                this.labelView.setTextColor(context.getResources().getColor(R.color.ColorError));
                return false;
            }else{
                return true;
            }
        }else{
            this.labelView.setTextColor(context.getResources().getColor(android.R.color.tertiary_text_dark));
            getProperties().setValue(spinner.getSelectedItem().toString());
            return true;
        }
    }

    /**
     * valida un componente de acuerdo al tipo.
     * @return true si se validó el componente con exito, false en caso contrario.
     */
    public boolean validate() {
        Field properties = getProperties();
        if (properties.getType().equals(QuestionLayout.Types.TEXT_FIELD.code())) {
            return validateEditText();
        } else {
            if (properties.getType().equals(QuestionLayout.Types.DATE_PICKER.code())) {
                return validateEditText();
            } else {
                if (properties.getType().equals(QuestionLayout.Types.CHECK_BOX.code())) {
                    // TODO: 26/05/2016 implementación del validador de CheckBox.
                } else {
                    if (properties.getType().equals(QuestionLayout.Types.RADIO_GROUP.code())) {
                        return validateRadioGroup();
                    } else {
                        if (properties.getType().equals(QuestionLayout.Types.AUTO_COMPLETE.code())) {
                            return validateEditText();
                        } else {
                            if (properties.getType().equals(QuestionLayout.Types.TEXT_VIEW.code())) {
                                // TODO: 26/05/2016 iplementación del validador de TextView.
                            } else {
                                if (properties.getType().equals(QuestionLayout.Types.SPINNER.code())) {
                                    return validateSpinner();
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /********************************************
     * Implementación de la interfaz Parcelable *
     ********************************************/

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeParcelable(properties, i);
        parcel.writeParcelable(subForm, i);
    }

    public static final Creator<QuestionLayout> CREATOR = new Creator<QuestionLayout>() {
        @Override
        public QuestionLayout createFromParcel(Parcel in) {
            return new QuestionLayout(in);
        }

        @Override
        public QuestionLayout[] newArray(int size) {
            return new QuestionLayout[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    /*********************
     * GETTERS y SETTERS *
     *********************/


    public Field getProperties() {
        return properties;
    }

    public void setView(View questionView) {
        this.questionView = questionView;
    }

    public View getView(){
        return this.questionView;
    }

    public void setProperties(Field properties) {
        this.properties = properties;
    }

    public void setQuestionLayout(LinearLayout questionLayout) {
        this.questionLayout = questionLayout;
    }

    public Activity getContext() {
        return context;
    }

    public void setContext(Activity context) {
        this.context = context;
    }

    public FormLayout getSubForm() {
        return subForm;
    }

    public TextView getLabelView() {
        return labelView;
    }

    public void setLabelView(TextView labelView) {
        this.labelView = labelView;
    }

    public void setSubForm(FormLayout subForm) {
        this.subForm = subForm;
    }
}
