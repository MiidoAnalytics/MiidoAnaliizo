package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by User on 19/05/2016.
 */
public class Options implements Parcelable{

    private int id;
    private ArrayList<Integer> fields;
    private ArrayList<String> options;

    public enum JOptions{
        ID("Id"),FIELDS("Field"),OPTIONS("Options");
        private final String code;
        JOptions(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public Options(int id){
        this.id = id;
        this.fields = new ArrayList<>();
        this.options = new ArrayList<>();
    }

    public Options(JSONObject options)throws JSONException{
        this.fields = new ArrayList<>();
        this.options = new ArrayList<>();
        parse(options);
    }

    protected Options(Parcel in) {
        setId(in.readInt());
        in.readList(fields,Integer.class.getClassLoader());
        in.readList(options, String.class.getClassLoader());
    }

    public void parse(JSONObject options)throws JSONException{
        setId(options.getInt(JOptions.ID.code));
        JSONArray fields = options.getJSONArray(JOptions.FIELDS.code);
        for(int i = 0; i < fields.length(); i++){
            this.fields.add(fields.getInt(i));
        }
        JSONArray ops = options.getJSONArray(JOptions.OPTIONS.code);
        for(int i = 0; i < ops.length(); i++){
            this.options.add(ops.getString(i));
        }
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeInt(id);
        parcel.writeList(fields);
        parcel.writeList(options);
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Creator<Options> CREATOR = new Creator<Options>() {
        @Override
        public Options createFromParcel(Parcel in) {
            return new Options(in);
        }

        @Override
        public Options[] newArray(int size) {
            return new Options[size];
        }
    };

    public int getField(int pos){
        return fields.get(pos);
    }

    public String getOption(int pos){
        return options.get(pos);
    }

    public void addField(int fieldId){
        fields.add(fieldId);
    }

    public void addOption(String option){
        options.add(option);
    }

    public int optionsLength(){
        return options.size();
    }

    public int fieldsLength(){
        return fields.size();
    }

    public boolean optionsEmpty(){
        return options.isEmpty();
    }

    public boolean fieldsEmpty(){
        return fields.isEmpty();
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public ArrayList<Integer> getFields() {
        return fields;
    }

    public void setFields(ArrayList<Integer> fields) {
        this.fields = fields;
    }

    public ArrayList<String> getOptions() {
        return options;
    }

    public void setOptions(ArrayList<String> options) {
        this.options = options;
    }
}
