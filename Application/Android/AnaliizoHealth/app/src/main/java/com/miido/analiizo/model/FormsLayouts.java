package com.miido.analiizo.model;

import android.app.Activity;
import android.os.Parcel;
import android.os.Parcelable;
import android.util.Log;

import com.miido.analiizo.util.JsonUtil;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * @author Ing. Miguel Angel Urago Blanco MIIDO S.A.S on 06/05/2016.
 */
public class FormsLayouts implements Parcelable{

    private ArrayList<FormLayout> forms;
    private Activity context;
    private JsonUtil jsonUtil;

    public enum PollType{
        COMPLETE(1),PERSON(2), SATISFACTION(3);
        private final int code;
        PollType(int code){
            this.code = code;
        }
        public int code(){
            return this.code;
        }
    }

    /****************
     * Constructores *
     ****************/

    public FormsLayouts(){
        forms = new ArrayList<>();
    }

    public FormsLayouts(Activity context,long structureId, int pollType)throws JSONException{
        this.context = context;
        forms = new ArrayList<>();
        jsonUtil = new JsonUtil(context,structureId, pollType);
        parse();
    }

    protected FormsLayouts(Parcel in) {
        forms = in.createTypedArrayList(FormLayout.CREATOR);
    }

    /********************************************
     * Implementacion de la interfaz Parcelable *
     ********************************************/

