package com.miido.analiizo.util;

import android.app.Activity;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.util.Log;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.DynamicJoiner;
import com.miido.analiizo.model.Field;
import com.miido.analiizo.model.FieldJoiner;
import com.miido.analiizo.model.Form;
import com.miido.analiizo.model.FormsLayouts;
import com.miido.analiizo.model.Handler;
import com.miido.analiizo.model.Options;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by User on 20/05/2016.
 */
public class JsonUtil {

    private JSONArray forms;
    private JSONArray fields;
    private JSONArray options;
    private JSONArray handlers;
    private JSONArray dynamicJoiners;
    private JSONArray fieldsJoiners;

    private Activity context;
    private long structureId;
    private JSONObject structure;

    private int type;

    enum JPoll{
        FORMS("forms"),OPTIONS("options"),HANDLERS("handler_event"),FIELDS("fields_structure"),
        DYNAMIC_JOINER("dynamicJoiner"),HANDLER_FIELD_JOINER("HandlerFieldJoiner"),FIELDS_JOINER("fieldsJoiner");
        private final String code;
        JPoll(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    /*public JsonUtil(JSONObject structure, int type)throws JSONException{
        this.structure = structure;
        init(type);
    }*/

    public JsonUtil(Activity context, long structureId, int type)throws JSONException,SQLiteException{
        this.context = context;
        this.structureId = structureId;
        this.type = type;
        structure = getPollStructureFromDB(structureId);
        init(type);
    }

    private void init(int type)throws JSONException{

        if (FormsLayouts.PollType.PERSON.code() == type) {
            forms = getPersonForms();
        }else{
            if(FormsLayouts.PollType.SATISFACTION.code() == type){
                forms = getHomeInfoForms();
            }else{
                forms = structure.getJSONArray(JPoll.FORMS.code);
            }
        }

        fields = structure.getJSONArray(JPoll.FIELDS.code);
        options = structure.getJSONArray(JPoll.OPTIONS.code);
        handlers = structure.getJSONArray(JPoll.HANDLERS.code);
        if(structure.has(JPoll.DYNAMIC_JOINER.code)){
            dynamicJoiners = structure.getJSONArray(JPoll.DYNAMIC_JOINER.code);
        }else{
            dynamicJoiners = structure.getJSONArray(JPoll.HANDLER_FIELD_JOINER.code);
        }
        if(structure.has(JPoll.FIELDS_JOINER.code)){
            fieldsJoiners = structure.getJSONArray(JPoll.FIELDS_JOINER.code);
        }else{
            fieldsJoiners = new JSONArray();
        }
    }

    private JSONArray getPersonForms()throws JSONException{
        JSONArray allForms = structure.getJSONArray(JPoll.FORMS.code);
        JSONArray personForms = new JSONArray();
        for(int i = 0; i < allForms.length(); i++){
            JSONObject form = allForms.getJSONObject(i);
            if(form.getInt(Form.JForm.ID.code()) != 0){
                personForms.put(form);
            }
        }
        return personForms;
    }

    private JSONArray getHomeInfoForms()throws JSONException{
        JSONArray allForms = structure.getJSONArray(JPoll.FORMS.code);
        JSONArray personForms = new JSONArray();
        for(int i = 0; i < allForms.length(); i++){
            JSONObject form = allForms.getJSONObject(i);
            if(form.getInt(Form.JForm.CLONEABLE.code()) == 1){
                // los formularios de satisfacción y vivienda tienen la propiedad inside en 1 para ocultarlos
                form.put(Form.JForm.INSIDE.code(),"0"); // Aquí se modifica la propiedad inside en 0 para que sean visibles
                personForms.put(form);
            }else{
                if(form.getInt(Form.JForm.CLONEABLE.code()) == 2){
                    personForms.put(form);
                }
            }
        }
        return personForms;
    }

    /**
     * Obtiene desde la base de datos local del dispositivo el objeto JSon que representa la estructura de la encuesta.
     * @param structureId identificador unico de la encuesta.
     * @return objeto Json con la estructura de la encuesta.
     * @throws JSONException es lanzada si ocurre algun error al obtener llaves del JSONObject
     * @throws SQLiteException es lanzada si ocurre algun error al ejecutar la consulta sql.
     */
    private JSONObject getPollStructureFromDB(long structureId) throws JSONException,SQLiteException {
        SqlHelper sqlHelper = new SqlHelper(context);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.structureDatabase;
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

    public JSONArray getFrontForms()throws JSONException{
        JSONArray tmpForms = new JSONArray();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getString(Form.JForm.INSIDE.code()).equals("0")){
                tmpForms.put(form);
            }
        }
        return tmpForms;
    }

    public JSONArray getFormFields(int formId) throws JSONException{
        JSONArray tmpFields = new JSONArray();
        for( int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getInt(Field.JField.FORM.code()) == formId){
                tmpFields.put(field);
            }
        }
        return tmpFields;
    }

