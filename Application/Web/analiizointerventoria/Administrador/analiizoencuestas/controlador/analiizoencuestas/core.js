var structure;
var fields_structure;
var options;
var forms;
var forms_order;
var handler_event;
var handlerFieldJoiner;
var fieldsJoiner;
var aditionalFieldsRules;
var documentInfo;

var ss;
var currentForm;
var targetContainer;

var dNBButton;
var nButton;
var bButton;
var fButton;

var iSelected;
var cSelected;

var personMap = "";
var personsCounter = 0;
var home;
var house;

var ieOn;
var currDate;

var formContainer   = new Array();
var objectContainer = new Array();
var groupContainer   = JSON.parse("{}");
var personContainer  = JSON.parse("{}");
var personsContainer = JSON.parse("[]");
var homeContainer    = JSON.parse("{}");
var locationContainer= JSON.parse("{}");

var documentContainer= JSON.parse("{}");


$(document).ready(function () {
    if(sessionStorage.pollSended) {
        if(sessionStorage.pollSended.length > 10)
            location.href = "http://52.27.125.67/analiizopostgres/Administrador/analiizoencuestas/controlador/analiizoencuestas/gracias.html";
    }
    /*var isIE = (navigator.userAgent.indexOf("MSIE") != -1); ie9 = /MSIE 9/i.test(navigator.userAgent); ie10 = /MSIE 10/i.test(navigator.userAgent); ie11 = /rv:11.0/i.test(navigator.userAgent); if((ie11) || (ie11) || (ie10) || (ie9)){targetContainer = document.getElementById("masterDiv"); targetContainer. += "<h2>"+_MSJ11+"</h2>"; document.body.innerHTML += "<h3>"+_MSJ12+"</h3>"; return; }*/ var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yy = today.getFullYear();
    currDate = (yy+"-"+((mm < 10) ? "0"+mm : mm)+"-"+((dd < 10) ? "0"+dd : dd));
    var navegador = navigator.userAgent;
    targetContainer = document.getElementById("masterDiv");
    if ((navigator.userAgent.indexOf('MSIE') !=-1) || (navigator.userAgent.indexOf('rv:11') !=-1)) {
        targetContainer.innerHTML += ("<h2>"+_MSJ11+"</h2>"+"<h3>"+_MSJ12+"</h3>");
        return;
    } else
        ieOn = false;
    ss = 1;
    currentForm = 0;

    //personMap = "15";
    //home = "14";
    //house ="16";
    //_tester(JSON.parse("{\"llave\": \"valor\"}"), 3);
});

$( window ).unload(function() {
  //pendiente para seguridad de información.
});
/*if(r){
    $(window).bind('beforeunload', function() {
          return 'Si continuas podrías perder toda la informacion de la encuesta actual.';
          //return 0;
    });
}*/

function _tester(jsonObject, h){
    var action = "";
    var extras = "";
    if(h == 1){
        groupContainer = jsonObject;
        action = "SENDGROUP";
    } else if (h == 0){
        personsContainer[personsCounter] = jsonObject;
        action = "SENDPERSON";
    } else if (h == 2){
        homeContainer = jsonObject
        action = "SENDHOUSE";
    } else if (h == 3){
        action = "SENDPOLL";
        extras = personMap+","+home+","+house+"||0||"+personsContainer.length;
    }
    $.post("lib/27052015/$010620151159.php",
        {
            call:       action,
            arguments:  JSON.stringify(jsonObject),
            home:       home,
            extras:     extras
        },
        function (data) {
            if(h == 3){
               //setSqs();
               //setConfig();
               //setMessage(JSON.stringify(jsonObject));
                /*if(sendMessage()){
                    $(window).bind('beforeunload', true);
                    $( targetContainer ).hide("fade", {direction: "center"}, "medium", function(){location.href = "";});
                } else{
                    alert('Error al almacenar la informacion,\nes posible que no su equipo no tenga acceso a internet');
                    return;
                }*/
                //console.log(data);
                //console.error(jsonObject);
                //return;
                $(window).bind('beforeunload', true);
                sessionStorage.pollSended = "no borres esto por favorr!!!!! Dios te ama!! noo";
               $( targetContainer ).hide("fade", {direction: "center"}, "medium", function(){location.reload();});
            }

            personContainer  = JSON.parse("{}");
            //objectsCleaner();
            //currentForm = 0;
            /*if (h == 0) {
                personsCounter++;
                if(personMap.length > 0)
                    personMap += ",";
                personMap += data;
            } else if (h == 2) {
                house = data;
            }

            if(h == 1){
                home = data;
                $( formContainer[currentForm].cluster ).hide("fade", {}, "medium", pollViewer);
            } else {
                $( resumeContainer ).hide("fade", {}, "medium", pollViewer);
            }*/
        }
    ).fail(function() {
        alert( "Se han presentado algunos problemas al momento de guardar la informacion\nverifica tu conexion a internet y vuelve a intentar." );
    });
}


function init(){
    preloaded();
    setJSONStructure(strcuture_str);
    parseStructure();
    formsCreator();
    createObjects();
    createInterface(objectContainer);
    prepareElements();
    createNextBackButton();
    addNBButtonHandler();
    //selectInterviewer();
    //createFilterInterface();
    showFormG();
    //pollViewer();
    //showFormP();
    //showFormH();
}

//var url = "http://miidolinux.cloudapp.net:3000/key/Poll"; $.ajax({crossOrigin: true, type: "GET", url: url, success: function(data) {//console.log(data); alert(data); } }); var xmlhttp; xmlhttp = new XMLHttpRequest(); xmlhttp.onreadystatechange = function () {if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {alert(xmlhttp.responseText); alert("success"); } } xmlhttp.open("POST", url, true); xmlhttp.setRequestHeader('Content-Type', 'application/xml'); //xmlhttp.setRequestHeader("Content-Type", "application/json"); xmlhttp.setRequestHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept"); xmlhttp.setRequestHeader("Access-Control-Allow-Origin", "http://miidolinux.cloudapp.net"); xmlhttp.setRequestHeader('X-PINGOTHER', 'pingpong'); xmlhttp.setRequestHeader("Access-Control-Allow-Methods","POST, GET"); xmlhttp.setRequestHeader("Access-Control-Max-Age","1728000"); xmlhttp.send(); */

function preloaded(){
    var arr = "";
    for(var x in diseases){
      arr += ($.map(diseases[x], function(val) { return val; })+"||");
    }
    diseases = arr;

    var arr = "";
    for(var x in city){
      arr += ($.map(city[x], function(val) { return val; })+"||");
    }
    city = arr;

    var arr = "";
    for(var x in interviewer){
      arr += ($.map(interviewer[x], function(val) { return val; })+"||");
    }
    interviewer = arr;

    var arr = "";
    for(var x in lister){
      arr += ($.map(lister[x], function(val) { return val; })+"||");
    }
    lister = arr;

    var arr = "";
    for(var x in cums){
      arr += ($.map(cums[x], function(val) { return val; })+"||");
    }
    cums = arr;

    var arr = "";
    for(var x in cups){
      arr += ($.map(cups[x], function(val) { return val; })+"||");
    }
    cups = arr;

    var arr = "";
    for(var x in ciuo){
      arr += ($.map(ciuo[x], function(val) { return val; })+"||");
    }
    ciuo = arr;

    var arr = "";
    for(var x in deptos){
      arr += ($.map(deptos[x], function(val) { return val; })+"||");
    }
    deptos = arr;
}