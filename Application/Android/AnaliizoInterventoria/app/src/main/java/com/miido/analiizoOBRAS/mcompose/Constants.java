package com.miido.analiizoOBRAS.mcompose;

/**
 * Definición de variables globales de la aplicación.
 * @author Alvaro Salgado MIIDO S.A.S 19/02/2015
 * @version 1.0
 **/
public class Constants {

    /**
     * **************************** *
     *        APP CONSTANTS         *
     * **************************** *
     **/
    public final int version_name = 2;
    public final int version_subname = 28;
    public final int appStatus = 0;
    public final int filteredCount = 6;  //used for filtered person action
    public final String structureDatabase = "ANALIIZO_DB";
    public final String securityDatabase = "SECURITY_DB";
    public final String pollDatabase = "POLLDATA_DB";
    public final String localSharedPreferences_name = "config.com.analiizoOBRAS.analiizo";
    public final String localFile_name = "lf.com.analiizoOBRAS.analiizo.json";
    public final String __DB = "http://www.analiizoOBRAS.com.co/ANALIIZO_DB.db";
    //public final String __DB_BETA = "http://www.miido.com.co/ANALIIZO_DB_BETA.db";
    public final String __ST_developer = "estructura modo desarrollo";
    public final String __ST = "http://ec2-52-27-125-67.us-west-2.compute.amazonaws.com:1137/key/udcPoll";//testPoll
    public final String __SS = "http://ec2-52-27-125-67.us-west-2.compute.amazonaws.com:1137/key/LoginInterviewer";//LoginInterviewer
    public final String __SQS_old = "https://sqs.us-west-2.amazonaws.com/615120578662/ANALIIZO";
    public final String __SQS = "https://sqs.us-west-2.amazonaws.com/948221686519/ANALIIZO";
    public final String __KEY_old = "AKIAJFRBUADO6RI5SXNA";
    public final String __KEY = "AKIAIJGKJHAHCQLJ5CIQ";
    public final String __SKEY_old = "6BVxmepSNBtiFd8FavzuSilbz3J+IA43qgBlFt9T'";
    public final String __SKEY = "8uaWDLAmtFJtzen2hLUw1JtAH3geyMbJ0Zf7gEbP";
    public final String SimpleDateFormat = "yyyy-MM-dd HH:mm:ss";
    public final String mCursorDrawableRes = "mCursorDrawableRes";
    public final String separator = " - ";
    public final int buttonWidth = 100;

    /**
     * **************************** *
     *         SQL CONSTANTS        *
     * **************************** *
     **/
    public final String GENERIC_SELECT_QUERY_WITHOUT_CONDITIONS = "SELECT [FIELDS] FROM [TABLE]";
    public final String GENERIC_SELECT_QUERY_WITH_CONDITIONS = "SELECT [FIELDS] FROM [TABLE] WHERE [CONDITIONS]";
    public final String CREATE_STRUCTURE_DATA_TABLE_QUERY = "CREATE TABLE IF NOT EXISTS 'Structure' ( " +
            "'iId' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, " +
            "'vchSurvey' TEXT NOT NULL, 'iStatus' INTEGER NOT NULL DEFAULT 2, " +
            "'iUpdateVersion' INTEGER NOT NULL);";
    public final String INSERT_STRUCTURE_DATA_QUERY = "INSERT INTO Structure VALUES (NULL, '%s', %s, %s);";
    public final String UPDATE_STRUCTURE_DATA_QUERY = "UPDATE Structure SET iStatus = 2";
    public final String SELECT_STRUCTURE_DATA_QUERY = "SELECT vchSurvey FROM Structure WHERE iStatus = 1;";
    public final String QUERY_7 = "SELECT COUNT(vchSurvey) FROM Structure WHERE iStatus = 1;";
    public final String SELECT_USERS_DATA_BY_STATUS_QUERY = "SELECT vchSecurityStructure FROM security WHERE iSecurityStatus = 1";
    public final String CREATE_USERS_DATA_TABLE_QUERY = "CREATE TABLE IF NOT EXISTS 'security' (" +
            "'iSecurityId' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, " +
            "'vchSecurityStructure' TEXT NOT NULL, " +
            "'iSecurityStatus' INTEGER NOT NULL);";
    public final String UPDATE_USERS_DATA_QUERY = "UPDATE security SET iSecurityStatus = 2";
    public final String INSERT_USERS_DATA_QUERY = "INSERT INTO security VALUES (null, '%s', 1)";

