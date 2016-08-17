package com.miido.analiizo.mcompose;

import android.content.Context;
import android.graphics.Color;
import android.text.InputFilter;
import android.text.InputType;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListPopupWindow;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TextView;

import com.miido.analiizo.R;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;

import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.List;

/**
 * crea un control o pregunta de la encuesta a partir de la estructura.
 * @author Salgado MIIDO S.A.S 18/02/2015.
 * @version 1.0
 */
public class ObjectCreator {

    private Object object;
    private Context context;
    private SqlHelper sqlHelper;
    private Constants constants;

    private int id;
    private String referenceQuestion;
    private String name;
    private String hint;
    private String readOnly;
    private String autoCompleteTable;
    private int required;
    private List<String> options;

    private int type;
    private int inputRules;
    private int maxLength;

    private JSONArray ACindex;
    private String[][] acContent;

    /**
     * constructor
     * @param context contexto del objeto.
     * @see SqlHelper
     * @see Constants
     * @see JSONArray
     */
    public ObjectCreator(Context context) {
        this.context = context;
        this.sqlHelper = new SqlHelper(this.context);
        this.constants = new Constants();
        this.ACindex = new JSONArray();
        this.acContent = new String[100][];
    }

    /**
     * establece el nombre del control
     * @param name texto con el nombre del control.
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     * establede el tipo de control
     * @param type texto con el tipo de control.
     */
    public void setType(int type) {
        this.type = type;
    }

    /**
     * establece la propiedad de solo lectura del control
     * @param ro texto con el valor de la propiedad de solo lectura.
     */
    public void setReadOnly(String ro) {
        this.readOnly = ro;
    }

    /**
     * establece el mensaje del control
     * @param hint texto con el mensaje de hint del control
     */
    public void setHint(String hint) {
        this.hint = hint;
    }

    /**
     * establece la propiedad de autocompletado del control
     * @param autoCompleteTable texto con la propiedad de autocompletado del control.
     */
    public void setAutoCompleteTable(String autoCompleteTable) {
        this.autoCompleteTable = autoCompleteTable;
    }

    /**
     * establece el tipo de regla del control
     * @param rules número con el tipo de regla del control.
     */
    public void setInputRules(int rules) {
        this.inputRules = rules;
    }

    public int getInputRules(){
        return this.inputRules;
    }

    /**
     * establece el tamaño maximo de caracteres del control
     * @param maxLength numero maximo de caracteres soportado por el control.
     */
    public void setMaxLength(int maxLength) {
        this.maxLength = maxLength;
    }

    /**
     * establece el listado de opciones del control
     * @param options listado de opciones del control.
     */
    public void setOptionsList(List<String> options) {
        this.options = options;
    }

    /**
     * establece el cursor apartir de los recursos.
     */
    private void setCursorDrawable() {
        try {
            Field f = TextView.class.getDeclaredField("mCursorDrawableRes");
            f.setAccessible(true);
            f.set((this.object), R.drawable.cursor);
        } catch (Exception ignored) {
        }
    }

    /**
     * establece si el control es obligatorio
     * @param required true si es obligatorio, false si no lo es
     */
    public void setRequired(Boolean required){
        this.required = ((required) ? 1 : 0);
    }

    /**
     * Construye el objeto apartir de las propiedades establecidas
     * @return un Object con las propiedades establecidas.
     * @see #createObject()
     * @see #setObjectProperties()
     */
    public Object buildObject() {
        createObject();
        setObjectProperties();
        return this.object;
    }