    public static final Creator<FormsLayouts> CREATOR = new Creator<FormsLayouts>() {
        @Override
        public FormsLayouts createFromParcel(Parcel in) {
            return new FormsLayouts(in);
        }

        @Override
        public FormsLayouts[] newArray(int size) {
            return new FormsLayouts[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeTypedList(forms);
    }

    public void parse()throws JSONException{
        JSONArray forms = jsonUtil.getFrontForms();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            FormLayout formLayout = parseForm(form, false);
            this.forms.add(formLayout);
        }
    }

    public FormLayout parseForm(JSONObject form, boolean preventRecursive)throws JSONException{
        FormLayout formLayout = new FormLayout(this.context,new Form(form));
        JSONArray fields = jsonUtil.getFormFields(formLayout.getProperties().getId());
        for(int j = 0; j < fields.length(); j++){
            JSONObject field = fields.getJSONObject(j);
            QuestionLayout questionLayout = new QuestionLayout(this.context,new Field(field));
            String fieldType = questionLayout.getProperties().getType();
            if(fieldType.equals(QuestionLayout.Types.SPINNER.code()) || fieldType.equals(QuestionLayout.Types.RADIO_GROUP.code())) {
                JSONObject options = jsonUtil.getOption(questionLayout.getProperties().getId());
                questionLayout.getProperties().setOptions(new Options(options));
            }
            /*if ((fieldType.equals(QuestionLayout.Types.CHECK_BOX.code()) || fieldType.equals(QuestionLayout.Types.SPINNER.code()))
                    && !preventRecursive) {*/

            // TODO: 21/06/2016 developer
            if (questionLayout.getProperties().getId() == 147) {
                toString();
            }

            JSONObject jFieldJoiner = jsonUtil.getFieldJoiner(questionLayout.getProperties().getId());
            if(jFieldJoiner != null){
                FieldJoiner fieldJoiner = new FieldJoiner(jFieldJoiner);
                questionLayout.getProperties().setFieldJoiner(fieldJoiner);
            }

           /* JSONArray jDynamicJoiners = jsonUtil.getDynamicJoiner(questionLayout.getProperties().getId());
            for (int i = 0; i < jDynamicJoiners.length(); i++) {
                JSONObject jDynamicJoiner = jDynamicJoiners.getJSONObject(i);
                questionLayout.setJsonUtil(jsonUtil);
                DynamicJoiner dynamicJoiner = new DynamicJoiner(jDynamicJoiner);
                JSONObject handler = jsonUtil.getHandler(dynamicJoiner.getHandler());
                dynamicJoiner.setHandlerEvent(new Handler(handler));
                // TODO: 09/06/2016
                JSONObject subForm = jsonUtil.getForm(dynamicJoiner.getForm());
                if (subForm != null) {
                    FormLayout subFormLayout = parseForm(subForm, true);
                    subFormLayout.setVisible(true);
                    dynamicJoiner.setTargetForm(subFormLayout);
                }
                questionLayout.getProperties().addDynamicJoiner(dynamicJoiner);
            }*/
            //}

            int fieldHandlerId = questionLayout.getProperties().getHandler();
            if(fieldHandlerId != 0 && fieldHandlerId != -1){
                JSONObject handler = jsonUtil.getHandler(fieldHandlerId);
                questionLayout.getProperties().setHandlerEvent(new Handler(handler));
                /*JSONObject subForm = jsonUtil.getSubForm(questionLayout.getProperties().getId());
                if(subForm != null) {
                    FormLayout subFormLayout = parseForm(subForm,false);
                    questionLayout.setSubForm(subFormLayout);
                }*/
                JSONArray subForms = jsonUtil.getSubForms(questionLayout.getProperties().getId());
                for( int i = 0; i < subForms.length(); i++){
                    FormLayout subFormLayout = parseForm(subForms.getJSONObject(i), false);
                    questionLayout.addSubForm(subFormLayout);
                }
            }
            formLayout.addQuestion(questionLayout);
        }
        return formLayout;
    }

    public ArrayList<HandlerFieldJoiner> getHandlersFieldJoiner(int formPosition){
        ArrayList<HandlerFieldJoiner> handlersFieldJoiner = new ArrayList<>();
        try{
            JSONArray jHandlersFieldJoiner = jsonUtil.getHandlersFieldJoiner();// estructura
            for(int i = 0; i < jHandlersFieldJoiner.length(); i++){
                JSONObject jHandlerFieldJoiner = jHandlersFieldJoiner.getJSONObject(i);
                HandlerFieldJoiner handlerFieldJoiner = new HandlerFieldJoiner(jHandlerFieldJoiner);


                for(int j = 0; j < handlerFieldJoiner.getHandlersIds().size(); j++){// obtiene los handlers
                    JSONObject jHandlerEvent = jsonUtil.getHandler(handlerFieldJoiner.getHandlersIds().get(j));// estructura
                    handlerFieldJoiner.getHandlers().add(new Handler(jHandlerEvent));

                    JSONObject jField = jsonUtil.getFieldById(handlerFieldJoiner.getFieldsIds().get(j));// structura
                    int formId = jField.getInt(Field.JField.FORM.code());
                    if(formId == forms.get(formPosition).getProperties().getId()){
                        boolean exist = false;
                        for(int k = 0; k < handlersFieldJoiner.size(); k++){
                            if(handlerFieldJoiner.equals(handlersFieldJoiner.get(k))){
                                exist = true;
                                break;
                            }
                        }
                        if(!exist) {
                            JSONObject jForm = jsonUtil.getForm(handlerFieldJoiner.getTargetForm());// estructura
                            if(jForm != null){// obtiene los formularios.
                                FormLayout form = parseForm(jForm, true);
                                handlerFieldJoiner.setForm(form);
                            }
                            handlersFieldJoiner.add(handlerFieldJoiner);
                        }
                    }
                }
            }
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
        return handlersFieldJoiner;
    }

    public JSONArray toJsonArray() throws JSONException{
        JSONArray forms = new JSONArray();
        for(int i = 0; i < length(); i++){
            FormLayout formLayout = getForm(i);
            forms.put(formLayout.toJsonObject());
        }
        return forms;
    }

    public int length(){
        return forms.size();
    }

    public boolean isEmpty(){
        return forms.isEmpty();
    }

    public void addForm(FormLayout form){
        forms.add(form);
    }

    public FormLayout getForm(int position){
        return forms.get(position);
    }

    public Activity getContext() {
        return context;
    }

    public void setContext(Activity context) {
        this.context = context;
    }

    public JsonUtil getJsonUtil() {
        return jsonUtil;
    }

    public void setJsonUtil(JsonUtil util) {
        this.jsonUtil = util;
    }

    public ArrayList<FormLayout> getForms() {
        return forms;
    }

    public void setForms(ArrayList<FormLayout> forms) {
        this.forms = forms;
    }
}
