package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by User on 24/05/2016.
 */
public class DynamicJoiner implements Parcelable{

    private int field;
    private int handler;
    private int form;
    private Handler handlerEvent;

    public enum JDynamic{
        FIELD("field"),HANDLER("handler"),FORM("formJoined");
        private final String code;
        JDynamic(String code){
            this.code = code;
        }
        public String code(){
            return code;
        }
    }

    public DynamicJoiner(){

    }

    public DynamicJoiner(JSONObject dynamicJoiner)throws JSONException{
        parse(dynamicJoiner);
    }

    protected DynamicJoiner(Parcel in) {
        field = in.readInt();
        handler = in.readInt();
        form = in.readInt();
        handlerEvent = in.readParcelable(Handler.class.getClassLoader());
    }

    public void parse(JSONObject dynamicJoiner)throws JSONException{
        setField(dynamicJoiner.getInt(JDynamic.FIELD.code()));
        setHandler(dynamicJoiner.getInt(JDynamic.HANDLER.code()));
        setForm(dynamicJoiner.getInt(JDynamic.FORM.code()));
    }

    public static final Creator<DynamicJoiner> CREATOR = new Creator<DynamicJoiner>() {
        @Override
        public DynamicJoiner createFromParcel(Parcel in) {
            return new DynamicJoiner(in);
        }

        @Override
        public DynamicJoiner[] newArray(int size) {
            return new DynamicJoiner[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeInt(field);
        parcel.writeInt(handler);
        parcel.writeInt(form);
        parcel.writeParcelable(handlerEvent, i);
    }

    public int getField() {
        return field;
    }

    public void setField(int field) {
        this.field = field;
    }

    public int getHandler() {
        return handler;
    }

    public void setHandler(int handler) {
        this.handler = handler;
    }

    public int getForm() {
        return form;
    }

    public void setForm(int form) {
        this.form = form;
    }

    public Handler getHandlerEvent() {
        return handlerEvent;
    }

    public void setHandlerEvent(Handler handlerEvent) {
        this.handlerEvent = handlerEvent;
    }
}
