package com.miido.miido.mcompose;

import android.content.Context;
import android.database.Cursor;
import android.widget.Toast;

import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;
import org.json.JSONObject;

/**
 * *******************************
 * Created by Alvaro on 18/02/2015.
 * *******************************
 */
public class structure {

    public String[][] structure;
    public String[][] options;
    public String[][] forms;
    public String[] formsOrder;
    public String[][] handlerEvent;
    public String[][] fieldHandlerJoin;
    public String[][] fieldsJoiner;
    public String[][] aditionalFieldsRules;
    public JSONArray dynamicJoiner;
    public String[] documentInfo;
    public JSONObject jStructure;
    private constants constants;
    private Context context;

    private String extras;

    public structure(Context context) {
        this.constants = new constants();
        this.context = context;
        try {
            this.jStructure = getStructure();
            //this.jStructure = new JSONObject(constants.structure_1+constants.structure_2);
        } catch (Exception e) {
            Toast.makeText(this.context, e.getMessage(), Toast.LENGTH_SHORT).show();
        }
    }

    private JSONObject getStructure() throws Exception {
        sqlHelper sqlHelper = new sqlHelper(this.context);
        sqlHelper.OOCDB();
        sqlHelper.setQuery(this.constants.QUERY_6);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        return (new JSONObject(cursor.getString(0)));
    }

    public void setStructure() {
        try {
            setVariables();
        } catch (Exception e) {
            Toast.makeText(this.context, e.toString(), Toast.LENGTH_LONG).show();
        }
    }

    public void setVariables() throws Exception {
        JSONArray jArray;
        //fields_structure
        jArray = jStructure.getJSONArray("fields_structure");
        try {
            if (jArray.length() > 0) {
                this.structure = new String[jArray.length()][jArray.getJSONObject(0).length() + 1];
                for (int c = 0; c < jArray.length(); c++) {
                    this.structure[c][0] = jArray.getJSONObject(c).getString("Id");
                    this.structure[c][1] = jArray.getJSONObject(c).getString("Name");
                    this.structure[c][2] = jArray.getJSONObject(c).getString("Label");
                    this.structure[c][3] = jArray.getJSONObject(c).getString("Type");
                    this.structure[c][4] = jArray.getJSONObject(c).getString("Required");
                    this.structure[c][5] = jArray.getJSONObject(c).getString("Hint");
                    this.structure[c][6] = jArray.getJSONObject(c).getString("Rules");
                    this.structure[c][7] = jArray.getJSONObject(c).getString("Length");
                    this.structure[c][8] = jArray.getJSONObject(c).getString("Parent");
                    this.structure[c][9] = jArray.getJSONObject(c).getString("Form");
                    this.structure[c][10] = jArray.getJSONObject(c).getString("Order");
                    this.structure[c][11] = jArray.getJSONObject(c).getString("ReadOnly");
                    this.structure[c][12] = jArray.getJSONObject(c).getString("AutoComplete");
                    this.structure[c][13] = jArray.getJSONObject(c).getString("Handler");
                    this.structure[c][14] = jArray.getJSONObject(c).getString("FreeAdd");
                }
            }

            //Options
            jArray = jStructure.getJSONArray("options");
            if (jArray.length() > 0) {
                this.options = new String[jArray.length()][jArray.getJSONObject(0).length() + 1];
                for (int c = 0; c < jArray.length(); c++) {
                    this.options[c][0] = jArray.getJSONObject(c).getString("Id");
                    JSONArray jArray_tmp = jArray.getJSONObject(c).getJSONArray("Field");
                    this.options[c][1] = "";
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.options[c][1] += jArray_tmp.getString(i) + "~";
                    }
                    jArray_tmp = jArray.getJSONObject(c).getJSONArray("Options");
                    this.options[c][2] = "";
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.options[c][2] += jArray_tmp.getString(i) + "~";
                    }
                    this.options[c][1] = this.options[c][1].substring(0, this.options[c][1].length() - 1);
                    this.options[c][2] = this.options[c][2].substring(0, this.options[c][2].length() - 1);
                }
            }

            //forms
            jArray = jStructure.getJSONArray("forms");
            if (jArray.length() > 0) {
                this.forms = new String[jArray.length()][jArray.getJSONObject(0).length() + 1];
                for (int c = 0; c < jArray.length(); c++) {
                    this.forms[c][0] = jArray.getJSONObject(c).getString("Id");
                    this.forms[c][1] = jArray.getJSONObject(c).getString("Header");
                    this.forms[c][2] = jArray.getJSONObject(c).getString("Parent");
                    this.forms[c][3] = jArray.getJSONObject(c).getString("Inside");
                    this.forms[c][4] = jArray.getJSONObject(c).getString("Handler");
                    this.forms[c][5] = jArray.getJSONObject(c).getString("Clonable");
                }
            }

