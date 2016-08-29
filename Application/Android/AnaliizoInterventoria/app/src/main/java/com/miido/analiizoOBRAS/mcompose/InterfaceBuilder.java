package com.miido.analiizoOBRAS.mcompose;

import android.content.Context;
import android.util.Log;
//import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

/**
 * crea la interfaz de los formularios de la encuesta.
 * @version Alvaro Salgado MIIDO S.A.S 18/02/2015.
 * @version 1.0
 */
public class InterfaceBuilder {

    private ObjectCreator objectCreator;
    private Context context;
    private Object[][] objects;

    private Boolean buildResults;

    private String[][] components;

    private String[][] optionsList;

    private int height;
    private int width;
    private int counter;

    /**
     * constructor
     * @param context contexto del objeto.
     */
    public InterfaceBuilder(Context context) {
        this.context = context;
        this.buildResults = false;
        objectCreator = new ObjectCreator(this.context);
    }

    /**
     *
     */
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

    /**
     *
     * @return
     */
    private Boolean buildInterface() {
        this.counter = 0;
        try {
            for (String[] component : this.components) {
                objectCreator.setId(Integer.parseInt(component[0]));//MIGUEEEEEEE
                objectCreator.setReferenceQuestion(component[15]);//MIGUEEEEEEE
                objectCreator.setName(component[1]);  //Name
                objectCreator.setType(decodeObjectType(component[3])); //Type
                objectCreator.setHint(component[5]);//2 //Hint
                objectCreator.setInputRules(decodeObjectRules(component[6]));//2 //Rules
                objectCreator.setMaxLength(Integer.parseInt(component[7]));//2 //Length
                objectCreator.setOptionsList(findOptionsAvailable(Integer.parseInt(component[0])));
                objectCreator.setReadOnly(component[11]);
                objectCreator.setAutoCompleteTable(component[12]);
                objectCreator.setRequired(Boolean.parseBoolean(component[4]));
                this.objects[this.counter][0] = objectCreator.buildObject();
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

    /**
     * Identifica el tipo de objeto a crear
     * @param type tipo de objeto a crear
     * @return dato numerico de 1 al 8 que determina el tipo de objeto a crear., 0 si el objeto no es soportado.
     */
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
                //Toast.makeText(this.context, "NoObjectType", Toast.LENGTH_LONG).show();
                return 0;
        }
    }

    /**
     * identifica el tipo de regla del objeto a crear
     * @param rules tipo de regla
     * @return dato numerico del 0 al 3, toma 0 como defecto.
     */
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

    /**
     * obtiene las opciones asignadas a un elemento o pregunta
     * @param id identificador del el objeto o pregunta.
     * @return un ArrayList con las opciones asignadas al elemento.
     * @throws Exception es lanzada si ocurre un error a obtener el listado de opciones.
     */
    public List<String> findOptionsAvailable(int id) throws Exception {
        List<String> optionsFound = new ArrayList<>();
        try {
            optionsFound.add(0, "Seleccione ...");
            for (String[] option : this.optionsList) {
                String[] relId = option[1].split("~");
                for (String id_tmp : relId) {
                    if (Integer.parseInt(id_tmp) == id) {
                        //Toast.makeText(this.context, option[2], Toast.LENGTH_SHORT).show();
                        String[] options_tmp = option[2].split("~");
                        for (int c = 0; c < options_tmp.length; c++) {
                            if (!options_tmp[c].equals("-")) {
                                options_tmp[c] = options_tmp[c].replace("\"", "");
                                optionsFound.add(optionsFound.size(), options_tmp[c]);
                            }
                        }
                    }
                }
            }
        }catch (Exception ex){
            Log.e(this.getClass().getName(),ex.getMessage());
        }
        return optionsFound;
    }

    /**
     * establece la matriz de componenetes
     * @param components matriz de componentes
     * @return true si establece la matriz de componentes de manera exitosa, false en caso contrario.
     */
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

    /**
     * establece el ancho de la matriz de componentes.
     * @param width valor del ancho de la matriz.
     */
    public void setArrayWidht(int width) {
        this.width = width;
    }

    /**
     * establece la matriz de opciones
     * @param optionsList matriz de opciones.
     */
    public void setOptionsList(String[][] optionsList) {
        this.optionsList = optionsList;
    }

    /**
     * establece el alto de la matriz de componentes
     * @param height calor del alto de la matriz de componentes.
     * @throws Exception
     */
    public void setArrayHeight(int height) throws Exception {
        this.height = height;
    }

    /**
     * obtiene los resultados construidos de la interface
     * @return true si los resultados de la interfaz fueron creados con exito, false en caso contrario.
     */
    public Boolean getBuildResults() {
        return this.buildResults;
    }

    /**
     * obtiene a matriz de los objetos construidos
     * @return matriz de los objetos construidos.
     */
    public Object[][] getObjects() {
        return this.objects;
    }
}