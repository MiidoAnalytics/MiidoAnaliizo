package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by User on 23/05/2016.
 */
public class Handler implements Parcelable{

    private int id;
    private ArrayList<String> types;
    private ArrayList<String> parameters;

    public enum JHandler{
        ID("Id"), TYPES("Types"), PARAMS("Parameters");
        private final String code;
        JHandler(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public enum CompareTypes{
        equalTo("="),otherThan("!="),lessThan("<"),greaterThan(">");
        private final String type;
        CompareTypes(String type){
            this.type = type;
        }
        public String type(){
            return type;
        }
    }

    public Handler(){
        id = 0;
        types = new ArrayList<>();
        parameters = new ArrayList<>();
    }

    protected Handler(Parcel in) {
        id = in.readInt();
        types = in.createStringArrayList();
        parameters = in.createStringArrayList();
    }

    public Handler(JSONObject handler)throws JSONException{
        types = new ArrayList<>();
        parameters = new ArrayList<>();
        parse(handler);
    }

    public void parse(JSONObject handler)throws JSONException{
        setId(handler.getInt(JHandler.ID.code));
        JSONArray types = handler.getJSONArray(JHandler.TYPES.code);
        for( int i = 0; i < types.length(); i++){
            this.types.add(types.getString(i));
        }
        JSONArray params = handler.getJSONArray(JHandler.PARAMS.code);
        for( int i = 0; i < params.length(); i++){
            this.parameters.add(params.getString(i));
        }
    }

    public boolean compareTo(String value)throws NumberFormatException{
        if(types.size() == parameters.size()) {
            for (int i = 0; i < types.size(); i++) {
                String type = types.get(i);
                if(type.equals(CompareTypes.equalTo.type)) {
                    return parameters.get(i).equals(value);
                }else{
                    if(type.equals(CompareTypes.otherThan.type)){
                        return !parameters.get(i).equals(value);
                    }else{
                        if(type.equals(CompareTypes.lessThan.type)){
                            return Integer.parseInt(parameters.get(i)) < Integer.parseInt(value);
                        }else{
                            if(type.equals(CompareTypes.greaterThan.type)){
                                return Integer.parseInt(parameters.get(i)) > Integer.parseInt(value);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public static final Creator<Handler> CREATOR = new Creator<Handler>() {
        @Override
        public Handler createFromParcel(Parcel in) {
            return new Handler(in);
        }

        @Override
        public Handler[] newArray(int size) {
            return new Handler[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeInt(id);
        parcel.writeStringList(types);
        parcel.writeStringList(parameters);
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public ArrayList<String> getTypes() {
        return types;
    }

    public void setTypes(ArrayList<String> types) {
        this.types = types;
    }

    public ArrayList<String> getParameters() {
        return parameters;
    }

    public void setParameters(ArrayList<String> parameters) {
        this.parameters = parameters;
    }
}
