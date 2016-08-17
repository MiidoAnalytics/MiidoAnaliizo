package com.miido.analiizo.model;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Parcel;
import android.os.Parcelable;
import android.text.InputFilter;
import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;

import com.miido.analiizo.DatePickerActivity;
import com.miido.analiizo.FormActivity;
import com.miido.analiizo.ItemSelectActivity;
import com.miido.analiizo.R;
import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.util.JsonUtil;

import java.util.ArrayList;

/**
 * Configura un campo de acuerdo a su tipo y configura el Layout contenedor de este.
 * @author Ing. Miguel Angel Unrango Blanco Miido S.A.S 06/05/2016.
 * @see Parcelable
 * @see android.widget.CompoundButton.OnCheckedChangeListener
 * @see android.widget.RadioGroup.OnCheckedChangeListener
 * @see android.view.View.OnFocusChangeListener
 * @see android.view.View.OnClickListener
 */

public class QuestionLayout implements Parcelable,
        RadioGroup.OnCheckedChangeListener,
        CompoundButton.OnCheckedChangeListener,
        View.OnClickListener,
        View.OnFocusChangeListener,
        AdapterView.OnItemSelectedListener,
        View.OnLongClickListener{

    private Activity context;// Actividad donde se generan las vistas.
    private Field properties;// propiedades extraidas de la extructura JSON.
    private LinearLayout questionLayout; //LinearLAyout Contenedor de la pregunta.
    private TextView labelView; // TextView enunciado de la pregunta
    private View questionView;// View vista de la pregunta (TextFiel,Autocomplete, etc).
    //private FormLayout subForm; // FormLayout que representa un subformulario que se muestra según la respuesta de a pregunta.

    private ArrayList<FormLayout> subForms;

    private JsonUtil jsonUtil;

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
        setSubForms( new ArrayList<FormLayout>());
    }

    /**
     * Implementación de constructor para que objetos de la clase sean Parcelables
     * @param in Parcel de lectura de datos.
     */
    protected QuestionLayout(Parcel in) {
        properties = in.readParcelable(Field.class.getClassLoader());
        //subForm = in.readParcelable(FormLayout.class.getClassLoader());
        subForms = in.createTypedArrayList(FormLayout.CREATOR);
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

        if(getProperties().isReadOnly()){
            this.labelView.setCompoundDrawablesWithIntrinsicBounds(
                    context.getResources().getDrawable(R.drawable.ic_question_lock),null,null,null);
        }

        // TODO: 20/06/2016 developer.
        this.questionView.setOnLongClickListener(this);

        this.questionView.setEnabled(!getProperties().isReadOnly());

        questionView.setLayoutParams(
                new LinearLayout.LayoutParams(
                        ViewGroup.LayoutParams.MATCH_PARENT,
                        ViewGroup.LayoutParams.WRAP_CONTENT
                )
        );
        viewContainer.addView(this.questionView);
        return this.questionLayout;
    }

    public TextView getTexView(){
        TextView textView = new TextView(context);
        textView.setText(properties.getLabel());
        return  textView;
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
            editText.setHint(new Constants().DATE_FORMAT);
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
                        if(getProperties().getType().equals(Types.DATE_PICKER.code())){
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
        if(getProperties().getHandlerEvent()!= null || getProperties().getDynamicJoiners().size() > 0){
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
        if(properties.getHandlerEvent() != null || properties.getDynamicJoiners().size() > 0){
            spinner.setOnItemSelectedListener(this);
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
                                return getTexView();
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

    @Override
    public boolean onLongClick(View view) {
        com.miido.analiizo.util.Dialog.Alert(context,"Question Info",
                getProperties().toString()).show();
        return true;
    }

    @Override
    public void onItemSelected(AdapterView<?> adapterView, View view, int position, long id) {
        String value = adapterView.getSelectedItem().toString();
        getProperties().setValue(value);
        this.questionLayout.setTag(getProperties());

        if(properties.getHandlerEvent() != null) {
            if (properties.getHandlerEvent().compareTo(value)) {
                for (FormLayout subForm : getSubForms()) {
                    if(!subForm.isVisible()) {
                        showSubForm(subForm.getFormLayout());
                        break;// el primer subformulario.
                    }
                }
                //showSubForm(getSubForm().getFormLayout());
            } else {
                hideSubForm();
            }
        }
        /*for(DynamicJoiner dynamicJoiner : getProperties().getDynamicJoiners()) {
            int fieldId = dynamicJoiner.getField();
            int formId = dynamicJoiner.getForm();
            DynamicJoiner tmpDynamicJoiner = evaluateFieldHandlerJoiner(fieldId, formId);
            if(tmpDynamicJoiner != null) {
                Handler handler = tmpDynamicJoiner.getHandlerEvent();
                if (handler != null) {
                    value = value == null || value.equals("Seleccione...") ? "-" : value;
                    if (tmpDynamicJoiner != null && handler.compareTo(value)) {
                        for(FormLayout subForm : getSubForms()) {
                            if(subForm.isVisible() && subForm.getProperties().getInside() == 1) {
                                showSubForm(subForm.getFormLayout());
                                //showDynamicForm(subForm.getFormLayout());
                                break;
                            }
                        }
                    }
                }
            }
        }*/
    }

    /*private DynamicJoiner evaluateFieldHandlerJoiner(int idField,int idForm){
        LinearLayout formsContainer = (LinearLayout) context.findViewById(R.id.formContainer);
        return evaluateFieldHandlerJoiner(idField, idForm, formsContainer);
    }

    private DynamicJoiner evaluateFieldHandlerJoiner(int idField,int idForm,LinearLayout formsContainer){
        for (int i = 0; i < formsContainer.getChildCount(); i++){
            LinearLayout formLayout = (LinearLayout) formsContainer.getChildAt(i);
            LinearLayout questionContainer = (LinearLayout) formLayout.getChildAt(formLayout.getChildCount() == 2 ? 1 : 0);
            for(int j = 0; j < questionContainer.getChildCount(); j++){
                LinearLayout questionLayout = (LinearLayout) questionContainer.getChildAt(j);
                Field properties = (Field) questionLayout.getTag();
                for( DynamicJoiner dynamicJoiner : properties.getDynamicJoiners()) {
                    if (dynamicJoiner != null) {
                        Handler handler = dynamicJoiner.getHandlerEvent();
                        String value = properties.getValue();
                        value = value == null || value.equals("Seleccione...") ? "-" : value;
                        if (dynamicJoiner.getField() != idField && dynamicJoiner.getForm() == idForm && handler.compareTo(value)) {
                            return dynamicJoiner;
                        } else {
                            LinearLayout subFormContainer = (LinearLayout) questionLayout.getChildAt(questionLayout.getChildCount() == 3 ? 2 : 1);
                            return evaluateFieldHandlerJoiner(idField, idForm, subFormContainer);
                        }
                    }
                }
            }
        }
        return null;
    }*/

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }

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
                    for(FormLayout subForm : getSubForms()){
                        showSubForm(subForm.getFormLayout());
                    }
                    //showSubForm(getSubForm().getFormLayout());
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

        if (getProperties().getHandlerEvent().compareTo(value)) {
            for (FormLayout formLayout : getSubForms()) {
                if (!formLayout.isVisible()) {
                    showSubForm(formLayout.getFormLayout());
                    break;
                }
            }
        } else {
            hideSubForm();
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
        /*AlertDialog.Builder builder = new AlertDialog.Builder(this.context);
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
                Date currentDate = new Date();
                int edad = currentDate.getYear() - calendar.getTime().getYear() - (calendar.getTime().getMonth() > currentDate.getMonth() ? 1 : 0);
                Toast.makeText(context, edad + "", Toast.LENGTH_LONG).show();
                ((EditText)getView()).setText(date);
            }
        });
        builder.setNegativeButton("Cancelar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
        builder.create().show();*/
        Intent intent = new Intent(context,DatePickerActivity.class);
        EditText datePicker = (EditText) getQuestionView();
        if(!datePicker.getText().toString().equals("")){
            intent.putExtra(DatePickerActivity.DATE_EXTRA, datePicker.getText().toString());
        }
        context.startActivityForResult(intent, FormActivity.DATE_PICKER_REQUEST_CODE);
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
    public void showSubForm(LinearLayout formLayout){
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
        if(editText == null){
            return true;
        }
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
        if(radioGroup == null){
            return true;
        }
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
        if(spinner == null){
            return true;
        }
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

    private boolean validateCheckBox(){
        CheckBox checkBox = (CheckBox) getView();
        getProperties().setValue(checkBox.isChecked() ? CbResponse.ON.code : CbResponse.OFF.code);
        return true;
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
                    return validateCheckBox();
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
     *  Llenado de tipos de campo               *
     ********************************************/

    private void fillEditText(){
        EditText editText = (EditText) getView();
        editText.setText(getProperties().getValue());
    }

    private void fillRadioGroup(){
        RadioGroup radioGroup = (RadioGroup) getView();
        String value = getProperties().getValue();
        for(int i = 0; i < radioGroup.getChildCount(); i++){
            RadioButton radioButton = (RadioButton) radioGroup.getChildAt(i);
            if(radioButton.getText().toString().equals(value)){
                radioButton.setChecked(true);
                break;
            }
        }
    }

    private void fillSpinner(){
        Spinner spinner = (Spinner) getView();
        String value = getProperties().getValue();
        ArrayAdapter<String> adapter = (ArrayAdapter<String>) spinner.getAdapter();
        for(int i = 0; i < adapter.getCount(); i++){
            if(adapter.getItem(i).equals(value)){
                spinner.setSelection(i);
                break;
            }
        }
    }

    private void fillCheckBox(){
        ((CheckBox) getView()).setChecked(getProperties().getValue().equals(CbResponse.ON.code));
    }

    public void fill(){
        if (properties.getType().equals(QuestionLayout.Types.TEXT_FIELD.code())) {
            fillEditText();
        } else {
            if (properties.getType().equals(QuestionLayout.Types.DATE_PICKER.code())) {
                fillEditText();
            } else {
                if (properties.getType().equals(QuestionLayout.Types.CHECK_BOX.code())) {
                    fillCheckBox();
                } else {
                    if (properties.getType().equals(QuestionLayout.Types.RADIO_GROUP.code())) {
                        fillRadioGroup();
                    } else {
                        if (properties.getType().equals(QuestionLayout.Types.AUTO_COMPLETE.code())) {
                            fillEditText();
                        } else {
                            if (properties.getType().equals(QuestionLayout.Types.TEXT_VIEW.code())) {
                                // TODO: 26/05/2016 iplementación del validador de TextView.
                            } else {
                                if (properties.getType().equals(QuestionLayout.Types.SPINNER.code())) {
                                    fillSpinner();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /********************************************
     * Implementación de la interfaz Parcelable *
     ********************************************/

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeParcelable(properties, i);
        //parcel.writeParcelable(subForm, i);
        parcel.writeTypedList(subForms);
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

    /*public FormLayout getSubForm() {
        return subForm;
    }

    public void setSubForm(FormLayout subForm) {
        this.subForm = subForm;
    }*/

    public TextView getLabelView() {
        return labelView;
    }

    public void setLabelView(TextView labelView) {
        this.labelView = labelView;
    }

    public void setQuestionView(View questionView) {
        this.questionView = questionView;
    }

    public ArrayList<FormLayout> getSubForms() {
        return subForms;
    }

    public void setSubForms(ArrayList<FormLayout> subforms) {
        this.subForms = subforms;
    }

    public void addSubForm(FormLayout subForm){
        this.subForms.add(subForm);
    }

    public JsonUtil getJsonUtil() {
        return jsonUtil;
    }

    public void setJsonUtil(JsonUtil jsonUtil) {
        this.jsonUtil = jsonUtil;
    }
}
