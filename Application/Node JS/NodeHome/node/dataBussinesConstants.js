
var vKey = 'Constants';
var vResultado = ("version_name=2; version_subname=28; appStatus=0; filteredCount=6; database=\"ANALIIZO_DB\"; securityDatabase=\"SECURITY_DB\"; localSharedPreferences_name=\"config.com.miido.analiizo\"; localFile_name=\"lf.com.miido.analiizo.json\"; __DB=\"http://www.miido.com.co/ANALIIZO_DB.db\"; g __DB_BETA=\"http://www.miido.com.co/ANALIIZO_DB_BETA.db\"; __ST_developer=\"estructura modo desarrollo\"; __ST=\"http://miidolinux.cloudapp.net:3000/key/Poll\"; __SS=\"http://miidolinux.cloudapp.net:3000/key/LoginInterviewer\"; __SQS=\"https://sqs.us-west-2.amazonaws.com/615120578662/ANALIIZO\"; __SQS_old=\"https://sqs.us-west-2.amazonaws.com/948221686519/ANALIIZO\"; __KEY=\"AKIAJFRBUADO6RI5SXNA\"; __KEY_old=\"AKIAIJGKJHAHCQLJ5CIQ\"; __SKEY=\"6BVxmepSNBtiFd8FavzuSilbz3J+IA43qgBlFt9T'\"; __SKEY_old=\"8uaWDLAmtFJtzen2hLUw1JtAH3geyMbJ0Zf7gEbP\"; SimpleDateFormat=\"yyyy-MM-dd HH:mm:ss\"; mCursorDrawableRes=\"mCursorDrawableRes\"; separator=\" - \"; QUERY_1=\"SELECT [FIELDS] FROM [TABLE]\"; QUERY_2=\"SELECT [FIELDS] FROM [TABLE] WHERE [CONDITIONS]\"; QUERY_3=\"CREATE TABLE IF NOT EXISTS 'structure' ( 'iId' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, 'vchSurvey' TEXT NOT NULL, 'iStatus' INTEGER NOT NULL DEFAULT 2, 'iUpdateVersion' INTEGER NOT NULL);\"; QUERY_4=\"INSERT INTO structure VALUES (NULL, [STRUCTURE], [STATUS], [VERSION]);\"; QUERY_5=\"UPDATE structure SET iStatus=2\"; QUERY_6=\"SELECT vchSurvey FROM structure WHERE iStatus=1;\"; QUERY_7=\"SELECT COUNT(vchSurvey) FROM structure WHERE iStatus=1;\"; QUERY_8=\"SELECT vchSecurityStructure FROM security WHERE iSecurityStatus=1\"; QUERY_9=\"CREATE TABLE IF NOT EXISTS 'security' ('iSecurityId' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, 'vchSecurityStructure' TEXT NOT NULL, 'iSecurityStatus' INTEGER NOT NULL);\"; QUERY_10=\"UPDATE security SET iSecurityStatus=2\"; QUERY_11=\"INSERT INTO security VALUES (null, [STRUCTURE], 1)\"; latitude=\"Latitude\"; longitude=\"Longitude\"; accuracy=\"Accuracy\"; location=\"Location\"; String doc_info=\"DOCUMENTINFO\"; String home=\"HOME\"; String finished=\"Finished\"; String FinishedStructureVersion=\"FinishedStructureVersion\"; String FinishedAppVersion=\"FinishedAppVersion\"; String paused=\"Paused\"; String PausedStructureVersion=\"PausedStructureVersion\"; String PausedAppVersion=\"PausedAppVersion\"; String DataEdited=\"DataEdited\"; String user_id=\"userId\"; String username=\"username\"; String target=\"target\"; String case_id=\"case\"; String result=\"RESULT\"; String open=\"OPEN\"; String nButton=\"SIGUIENTE\"; String bButton=\"ANTERIOR\"; String eButton=\"Editar\"; String fButton=\"FINALIZAR\"; String sButton=\"GUARDAR\"; String oButton=\"OPCIONES\"; String pButton=\"PAUSAR\"; String rButton=\"REGRESAR\"; String aButton=\"+\"; String cButton=\"Cancelar\"; String afButton=\"Afiliado\"; String naButton=\"No Afiliado\"; String erButton=\"Error de Documento\"; String okButton=\"Aceptar\"; String summary=\"Resumen de la persona\"; String ats     =\"Ingreso al Sistema\"; String FHeader=\"INFORMACION DEL GRUPO FAMILIAR\"; String fHeader=\"GRUPO FAMILIAR\"; String pHeader=\"INFORMACION DE LA PERSONA\"; String hHeader=\"INFORMACION DE VIVINDA Y SATISFACCIÃ“N\"; String downOk=\"DESCARGA COMPLETA\"; String downFail=\"ERROR EN LA DESCARGA\"; String error=\"Error\"; String atention=\"AtenciÃ³n\"; String finish=\"Finalizado\"; String goBack=\"Regresar\"; String uParamsOk=\"Los parÃ¡metros de la aplicaciÃ³n han sido actualizados correctamente\"; String uParamsFail=\"VersiÃ³n de la aplicaciÃ³n desactualizada, no se pueden obtener parÃ¡metros\"; docTypesForDecoder={{\"MSI\", \"Menor sin identificaciÃ³n\"},{\"ASI\", \"Adulto sin identificacion\"},{\"RC\", \"Registro civil\"},{\"TI\", \"Tarjeta de identidad\"},{\"CC\", \"CÃ©dula de ciudadanÃ\u00ADa\"},{\"CE\", \"CÃ©dula de extranjerÃ\u00ADa\"},{\"PP\", \"Pasaporte\"}}; _STATUS002=\"ERROR DE DOCUMENTO\"; _STATUS003=\"AFILIADO\"; _ERROR001=\"Usuario y/o contraseÃ±a invÃ¡lidos\"; _EM001=\"BÃºsqueda de afiliado\"; _EM002=\"Ingrese:\\nNÃºmero de identificaciÃ³n, Nombres o Apellidos.\"; _EM003=\"Si mientras digita el documento, nombre o apellido de la persona no aparece dentro de la lista que se despliega, antes de continuar, por favor verifique si no se afiliÃ³ con otro documento, ejemplo: Menor sin IdentificaciÃ³n, Registro Civil, o Tarjeta de identidad\"; _EM004=\"Familia\"; _EM005=\"Fecha y hora de creaciÃ³n\"; _EM006=\"Fecha y hora de pausa\"; _EM007=\"Fecha y hora de FinalizaciÃ³n\"; _ADV000=\"Preparando la aplicacion para su primer uso.\"; _ADV001=\"Ingrese su usuario\"; _ADV002=\"Ingrese su contraseÃ±a\"; _ADV003=\"Esta opciÃ³n se encuentra inactiva\"; _ADV004=\"Descargando datos.\\nPor favor espere un momento.\"; _ADV005=\"Sincronizando parÃ¡metros de la aplicaciÃ³n.\\nPor favor espere un momento...\";_ADV006=\"No se ha encontrado una estructura disponible para la encuesta\"; _ADV007=\"Desafortunadamente hemos encontrado una falla grave en tu dispositivo.\\nSolicitamos por favor nos hagas saber del problema para poderte dar una soluciÃ³n.\"; _ADV008=\"No logramos encontrar informacion preliminar instalada en tu dispositivo, por favor realiza los siguientes pasos:\\nAjustes->Sincronizar Datos.\\nAjustes->Sincronizar parÃ¡metros\"; _ADV009=\"Creando interfaz, por favor espera un momento.\"; _ADV010=\"Verifique los campos resaltados para poder continuar\"; _ADV011=\"No se ha podido completar la solicitud, por favor revise que el dispositivo estÃ© conectado a internet\"; _ADV012=\"Error en el tipo de documento, esta persona deberÃ\u00ADa tener uno de los siguientes tipos de identificaciÃ³n:\"; _ADV013=\"Por favor espere un momento...\"; _ADV014=\"Algunas personas en la encuesta no han sido finalizadas.\"; _ADV015=\"No se puede eliminar una encuesta ya iniciada.\\nUse la opcion pausar para regresar sin perder informaciÃ³n\"; _ADV016=\"Recuerde que para reanudar la encuesta debe ir a la opcion \\\"Consultar / Examinar\\\" del menÃº principal.\"; _ADV017=\"No se ha iniciado una encuesta, para regresar al menÃº principal use la opcion \\\"Regresar\\\"\"; _ADV018=\"No se puede eliminar una encuesta ya iniciada.\\nPara finalizar la encuesta utilice la opcion \\\"Finalizar\\\".\"; _ADV019=\"No se ha encontrado ninguna encuesta pausada\"; _ADV020=\"Lamentablemente hemos encontrado que tu GPS se encuentra apagado, por lo tanto la aplicaciÃ³n no funcionarÃ¡ hasta que lo actives.\"; _ADV021=\"Se ha completado la descarga exitosamente.\"; _ADV022=\"Es posible que su usuario haya sido agregado hace poco.\\nÂ¿Desea sincronizar los usuarios de la aplicaciÃ³n?\"; _ADV023=\"No se ha llenado la encuesta de vivienda y satisfaccion,\\npara poder continuar es necesario llenar esta informaciÃ³n\"; _ADV024=\"Enviando encuestas\"; _ADV025=\"La fecha seleccionada no puede ser mayor a la fecha actual\"; _ADV026=\"Error en la fecha seleccionada\"; _ADV027=\"La persona seleccionada ya se ha agregado a la encuesta, por favor seleccione otra.\"; allowedTypes={\"tf\", \"dp\", \"sp\", \"cb\", \"tv\", \"rg\", \"ac\"}; allowedRules={\"vch\", \"int\", \"eml\", \"dec\"}; targetFilter={\"tipoId\", \"documento\", \"nombre\", \"apellido\", \"fecNac\"}; ageDocRules={{\"0\", \"0\", \"Menor sin identificaciÃ³n\"},{\"1\", \"7\", \"Registro civil\"},{\"8\", \"18\", \"Tarjeta de identidad\"},{\"18\", \"120\", \"CÃ©dula de ciudadanÃ\u00ADa\"},{\"18\", \"120\", \"CÃ©dula de extranjerÃ\u00ADa\"},{\"0\", \"120\", \"Pasaporte\"}}; familyLastNameIndex=2;");
var redis = require("redis")
    , client = redis.createClient();
 
client.on("error", function (err) {
    console.log("Error " + err);
});
 
client.on("connect", runSample);
 
function runSample() {
    // Set a value
    client.set(vKey, vResultado, function (err, reply) {
        console.log(reply.toString());
    });
    // Get a value
    client.get(vKey, function (err, reply) {
        console.log(reply.toString());
    });
}