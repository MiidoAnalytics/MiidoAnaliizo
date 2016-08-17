package com.miido.miido.mcompose;

import android.content.Context;
import android.graphics.Color;
import android.text.InputFilter;
import android.text.InputType;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.CheckBox;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListPopupWindow;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TextView;

import com.miido.miido.R;
import com.miido.miido.util.sqlHelper;

import org.json.JSONArray;

import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.List;

/**
 * *******************************
 * Created by Alvaro on 18/02/2015.
 * *******************************
 */
public class objectCreator {

    private Object object;
    private Context context;
    private sqlHelper sqlHelper;
    private constants constants;

    private String name;
    private String hint;
    private String readOnly;
    private String autoCompleteTable;
    private int required;
    private List<String> options;

    private int type;
    private int inputRules;
    private int maxLength;

    private JSONArray ACindex;
    private String[][] acContent;

    public objectCreator(Context context) {
        this.context = context;
        this.sqlHelper = new sqlHelper(this.context);
        this.constants = new constants();
        this.ACindex = new JSONArray();
        this.acContent = new String[100][];
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setType(int type) {
        this.type = type;
    }

    public void setReadOnly(String ro) {
        this.readOnly = ro;
    }

    public void setHint(String hint) {
        this.hint = hint;
    }

    public void setAutoCompleteTable(String autoCompleteTable) {
        this.autoCompleteTable = autoCompleteTable;
    }

    public void setInputRules(int rules) {
        this.inputRules = rules;
    }

    public void setMaxLength(int maxLength) {
        this.maxLength = maxLength;
    }

    public void setOptionsList(List<String> options) {
        this.options = options;
    }

    private void setCursorDrawable() {
        try {
            Field f = TextView.class.getDeclaredField("mCursorDrawableRes");
            f.setAccessible(true);
            f.set((this.object), R.drawable.cursor);
        } catch (Exception ignored) {
        }
    }

    public void setRequired(Boolean required){
        this.required = ((required) ? 1 : 0);
    }

    public Object buildObject() {
        createObject();
        setObjectProperties();
        return this.object;
    }

    public void createObject() {
        switch (this.type) {
            case 0:
                this.object = new ScrollView(this.context);
                break;
            case 1:
                this.object = new EditText(this.context);
                break;
            case 2:
                this.object = new DatePicker(this.context);

                break;
            case 3:
                this.object = new CheckBox(this.context);
                break;
            case 4:
                if (this.options.size() < 4) {
                    this.object = new RadioGroup(this.context);
                } else {
                    this.type = 8;
                    createObject();
                }
                break;
            case 5:
                this.object = new AutoCompleteTextView(this.context);
                break;
            case 6:
                this.object = new TextView(this.context);
                break;
            case 7:
                this.object = new ListPopupWindow(this.context);
                break;
            case 8:
                this.object = new Spinner(this.context);
                break;
            default:
                break;
        }
    }

    private void setObjectProperties() {
        ((View) this.object).setTag(this.name);
        switch (this.type) {
            case 1:
                ((EditText) this.object).setHint(this.hint);
                ((EditText) this.object).setHintTextColor(Color.GRAY);
                ((EditText) this.object).setTextColor(Color.BLACK);
                ((EditText) this.object).setFilters(new InputFilter[]{new InputFilter.LengthFilter(this.maxLength)});
                setCursorDrawable();
                ((EditText) this.object).setId(this.required);

                if (this.readOnly.equals("1")) {
                    ((EditText) this.object).setEnabled(false);
                    ((EditText) this.object).setBackgroundResource(R.drawable.disabled);
                }

                ((EditText) this.object).setPadding(
                        15, ((EditText) this.object).getPaddingTop(),
                        ((EditText) this.object).getPaddingRight(), ((EditText) this.object).getPaddingBottom() - 3);
                switch (this.inputRules) {
                    case 0:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_TEXT);
                        break;
                    case 1:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_NUMBER);
                        break;
                    case 2:
                        ((EditText) this.object).setInputType(InputType.TYPE_TEXT_VARIATION_EMAIL_ADDRESS);
                        break;
                    case 3:
                        ((EditText) this.object).setInputType(InputType.TYPE_CLASS_NUMBER | InputType.TYPE_NUMBER_FLAG_DECIMAL);
                }
                break;
            case 2:
                ((DatePicker) this.object).setId(this.required);
                break;
            case 3:
                ((CheckBox) this.object).setId(this.required);
                break;
            case 4:
                try {
                    if (this.options.size() > 0) {
                        for (int a = 0; a < this.options.size(); a++) {
                            if (!this.options.get(a).equals("-") && !this.options.get(a).equals("")) {
                                RadioButton rb = new RadioButton(this.context);
                                rb.setText(this.options.get(a) + "");
                                rb.setTextColor(Color.BLACK);
                                rb.setButtonDrawable(R.drawable.radio_button);
                                rb.setPadding(0, 0, 25, 0);
                                ((RadioGroup) this.object).addView(rb);
                            }
                        }
                        ((RadioGroup) this.object).setPadding(0, 5, 0, 0);
                        ((RadioGroup) this.object).setOrientation(LinearLayout.HORIZONTAL);
                    }
                } catch (Exception e) {
                    e.getMessage();
                }
                ((RadioGroup) this.object).setId(this.required);
                break;
            case 5:
                ((AutoCompleteTextView) object).setDropDownHeight(300);
                ((AutoCompleteTextView) object).setThreshold(250);
                ((AutoCompleteTextView) object).setHint(hint);
                ((AutoCompleteTextView) object).setHintTextColor(Color.GRAY);
                ((AutoCompleteTextView) object).setMaxWidth(((AutoCompleteTextView) object).getWidth());
                ((AutoCompleteTextView) object).setId(this.required);
                setCursorDrawable();
                List<String> empty = new ArrayList<>();
                ArrayAdapter<String> adapter = new ArrayAdapter<>(context, R.layout.dropdown, empty);
                ((AutoCompleteTextView) object).setAdapter(adapter);
                ((AutoCompleteTextView) object).setContentDescription(autoCompleteTable);
                break;
            case 8:
                ArrayAdapter<String> aa = new ArrayAdapter<>(this.context, R.layout.custom_spinner, this.options);
                aa.setDropDownViewResource(R.layout.dropdown);
                ((Spinner) this.object).setMinimumWidth(((Spinner) this.object).getWidth());
                ((Spinner) this.object).setAdapter(aa);
                ((Spinner) this.object).setId(this.required);
                if (this.readOnly.equals("1")) {
                    ((Spinner) this.object).setEnabled(false);
                }
                ((Spinner) this.object).setPrompt("Choose an option");
                break;
        }
    }
}