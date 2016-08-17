package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by Miguel Urango MIIDO S.A.S on 10/03/2016.
 */
public class Project implements Parcelable{

    private long id;
    private String name;
    private String description;

    /**
     * 
     */
    public Project(){
        this.id = 0;
        this.name = "";
        this.description = "";
    }

    /**
     *
     * @param id
     * @param name
     */
    public Project(long id, String name,String description){
        this.id = id;
        this.name = name;
        this.description = description;
    }

    /**
     *
     * @param in
     */
    public Project(Parcel in){
        readFromParcel(in);
    }

    /**
     *
     * @param in
     */
    public void readFromParcel(Parcel in){
        setId(in.readLong());
        setName(in.readString());
        setDescription(in.readString());
    }

    /**
     *
     * @param dest
     * @param flags
     */
    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeLong(this.id);
        dest.writeString(this.name);
        dest.writeString(this.description);
    }

    /**
     *
     * @return
     */
    @Override
    public int describeContents() {
        return 0;
    }

    /**
     *
     */
    public static final Parcelable.Creator<Project> CREATOR = new Parcelable.Creator<Project>(){

        @Override
        public Project createFromParcel(Parcel in) {
            return new Project(in);
        }

        @Override
        public Project[] newArray(int size) {
            return new Project[size];
        }
    };

    /**
     *
     * @return
     */
    public long getId() {
        return id;
    }

    /**
     *
     * @param id
     */
    public void setId(long id) {
        this.id = id;
    }

    /**
     *
     * @return
     */
    public String getName() {
        return name;
    }

    /**
     *
     * @param name
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     *
     * @return
     */
    public String getDescription(){
        return this.description;
    }

    /**
     *
     * @param description
     */
    public void setDescription(String description){
        this.description = description;
    }
}
