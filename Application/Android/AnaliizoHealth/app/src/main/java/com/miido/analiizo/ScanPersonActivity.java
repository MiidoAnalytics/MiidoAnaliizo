package com.miido.analiizo;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Rect;
import android.media.MediaPlayer;
import android.os.Vibrator;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.Toast;
/**
 * com.microblick.* libreria de reconocimiento de Código PDF417
 */
import com.microblink.geometry.Point;
import com.microblink.geometry.Quadrilateral;
import com.microblink.geometry.quadDrawers.QuadrilateralDrawer;
import com.microblink.hardware.SuccessCallback;
import com.microblink.hardware.camera.CameraType;
import com.microblink.recognition.InvalidLicenceKeyException;
import com.microblink.recognizers.BaseRecognitionResult;
import com.microblink.recognizers.barcode.pdf417.Pdf417RecognizerSettings;
import com.microblink.recognizers.barcode.pdf417.Pdf417ScanResult;
import com.microblink.recognizers.settings.RecognizerSettings;
import com.microblink.recognizers.settings.RecognizerSettingsUtils;
import com.microblink.util.RecognizerCompatibility;
import com.microblink.view.CameraAspectMode;
import com.microblink.view.CameraEventsListener;
import com.microblink.view.NotSupportedReason;
import com.microblink.view.recognition.DetectionStatus;
import com.microblink.view.recognition.RecognitionType;
import com.microblink.view.recognition.RecognizerView;
import com.microblink.view.recognition.RecognizerViewEventListener;
import com.microblink.view.recognition.ScanResultListener;
import com.microblink.view.viewfinder.QuadView;
import com.miido.analiizo.model.Person;
import com.miido.analiizo.model.Poll;

import java.util.List;
import android.os.Handler;

/**
 * Activity que Utiliza la libreria PDF417.mobi de microblick para escanear códigos PDF417 y extraer información
 * del códfigo ubicado en la parte posterior del la cédula de ciudadania.
 * @Author Ing. Miguel Angel Urango Blanco MIIDO S.A.S 12/11/2015
 * @version 1.0
 * @see ScanResultListener controla los eventos de reconocimiento de códigos
 * @see  CameraEventsListener controla los eventos de la cámara
 * @see  RecognizerViewEventListener controla los eventos de la actividad de escaneo de los códigos
 */

public class ScanPersonActivity extends Activity implements ScanResultListener, CameraEventsListener,RecognizerViewEventListener{

    //se utliza para crear un retardo de 2 seg al momento de reconocer un código
    private Handler mHandler = new Handler();

    //vista que muestra la cámara de reconocimieto de códigos
    private RecognizerView mRecognizerView;

    //componente que enfoca el codigo antes de reconocerlo,representado como un rectangulo [   ]
    private QuadView mQuadView;

    //vista donde se extraen las vistas por defecto del RecognizerView
    private View mLayout;

    //botón para cerrar la actividad
    private Button mBackButton;

    //boton para controlar el flash de la cámara
    private Button mTorchButton;

    //true si el flash esta encedido y false de lo contrario.
    private boolean mTorchEnabled = false;

    private Poll currentPoll;

    //almacena la etiqueta del extra donde se guardaran los datos de reconocimiento de la actividad
    //para compartirla con la actividad que lanza a la actual.
    //Esta variable no se puede modificar en tiempo de ejecución
    public static final String PERSON_EXTRA = "PERSON_EXTRA";

