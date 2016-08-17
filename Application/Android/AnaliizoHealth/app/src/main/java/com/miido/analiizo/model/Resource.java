package com.miido.analiizo.model;

import android.graphics.Bitmap;
import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by User on 14/04/2016.
 */
public class Resource implements Parcelable{

    private String name;
    private String description;
    private String directory;
    private String mimeType;
    private String savedDate;
    private String sendDate;
    private String tmpPath;
    private Bitmap image;

    public Resource(String name,String description, String directory, String mimeType,String saveddate,String senddate, Bitmap image){
        this.name = name;
        this.setDescription(description);
        this.directory = directory;
        this.mimeType = mimeType;
        this.setSavedDate(saveddate);
        this.setSendDate(senddate);
        this.tmpPath = "";
        this.image = image;
    }

    public Resource(){
        name = description = directory = mimeType = savedDate = sendDate = tmpPath = null;
        image = null;
    }

    public Resource(Parcel in){
        readFromParcel(in);
    }

    public void readFromParcel(Parcel in){
        setName(in.readString());
        setDescription(in.readString());
        setDirectory(in.readString());
        setMimeType(in.readString());
        setSavedDate(in.readString());
        setSendDate(in.readString());
        setTmpPath(in.readString());
        setImage((Bitmap) in.readParcelable(Bitmap.class.getClassLoader()));
    }

    @Override
    public void writeToParcel(Parcel parcel, int flag) {
        parcel.writeString(getName());
        parcel.writeString(getDescription());
        parcel.writeString(getDirectory());
        parcel.writeString(getMimeType());
        parcel.writeString(getSavedDate());
        parcel.writeString(getSendDate());
        parcel.writeString(getTmpPath());
        parcel.writeParcelable(getImage(), flag);
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Parcelable.Creator<Resource> CREATOR = new Parcelable.Creator<Resource>(){

        @Override
        public Resource createFromParcel(Parcel parcel) {
            return new Resource(parcel);
        }

        @Override
        public Resource[] newArray(int size) {
            return new Resource[size];
        }
    };

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDirectory() {
        return directory;
    }

    public void setDirectory(String directory) {
        this.directory = directory;
    }

    public String getMimeType() {
        return mimeType;
    }

    public void setMimeType(String mimeType) {
        this.mimeType = mimeType;
    }

    public Bitmap getImage() {
        return image;
    }

    public void setImage(Bitmap image) {
        this.image = image;
    }

    public String getTmpPath() {
        return tmpPath;
    }

    public void setTmpPath(String tmpPath) {
        this.tmpPath = tmpPath;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getSavedDate() {
        return savedDate;
    }

    public void setSavedDate(String savedDate) {
        this.savedDate = savedDate;
    }

    public String getSendDate() {
        return sendDate;
    }

    public void setSendDate(String sendDate) {
        this.sendDate = sendDate;
    }
}
