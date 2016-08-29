package com.miido.analiizoOBRAS.util;

import android.content.Context;
import android.content.res.AssetManager;
import android.util.Log;

import java.io.InputStream;
import java.util.Properties;

/**
 * Clase encargada de obtener propiedades de la aplicación
 * @author Ing. Miguel Angel Urango Blanco Encryptor S.A.S 28/04/2016.
 */
public class PropertyReader {

    private Context context;
    private Properties properties;

    /**
     * Constructor
     * @param context contexto de la aplicación
     */
    public PropertyReader(Context context){
        this.context=context;
        properties = new Properties();
    }

    /**
     * Obtiene las propiedades desde un archivo.
     * @param file archivo de propiedades.
     * @return propiedades del erchivo.
     */
    public Properties getMyProperties(String file){
        try{
            AssetManager assetManager = context.getAssets();
            InputStream inputStream = assetManager.open(file);
            properties.load(inputStream);

        }catch (Exception e){
            Log.e(getClass().getName(), e.getMessage());
        }

        return properties;
    }

}
