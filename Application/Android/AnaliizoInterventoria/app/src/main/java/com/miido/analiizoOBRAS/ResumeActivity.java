package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
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

import com.miido.analiizoOBRAS.mcompose.Constants;
import com.miido.analiizoOBRAS.model.Field;
import com.miido.analiizoOBRAS.model.Form;
import com.miido.analiizoOBRAS.util.SqlHelper;
import com.unnamed.b.atv.model.TreeNode;
import com.unnamed.b.atv.view.AndroidTreeView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Date;

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

    private int currentPollId;

    public static final String RESPONSE_EXTRA = "json_response";
    public static final String POLL_ID_EXTRA = "poll_id";
    public static final String POLL_NAME_EXTRA = "poll_name";

    /**
     * Se ejecuta al ser creada la actividad
     * @param savedInstanceState contiene el estado de la actividad
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.resume_layout);

        currentPollId = getIntent().getIntExtra(POLL_ID_EXTRA, 0);

        toolbar = (Toolbar) findViewById(R.id.resumeToolBar);
        toolbar.setTitle("Semana 1");
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
            TreeNode root = getRootNode(forms);
            AndroidTreeView treeView = new AndroidTreeView(this, root);
            LinearLayout linearLayout = (LinearLayout) findViewById(R.id.treeView);

            linearLayout.addView(treeView.getView());
        }catch (JSONException ex){
            Log.e(getClass().getName(), ex.getMessage());
        }
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
            root.addChild(getFormNode(form));
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
        String formSubTitle = getResources().getQuantityString(R.plurals.resume_questions_lenghts,questionsSize,questionsSize);
        IconTreeItem formIconTreeItem = new IconTreeItem(formTitle,formSubTitle,true);
        TreeNode formNode = new TreeNode(formIconTreeItem);
        formNode.setViewHolder(new IconTreeItemHolder(this));
        for(int i = 0; i < questionsSize; i++){
            JSONObject question = questions.getJSONObject(i);
            String questionTitle = question.getString(Field.JField.LABEL.code());
            String questionSubTitle = "No repondida";
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

    /**
     * Inserta los datos de le encuesta respondida en la base de datos local
     * @param pollDataStructure número identificador de la estructura de la encuesta.
     * @throws SQLiteException es lanzada si ocurre algún error en la ejecución de la consulta.
     */
    private int storePollToDB(String pollDataStructure)throws SQLiteException{
//        Constants constants = new Constants();
//        SqlHelper sqlHelper = new SqlHelper(getApplicationContext());
//        sqlHelper.databaseName = constants.pollDatabase;
//        sqlHelper.OOCDB();
//        sqlHelper.setQuery(constants.CREATE_POLL_DATA_TABLE_QUERY);
//        sqlHelper.execQuery();
//        String saveDate = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
//        sqlHelper.setQuery(String.format(constants.INSERT_POLL_DATA_QUERY, currentPollId, pollDataStructure, saveDate));
//        sqlHelper.execInsert();
//
//        sqlHelper.setQuery(constants.SELECT_LAST_POLL_DATA_QUERY);
        int lastId = 0;
//        sqlHelper.execQuery();
//        Cursor cursor = sqlHelper.getCursor();
//        if(cursor.getCount()>0){
//            lastId = cursor.getInt(0);
//        }
//        sqlHelper.close();
        return lastId;
    }

    /**
     * Almacena la respuesta de todas las preguntas en formato JSON en la base de datos.
     */
    private void savePoll(){
        try{
            if(/*storePollToDB(forms.toString()) > 0*/true){
                launchConfirmExitDialog(R.string.app_store_data_success_confirm);
            }
        }catch (SQLiteException ex){
            Log.e(getClass().getName(), ex.getMessage());
            launchAlertDialog(R.string.app_store_data_failed);
        }
    }

    /**
     * Actualiza la respuesta de la encuesta en la base de datos
     * @param pollStructure estructura de respuesta de la encuesta.
     * @throws SQLiteException es lanzada si ocurre algún error al tratar de actualizar el registro en la base de datos.
     */
    private void updatePollStructureFromDB(String pollStructure) throws SQLiteException{
        /*SqlHelper sql = new SqlHelper(this);
        Constants constants = new Constants();
        sql.databaseName = constants.pollDatabase;
        sql.OOCDB();
        String saveDate = new SimpleDateFormat(constants.SimpleDateFormat).format(new Date());
        sql.setQuery(String.format("UPDATE poll SET polldata = '%s', savedate = '%s' WHERE id = %s ;", pollStructure, saveDate, POSITION));
        sql.execUpdate();
        Toast.makeText(this, "Información actualizada con exito",Toast.LENGTH_LONG).show();*/
    }

    /**
     * Muestra un dialogo de alerta.
     * @param message mensaje que despliega el dialogo
     */
    private void launchAlertDialog(int message){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.Alert(this,R.string.app_alert_dialog_title, message);
        d.show();
    }

    /**
     * Muestra un dialogo de confirmación
     * @param message mensaje que despliega el dialogo.
     */
    private void launchConfirmExitDialog(int message){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.confirm(this,
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
