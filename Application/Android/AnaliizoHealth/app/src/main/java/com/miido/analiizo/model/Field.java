package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;
import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by User on 19/05/2016.
 */
public class Field implements Parcelable{

    private int id;
    private int form;
    private String hint;
    private String name;
    private String type;
    private String label;
    private int order;
    private String rules;
    private int length;
    private int parent;
    private String freeAdd;
    private int handler;
    private boolean readOnly;
    private boolean required;
    private String autocomplete;

    private String value;

    private Options options;
    private Handler handlerEvent;
    private FieldJoiner fieldJoiner;
    private ArrayList<DynamicJoiner> dynamicJoiners;


    /**
     * Contiene las constantes que representan propiedades JSON de un campo en la estructura
     */
    public enum JField{
        ID("Id"),FORM("Form"),HINT("Hint"),NAME("Name"),TYPE("Type"),
        LABEL("Label"),ORDER("Order"),RULES("Rules"),LENGTH("Length"),
        PARENT("Parent"),FREEADD("FreeAdd"),HANDLER("Handler"),READONLY("ReadOnly"),
        REQUIRED("Required"),AUTOCOMPLETE("AutoComplete"),VALUE("Value"),SUBFORM("SubForm");
        private final String code;
        JField(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public Field(){
        options = new Options(0);
        dynamicJoiners = new ArrayList<>();
    }

    public Field(JSONObject properties)throws JSONException{
        dynamicJoiners = new ArrayList<>();
        parse(properties);
    }

    protected Field(Parcel in){
        readFromParcel(in);
    }

    public JSONObject toJSonObject()throws JSONException{
        JSONObject json = new JSONObject();
        json.put(JField.ID.code(),getId());
        json.put(JField.FORM.code(),getForm());
        json.put(JField.NAME.code(),getName());
        json.put(JField.LABEL.code(),getLabel());
        json.put(JField.TYPE.code(),getType());
        json.put(JField.RULES.code(),getRules());
        json.put(JField.VALUE.code(),getValue());
        return json;
    }

    @Override
    public void writeToParcel(Parcel parcel, int flag) {
        parcel.writeInt(getId());
        parcel.writeInt(getForm());
        parcel.writeString(getHint());
        parcel.writeString(getName());
        parcel.writeString(getType());
        parcel.writeString(getLabel());
        parcel.writeInt(getOrder());
        parcel.writeString(getRules());
        parcel.writeInt(getLength());
        parcel.writeInt(getParent());
        parcel.writeString(getFreeAdd());
        parcel.writeInt(getHandler());
        parcel.writeBooleanArray(new boolean[]{this.readOnly});
        parcel.writeBooleanArray(new boolean[]{this.required});
        parcel.writeString(getAutocomplete());
        parcel.writeString(getValue());
        parcel.writeParcelable(options, flag);
        parcel.writeParcelable(handlerEvent, flag);
        parcel.writeParcelable(fieldJoiner, flag);
        parcel.writeTypedList(dynamicJoiners);
    }

    public void readFromParcel(Parcel in){
        setId(in.readInt());
        setForm(in.readInt());
        setHint(in.readString());
        setName(in.readString());
        setType(in.readString());
        setLabel(in.readString());
        setOrder(in.readInt());
        setRules(in.readString());
        setLength(in.readInt());
        setParent(in.readInt());
        setFreeAdd(in.readString());
        setHandler(in.readInt());
        boolean[] readOnly = new boolean[1];
        in.readBooleanArray(readOnly);
        setReadOnly(readOnly[0]);
        boolean[] required = new boolean[1];
        in.readBooleanArray(required);
        setRequired(required[0]);
        setAutocomplete(in.readString());
        setValue(in.readString());
        options = in.readParcelable(Options.class.getClassLoader());
        handler = in.readParcelable(Handler.class.getClassLoader());
        fieldJoiner = in.readParcelable(Field.class.getClassLoader());
        in.readTypedList(dynamicJoiners, DynamicJoiner.CREATOR);
    }

    public static final Creator<Field> CREATOR = new Creator<Field>() {
        @Override
        public Field createFromParcel(Parcel in) {
            return new Field(in);
        }

        @Override
        public Field[] newArray(int size) {
            return new Field[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }


    /**
     * Obtiene las propiedades de un campo apartir de un JSONObject
     * @param properties Objecto JSON que contiene las propiedades del campo
     * @throws JSONException es lanzado si ocurre algun error al tratar de obtener una propiedad del JSONObject
     */
    public void parse(JSONObject properties)throws JSONException{
        setId(properties.getInt(JField.ID.code));
        setForm(properties.getInt(JField.FORM.code));
        setHint(properties.getString(JField.HINT.code));
        setName(properties.getString(JField.NAME.code));
        setType(properties.getString(JField.TYPE.code));
        setLabel(properties.getString(JField.LABEL.code));
        setOrder(properties.getInt(JField.ORDER.code));
        setRules(properties.getString(JField.RULES.code));
        setLength(properties.getInt(JField.LENGTH.code));
        setParent(properties.getInt(JField.PARENT.code));
        setFreeAdd(properties.getString(JField.FREEADD.code));
        try {
            setHandler(properties.getInt(JField.HANDLER.code));
        }catch (JSONException ex){
            setHandler(-1);
            Log.e(getClass().getName(), ex.getMessage());
            Log.e(getName(),ex.getMessage());
        }
        try {
            setReadOnly(properties.getBoolean(JField.READONLY.code));
        }catch (JSONException ex){
            setReadOnly(properties.getInt(JField.READONLY.code()) == 0 ? false : true);
        }
        setRequired(properties.getBoolean(JField.REQUIRED.code));
        setAutocomplete(properties.getString(JField.AUTOCOMPLETE.code));
    }

    @Override
    public String toString(){
        StringBuilder buffer = new StringBuilder();
        buffer.append(JField.ID.code() + " : " + getId() + "\n");
        buffer.append(JField.FORM.code + " : " + getForm() + "\n");
        buffer.append(JField.HINT.code + " : " + getHint() + "\n");
        buffer.append(JField.NAME.code + " : " + getName() + "\n");
        buffer.append(JField.TYPE.code + " : " + getType() + "\n");
        buffer.append(JField.LABEL.code + " : " + getLabel() + "\n");
        buffer.append(JField.ORDER.code + " : " + getOrder() + "\n");
        buffer.append(JField.RULES.code + " : " + getRules() + "\n");
        buffer.append(JField.LENGTH.code + " : " + getLength() + "\n");
        buffer.append(JField.PARENT.code + " : " + getParent() + "\n");
        buffer.append(JField.FREEADD.code + " : " + getFreeAdd() + "\n");
        buffer.append(JField.HANDLER.code  + " : " + getHandler() + "\n");
        buffer.append(JField.READONLY.code  + " : " + isReadOnly() + "\n");
        buffer.append(JField.REQUIRED.code  + " : " + isRequired() + "\n");
        buffer.append(JField.AUTOCOMPLETE.code + " : " + getAutocomplete() + "\n");
        return buffer.toString();
    }

    public void addDynamicJoiner(DynamicJoiner dynamicJoiner){
        dynamicJoiners.add(dynamicJoiner);
    }

    public DynamicJoiner getDynamicJoiner(int position){
        return dynamicJoiners.get(position);
    }

    /*
     *Getters y setters
     */

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getForm() {
        return form;
    }

    public void setForm(int form) {
        this.form = form;
    }

    public String getHint() {
        return hint;
    }

    public void setHint(String hint) {
        this.hint = hint;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getLabel() {
        return label;
    }

    public void setLabel(String label) {
        this.label = label;
    }

    public int getOrder() {
        return order;
    }

    public void setOrder(int order) {
        this.order = order;
    }

    public String getRules() {
        return rules;
    }

    public void setRules(String rules) {
        this.rules = rules;
    }

    public int getLength() {
        return length;
    }

    public void setLength(int length) {
        this.length = length;
    }

    public int getParent() {
        return parent;
    }

    public void setParent(int parent) {
        this.parent = parent;
    }

    public String getFreeAdd() {
        return freeAdd;
    }

    public void setFreeAdd(String freeAdd) {
        this.freeAdd = freeAdd;
    }

    public int getHandler() {
        return handler;
    }

    public void setHandler(int handler) {
        this.handler = handler;
    }

    public boolean isReadOnly() {
        return readOnly;
    }

    public void setReadOnly(boolean readOnly) {
        this.readOnly = readOnly;
    }

    public boolean isRequired() {
        return required;
    }

    public void setRequired(boolean required) {
        this.required = required;
    }

    public String getAutocomplete() {
        return autocomplete;
    }

    public void setAutocomplete(String autocomplete) {
        this.autocomplete = autocomplete;
    }

    public Options getOptions() {
        return options;
    }

    public void setOptions(Options options) {
        this.options = options;
    }

    public Handler getHandlerEvent() {
        return handlerEvent;
    }

    public void setHandlerEvent(Handler handlerEvent) {
        this.handlerEvent = handlerEvent;
    }

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value;
    }

    public ArrayList<DynamicJoiner> getDynamicJoiners() {
        return dynamicJoiners;
    }

    public void setDynamicJoiners(ArrayList<DynamicJoiner> dynamicJoiners) {
        this.dynamicJoiners = dynamicJoiners;
    }

    public FieldJoiner getFieldJoiner() {
        return fieldJoiner;
    }

    public void setFieldJoiner(FieldJoiner fieldJoiner) {
        this.fieldJoiner = fieldJoiner;
    }
}