    public final String INSERT_ITEM_QUERY = "INSERT INTO %s VALUES(%s,'%s');";
    public final String UPDATE_ITEM_QUERY = "UPDATE %s SET item = '%s' WHERE id = %s";
    public final String CREATE_DATA_PAUSED_TABLE_QUERY = "CREATE TABLE IF NOT EXISTS 'pollpause'( " +
            "'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT," +
            "'idstructure' INTEGER NOT NULL," +
            "'target' TEXT  NOT NULL," +
            "'paused' BOOLEAN NOT NULL DEFAULT 1);";
    public final String INSERT_DATA_PAUSED_QUERY = "INSERT INTO pollpause (idstructure,target) VALUES (%s,'%s');";
    public final String SELECT_DATA_PAUSED_QUERY = "SELECT * FROM pollpause WHERE idstructure = %s AND paused = 1;";
    public final String UPDATE_DATA_PAUSED_QUERY = "UPDATE pollpause SET paused = 0 WHERE idstructure = %s and paused = 1";
    public final String CREATE_POLL_DATA_TABLE_QUERY = "CREATE TABLE IF NOT EXISTS 'poll' ( " +
            "'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " +
            "'idstructure' INTEGER NOT NULL, " +
            "'polldata' TEXT NOT NULL, " +
            "'savedate' TEXT NOT NULL, " +
            "'senddate' TEXT);";
    public final String INSERT_POLL_DATA_QUERY = "INSERT INTO poll VALUES(null,%s,'%s','%s',null);";
    public final String SELECT_LAST_POLL_DATA_QUERY = "SELECT id FROM poll ORDER BY id DESC LIMIT 1;";
    public final String SELECT_POLL_DATA_QUERY = "SELECT id,polldata FROM poll WHERE idstructure = %s AND senddate is null;";
    public final String UPDATE_POLL_DATA_QUERY = "UPDATE poll SET senddate = '%s' WHERE idstructure = %s AND senddate is null;";
    public final String CREATE_PROJECTS_TABLE_QUERY = "CREATE TABLE IF NOT EXISTS 'projects' ( " +
            "'id' INTEGER NOT NULL PRIMARY KEY," +
            "'userid' INTEGER NOT NULL, " +
            "'name' TEXT NOT NULL," +
            "'description' TEXT NOT NULL);";
    public final String INSERT_PROJECT_DATA_QUERY = "INSERT INTO projects VALUES(%s,%s,'%s','%s');";
    public final String UPDATE_PROJECT_DATA_QUERY = "UPDATE projects SET name = '%s', description = '%s' WHERE id = %s;";

    public final String INSERT_RESOURCE_DATA_QUERY = "INSERT INTO resources (pollid,path,name,description,mime,savedate) VALUES (%s,'%s','%s','%s','%s','%s')";
    public final String INSERT_MULTIPLE_RESOURCE_DATA_QUERY = "INSERT INTO resources (pollid,path,name,description,mime,savedate) VALUES %s";
    public final String DELETE_RESOURCE_DATA_QUERY = "DELETE FROM resources WHERE name = '%s'";
    public final String SELECT_RESOURCE_DATA_QUERY = "SELECT name,description,path,mime,savedate,senddate FROM resources WHERE pollid = %s";
    public final String SELECT_RESOURCE_DATA_SENT_QUERY = "SELECT COUNT(pollid) FROM resources WHERE pollid = %s AND senddate IS NOT NULL";
    public final String SELECT_RESOURCE_DATA_SAVED_QUERY = "SELECT COUNT(pollid) FROM resources WHERE pollid = %s AND savedate IS NOT NULL AND senddate IS NULL";
    public final String UPDATE_RESOURCE_DATA_QUERY = "UPDATE resources SET senddate = '%s' WHERE pollid = %s";
    public final String CREATE_RESOURCES_DATA_QUERY = "CREATE TABLE IF NOT EXISTS 'resources' (" +
            "'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE," +
            "'pollid' INTEGER NOT NULL," +
            "'path' TEXT NOT NULL," +
            "'name' TEXT NOT NULL," +
            "'description' TEXT NOT NULL,"+
            "'mime' TEXT NOT NULL," +
            "'savedate' TEXT NOT NULL," +
            "'senddate' TEXT);";

    /**
     * **************************** *
     *     JSON NAME CONSTANTS      *
     * **************************** *
     **/
    public final String latitude = "Latitude";
    public final String longitude = "Longitude";
    public final String accuracy = "Accuracy";
    public final String location = "Location";
    public final String doc_info = "DOCUMENTINFO";
    public final String home = "HOME";
    public final String finished = "Finished";
    public final String FinishedStructureVersion = "FinishedStructureVersion";
    public final String FinishedAppVersion = "FinishedAppVersion";
    public final String paused = "Paused";
    public final String PausedStructureVersion = "PausedStructureVersion";
    public final String PausedAppVersion = "PausedAppVersion";
    public final String DataEdited = "DataEdited";