    /**
     * Este metodo heredado de Activity se ejecuta cada vez que la Actividad es creada.
     * se utiliza frecuentemente para inicilizar las variables
     * @param savedInstanceState guarda el estado de la actividad en una estructura de datos llave-valor
     * @see Bundle
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // oculta la barra de notificaciones
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        // oculta la barra de titulo de la actividad
        getWindow().requestFeature(Window.FEATURE_NO_TITLE);

        super.onCreate(savedInstanceState);

        // Instanciación del RecognizerView
        mRecognizerView = new RecognizerView(this);

        // obtener las configuraciones iniciales del RecognizerView
        RecognizerSettings[] settArray = setupSettingsArray();
        // comporbación de la existencia de cámara tracera
        if(!RecognizerCompatibility.cameraHasAutofocus(CameraType.CAMERA_BACKFACE, this)) {
            settArray = RecognizerSettingsUtils.filterOutRecognizersThatRequireAutofocus(settArray);
        }
        // Establecer las configuraciones iniciales del RecognizerView
        mRecognizerView.setRecognitionSettings(settArray);

        try {
            // estableciendo la llave de licencia
            mRecognizerView.setLicenseKey("XKKCMBWY-W6IEPUWB-CFRUZFVR-RDAGSHJQ-PWZPOSLK-VGFFFSFH-T2ME4TEW-WGEJDYNT");
        } catch (InvalidLicenceKeyException exc) {
            Toast.makeText(this, "Licencia invalida", Toast.LENGTH_LONG).show();
        }

        // agrega controladores de eventos el RecognizerView
        mRecognizerView.setScanResultListener(this);
        mRecognizerView.setCameraEventsListener(this);
        mRecognizerView.setRecognizerViewEventListener(this);

        // agrega animación al cambiar la orientación del dispositivo
        mRecognizerView.setAnimateRotation(true);
        mRecognizerView.setAspectMode(CameraAspectMode.ASPECT_FILL);

        // crea el RecognizerView
        mRecognizerView.create();

        // se agrega el ReconizerView como el contenido de la actividad
        setContentView(mRecognizerView);

        // istanciación del QuadView
        mQuadView = new QuadView(this, null, new QuadrilateralDrawer(this), 0.35, 0.35, mRecognizerView.getHostScreenOrientation());
        // se agrega el QuatView a el RecognizerView
        mRecognizerView.addChildView(mQuadView, false);

        // instanciacion del Layout desde xml
        mLayout = getLayoutInflater().inflate(R.layout.default_barcode_camera_overlay, null);

        // Instanciación del boton atras desde xml
        mBackButton = (Button) mLayout.findViewById(R.id.backButton);
        // agrega texto al boton atras desde los recursos
        mBackButton.setText(getString(R.string.Back));

        // agrega el manejador de eventos al boton atras
        mBackButton.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                onBackPressed();
            }
        });

        // obtención del boton flash desde xml
        mTorchButton = (Button) mLayout.findViewById(R.id.torchButton);

        // modificación del texto y la imagen del botón
        mTorchButton.setText(R.string.Light);
        mTorchButton.setCompoundDrawablesWithIntrinsicBounds(R.drawable.lightoff,0,0,0);
        mTorchButton.setVisibility(View.GONE);

        // agregación del layout al RecognizerView
        mRecognizerView.addChildView(mLayout, true);
    }

    /**
     * Establece configuraciones iniciales para el RecognizerView
     * @return arreglo de configuraciones iniciales.
     */
    private RecognizerSettings[] setupSettingsArray() {
        Pdf417RecognizerSettings sett = new Pdf417RecognizerSettings();
        sett.setInverseScanning(false);
        sett.setUncertainScanning(true);
        sett.setNullQuietZoneAllowed(false);
        return new RecognizerSettings[] { sett };
    }

    /**
     * controlador de evento cuando se da click o pulsa el botón atras
     */
    @Override
    public void onBackPressed() {
        setResult(RESULT_CANCELED, null);
        finish();
    }

    /**
     * Se ejecuta cuando la actividad es iniciada, iniciliza el RecognizerView
     * @see RecognizerView
     */
    @Override
    protected void onStart() {
        super.onStart();
        mRecognizerView.start();
    }

    /**
     * Se ejecuta cuando la actividad pasa se reanuda despues de una pausa, reanuda el RecognizerView
     * @see RecognizerView
     */
    @Override
    protected void onResume() {
        super.onResume();
        mRecognizerView.resume();
    }

    /**
     * Se ejecuta cuando la actividad es pausada, entra a un estado de inactividad cuando se inicia otra actividad desde ella o se llama a un dialogo.
     * pausa el RecognizerView
     * @see RecognizerView
     */
    @Override
    protected void onPause() {
        super.onPause();
        mRecognizerView.pause();
    }

    /**
     * Se ejecuta cuanto la actividad es detenida, detiene el RecognizerView
     * @see RecognizerView
     */
    @Override
    protected void onStop() {
        super.onStop();
        mRecognizerView.stop();
    }

    /**
     * se ejecuta cuanto la actividad es destruida, destruye el RecognizerView
     * @see RecognizerView
     */
    @Override
    protected void onDestroy() {
        super.onDestroy();
        mRecognizerView.destroy();
    }

