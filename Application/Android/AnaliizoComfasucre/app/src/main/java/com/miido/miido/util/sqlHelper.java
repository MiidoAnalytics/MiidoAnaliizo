package com.miido.miido.util;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteException;

import com.miido.miido.mcompose.constants;

/**
 * *******************************
 * Created by Alvaro on 26/03/2015.
 * *******************************
 */
public class sqlHelper {

    public String databaseName;
    constants constants;
    Context context;
    Cursor cursor;
    String query;
    SQLiteDatabase database;

    public sqlHelper(Context context) {
        this.constants = new constants();
        this.context = context;
        this.databaseName = constants.database;
    }

    public void OOCDB() throws SQLiteException {
        this.database = this.context.openOrCreateDatabase(this.databaseName, Context.MODE_PRIVATE, null);
        this.database.setVersion(this.constants.version_name);
    }

    public void execQuery() throws SQLiteException {
        this.cursor = this.database.rawQuery(this.query, null);
        this.cursor.moveToFirst();
    }

    public void execInsert() throws SQLiteException {
        this.database.execSQL(this.query);
    }

    public void execUpdate() throws SQLiteException {
        this.database.execSQL(this.query);
    }

    public Cursor getCursor() {
        return this.cursor;
    }

    public String getQuery() {
        return this.query;
    }

    public void setQuery(String query) {
        this.query = query;
    }

}
