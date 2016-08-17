package com.miido.analiizo;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.model.Field;
import com.miido.analiizo.model.Form;
import com.miido.analiizo.model.FormsLayouts;
import com.miido.analiizo.model.Poll;
import com.miido.analiizo.util.SqlHelper;
import com.unnamed.b.atv.model.TreeNode;
import com.unnamed.b.atv.view.AndroidTreeView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;

/**
 * Muestra un resumen de las preguntas respondidas de cada formulario.
 * @author Ing. Miguel Angel Urango Blanco Miido S.A.S 07/06/2016
 * @see ActionBarActivity
 */
public class ResumeActivity extends ActionBarActivity implements View.OnClickListener{

    private JSONArray forms;
    private Toolbar toolbar;
    private Button backButton;
    private Button saveButton;

    private Poll currentPoll;
    private int pollType;
    private int homeId;

    public static final String RESPONSE_EXTRA = "json_response";
    public static final String CURRENT_POLL_EXTRA = "poll_id";
    public static final String POLL_TYPE_EXTRA = "poll_type";
    public static final String HOME_ID_EXTRA = "home_id";
    public static final String POLL_NAME_EXTRA = "poll_name";
    public static final String PERSON_ID_EXTRA = "person_id";
    public static final String EDIT_MODE_EXTRA = "edit_mode";

    private final int POlL_INFO_REQUEST_CODE = 1000;

    /**
     * Se ejecuta al ser creada la actividad
     * @param savedInstanceState contiene el estado de la actividad
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.resume_layout);

        currentPoll = getIntent().getParcelableExtra(CURRENT_POLL_EXTRA);
        pollType = getIntent().getIntExtra(POLL_TYPE_EXTRA, -1);
        homeId = getIntent().getIntExtra(HOME_ID_EXTRA, 0);

        toolbar = (Toolbar) findViewById(R.id.resumeToolBar);
        toolbar.setTitle("Nombre de la encuesta");
        toolbar.setSubtitle("Resumen de la encuesta");
        toolbar.setLogo(R.drawable.ic_action_report);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);;
        toolbar.setNavigationOnClickListener(this);

        backButton = (Button) findViewById(R.id.resumeBackButton);
        saveButton = (Button) findViewById(R.id.resumeSaveButton);
        backButton.setOnClickListener(this);
        saveButton.setOnClickListener(this);

        try {
            forms = new JSONArray(getIntent().getStringExtra(RESPONSE_EXTRA));
            forms = filterForms(forms);
            TreeNode root = getRootNode(forms);
            AndroidTreeView treeView = new AndroidTreeView(this, root);
            LinearLayout linearLayout = (LinearLayout) findViewById(R.id.treeView);

            linearLayout.addView(treeView.getView());
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
    }

    private JSONArray filterForms(JSONArray forms)throws JSONException{
        JSONArray tmp = new JSONArray();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            if(form.getInt(Form.JForm.INSIDE.code()) == 0){
                tmp.put(form);
            }
        }
        return tmp;
    }

    /**
     * Agrega los formularios a un nodo Raiz
     * @param forms estructura JSON con los formularios repondidos
     * @return el nodo raiz del TreeView
     * @throws JSONException es lanzado si ocurre algún error al procesar el JSONArray
     * @see #getFormNode(JSONObject)
     */
    private TreeNode getRootNode(JSONArray forms) throws JSONException{
        TreeNode root = TreeNode.root();
        for(int i = 0; i < forms.length(); i++){
            JSONObject form = forms.getJSONObject(i);
            //if(form.getInt(Form.JForm.INSIDE.code())  == 0) {
                root.addChild(getFormNode(form));
            //}
        }
        return root;
    }

