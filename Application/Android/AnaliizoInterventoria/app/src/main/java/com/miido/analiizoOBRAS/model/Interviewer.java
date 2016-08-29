package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by User on 08/04/2016.
 */
public class Interviewer implements Parcelable{

    private int id;
    private String user;
    private String password;

    public Interviewer(){
        this.id = 0;
        this.user = this.password = "";
    }

    public Interviewer(int id, String user, String password){
        this.id = id;
        this.user = user;
        this.password = password;
    }

    public Interviewer(Parcel in){
        readFromParcel(in);
    }

    public void readFromParcel(Parcel in){
        setId(in.readInt());
        setUser(in.readString());
        setPassword(in.readString());
    }

    @Override
    public void writeToParcel(Parcel dest, int flags){
        dest.writeInt(this.id);
        dest.writeString(this.user);
        dest.writeString(this.password);
    }

    public static final Parcelable.Creator<Interviewer> CREATOR = new Parcelable.Creator<Interviewer>(){

        @Override
        public Interviewer createFromParcel(Parcel in) {
            return new Interviewer(in);
        }

        @Override
        public Interviewer[] newArray(int size) {
            return new Interviewer[size];
        }
    };

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getUser() {
        return user;
    }

    public void setUser(String user) {
        this.user = user;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    @Override
    public int describeContents() {
        return 0;
    }

}
