package com.miido.analiizo.util;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;

public class Dialog {
	
	public static AlertDialog confirm(Context context,String title,String message){
		AlertDialog.Builder builder=new AlertDialog.Builder(context);
		builder.setTitle(title);
		builder.setMessage(message);
		builder.setPositiveButton("Aceptar", new OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				dialog.cancel();
			}
		});
		builder.setNegativeButton("Cancelar", new OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
			}
		});
		return builder.create();
	}

    public static AlertDialog confirm(Context context, int title, int message){
        return confirm(context, context.getString(title),context.getString(message));
    }

	public static AlertDialog Alert(Context context, String title, String message){
		AlertDialog.Builder builder=new AlertDialog.Builder(context);
		builder.setTitle(title);
		builder.setMessage(message);
		builder.setPositiveButton("Aceptar", new OnClickListener() {
			@Override
			public void onClick(DialogInterface dialog, int i) {
				dialog.cancel();
			}
		});
		return builder.create();
	}

    public static AlertDialog Alert(Context context, int title, int message){
        return Alert(context, context.getString(title),context.getString(message));
    }

	public static ProgressDialog progressDialog(Context context, String title,String message,int maxvalue){
		ProgressDialog progressDialog = new ProgressDialog(context);
		progressDialog.setTitle(title);
		progressDialog.setMessage(message);
		progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
		progressDialog.setProgress(0);
		progressDialog.setMax(maxvalue);
        progressDialog.setCancelable(false);
        progressDialog.setCanceledOnTouchOutside(false);
		return progressDialog;
	}

    public static ProgressDialog progressDialog(Context context, int title, int message, int maxvalue){
        return progressDialog(context, context.getString(title), context.getString(message), maxvalue);
    }

}
