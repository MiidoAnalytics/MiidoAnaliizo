package com.miido.analiizo.util;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteException;

import com.miido.analiizo.mcompose.Constants;

/**
 * Establece una conexión con la base de datos local SQLITE del dispositivo, para crear bases de datos y hacer consultas
 * @author Alvaro Salagado MIIDO S.A.S 26/03/2015.
 * @version 1.0
 */
public class SqlHelper {

    public String databaseName;// nombre de la base de datos
    Constants constants;// Objecto con las constatntes de la aplicación.
    Context context;// contexto de la aplicación.
    Cursor cursor;// cursor de la consulta
    String query;// consulta a ejecutar
    SQLiteDatabase database; // Objeto que establece la conexión con SQLite.

    /**
     * Constructor
     * @param context contexto de la aplicación
     * @see Constants
     */
    public SqlHelper(Context context) {
        this.constants = new Constants();
        this.context = context;
        this.databaseName = constants.structureDatabase;
    }

    /**
     * Abre o crea una nueva conexión a una base de datos
     * @throws SQLiteException es lanzada si ocurre algún problema al crear o abrir una nueva conexión a una base datos.
     * @see SQLiteDatabase
     */
    public void OOCDB() throws SQLiteException {
        this.database = this.context.openOrCreateDatabase(this.databaseName, Context.MODE_PRIVATE, null);
        this.database.setVersion(this.constants.version_name);
    }

    /**
     * Ejecuta una consulta Select en la base de datos.
     * @throws SQLiteException es lanzada si ocurre algún error en la ejecución de la consulta.
     * @see Cursor
     */
    public void execQuery() throws SQLiteException {
        this.cursor = this.database.rawQuery(this.query, null);
        this.cursor.moveToFirst();
    }

    /**
     * Ejecuta una consulta Insert en la base de datos
     * @throws SQLiteException es lanzada si ocurre algún error en la ejecución de la inserción.
     */
    public void execInsert() throws SQLiteException {
        this.database.execSQL(this.query);
    }

    /**
     * Eejecuta una consulta Update en la base de datos
     * @throws SQLiteException es lanzada si ocurre algún error en la ejecución de la actualización.
     */
    public void execUpdate() throws SQLiteException {
        this.database.execSQL(this.query);
    }

    /**
     * Obtiene el cursor de la consulta Select.
     * @return el cursor de la consulta
     * @see Cursor
     */
    public Cursor getCursor() {
        return this.cursor;
    }

    /**
     * obtiene la consulta actual
     * @return texto de la consulta
     */
    public String getQuery() {
        return this.query;
    }

    /**
     * establece la consulta
     * @param query texto de la consulta.
     */
    public void setQuery(String query) {
        this.query = query;
    }

    /**
     * Cierra la conexión a la base de datos sqlite.
     */
    public void close(){
        //cursor.close();
        database.close();
    }

}
