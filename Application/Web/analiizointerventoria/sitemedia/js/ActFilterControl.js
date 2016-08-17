function enviar(inputString) {
    //alert(inputString);
    for (var i = 0; i < 200; i++) {
        document.getElementById('D' + i).value = inputString;
        document.getElementById('M' + i).value = inputString;
    }
}
$(document).ready(function()
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
            });


