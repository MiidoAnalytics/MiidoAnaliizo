package com.miido.analiizoOBRAS.model;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Clase objeto que contiene la estrutura de los datos basicos de una persona.
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S 09/11/2015.
 * @version 1.0
 * @see Parcelable
 */

public class Person implements Parcelable {

    private char gender,RH;
    private String firstname1,firstname2,lastname1,lastname2,bloodgroup;
    private long id,birthday;

    /**
     * Constructor vacio inicializa todas las variables con valores de defecto.
     */
    public Person(){
        firstname1 = firstname2 = lastname1 = lastname2 = bloodgroup = "";
        gender = RH = ' ';
        id = birthday = 0;
    }

    /**
     * constructor
     * @param id indentificador de la persona
     * @param firstname1 primer nombre
     * @param firstname2 segundo nombre
     * @param lastname1 primer apellido
     * @param lastname2 segundo apellido
     * @param gender género F o M
     * @param bloodgroup grupo sanguineo
     * @param RH rh sanguineo + o -
     * @param birthday fecha de nacimiento
     */
    public Person(long id,
                  String firstname1, String firstname2,
                  String lastname1, String lastname2,
                  char gender, String bloodgroup, char RH, long birthday){
        setGender(gender);
        setFirstname1(firstname1);
        setFirstname2(firstname2);
        setLastname1(lastname1);
        setLastname2(lastname2);
        setId(id);
        setBloodgroup(bloodgroup);
        setRH(RH);
        setBirthday(birthday);
    }

    /**
     * Constructor que genera los datos a traves de un Parcel
     * @param in Parcel que genera el objeto y poder compartir el objeto etre actividades en los extras
     * @see Parcel
     */
    public Person(Parcel in){
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
        dest.writeLong(id);
        dest.writeString(firstname1);
        dest.writeString(firstname2);
        dest.writeString(lastname1);
        dest.writeString(lastname2);
        dest.writeCharArray(new char[]{gender});
        dest.writeString(bloodgroup);
        dest.writeCharArray(new char[]{RH});
        dest.writeLong(birthday);
    }

    /**
     * Lee los atributos de la clase a través de un Parcel
     * @param in Parcel de donde se leen los atributos
     * @see Parcel
     */
    public void readFromParcel(Parcel in){
        setId(in.readLong());
        setFirstname1(in.readString());
        setFirstname2(in.readString());
        setLastname1(in.readString());
        setLastname2(in.readString());
        char[] tmp = new char[1];
        in.readCharArray(tmp);
        setGender(tmp[0]);
        setBloodgroup(in.readString());
        in.readCharArray(tmp);
        setRH(tmp[0]);
        setBirthday(in.readLong());
    }

    /**
     * Crea un objeto de la clase actual a través de un Parcel
     */
    public static final Parcelable.Creator<Person> CREATOR
            = new Parcelable.Creator<Person>(){

        public Person createFromParcel(Parcel in){
            return new Person(in);
        }

        public Person[] newArray(int size){
            return new Person[size];
        }

    };

    /**
     * Obtine el género de la persona
     * @return un caracter con el género de la persona
     */
    public char getGender() {return gender;}

    /**
     * Establece el genero de la persona
     * @param gender el carácter con el genero de la persona
     */
    public void setGender(char gender) {
        this.gender = gender;
    }

    /**
     * Obtiene el primer nombre de la persona
     * @return una cadena de texto con el primer nombre de la persona.
     */
    public String getFirstname1() {
        return firstname1;
    }

    /**
     * Establece el primer nombre de la persona
     * @param firstname1 cadena de texto con el nombre de la persona
     */
    public void setFirstname1(String firstname1) {
        this.firstname1 = firstname1;
    }

    /**
     * Obtine el segundo nombre de la persona
     * @return una cadena de texto con el segundo nombre de la persona.
     */
    public String getFisrtname2() {
        return firstname2;
    }

    /**
     * Establece el segundo nombre de la persona
     * @param fisrtname2 cadena de texto con el segundo nombre de la persona.
     */
    public void setFirstname2(String fisrtname2) {
        this.firstname2 = fisrtname2;
    }

    /**
     * Obtiene el primer apellido de la persona.
     * @return cadena de texto con el primer apellido de la persona.
     */
    public String getLastname1() {
        return lastname1;
    }

    /**
     * Establece el primer apellido de la persona
     * @param lastname1 cadena de texto con el primer apellido de la persona
     */
    public void setLastname1(String lastname1) {
        this.lastname1 = lastname1;
    }

    /**
     * Obtiene el segundo apellido de la persona
     * @return cadena de texto con el segundo apellido de la persona.
     */
    public String getLastname2() {
        return lastname2;
    }

    /**
     * Establece el segundo apellido de la persona
     * @param lastname2 cadena de texto con el segunso apellido de la persona.
     */
    public void setLastname2(String lastname2) {
        this.lastname2 = lastname2;
    }

    /**
     * Obtine el identificador de la persona
     * @return número identificador de la persona
     */
    public long getId() {
        return id;
    }

    /**
     * Establece el identificador de la persona.
     * @param id número identificador de la persona.
     */
    public void setId(long id) {
        this.id = id;
    }

    /**
     * Obtiene el grupo sanguineo de la persona
     * @return
     */
    public String getBloodgroup() {
        return bloodgroup;
    }

    /**
     * Establece el grupo sanguineo de persona
     * @param bloodgroup carácter con el grupo sanquineo de la persona.
     */
    public void setBloodgroup(String bloodgroup) {
        this.bloodgroup = bloodgroup;
    }

    /**
     * Obtiene el RH saguineo de la persona
     * @return carácter con el grupo sanguineo de la persona
     */
    public char getRH() {
        return RH;
    }

    /**
     * Establece el RH sanguineo de la persona
     * @param RH caracter con el RH sanguineo de la persona
     */
    public void setRH(char RH) {
        this.RH = RH;
    }

    /**
     * Obtiene la fecha de nacimiento de la persona
     * @return cadena de texto con la fecha de nacimiento de la persona.
     */
    public long getBirthday() {
        return birthday;
    }

    /**
     * Establece la fecha de nacimiento de la persona.
     * @param birthday cadena de texto con la fecha de nacimiento de la persona.
     */
    public void setBirthday(long birthday) {
        this.birthday = birthday;
    }
}
