package com.miido.analiizo.mcompose;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.AdapterView;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.SpinnerAdapter;
import android.widget.TableLayout;
import android.widget.TextView;

import com.miido.analiizo.ItemSelectActivity;
import com.miido.analiizo.Main;
import com.miido.analiizo.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * crea objetos en base a la estructura de la encuesta
 * @version Alvaro Salgado MIIDO S.A.S 27/07/2015.
 * @version 1.0
 */
public class LiveObjectCreator {

    protected Constants constants;
    protected Context context;
    protected Activity activity;
    protected ComposeTools tools;

    protected JSONArray dynamicJoiner;
    protected String[][] structure;
    protected String[][] forms;
    protected String[][] handlers;
    protected String[][] options;
    protected int target;
    protected int handler;
    protected int idPrefix = 0;

    /**
     * constructor
     * @param context contexto del objeto
     */
    public LiveObjectCreator(Context context, Activity activity){
        this.context = context;
        this.activity = activity;
        this.constants = new Constants();
    }

    /**
     * busca un elemento en la estructura que contenga un handler, busca el elemento que generará el evento y luego crea el handler para el objeto
     * @param id identificador del objeto en la estructura
     * @param parent padre del objeto
     * @param type tipo de objeto
     * @return true si el handler es creado, false en caso contrario.
     * @throws Exception es lanzada si ocurre algún error en el método.
     * @see #findDynamicJoiner(int)
     * @see #findDynamicTarget()
     * @see #createHandler(int, Object, int)
     */
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

    /**
     * busca la propiedad dinamic joiner de la estructura
     * @param id identificador unico del campo
     * @return true si encontro la propiedad dinamic joiner, false en caso contrario.
     * @throws Exception es lanzada si ocurre un error al extraer datos de la estructura.
     * @see JSONObject
     */
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

    /**
     * busca el form que se desplegará condicionado a la repuesta de una pregunta
     * @return true si encuetra el origen, false en caso contrario.
     */
    private Boolean findDynamicTarget(){
        for (String[] tmp_forms : this.forms){
            if(tmp_forms[0].equals(this.target+"")){
                return true;
            }
        }
        return false;
    }

    /**
     * crea el manejador de evento
     * @param id identificador del objeto en la estructura
     * @param object objeto o vista que desencadenará el evento
     * @param type tipo de objeto
     * @throws Exception es lanzada si ocurre algún error al crear el manejador.
     * @see #matchHandlerValidator(String, String)
     */
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
                                    ViewParent vp = group.getParent();
                                    ViewParent vp2 = vp.getParent();
                                    /*for (int a = 0; a < ((LinearLayout) vp2).getChildCount(); a++) {
                                        if (((LinearLayout) vp2).getChildAt(a).getClass().equals(LinearLayout.class)) {
                                            ((LinearLayout) ((LinearLayout) vp2).getChildAt(a)).removeAllViews();
                                            ((LinearLayout) vp2).getChildAt(a).destroyDrawingCache();
                                            ((LinearLayout) vp2).removeViewAt(a);
                                        }
                                    }*/

                                    Boolean deleteFront = false;
                                    //agregar edinswon

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

                //(+)
                /*String tmp = (((CheckBox) object).getContentDescription().toString());
                String[] params = tmp.split(",");
                try {
                    String checked;
                    if (((CheckBox) object).isChecked()) {
                        checked = "on";
                    } else {
                        checked = "off";
                    }

                    //(+) agregado para versión interventoria
                    ViewParent checkBoxParent = ((CheckBox) object).getParent();
                    ViewParent vp = checkBoxParent.getParent();

                    for (; ; ) {
                        //if (vp.getParent().getParent().getClass().equals(ScrollView.class))
                        if (vp.getParent() == null)
                            break;
                        checkBoxParent = checkBoxParent.getParent();
                        vp = vp.getParent();
                    }

                    if (matchHandlerValidator(checked, params[2])) {
                        //agregar edinswon
                        ((View) vp).setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                        ((LinearLayout) vp).setOrientation(LinearLayout.VERTICAL);
                        LinearLayout ll = new LinearLayout(context);
                        //agregar edinswon
                        ll.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                        ll.setOrientation(LinearLayout.VERTICAL);
                        ll.addView(liveObjectsCreator(Integer.parseInt(params[0]), Integer.parseInt(params[1])));
                        ll.setTag(((CheckBox) object).getTag());
                        ((LinearLayout) vp).addView(ll, ((LinearLayout) vp).getChildCount() - 1);
                    }
                } catch (Exception e) {
                    Log.e(getClass().getName(), e.getMessage());
                    e.printStackTrace();
                }*/
                //(+)


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

