package com.miido.miido.mcompose;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

/**
 * *******************************
 * Created by Alvaro on 18/02/2015.
 * *******************************
 */
public class interfaceBuilder {

    private objectCreator ocClass;
    private Context context;
    private Object[][] objects;

    private Boolean buildResults;

    private String[][] components;

    private String[][] optionsList;

    private int height;
    private int width;
    private int counter;

    public interfaceBuilder(Context context) {
        this.context = context;
        this.buildResults = false;
        ocClass = new objectCreator(this.context);
    }

    public void starInterfaceBuilder() {
        this.counter = 0;
        objects = new Object[components.length][9];
        if (this.components.length > 0) {
            if (this.components[0].length > 0) {
                if (buildInterface()) {
                    this.buildResults = true;
                }
            }
        } else {
            this.buildResults = false;
        }
    }

    private Boolean buildInterface() {
        this.counter = 0;
        try {
            for (String[] component : this.components) {
                ocClass.setName(component[1]);  //Name
                ocClass.setType(decodeObjectType(component[3])); //Type
                ocClass.setHint(component[5]);//2 //Hint
                ocClass.setInputRules(decodeObjectRules(component[6]));//2 //Rules
                ocClass.setMaxLength(Integer.parseInt(component[7]));//2 //Length
                ocClass.setOptionsList(findOptionsAvailable(Integer.parseInt(component[0])));
                ocClass.setReadOnly(component[11]);
                ocClass.setAutoCompleteTable(component[12]);
                ocClass.setRequired(Boolean.parseBoolean(component[4]));
                this.objects[this.counter][0] = ocClass.buildObject();
                this.objects[this.counter][1] = component[2]; //Label
                this.objects[this.counter][2] = component[9];//2 //Form
                this.objects[this.counter][3] = component[0]; //Id
                this.objects[this.counter][4] = component[1]; //FieldName
                this.objects[this.counter][5] = component[4]; // Required
                this.counter++;
            }
            return true;
        } catch (Exception ex) {
            //Toast.makeText(this.context, "buildInterfaceException::"+this.counter+ex.getMessage(), Toast.LENGTH_LONG).show();
            return false;
        }
    }

    public int decodeObjectType(String type) {
        switch (type) {
            case "tf":
                return 1;
            case "dp":
                return 2;
            case "cb":
                return 3;
            case "rg":
                return 4;
            case "ac":
                return 5;
            case "tv":
                return 6;
            case "sp":
                return 8;
            default:
                Toast.makeText(this.context, "NoObjectType", Toast.LENGTH_LONG).show();
                return 0;
        }
    }

    public int decodeObjectRules(String rules) {
        switch (rules) {
            case "vch":
                return 0;
            case "int":
                return 1;
            case "eml":
                return 2;
            case "dec":
                return 3;
            default:
                //Toast.makeText(this.context, "NoObjectRules"+this.counter, Toast.LENGTH_LONG).show();
                return 0;
        }
    }

    public List<String> findOptionsAvailable(int id) throws Exception {
        List<String> optionsFound = new ArrayList<>();
        for (String[] option : this.optionsList) {
            String[] relId = option[1].split("~");
            for (String id_tmp : relId) {
                if (Integer.parseInt(id_tmp) == id) {
                    //Toast.makeText(this.context, option[2], Toast.LENGTH_SHORT).show();
                    String[] options_tmp = option[2].split("~");
                    for (int c = 0; c < options_tmp.length; c++) {
                        options_tmp[c] = options_tmp[c].replace("\"", "");
                        optionsFound.add(c, options_tmp[c]);
                    }
                }
            }
        }
        return optionsFound;
    }

    //SETTERS
    public boolean setArrayValue(String[][] components) {
        try {
            if (this.height == components.length) {
                if (this.width == components[0].length) {
                    this.components = new String[this.height][this.width];
                    this.components = components;
                    return true;
                }
            }
        } catch (Exception e) {
            Log.e("ib.sav", e.getMessage());
        }
        return false;
    }

    public void setArrayWidht(int width) {
        this.width = width;
    }

    public void setOptionsList(String[][] optionsList) {
        this.optionsList = optionsList;
    }

    //GETTERS

    public void setArrayHeight(int height) throws Exception {
        this.height = height;
    }

    public Boolean getBuildResults() {
        return this.buildResults;
    }

    public Object[][] getObjects() {
        return this.objects;
    }
}