    /**
     * **************************** *
     *      BUNDLE NAME CONSTANTS   *
     * **************************** *
     **/
    public final String user_id = "userId";
    public final String username = "username";
    public final String target = "target";
    public final String case_id = "case";
    public final String result = "RESULT";
    public final String open = "OPEN";

    /**
     * *************************** *
     *    ENVIRONMENT CONSTANTS    *
     * *************************** *
     **/
    public final String nButton = "SIGUIENTE";
    public final String bButton = "ANTERIOR";
    public final String eButton = "Editar";
    public final String fButton = "FINALIZAR";
    public final String sButton = "GUARDAR";
    public final String oButton = "OPCIONES";
    public final String pButton = "PAUSAR";
    public final String rButton = "REGRESAR";
    public final String aButton = "+";
    public final String cButton = "Cancelar";
    public final String afButton = "Afiliado";
    public final String naButton = "No Afiliado";
    public final String erButton = "Nuevo Afiliado";
    public final String okButton = "Aceptar";
    public final String summary = "Resumen del informe";
    public final String ats      = "Ingreso al Sistema";

    public final String FHeader = "INFORMACION DEL GRUPO FAMILIAR";
    public final String fHeader = "GRUPO FAMILIAR";
    public final String pHeader = "INFORMACION DE LA PERSONA";
    public final String hHeader = "INFORMACION DE VIVIENDA Y SATISFACCIÓN";

    public final String downOk = "DESCARGA COMPLETA";
    public final String downFail = "ERROR EN LA DESCARGA";
    public final String error = "Error";
    public final String atention = "Atención";
    public final String finish = "Finalizado";
    public final String goBack = "Regresar";
    public final String persons= "Personas";
    public final String groupo = "Grupos";

    public final String uParamsOk = "Los parámetros de la aplicación han sido actualizados correctamente";
    public final String uParamsFail = "Versión de la aplicación desactualizada, no se pueden obtener parámetros";


    public final String[][] docTypesForDecoder = {
            {"MSI", "Menor sin identificación"},
            {"ASI", "Adulto sin identificacion"},
            {"RC", "Registro civil"},
            {"TI", "Tarjeta de identidad"},
            {"CC", "Cédula de ciudadanía"},
            {"CE", "Cédula de extranjería"},
            {"PP", "Pasaporte"}
    };

    /**
     * *************************** *
     *   STATUS MESSAGE CONSTANTS  *
     * *************************** *
     **/
    public final String _STATUS001 = "PAUSADO";
    public final String _STATUS002 = "ERROR DE DOCUMENTO";
    public final String _STATUS003 = "AFILIADO";

    /**
     * *************************** *
     *   ERROR MESSAGE CONSTANTS   *
     * *************************** *
     **/

    public final String _ERROR001 = "Usuario y/o contraseña inválidos";

    /**
     * ***************************** *
     * ENVIRONMENT MESSAGE CONSTANTS *
     * ***************************** *
     **/
    //public final String _EM000 = "";
    public final String _EM001 = "Búsqueda de afiliado";
    public final String _EM002 = "Ingrese:\nNúmero de identificación, Nombres o Apellidos.";
    public final String _EM003 = "Si mientras digita el documento, nombre o apellido de la persona no aparece dentro de la lista que se despliega, antes de continuar, por favor verifique si no se afilió con otro documento, ejemplo: Menor sin Identificación, Registro Civil, o Tarjeta de identidad";
    public final String _EM004 = "Encuesta";
    public final String _EM005 = "Fecha y hora de creación";
    public final String _EM006 = "Fecha y hora de pausa";
    public final String _EM007 = "Fecha y hora de Finalización";


    /**
     * *************************** *
     * ADVERTISE MESSAGE CONSTANTS *
     * *************************** *
     **/

