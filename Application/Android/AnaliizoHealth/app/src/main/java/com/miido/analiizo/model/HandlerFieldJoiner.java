package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;
import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by User on 14/06/2016.
 */
public class HandlerFieldJoiner implements Parcelable{

    private int targetForm;
    private ArrayList<Integer> fieldsIds;
    private ArrayList<Integer> handlersIds;
    private ArrayList<Handler> handlers;
    private FormLayout form;



    enum JProperties{
        FIELD_IDS("idFields"), HANDLER_IDS("idHandlers"), TARGET_FORM("TargetForm");
        private final String code;
        JProperties(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public HandlerFieldJoiner(){
        fieldsIds = new ArrayList<>();
        handlersIds = new ArrayList<>();
        handlers = new ArrayList<>();
        targetForm = 0;
        setForm(null);
    }

    public HandlerFieldJoiner(JSONObject properties)throws JSONException{
        fieldsIds = new ArrayList<>();
        handlersIds = new ArrayList<>();
        handlers = new ArrayList<>();
        parse(properties);
    }

    protected HandlerFieldJoiner(Parcel in) {
        this.targetForm = in.readInt();
        in.readList(fieldsIds,Integer.class.getClassLoader());
        in.readList(handlersIds,Integer.class.getClassLoader());
        this.handlers = in.createTypedArrayList(Handler.CREATOR);
        this.form = in.readParcelable(FormLayout.class.getClassLoader());
    }

    private void parse(JSONObject properties)throws JSONException{
        this.targetForm = properties.getInt(JProperties.TARGET_FORM.code());
        JSONArray idFields = properties.getJSONArray(JProperties.FIELD_IDS.code());
        JSONArray idHandlers = properties.getJSONArray(JProperties.HANDLER_IDS.code());
        if(idFields.length() == idHandlers.length()) {
            for (int i = 0; i < idFields.length(); i++) {
                fieldsIds.add(idFields.getInt(i));
                handlersIds.add(idHandlers.getInt(i));
            }
        }else{
            Log.e(getClass().getName(), "Fields.Length() != Handlers.Length()");
        }
    }

    public Handler getHandler(int fieldId){
        if(fieldsIds.size() == handlersIds.size() && fieldsIds.size() == handlers.size()){
            for(int i = 0; i < fieldsIds.size(); i++){
                if(fieldsIds.get(i) == fieldId){
                    return handlers.get(i);
                }
            }
        }else{
            Log.e(getClass().getName(), "Field.Length() != Handler.Length");
        }
        return null;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int flag) {
        parcel.writeInt(targetForm);
        parcel.writeList(fieldsIds);
        parcel.writeList(handlersIds);
        parcel.writeTypedList(handlers);
        parcel.writeParcelable(getForm(), flag);
    }

    public static final Creator<HandlerFieldJoiner> CREATOR = new Creator<HandlerFieldJoiner>() {
        @Override
        public HandlerFieldJoiner createFromParcel(Parcel in) {
            return new HandlerFieldJoiner(in);
        }

        @Override
        public HandlerFieldJoiner[] newArray(int size) {
            return new HandlerFieldJoiner[size];
        }
    };

    public int getTargetForm() {
        return targetForm;
    }

    public void setTargetForm(int targetForm) {
        this.targetForm = targetForm;
    }

    public ArrayList<Integer> getFieldsIds() {
        return fieldsIds;
    }

    public void setFieldsIds(ArrayList<Integer> fieldsIds) {
        this.fieldsIds = fieldsIds;
    }

    public ArrayList<Integer> getHandlersIds() {
        return handlersIds;
    }

    public void setHandlersIds(ArrayList<Integer> handlersIds) {
        this.handlersIds = handlersIds;
    }

    public ArrayList<Handler> getHandlers() {
        return handlers;
    }

    public void setHandlers(ArrayList<Handler> handlers) {
        this.handlers = handlers;
    }

    public FormLayout getForm() {
        return form;
    }

    public void setForm(FormLayout form) {
        this.form = form;
    }
}