                            //(+) agregado para versión interventoria
                            ViewParent vp2 = buttonView.getParent();
                            ViewParent vp = vp2.getParent();
                            for (; ; ) {
                                if (vp.getParent().getParent().getClass().equals(ScrollView.class))
                                    break;
                                vp2 = vp2.getParent();
                                vp = vp.getParent();
                            }
                            if (matchHandlerValidator(checked, params[2])) {
                                //agregar edinswon
                                ((View) vp).setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                                ((LinearLayout) vp).setOrientation(LinearLayout.VERTICAL);
                                LinearLayout ll = new LinearLayout(context);
                                //agregar edinswon
                                ll.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                                ll.setOrientation(LinearLayout.VERTICAL);
                                ll.addView(liveObjectsCreator(Integer.parseInt(params[0]), Integer.parseInt(params[1])));
                                ll.setTag(buttonView.getTag());
                                ((LinearLayout) vp).addView(ll, ((LinearLayout) vp).getChildCount() - 1);
                            } else {
                                Boolean deleteFront = false;
                                //agregar edinswon
                                for (int a = 0; a < ((TableLayout) vp).getChildCount() - 1; a++) {
                                    if (!(deleteFront)) {
                                        if (((LinearLayout) vp).getChildAt(a) == vp2) {
                                            deleteFront = true;
                                        }
                                    } else {
                                        try {
                                            ((TableLayout) vp).removeViewAt(a);
                                            a--;
                                        } catch (Exception e) {
                                            Log.e("error", "error");
                                        }
                                    }
                                }
                            }
                        } catch (Exception e) {
                            Log.e("a", "b");
                            e.printStackTrace();
                        }
                        //(+)
                            /*
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
                        }*/
                    }
                });
            break;
        }
    }

    /**
     * Obtiene un objeto dentro de la estructura identificado por nombre
     * @param name nombre del objeto
     * @return un objeto JSONObject con el objeto encontrado, null si no encuentra el objeto
     * @throws JSONException se lanza si ocurre algún error en el método.
     * @see JSONObject
     */
    public JSONObject findJObject(String name) throws JSONException{
        JSONArray jaTmp = tools.person.getJSONArray("diseases");
        for (int index = 0; index < jaTmp.length(); index++){
            if (name.equals(jaTmp.getJSONObject(index).getString("disName"))) {
                return jaTmp.getJSONObject(index);
            }
        }
        return null;
    }

    /**
     * valida el valor de la respuesta para lanzar el evento
     * @param value valor de la respuesta
     * @param handler nombre del handler a ejecutar.
     * @return true si la respuesta cumple con las condiciones del handler joiner, false en caso contrario.
     */
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

    /**
     * crea objetos vista para el formulario de la encuesta
     * @param id identificador del objeto
     * @param target objeto target
     * @return Linearlayout que contiene los obejetos.
     * @throws Exception es lanzada si ocurre un error en la creación del objeto.
     */
    protected LinearLayout liveObjectsCreator(int id, int target) throws Exception{
        Utilities utilities = new Utilities(this.context,this.activity, this.structure, this.forms, this.options);
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

    /**
     * establece el objeto dinamyc joiner
     * @param dynamicJoiner objeto JSONArray con la propiedad dinamyc joiner de la estructura
     */
    public void setDynamicJoiner(JSONArray dynamicJoiner){this.dynamicJoiner = dynamicJoiner;}

    /**
     * establece la estructura de la encuesta
     * @param structure matriz de string con la estructura de la encuesta.
     */
    public void setStructure(String[][] structure){this.structure = structure;}

    /**
     * establece los forms de la encuesta
     * @param forms matriz de string con los forms de la encuesta
     */
    public void setForms(String[][] forms){this.forms = forms;}

    /**
     * establece las opciones de una pregunta
     * @param options matriz de string que contiene las opciones.
     */
    public void setOptions(String[][] options){
        this.options = options;
    }

    /**
     * establece los handlers
     * @param handlers matriz de string que contiene los handlers
     */
    public void setHandlers(String[][] handlers){this.handlers = handlers;}

    /**
     * Estable el objeto ComposeTools
     * @param tools obeto ComposeTools
     * @see ComposeTools
     */
    public void setTools(ComposeTools tools) {
        this.tools = tools;
    }
}

