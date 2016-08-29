package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Clase que contiene la estructura y los datos de una encuesta
 * @author Ing. Miguel Angel Urango Blanco 13/01/2016.
 * @version 1.0
 */
public class Poll implements Parcelable{

    private int id;
    private long structureId,interviewerId,projectId,clientId;
    private String title,header,projectName;

    private String content,savedDate,sentDate;

    /**
     * constructor
     */
    public Poll(){
        id = 0;
        structureId = interviewerId = projectId = clientId = 0;
        title = header = projectName = content = savedDate = sentDate = "";
    }

    /**
     * constructor
     * @param structureId número identificador de la estrucutura
     * @param interviewerId número identificador del encuestador
     * @param clientId número identificador del cliente
     * @param projectId número de identificador de proyecto
     * @param projectName nombre del proyecto
     * @param title título de la encuesta
     * @param header descripción de la encuesta
     */
    public Poll(int id,long structureId,long interviewerId,long clientId,long projectId,String projectName,String title,String header,String content,String savedDate,String sentDate){
        this.id = id;
        this.structureId = structureId;
        this.interviewerId = interviewerId;
        this.projectId = projectId;
        this.clientId = clientId;
        this.title = title;
        this.header = header;
        this.projectName = projectName;
        this.content = content;
        this.savedDate = savedDate;
        this.sentDate = sentDate;
    }

    /**
     * Constructor que genera los datos a traves de un Parcel
     * @param in Parcel que genera el objeto y poder compartir el objeto etre actividades en los extras
     * @see Parcel
     */
    public Poll(Parcel in){
        readFromParcel(in);
    }

    /**
     * @return generalmente se retorna cero (0)
     */
    @Override
    public int describeContents() {
        return 0;
    }

    /**
     * escribe los datos contenidos en los atributos a un parcel de destino
     * @param dest parcel de destino
     * @param flags generalmente se ulitiza cero (0)
     * @see Parcel
     */
    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(id);
        dest.writeLong(structureId);
        dest.writeLong(interviewerId);
        dest.writeLong(projectId);
        dest.writeLong(clientId);
        dest.writeString(title);
        dest.writeString(header);
        dest.writeString(projectName);
        dest.writeString(content);
        dest.writeString(savedDate);
        dest.writeString(sentDate);
    }

    /**
     * Lee los atributos de la clase a través de un Parcel
     * @param in Parcel de donde se leen los atributos
     * @see Parcel
     */
    public void readFromParcel(Parcel in){
        setId(in.readInt());
        setStructureId(in.readLong());
        setInterviewerId(in.readLong());
        setProjectId(in.readLong());
        setClientId(in.readLong());
        setTitle(in.readString());
        setHeader(in.readString());
        setProjectName(in.readString());
        setContent(in.readString());
        setSavedDate(in.readString());
        setSentDate(in.readString());
    }

    /**
     * Crea un objeto de la clase actual a través de un Parcel
     */
    public static final Parcelable.Creator<Poll> CREATOR
            = new Parcelable.Creator<Poll>(){

        public Poll createFromParcel(Parcel in){
            return new Poll(in);
        }

        public Poll[] newArray(int size){
            return new Poll[size];
        }

    };

    /**
     * Obtiene el identificador unico de la encuesta
     * @return número con el identificador unico de la encuesta
     */
    public int getId() {
        return id;
    }

    /**
     * Establece el identificador unico de la encuesta
     * @param id número con el identificador unico de la encuesta
     */
    public void setId(int id) {
        this.id = id;
    }

    /**
     * obtiene el identificador de la estructura
     * @return número de indetificador de la estructura
     */
    public long getStructureId() {
        return structureId;
    }

    /**
     * establece el identificador de la estructura
     * @param structureId número de indetificador de la estructura
     */
    public void setStructureId(long structureId) {
        this.structureId = structureId;
    }

    /**
     * obtiene el número identificador del encuestador
     * @return número identificador del encuestador
     */
    public long getInterviewerId() {
        return interviewerId;
    }

    /**
     * establece el identificador del encuestador
     * @param interviewerId número identificador del encuestador
     */
    public void setInterviewerId(long interviewerId) {
        this.interviewerId = interviewerId;
    }

    /**
     * obtiene el identificador del proyecto
     * @return número identificador del proyecto
     */
    public long getProjectId() {
        return projectId;
    }

    /**
     * establece el identificador del proyecto
     * @param projectId número identificador del proyecto
     */
    public void setProjectId(long projectId) {
        this.projectId = projectId;
    }

    /**
     * obtiene el identificador del cliente
     * @return número identificador del cliente
     */
    public long getClientId() {
        return clientId;
    }

    /**
     * estable el identificador del cliente
     * @param clientId número identificador del cliente
     */
    public void setClientId(long clientId) {
        this.clientId = clientId;
    }

    /**
     * obtiene el título de la encuesta
     * @return cadena de texto con el título de la encuesta
     */
    public String getTitle() {
        return title;
    }

    /**
     * establece el título de la encuesta
     * @param title cadena de texto con el título de la encuesta
     */
    public void setTitle(String title) {
        this.title = title;
    }

    /**
     * obtiene la descripción de la encuesta
     * @return cadena de texto con la descripción de la encuesta
     */
    public String getHeader() {
        return header;
    }

    /**
     * establece la descripción de la encuesta
     * @param header cadena de texto con la descripción de la encuesta
     */
    public void setHeader(String header) {
        this.header = header;
    }

    /**
     * obtiene el nombre del proyecto
     * @return cadena de texto con el nombre del proyecto
     */
    public String getProjectName() {
        return projectName;
    }

    /**
     * Establece el nombre del proyecto
     * @param projectName cadena de texto con el nombre del proyecto
     */
    public void setProjectName(String projectName) {
        this.projectName = projectName;
    }

    /**
     * obtiene el contenido de la encuesta
     * @return cadena de texto con el contenido de la encuesta
     */
    public String getContent() {
        return content;
    }

    /**
     * Establece el contenido de la encuesta
     * @param content cadena de texto con el contenido de la encuesta
     */
    public void setContent(String content) {
        this.content = content;
    }

    /**
     * obtiene la fecha de guardado de la encuesta
     * @return cadena de texto con la fecha de guardado de la encuesta
     */
    public String getSavedDate() {
        return savedDate;
    }

    /**
     * Establece la fecha de guardado de la encuesta
     * @param savedDate cadena de texto con la fecha de guardado de la encuesta
     */
    public void setSavedDate(String savedDate) {
        this.savedDate = savedDate;
    }

    /**
     * obtiene la fecha de enviado de la encuesta
     * @return cadena de texto con la fecha de enviado de la encuesta
     */
    public String getSentDate() {
        return sentDate;
    }

    /**
     * Establece la fecha de enviado de la encuesta
     * @param sentDate cadena de texto con la fecha de enviado de la encuesta
     */
    public void setSentDate(String sentDate) {
        this.sentDate = sentDate;
    }

}
