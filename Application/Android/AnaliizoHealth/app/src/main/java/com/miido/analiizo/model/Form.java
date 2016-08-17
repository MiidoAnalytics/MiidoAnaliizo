package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by User on 19/05/2016.
 */
public class Form implements Parcelable{

    private int id;
    private String name;
    private String header;
    private int inside;
    private int parent;
    private int handler;
    private String clonable;

    /**
     * Contiene las constantes correspondientes a las propiedades del formualario en el objeto JSON
     */
    public enum JForm{
        ID("Id"),NAME("Name"),HEADER("Header"),INSIDE("Inside"),
        PARENT("Parent"),HANDLER("Handler"), CLONEABLE("Clonable"),FIELDS("Fields");
        private final String code;
        JForm(String code){
            this.code = code;
        }
        public String code(){
            return this.code;
        }
    }

    public Form(JSONObject properties)throws JSONException{
        parse(properties);
    }

    protected Form(Parcel in) {
        id = in.readInt();
        name = in.readString();
        header = in.readString();
        inside = in.readInt();
        parent = in.readInt();
        handler = in.readInt();
        clonable = in.readString();
    }

    /**
     * Obtiene las propiedades del formulario apartir de un JSONObject
     * @param properties Objeto JSON con las propiedades del formulario
     * @throws JSONException es lanzada si ocurre un error al tratar de obtener alguna propiedad del JSONObject
     */
    public void parse(JSONObject properties)throws JSONException {
        setId(properties.getInt(JForm.ID.code));
        if(properties.has(JForm.NAME.code)) {
            setName(properties.getString(JForm.NAME.code));
        }
        setHeader(properties.getString(JForm.HEADER.code));
        setInside(properties.getInt(JForm.INSIDE.code));
        setParent(properties.getInt(JForm.PARENT.code));
        setHandler(properties.getInt(JForm.HANDLER.code));
        setClonable(properties.getString(JForm.CLONEABLE.code));
    }

    public JSONObject toJsonObject()throws JSONException{
        JSONObject json = new JSONObject();
        json.put(JForm.ID.code(),getId());
        json.put(JForm.NAME.code(),getName());
        json.put(JForm.HEADER.code(),getHeader());
        json.put(JForm.PARENT.code(),getParent());
        json.put(JForm.INSIDE.code(),getInside());
        return json;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeInt(id);
        parcel.writeString(name);
        parcel.writeString(header);
        parcel.writeInt(inside);
        parcel.writeInt(parent);
        parcel.writeInt(handler);
        parcel.writeString(clonable);
    }

    public static final Creator<Form> CREATOR = new Creator<Form>() {
        @Override
        public Form createFromParcel(Parcel in) {
            return new Form(in);
        }

        @Override
        public Form[] newArray(int size) {
            return new Form[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    /*
     * Getters y setters
     */

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getHeader() {
        return header;
    }

    public void setHeader(String header) {
        this.header = header;
    }

    public int getInside() {
        return inside;
    }

    public void setInside(int inside) {
        this.inside = inside;
    }

    public int getParent() {
        return parent;
    }

    public void setParent(int parent) {
        this.parent = parent;
    }

    public int getHandler() {
        return handler;
    }

    public void setHandler(int handler) {
        this.handler = handler;
    }

    public String getClonable() {
        return clonable;
    }

    public void setClonable(String clonable) {
        this.clonable = clonable;
    }
}