    /**
     * Si se detecta un cambio en la configuración en tiempo de ejecución dentro del RecognizerView
     * este controlador se activa para aplicar la nueva configuración
     * @param newConfig la nueva configuración detectada.
     */
    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        mRecognizerView.changeConfiguration(newConfig);
    }

    /**
     * Este controlador de evento se activa cada vez que el RecognizerView hace la lectura de un coódigo
     * en este caso se verifica el tipo PDF417.
     * @param dataArray Arreglo con los datos reconcidos.
     * @param recognitionType objeto que determina que tipo de reconocimiento se hizo.
     * @see #getDecodePerson(Pdf417ScanResult)
     */
    @Override
    public void onScanningDone(BaseRecognitionResult[] dataArray, RecognitionType recognitionType) {
        for(BaseRecognitionResult baseResult : dataArray) {
            if(baseResult instanceof Pdf417ScanResult) {
                Pdf417ScanResult result = (Pdf417ScanResult) baseResult;

                if(result.isUncertain()){
                    Toast.makeText(ScanPersonActivity.this, "No se púdo leer el código", Toast.LENGTH_SHORT).show();
                }else {
                    Person person = getDecodePerson(result);
                    MediaPlayer.create(this, R.raw.beep).start();// reproducir sonido
                    ((Vibrator)getSystemService(Context.VIBRATOR_SERVICE)).vibrate(500);// vibrar medio segundo

                    Toast.makeText(ScanPersonActivity.this, "CC N° " + person.getId() + " Lectura correcta", Toast.LENGTH_SHORT).show();

                    Intent intent = new Intent();
                    intent.putExtra(PERSON_EXTRA, person);
                    setResult(RESULT_OK,intent);
                    finish();
                }
                // delay de 2 segundos antes de resumir el reconocimiento proximo
                mHandler.postDelayed(new Runnable() {

                    @Override
                    public void run() {
                        mRecognizerView.resumeScanning(true);
                    }
                }, 2000);
            }
        }
    }

    /**
     * Decodifica la información basica de la persona incluida dentro del código PDF417
     * @param scanresult resultado del escaneo
     * @return objeto Person con los datos de la persona
     * @throws NullPointerException se lanza si ningun resultado fue escaneado
     * @see Person
     * @see Pdf417ScanResult
     * @see #parseDecode(int, String[])
     */
    private Person getDecodePerson(Pdf417ScanResult scanresult)throws NullPointerException{
        Person person = null;
        if(scanresult.getRawData().getElements().size()>1){
            // tipos de cédula A01
            String fields[] = scanresult.getStringData().split("\0");
            person = parseDecode(4,fields);
        }else{
            //  tipos de cédula A02 - B02
            String data[] = scanresult.getStringData().split("\0");
            String[] fields = data[data.length - 2].split(" ");
            person = parseDecode(3 , fields);

        }
        return person;
    }

    /**
     * Método ayudante de el método decodePerson para decodificar los datos de persona incluidos dentro del código PDF417 de la cédula.
     * @param startfield campo de inicio
     * @param fields campos identificados
     * @return objeto Person con los datos de la persona escaneada
     * @see Person
     * @see #getDecodePerson(Pdf417ScanResult)
     */
    private Person parseDecode(final int startfield, String[] fields){
        Person person = new Person();
        int numberOfFields = 5, count = 1;
        for(String field : fields){
            if(!field.equals("")){
                if(count>= startfield && count< startfield + numberOfFields){
                    switch (startfield + numberOfFields - count){
                        case 5:
                            String tmplastname = "";
                            String tmpid = "";
                            for(int i = 0; i < field.length(); i++){
                                try{
                                    Integer.parseInt(field.charAt(i) + "");
                                    tmpid += field.charAt(i) + "";
                                }catch (NumberFormatException ex){
                                    tmplastname += field.charAt(i) + "";
                                }
                            }
                            if(startfield == 3) {// tipos de cédula A02 - B02
                                person.setId(Long.parseLong(tmpid.substring(8)));
                            }else{
                                person.setId(Long.parseLong(tmpid));
                            }
                            person.setLastname1(tmplastname);break;
                        case 4:person.setLastname2(field);break;
                        case 3:person.setFirstname1(field);break;
                        case 2:person.setFirstname2(field);break;
                        case 1:
                            person.setRH(field.charAt(field.length() - 1));
                            person.setBloodgroup(field.charAt(field.length() - 2) + "");
                            person.setGender(field.charAt(1));
                            person.setBirthday(Long.parseLong(field.substring(2 , 10)));break;
                    }
                }
                if(++count >= startfield + numberOfFields)
                    break;
            }
        }
        return person;
    }

    /**
     * se ejecuta antes de que la camára sea inicializada para aplicar configuraciones
     */
    @Override
    public void onCameraPreviewStarted() {
        if(mRecognizerView.isCameraTorchSupported()) {
            mTorchButton.setVisibility(View.VISIBLE);
            mTorchButton.setOnClickListener(new View.OnClickListener() {

                @Override
                public void onClick(View v) {
                    mRecognizerView.setTorchState(!mTorchEnabled, new SuccessCallback() {
                        @Override
                        public void onOperationDone(final boolean success) {
                            runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    if(success) {
                                        mTorchEnabled = !mTorchEnabled;
                                        if(mTorchEnabled) {
                                            mTorchButton.setCompoundDrawablesWithIntrinsicBounds(R.drawable.lighton,0,0,0);
                                        } else {
                                            mTorchButton.setCompoundDrawablesWithIntrinsicBounds(R.drawable.lightoff,0,0,0);
                                        }
                                    }
                                }
                            });
                        }
                    });
                }
            });
        }
    }

    /**
     * se ejecuta cuando el preview de la camára es detenido
     */
    @Override
    public void onCameraPreviewStopped() {
        // TODO: 20/01/2016
    }

    /**
     * se ejecuta cuando la inicialización de la camara presenta un error.
     * @param exc excepción capturada
     * @see #errorDialog()
     */
    @Override
    public void onStartupError(Throwable exc) {
        errorDialog();
    }

    /**
     * Se ejecuta cuando Microblick no es soportado por el dispositivo
     * @param reason la razón por la cual no es soportado el dispositivo.
     * @see #errorDialog()
     */
    @Override
    public void onNotSupported(NotSupportedReason reason) {
        android.util.Log.e("ERROR", "Dispositivo no soportado "+reason);
        errorDialog();
    }

    /**
     * Mustra un dialogo de error si es capturado cualquier error
     * @see AlertDialog
     */
    private void errorDialog() {
        AlertDialog.Builder ab = new AlertDialog.Builder(this);
        ab.setMessage("Ha ocurrido un error!")
                .setTitle("Error")
                .setCancelable(false)
                .setNeutralButton("Aceptar", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        if(dialog != null) {
                            dialog.dismiss();
                        }
                        setResult(RESULT_CANCELED);
                        finish();
                    }
                }).create().show();
    }

    /**
     * se ejecuta si el autoenfoque de la camara presenta un error
     */
    @Override
    public void onAutofocusFailed() {
        Toast.makeText(this, "Falló el autoenfoque", Toast.LENGTH_SHORT).show();
    }

    /**
     * se ejecuta cuando el autoenfoque es iniciado
     * @param areas area de autoenfoque
     */
    @Override
    public void onAutofocusStarted(Rect[] areas) {
        // TODO: 20/01/2016
    }

    /**
     * se ejecuta cuando el autoenfoque es detenido
     * @param areas area de autoenfoque
     */
    @Override
    public void onAutofocusStopped(Rect[] areas) {
        // TODO: 20/01/2016
    }

    /**
     * se ejecuta cuando no se ha dectetado ningún código
     * @see QuadView
     */
    @Override
    public void onNothingDetected() {
        mQuadView.setDefaultTarget();
        mQuadView.publishDetectionStatus(DetectionStatus.FAIL);
    }

    /**
     * muestra puntos de interes en la camara en lo que podria ser un código para leer.
     * @param points lista de los puntos de interes
     * @param detectionStatus estado de la detección.
     * @see QuadView
     */
    @Override
    public void onDisplayPointsOfInterest(List<Point> points, DetectionStatus detectionStatus) {
        mQuadView.publishDetectionStatus(detectionStatus);
    }

    /**
     * Muestra un rectangulo encerrando lo que podria ser un código para leer
     * @param quadrilateral cuadricula o zona de interes
     * @param detectionStatus estado de la detección
     * @see QuadView
     */
    @Override
    public void onDisplayQuadrilateralObject(Quadrilateral quadrilateral, DetectionStatus detectionStatus) {
        mQuadView.setNewTarget(quadrilateral);
        mQuadView.publishDetectionStatus(detectionStatus);
    }
}