    public final String _ADV000 = "Preparando la aplicacion para su primer uso.";
    public final String _ADV001 = "Ingrese su usuario";
    public final String _ADV002 = "Ingrese su contraseña";
    public final String _ADV003 = "Esta opción se encuentra inactiva";
    public final String _ADV004 = "Descargando datos.\nPor favor espere un momento.";
    public final String _ADV005 = "Sincronizando parámetros de la aplicación.\nPor favor espere un momento...";//"¡Hey compa aguanta el burro o te doy un cocotazo!";
    public final String _ADV006 = "No se ha encontrado una estructura disponible para la encuesta";
    public final String _ADV007 = "Desafortunadamente hemos encontrado una falla grave en tu dispositivo.\nSolicitamos por favor nos hagas saber del problema para poderte dar una solución.";
    public final String _ADV008 = "No logramos encontrar informacion preliminar instalada en tu dispositivo, por favor realiza los siguientes pasos:\nAjustes->Sincronizar Datos.\nAjustes->Sincronizar parámetros";
    public final String _ADV009 = "Creando interfaz, por favor espera un momento.";
    public final String _ADV010 = "Verifique los campos resaltados para poder continuar";
    public final String _ADV011 = "No se ha podido completar la solicitud, por favor revise que el dispositivo esté conectado a internet";
    public final String _ADV012 = "Error en el tipo de documento, esta persona debería tener uno de los siguientes tipos de identificación:";
    public final String _ADV013 = "Por favor espere un momento...";
    public final String _ADV014 = "Algunas personas en la encuesta no han sido finalizadas.";
    public final String _ADV015 = "No se puede eliminar una encuesta ya iniciada.\nUse la opcion pausar para regresar sin perder información";
    public final String _ADV016 = "Recuerde que para reanudar la encuesta debe ir a la opcion \"Consultar / Examinar\" del menú principal.";
    public final String _ADV017 = "No se ha iniciado una encuesta, para regresar al menú principal use la opcion \"Regresar\"";
    public final String _ADV018 = "No se puede eliminar una encuesta ya iniciada.\nPara finalizar la encuesta utilice la opcion \"Finalizar\".";
    public final String _ADV019 = "No se ha encontrado ninguna encuesta pausada";
    public final String _ADV020 = "Lamentablemente hemos encontrado que tu GPS se encuentra apagado, por lo tanto la aplicación no funcionará hasta que lo actives.";
    public final String _ADV021 = "Se ha completado la descarga exitosamente.";
    public final String _ADV022 = "Es posible que su usuario haya sido agregado hace poco.\n¿Desea sincronizar los usuarios de la aplicación?";
    public final String _ADV023 = "No se ha llenado la encuesta de vivienda y satisfaccion,\npara poder continuar es necesario llenar esta información";
    public final String _ADV024 = "Enviando encuestas";
    public final String _ADV025 = "La fecha seleccionada no puede ser mayor a la fecha actual";
    public final String _ADV026 = "Error en la fecha seleccionada";
    public final String _ADV027 = "La persona seleccionada ya se ha agregado a la encuesta, por favor seleccione otra.";

    /**
     * *************************** *
     *          UTILITIES          *
     ***************************** *
     **/

    public final String[] allowedTypes = {"tf", "dp", "sp", "cb", "tv", "rg", "ac"};
    public final String[] allowedRules = {"vch", "int", "eml", "dec"};
    public final String[] targetFilter = {"tipoId", "documento", "nombre", "apellido", "fecNac"};
    public final String[][] ageDocRules = {
            {"0", "0", "Menor sin identificación"},
            {"1", "7", "Registro civil"},
            {"7", "18", "Tarjeta de identidad"},
            {"18", "120", "Cédula de ciudadanía"},
            {"18", "120", "Cédula de extranjería"},
            {"0", "120", "Pasaporte"}};
    public final int familyLastNameIndex = 2;
    public final String[][] fieldsToFilter = {
            {"diseases", "vchDiseaseKey,vchDiseaseDescription"},
            {"ciuo", "vchCode,vchDescription"},
            {"cups", "vchDescription"},
            {"medicaments", "vchDescription"},
            {"insumos","item"},
            {"observacionparticular","item"},
            {"itemproeje","item"},
            {"muestraensayo", "item"},
            {"equipos", "item"}};

    public final String disPrefix = "per";

    /**
     * *************************** *
     *     STORAGE TRANSLATIONS    *
     * *************************** *
     **/
    public final String perMedic = "perMedic";
    public final String medicaments = "medicaments";
    public final String perProvee = "perProvee1_1";
    public final String perEvoluc = "perEvoluc1_1";
    public final String medProv = "medProv";
    public final String medEvol = "medEvol";
    public final String disName = "disName";
    public final String disStat = "disStat";
    public final String medDesc = "medDesc";
    public final String disCode = "disCode";
    public final String diseases = "diseases";
    public final String perPrefix = "per";
    public final String pCodEnf = "pCodEnf";


    /**
     * *************************** *
     *     DEVELOPER CONSTANTS     *
     * *************************** *
     **/

    public final String securityDev = "[[{\"strUsername\":\"usr\",\"strHashPassword\":\"202cb962ac59075b964b07152d234b70\"}]]";
    public final String[] developer_devices = {
            "78:24:af:57:71:9a"};


