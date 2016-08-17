package com.miido.analiizo.util;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.os.Bundle;
import android.widget.DatePicker;

import java.util.Calendar;

/**
 * Dá formato al objeto selector de fechas.
 * @author Alvaro Salagado MIIDO S.A.S 29/07/2015.
 * @version 1.0
 * @see DialogFragment
 * @see android.app.DatePickerDialog.OnDateSetListener
 */
public class DatePickerFragment extends DialogFragment
        implements DatePickerDialog.OnDateSetListener {

    /**
     * Método que se ejecuta cuando el dialogo es creado
     * @param savedInstanceState estado de la actividad
     * @return objecto DatePickerDialog
     * @see DatePickerDialog
     */
    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        // Use the current date as the default date in the picker
        final Calendar c = Calendar.getInstance();
        int year = c.get(Calendar.YEAR);
        int month = c.get(Calendar.MONTH);
        int day = c.get(Calendar.DAY_OF_MONTH);

        // Create a new instance of DatePickerDialog and return it
        return new DatePickerDialog(getActivity(), this, year, month, day);
    }

    /**
     * Método que respnde al evento cuando una fecha es colocada en el DatePicker
     * @param view vista que ejecta el evento
     * @param year año
     * @param month mes
     * @param day día
     */
    public void onDateSet(DatePicker view, int year, int month, int day) {
        // Do something with the date chosen by the user
        //// TODO: 25/01/2016
    }
}