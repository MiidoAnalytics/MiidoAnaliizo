package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Contiene la estructura de un DynamicJoiner utilizado para encapsular un evento que muestra un formulario dinámico
 * @author Ing. Miguel Angel Urango Blanco Miido.S.A.S on 24/05/2016.
 * @see Parcelable
 */
public class DynamicJoiner implements Parcelable{

    private int field;
    private int handler;
    private int form;
    private Handler handlerEvent;
    private FormLayout targetForm;

    /**
     * Propiedades de la estructura Json.
     */
    public enum JDynamic{
        FIELD("field"),HANDLER("handler"),FORM("formJoined"),//encuesta interventoría.
        FIELD_IDS("idFields"), HANDLER_IDS("idHandlers"), TARGET_FORM("TargetForm");//encuesta salud.
        private final String code;
        JDynamic(String code){
            this.code = code;
        }
        public String code(){
            return code;
        }
    }

    /**
     * Contructor vacio.
     */
    public DynamicJoiner(){}

    /**
     * Contructor que recive un Objeto Json para parsear a un objeto DynamicJoiner.
     * @param dynamicJoiner Objeto JSon que contiene la estructura del DynamicJoiner
     * @throws JSONException es lanzada si ocurre algún error al obtener una propiedad Json.
     */
    public DynamicJoiner(JSONObject dynamicJoiner)throws JSONException{
        parse(dynamicJoiner);
    }

    /**
     * Contructor para implementar la interfaz Parcelable.
     * @param in Parcel que lee los atributos.
     */
    protected DynamicJoiner(Parcel in) {
        field = in.readInt();
        handler = in.readInt();
        form = in.readInt();
        handlerEvent = in.readParcelable(Handler.class.getClassLoader());
        targetForm = in.readParcelable(FormLayout.class.getClassLoader());
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
    public void writeToParcel(Parcel parcel, int flag) {
        parcel.writeInt(field);
        parcel.writeInt(handler);
        parcel.writeInt(form);
        parcel.writeParcelable(handlerEvent, flag);
        parcel.writeParcelable(getTargetForm(), flag);
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

    public FormLayout getTargetForm() {
        return targetForm;
    }

    public void setTargetForm(FormLayout targetForm) {
        this.targetForm = targetForm;
    }
}
