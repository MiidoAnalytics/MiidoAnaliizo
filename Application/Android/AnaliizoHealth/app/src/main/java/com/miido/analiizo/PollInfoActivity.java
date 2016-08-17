package com.miido.analiizo;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.FamilyGroup;
import com.miido.analiizo.model.Field;
import com.miido.analiizo.model.Form;
import com.miido.analiizo.model.FormsLayouts;
import com.miido.analiizo.model.Person;
import com.miido.analiizo.model.Poll;
import com.miido.analiizo.util.SqlHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;

public class PollInfoActivity extends ActionBarActivity implements View.OnClickListener,AdapterView.OnItemClickListener{

    private ListView listView;
    private PersonAdapter adapter;
    private Toolbar toolbar;

    private TextView area;
    private TextView population;
    private TextView groupName;
    private TextView address;
    private TextView phoneNumber;
    private TextView groupsNumber;

    private Poll currentPoll;
    private int pollId;
    private int homeId;
    private FamilyGroup familyGroup;

    private Constants constants = new Constants();
    private SqlHelper sqlHelper;

    public static final String CURRENT_POLL_EXTRA = "current_poll";
    public static final String POLL_ID_EXTRA = "poll_id";
    public static final String HOME_ID_EXTRA = "home_id";

    public static final int HOME_INFO_REQUEST_CODE = 1000;
    public static final int PERSON_INFO_REQUEST_CODE = 1001;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.poll_info_layout);

        currentPoll = getIntent().getParcelableExtra(CURRENT_POLL_EXTRA);
        //pollId = getIntent().getIntExtra(POLL_ID_EXTRA, 0);
        homeId = getIntent().getIntExtra(HOME_ID_EXTRA, 0);

        area = (TextView) findViewById(R.id.area);
        population = (TextView) findViewById(R.id.population);
        groupName = (TextView) findViewById(R.id.groupName);
        address = (TextView) findViewById(R.id.address);
        phoneNumber = (TextView) findViewById(R.id.phoneNumber);
        groupsNumber = (TextView) findViewById(R.id.groupsNumber);

        sqlHelper = new SqlHelper(this);
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();

        this.familyGroup = getFamilyGroupFromDB(homeId);
        setHomeFamilyLabels(this.familyGroup);

        toolbar  = (Toolbar) findViewById(R.id.poll_info_toolbar);
        toolbar.setTitle("Título de la encuesta");
        toolbar.setSubtitle("Subtítulo de la encuesta");
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        toolbar.setNavigationOnClickListener(this);

        listView = (ListView) findViewById(R.id.personList);
        adapter = new PersonAdapter(this, getPersonsFromDB(homeId));
        listView.setAdapter(adapter);
        listView.setOnItemClickListener(this);
    }

    private void setHomeFamilyLabels(FamilyGroup familyGroup){
        area.setText(area.getText() + familyGroup.getArea());
        population.setText(population.getText() + familyGroup.getPopulation());
        groupName.setText(groupName.getText() + familyGroup.getFamilyGroup());
        address.setText(address.getText() + familyGroup.getAddress());
        phoneNumber.setText(phoneNumber.getText() + "" + familyGroup.getPhoneNumber());
        groupsNumber.setText(groupsNumber.getText() + "" + familyGroup.getFamilyGroups());
    }

    private int getPersonsCount(int homeId)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(String.format("SELECT COUNT(id) FROM person WHERE homeid = %s ;", homeId));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        int count = 0;
        if(cursor.getCount() > 0){
            count = cursor.getInt(0);
        }
        cursor.close();
        return count;
    }

    private int getSatisfactionCount(int homeId)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(constants.CREATE_SATISFACTION_RESPONSE_QUERY);
        sql.execQuery();
        sql.setQuery(String.format("SELECT COUNT(id) FROM satisfaction WHERE homeid = %s ;", homeId));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        int count = 0;
        if(cursor.getCount() > 0){
            count = cursor.getInt(0);
        }
        cursor.close();
        return count;
    }

    private void updateHomeStatus(int homeId,int status)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(String.format("UPDATE home SET status = %s where id = %s", status, homeId));
        sql.execUpdate();
    }

    private FamilyGroup getFamilyGroupFromDB(int idFamily)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(String.format(constants.SELECT_HOME_INFO_BY_ID_QUERY, idFamily, 0));
        sql.execQuery();
        FamilyGroup familyGroup = null;
        Cursor cursor = sql.getCursor();
        if(cursor.getCount() > 0){
            String content = cursor.getString(2);
            try{
                JSONObject json = new JSONObject(content);
                familyGroup = new FamilyGroup(
                        json.getString("Area"),
                        json.getString("Poblacion"),
                        json.getString("Familia"),
                        json.getString("Direccion"),
                        json.getInt("Telefono"),
                        json.getInt("cugrfacoesvi"),
                        json.getInt("cupehaesvi"),
                        json.getInt("cupepeco"));
            }catch (JSONException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
        cursor.close();
        return familyGroup;
    }

    private ArrayList<Person> getPersonsFromDB(int idFamily)throws SQLiteException{
        sqlHelper.setQuery(String.format(constants.SELECT_PERSONS_INFO_QUERY, idFamily));
        sqlHelper.execQuery();
        ArrayList<Person> persons = new ArrayList<>();
        Cursor cursor = sqlHelper.getCursor();
        for(int i = 0; i < cursor.getCount(); i++){
            int id = cursor.getInt(0);
            String content = cursor.getString(2);
            try {
                JSONObject json = new JSONObject(content);

                Person person = new Person(
                        id,
                        json.getInt("documento"),
                        json.getString("tipoId"),
                        json.getString("nombre"),
                        json.has("sNombre") ? json.getString("sNombre") : "",
                        json.getString("apellido"),
                        json.has("sApellido") ? json.getString("sApellido") : "",
                        json.getString("genero").toUpperCase().startsWith("F") ? 'F' : 'M',
                        "",' ',0
                );
                person.setPosition(id);
                persons.add(person);
                cursor.moveToNext();
            }catch (JSONException ex){
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
        cursor.close();
        return persons;
    }

    private void startSatisfactionPoll(){
        Intent intent = new Intent(this, FormActivity.class);
        intent.putExtra(FormActivity.CURRENT_POLL_EXTRA, currentPoll);
        intent.putExtra(FormActivity.POLL_TYPE_EXTRA, FormsLayouts.PollType.SATISFACTION.code());
        intent.putExtra(FormActivity.HOME_ID_EXTRA, homeId);
        startActivityForResult(intent, HOME_INFO_REQUEST_CODE);
    }

    private void startPersonPoll(Person person){
        Intent intent = new Intent(this, FormActivity.class);
        if(person != null) {
            try {
                JSONArray fields = new JSONArray()
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getTypeId()).put(Field.JField.NAME.code(),"tipoId"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getId()+"").put(Field.JField.NAME.code(),"documento"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getFirstname1()).put(Field.JField.NAME.code(),"nombre"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getFisrtname2()).put(Field.JField.NAME.code(),"sNombre"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getLastname1()).put(Field.JField.NAME.code(),"apellido"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getLastname2()).put(Field.JField.NAME.code(),"sApellido"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getBloodgroup()).put(Field.JField.NAME.code(),"fecNac"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),person.getBloodgroup().equals("") ? "" : calculateYearsOld(person.getBloodgroup())).put(Field.JField.NAME.code(),"Edad"))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),""))
                        .put(new JSONObject().put(Field.JField.VALUE.code(),!person.getFirstname1().equals("") ? "CAJA DE COMPENSACIÓN FAMILIAR DE LA GUAJIRA" : "").put(Field.JField.NAME.code(),"eps"));
                JSONObject jPerson = new JSONObject();
                jPerson.put(Form.JForm.ID.code(),2);
                jPerson.put(Form.JForm.FIELDS.code(),fields);
                intent.putExtra(FormActivity.PERSON_CONTENT_EXTRA, jPerson.toString());
            } catch (JSONException ex) {
                Log.e(getClass().getName(), ex.getMessage());
            }
        }
        intent.putExtra(FormActivity.CURRENT_POLL_EXTRA, currentPoll);
        intent.putExtra(FormActivity.POLL_TYPE_EXTRA, FormsLayouts.PollType.PERSON.code());
        intent.putExtra(FormActivity.HOME_ID_EXTRA, homeId);
        startActivityForResult(intent, PERSON_INFO_REQUEST_CODE);
    }

    private String calculateYearsOld(String strBirthDay){
        SimpleDateFormat dateFormat = new SimpleDateFormat(new Constants().DATE_FORMAT);
        Calendar calendar = Calendar.getInstance();
        int cy = calendar.get(Calendar.YEAR),cm = calendar.get(Calendar.MONTH),cd = calendar.get(Calendar.DAY_OF_MONTH);
        try {
            calendar.setTime(dateFormat.parse(strBirthDay));
            int by = calendar.get(Calendar.YEAR),bm = calendar.get(Calendar.MONTH),bd = calendar.get(Calendar.DAY_OF_MONTH);
            int yearsOld = cy - by - (cm <= bm && cd < bd ? 1 : 0);
            return String.valueOf(yearsOld);
        }catch (ParseException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
        return null;
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
        Intent intent = new Intent(this, FormActivity.class);
        intent.putExtra(FormActivity.CURRENT_POLL_EXTRA, currentPoll);
        intent.putExtra(FormActivity.POLL_TYPE_EXTRA, FormsLayouts.PollType.PERSON.code());
        intent.putExtra(FormActivity.HOME_ID_EXTRA, homeId);
        intent.putExtra(FormActivity.PERSON_ID_EXTRA, adapter.getItem(position).getPosition());
        startActivityForResult(intent, PERSON_INFO_REQUEST_CODE);
    }

    private void showAlertDialog(String title, String message){
        AlertDialog dialog = com.miido.analiizo.util.Dialog.Alert(this, title, message);
        dialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialog) {
                showSearchPersonDialog();
                dialog.dismiss();
            }
        });
        dialog.show();
    }

    private void showSearchPersonDialog(){
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Busqueda de afiliados");
        Constants constants = new Constants();
        builder.setMessage(constants._EM002 + "\n" + constants._EM003);
        final AutoCompleteTextView ac = new AutoCompleteTextView(this);
        ac.setHint("Nombre o Identificación");
        ac.setBackgroundResource(R.drawable.spinner);
        LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
        );
        ac.setLayoutParams(layoutParams);
        ac.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int start, int before, int count) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int start, int before, int count) {
                if(charSequence.length() > 2){
                    try {
                        ArrayList<String> persons = getPersonsFromDB(charSequence.toString());
                        final ArrayAdapter<String> ad = new ArrayAdapter<>(getApplicationContext(), R.layout.dropdown, persons);
                        ad.setNotifyOnChange(true);
                        ad.notifyDataSetChanged();
                        ad.getFilter().filter(charSequence, ac);
                        ac.setAdapter(ad);
                        ac.setThreshold(charSequence.length() - 1);
                    }catch (SQLiteException ex){
                        Log.e(getClass().getName(), ex.getMessage());
                    }

                }
            }

            @Override
            public void afterTextChanged(Editable editable) {

            }
        });

        builder.setView(ac);
        //builder.setIcon(getResources().getDrawable(R.drawable.ic_action_search));
        builder.setNegativeButton("Nuevo", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                Person person = new Person();
                try{
                    person.setId(Long.parseLong(ac.getText().toString()));
                    startPersonPoll(person);
                }catch (NumberFormatException ex){
                    Log.e(getClass().getName(), ex.getMessage());
                    showAlertDialog(getString(R.string.app_alert_dialog_title), "Ingrese el número de identificación de la persona.");
                }
            }
        });
        builder.setPositiveButton("Afiliado", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                if(!ac.getText().toString().equals("")) {
                    String id = ac.getText().toString().split(" ")[0];
                    Log.e("SELECTION", ac.getText().toString());
                    Person person = getPersonFromDB(id);
                    if(person != null) {
                        startPersonPoll(person);
                    }else{
                        showAlertDialog(getString(R.string.app_alert_dialog_title), "La persona ingresada no se encuentra en la base de datos de afiliados");
                    }
                }else{
                    showAlertDialog(getString(R.string.app_alert_dialog_title), "Debe seleccionar una persona en la base de datos de afiliados");
                }
            }
        });
        builder.setNeutralButton("Cancelar", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });
        AlertDialog dialog = builder.create();
        dialog.setCancelable(false);
        dialog.show();
    }

    private ArrayList<String> getPersonsFromDB(String key)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.itemsDatabase;
        sql.OOCDB();
        sql.setQuery("SELECT DISTINCT * FROM persons WHERE " +
                "vchIdRc LIKE '"+key+"%' OR vchIdTI LIKE '"+key+"%' OR " +
                "vchIdCC LIKE '"+key+"%' OR vchIdOther LIKE '"+key+"%' OR " +
                "vchFirstName LIKE '"+key+"%' OR vchLastName LIKE '"+key+"%' LIMIT 0,5 ;");
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        ArrayList<String> persons = new ArrayList<>();
        for(int i = 0; i < cursor.getCount(); i++){
            String typeId = cursor.getString(1);
            int idPos = typeId.equals("RC") ? 2 : typeId.equals("TI") ? 3 : typeId.equals("CC") ? 4 : 5;
            persons.add(cursor.getString(idPos) + " - " + cursor.getString(6) + " " + cursor.getString(7));
            cursor.moveToNext();
        }
        cursor.close();
        return persons;
    }

    private Person getPersonFromDB(String id)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.itemsDatabase;
        sql.OOCDB();
        sql.setQuery(String.format("SELECT DISTINCT * FROM persons WHERE vchIdRc = '%s' OR vchIdTI = '%s' " +
                "OR vchIdCC = '%s' OR vchIdOther = '%s' ",id, id, id, id));
        sql.execQuery();
        Cursor cursor = sql.getCursor();
        Person person = null;
        if(cursor.getCount() > 0 ){
            person = new Person();
            person.setPosition(cursor.getInt(0));
            person.setTypeId(cursor.getString(1));
            int pos = person.getTypeId().equals("RC") ? 2 : person.getTypeId().equals("TI") ? 3 : person.getTypeId().equals("CC") ? 4 : 5;
            person.setTypeId(person.getTypeId().equals("RC") ? "Registro civil"
                    : person.getTypeId().equals("TI") ? "Targeta de indentidad"
                    : person.getTypeId().equals("CC") ? "Cédula de ciudadanía" : "");
            person.setId(cursor.getLong(pos));
            SimpleDateFormat dateFormat = new SimpleDateFormat(new Constants().DATE_FORMAT);
            if(cursor.getString(7) != null && !cursor.getString(7).equals("")){
                //try {
                    //person.setBloodgroup(dateFormat.parse(cursor.getString(7)).toString());
                    person.setBloodgroup("1985-11-29");
                //}catch (ParseException ex){
                  //  Log.e(getClass().getName(), ex.getMessage());
                //}
            }
            String firstName = cursor.getString(6);
            String[] fNames = firstName.split(" ");
            String n2 = "";
            for(int i = 0; i < fNames.length; i++){
                if(i == 0){
                    person.setFirstname1(fNames[i]);
                }else{
                    if(i == fNames.length -1 ){
                        n2 += fNames[i];
                    }else {
                        n2 += fNames[i] + " ";
                    }
                }
            }
            person.setFirstname2(n2);
            String lastName = cursor.getString(7);
            String[] lNames = lastName.split(" ");
            n2 = "";
            for(int i = 0; i < lNames.length; i++){
                if(i == 0){
                    person.setLastname1(lNames[i]);
                }else{
                    if(i == lNames.length - 1){
                        n2 += lNames[i];
                    }else{
                        n2 += lNames[i] + " ";
                    }
                }
            }
            person.setLastname2(n2);
        }
        cursor.close();
        return person;
    }

    /**
     * Dispatch incoming result to the correct fragment.
     *
     * @param requestCode
     * @param resultCode
     * @param data
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(resultCode == RESULT_OK){
            if(requestCode == PERSON_INFO_REQUEST_CODE){
                adapter.clear();
                adapter.addAll(getPersonsFromDB(homeId));
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        adapter.notifyDataSetChanged();
                    }
                });
            }else{
                if(requestCode == HOME_INFO_REQUEST_CODE){
                    // TODO: 08/07/2016 implementación de respuesta encuesta de satisfacción. 
                }
            }
        }
    }

    private void launchConfirmExitDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this,getString(R.string.app_confirm_dialog_title),
                "¿Desea salir de la encuesta?\nla encuesta será pausada automaticamente");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                setResult(RESULT_OK);
                finish();
            }
        });
        d.show();
    }

    private void launchConfirmSaveDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this,getString(R.string.app_confirm_dialog_title),
                "¿Desea guardar la encuesta?\nTodos los datos seran guardados");
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                updateHomeStatus(homeId, 2);
                setResult(RESULT_OK);
                finish();
            }
        });
        d.show();
    }

    @Override
    public void onClick(View view) {
        onBackPressed();
    }

    @Override
    public void onBackPressed() {
        launchConfirmExitDialog();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.poll_info_menu, menu);
        return true;
    }

    private void showPersonsDialog(int persons, int currentPersons){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,
                getString(R.string.app_alert_dialog_title),
                "Usted especificó que habitan " + persons + " personas en esta vivienda.\n" +
                        "Faltan " + (persons - currentPersons) + " personas por agregar.");
        d.show();
    }

    private void showPersonsDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,
                getString(R.string.app_alert_dialog_title),
                "Ya se ha completado el número de personas que habitan esta vivienda");
        d.show();
    }

    private void showSatisfactionDialog(){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,
                getString(R.string.app_alert_dialog_title),
                "Usted aún no ha llenado la encusta de satisfacción de vivienda y eps");
        d.show();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int personsCount = getPersonsCount(homeId);
        switch (item.getItemId()){
            case R.id.addHouse: startSatisfactionPoll();break;
            case R.id.addPerson:
                if(personsCount == familyGroup.getPersonsNumber()){
                    showPersonsDialog();
                }else{
                    showSearchPersonDialog();
                }
                break;
            case R.id.savePoll:
                personsCount = getPersonsCount(homeId);
                if(familyGroup.getPersonsNumber() > personsCount){
                    showPersonsDialog(familyGroup.getPersonsNumber(),personsCount);
                }else{
                    if(getSatisfactionCount(homeId) == 0){
                        showSatisfactionDialog();
                    }
                }
                if(personsCount == familyGroup.getPersonsNumber() && getSatisfactionCount(homeId) == 1){
                    launchConfirmSaveDialog();
                }
                break;
        }
        return true;
    }

    private class PersonAdapter extends ArrayAdapter<Person>{

        public PersonAdapter(Context context,ArrayList<Person> persons) {
            super(context, -1, persons);
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent) {
            View view = convertView;
            if(view == null){
                LayoutInflater inflater = (LayoutInflater) getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                view = inflater.inflate(R.layout.person_item_layout, parent, false);
            }
            Person person = getItem(position);
            TextView firstName = (TextView) view.findViewById(R.id.firstName);
            firstName.setText(person.getFirstname1() + " " + person.getFisrtname2());
            TextView lastName = (TextView) view.findViewById(R.id.lastName);
            lastName.setText(person.getLastname1() + " " + person.getLastname2());
            TextView typeId = (TextView) view.findViewById(R.id.typeId);
            typeId.setText(person.getTypeId());
            TextView id = (TextView) view.findViewById(R.id.id);
            id.setText(person.getId() + "");
            return view;
        }
    }
}