    public JSONArray getSubForms(int fieldId) throws JSONException{
        JSONArray subForms = new JSONArray();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getInt(Form.JForm.INSIDE.code()) == 1 && form.getInt(Form.JForm.PARENT.code()) == fieldId){
                subForms.put(form);
            }
        }
        return subForms;
    }

    public JSONObject getForm(int formId) throws JSONException{
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getInt(Form.JForm.ID.code()) == formId){
                return form;
            }
        }
        return null;
    }

    private JSONObject getFieldByName(String fieldName)throws JSONException{
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString(Field.JField.NAME.code()).equals(fieldName)){
                return field;
            }
        }
        return null;
    }

    public JSONObject getFieldById(int fieldId)throws JSONException{
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getInt(Field.JField.ID.code()) == fieldId){
                return field;
            }
        }
        return null;
    }

    public JSONObject getOption(int fieldId) throws JSONException{
        for(int i = 0; i < options.length(); i++){
            JSONObject option = options.getJSONObject(i);
            JSONArray fields = option.getJSONArray(Options.JOptions.FIELDS.code());
            for( int o = 0; o < fields.length(); o++){
                if(fields.getInt(o) == fieldId){
                    return option;
                }
            }
        }
        return null;
    }

    public JSONObject getHandler(int handlerId)throws JSONException{
        for(int i = 0; i < handlers.length(); i++){
            JSONObject handler = handlers.getJSONObject(i);
            if(handler.getInt(Handler.JHandler.ID.code()) == handlerId){
                return handler;
            }
        }
        return null;
    }

    public JSONArray getHandlersFieldJoiner(int fieldId)throws JSONException{
        JSONArray fieldsJoiner = new JSONArray();
        for(int i = 0; i < dynamicJoiners.length(); i++){
            JSONObject fieldJoiner = dynamicJoiners.getJSONObject(i);
            JSONArray fieldIds = fieldJoiner.getJSONArray(DynamicJoiner.JDynamic.FIELD_IDS.code());
            for(int j = 0; j < fieldIds.length(); j++){
                if(fieldId == fieldIds.getInt(j)){
                    fieldsJoiner.put(fieldJoiner);
                }
            }
        }
        return fieldsJoiner;
    }

    public JSONArray getHandlersFieldJoiner() throws JSONException{
        return dynamicJoiners;
    }

    public JSONObject getFieldJoiner(int idField)throws JSONException{
        for(int i = 0; i < fieldsJoiners.length(); i++){
            JSONObject fieldJoiner = fieldsJoiners.getJSONObject(i);
            if(idField == fieldJoiner.getInt(FieldJoiner.Json.ID_FROM.code())){
                return fieldJoiner;
            }
        }
        return null;
    }

    public JSONArray getDynamicJoiner(int fieldId)throws JSONException{
        JSONArray dynamicsJoiner = new JSONArray();
        for(int i = 0; i < dynamicJoiners.length(); i++){
            JSONObject dynamicJoiner = dynamicJoiners.getJSONObject(i);
            if(dynamicJoiner.has(DynamicJoiner.JDynamic.FIELD.code())) {
                if (dynamicJoiner.getInt(DynamicJoiner.JDynamic.FIELD.code()) == fieldId) {
                    dynamicsJoiner.put(dynamicJoiner);
                }
            }else{
                if(dynamicJoiner.has(DynamicJoiner.JDynamic.FIELD_IDS.code())){
                    JSONArray idFields = dynamicJoiner.getJSONArray(DynamicJoiner.JDynamic.FIELD_IDS.code());
                    JSONArray idHandlers = dynamicJoiner.getJSONArray(DynamicJoiner.JDynamic.HANDLER_IDS.code());
                    if(idFields.length() == idHandlers.length()) {
                        for (int j = 0; j < idFields.length(); j++) {
                            if (fieldId == idFields.getInt(j)) {
                                dynamicsJoiner.put(new JSONObject().
                                        put(DynamicJoiner.JDynamic.FIELD.code(),idFields.get(j))
                                        .put(DynamicJoiner.JDynamic.HANDLER.code(),idHandlers.getInt(j))
                                        .put(DynamicJoiner.JDynamic.FORM.code(),dynamicJoiner.getInt(DynamicJoiner.JDynamic.TARGET_FORM.code())));
                            }
                        }
                    }else{
                        Log.e(getClass().getName(), "Field.Length != Handler.Length");
                    }
                }
            }
        }
        return dynamicsJoiner;
    }

    public long getStructureId() {
        return structureId;
    }

    public void setStructureId(long structureId) {
        this.structureId = structureId;
    }

    public JSONObject getStructure() {
        return structure;
    }

    public void setStructure(JSONObject structure) {
        this.structure = structure;
    }
}