    /**
     * Obtiene un formulario representado en un TreeNode con sus nodos hijos.
     * @param form JSONObject que representa un formulario
     * @return un formulario represntado en un TreeView con sus nodos hijos que representan las preguntas.
     * @throws JSONException es lanzada si ocurre algún error al procesar un objeto JSON.
     * @see IconTreeItemHolder
     */
    private TreeNode getFormNode(JSONObject form)throws JSONException{
        String formTitle = form.getString(Form.JForm.HEADER.code());
        JSONArray questions = form.getJSONArray(Form.JForm.FIELDS.code());
        int questionsSize = questions.length();
        String formSubTitle = getResources().getQuantityString(R.plurals.resume_questions_sizes,questionsSize,questionsSize);
        IconTreeItem formIconTreeItem = new IconTreeItem(formTitle,formSubTitle,true);
        TreeNode formNode = new TreeNode(formIconTreeItem);
        formNode.setViewHolder(new IconTreeItemHolder(this));
        for(int i = 0; i < questionsSize; i++){
            JSONObject question = questions.getJSONObject(i);
            String questionTitle = question.getString(Field.JField.LABEL.code());
            String questionSubTitle = "No respondida";
            if(question.has(Field.JField.VALUE.code())) {
                questionSubTitle = question.getString(Field.JField.VALUE.code());
            }
            IconTreeItem questionIconTreeItem = new IconTreeItem(questionTitle,questionSubTitle,false);
            TreeNode questionNode = new TreeNode(questionIconTreeItem);
            questionNode.setViewHolder(new IconTreeItemHolder(this));
            formNode.addChild(questionNode);
            if(question.has(Field.JField.SUBFORM.code())){
                // Llamana recursiva para obtener subformularios.
                formNode.addChild(getFormNode(question.getJSONArray(Field.JField.SUBFORM.code()).getJSONObject(0)));
            }

        }
        return formNode;
    }

