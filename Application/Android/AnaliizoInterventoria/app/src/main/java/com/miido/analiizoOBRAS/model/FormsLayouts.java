package com.miido.analiizoOBRAS.model;

import android.app.Activity;
import android.content.Context;
import android.os.Parcel;
import android.os.Parcelable;

import com.miido.analiizoOBRAS.util.JsonUtil;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by User on 06/05/2016.
 */
public class FormsLayouts implements Parcelable{

    private ArrayList<FormLayout> forms;
    private Activity context;
    private JsonUtil util;

    /****************
     * Constructores *
     ****************/

    public FormsLayouts(){
        forms = new ArrayList<>();
    }

    public FormsLayouts(Activity context,JSONObject poll)throws JSONException{
        this.context = context;
        forms = new ArrayList<>();
        util = new JsonUtil(poll);
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
        JSONArray forms = util.getFrontForms();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            FormLayout formLayout = parseForm(form, false);
            this.forms.add(formLayout);
        }
    }

    private FormLayout parseForm(JSONObject form, boolean preventRecursive)throws JSONException{
        FormLayout formLayout = new FormLayout(this.context,new Form(form));
        JSONArray fields = util.getFormFields(formLayout.getProperties().getId());
        for(int j = 0; j < fields.length(); j++){
            JSONObject field = fields.getJSONObject(j);
            QuestionLayout questionLayout = new QuestionLayout(this.context,new Field(field));
            String fieldType = questionLayout.getProperties().getType();
            if(fieldType.equals(QuestionLayout.Types.SPINNER.code()) || fieldType.equals(QuestionLayout.Types.RADIO_GROUP.code())) {
                JSONObject options = util.getOption(questionLayout.getProperties().getId());
                questionLayout.getProperties().setOptions(new Options(options));
            }else{
                if(fieldType.equals(QuestionLayout.Types.CHECK_BOX.code()) && !preventRecursive){
                    JSONObject jDynamicJoiner = util.getDynamicJoiner(questionLayout.getProperties().getId());
                    if(jDynamicJoiner != null) {
                        DynamicJoiner dynamicJoiner = new DynamicJoiner(jDynamicJoiner);
                        JSONObject handler = util.getHandler(dynamicJoiner.getHandler());
                        dynamicJoiner.setHandlerEvent(new Handler(handler));
                        questionLayout.getProperties().setDynamicJoiner(dynamicJoiner);
                        // TODO: 09/06/2016
                        /*JSONObject subForm = util.getForm(dynamicJoiner.getForm());
                        if(subForm != null){
                            FormLayout subFormLayout = parseForm(subForm,true);
                            questionLayout.setSubForm(subFormLayout);
                        }*/
                    }
                }
            }
            int fieldHandlerId = questionLayout.getProperties().getHandler();
            if(fieldHandlerId != 0){
                JSONObject handler = util.getHandler(fieldHandlerId);
                questionLayout.getProperties().setHandlerEvent(new Handler(handler));
                JSONObject subForm = util.getSubForm(questionLayout.getProperties().getId());
                if(subForm != null) {
                    FormLayout subFormLayout = parseForm(subForm,false);
                    questionLayout.setSubForm(subFormLayout);
                }
            }
            formLayout.addQuestion(questionLayout);
        }
        return formLayout;
    }

    public JSONArray toJsonArray() throws JSONException{
        JSONArray forms = new JSONArray();
        for(int i = 0; i < length(); i++){
            FormLayout formLayout = getForm(i);
            forms.put(formLayout.toJsonObject());
        }
        return forms;
    }

    // TODO: 27/05/2016 Solución al error de agregación de viesta que ya tiene un padre.
    public FormLayout getDynamicForm(int formId)throws JSONException{
        JSONObject form = util.getForm(formId);
        return  parseForm(form,false);
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
}
