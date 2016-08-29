package com.miido.analiizoOBRAS.mcompose;

import android.content.Context;
import android.graphics.Color;
import android.text.InputFilter;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TextView;

import com.miido.analiizoOBRAS.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.util.ArrayList;
import java.util.EventObject;
import java.util.Iterator;

/**
 * proporciona herramientas para generar la interfaz grafica de la encuesta.
 * @version Alvaro Salgado MIIDO S.A.S 25/02/2015
 * @version 1.0
 */

public class ComposeTools {

    public Structure structure;
    public String[][] fieldsStructure;
    public int type;
    public int hType;
    public int lastId;
    public Boolean started;
    //public String asignedId;
    public JSONObject person;
    public JSONObject Master;
    public Boolean gotValue;
    public Boolean filteredValue;
    public String info;
    public String[] preloaded;
    public String[][] personStr;
    private Constants constants;
    private Context context;
    private Boolean[][] mJoinHandler;
    private OutputStreamWriter osw;
    private BufferedReader br;
    private int event;
    //private ArrayList alfs;
    //private int aux;
    //private String formHandled;
    private String formJoinedHandled;
    private int formInsider;
    private int parentField;
    //private int parentForm;
    private String target;
    private ArrayList<String> indexHiddenSubForms;
    private int action;

    /**
     * constructor
     * @param context contexto del objeto
     * @param structureid identificador de la estructura.
     */
    public ComposeTools(Context context, long structureid) {
        this.constants = new Constants();
        this.context = context;
        this.structure = new Structure(context,structureid);
        this.structure.setStructure();
        this.person = new JSONObject();
        this.started = false;
    }