    private int storeHomeInfoToDB(long structureId,String content,String auxContent,int status)throws SQLiteException,JSONException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_HOME_RESPONSE_QUERY);
        sqlHelper.execQuery();
        SimpleDateFormat dateFormat = new SimpleDateFormat(constants.SimpleDateFormat);
        String date = dateFormat.format(Calendar.getInstance().getTime());
        sqlHelper.setQuery(String.format(constants.INSERT_HOME_RESPONSE_QUERY, structureId, content, auxContent, status,date));
        sqlHelper.execInsert();
        sqlHelper.setQuery(constants.SELECT_LAST_HOME_ID_QUERY);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        int res = 0;
        if(cursor.getCount() > 0){
            res = cursor.getInt(0);
        }
        return res;
    }

    private int storePersonInfoToDB(long homeId, String content,String auxContent)throws SQLiteException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_PERSON_RESPONSE_QUERY);
        sqlHelper.execQuery();
        sqlHelper.setQuery(String.format(constants.INSERT_PERSON_RESPONSE_QUERY, homeId, content,auxContent));
        sqlHelper.execInsert();
        sqlHelper.setQuery(constants.SELECT_LAST_PERSON_ID_QUERY);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        int res = 0;
        if(cursor.getCount() > 0){
            res = cursor.getInt(0);
        }
        return res;
    }

    private int updatePersonInfoFromDB(int personId, String content, String auxContent)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(String.format("UPDATE person SET content = '%s', auxcontent = '%s' WHERE id = %s ;",content,auxContent,personId));
        sql.execUpdate();
        return personId;
    }

    private int storeSatisfactionInfoToDB(int homeId, String content,String auxContent)throws SQLiteException{
        SqlHelper sqlHelper = new SqlHelper(this);
        Constants constants = new Constants();
        sqlHelper.databaseName = constants.responseDatabase;
        sqlHelper.OOCDB();
        sqlHelper.setQuery(constants.CREATE_SATISFACTION_RESPONSE_QUERY);
        sqlHelper.execQuery();
        sqlHelper.setQuery(String.format(constants.INSERT_SATISFACTION_RESPONSE_QUERY, homeId, content,auxContent));
        sqlHelper.execInsert();
        sqlHelper.setQuery(constants.SELECT_LAST_SATISFACTION_ID_QUERY);
        sqlHelper.execQuery();
        Cursor cursor = sqlHelper.getCursor();
        int res = 0;
        if(cursor.getCount() > 0){
            res = cursor.getInt(0);
        }
        return res;
    }

    private int updateSatisfactionFromDB(int homeId, String content, String auxContent)throws SQLiteException{
        SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.responseDatabase;
        sql.OOCDB();
        sql.setQuery(String.format("UPDATE satisfaction SET content = '%s', auxcontent = '%s' WHERE homeid = %s",content, auxContent, homeId));
        sql.execUpdate();
        return homeId;
    }

    private boolean removeTmpData(){
        SharedPreferences preferences = getSharedPreferences(getPackageName(), MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();
        String preferenceName = pollType + "." + currentPoll.getStructureId() + ".Content";
        editor.remove(preferenceName);
        return editor.commit();
    }

    /**
     * Almacena la respuesta de todas las preguntas en formato JSON en la base de datos.
     */
    private void savePoll(){
        try{
            //if(/*storePollToDB(forms.toString()) > 0*/true){
              //  launchConfirmExitDialog(R.string.app_store_data_success_confirm);
            //}
            if(pollType == FormsLayouts.PollType.PERSON.code()){
                String personResponse = getPersonResponse().toString();
                int personId;
                if(getIntent().getBooleanExtra(EDIT_MODE_EXTRA, false)){
                    personId = getIntent().getIntExtra(PERSON_ID_EXTRA, 0);
                    if(personId > 0) {
                        personId = updatePersonInfoFromDB(personId, personResponse, forms.toString());
                    }else{
                        Log.e(getClass().getName(), "No se pudo almacenar el registro");
                    }
                }else {
                    personId = storePersonInfoToDB(homeId, personResponse, forms.toString());
                    removeTmpData();
                }
                setResult(RESULT_OK);
                finish();
            }else{
                if(pollType == FormsLayouts.PollType.SATISFACTION.code()){
                    String satisfactionResponse = forms.toString();
                    if(getIntent().getBooleanExtra(EDIT_MODE_EXTRA, false)){
                        int home = updateSatisfactionFromDB(homeId, satisfactionResponse, forms.toString());
                    }else {
                        int satisfactionId = storeSatisfactionInfoToDB(homeId, satisfactionResponse, forms.toString());
                        removeTmpData();
                    }
                    setResult(RESULT_OK);
                    finish();
                }else{
                    String homeResponse = getHomeResponse().toString();
                    String homeAuxContent = forms.getJSONObject(0).toString();
                    int homeId = storeHomeInfoToDB(currentPoll.getStructureId(),homeResponse,homeAuxContent,0);
                    String personResponse = getPersonResponse().toString();
                    JSONArray personAuxContent = new JSONArray();
                    for(int i = 1; i < forms.length(); i++){
                        personAuxContent.put(forms.getJSONObject(i));
                    }
                    int personId = storePersonInfoToDB(homeId, personResponse,personAuxContent.toString());
                    removeTmpData();
                    startPollInfo(currentPoll.getStructureId(), homeId);
                }
            }
        }catch (Exception ex){
            Log.e(getClass().getName(), ex.getMessage());
            launchAlertDialog(R.string.app_store_data_failed);
        }
    }

    private JSONObject getHomeResponse() throws JSONException{
        JSONObject json = new JSONObject();
        JSONArray fields = forms.getJSONObject(0).getJSONArray("Fields");
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            String key = field.getString("Name");
            if(key.equals("Area") || key.equals("Poblacion") || key.equals("Familia")
                    || key.equals("Direccion") || key.equals("Telefono") || key.equals("cugrfacoesvi") ||
                    key.equals("cupepeco") || key.equals("cupehaesvi")){
                if(field.has("Value")) {
                    json.put(key, field.getString("Value"));
                }
            }
        }
        return json;
    }

    private JSONObject getPersonResponse()throws JSONException{
        JSONObject json = new JSONObject();
        int pos = pollType == FormsLayouts.PollType.PERSON.code() ? 0 : 1;
        JSONArray fields = forms.getJSONObject(pos).getJSONArray("Fields");
        for(int i = 0; i < fields.length(); i++){
            JSONObject field = fields.getJSONObject(i);
            String key = field.getString("Name");
            if(key.equals("tipoId") || key.equals("documento") || key.equals("nombre") || key.equals("genero")
                    || key.equals("sNombre") || key.equals("apellido") || key.equals("sApellido")){
                if(field.has("Value")) {
                    json.put(key, field.getString("Value"));
                }
            }
        }
        return json;
    }

    private void startPollInfo(long structureId ,int homeId){
        Intent intent = new Intent(this, PollInfoActivity.class);
        intent.putExtra(PollInfoActivity.CURRENT_POLL_EXTRA, currentPoll);
        intent.putExtra(PollInfoActivity.POLL_ID_EXTRA, structureId);
        intent.putExtra(PollInfoActivity.HOME_ID_EXTRA, homeId);
        startActivityForResult(intent, POlL_INFO_REQUEST_CODE);
    }

    /**
     * Muestra un dialogo de alerta.
     * @param message mensaje que despliega el dialogo
     */
    private void launchAlertDialog(int message){
        AlertDialog d = com.miido.analiizo.util.Dialog.Alert(this,R.string.app_alert_dialog_title, message);
        d.show();
    }

    /**
     * Muestra un dialogo de confirmación
     * @param message mensaje que despliega el dialogo.
     */
    private void launchConfirmExitDialog(int message){
        AlertDialog d = com.miido.analiizo.util.Dialog.confirm(this,
                R.string.app_confirm_dialog_title ,message);
        d.setOnCancelListener(new DialogInterface.OnCancelListener() {
            @Override
            public void onCancel(DialogInterface dialogInterface) {
                setResult(RESULT_OK);
                finish();
            }
        });
        d.show();
    }

    /**
     * Manejador del evento Click sobre una vista View.
     * @param view vista que desencadena el evento.
     */
    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.resumeBackButton: onBackPressed();break;
            case R.id.resumeSaveButton: savePoll();break;
            default:onBackPressed();
        }
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
            if(requestCode == POlL_INFO_REQUEST_CODE){
                setResult(RESULT_OK);
                finish();
            }
        }
    }

    /**
     * Manejador del evento cuando se pulsa el botón back o volver
     */
    @Override
    public void onBackPressed() {
        setResult(RESULT_CANCELED);
        finish();
    }

    /**
     * Clase contenedora de los datos de un nodo.
     */
    public static class IconTreeItem {
        public String title;
        public String subTitle;
        public boolean isParent;

        public IconTreeItem(String title,String subTitle,boolean isParent) {
            this.title = title;
            this.subTitle = subTitle;
            this.isParent = isParent;
        }
    }

    /**
     * Personaliza un Objeto TreeView
     * @see IconTreeItem
     */
    public class IconTreeItemHolder extends TreeNode.BaseNodeViewHolder<IconTreeItem> implements View.OnLongClickListener{
        private ImageView iconView;
        private IconTreeItem iconTreeItem;

        /**
         * Constructor
         * @param context contexto de la actividad.
         */
        public IconTreeItemHolder(Context context) {
            super(context);
        }

        /**
         * Obtiene el objeto TreeView apartir de un Layout personalizado.
         * @param node referencia a un nodo
         * @param value referencia a los datos que contiene el nodo
         * @return la vista del nodo.
         */
        @Override
        public View createNodeView(final TreeNode node, IconTreeItem value) {
            final LayoutInflater inflater = LayoutInflater.from(context);
            final View view = inflater.inflate(R.layout.resume_node_layout, null, false);

            iconTreeItem = value;

            TextView nodeTitle = (TextView) view.findViewById(R.id.nodeTitle);
            nodeTitle.setText(value.title);
            TextView nodeSubTitle = (TextView) view.findViewById(R.id.nodeSubTitle);
            nodeSubTitle.setText(value.subTitle);

            ImageView nodeIcon = (ImageView) view.findViewById(R.id.nodeTypeIcon);
            iconView = (ImageView) view.findViewById(R.id.nodeIcon);

            if(value.isParent){
                iconView.setImageBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.ic_arrow_down));
                nodeIcon.setImageBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.ic_form_view_list));
            }else{
                nodeIcon.setImageBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.ic_question_lens));
                iconView.setImageBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.ic_node_child));
                view.setOnLongClickListener(new View.OnLongClickListener() {
                    @Override
                    public boolean onLongClick(View view) {
                        Toast.makeText(getApplicationContext(), "Aqui", Toast.LENGTH_LONG).show();
                        return false;
                    }
                });
            }

            return view;
        }

        /**
         * Responde a el evento de espanción de un nodo
         * @param active si el nodo está espandido ya
         */
        @Override
        public void toggle(boolean active) {
            if(iconTreeItem.isParent) {
                Bitmap icon = BitmapFactory.decodeResource(getResources(), active ? R.drawable.ic_action_arrow_up : R.drawable.ic_arrow_down);
                iconView.setImageBitmap(icon);
            }
        }

        @Override
        public boolean onLongClick(View view) {
            Toast.makeText(context, "ejemplo", Toast.LENGTH_LONG).show();
            return false;
        }
    }

}