    /**
     * crea el objeto en base a las porpiedades establecidas
     */
    public void createObject() {
        switch (this.type) {
            case 0:
                this.object = new ScrollView(this.context);
                break;
            case 1:
                this.object = new EditText(this.context);
                break;
            case 2:
                this.object = new DatePicker(this.context);

                break;
            case 3:
                this.object = new CheckBox(this.context);
                break;
            case 4:
                if (this.options.size() < 4) {
                    this.object = new RadioGroup(this.context);
                } else if(this.options.size() <=5 ){
                    int counter = 0;
                    for (int index = 0; index < this.options.size(); index++){
                        if(!this.options.get(index).toString().equals("Seleccione ...")){
                            counter += this.options.get(index).length();
                        }
                    }
                    if((counter/this.options.size())<=2){
                        this.object = new RadioGroup(this.context);
                    } else {
                        this.type = 8;
                        createObject();
                    }
                } else {
                    this.type = 8;
                    createObject();
                }
                break;
            case 5:
                this.object = new AutoCompleteTextView(this.context);
                break;
            case 6:
                this.object = new TextView(this.context);
                break;
            case 7:
                this.object = new ListPopupWindow(this.context);
                break;
            case 8:
                this.object = new Spinner(this.context);
                break;
            default:
                break;
        }
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getReferenceQuestion() {
        return referenceQuestion;
    }

    public void setReferenceQuestion(String referenceQuestion) {
        this.referenceQuestion = referenceQuestion;
    }

    /**
     * establece las propiedades del la vista en base a los atributos establecidos
     */
    private void setObjectProperties() {
        ((View) this.object).setTag(this.name);
        switch (this.type) {
            case 1:
                ((EditText) this.object).setHint(this.hint);
                ((EditText) this.object).setHintTextColor(Color.GRAY);
                ((EditText) this.object).setTextColor(Color.BLACK);
                ((EditText) this.object).setFilters(new InputFilter[]{new InputFilter.LengthFilter(this.maxLength)});
                setCursorDrawable();
                ((EditText) this.object).setId(this.required);

                if (this.readOnly.equals("1")) {
                    ((EditText) this.object).setEnabled(false);
                    ((EditText) this.object).setBackgroundResource(R.drawable.disabled);
                }

                ((EditText) this.object).setPadding(
                        15, ((EditText) this.object).getPaddingTop(),
                        ((EditText) this.object).getPaddingRight(), ((EditText) this.object).getPaddingBottom() - 3);
                switch (this.inputRules) {
                    case 0:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_TEXT);
                        break;
                    case 1:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_NUMBER);
                        //(+) agregado para mostrar valor por defecto en los campos numericos
                        ((EditText) this.object).setText("0");
                        //((EditText) this.object).setHint("0");
                        //(+)
                        break;
                    case 2:
                        ((EditText) this.object).setInputType(InputType.TYPE_TEXT_VARIATION_EMAIL_ADDRESS);
                        break;
                    case 3:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL);
                        //(+) agregado para mostrar valor por defecto en los campos numericos
                        ((EditText) this.object).setText("0.0");
                        //((EditText) this.object).setHint("0.0");
                        //(+)
                }
                break;
            case 2:
                ((DatePicker) this.object).setId(this.required);
                break;
            case 3:
                ((CheckBox) this.object).setId(this.required);
                break;
            case 4:
                try {
                    if (this.options.size() > 0) {
                        for (int a = 0; a < this.options.size(); a++) {
                            if (!this.options.get(a).equals("Seleccione ...") && !this.options.get(a).equals("")) {
                                RadioButton rb = new RadioButton(this.context);
                                rb.setText(this.options.get(a) + "");
                                rb.setTextColor(Color.BLACK);
                                rb.setButtonDrawable(R.drawable.radio_button);
                                rb.setPadding(0, 0, 25, 0);
                                ((RadioGroup) this.object).addView(rb);
                            }
                        }
                        ((RadioGroup) this.object).setPadding(0, 5, 0, 0);
                        ((RadioGroup) this.object).setOrientation(LinearLayout.HORIZONTAL);
                    }
                } catch (Exception e) {
                    e.getMessage();
                }
                ((RadioGroup) this.object).setId(this.required);
                break;
            case 5:
                ((AutoCompleteTextView) object).setDropDownHeight(300);
                ((AutoCompleteTextView) object).setThreshold(250);
                ((AutoCompleteTextView) object).setHint(hint);
                ((AutoCompleteTextView) object).setHintTextColor(Color.GRAY);
                //(+)
                ((AutoCompleteTextView) object).setMaxWidth(((AutoCompleteTextView) object).getWidth());
                ((AutoCompleteTextView) object).setId(this.required);
                //(+)
                setCursorDrawable();
                List<String> empty = new ArrayList<>();
                ArrayAdapter<String> adapter = new ArrayAdapter<>(context, R.layout.dropdown, empty);
                ((AutoCompleteTextView) object).setAdapter(adapter);
                ((AutoCompleteTextView) object).setContentDescription(autoCompleteTable);
                break;
            case 8:
                ArrayAdapter<String> aa = new ArrayAdapter<>(this.context, R.layout.custom_spinner, this.options);
                aa.setDropDownViewResource(R.layout.dropdown);
                //((Spinner) this.object).setMinimumWidth(((Spinner) this.object).getWidth());
                ((Spinner) this.object).setMinimumWidth(100);
                //((Spinner) this.object).getLayoutParams().width = 300;
                ((Spinner) this.object).setAdapter(aa);
                ((Spinner) this.object).setId(this.required);
                if (this.readOnly.equals("1")) {
                    ((Spinner) this.object).setEnabled(false);
                }
                ((Spinner) this.object).setPrompt("Choose an option");
                break;
        }
    }
}