package com.miido.analiizoOBRAS;

import android.database.Cursor;
import android.graphics.Color;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import com.miido.analiizoOBRAS.model.Poll;
import com.miido.analiizoOBRAS.util.SqlHelper;

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Set;

import lecho.lib.hellocharts.listener.ColumnChartOnValueSelectListener;
import lecho.lib.hellocharts.model.Axis;
import lecho.lib.hellocharts.model.AxisValue;
import lecho.lib.hellocharts.model.Column;
import lecho.lib.hellocharts.model.ColumnChartData;
import lecho.lib.hellocharts.model.SubcolumnValue;
import lecho.lib.hellocharts.util.ChartUtils;
import lecho.lib.hellocharts.view.ColumnChartView;

/**
 * Muestra un gráfico de columnas con información de las encuestas respondidas en el dispositivo
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S 21/01/2016
 * @version 1.0
 * @see ActionBarActivity
 */
public class Statistics extends ActionBarActivity implements ColumnChartOnValueSelectListener{

    private ColumnChartView chart;
    private ColumnChartData data;
    private Toolbar toolbar;
    private ArrayList<Poll> polls;

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.statistics_layout_new);

        toolbar =(Toolbar) findViewById(R.id.chart_tool_bar);
        toolbar.setTitle("Encuestas realizadas");

        chart = (ColumnChartView) findViewById(R.id.chart1);
        chart.setOnValueTouchListener(this);
        chart.setInteractive(true);

        polls = getIntent().getParcelableArrayListExtra("polls");

        generateDefaultData();
    }

    /**
     * Médoto que responde a el evento de creción de los elementos de un menú
     * @param menu menu creado
     * @return verdadero si se selecciona un menú, falso de lo contrario.
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.column_chart, menu);
        return true;
    }

    /**
     * Método que responde al evento de selección de una opción de menú
     * @param item opción seleccionada en el menú.
     * @return verdadero si se selecciona un menu, falso de lo contrario.
     */
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        return super.onOptionsItemSelected(item);
    }

    /**
     * Hace una busqueda de una encuesta entre un listado de encuestas respondidas
     * @param structureid identificador de la estructura de la encuesta
     * @return la encuesta encontrada o null si no hay ninguna encuesta relacionada.
     * @see Poll
     */
    private Poll findPoll(long structureid){
        for (Poll poll : polls){
            if(poll.getStructureId() == structureid){
                return poll;
            }
        }
        return null;
    }

    /**
     * Obtiene un conteo de todas las encuestas respondidas en la base de datos local como un objeto de estructura llave-valor
     * HashMap<TOTAL_ENCUESTAS,ID_ENCUESTA>
     * @return una estrucura de datos HashMap con un listado de objetos llave-valor
     * @see HashMap
     * @see SqlHelper
     */
    private HashMap<Long,Integer> getPollsRespondents(){
        SqlHelper sql = new SqlHelper(getApplicationContext());
        sql.databaseName = "POLLDATA_DB";
        sql.OOCDB();
        sql.setQuery("SELECT idstructure,count(idstructure) as count FROM poll group by idstructure");
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        HashMap<Long,Integer> respondents = new HashMap<>();
        for(int i = 0; i<cursor.getCount(); i++){
            respondents.put(cursor.getLong(0),cursor.getInt(1));
            cursor.moveToNext();
        }
        cursor.close();
        return respondents;
    }

    /**
     * Genera un Gráfico de columnas con los datos obtenidos de las encuestas respondidas
     * @see HashMap
     * @see #getPollsRespondents()
     * @see ColumnChartView
     * @see ColumnChartData
     */
    private void generateDefaultData() {
        HashMap<Long, Integer> respondents = getPollsRespondents();
        int numSubcolumns = 1;
        // Column can have many subcolumns, here by default I use 1 subcolumn in each of 8 columns.
        List<Column> columns = new ArrayList<Column>();
        List<SubcolumnValue> values;
        Collection<Integer> respondentsCount = respondents.values();
        Iterator<Integer> i = respondentsCount.iterator();

        while (i.hasNext()) {
            values = new ArrayList<SubcolumnValue>();
            for (int j = 0; j < numSubcolumns; ++j) {
                values.add(new SubcolumnValue(i.next(), ChartUtils.pickColor()));
            }
            Column column = new Column(values);
            column.setHasLabels(true);
            columns.add(column);
        }

        data = new ColumnChartData(columns);

        Axis axisX = new Axis().setTextColor(Color.parseColor("#256027"));
        ArrayList axisValues = new ArrayList();
        Set<Long> respodentsKeys = respondents.keySet();
        Iterator<Long> j = respodentsKeys.iterator();
        int p = 0;
        while (j.hasNext()) {
            long key = j.next();
            axisValues.add(new AxisValue(p, (key + "").toCharArray()));
            p++;
        }
        axisX.setValues(axisValues);
        Axis axisY = new Axis().setHasLines(true).setTextColor(Color.parseColor("#256027"));

        axisX.setName("Encuestas");
        axisY.setName("# Encuestados");

        data.setAxisXBottom(axisX);
        data.setAxisYLeft(axisY);


        chart.setColumnChartData(data);
    }

    /**
     * Método lanzado cuando se selecciona una columna en el gráfico.
     * @param columnIndex pocisión de la columna
     * @param subcolumnIndex pocisión de la subcolumna
     * @param value valor de la columna
     */
    @Override
    public void onValueSelected(int columnIndex, int subcolumnIndex, SubcolumnValue value) {
        String label = "";
        for (char c : data.getAxisXBottom().getValues().get(columnIndex).getLabelAsChars()) {
            label += c;
        }
        Poll poll = findPoll(Long.parseLong(label));
        Toast.makeText(getApplicationContext(), "Encuesta: " + poll.getTitle(), Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onValueDeselected() {
        // TODO Auto-generated method stub
    }

}
