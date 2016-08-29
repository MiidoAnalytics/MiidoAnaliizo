package com.miido.analiizoOBRAS;

import android.app.AlertDialog;
import android.content.Intent;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;

public class ImagePreviewActivity extends ActionBarActivity implements View.OnClickListener{

    private Toolbar toolbar;
    private EditText editTextDescription;

    public static final String TITLE_EXTRA = "title";
    public static final String IMAGE_EXTRA = "image";
    public static final String DESCRIPTION_EXTRA = "description";
    public static final String IS_RESOURCE_SAVE_EXTRA = "isSaved";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.image_preview_layout_new);

        Intent intent = getIntent();
        String title = intent.getStringExtra(TITLE_EXTRA);
        String path = intent.getStringExtra(IMAGE_EXTRA);
        String description = intent.getStringExtra(DESCRIPTION_EXTRA);
        boolean isSaved = intent.getBooleanExtra(IS_RESOURCE_SAVE_EXTRA, false);

        this.toolbar = (Toolbar) findViewById(R.id.image_preview_tool_bar);
        this.toolbar.setTitle(title);
        setSupportActionBar(this.toolbar);
        this.toolbar.setLogo(R.drawable.ic_action_editor_insert_photo);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        this.toolbar.setNavigationOnClickListener(this);

        ImageView imageView = (ImageView) findViewById(R.id.imageContent);
        this.editTextDescription = (EditText) findViewById(R.id.imageDescription);
        this.editTextDescription.setEnabled(!isSaved);

        if(description!= null && !description.equals("")){
            this.editTextDescription.setText(description);
        }

        BitmapFactory.Options options = new BitmapFactory.Options();
        try {
            InputStream in = getContentResolver().openInputStream(Uri.parse(path));
            imageView.setImageBitmap(BitmapFactory.decodeStream(in, null, options));
            in.close();
        }catch (FileNotFoundException ex){
            Log.e("FILENOTFOUND", ex.getMessage());
        }catch (IOException ex){
            Log.e("IO", ex.getMessage());
        }

    }

    private void launchDescriptionEmptyDialog(){
        AlertDialog d = com.miido.analiizoOBRAS.util.Dialog.Alert(this,"Atención", "Debe especificar la descripción de la imágen");
        d.show();
    }

    @Override
    public void onBackPressed() {
        String description = this.editTextDescription.getText().toString();
        if(!description.equals("")) {
            Intent data = new Intent();
            data.putExtra(DESCRIPTION_EXTRA, description);
            setResult(RESULT_OK, data);
            finish();
        }else {
            launchDescriptionEmptyDialog();
        }
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()){
            default:onBackPressed();
        }
    }
}
