package com.miido.analiizo.model;

/**
 * Contiene la estructura y atributos de un grupo familiar.
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S on 05/07/2016.
 */
public class FamilyGroup {

    private String area;
    private String population;
    private String familyGroup;
    private String address;
    private int phoneNumber;
    private int familyGroups;
    private int personsNumber;
    private int personsNumberE;

    public FamilyGroup(){

    }

    public FamilyGroup(String area, String population, String familyGroup, String address, int phoneNumber, int familyGroups,int personsNumber,int personsNumberE){
        this.area = area;
        this.population = population;
        this.familyGroup = familyGroup;
        this.address = address;
        this.phoneNumber = phoneNumber;
        this.familyGroups = familyGroups;
        this.personsNumber = personsNumber;
        this.personsNumberE = personsNumberE;
    }

    public String getArea() {
        return area;
    }

    public void setArea(String area) {
        this.area = area;
    }

    public String getPopulation() {
        return population;
    }

    public void setPopulation(String population) {
        this.population = population;
    }

    public String getFamilyGroup() {
        return familyGroup;
    }

    public void setFamilyGroup(String familyGroup) {
        this.familyGroup = familyGroup;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public int getPhoneNumber() {
        return phoneNumber;
    }

    public void setPhoneNumber(int phoneNumber) {
        this.phoneNumber = phoneNumber;
    }

    public int getFamilyGroups() {
        return familyGroups;
    }

    public void setFamilyGroups(int familyGroups) {
        this.familyGroups = familyGroups;
    }

    public int getPersonsNumber() {
        return personsNumber;
    }

    public void setPersonsNumber(int personsNumber) {
        this.personsNumber = personsNumber;
    }

    public int getPersonsNumberE() {
        return personsNumberE;
    }

    public void setPersonsNumberE(int personsNumberE) {
        this.personsNumberE = personsNumberE;
    }
}