/**
 * clase ayudante de liveObjectcreator para la creaciónde objetos
 * @version Alvaro Salgado MIIDO S.A.S 27/07/2015.
 * @version 1.0
 * @see LiveObjectCreator
 */
class Utilities extends Activity{

    protected volatile ObjectCreator objectCreator;
    protected volatile InterfaceBuilder interfaceBuilder;
    protected final Context context;
    protected final Activity activity;
    protected final String[][] structure;
    protected final String[][] forms;
    protected JSONArray dynamicJoiner;
    protected String[][] handlers;
    protected String[][] options;

    /**
     * constructor
     * @param context constexto del objeto
     * @param structure structura de la encuesta
     * @param forms formularios de la encuesta
     * @param options opciones de las preguntas
     * @see ObjectCreator
     * @see InterfaceBuilder
     */
    public Utilities(Context context, Activity activity, String[][] structure, String[][] forms, String[][] options){
        this.context = context;
        this.activity = activity;
        this.structure = structure;
        this.forms = forms;
        this.objectCreator = new ObjectCreator(this.context);
        this.interfaceBuilder = new InterfaceBuilder(this.context);
        this.interfaceBuilder.setOptionsList(options);
        }

    /**
     * obtiene los elementos de la estructura
     * @param target identificador del elemento
     * @return matriz de string con el elemento
     * @throws Exception
     */
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