            //forms_order
            jArray = jStructure.getJSONArray("forms_order");
            if (jArray.length() > 0) {
                this.formsOrder = new String[jArray.length()];
                for (int c = 0; c < jArray.length(); c++) {
                    this.formsOrder[c] = jArray.getJSONObject(c).getString("Id");
                }
            }


            //handler_event
            jArray = jStructure.getJSONArray("handler_event");
            if (jArray.length() > 0) {
                this.handlerEvent = new String[jArray.length()][jArray.getJSONObject(0).length() + 1];
                for (int c = 0; c < jArray.length(); c++) {
                    this.handlerEvent[c][0] = jArray.getJSONObject(c).getString("Id");
                    JSONArray jArray_tmp = jArray.getJSONObject(c).getJSONArray("Types");
                    this.handlerEvent[c][1] = "";
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.handlerEvent[c][1] += jArray_tmp.getString(i) + ",";
                    }
                    jArray_tmp = jArray.getJSONObject(c).getJSONArray("Parameters");
                    this.handlerEvent[c][2] = "";
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.handlerEvent[c][2] += jArray_tmp.getString(i) + ",";
                    }
                    this.handlerEvent[c][1] = this.handlerEvent[c][1].substring(0, this.handlerEvent[c][1].length() - 1);
                    this.handlerEvent[c][2] = this.handlerEvent[c][2].substring(0, this.handlerEvent[c][2].length() - 1);
                }
            }

            //Fields Handler Joiner
            jArray = jStructure.getJSONArray("HandlerFieldJoiner");
            if (jArray.length() > 0) {
                this.fieldHandlerJoin = new String[jArray.length()][jArray.getJSONObject(0).length() + 1];
                for (int c = 0; c < jArray.length(); c++) {
                    JSONArray jArray_tmp = jArray.getJSONObject(c).getJSONArray("idFields");
                    this.fieldHandlerJoin[c][0] = "";
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.fieldHandlerJoin[c][0] += jArray_tmp.getString(i) + "~";
                    }
                    this.fieldHandlerJoin[c][1] = "";
                    jArray_tmp = jArray.getJSONObject(c).getJSONArray("idHandlers");
                    for (int i = 0; i < jArray_tmp.length(); i++) {
                        this.fieldHandlerJoin[c][1] += jArray_tmp.getString(i) + "~";
                    }
                    this.fieldHandlerJoin[c][0] = this.fieldHandlerJoin[c][0].substring(0, this.fieldHandlerJoin[c][0].length() - 1);
                    this.fieldHandlerJoin[c][1] = this.fieldHandlerJoin[c][1].substring(0, this.fieldHandlerJoin[c][1].length() - 1);
                    this.fieldHandlerJoin[c][2] = jArray.getJSONObject(c).getString("TargetForm");
                }
            }

            //Fields Joiner
            jArray = jStructure.getJSONArray("fieldsJoiner");
            this.fieldsJoiner = new String[jArray.length()][jArray.getJSONObject(0).length()];
            for (int c = 0; c < jArray.length(); c++) {
                this.fieldsJoiner[c][0] = jArray.getJSONObject(c).getString("Id");
                this.fieldsJoiner[c][1] = jArray.getJSONObject(c).getString("IdFrom");
                this.fieldsJoiner[c][2] = jArray.getJSONObject(c).getString("IdTo");
                this.fieldsJoiner[c][3] = jArray.getJSONObject(c).getString("Method");
            }

            //Aditional Rules
            jArray = jStructure.getJSONArray("AditionalFieldsRules");
            this.aditionalFieldsRules = new String[jArray.length()][jArray.getJSONObject(0).length()];
            for (int c = 0; c < jArray.length(); c++) {
                JSONArray jArray_tmp = jArray.getJSONObject(c).getJSONArray("Fields");
                this.aditionalFieldsRules[c][0] = "";
                for (int i = 0; i < jArray_tmp.length(); i++) {
                    this.aditionalFieldsRules[c][0] += jArray_tmp.getString(i) + "~";
                }
                this.aditionalFieldsRules[c][0] = aditionalFieldsRules[c][0].substring(1,
                        aditionalFieldsRules[c][0].length());
                this.aditionalFieldsRules[c][1] = jArray.getJSONObject(c).getString("Rule");
            }

            dynamicJoiner = jStructure.getJSONArray("dynamicJoiner");

            JSONObject jObject = jStructure.getJSONObject("Document_info");
            this.documentInfo = new String[jObject.length()];
            documentInfo[0] = jObject.getInt("structureVersion") + "";
            documentInfo[1] = jObject.getString("minVersionName");
            documentInfo[2] = jObject.getString("currentAppVersion");
            documentInfo[3] = jObject.getInt("structureStatus") + "";
        } catch (Exception e) {
            //Toast.makeText(this.context, "Bad Structure::"+w, Toast.LENGTH_LONG).show();
        }
    }

    public String[][] getHome(int top) {
        try {
            if (top > this.structure[0].length)
                return null;
        } catch (Exception e) {
            return null;
        }
        String[][] tmp = new String[countHome()][top];
        int c = 0;
        for (String[] tmp2 : structure) {
            if (tmp2[9].equals("0")) {
                System.arraycopy(tmp2, 0, tmp[c], 0, top);
                c++;
            }
        }
        return tmp;
    }

    public String[][] getPerson(int top) {
        try {
            if (top > this.structure[0].length)
                return null;
        } catch (Exception e) {
            return null;
        }
        String[][] tmp = new String[countPerson()][top];
        int c = 0;
        for (String[] tmp2 : structure) {
            if (!tmp2[9].equals("0")) {
                System.arraycopy(tmp2, 0, tmp[c], 0, top);
                c++;
            }
        }
        return tmp;
    }

    public int countHome() {
        int c = 0;
        for (String[] tmp : structure) {
            if (tmp[9].equals("0")) {
                c++;
            }
        }
        return c;
    }

    public int countPerson() {
        int c = 0;
        for (String[] tmp : structure) {
            if (!tmp[9].equals("0")) {
                c++;
            }
        }
        return c;
    }

    public String validateStructure() throws Exception {
        String errors = "";
        for (String[] structure_tmp : this.structure) {
            if (findObjectFormError(structure_tmp[9])) {
                errors += ("FE::Formulario " + structure_tmp[9] + " no encontrado [objeto:" + structure_tmp[0] + "]\n");
            }
            if (findTypeError(structure_tmp[3])) {
                errors += ("FE::Tipo " + structure_tmp[3] + " incorrecto [objeto:" + structure_tmp[0] + "]\n");
            }
            if (structure_tmp[3].equals(this.constants.allowedTypes[0])) {
                if (findRulesError(structure_tmp[6])) {
                    errors += ("FE::Regla " + structure_tmp[6] + " incorrecta [objeto:" + structure_tmp[0] + "]\n");
                }
            }
            if (findHandlerError(structure_tmp[13])) {
                errors += ("FE::Controlador " + structure_tmp[13] + " no encontrado [objeto:" + structure_tmp[0] + "]" + this.extras + "\n");
            }
            if (findNameError(structure_tmp[1])) {
                errors += ("FE::Error en el nombre de objeto " + structure_tmp[1] + " duplicado [objeto:" + structure_tmp[0] + "]\n");
            }
            if (findIdError(structure_tmp[0])) {
                errors += ("FE::Error en el identificador " + structure_tmp[0] + " [objeto:" + structure_tmp[0] + "]\n");
            }
            if (findLengthError(structure_tmp[7])) {
                errors += ("FE::Error en longitud " + structure_tmp[7] + " [objeto:" + structure_tmp[0] + "]" + this.extras + "\n");
            }
            if (findRequiredError(structure_tmp[4])) {
                errors += ("FE::Error en requerido " + structure_tmp[4] + " [objeto:" + structure_tmp[0] + "]\n");
            }
        }
        for (String[] options_tmp : this.options) {
            if (findOptionsFieldsError(options_tmp[0])) {
                errors += ("OE::Error de relacion de campos " + options_tmp[1].replace("~", ", ") + " [objeto:" + options_tmp[0] + "]" + this.extras + "\n");
            }
            if (findOptionsIdError(options_tmp[0])) {
                errors += ("OE::Error en el identificador " + options_tmp[0] + " [objeto:" + options_tmp[0] + "]\n");
            }
            if (findOptionsLengthError(options_tmp[2])) {
                errors += ("OE::Error en la cantidad de optiones " + options_tmp[0] + " debe ser mayor a 0 [objeto:" + options_tmp[0] + "]\n");
            }
        }
        for (String[] form_tmp : this.forms) {
            if (findFormParentError(form_tmp[2])) {
                errors += ("EE::Error en el padre " + form_tmp[2] + " no encontrado [objeto:" + form_tmp[0] + "]\n");
            }
            if ((Integer.parseInt(form_tmp[2]) == 0) && (Integer.parseInt(form_tmp[3]) != 0)) {
                errors += ("EE::Error en insider: Si el formulario no tiene padre no puede ser inside [objeto:" + form_tmp[0] + "]\n");
            }
            if (findEmptyForms(form_tmp[0])) {
                errors += ("EE::Error de contenido: Formulario vacio [objeto:" + form_tmp[0] + "]\n");
            }
        }
        if (errors.length() == 0)
            errors = "No se encontraron errores";
        return errors;
    }

    public Boolean findObjectFormError(String form) {
        for (String[] form_tmp : this.forms) {
            if (form_tmp[0].equals(form)) {
                if (form.equals("0")) {
                    return false;
                } else {
                    return findFormOrderError(form);
                }
            }
        }
        return true;
    }

    public Boolean findFormOrderError(String form) {
        for (String formOrder_tmp : this.formsOrder) {
            if (formOrder_tmp.equals(form)) {
                return false;
            }
        }
        return true;
    }

    public Boolean findTypeError(String type) {
        for (String type_tmp : this.constants.allowedTypes) {
            if (type_tmp.equals(type)) {
                return false;
            }
        }
        return true;
    }

    public Boolean findRulesError(String rule) {
        for (String rule_tmp : this.constants.allowedRules) {
            if (rule_tmp.equals(rule)) {
                return false;
            }
        }
        return true;
    }

    public Boolean findHandlerError(String handler) {
        if (handler.equals("0"))
            return false;
        String[] handlers = handler.split(",");
        int cHandler = 0;
        for (String[] handler_tmp : this.handlerEvent) {
            for (String handlers_tmp : handlers) {
                if (handler_tmp[0].equals(handlers_tmp)) {
                    cHandler++;
                }
            }
        }
        if (cHandler == handlers.length) {
            return false;
        }
        this.extras = ("(Handlers buscados=" + handlers.length + ". Handlers encontrados=" + cHandler + ")");
        return true;
    }

    public Boolean findNameError(String name) {
        int f = 0;
        for (String[] structure_tmp : this.structure) {
            if (structure_tmp[1].equals(name)) {
                f++;
            }
        }
        return f != 1;
    }

    public Boolean findIdError(String id) {
        int tymes = 0;
        for (String[] structure_tmp : this.structure) {
            if (structure_tmp[0].equals(id)) {
                tymes++;
            }
        }
        return tymes != 1;
    }

    public Boolean findLengthError(String length) {
        try {
            if (Integer.parseInt(length) < 0) {
                this.extras = "(Length no puede ser menor a 0)";
                return true;
            }
            return false;
        } catch (Exception e) {
            this.extras = "(Error::" + e.getMessage() + ")";
            return true;
        }
    }

    @SuppressWarnings("All")
    public Boolean findRequiredError(String required) {
        try {
            Boolean.parseBoolean(required);
            return false;
        } catch (Exception e) {
            return true;
        }
    }

    public Boolean findOptionsFieldsError(String id) {
        for (String[] options_tmp : this.options) {
            if (options_tmp[0].equals(id)) {
                options_tmp[1] = options_tmp[1].replace("\"", "");
                String[] fields_tmp = options_tmp[1].split("~");
                int f = 0;
                int b;
                String rs = "";
                for (String field_tmp : fields_tmp) {
                    b = f;
                    for (String[] strucuture_tmp : this.structure) {
                        if (field_tmp.equals(strucuture_tmp[0])) {
                            f++;
                        }
                    }
                    if (b == f) {
                        rs += field_tmp + ", ";
                    }
                }
                if (f == fields_tmp.length) {
                    return false;
                } else {
                    this.extras = "(Campos encontrados:" + f + " - Campos buscados:" + fields_tmp.length + ", Campos no encontrados : " + rs + ")";
                }
            }
        }
        return true;
    }

    public Boolean findOptionsIdError(String id) {
        int f = 0;
        for (String[] options_tmp : this.options) {
            if (options_tmp[0].equals(id)) {
                f++;
            }
        }
        return f != 1;
    }

    public Boolean findOptionsLengthError(String options) {
        options = options.replace("\"", "");
        String[] options_tmp = options.split("~");
        return options_tmp.length <= 0;
    }

    public Boolean findFormParentError(String parent) {
        if (parent.equals("0"))
            return false;
        for (String[] structure_tmp : this.structure) {
            if (structure_tmp[0].equals(parent)) {
                return false;
            }
        }
        return true;
    }

    public Boolean findEmptyForms(String id) {
        for (String[] structure_tmp : this.structure) {
            if (structure_tmp[9].equals(id)) {
                return false;
            }
        }
        return true;
    }
}