    public final String structure = "{\"forms\": [{\"Id\": \"0\", \"Name\": \"semana1\", \"Header\": \"Semana 1\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"2\", \"Name\": \"personaldeobra\", \"Header\": \"personal de obra\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"3\", \"Name\": \"maestroprocedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 2, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"4\", \"Name\": \"oficialprocedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 6, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"5\", \"Name\": \"ayudanteprocedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 10, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"6\", \"Name\": \"inspector procedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 14, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"7\", \"Name\": \"tecnicoprocedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 18, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"8\", \"Name\": \"especialistaprocedencia\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 22, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"9\", \"Name\": \"personaldeinterventoria\", \"Header\": \"personal de interventoría\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"10\", \"Name\": \"inspectorprocedenciaint\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 26, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"11\", \"Name\": \"tecnicoprocedenciaint\", \"Header\": \"procedencia \", \"Inside\": \"1\", \"Parent\": 30, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"12\", \"Name\": \"especialistaprocedenciaint\", \"Header\": \"procedencia\", \"Inside\": \"1\", \"Parent\": 34, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"13\", \"Name\": \"ASPECTOSDESEG.IND.AMBIENTALESYPGIO\", \"Header\": \"ASPECTOS DE SEG. IND. AMBIENTALES Y PGIO\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"14\", \"Name\": \"CONTROLDECALIDADDEMATERIALES\", \"Header\": \"CONTROL DE CALIDAD DE MATERIALES\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"15\", \"Name\": \"muestra list\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 55, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"29\", \"Name\": \"CONTROL DE CALIDAD DE MATERIALES - OTROS\", \"Header\": \"CONTROL DE CALIDAD DE MATERIALES - OTROS\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"30\", \"Name\": \"Control Materiales Otros\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 80, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"16\", \"Name\": \"VEEDURIACIUDADANA\", \"Header\": \"VEEDURÍA CIUDADANA\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"17\", \"Name\": \"veeduriaresolucion\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 58, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"18\", \"Name\": \"ASPECTOSTECNICOS\", \"Header\": \"ASPECTOS TECNICOS\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"19\", \"Name\": \"RESULTADOSDEENSAYOSDEMATERIALES\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 62, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"20\", \"Name\": \"ASPECTOSTECNICOSINSUMOS\", \"Header\": \"ASPECTOS TÉCNICOS - INSUMOS (EN OBRA)\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"21\", \"Name\": \"ASPECTOSTECNICOSEQUIPOS\", \"Header\": \"ASPECTOS TÉCNICOS - EQUIPOS\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"22\", \"Name\": \"ASPECTOSTECNICOSOBSERVPARTICULARES\", \"Header\": \"ASPECTOS TÉCNICOS - OBSERV. PARTICULARES\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"23\", \"Name\": \"OBRAS PROVISIONALES, DE EXPLANACIÓN Y CONFORMACIÓN DE SUBRASANTE\", \"Header\": \"OBRAS PROVISIONALES, DE EXPLANACIÓN Y CONFORMACIÓN DE SUBRASANTE\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"24\", \"Name\": \"CANTIDAD\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 72, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"25\", \"Name\": \"ACTIVIDADES NO PROGRAMADAS\", \"Header\": \"ACTIVIDADES NO PROGRAMADAS\", \"Inside\": \"0\", \"Parent\": \"0\", \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"26\", \"Name\": \"actividades no programadas\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 74, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"27\", \"Name\": \"descripcion botadero\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 49, \"Handler\": \"0\", \"Clonable\": \"0\"}, {\"Id\": \"28\", \"Name\": \"horas por semana\", \"Header\": \"\", \"Inside\": \"1\", \"Parent\": 51, \"Handler\": \"0\", \"Clonable\": \"0\"}], \"options\": [{\"Id\": 0, \"Field\": [2], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 1, \"Field\": [6], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 2, \"Field\": [10], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 3, \"Field\": [14], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 4, \"Field\": [18], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 5, \"Field\": [22], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 6, \"Field\": [26], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 7, \"Field\": [30], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 8, \"Field\": [34], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 9, \"Field\": [38], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 10, \"Field\": [39], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 11, \"Field\": [40], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 12, \"Field\": [41], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 13, \"Field\": [42], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 14, \"Field\": [43], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 15, \"Field\": [44], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 17, \"Field\": [46], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 18, \"Field\": [47], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 19, \"Field\": [48], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 20, \"Field\": [49], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 21, \"Field\": [50], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 22, \"Field\": [51], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 23, \"Field\": [52], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 24, \"Field\": [53], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 25, \"Field\": [54], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 26, \"Field\": [55], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 27, \"Field\": [58], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 28, \"Field\": [60], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 29, \"Field\": [61], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 30, \"Field\": [62], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 31, \"Field\": [63], \"Options\": [\"-\", \"SI \", \"NO\"]}, {\"Id\": 32, \"Field\": [68], \"Options\": [\"-\", \"BUENO\", \"REGULAR\", \"MALO\"]}, {\"Id\": 33, \"Field\": [72], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 34, \"Field\": [74], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 35, \"Field\": [80], \"Options\": [\"-\", \"SI\", \"NO\"]}, {\"Id\": 36, \"Field\": [81], \"Options\": [\"-\", \"PROCTOR 95%\", \"PROCTOR 90%\", \"GRANULOMETRÍA\"]}], \"forms_order\": [], \"fieldsJoiner\": [], \"Document_info\": {\"clientId\": \"2\", \"projectId\": \"1\", \"structureDes\": \"SEMANA 1\", \"structureName\": \"SEMANA 1\", \"minVersionName\": \"2.24\", \"structureStatus\": 0, \"structureVersion\": 1, \"currentAppVersion\": \"2.24\"}, \"dynamicJoiner\": [{\"field\": \"66\", \"handler\": \"4\", \"formJoined\": \"20\"}, {\"field\": \"69\", \"handler\": \"4\", \"formJoined\": \"21\"}, {\"field\": \"71\", \"handler\": \"4\", \"formJoined\": \"22\"}, {\"field\": \"57\", \"handler\": \"4\", \"formJoined\": \"15\"}, {\"field\": \"82\", \"handler\": \"4\", \"formJoined\": \"30\"}, {\"field\": \"83\", \"handler\": \"4\", \"formJoined\": \"26\"}], \"handler_event\": [{\"Id\": \"1\", \"Types\": [\"=\"], \"Parameters\": [\"SI\"]}, {\"Id\": \"2\", \"Types\": [\"!=\"], \"Parameters\": [\"\"]}, {\"Id\": \"3\", \"Types\": [\"!=\"], \"Parameters\": [\"-\"]}, {\"Id\": \"4\", \"Types\": [\"=\"], \"Parameters\": [\"on\"]}], \"fields_structure\": [{\"Id\": \"1\", \"Form\": \"0\", \"Hint\": \"\", \"Name\": \"nombredeinforme1\", \"Type\": \"tf\", \"Label\": \"nombre de informe\", \"Order\": \"0\", \"Rules\": \"vch\", \"Length\": \"200\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": \"2\", \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"maestro2\", \"Type\": \"rg\", \"Label\": \"maestro\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 3, \"Form\": \"3\", \"Hint\": \"\", \"Name\": \"dezona3\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 4, \"Form\": \"3\", \"Hint\": \"\", \"Name\": \"fueradezona4\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 5, \"Form\": \"3\", \"Hint\": \"\", \"Name\": \"vulnerable5\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 6, \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"oficial6\", \"Type\": \"rg\", \"Label\": \"oficial\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 7, \"Form\": \"4\", \"Hint\": \"\", \"Name\": \"dezona7\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 8, \"Form\": \"4\", \"Hint\": \"\", \"Name\": \"fueradezona8\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 9, \"Form\": \"4\", \"Hint\": \"\", \"Name\": \"vulnerable9\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 10, \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"ayudante10\", \"Type\": \"rg\", \"Label\": \"ayudante\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 11, \"Form\": \"5\", \"Hint\": \"\", \"Name\": \"dezona11\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 12, \"Form\": \"5\", \"Hint\": \"\", \"Name\": \"fueradezona12\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 13, \"Form\": \"5\", \"Hint\": \"\", \"Name\": \"vulnerable13\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 14, \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"inspector14\", \"Type\": \"rg\", \"Label\": \"inspector\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 15, \"Form\": \"6\", \"Hint\": \"\", \"Name\": \"dezona15\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 16, \"Form\": \"6\", \"Hint\": \"\", \"Name\": \"fueradezona16\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 17, \"Form\": \"6\", \"Hint\": \"\", \"Name\": \"vulnerable17\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 18, \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"tecnico18\", \"Type\": \"rg\", \"Label\": \"tecnico\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 19, \"Form\": \"7\", \"Hint\": \"\", \"Name\": \"dezona19\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 20, \"Form\": \"7\", \"Hint\": \"\", \"Name\": \"fueradezona20\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 21, \"Form\": \"7\", \"Hint\": \"\", \"Name\": \"vulnerable21\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 22, \"Form\": \"2\", \"Hint\": \"0\", \"Name\": \"especialista22\", \"Type\": \"rg\", \"Label\": \"especialista\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 23, \"Form\": \"8\", \"Hint\": \"\", \"Name\": \"dezona23\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 24, \"Form\": \"8\", \"Hint\": \"\", \"Name\": \"fueradezona24\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 25, \"Form\": \"8\", \"Hint\": \"\", \"Name\": \"vulnerable25\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 26, \"Form\": \"9\", \"Hint\": \"0\", \"Name\": \"inspector26\", \"Type\": \"rg\", \"Label\": \"inspector\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 27, \"Form\": \"10\", \"Hint\": \"\", \"Name\": \"dezona27\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 28, \"Form\": \"10\", \"Hint\": \"\", \"Name\": \"fueradezona28\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 29, \"Form\": \"10\", \"Hint\": \"\", \"Name\": \"vulnerable10\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 30, \"Form\": \"9\", \"Hint\": \"0\", \"Name\": \"tecnico30\", \"Type\": \"rg\", \"Label\": \"tecnico\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 31, \"Form\": \"11\", \"Hint\": \"\", \"Name\": \"dezona31\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 32, \"Form\": \"11\", \"Hint\": \"\", \"Name\": \"fueradezona11\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 33, \"Form\": \"11\", \"Hint\": \"\", \"Name\": \"vulnerable33\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 34, \"Form\": \"9\", \"Hint\": \"0\", \"Name\": \"especialista34\", \"Type\": \"rg\", \"Label\": \"especialista\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 35, \"Form\": \"12\", \"Hint\": \"\", \"Name\": \"dezona35\", \"Type\": \"tf\", \"Label\": \"de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 36, \"Form\": \"12\", \"Hint\": \"\", \"Name\": \"fueradezona36\", \"Type\": \"tf\", \"Label\": \"fuera de zona\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 37, \"Form\": \"12\", \"Hint\": \"\", \"Name\": \"vulnerable37\", \"Type\": \"tf\", \"Label\": \"vulnerable\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"50\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 38, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"epp38\", \"Type\": \"rg\", \"Label\": \"EPP\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 39, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"lineadevida39\", \"Type\": \"rg\", \"Label\": \"LINEA DE VIDA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 40, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"arnes40\", \"Type\": \"rg\", \"Label\": \"ARNES\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 41, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"certaltura41\", \"Type\": \"rg\", \"Label\": \"CERT. ALTURA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 42, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"senderospeatonales42\", \"Type\": \"rg\", \"Label\": \"SENDEROS PEATONALES\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 43, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"camilla43\", \"Type\": \"rg\", \"Label\": \"CAMILLA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 44, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"extinguidor44\", \"Type\": \"rg\", \"Label\": \"EXTINGUIDOR\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 46, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"canecareciclaje46\", \"Type\": \"rg\", \"Label\": \"CANECA-RECICLAJE\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 47, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"sealizacionpeligro47\", \"Type\": \"rg\", \"Label\": \"SEÑALIZACION-PELIGRO\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 48, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"charlasdemin48\", \"Type\": \"rg\", \"Label\": \"CHARLAS DE 5 MIN\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 49, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"botaderoautorizado49\", \"Type\": \"rg\", \"Label\": \"BOTADERO AUTORIZADO\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 78, \"Form\": \"27\", \"Hint\": \"\", \"Name\": \"nombrebotadero78\", \"Type\": \"tf\", \"Label\": \"Nombre Botadero\", \"Order\": \"0\", \"Rules\": \"\", \"Length\": \"200\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 50, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"fuentedematerialespetreos50\", \"Type\": \"rg\", \"Label\": \"FUENTE DE MATERIALES PETREOS\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 51, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"regdelluviasacum51\", \"Type\": \"rg\", \"Label\": \"REG. DE LLUVIAS ACUM.\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 79, \"Form\": \"28\", \"Hint\": \"\", \"Name\": \"horasporsemana79\", \"Type\": \"tf\", \"Label\": \"Horas por Semana\", \"Order\": \"0\", \"Rules\": \"dec\", \"Length\": \"100\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 52, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"campamentoalmacen52\", \"Type\": \"rg\", \"Label\": \"CAMPAMENTO-ALMACEN\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 53, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"baos53\", \"Type\": \"rg\", \"Label\": \"BAÑOS\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 54, \"Form\": \"13\", \"Hint\": \"0\", \"Name\": \"planambiental54\", \"Type\": \"rg\", \"Label\": \"PLAN AMBIENTAL\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 55, \"Form\": \"14\", \"Hint\": \"0\", \"Name\": \"muestra55\", \"Type\": \"rg\", \"Label\": \"muestra de concretos\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 56, \"Form\": \"15\", \"Hint\": \"\", \"Name\": \"muestra56\", \"Type\": \"ac\", \"Label\": \"muestra\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"muestraensayo\"}, {\"Id\": \"57\", \"Form\": \"15\", \"Hint\": \"\", \"Name\": \"aadir57\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": 80, \"Form\": \"29\", \"Hint\": \"0\", \"Name\": \"otros80\", \"Type\": \"rg\", \"Label\": \"Otros\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 81, \"Form\": \"30\", \"Hint\": \"0\", \"Name\": \"otrasmuestras81\", \"Type\": \"sp\", \"Label\": \"Otras Muestras\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": \"82\", \"Form\": \"30\", \"Hint\": \"\", \"Name\": \"aadir82\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": 58, \"Form\": \"16\", \"Hint\": \"0\", \"Name\": \"veeduriaciudadana55\", \"Type\": \"rg\", \"Label\": \"VEEDURIA CIUDADANA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 59, \"Form\": \"17\", \"Hint\": \"\", \"Name\": \"resolucion59\", \"Type\": \"tf\", \"Label\": \"resolucion\", \"Order\": \"0\", \"Rules\": \"vch\", \"Length\": \"200\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 77, \"Form\": \"17\", \"Hint\": \"\", \"Name\": \"numresolucin77\", \"Type\": \"tf\", \"Label\": \"Num. Resolución\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"100\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 60, \"Form\": \"18\", \"Hint\": \"0\", \"Name\": \"planosenobra60\", \"Type\": \"rg\", \"Label\": \"PLANOS EN OBRA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 61, \"Form\": \"18\", \"Hint\": \"0\", \"Name\": \"programaciondeobra61\", \"Type\": \"rg\", \"Label\": \"PROGRAMACION DE OBRA\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 62, \"Form\": \"18\", \"Hint\": \"0\", \"Name\": \"resultadosdeensayosdemateriales62\", \"Type\": \"rg\", \"Label\": \"RESULTADOS DE ENSAYOS DE MATERIALES\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 63, \"Form\": \"19\", \"Hint\": \"0\", \"Name\": \"cumple63\", \"Type\": \"rg\", \"Label\": \"CUMPLE\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 64, \"Form\": \"20\", \"Hint\": \"\", \"Name\": \"insumos64\", \"Type\": \"ac\", \"Label\": \"INSUMOS\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"insumos\"}, {\"Id\": 65, \"Form\": \"20\", \"Hint\": \"\", \"Name\": \"cantidad65\", \"Type\": \"tf\", \"Label\": \"CANTIDAD\", \"Order\": \"0\", \"Rules\": \"int\", \"Length\": \"1000\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": \"66\", \"Form\": \"20\", \"Hint\": \"\", \"Name\": \"aadir66\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": 67, \"Form\": \"21\", \"Hint\": \"\", \"Name\": \"equipo67\", \"Type\": \"ac\", \"Label\": \"EQUIPO\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"3\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"equipos\"}, {\"Id\": 68, \"Form\": \"21\", \"Hint\": \"0\", \"Name\": \"estado68\", \"Type\": \"sp\", \"Label\": \"ESTADO\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": \"69\", \"Form\": \"21\", \"Hint\": \"\", \"Name\": \"aadir69\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": 70, \"Form\": \"22\", \"Hint\": \"\", \"Name\": \"observacionesparticulares60\", \"Type\": \"ac\", \"Label\": \"OBSERVACIONES PARTICULARES\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"observacionparticular\"}, {\"Id\": \"71\", \"Form\": \"22\", \"Hint\": \"\", \"Name\": \"aadir71\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}, {\"Id\": 72, \"Form\": \"23\", \"Hint\": \"0\", \"Name\": \"obrasprovisionalesdeexplanacinyconformacindesubrasante72\", \"Type\": \"rg\", \"Label\": \"OBRAS PROVISIONALES, DE EXPLANACIÓN Y CONFORMACIÓN DE SUBRASANTE\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 73, \"Form\": \"24\", \"Hint\": \"\", \"Name\": \"localizacinyreplanteomanualparquesycanchasm73\", \"Type\": \"tf\", \"Label\": \"Localización y Replanteo manual Parques y canchas m2\", \"Order\": \"0\", \"Rules\": \"dec\", \"Length\": \"100\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 74, \"Form\": \"25\", \"Hint\": \"0\", \"Name\": \"actividadesnoprogramadas74\", \"Type\": \"rg\", \"Label\": \"Actividades No Programadas\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"1\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": 75, \"Form\": \"26\", \"Hint\": \"\", \"Name\": \"actividad75\", \"Type\": \"ac\", \"Label\": \"Actividad\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"actividadproyecto\"}, {\"Id\": 76, \"Form\": \"26\", \"Hint\": \"\", \"Name\": \"cantidad76\", \"Type\": \"tf\", \"Label\": \"Cantidad\", \"Order\": \"0\", \"Rules\": \"dec\", \"Length\": \"100\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"true\", \"AutoComplete\": \"0\"}, {\"Id\": \"83\", \"Form\": \"26\", \"Hint\": \"\", \"Name\": \"aadir83\", \"Type\": \"cb\", \"Label\": \"añadir\", \"Order\": \"0\", \"Rules\": \"0\", \"Length\": \"0\", \"Parent\": \"0\", \"FreeAdd\": \"0\", \"Handler\": \"0\", \"ReadOnly\": \"false\", \"Required\": \"false\", \"AutoComplete\": \"0\"}], \"HandlerFieldJoiner\": [], \"AditionalFieldsRules\": []}";
}
