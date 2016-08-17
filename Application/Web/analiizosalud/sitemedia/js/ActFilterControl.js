function enviar(inputString) {
    //alert(inputString);
    for (var i = 0; i < 200; i++) {
        document.getElementById('D' + i).value = inputString;
        document.getElementById('M' + i).value = inputString;
    }
}

$(document).ready(function(){
    $("#BAddFilter").unbind();
    $("#BAddFilter").bind("click", function() {
        var divFil = $("#SelFilter").val();
        if (divFil != "" && divFil != null) {
            var divfilEle = document.getElementById('SelFilter');
            var div6col = document.getElementById('1');
            var div6col2 = document.getElementById('2');
            var divFilM = document.createElement("div");
            var divpanel = document.createElement("div");
            var divboton = document.createElement("div");
            var buttonClose = document.createElement("INPUT");
            var lbDivFilM= document.createElement("LABEL");
            var divpanel2 = document.createElement("DIV");

            buttonClose.type = "button";
            buttonClose.className = "closeButton";
            buttonClose.value = "x";
            buttonClose.id = divFil+"bc";

            divfilEle.options[divfilEle.selectedIndex].disabled = true;
            var text = divfilEle.options[divfilEle.selectedIndex].innerHTML;

            lbDivFilM.id = divFil+"l";
            lbDivFilM.innerHTML = text+": ";

            divFilM.id = divFil;
            divpanel.id = divFil+"_panel";
            divpanel2.id = divFil+"_panel2";
            divboton.id = divFil+"db";

            divFilM.className = "panel panel-default";
            divpanel.className = "panel-body";
            divpanel2.className = "panel-body";
            divboton.className = "closeDiv";

            divboton.appendChild(buttonClose);
            divpanel.appendChild(divboton);
            divpanel.appendChild(lbDivFilM);
            divFilM.appendChild(divpanel);
            divFilM.appendChild(divpanel2);
            divCount1 = div6col.getElementsByTagName('div').length;
            divCount2 = div6col2.getElementsByTagName('div').length;

            if (divCount1 <= divCount2) {
                div6col.appendChild(divFilM);   
            }else{
                div6col2.appendChild(divFilM);   
            }
            //Elimina el filtro al cual se le dio click y activa el filtro en el combo
            $(divboton).bind("click", function() {
                var dom = $(this);
                filtro = dom.context.id;
                var idTmp = filtro.split("db");
                $('#'+idTmp[0]).remove();
                var op = document.getElementById("SelFilter").getElementsByTagName("option");
                for (var i = 0; i < op.length; i++) {
                    if (op[i].value == idTmp[0]) {
                        op[i].disabled = false;
                    }
                }
            });
            opciones(divFil, divpanel2);
        };
    });
});

function opciones(columna, divpanel2) {
    proyecto = document.getElementById('project').value;
    encuesta = document.getElementById('poll').value;
    $.post("opcionesreporteador.php", 
        {
            column: "" + columna + "",
            proyecto: "" + proyecto + "",
            encuesta: "" + encuesta + ""
        }, function (data) {
        obj = document.getElementById('#'+columna);
        $(divpanel2).html(data);
    });
}