    /**
     * crea la vista del elemento en base a la estructura
     * @param target identificador del elmento
     * @param elements matriz de string con los elementos
     * @param idPrefix identificador del prefijo
     * @return LinearLayout con el layout que contiene el elemento
     * @throws Exception es lanzanda si algún error ocurre al procesar el método.
     */
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
                ((TextView) v.get(v.size()-1)).setBackgroundResource(R.drawable.focus_border_style);
            } else if ((v.get(v.size()-1)).getClass().equals(DatePicker.class)){
                ((DatePicker) v.get(v.size() - 1)).setSpinnersShown(true);
                ((DatePicker) v.get(v.size() - 1)).setCalendarViewShown(false);
            } else if ((v.get(v.size()-1)).getClass().equals(Spinner.class)){
                ((Spinner) v.get(v.size()-1)).setBackgroundResource(R.drawable.spinner);
                LiveObjectCreator liveObjectCreator = initializeRecursiveVariables();
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
                LiveObjectCreator liveObjectCreator = initializeRecursiveVariables();
                liveObjectCreator.joinHandler(Integer.parseInt(tmpElement[0]), (v.get(v.size() - 1)), 3);
            }
        }
        LinearLayout result = new LinearLayout(this.context);
        result.setOrientation(LinearLayout.VERTICAL);
        result = layouts.get(map.getInt(target+""));
        result.setContentDescription(layouts.size() + "ss");
        result.setPadding(50, 0, 50, 0);
        return result;
    }

    /**
     * iniciliza el objeto LiveObjectCreator
     * @return objeto LiveObjectCreator
     */
    private LiveObjectCreator initializeRecursiveVariables(){
        LiveObjectCreator liveObjectCreator = new LiveObjectCreator(this.context,this.activity);
        liveObjectCreator.setDynamicJoiner(this.dynamicJoiner);
        liveObjectCreator.setStructure(this.structure);
        liveObjectCreator.setForms(this.forms);
        liveObjectCreator.setHandlers(this.handlers);
        liveObjectCreator.setOptions(this.options);
        return liveObjectCreator;
    }

    /**
     * crea una vista de auntocompletado y asigna eventos a este
     * @param object Objeto vista.
     */
    private void AutoCompleteTextViewListener(Object object){
        LinearLayout parent = (LinearLayout) ((View) object).getParent();
        TextView label = (TextView) parent.getChildAt(0);
        final String questionLabel = label.getText().toString();
        ((AutoCompleteTextView) object).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(context, ItemSelectActivity.class);
                AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) view;
                String serviceName = autoCompleteTextView.getContentDescription().toString();
                intent.putExtra(ItemSelectActivity.TITLE_EXTRA, questionLabel);
                intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, serviceName);
                intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, autoCompleteTextView.getText().toString());
                //intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                activity.startActivityForResult(intent, Main.ITEM_SELECT_REQUEST_CODE);//context.startActivity(intent); //startActivityForResult(intent, ITEM_SELECT_REQUEST_CODE);
            }
        });
        ((AutoCompleteTextView) object).setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(final View view, boolean hasfocus) {
                if (hasfocus) {
                    Intent intent = new Intent(context, ItemSelectActivity.class);
                    AutoCompleteTextView autoCompleteTextView = (AutoCompleteTextView) view;
                    String serviceName = autoCompleteTextView.getContentDescription().toString();
                    intent.putExtra(ItemSelectActivity.TITLE_EXTRA, questionLabel);
                    intent.putExtra(ItemSelectActivity.SERVICE_EXTRA, serviceName);
                    intent.putExtra(ItemSelectActivity.SELECTED_ITEM_EXTRA, autoCompleteTextView.getText().toString());
                    //intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                    activity.startActivityForResult(intent, Main.ITEM_SELECT_REQUEST_CODE);//context.startActivity(intent); //startActivityForResult(intent, ITEM_SELECT_REQUEST_CODE);


                    /*((AutoCompleteTextView) view).addTextChangedListener(new TextWatcher() {
                        @Override
                        public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

                        }

                        @Override
                        public void onTextChanged(CharSequence charSequence, int i0, int i1, int i2) {
                            SqlHelper SqlHelper = new SqlHelper(context);
                            final Constants Constants = new Constants();
                            final List<String> options = new ArrayList<>();
                            try {
                                AutoCompleteTextView ac = (AutoCompleteTextView) view;
                                String s = ac.getText().toString();
                                String auct = ac.getContentDescription().toString();
                                if (s.length() > 2) {
                                    try {
                                        options.clear();
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }

                                    SqlHelper.OOCDB();
                                    String query = Constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS;
                                    query = query.replace("[FIELDS]", "*");
                                    query = query.replace("[TABLE]", auct);
                                    for (int c = 0; c < Constants.fieldsToFilter.length; c++) {
                                        if (Constants.fieldsToFilter[c][0].equals(auct)) {
                                            String conditions = Constants.fieldsToFilter[c][1];
                                            conditions = conditions.replace(",", " LIKE '" + s + "%' OR ");
                                            conditions += " LIKE '" + s + "%' LIMIT 20";
                                            query = query.replace("[CONDITIONS]", conditions);
                                        }
                                    }

                                    Log.e("QUERY", query);

                                    SqlHelper.setQuery(query);
                                    SqlHelper.execQuery();
                                    Cursor cursor = SqlHelper.getCursor();
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
                            } catch (Exception e) {
                            }
                        }

                        @Override
                        public void afterTextChanged(Editable editable) {

                        }
                    });*/
                }
            }
        });

        /*((AutoCompleteTextView) object).setOnKeyListener(new View.OnKeyListener() {
            @Override
            public boolean onKey(View v, int keyCode, KeyEvent event) {
                SqlHelper SqlHelper = new SqlHelper(context);
                final Constants Constants = new Constants();
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

                        SqlHelper.OOCDB();
                        String query = Constants.GENERIC_SELECT_QUERY_WITH_CONDITIONS;
                        query = query.replace("[FIELDS]", "*");
                        query = query.replace("[TABLE]", auct);
                        for (int c = 0; c < Constants.fieldsToFilter.length; c++) {
                            if (Constants.fieldsToFilter[c][0].equals(auct)) {
                                String conditions = Constants.fieldsToFilter[c][1];
                                conditions = conditions.replace(",", " LIKE '" + s + "%' OR ");
                                conditions += " LIKE '" + s + "%' LIMIT 20";
                                query = query.replace("[CONDITIONS]", conditions);
                            }
                        }

                        Log.e("QUERY", query);

                        SqlHelper.setQuery(query);
                        SqlHelper.execQuery();
                        Cursor cursor = SqlHelper.getCursor();
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
                } catch (Exception e) {
                }
                return false;
            }
        });*/
    }

    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        Log.e(this.getClass().getName(), "REQUESTCODE: " + requestCode);
    }

    /**
     * Obtiene el form padre de los elementos o preguntas
     * @param parent  identificador del form padre
     * @param elements matriz de string con los elementos o preguntas.
     * @return
     */
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