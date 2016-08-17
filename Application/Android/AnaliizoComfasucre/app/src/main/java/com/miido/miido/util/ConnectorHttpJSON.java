package com.miido.miido.util;

import android.annotation.SuppressLint;
import android.content.Context;
import android.database.sqlite.SQLiteException;
import android.os.StrictMode;
import android.util.Log;

import com.miido.miido.mcompose.constants;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URL;
import java.util.StringTokenizer;

/**
 * *******************************
 * Created by Alvaro on 03/03/2015.
 * *******************************
 */
public class ConnectorHttpJSON {

    public int dCase = 0;
    private constants constants;
    private sqlHelper sqlHelper;
    private Context context;
    private URL URL;
    private String url;


    public ConnectorHttpJSON(Context context) throws Exception {
        this.context = context;
        this.constants = new constants();
    }

    public String[] downloadStructure(Boolean developerMode) throws Exception {
        StringBuilder sBuilder = new StringBuilder();
        try {
            dCase = 0;
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            this.sqlHelper = new sqlHelper(this.context);
            HttpClient client = new DefaultHttpClient();
            HttpGet request = new HttpGet();
            request.setURI(new URI(this.url));
            HttpResponse response = client.execute(request);
            BufferedReader in = new BufferedReader(new InputStreamReader(response
                    .getEntity().getContent()));

            String line;
            while ((line = in.readLine()) != null) {
                sBuilder.append(line).append("\n");
            }
            in.close();
            if (developerMode) {
                if (sBuilder.length() < 20) {
                    dCase = 1;
                    sBuilder = new StringBuilder();
                    sBuilder.append(this.constants.structure_1 + this.constants.structure_2);
                }
            } else {
                if (sBuilder.length() < 20) {
                    return (new String[]{this.constants.downFail, this.constants._ADV011});
                }
            }
        } catch (Exception e) {
            if (developerMode) {
                dCase = 1;
                sBuilder = new StringBuilder();
                sBuilder.append(this.constants.structure_1 + this.constants.structure_2);
            } else {
                return (new String[]{this.constants.downFail, this.constants._ADV011});
            }
        }
        String str;
        try {
            str = sBuilder.toString().replace("\\", "");
            str = str.replace("\"{", "{");
            str = str.replace("}\"", "}");
            str = str.trim();
            JSONObject jo = new JSONObject(str);
            try {
                jo = jo.getJSONObject("Document_info");
                StringTokenizer st = new StringTokenizer(jo.getString("minVersionName"), ".");
                int version_name = Integer.parseInt(st.nextToken());
                int version_subName = Integer.parseInt(st.nextToken());
                if (this.constants.version_name >= version_name) {
                    if (this.constants.version_subname >= version_subName) {
                        if (jo.getInt("structureStatus") == this.constants.appStatus) {
                            try {
                                this.sqlHelper.OOCDB();
                                this.sqlHelper.setQuery(this.constants.QUERY_3);
                                this.sqlHelper.execQuery();
                                this.sqlHelper.setQuery(this.constants.QUERY_5);
                                this.sqlHelper.execUpdate();
                                this.sqlHelper.setQuery(this.constants.QUERY_4.replace("[STRUCTURE]", "'" + str + "'"));
                                this.sqlHelper.setQuery(this.sqlHelper.getQuery().replace("[STATUS]", "1"));
                                this.sqlHelper.setQuery(this.sqlHelper.getQuery().replace("[VERSION]", jo.getInt("structureVersion") + ""));
                                this.sqlHelper.execInsert();
                                return (new String[]{this.constants.downOk, this.constants.uParamsOk});
                            } catch (Exception e) {
                                return (new String[]{this.constants.downFail, e.getMessage()});
                            }
                        }
                    } else {
                        return (new String[]{this.constants.downFail, this.constants.uParamsFail});
                    }
                } else {
                    return (new String[]{this.constants.downFail, this.constants.uParamsFail});
                }
            } catch (Exception e) {
                return (new String[]{this.constants.downFail, e.getMessage()});
            }
        } catch (Exception e) {
            return (new String[]{this.constants.downFail + "asdasdasd", e.getMessage()});
        }
        return (new String[]{this.constants.downFail, this.constants.uParamsFail});
    }

    @SuppressWarnings("All")
    public String downloadDataBase() throws Exception {
        try {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            String size;
            this.constants = new constants();
            HttpURLConnection hrc = (HttpURLConnection) this.URL.openConnection();
            hrc.setRequestMethod("GET");
            hrc.setDoOutput(true);
            hrc.connect();
            @SuppressLint("SdCardPath")
            File dir = new File("/data/data/com.miido.miido/databases/");
            dir.mkdirs();
            File file = new File(dir, this.constants.database + "");
            if (file.createNewFile()) {
                file.createNewFile();
            }

            FileOutputStream fos = new FileOutputStream(file);
            InputStream is = hrc.getInputStream();
            size = hrc.getContentLength() + "";
            byte[] buffer = new byte[92048];
            int bufferLength = 0;

            while ((bufferLength = is.read(buffer)) > 0) {
                fos.write(buffer, 0, bufferLength);
            }
            fos.close();
            return size;
        } catch (MalformedURLException e) {
            return "mfwe" + e.getMessage();
        } catch (IOException ie) {
            return "ie" + ie.getMessage();
        }
    }

    public int downloadSecurityStructure() {
        StringBuilder sBuilder = new StringBuilder();
        try {
            dCase = 0;
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            this.sqlHelper = new sqlHelper(this.context);
            HttpClient client = new DefaultHttpClient();
            HttpGet request = new HttpGet();
            request.setURI(new URI(this.url));
            HttpResponse response = client.execute(request);
            BufferedReader in = new BufferedReader(new InputStreamReader(response
                    .getEntity().getContent()));

            String line;
            while ((line = in.readLine()) != null) {
                sBuilder.append(line).append("\n");
            }
            in.close();
        } catch (Exception e) {
            return (0);
        }
        String str = "";
        try {
            str = sBuilder.toString().replace("\\", "");
            str = str.replace("\"[", "[");
            str = str.replace("]\"", "]");
            JSONArray ja = new JSONArray(str);
            try {
                //ja = ja.getJSONArray(0);
                try {
                    this.sqlHelper.databaseName = constants.securityDatabase;
                    this.sqlHelper.OOCDB();
                    this.sqlHelper.setQuery(constants.QUERY_9);
                    this.sqlHelper.execQuery();
                    this.sqlHelper.setQuery(constants.QUERY_10);
                    this.sqlHelper.execUpdate();
                    this.sqlHelper.setQuery(constants.QUERY_11.replace("[STRUCTURE]", "'" + ja.toString() + "'"));
                    this.sqlHelper.execInsert();
                    return (1);
                } catch (SQLiteException sqe) {
                    return (2);
                }
            } catch (Exception e) {
                Log.e("downloadSecurityStructu", e.getMessage());
                Log.e("downloadSecurityStructu", str);
                return (3);
            }
        } catch (Exception e) {
            return (4);
        }
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public void createUrl() throws Exception {
        this.URL = new URL(this.url);
    }

}
