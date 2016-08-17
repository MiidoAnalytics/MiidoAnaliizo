package com.miido.analiizo.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * @author Ing. Miguel Angel Urango Blanco Miido S.A.S 02/05/2016.
 * @see Parcelable
 */
public class Item implements Parcelable{

    private int id;
    private String name;
    private String description;

    public Item(){
        id = 0;
        name = description = null;
    }

    public Item(int id, String name, String description){
        this.id = id;
        this.name = name;
        this.description = description;
    }

    public Item(Parcel in){
        readFromParcel(in);
    }

    public void readFromParcel(Parcel in){
        setId(in.readInt());
        setName(in.readString());
        setDescription(in.readString());
    }

    public static final Creator<Item> CREATOR = new Creator<Item>() {
        @Override
        public Item createFromParcel(Parcel in) {
            return new Item(in);
        }

        @Override
        public Item[] newArray(int size) {
            return new Item[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel out, int flag) {
        out.writeInt(getId());
        out.writeString(getName());
        out.writeString(getDescription());
    }



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

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }
}
