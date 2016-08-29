package com.miido.analiizoOBRAS.util;

import com.miido.analiizoOBRAS.model.DynamicJoiner;
import com.miido.analiizoOBRAS.model.Field;
import com.miido.analiizoOBRAS.model.Form;
import com.miido.analiizoOBRAS.model.Handler;
import com.miido.analiizoOBRAS.model.Options;

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

    enum JPoll{
        FORMS("forms"),OPTIONS("options"),HANDLERS("handler_event"),FIELDS("fields_structure"),
        DYNAMIC_JOINER("dynamicJoiner");
        private final String code;
        JPoll(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public JsonUtil(JSONObject structure)throws JSONException{
        forms = structure.getJSONArray(JPoll.FORMS.code);
        fields = structure.getJSONArray(JPoll.FIELDS.code);
        options = structure.getJSONArray(JPoll.OPTIONS.code);
        handlers = structure.getJSONArray(JPoll.HANDLERS.code);
        dynamicJoiners = structure.getJSONArray(JPoll.DYNAMIC_JOINER.code);
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

    public JSONObject getSubForm(int fieldId) throws JSONException{
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getInt(Form.JForm.INSIDE.code()) != 0 && form.getInt(Form.JForm.PARENT.code()) == fieldId){
                return form;
            }
        }
        return null;
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

    private JSONObject getFieldById(String fieldId)throws JSONException{
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            if(field.getString(Field.JField.ID.code()).equals(fieldId)){
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

    public JSONObject getDynamicJoiner(int fieldId)throws JSONException{
        for(int i = 0; i < dynamicJoiners.length(); i++){
            JSONObject dynamicJoiner = dynamicJoiners.getJSONObject(i);
            if(dynamicJoiner.getInt(DynamicJoiner.JDynamic.FIELD.code()) == fieldId){
                return dynamicJoiner;
            }
        }
        return null;
    }

}
