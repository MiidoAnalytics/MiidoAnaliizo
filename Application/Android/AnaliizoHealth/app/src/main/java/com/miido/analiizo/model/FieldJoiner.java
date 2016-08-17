package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by User on 21/06/2016.
 */
public class FieldJoiner implements Parcelable{

    private int id;
    private int idFrom;
    private int inTo;
    private String method;

    public enum Json{
        ID("Id"),ID_FROM("IdFrom"),INTO("IdTo"),METHOD("Method");
        private final String code;
        Json(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public FieldJoiner(JSONObject fieldJoiner)throws JSONException{
        parse(fieldJoiner);
    }

    protected FieldJoiner(Parcel in) {
        id = in.readInt();
        idFrom = in.readInt();
        inTo = in.readInt();
        method = in.readString();
    }

    private void parse(JSONObject fieldJoiner)throws JSONException{
        setId(fieldJoiner.getInt(Json.ID.code));
        setIdFrom(fieldJoiner.getInt(Json.ID_FROM.code));
        setInTo(fieldJoiner.getInt(Json.INTO.code));
        setMethod(fieldJoiner.getString(Json.METHOD.code));
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeInt(id);
        parcel.writeInt(idFrom);
        parcel.writeInt(inTo);
        parcel.writeString(method);
    }

    public static final Creator<FieldJoiner> CREATOR = new Creator<FieldJoiner>() {
        @Override
        public FieldJoiner createFromParcel(Parcel in) {
            return new FieldJoiner(in);
        }

        @Override
        public FieldJoiner[] newArray(int size) {
            return new FieldJoiner[size];
        }
    };

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getIdFrom() {
        return idFrom;
    }

    public void setIdFrom(int idFrom) {
        this.idFrom = idFrom;
    }

    public int getInTo() {
        return inTo;
    }

    public void setInTo(int inTo) {
        this.inTo = inTo;
    }

    public String getMethod() {
        return method;
    }

    public void setMethod(String method) {
        this.method = method;
    }
}