var counter = 1;
function crearOpciones(select, campo){
    var divPadre = select.parentNode;  
    var dom = $(select);
    idSelect = dom.context.id;
    var valSelOpe = document.getElementById(idSelect).value;
    var text = select.options[select.selectedIndex].innerHTML;
    if (text != "Entre") {
        if (text != "Seleccione") {
            select.options[select.selectedIndex].disabled = true;
        };
    };

    if (text != "Seleccione") {
        //select.options.selectedIndex = "";
        var divOptOpe = document.createElement("div");
        var lbValOp= document.createElement("LABEL");
        var inValOp = document.createElement("INPUT");
        var lbSince= document.createElement("LABEL");
        var inSince = document.createElement("INPUT");
        var lbUntil= document.createElement("LABEL");
        var inUntil = document.createElement("INPUT");
        var divbotonAdd = document.createElement("div");
        var buttonDel = document.createElement("INPUT");

        divbotonAdd.id = text+"_"+counter;
        divbotonAdd.style = "margin-left : 35em";

        buttonDel.type = "button";
        buttonDel.className = "closeButton";
        buttonDel.value = "-";
        buttonDel.id = campo+"ba";

        inValOp.type = "number";
        inValOp.min = "0";
        inValOp.max = "300";
        inValOp.placeholder = "Ingrese Valor";
        inValOp.required = "required";

        inSince.type = "number";
        inSince.min = "0";
        inSince.max = "300";
        inSince.placeholder = "Valor 1";
        inSince.required = "required";

        inUntil.type = "number";
        inUntil.min = "0";
        inUntil.max = "300";
        inUntil.placeholder = "Valor 2";
        inUntil.required = "required";

        lbValOp.className = "col-lg-8 control-label";
        inValOp.className = "form-control";
        divOptOpe.className = "panel-body col-lg-8";
        lbSince.className = "col-lg-8 control-label";
        inSince.className = "form-control";
        lbUntil.className = "col-lg-8 control-label";
        inUntil.className = "form-control";

        lbSince.id = text;
        divOptOpe.id = text+"_"+campo+counter;
        inSince.id = "since_"+campo+counter;
        inUntil.id = "until_"+campo+counter;
        inSince.name = "since_"+campo+counter;
        inUntil.name = "until_"+campo+counter;

        switch(valSelOpe){
            case "<":
                inValOp.name = valSelOpe+"_"+campo+counter;
                inValOp.id = valSelOpe+"_"+campo+counter;
                break;
            case ">":
                inValOp.name = valSelOpe+"_"+campo+counter;
                inValOp.id = valSelOpe+"_"+campo+counter;
                break;
            case "<=":
                inValOp.name = valSelOpe+"_"+campo+counter;
                inValOp.id = valSelOpe+"_"+campo+counter;
                break;
            case ">=":
                inValOp.name = valSelOpe+"_"+campo+counter;
                inValOp.id = valSelOpe+"_"+campo+counter;
                break;
        }
        
        var idDivPri = divPadre.id;
        idDivPri = idDivPri.split("_");
        idSelOpt = "";
        for (var i = 0; i < idDivPri.length - 1; i++) {
            if(idDivPri[i] != "panel2"){
                if (i == idDivPri.length - 2) {
                    idSelOpt += idDivPri[i];
                }else{
                    idSelOpt += idDivPri[i]+"_";
                };
            }
        };

        var divOper = divPadre.getElementsByTagName('div');
        switch(text){
            case 'Entre':
                divbotonAdd.appendChild(buttonDel);
                lbSince.innerHTML = "Desde: ";
                lbUntil.innerHTML = "Hasta: ";
                divOptOpe.appendChild(lbSince);
                divOptOpe.appendChild(inSince);
                divOptOpe.appendChild(lbUntil);
                divOptOpe.appendChild(inUntil);
                divOptOpe.appendChild(divbotonAdd);
                divPadre.appendChild(divOptOpe);
                break;
            case 'Menor a':
                divbotonAdd.appendChild(buttonDel);
                lbValOp.innerHTML = text+": ";
                divOptOpe.appendChild(lbValOp);
                divOptOpe.appendChild(inValOp);
                divOptOpe.appendChild(divbotonAdd);
                divPadre.appendChild(divOptOpe);

                try{
                    for (i=0;divOper.length;i++){
                        var idDiv = divOper[i].id;
                        idDiv = idDiv.split("_");
                        idDiv = idDiv[0];
                        if (idDiv == "Menor o igual") {
                            document.getElementById(divOper[i].id).remove();
                            selOpe = document.getElementById("opS_"+idSelOpt);
                            for (var i = 0; i < selOpe.length; i++) {
                                var opt = selOpe[i].innerHTML;
                                if (opt == idDiv) {
                                    selOpe[i].disabled = false;
                                };
                            };
                        };
                    }
                }catch(Exception){};
                break;
            case 'Mayor a':
                divbotonAdd.appendChild(buttonDel);
                lbValOp.innerHTML = text+": ";
                divOptOpe.appendChild(lbValOp);
                divOptOpe.appendChild(inValOp);
                divOptOpe.appendChild(divbotonAdd);
                divPadre.appendChild(divOptOpe);

                try{
                    for (i=0;divOper.length;i++){
                        var idDiv = divOper[i].id;
                        idDiv = idDiv.split("_");
                        idDiv = idDiv[0];
                        if (idDiv == "Mayor o igual") {
                            document.getElementById(divOper[i].id).remove();
                            selOpe = document.getElementById("opS_"+idSelOpt);
                            for (var i = 0; i < selOpe.length; i++) {
                                var opt = selOpe[i].innerHTML;
                                if (opt == idDiv) {
                                    selOpe[i].disabled = false;
                                };
                            };
                        };
                    }
                }catch(Exception){};
                break;
            case "Menor o igual":
                divbotonAdd.appendChild(buttonDel);
                lbValOp.innerHTML = text+": ";
                divOptOpe.appendChild(lbValOp);
                divOptOpe.appendChild(inValOp);
                divOptOpe.appendChild(divbotonAdd);
                divPadre.appendChild(divOptOpe);

                try{
                    for (i=0;divOper.length;i++){
                        var idDiv = divOper[i].id;
                        idDiv = idDiv.split("_");
                        idDiv = idDiv[0];
                        if (idDiv == "Menor a") {
                            document.getElementById(divOper[i].id).remove();
                            selOpe = document.getElementById("opS_"+idSelOpt);
                            for (var i = 0; i < selOpe.length; i++) {
                                var opt = selOpe[i].innerHTML;
                                if (opt == idDiv) {
                                    selOpe[i].disabled = false;
                                };
                            };
                        };
                    }
                }catch(Exception){};
                break;
            case "Mayor o igual":
                divbotonAdd.appendChild(buttonDel);
                lbValOp.innerHTML = text+": ";
                divOptOpe.appendChild(lbValOp);
                divOptOpe.appendChild(inValOp);
                divOptOpe.appendChild(divbotonAdd);
                divPadre.appendChild(divOptOpe);

                try{
                    for (i=0;divOper.length;i++){
                        var idDiv = divOper[i].id;
                        idDiv = idDiv.split("_");
                        idDiv = idDiv[0];
                        if (idDiv == "Mayor a") {
                            document.getElementById(divOper[i].id).remove();
                            selOpe = document.getElementById("opS_"+idSelOpt);
                            for (var i = 0; i < selOpe.length; i++) {
                                var opt = selOpe[i].innerHTML;
                                if (opt == idDiv) {
                                    selOpe[i].disabled = false;
                                };
                            };
                        };
                    }
                }catch(Exception){};
                break;
        }
        
        //Elimina el filtro al cual se le dio click y activa el filtro en el combo
        $(divbotonAdd).bind("click", function() {
            var idTmp2 = $(this).context.id;
            //console.log(idTmp2);
            idTmp3 = idTmp2.split("_");
            idTmp3 = idTmp3[0];
            selOpe = document.getElementById("opS_"+idSelOpt);
            //console.log(selOpe);
            for (var i = 0; i < selOpe.length; i++) {
                var opt = selOpe[i].innerHTML;
                if (opt == idTmp3) {
                    selOpe[i].disabled = false;
                };
            };
            divRemove = divbotonAdd.parentNode;
            divRemove.remove();
        });
    };
    counter++;
}