    /**
     * crea una vista a partir del objeto
     * @param object objeto a convertir
     * @param name nombre del objeto
     * @return objeto View que es el componente visual en adroid
     * @throws Exception es lanzada si ocurre un error en la creación de la vista.
     */
    public View ObjectCreator(int id ,Object object, String name) throws Exception {
        this.hType = 1;
        this.type = 0;
        gotValue = false;
        filteredValue = false;

        if (object.getClass().equals(EditText.class)) {
            ((EditText) object).setBackgroundResource(R.drawable.focus_border_style);

            ((EditText) object).setContentDescription(name);
            ((EditText) object).setCursorVisible(true);
            try {
                ((EditText) object).setText(person.getString(name));
                gotValue = true;
            } catch (JSONException je) {
                je.printStackTrace();
                //throw new RuntimeException(je);
            }
            if (((EditText) object).getText().length() == 0) {
                try {
                    ((EditText) object).setText(validateFoundedData(name));
                    if (((EditText) object).length() > 0) {
                        ((EditText) object).setEnabled(false);
                        ((EditText) object).setBackgroundResource(R.drawable.disabled);
                        filteredValue = true;
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    //throw new RuntimeException(e);
                }
            }
            this.type = 2;
            return ((EditText) object);
        } else if (object.getClass().equals(DatePicker.class)) {
            EditText et_tmp = new EditText(this.context);
            et_tmp.setFilters(new InputFilter[]{new InputFilter.LengthFilter(10)});
            et_tmp.setBackgroundResource(R.drawable.focus_border_style);
            et_tmp.setCompoundDrawablesWithIntrinsicBounds(null, null, context.getResources().getDrawable(R.drawable.ic_date), null);
            et_tmp.setHint("DD/MM/AAAA");
            et_tmp.setHintTextColor(Color.DKGRAY);
            et_tmp.setTextColor(Color.BLACK);
            et_tmp.setContentDescription(name);
            et_tmp.setTag(((DatePicker) object).getTag());
            try {
                et_tmp.setText(person.getString(name));
                gotValue = true;
            } catch (JSONException je) {
                je.printStackTrace();
            }
            try {
                et_tmp.setText(validateFoundedData(name));
                if (et_tmp.length() > 0) {
                    et_tmp.setEnabled(false);
                    et_tmp.setBackgroundResource(R.drawable.disabled);
                    filteredValue = true;
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            this.hType = 0;
            this.type = 2;
            return et_tmp;
        } else if (object.getClass().equals(RadioGroup.class)) {
            ((RadioGroup) object).setFocusableInTouchMode(true);
            ((RadioGroup) object).setContentDescription(name);
            try {
                String subName = name.substring(0, 3);
                if (!subName.equals(constants.disPrefix)) {
                    String val = person.getString(name);
                    for (int c = 0; c < ((RadioGroup) object).getChildCount(); c++) {
                        View rb = ((RadioGroup) object).getChildAt(c);
                        try {
                            if (rb.getClass().equals(RadioButton.class)) {
                                if (((RadioButton) rb).getText().equals(val)) {
                                    ((RadioButton) rb).setChecked(true);
                                }
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                    if (val.length() > 0) {
                        ((RadioGroup) object).setEnabled(false);
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            this.type = 4;
            return ((RadioGroup) object);
        } else if (object.getClass().equals(Spinner.class)) {
            ((Spinner) object).setBackgroundResource(R.drawable.spinner);
            ((Spinner) object).setContentDescription(name);
            ((Spinner) object).setRight(1);
            ((Spinner) object).setFocusableInTouchMode(true);
            try {
                String value = (person.getString(name));
                for (int c = 0; c < ((Spinner) object).getCount(); c++) {
                    if (((Spinner) object).getItemAtPosition(c).toString().equals(value)) {
                        ((Spinner) object).setSelection(c);
                        gotValue = true;
                        break;
                    }
                }
            } catch (JSONException je) {
                je.printStackTrace();
            }
            try {
                String value = (validateFoundedData(name));
                for (int c = 0; c < ((Spinner) object).getCount(); c++) {
                    if (((Spinner) object).getItemAtPosition(c).toString().equals(value)) {
                        ((Spinner) object).setSelection(c);
                        gotValue = true;
                        break;
                    }
                }
                if (value.length() > 0) {
                    ((Spinner) object).setEnabled(false);
                    ((Spinner) object).setBackgroundResource(R.drawable.disabled);
                    filteredValue = true;
                }
            } catch (Exception je) {
                je.printStackTrace();
            }
            this.type = 8;
            return ((Spinner) object);
        } else if (object.getClass().equals(CheckBox.class)) {
            ((CheckBox) object).setButtonDrawable(R.drawable.checkbox);
            ((CheckBox) object).setTextColor(Color.DKGRAY);
            ((CheckBox) object).setContentDescription(name);
            try {
                if (Boolean.parseBoolean(person.getString(name))) {
                    ((CheckBox) object).setChecked(true);
                    gotValue = true;
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            this.type = 3;
            return ((CheckBox) object);
        } else if (object.getClass().equals(TextView.class)) {
            ((TextView) object).setBackgroundResource(R.drawable.textview_1);
            return (TextView) object;
        } else if (object.getClass().equals(AutoCompleteTextView.class)) {
            ((AutoCompleteTextView) object).setTextColor(Color.BLACK);
            ((AutoCompleteTextView) object).setBackgroundResource(R.drawable.focus_border_style);
            //((AutoCompleteTextView) object).setContentDescription("OFF");
            try {
                ((AutoCompleteTextView) object).setText(person.getString(name));
                //((AutoCompleteTextView) object).setContentDescription("ON");
            } catch (JSONException je) {
                je.printStackTrace();
            }
            try {
                if (((AutoCompleteTextView) object).getText().length() < 1) {
                    ((AutoCompleteTextView) object).setText(validateFoundedData(name));
                    if (((AutoCompleteTextView) object).getText().length() > 0) {
                        ((AutoCompleteTextView) object).setEnabled(false);
                        ((AutoCompleteTextView) object).setBackgroundResource(R.drawable.disabled);
                        filteredValue = true;
                        //((AutoCompleteTextView) object).setContentDescription("ON");
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            this.type = 5;
            return (AutoCompleteTextView) object;
        }
        return null;
    }

    /**
     * Valida información del campo desde las constantes.
     * @param name nombre del objeto
     * @return el nombre del objeto validado.
     * @throws Exception lanza una excepción si la validación sale mal.
     */
    public String validateFoundedData(String name) throws Exception {
        int c = 0;
        for (String field : this.constants.targetFilter) {
            if (name.equals(field)) {
                return this.preloaded[c + 1];
            }
            c++;
        }
        return "";
    }

    /**
     * inicializa la matriz de Join Handler
     */
    public void startJoinHandler() {
        JSONArray handlerFieldJoiner = new JSONArray();
        try {
            handlerFieldJoiner = this.structure.jStructure.getJSONArray("HandlerFieldJoiner");
            this.mJoinHandler = new Boolean[handlerFieldJoiner.length()][];
        } catch (JSONException je) {
            je.printStackTrace();
        }
        for (int c = 0; c < handlerFieldJoiner.length(); c++) {
            try {
                this.mJoinHandler[c] = new Boolean[handlerFieldJoiner.getJSONObject(c).getJSONArray("idFields").length()];
            } catch (JSONException je) {
                je.printStackTrace();
            }
        }
        for (int c = 0; c < mJoinHandler.length; c++) {
            for (int i = 0; i < mJoinHandler[c].length; i++) {
                mJoinHandler[c][i] = false;
            }
        }
    }

    /**
     * obtiene la estructura de la encuesta.
     * @see Structure
     */
    public void orderFields() {
        try {
            //this.aux = 0;
            //this.Structure.Structure = orderStructure(this.Structure.Structure);
            this.fieldsStructure = this.structure.structure;//new String[this.Structure.Structure.length][this.Structure.Structure[0].length];

            info = "";
            //orderList();
        } catch (Exception e) {
            toast(e.getMessage(), 1);
        }
    }

    public String[][] orderStructure(String[][] structure1) throws Exception {
        int order = 10;
        int form = 9;
        if (structure1.length > 0) {
            if (structure1[0].length < 0) {
                return null;
            }
        } else {
            return null;
        }
        String[][] structure2 = new String[structure1.length][structure1[0].length];
        String[] Assigned = new String[structure1.length];

        for (int x = 0; x < Assigned.length; x++) {
            Assigned[x] = "";
        }

        for (int c = 0; c < structure2.length; c++) {
            int ominAvailable = structure1.length;
            int fminAvailable = structure1.length;
            for (String[] forms_tmp : this.structure.forms) {
                if (Integer.parseInt(forms_tmp[0]) > fminAvailable) {
                    fminAvailable = Integer.parseInt(forms_tmp[0]);
                }
            }
            for (String[] structure_tmp : this.structure.structure) {
                if (Integer.parseInt(structure_tmp[0]) > ominAvailable) {
                    ominAvailable = Integer.parseInt(structure_tmp[0]);
                }
            }
            fminAvailable++;
            ominAvailable++;

            //Seek min form number
            for (String[] structure_tmp : structure1) {
                try {
                    if (Integer.parseInt(structure_tmp[form]) < fminAvailable) {
                        Boolean f = false;
                        for (int i = 0; i < Assigned.length; i++) {
                            if ((Assigned[i].equals(structure_tmp[0])))
                                f = true;
                        }
                        if (!(f)) {
                            fminAvailable = Integer.parseInt(structure_tmp[form]);
                        }
                    }
                } catch (Exception e) {
                }
            }
            //Seek min order number to orderArray
            for (String[] structure_tmp : structure1) {
                try {
                    if (Integer.parseInt(structure_tmp[order]) < ominAvailable) {
                        if (fminAvailable == Integer.parseInt(structure_tmp[form])) {
                            Boolean f = false;
                            for (int i = 0; i < Assigned.length; i++) {
                                if ((Assigned[i].equals(structure_tmp[0])))
                                    f = true;
                            }
                            if (!(f)) {
                                ominAvailable = Integer.parseInt(structure_tmp[order]);
                            }
                        }
                    }
                } catch (Exception e) {
                }
            }

            //take data from Structure and assign to new Structure
            for (String[] structure_tmp : structure1) {
                try {
                    if ((Integer.parseInt(structure_tmp[form]) == fminAvailable) &&
                            (Integer.parseInt(structure_tmp[order]) == ominAvailable)) {
                        structure2[c] = structure_tmp;
                        Assigned[c] = structure_tmp[0];
                    }
                } catch (Exception e) {
                }
            }
        }

        String[][] structure3 = new String[structure2.length][structure2[0].length];
        int i = 0;
        for (String form_tmp : this.structure.formsOrder) {
            for (String[] structure_tmp : structure2) {
                if (structure_tmp[9].equals(form_tmp)) {
                    structure3[i] = structure_tmp;
                    i++;
                }
            }
        }
        Log.e("length", structure2.length+"");

//        asignedId = "";
//        for (String asigned : Assigned) {
//            asignedId += asigned + "\n";
//        }
        return structure3;
    }

    /**
     * obtiene el orden lógico de la estructura
     * @return la estructura de la encuesta.
     * @throws Exception es lanzada si ocurre algo mal con la extracción de la estructura.
     */
    public String[][] orderStructureLogical() throws Exception {
        orderFields();
        return this.fieldsStructure;
    }

    /**
     * encuentra un manejador de evento para un objeto
     * @param id identificador del objeto
     * @param value valor que desencadena el evento
     * @return true si el manejador de eventos es encontrado, false en caso contrario.
     */
    public Boolean findEventMatch(int id, String value) {
        this.lastId = id;
        this.indexHiddenSubForms = new ArrayList<>();

        String[] handlers = {};
        int matches;
        for (String[] structure_tmp : this.structure.structure) {
            if (Integer.parseInt(structure_tmp[0]) == id) {
                String tmp = structure_tmp[13].trim();
                tmp = tmp.replace(" ", "");
                handlers = tmp.split(",");
            }
            if(this.structure.handlerEvent != null)
            for (String[] handler_tmp : this.structure.handlerEvent) {
                for (String handler : handlers) {
                    matches = 0;
                    if (handler.equals(handler_tmp[0])) {
                        String tmp = handler_tmp[1].trim();
                        tmp = tmp.replace(" ", "");
                        String[] types = tmp.split(",");
                        tmp = handler_tmp[2].trim();
                        tmp = tmp.replace("  ", " ");
                        String[] parameters = tmp.split(",");
                        for (int c = 0; c < types.length; c++) {
                            switch (types[c]) {
                                case "=":
                                    if (value.equals(parameters[c])) {
                                        matches++;
                                    }
                                    break;
                                case "!=":
                                    if (!(value.equals(parameters[c]))) {
                                        matches++;
                                    }
                                    break;
                                case ">":
                                    try {
                                        if (Integer.parseInt(value) > Integer.parseInt(parameters[c])) {
                                            matches++;
                                        }
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }
                                    break;
                                case "<":
                                    try {
                                        if (Integer.parseInt(value) < Integer.parseInt(parameters[c])) {
                                            matches++;
                                        }
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }
                                    break;
                            }
                        }
                        for (String[] form_tmp : this.structure.forms) {
                            if ((("" + id).equals(form_tmp[2]))) {
                                int c = 0;
                                for (String[] handlerJoiner : this.structure.fieldHandlerJoin) {
                                    if (handlerJoiner[2].equals(form_tmp[0])) {
                                        c++;
                                    }
                                }
                                if (c == 0)
                                    this.indexHiddenSubForms.add(form_tmp[0]);
                            } else if (handler.equals(form_tmp[4]) && (("" + id).equals(form_tmp[2]))) {
                                int c = 0;
                                for (String[] handlerJoiner : this.structure.fieldHandlerJoin) {
                                    if (handlerJoiner[2].equals(form_tmp[0])) {
                                        c++;
                                    }
                                }
                                if (c == 0)
                                    this.indexHiddenSubForms.add(form_tmp[0]);
                            }
                        }
                        //this.formHandled = "-1";
                        return matches == types.length && matches == parameters.length;
                    }
                }
            }
        }
        return false;
    }

    /**
     * valida el valor que desencadena el evento
     * @param value valor que desencadena el evento
     * @param handler_tmp listado de los nombres de los manejadores
     * @return true si la validación se dió de manera exitosa, false en caso contrario.
     */
    private boolean validateMatch(String value, String[] handler_tmp) {
        String tmp = handler_tmp[1].trim();
        tmp = tmp.replace(" ", "");
        String[] types = tmp.split(",");
        tmp = handler_tmp[2].trim();
        tmp = tmp.replace("  ", " ");
        String[] parameters = tmp.split(",");
        int matches = 0;
        for (int c = 0; c < types.length; c++) {
            switch (types[c]) {
                case "=":
                    if (value.equals(parameters[c])) {
                        matches++;
                    }
                    break;
                case "!=":
                    if (!(value.equals(parameters[c]))) {
                        matches++;
                    }
                    break;
                case ">":
                    try {
                        if (Integer.parseInt(value) > Integer.parseInt(parameters[c])) {
                            matches++;
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    break;
                case "<":
                    try {
                        if (Integer.parseInt(value) < Integer.parseInt(parameters[c])) {
                            matches++;
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    break;
            }
        }
        return matches == types.length && matches == parameters.length;
    }

    /**
     * encuentra el manejador de eeventos asignado a un objeto
     * @param id identificador del objeto
     * @param value valor que desencadena el evento.
     * @return true si se encuentra el objeto que desencadena el evento, false en caso contrario.
     */
    public Boolean findJoinMatchEvents(int id, String value) {
        this.formJoinedHandled = "";
        Boolean founded = false;
        try {
            int x = 0;
            for (String[] jh_tmp : this.structure.fieldHandlerJoin) {
                String[] fields = jh_tmp[0].split("~");
                String[] handlers = jh_tmp[1].split("~");
                int matches = 0;
                Boolean matched = false;
                for (int c = 0; c < fields.length; c++) {
                    if (fields[c].equals(id + "")) {
                        matched = true;
                        for (String[] handler_tmp : this.structure.handlerEvent) {
                            if (handler_tmp[0].equals(handlers[c])) {
                                this.mJoinHandler[x][c] = validateMatch(value, handler_tmp);
                            }
                        }
                    }
                    if (this.mJoinHandler[x][c]) {
                        matches++;
                    }
                    if (matched) {
                        if (matches == this.mJoinHandler[x].length) {
                            this.formJoinedHandled += (jh_tmp[2]) + ";s~";
                            founded = true;
                        } else {
                            this.formJoinedHandled += (jh_tmp[2]) + ";h~";
                        }
                    }
                }
                x++;
            }
            if (founded) {
                return true;
            }
        } catch (Exception e) {
            toast(e.getMessage(), 1);
        }
        return false;
    }

    /**
     * Obtiene el formulario que es estimulado por el evento
     * @return la estructura del formulario.
     */
    public String getFormJoinedHandled() {
        //toast(this.formJoinedHandled,1);
        return this.formJoinedHandled;
    }

    /**
     * obtine la plista del formulario que es estimulado por el evento.
     * @return ArrayList de los formularios ocultos.
     */
    public ArrayList getIndexHiddenSubForms() {
        return this.indexHiddenSubForms;
    }

    /**
     * valida un subformulario identificado con un id
     * @param id identificador del formulario
     * @return numero de que respresenta el estado de la validación, -1 si no se validó con exito.
     */
    public int validateSubForm(int id) {
        int sf = -1;
        this.formInsider = 0;
        for (String[] structure_tmp : this.structure.structure) {
            if (id == Integer.parseInt(structure_tmp[0])) {
                for (String[] form_tmp : this.structure.forms) {
                    if (structure_tmp[9].equals(form_tmp[0])) {
                        if (!(form_tmp[2].equals("0"))) {
                            try {
                                //toast(form_tmp[0],1);
                                this.parentField =
                                        Integer.parseInt(form_tmp[2]);
                                this.formInsider =
                                        Integer.parseInt(form_tmp[3]);
                                return Integer.parseInt(form_tmp[0]);
                            } catch (Exception e) {
                                toast("error", 1);
                                sf = -1;
                            }
                        }
                    }
                }
            }
        }
        return sf;
    }

    /**
     * Obtiene la posición de el padre del formulario
     * @return pocisión del formulario padre.
     */
    public int getParentIndex() {
        for (String[] structure_tmp : this.structure.structure) {
            if (structure_tmp[0].equals(this.parentField + "")) {
                int f = 0;
                for (String[] form_tmp : this.structure.forms) {
                    if (form_tmp[0].equals("0")) {
                        f--;
                    }
                    if (form_tmp[0].equals(structure_tmp[9])) {
                        //this.parentForm = Integer.parseInt(form_tmp[0]);
                        return f;
                    }
                    f++;
                }
            }
        }
        return 0;
    }

    /**
     * Guarda información del formulario actual en un archivo de preferencias
     * @param currentForm identificador del formulario en la estructura.
     * @param object matriz de objetos
     * @throws Exception es lanzada si ocurre un error al guardar los datos.
     */
    public void saveData(int currentForm, Object[][] object) throws Exception {
        this.started = true;
        for (Object[] object_tmp : object) {
            Boolean saveIt = false;
            try {
                if ((Integer.parseInt((String) object_tmp[6])) == currentForm) {
                    saveIt = true;
                }
            } catch (Exception err) {
                if ((Integer.parseInt((String) object_tmp[8])) == currentForm) {
                    saveIt = true;
                }
            }
            if (saveIt) {
                String value = "";
                if (object_tmp[0].getClass().equals(EditText.class)) {
                    value = ((EditText) object_tmp[0]).getText().toString();
                } else if (object_tmp[0].getClass().equals(RadioGroup.class)) {
                    for (int c = 0; c < ((RadioGroup) object_tmp[0]).getChildCount(); c++) {
                        View rb = ((RadioGroup) object_tmp[0]).getChildAt(c);
                        try {
                            if (rb.getClass().equals(RadioButton.class)) {
                                if (((RadioButton) rb).isChecked()) {
                                    value = ((RadioButton) rb).getText().toString();
                                }
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                } else if (object_tmp[0].getClass().equals(CheckBox.class)) {
                    value = ((CheckBox) object_tmp[0]).isChecked() + "";
                } else if (object_tmp[0].getClass().equals(Spinner.class)) {
                    if (!(((Spinner) object_tmp[0]).getSelectedItem().toString().equals("-")))
                        value = ((Spinner) object_tmp[0]).getSelectedItem() + "";
                } else if (object_tmp[0].getClass().equals(AutoCompleteTextView.class)) {
                    value = ((AutoCompleteTextView) object_tmp[0]).getText().toString();
                }
                if (!(value.equals(""))) {
                    try {
                        person.put(((String) object_tmp[4]), value);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            }
        }
        if (person.length() > 0) {
            if (this.event != 0) {
                wLocalFile();
            }
        }
    }

    /**
     * colapsa un formulario para seguir llenando datos de persona.
     * @param tmp JSONObject con los datos de la encuesta
     * @throws JSONException es lanzada si ocurre un error con el procesamiento del el objeto JSON.
     */
    public void collapse(JSONObject tmp) throws JSONException{
        Iterator keys = tmp.keys();
        while (keys.hasNext()){
            String key = keys.next().toString();
            person.remove(key);
            person.put(key, tmp.get(key));
        }
        if (this.event != 0)
            wLocalFile();
    }

    /**
     * guarda toda la información de la encuesta.
     * @param object matriz de objetos
     * @param homeCase true si es el caso de los parametros home
     * @param tmp JSONObject con los datos de la encuesta.
     */
    public void saveAllData(Object[][] object, Boolean homeCase, JSONObject tmp) {
        try {
            if (!homeCase) {
                try {
                    collapse(tmp);
                } catch (Exception e){
                    Log.e("Error", e.getMessage());
                }
            } else {
                for (Object[] obj_tmp : object) {
                    if ((obj_tmp[6]).equals("homeL")) {
                        saveData(Integer.parseInt((String) obj_tmp[8]), object);
                    }
                }
                if (!wLocalFile()) {
                    Log.e("Error", "no guardo");
                }
            }
        } catch (Exception e) {
            //toast(e.getMessage(), 1);
        }

    }

    /**
     * obtiene el fild joiner de un objeto en la estrructura de la encuesta
     * @param id identificador del objeto
     * @return el nombre del fieldjoiner.
     */
    public String calculateFieldJoiner(String id) {
        for (String[] fj_tmp : structure.fieldsJoiner) {
            if (id.equals(fj_tmp[1])) {
                String result;
                int index = 0;
                for (String[] structure_tmp : structure.structure) {
                    if (fj_tmp[2].equals(structure_tmp[0])) {
                        break;
                    }
                    index++;
                }
                result = index + "," + fj_tmp[3];
                return result;
            }
        }
        return null;
    }

    /**
     * muestra un resumen con  los datos de la encuesta llena.
     * @param tl TableLayout para mostras los datos de resumen
     * @param homeEnabled true si el parametro home esta habilitado.
     * @return el TableLayout para mostrar los datos de resumen.
     * @throws Exception es lanzada si ocurre un error al mostrar el resumen de la encuesta.
     */
    public TableLayout showSummary(TableLayout tl, Boolean homeEnabled) throws Exception {
        readLocalFile();
        LinearLayout.LayoutParams lp;
        if (this.context.getResources().getDisplayMetrics().widthPixels > 600) {
            lp = new LinearLayout.LayoutParams((this.context.getResources().getDisplayMetrics().widthPixels / 4) * 3, 30);
        } else {
            lp = new LinearLayout.LayoutParams(this.context.getResources().getDisplayMetrics().widthPixels - 2, 30);
        }

        lp.setMargins(0, 0, 0, 5);
        lp.width = ViewGroup.LayoutParams.MATCH_PARENT;
        lp.height = ViewGroup.LayoutParams.WRAP_CONTENT;

        this.person = new JSONObject(decodeLocalFile());
        if (event == 0)
            this.person = person.getJSONObject(constants.home);
        LinearLayout[] ln_tmp = new LinearLayout[this.structure.countPerson()];
        TextView[] tv_tmp = new TextView[this.structure.countPerson()];
        int c = 0;
        for (String[] field : this.structure.structure) {
            int he = 0;
            if (homeEnabled) {
                he = 0;//-999; for home enabled
            }
            if (Integer.parseInt(field[9]) != he) {
                try {
                    ln_tmp[c] = new LinearLayout(this.context);
                    tv_tmp[c] = new TextView(this.context);
                    tv_tmp[c].setBackgroundResource(R.drawable.textview_1);
                    if ((!person.getString(field[1]).equals("")) &&
                            (!person.getString(field[1]).equals("-")) &&
                            (!person.getString(field[1]).equals("null"))) {
                        tv_tmp[c].setTextColor(Color.BLACK);
                        tv_tmp[c].setGravity(Gravity.LEFT);
                        String txt = ((field[2].equals("")) ? field[1] : field[2]);
                        String val = this.person.getString(field[1]);
                        val = ((val.equals("false")) ? "No." : val);
                        val = ((val.equals("true")) ? "Si." : val);
                        tv_tmp[c].setText(txt + ":    " + val);
                        //tv_tmp[c].setHeight(55);
                        tv_tmp[c].setLayoutParams(lp);
                        ln_tmp[c].addView(tv_tmp[c]);
                        tl.addView(ln_tmp[c]);
                        ln_tmp[c].setLayoutParams(lp);
                        ln_tmp[c].setPadding(15, 0, 0, 0);
                        ln_tmp[c].setGravity(Gravity.LEFT);
                        c++;
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }
        tl.setLayoutParams(new LinearLayout.LayoutParams(this.context.getResources().getDisplayMetrics().widthPixels, -2));
        tl.setGravity(Gravity.CENTER_HORIZONTAL);
        return tl;
    }

    /**
     * Busca o crea un archivo local
     * @return true si el archivo es encontrado o creado con exito, false en caso contrario.
     */
    public Boolean focLocalFile() {
        try {
            FileOutputStream fos = (this.context.openFileOutput(this.target, Context.MODE_PRIVATE));
            this.osw = new OutputStreamWriter(fos);
            //this.osw.close();
            return true;
        } catch (Exception e) {
            //toast("no escribio archivo", 0);
            return false;
        }
    }

    /**
     * Escribe en un archivo local
     * @return true si los datos fueron escritos con exito, fase en caso contrario.
     */
    public Boolean wLocalFile() {
        //if(true) return true;
        try {
            if (!fioLocalFile()) {
                focLocalFile();
            }
            if ((this.event == 0) || (this.event == 2)) {
                try {
                    //person.getJSONObject(Constants.home);
                    try {
                        Master.length();
                    } catch (Exception e){
                        Master = new JSONObject();
                    }
                    this.Master.put(constants.home, person);
                    //this.person = this.Master;
                    osw.write(this.Master.toString());
                    osw.close();
                    return true;
                } catch (Exception e) {
                    return false;
                }
            }
            if (action == 1) {
                person.put(constants.DataEdited, true);
            }
            osw.write(person.toString());
            osw.close();
            return true;
        } catch (Exception e) {
            //(e.getMessage(), 1);
            return false;
        }
    }

    /**
     * lee datos en el archivo local
     * @return true si lee los datos con exito, false en caso contrario.
     */
    public Boolean readLocalFile() {
        try {
            InputStreamReader isr = new InputStreamReader(this.context.openFileInput(target));
            this.br = new BufferedReader(isr);
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    /**
     * busca si el archivo local está abierto.
     * @return true si el archivo local esta abierto, false en caso contrario.
     */
    public Boolean fioLocalFile() {
        try {
            osw.append("");
            return true;
        } catch (Exception e) {
            return focLocalFile();
        }
    }

    /**
     * establece los datos de una persona
     * @throws Exception es lanzada si ocurre un error el procesamiento del objeto JSON.
     */
    public void setPerson() throws Exception {
        try {
            this.person = new JSONObject(decodeLocalFile());
        } catch (Exception e) {
            this.person = new JSONObject();
        }
        if (this.event == 0) {
            this.Master = this.person;
            try {
                this.person = this.Master.getJSONObject("HOME");
            } catch (Exception e) {
                this.person = new JSONObject();
            }
        }
    }

    /**
     * establece el atributo preloaded.
     * @param preloaded atributo preloaded.
     */
    public void setPreloaded(String[] preloaded) {
        this.preloaded = preloaded;
        this.personStr = this.structure.getPerson(3);
    }

    /**
     * establece el tipo de acción
     * @param action tipo de acción
     */
    public void setAction(int action) {
        this.action = action;
    }

    /**
     * obtiene los forms contenidos en otros.
     * @return
     */
    public int getFormInsider() {
        return this.formInsider;
    }

    /**
     * establece el objeto target
     * @param target identificador del objeto.
     */
    public void setTarget(String target) {
        this.target = target;
    }

    /**
     * establece el evento
     * @param event identificador del evento
     */
    public void setEvent(int event) {
        this.event = event;
    }

    /**
     * decodifica la información del archivo local
     * @return la información decodificada en el archivo local.
     * @throws IOException
     */
    private String decodeLocalFile() throws IOException {
        String line;
        StringBuilder sb = new StringBuilder();
        while ((line = br.readLine()) != null) {
            sb.append(line.trim());
        }
        return sb.toString();
    }

    /**
     * muestra una tostada
     * @param msj mensaje a mostrar
     * @param tCase caso a mostrar.
     */
    public void toast(String msj, int tCase) {
        //if (tCase == 1)
            //Toast.makeText(this.context, msj, Toast.LENGTH_SHORT).show();
        //else
            //Toast.makeText(this.context, msj, Toast.LENGTH_LONG).show();
    }
}