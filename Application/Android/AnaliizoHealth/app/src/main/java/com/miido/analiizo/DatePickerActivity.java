package com.miido.analiizo;

import android.content.Intent;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.DatePicker;
import android.widget.ImageButton;

import com.miido.analiizo.mcompose.Constants;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;

public class DatePickerActivity extends ActionBarActivity implements DatePicker.OnDateChangedListener,View.OnClickListener{

    private DatePicker datePicker;
    private ImageButton setDateButton;
    private String date;

    public static final String DATE_EXTRA = "DATE";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.datepicker_layout);

        setDateButton = (ImageButton) findViewById(R.id.setDateButton);
        setDateButton.setOnClickListener(this);

        datePicker = (DatePicker) findViewById(R.id.datePicker);
        Calendar calendar = Calendar.getInstance();

        datePicker.setMaxDate(calendar.getTimeInMillis());
        datePicker.init(calendar.get(Calendar.YEAR),calendar.get(Calendar.MONTH),calendar.get(Calendar.DAY_OF_MONTH), this);
        calendar.set(calendar.get(Calendar.YEAR) - 100,calendar.get(Calendar.MONTH),calendar.get(Calendar.DAY_OF_MONTH));
        datePicker.setMinDate(calendar.getTimeInMillis());

        String date = getIntent().getStringExtra(DATE_EXTRA);
        if(date != null){
            SimpleDateFormat dateFormat = new SimpleDateFormat(new Constants().DATE_FORMAT);
            try {
                calendar.setTime(dateFormat.parse(date));
                datePicker.init(calendar.get(Calendar.YEAR),calendar.get(Calendar.MONTH),calendar.get(Calendar.DAY_OF_MONTH), this);
            }catch (ParseException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }

    }

    @Override
    public void onDateChanged(DatePicker datePicker, int year, int month, int dayMonth) {
        Calendar calendar = Calendar.getInstance();
        calendar.set(year,month,dayMonth);
        SimpleDateFormat dateFormat = new SimpleDateFormat(new Constants().DATE_FORMAT);
        this.date = dateFormat.format(calendar.getTime());
        setDateButton.setVisibility(View.VISIBLE);
    }

    @Override
    public void onBackPressed(){
        setResult(RESULT_CANCELED);
        finish();
    }

    @Override
    public void onClick(View view) {
        if(view.getId() == R.id.setDateButton) {
            Intent intent = new Intent();
            intent.putExtra(DATE_EXTRA, this.date);
            setResult(RESULT_OK, intent);
            finish();
        }
    }
}