/*$(document).ready(function()
                {
                $("#BAddFilter").click(function () {
                    var divFil = $("#SelFilter").val();
                    $('#'+divFil).show();

                    switch(divFil) {
                        case 'DivFecEnc':
                            $('#since').removeAttr('disabled');
                            $('#until').removeAttr('disabled');
                        break;
                        case 'DivGender':
                            $('#gender').removeAttr('disabled');
                        break;
                        case 'DivEncuestador':
                            $('#interFilter').removeAttr('disabled');
                        break;
                        case 'DivMedicine':
                            $('#medicineFilter').removeAttr('disabled');
                        break;
                        case 'DivDespla':
                            $('#desplazadoFilter').removeAttr('disabled');
                        break;
                        case 'DivMinus':
                            $('#minusvalidoFilter').removeAttr('disabled');
                        break;
                        case 'DivVivienda':
                            $('#viviendaFilter').removeAttr('disabled');
                        break;
                        case 'DivAgua':
                            $('#aguaFilter').removeAttr('disabled');
                        break;
                        case 'DivAlca':
                            $('#alcantarilladoFilter').removeAttr('disabled');
                        break;
                        case 'divTownFil':
                            $('#town').removeAttr('disabled');
                        break;
                        case 'divAge':
                            $('#ageSince').removeAttr('disabled');
                            $('#ageUntil').removeAttr('disabled');
                        break;
                        case 'divDisease':
                            $('#diseaseFilter').removeAttr('disabled');
                        break;
                        case 'divEtnia':
                            $('#etniaFilter').removeAttr('disabled');
                        break;
                        case 'divEducativo':
                            $('#estudiosFilter').removeAttr('disabled');
                        break;
                        case 'divOdont':
                            $('#odontologiaFilter').removeAttr('disabled');
                        break;
                        case 'divGasNat':
                            $('#gasNatFilter').removeAttr('disabled');
                        break;
                        case 'divElec':
                            $('#eneElecFilter').removeAttr('disabled');
                        break;
                        
                    }    
                });

                $("#hideFecEncu").click(function () {
                    $('#DivFecEnc').hide();
                    $('#since').prop("disabled","disabled");
                    $('#until').prop("disabled","disabled");
                });
                $("#hideGender").click(function () {
                    $('#DivGender').hide();
                    $('#gender').prop("disabled","disabled");
                });
                $("#hideEncues").click(function () {
                    $('#DivEncuestador').hide();
                    $('#interFilter').prop("disabled","disabled");
                });
                $("#hideMedicine").click(function () {
                    $('#DivMedicine').hide();
                    $('#medicineFilter').prop("disabled","disabled");
                });
                $("#hideDes").click(function () {
                    $('#DivDespla').hide();
                    $('#desplazadoFilter').prop("disabled","disabled");
                });
                $("#hideMinus").click(function () {
                    $('#DivMinus').hide();
                    $('#minusvalidoFilter').prop("disabled","disabled");
                });
                $("#hideVivi").click(function () {
                    $('#DivVivienda').hide();
                    $('#viviendaFilter').prop("disabled","disabled");
                });
                $("#hideAgua").click(function () {
                    $('#DivAgua').hide();
                    $('#aguaFilter').prop("disabled","disabled");
                });
                $("#hideAlcan").click(function () {
                    $('#DivAlca').hide();
                    $('#alcantarilladoFilter').prop("disabled","disabled");
                });
                $("#hideTown").click(function () {
                    $('#divTownFil').hide();
                    $('#town').prop("disabled","disabled");
                });
                $("#hideAge").click(function () {
                    $('#divAge').hide();
                    $('#ageSince').prop("disabled","disabled");
                    $('#ageUntil').prop("disabled","disabled");
                });
                $("#hideDisease").click(function () {
                    $('#divDisease').hide();
                    $('#diseaseFilter').prop("disabled","disabled");
                });
                $("#hideEtnia").click(function () {
                    $('#divEtnia').hide();
                    $('#etniaFilter').prop("disabled","disabled");
                });
                $("#hideEducativo").click(function () {
                    $('#divEducativo').hide();
                    $('#estudiosFilter').prop("disabled","disabled");
                });
                $("#hideOdon").click(function () {
                    $('#divOdont').hide();
                    $('#odontologiaFilter').prop("disabled","disabled");
                });
                $("#hideGasNat").click(function () {
                    $('#divGasNat').hide();
                    $('#gasNatFilter').prop("disabled","disabled");
                });
                $("#hideElec").click(function () {
                    $('#divElec').hide();
                    $('#eneElecFilter').prop("disabled","disabled");
                });
            });*/


