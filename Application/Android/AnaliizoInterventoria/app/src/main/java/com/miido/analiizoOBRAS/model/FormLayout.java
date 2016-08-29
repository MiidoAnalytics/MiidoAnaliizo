package com.miido.analiizoOBRAS.model;

import android.content.Context;
import android.os.Parcel;
import android.os.Parcelable;
import android.util.Log;
import android.view.LayoutInflater;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.miido.analiizoOBRAS.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * @author Ing. Miguel Angel Urango Blanco Miido S.A.S 06/05/2016.
 */
public class FormLayout implements Parcelable{

    private Form properties;
    private ArrayList<QuestionLayout> questions;
    private ArrayList<FormLayout> dynamicForms;
    private LinearLayout formLayout;
    private Context context;

    public FormLayout(Context context,Form properties){
        setProperties(properties);
        setContext(context);
        setQuestions(new ArrayList<QuestionLayout>());
        setDynamicForms(new ArrayList<FormLayout>());
    }

    protected FormLayout(Parcel in) {
        properties = in.readParcelable(Form.class.getClassLoader());
        questions = in.createTypedArrayList(QuestionLayout.CREATOR);
        dynamicForms = in.createTypedArrayList(FormLayout.CREATOR);
    }

    public JSONObject toJsonObject()throws JSONException{
        JSONObject json = toJsonObject(FormLayout.this);
        return json;
    }

    private JSONObject toJsonObject(FormLayout form) throws JSONException{
        JSONObject json = form.getProperties().toJsonObject();
        for(int i = 0; i < form.questionsLength(); i++){
            QuestionLayout questionLayout = form.questions.get(i);
            JSONObject jsonQuestion = questionLayout.getProperties().toJSonObject();
            FormLayout formLayout = questionLayout.getSubForm();
            String value = questionLayout.getProperties().getValue();
            Handler handler = questionLayout.getProperties().getHandlerEvent();
            DynamicJoiner dynamicJoiner = questionLayout.getProperties().getDynamicJoiner();
            if(formLayout != null && value != null &&
                    ((handler != null && handler.compareTo(questionLayout.getProperties().getValue())) ||
                            (dynamicJoiner != null && dynamicJoiner.getHandlerEvent().compareTo(value)))){
                try {
                    jsonQuestion.put(Field.JField.SUBFORM.code(),new JSONArray().put(toJsonObject(questionLayout.getSubForm())));
                }catch (JSONException ex){
                    Log.e(getClass().getName(), ex.getMessage());
                    jsonQuestion.put(Field.JField.SUBFORM.code(),new JSONArray().put(toJsonObject(questionLayout.getSubForm())));
                }
            }
            try {
                json.getJSONArray(Form.JForm.FIELDS.code()).put(jsonQuestion);
            }catch (JSONException ex){
                Log.e(getClass().getName(), ex.getMessage());
                json.put(Form.JForm.FIELDS.code(), new JSONArray().put(jsonQuestion));
            }
        }
        return json;
    }

    public LinearLayout getFormLayout(){
        if(this.formLayout == null) {
            LayoutInflater layoutInflater = (LayoutInflater) this.context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            this.formLayout = (LinearLayout) layoutInflater.inflate(R.layout.form_layout, null, false);
            this.formLayout.setTag(getProperties());
            TextView headerText = (TextView) formLayout.findViewById(R.id.headerTextView);
            String header = getProperties().getHeader();
            if (header != null && !header.equals("")) {
                headerText.setText(header);
            } else {
                formLayout.removeViewAt(0);
            }
            LinearLayout questionContainer = (LinearLayout) this.formLayout.findViewById(R.id.questionsContainer);
            for (int i = 0; i < questionsLength(); i++) {
                questionContainer.addView(getQuestion(i).getQuestionLayout());
                //questionContainer.addView(getQuestion(i).getView());
            }
        }
        return this.formLayout;
    }

    public static final Creator<FormLayout> CREATOR = new Creator<FormLayout>() {
        @Override
        public FormLayout createFromParcel(Parcel in) {
            return new FormLayout(in);
        }

        @Override
        public FormLayout[] newArray(int size) {
            return new FormLayout[size];
        }
    };

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeParcelable(properties, i);
        parcel.writeTypedList(questions);
        parcel.writeTypedList(dynamicForms);
    }

    public int questionsLength(){
        return questions.size();
    }

    @Override
    protected FormLayout clone() throws CloneNotSupportedException {
        return new FormLayout(context, properties);
    }

    public boolean isQuestionsEmpty(){
        return questions.isEmpty();
    }

    public void setFormLayout(LinearLayout formLayout){
        this.formLayout = formLayout;
    }

    public void addQuestion(QuestionLayout question){
        questions.add(question);
    }

    public QuestionLayout getQuestion(int position){
        return questions.get(position);
    }

    public Form getProperties() {
        return properties;
    }

    public void setProperties(Form properties) {
        this.properties = properties;
    }

    public ArrayList<QuestionLayout> getQuestions() {
        return questions;
    }

    public void setQuestions(ArrayList<QuestionLayout> questions) {
        this.questions = questions;
    }

    public Context getContext() {
        return context;
    }

    public void setContext(Context context) {
        this.context = context;
    }

    public ArrayList<FormLayout> getDynamicForms() {
        return dynamicForms;
    }

    public void setDynamicForms(ArrayList<FormLayout> dynamicForms) {
        this.dynamicForms = dynamicForms;
    }
}
