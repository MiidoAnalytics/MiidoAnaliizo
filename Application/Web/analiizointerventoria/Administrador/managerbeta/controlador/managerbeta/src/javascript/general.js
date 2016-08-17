var socket = io.connect('http://ec2-52-27-125-67.us-west-2.compute.amazonaws.com:3000');
//var socket = io.connect('http://localhost:3000');
var gCount = 0;
var groups = JSON.parse('{"fields_structure":[],"options":[],"forms":[],"forms_order":[],"handler_event":[],"HandlerFieldJoiner":[],"fieldsJoiner":[],"AditionalFieldsRules":[],"dynamicJoiner":[],"Document_info":{"projectId":"","structureName":"","structureDes":"","clientId":"2","structureVersion":1,"minVersionName":"2.24","currentAppVersion":"2.24","structureStatus":0}}');
//var groups = JSON.parse('{"fields_structure":[{"Id":"1","Name":"grupoArea","Label":"Area","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"2","Name":"grupoPoblacion","Label":"Población","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"3","Name":"grupoApellido","Label":"Apellido del núcleo familiar","Type":"tf","Required":"true","Hint":"Apellido del núcleo familiar","Rules":"vch","Length":"60","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"4","Name":"grupoDireccion","Label":"Dirección","Type":"tf","Required":"true","Hint":"Dirección","Rules":"vch","Length":"150","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"5","Name":"grupoTelefono","Label":"Teléfono","Type":"tf","Required":"true","Hint":"Teléfono","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"6","Name":"grupoGruposHaVi","Label":"¿Cuantos grupos familiares comparten esta vivienda?","Type":"tf","Required":"true","Hint":"Grupos familiares","Rules":"int","Length":"1","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"7","Name":"grupoPersonasHaVi","Label":"¿Cuantas personas habitan ésta vivienda?","Type":"tf","Required":"true","Hint":"Personas que la habitan","Rules":"int","Length":"2","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"8","Name":"grupoPersonasPerEnt","Label":"¿Cuantas de estas personas pertenecen a la Empresa?","Type":"tf","Required":"true","Hint":"Personas de empresa","Rules":"int","Length":"2","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"2","Form":"0"},{"Id":"9","Name":"personaTIdentificacion","Label":"Tipo de identificación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"10","Name":"personaDIdentificacion","Label":"Documento de identificación","Type":"tf","Required":"true","Hint":"Documento de identificación","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"11","Name":"personaPNombre","Label":"Primer nombre","Type":"tf","Required":"true","Hint":"Primer nombre","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"12","Name":"personaSNombre","Label":"Segundo nombre","Type":"tf","Required":"false","Hint":"Segundo nombre","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"13","Name":"personaPApellido","Label":"Primer apellido","Type":"tf","Required":"true","Hint":"Primer apellido","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"14","Name":"personaSApeliido","Label":"Segundo apellido","Type":"tf","Required":"false","Hint":"Segundo apellido","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"15","Name":"personaFNacimiento","Label":"Fecha de nacimiento","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"16","Name":"personaEdad","Label":"Edad","Type":"tf","Required":"true","Hint":"Edad","Rules":"int","Length":"3","Parent":"0","Order":"0","ReadOnly":"true","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"17","Name":"personaTelefono","Label":"Celular o teléfono","Type":"tf","Required":"false","Hint":"Celular o teléfono","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"18","Name":"personaGenero","Label":"Genero","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"19","Name":"personaParentesco","Label":"Parentesco","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"20","Name":"personaRSaludo","Label":"Régimen de salud","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"21","Name":"personaTAfiliacion","Label":"Tipo de afiliación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"22","Name":"personaRaza","Label":"Raza","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"23","Name":"personaEps","Label":"EPS","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"24","Name":"personaNCarnet","Label":"Número de carnet","Type":"tf","Required":"true","Hint":"Número de carnet","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"25","Name":"Beneficios","Label":"Beneficios","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"1"},{"Id":"26","Name":"beneFamAccion","Label":"Familias en accion","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"27","Name":"benefPlanMunAlimentos","Label":"Plan mundial de alimentos","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"28","Name":"benefCeroSiem","Label":"De cero a siempre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"29","Name":"bebefBecaEstud","Label":"Becas estudiantiles","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"30","Name":"benefCredIcetex","Label":"Créditos ICETEX","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"31","Name":"benefOtros","Label":"Otros","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"32","Name":"personaOcupacion","Label":"Ocupación","Type":"ac","Required":"true","Hint":"Ocupación","Rules":"vch","Length":"900","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"ciuo","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"33","Name":"personaDesplazado","Label":"Desplazado","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"34","Name":"personaDiscapacitado","Label":"Discapacitado","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"1"},{"Id":"35","Name":"discapacitadoEOrto","Label":"¿Requiere uso de algún elemento ortopédico?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"3"},{"Id":"36","Name":"eleOrtoProtesis","Label":"Prótesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"37","Name":"eleOrtoOrtesis","Label":"Órtesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"38","Name":"eleOrtoSillRuedas","Label":"Silla de ruedas","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"39","Name":"eleOrtoCaminador","Label":"Caminador","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"40","Name":"eleOrtoMuleta","Label":"Muleta(s)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"41","Name":"eleOrtoOtro","Label":"Otro elemento ortopédico","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"42","Name":"personaNEstudios","Label":"Nivel de estudios","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"43","Name":"hogarConsuAlcohol","Label":"¿Hay consumo de alcohol?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"5"}],"options":[{"Options":["-","Rural","Urbana"],"Id":"0","Field":["1"]},{"Options":["-","Barrio","Caserío","Corregimiento","Vereda"],"Id":"1","Field":["2"]},{"Options":["-","Menor sin identificación","Adulto sin identificación","Registro civil","Tarjeta de identidad","Cédula de ciudadnía","Cédula de extranjería","Pasaporte"],"Id":"2","Field":["9"]},{"Options":["-","Femenino","Masculino"],"Id":"3","Field":["18"]},{"Options":["-","Jefe de familia","Cónyuge","Hijo (a)","Otro pariente","Otro no pariente"],"Id":"4","Field":["19"]},{"Options":["-","Ninguno","Contributivo","Subsidiado"],"Id":"5","Field":["20"]},{"Options":["-","Beneficiario","Cotizante","Sin adiliación"],"Id":"6","Field":["21"]},{"Options":["-","Blanca","Afrocolombiana","Indígena","Mestiza","ROM (Gitanos)","Raizal"],"Id":"7","Field":["22"]},{"Options":["-","Empresa actual","Otra"],"Id":"8","Field":["23"]},{"Options":["-","Si","No"],"Id":"9","Field":["25","33","34","35","43"]},{"Options":["-","Ninguno","Primaria","Primaria incompleta","Secundaria o Bachillerato","Secundaria incompleta","Técnica o tecnológica","Técnica o tecnológica incompleta","Superior o universitaria","Postgrado"],"Id":"10","Field":["42"]}],"forms":[{"Id":"0","Parent":"0","Clonable":"0","Inside":"0","Handler":"0","Name":"infoGrupo","Header":""},{"Id":"1","Parent":"0","Clonable":"0","Inside":"0","Handler":"0","Name":"infoPersonal","Header":"Información Personal"},{"Id":"2","Parent":"25","Clonable":"0","Inside":"0","Handler":"0","Name":"beneficios","Header":""},{"Id":"3","Parent":"34","Clonable":"0","Inside":"0","Handler":"0","Name":"discapacitado","Header":""},{"Id":"4","Parent":"35","Clonable":"0","Inside":"0","Handler":"0","Name":"elementoOrtopedico","Header":""},{"Id":"5","Parent":"8","Clonable":"0","Inside":"1","Handler":"0","Name":"infoHogar","Header":"Información de la vivienda"}],"forms_order":[],"handler_event":[{"Types":["="],"Parameters":["Si"],"Id":"1"},{"Id":"2","Types":["!="],"Parameters":["4d186321c1a7f0f354b297e8914ab240"],"Protected":true}],"HandlerFieldJoiner":[],"fieldsJoiner":[],"AditionalFieldsRules":[],"dynamicJoiner":[],"Document_info":{"structureVersion":1,"minVersionName":"2.24","currentAppVersion":"2.24","structureStatus":0}}');
var editName = "";
var isEddit = false;
var selected = JSON.parse("{}");
var started_f = false;
var event_c = JSON.parse("{}");
var option_c = JSON.parse("{}");
var proce_c = JSON.parse("{}");
var tmpField = undefined;
var importDefault = true;
var catchingCounter = 0;

$(document).ready(function(){
	write(action);
	contMenu();
});

function dw(parameter) {
	document.write(capitalizeFirstLetter(parameter));
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function write(parameter) {
	document.title = _PAGE_NAME;
	$( "#alert_dialog_bk" ).animate({
	    opacity: 0
	},0);
	$( "#alert_dialog_fs" ).animate({
	    opacity: 0
	},0);
	$("#bt_alert_dialog_accept").val(capitalizeFirstLetter(_ACEPTAR));
	$("#bt_alert_dialog_cancel").val(capitalizeFirstLetter(_CANCELAR));
	$("#bt_alert_dialog_cancel").unbind();
	$("#bt_alert_dialog_cancel").bind("click", function() {
		$( "#alert_dialog_bk" ).animate({
		    opacity: 0
		},500, function(){$( "#alert_dialog_bk" ).css("display", "none");});
		$( "#alert_dialog_fs" ).animate({
		    opacity: 0
		},500, function() {
			$( "#alert_dialog_fs" ).css("display", "none");
		});
	});

	if ((importDefault) && (sessionStorage.importDefault)) {
		//importDefault = ((sessionStorage.importDefault == "false") ? false : true);
		//console.log(importDefault);
	}
	try {
		if(importDefault) {
			groups = JSON.parse(sessionStorage.extras);
			createUnload();
			importDefault = false;
		}
	} catch(Exception) {
		delete(sessionStorage.editionIndex);
		delete(sessionStorage.allPollData);
		if((importDefault) && (parameter == 3)) {
			importDefault = false;
			noPoll = true;
			if(sessionStorage.tmpStructure) {
				var storedStructure = sessionStorage.tmpStructure;
				if(storedStructure.length > 300) {
					if(confirm(inf_dec_imp_ult_str)) {
						groups = JSON.parse(storedStructure);
						noPoll = false;
					}
					else noPoll = true;
				}
			}
			if (noPoll) {
				if(confirm(inf_imp_def_pla))
					groups = JSON.parse('{"fields_structure":[{"Id":"1","Name":"grupoArea","Label":"Area","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"2","Name":"grupoPoblacion","Label":"Población","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"3","Name":"grupoApellido","Label":"Apellido del núcleo familiar","Type":"tf","Required":"true","Hint":"Apellido del núcleo familiar","Rules":"vch","Length":"60","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"4","Name":"grupoDireccion","Label":"Dirección","Type":"tf","Required":"true","Hint":"Dirección","Rules":"vch","Length":"150","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"5","Name":"grupoTelefono","Label":"Teléfono","Type":"tf","Required":"true","Hint":"Teléfono","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"6","Name":"grupoGruposHaVi","Label":"¿Cuantos grupos familiares comparten esta vivienda?","Type":"tf","Required":"true","Hint":"Grupos familiares","Rules":"int","Length":"1","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"7","Name":"grupoPersonasHaVi","Label":"¿Cuantas personas habitan ésta vivienda?","Type":"tf","Required":"true","Hint":"Personas que la habitan","Rules":"int","Length":"2","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"0"},{"Id":"8","Name":"grupoPersonasPerEnt","Label":"¿Cuantas de estas personas pertenecen a la Empresa?","Type":"tf","Required":"true","Hint":"Personas de empresa","Rules":"int","Length":"2","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"2","Form":"0"},{"Id":"9","Name":"personaTIdentificacion","Label":"Tipo de identificación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"10","Name":"personaDIdentificacion","Label":"Documento de identificación","Type":"tf","Required":"true","Hint":"Documento de identificación","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"11","Name":"personaPNombre","Label":"Primer nombre","Type":"tf","Required":"true","Hint":"Primer nombre","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"12","Name":"personaSNombre","Label":"Segundo nombre","Type":"tf","Required":"false","Hint":"Segundo nombre","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"13","Name":"personaPApellido","Label":"Primer apellido","Type":"tf","Required":"true","Hint":"Primer apellido","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"14","Name":"personaSApeliido","Label":"Segundo apellido","Type":"tf","Required":"false","Hint":"Segundo apellido","Rules":"vch","Length":"25","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"15","Name":"personaFNacimiento","Label":"Fecha de nacimiento","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"16","Name":"personaEdad","Label":"Edad","Type":"tf","Required":"true","Hint":"Edad","Rules":"int","Length":"3","Parent":"0","Order":"0","ReadOnly":"true","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"17","Name":"personaTelefono","Label":"Celular o teléfono","Type":"tf","Required":"false","Hint":"Celular o teléfono","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"18","Name":"personaGenero","Label":"Genero","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"19","Name":"personaParentesco","Label":"Parentesco","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"20","Name":"personaRSaludo","Label":"Régimen de salud","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"21","Name":"personaTAfiliacion","Label":"Tipo de afiliación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"22","Name":"personaRaza","Label":"Raza","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"23","Name":"personaEps","Label":"EPS","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"24","Name":"personaNCarnet","Label":"Número de carnet","Type":"tf","Required":"true","Hint":"Número de carnet","Rules":"int","Length":"10","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"25","Name":"Beneficios","Label":"Beneficios","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"1"},{"Id":"26","Name":"beneFamAccion","Label":"Familias en accion","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"27","Name":"benefPlanMunAlimentos","Label":"Plan mundial de alimentos","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"28","Name":"benefCeroSiem","Label":"De cero a siempre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"29","Name":"bebefBecaEstud","Label":"Becas estudiantiles","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"30","Name":"benefCredIcetex","Label":"Créditos ICETEX","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"31","Name":"benefOtros","Label":"Otros","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"2"},{"Id":"32","Name":"personaOcupacion","Label":"Ocupación","Type":"ac","Required":"true","Hint":"Ocupación","Rules":"vch","Length":"900","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"ciuo","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"33","Name":"personaDesplazado","Label":"Desplazado","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"34","Name":"personaDiscapacitado","Label":"Discapacitado","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"1"},{"Id":"35","Name":"discapacitadoEOrto","Label":"¿Requiere uso de algún elemento ortopédico?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"1","Form":"3"},{"Id":"36","Name":"eleOrtoProtesis","Label":"Prótesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"37","Name":"eleOrtoOrtesis","Label":"Órtesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"38","Name":"eleOrtoSillRuedas","Label":"Silla de ruedas","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"39","Name":"eleOrtoCaminador","Label":"Caminador","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"40","Name":"eleOrtoMuleta","Label":"Muleta(s)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"41","Name":"eleOrtoOtro","Label":"Otro elemento ortopédico","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"4"},{"Id":"42","Name":"personaNEstudios","Label":"Nivel de estudios","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"1"},{"Id":"43","Name":"hogarConsuAlcohol","Label":"¿Hay consumo de alcohol?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Parent":"0","Order":"0","ReadOnly":"false","AutoComplete":"0","FreeAdd":"0","Handler":"0","Form":"5"}],"options":[{"Options":["-","Rural","Urbana"],"Id":"0","Field":["1"]},{"Options":["-","Barrio","Caserío","Corregimiento","Vereda"],"Id":"1","Field":["2"]},{"Options":["-","Menor sin identificación","Adulto sin identificación","Registro civil","Tarjeta de identidad","Cédula de ciudadnía","Cédula de extranjería","Pasaporte"],"Id":"2","Field":["9"]},{"Options":["-","Femenino","Masculino"],"Id":"3","Field":["18"]},{"Options":["-","Jefe de familia","Cónyuge","Hijo (a)","Otro pariente","Otro no pariente"],"Id":"4","Field":["19"]},{"Options":["-","Ninguno","Contributivo","Subsidiado"],"Id":"5","Field":["20"]},{"Options":["-","Beneficiario","Cotizante","Sin adiliación"],"Id":"6","Field":["21"]},{"Options":["-","Blanca","Afrocolombiana","Indígena","Mestiza","ROM (Gitanos)","Raizal"],"Id":"7","Field":["22"]},{"Options":["-","Empresa actual","Otra"],"Id":"8","Field":["23"]},{"Options":["-","Si","No"],"Id":"9","Field":["25","33","34","35","43"]},{"Options":["-","Ninguno","Primaria","Primaria incompleta","Secundaria o Bachillerato","Secundaria incompleta","Técnica o tecnológica","Técnica o tecnológica incompleta","Superior o universitaria","Postgrado"],"Id":"10","Field":["42"]}],"forms":[{"Id":"0","Parent":"0","Clonable":"0","Inside":"0","Handler":"0","Name":"infoGrupo","Header":""},{"Id":"1","Parent":"0","Clonable":"0","Inside":"0","Handler":"0","Name":"infoPersonal","Header":"Información Personal"},{"Id":"2","Parent":"25","Clonable":"0","Inside":"0","Handler":"0","Name":"beneficios","Header":""},{"Id":"3","Parent":"34","Clonable":"0","Inside":"0","Handler":"0","Name":"discapacitado","Header":""},{"Id":"4","Parent":"35","Clonable":"0","Inside":"0","Handler":"0","Name":"elementoOrtopedico","Header":""},{"Id":"5","Parent":"8","Clonable":"0","Inside":"1","Handler":"0","Name":"infoHogar","Header":"Información de la vivienda"}],"forms_order":[],"handler_event":[{"Types":["="],"Parameters":["Si"],"Id":"1"},{"Id":"2","Types":["!="],"Parameters":["4d186321c1a7f0f354b297e8914ab240"],"Protected":true}],"HandlerFieldJoiner":[],"fieldsJoiner":[],"AditionalFieldsRules":[],"dynamicJoiner":[],"Document_info":{"structureVersion":1,"minVersionName":"2.24","currentAppVersion":"2.24","structureStatus":0}}');
				else
					sessionStorage.importDefault = false;
			}
			sessionStorage.tmpStructure = JSON.stringify(groups);
			sessionStorage.spStructure = JSON.stringify(groups);
		}
	}
	
	if((action > 1) && (action < 10)) {
		sessionStorage.tmpStructure = JSON.stringify(groups);
	}

	switch(parameter) {
		case 0:
			location='../../../../Administrador/proyectos/controlador/proyectos.php';
			/*$("#p_notice").fadeToggle(0);
			$("#i_loading").fadeToggle(0);
			$("#bt_inic").attr('value', capitalizeFirstLetter(_ACEPTAR));
			$("#f_login").attr('onsubmit', 'return prepareService(".login")');
			$("#it_usuario").attr('placeholder', _NOMBRE_USUARIO);
			$("#ip_contrasena").attr('placeholder', _CONTRASENA_USUARIO);*/
		break;
		case 1:
			//MAIN ACTIONS
			/*$('#l_poll_creator').unbind();
			$('#l_poll_finder').unbind();
			$('#l_poll_editor').unbind();
			$('#l_sesion_closer').unbind();
			$('#l_poll_creator').bind("click", function(){
				document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request" value="creator" /> </form>';
				document.getElementById("f_requester").submit();
			});
			$('#l_poll_finder').bind("click", function(){
				document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request" value="creator" /> </form>';
				document.getElementById("f_requester").submit();
			});
			$('#l_poll_editor').bind("click", function(){
				document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request." value="creator" /> </form>';
				document.getElementById("f_requester").submit();
			});
			$('#l_sesion_closer').bind("click", function(){
				prepareService('.logout');
			});*/
		break;
		case 2:
			selected.group = undefined;
			selected.field = undefined;
			$("#bt_save_group").attr('value', capitalizeFirstLetter(_GUARDAR));
			$("#f_login").attr('onsubmit', 'return false;');
			$("#bt_save_group").unbind();
			$("#bt_save_group").bind("click", function(){
				if ($("#it_nGrupo").val() != "") {
					var tableTmp = document.getElementById("left_resumer_table_f");
					var idTmp;
					if(!isEddit) {
						try {
							idTmp = (parseInt(groups.forms[groups.forms.length-1].Id) + 1)+"";
							editName = groups.forms.length;
						} catch(Exception) {
							idTmp = "1";
							editName = 0;
						}
						groups.forms[editName] = JSON.parse("{}");
						if(!isEddit) {
							groups.forms[editName].Id = idTmp;
							groups.forms[editName].Parent = "0";
							groups.forms[editName].Clonable = "0";
							groups.forms[editName].Inside = "0";
							groups.forms[editName].Handler = "0";
						}
						gCount++;
					}
					groups.forms[editName].Name = $("#it_nGrupo").val();
					if (($("#it_tGrupo").val() != "")) {
						groups.forms[editName].Header = $("#it_tGrupo").val();
					} else {
						try {
							groups.forms[editName].Header = "";
						} catch(Exception) {}
					}
					selected.group = undefined;
					selected.field = undefined;
					editName = undefined;
					isEddit = false;
					fillGroups("left_resumer_table_g", 0);
					$("#it_nGrupo").val("");
					$("#it_tGrupo").val("");
					return false;
				}
			});
			fillGroups("left_resumer_table_g", 0);
		break;
		case 3:
			if(selected.group != undefined) {
				selected.group = undefined;
				selected.field = undefined;
			}
			fillGroups("left_resumer_table_f", 1);
			$("#it_nGrupo").val("");
			$("#it_tGrupo").val("");
			isEddit = false;
			//createActionsForField();
		break;
		case 4:
			$("#bt_append_handler").val(capitalizeFirstLetter(_AGREGAR));
			selected.group = undefined;
			selected.field = undefined;
			selected.group2 = undefined;
			selected.event = undefined;
			$( "#events_creator_editor" ).animate({
			    opacity: 0,
			    left: "+=250"
			  }, 0, function(){
			  	document.getElementById("events_creator_editor").style.display = "none";
			  	document.getElementById("events_selector_actions").style.display = "block";
			  	$( "#events_selector_actions" ).animate({
				    opacity: 0,
				    left: "-=250"
				},0, function() {
				  	$( "#events_selector_actions" ).animate({
					    opacity: 1,
					    left: "+=250"
					  }, 0, function() {
					  	$( "#events_creator_editor" ).animate({
						    opacity: 0,
						    left: "-=250"
						  }, 0);
					  });
				});
			});
			fillGroups("left_listener_table_f", 2);
			$("#it_nGrupo").val("");
			$("#it_tGrupo").val("");
			isEddit = false;
			createActionsForField();
		break;
		case 5:
			document.getElementById("l_maker").innerHTML = capitalizeFirstLetter(_ESTABLECER+" "+_ENCUESTA);
			$("#makeGeneralBlok").val(capitalizeFirstLetter(_GENERAL));
			$("#makeSubBlock").val(capitalizeFirstLetter(_SECUNDARIA));
			$("#closePoll").val(capitalizeFirstLetter(_CERRAR));
			var dvTmp = document.getElementById("d_left_mapper");
			dvTmp.innerHTML = "";
			dvTmp.innerHTML = (fillPoll());
			document.getElementById("d_left_alert_fs").innerHTML = "";
			selected.group = undefined;
			//Asigna un evento para mostrar el secundario
			findGeneralAndSecondary();
			$("#closePoll").removeAttr("disabled");
			$("#makeGeneralBlok").unbind();
			if(selected.general == undefined) {
				$("#makeGeneralBlok").bind("click", function() {
					selected.content = document.getElementById("r_alert_dialog_content").innerHTML;
					document.getElementById("r_alert_dialog_content").innerHTML = "";
					$( "#alert_dialog_bk" ).css("display", "block");
					$( "#alert_dialog_fs" ).css("display", "block");
					$( "#alert_dialog_bk" ).animate({
					    opacity: 0.5
					},500);
					$( "#alert_dialog_fs" ).animate({
					    opacity: 1
					},500, function() {
						var tbTmp = document.createElement("TABLE");
						
						tbTmp.id = "lContentTable";
						tbTmp.className = "d_left_resumer_table";
						
						document.getElementById("d_left_alert_fs").appendChild(tbTmp);

						fillGroups("lContentTable", 4);
					});
				});
			} else {

				$("#makeGeneralBlok").attr("disabled", "disabled");
				$("#makeGeneralBlok").attr("title", inf_est_gen_gru.replace($_GRUPO, groups.forms[selected.general].Header));
			}
			$("#makeSubBlock").unbind();
			if(selected.secondary == undefined) {
				$("#makeSubBlock").bind("click", function() {
					selected.content = document.getElementById("r_alert_dialog_content").innerHTML;
					document.getElementById("r_alert_dialog_content").innerHTML = "";
					$( "#alert_dialog_bk" ).css("display", "block");
					$( "#alert_dialog_fs" ).css("display", "block");
					$( "#alert_dialog_bk" ).animate({
					    opacity: 0.5
					},500);
					$( "#alert_dialog_fs" ).animate({
					    opacity: 1
					},500, function() {
						var tbTmp = document.createElement("TABLE");
						
						tbTmp.id = "lContentTable";
						tbTmp.className = "d_left_resumer_table";

						document.getElementById("d_left_alert_fs").appendChild(tbTmp);

						fillGroups("lContentTable", 5);
					});
				});
			} else {
				$("#makeSubBlock").attr("disabled", "disabled");
				$("#makeSubBlock").attr("title", inf_est_gen_gru.replace($_GRUPO, groups.forms[selected.general].Header));
			}
			$("#closePoll").unbind();
			if((selected.general != undefined) /*&& (selected.secondary != undefined)*/) {
				$("#closePoll").bind("click", function() {
					socket.emit('pollIncoming', (JSON.stringify(groups)));
					delete(sessionStorage.tmpStructure);
            		prepareService('.pollSaver');
            	});
			} else {
				$("#closePoll").attr("disabled", "disabled");
			}
		break;
		case 6:
			//servicio que busca las encuestas creadas
			prepareService(".pollFinder");
		break;
		case 7:
			
		break;
		case 8:
			//servicio que busca los proyectos activos
			prepareService(".projectFinder");
			//se asigna el valor guardar al botón
			$("#bt_save_pollname").attr('value', capitalizeFirstLetter(_GUARDAR));
			//se verifica si se modifica o se crea nueva encuesta
			if(sessionStorage.editionName != ""){
				//si se modifica asigna a los campos nombre y descripción de la encuesta
				$("#it_nPoll").val(groups.Document_info['structureName']);
				$("#it_desPoll").val(groups.Document_info['structureDes']);
			}
			//se llama a la función que agrega el evento click para el botón guardar
			savePollName();
		break;
	}
	try {
		$('#l_poll_creator').unbind();
		$('#l_poll_defaults').unbind();
		$('#l_poll_editor').unbind();
		$('#report_creator').unbind();

		$('#l_poll_creator').bind("click", function(){
			document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request" value="creator" /> </form>';
			document.getElementById("f_requester").submit();
			sessionStorage.editionName = "";
		});
		$('#l_poll_defaults').bind("click", function(){
			document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request." value="creator" /> </form>';
			document.getElementById("f_requester").submit();
		});
		$('#l_poll_editor').bind("click", function(){
			document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request" value="editor" /> </form>';
			document.getElementById("f_requester").submit();
		});
		
		/*$('#l_sesion_closer').bind("click", function(){
			action = 1;
			prepareService('.logout');
		});*/

		$('#l_menu_return').bind("click", function(){
			//window.location="http://52.27.125.67/analiizointerventoria/Informedeavances/informeAvances/controlador/informeAvances.php";
			location='../../../../Administrador/proyectos/controlador/proyectos.php';
		});
	} catch(error) {}
	try {
		$("#r_groups").unbind();
		$("#r_fields").unbind();
		$("#r_listener").unbind();
		$("#r_finish").unbind();
		$("#r_pollname").unbind();

		$("#r_pollname").bind("click", function() {
			/*action = 8;
			write(action);*/
		});

		$("#r_groups").bind("click", function() {
			action = 2;
			write(action);
		});
		$("#r_fields").bind("click", function() {
			action = 3;
			write(action);
		});
		$("#r_listener").bind("click", function() {
			action = 4;
			write(action);
		});
		$("#r_finish").bind("click", function() {
			action = 5;
			write(action);
		});

	} catch(error) {}
}
//Se agrega el evento click al botón que guarda los datos de nombre, descripción y proyecto
function savePollName(){
	$("#bt_save_pollname").attr('value', capitalizeFirstLetter(_GUARDAR));
	$("#bt_save_pollname").unbind();
	$("#bt_save_pollname").bind("click", function(e){
		e.preventDefault();
		if ($("#it_nProject").val() != "" && $("#it_nPoll").val() != "") {
			groups.Document_info['projectId'] = $("#it_nProject").val();
			groups.Document_info['structureName'] = $("#it_nPoll").val();
			groups.Document_info['structureDes'] = $("#it_desPoll").val();
			sessionStorage.editionName = groups.Document_info['structureName'];
			alert('Se guardo correctamente');
		}else{
			alert('Por favor complete los datos');
		}
	});
}

function fillGroups(id, activeListener) {
	var tableTmp = document.getElementById(id);
	tableTmp.innerHTML="";
	for (var index in groups.forms) {
		if((activeListener == 3) && (groups.forms[index].Parent == groups.fields_structure[selected.field].Id)){
			alert('Esta pregunta ya cuenta con una realación con el grupo: '+groups.forms[index].Name);
		} else 
		if ((selected.group != index) && (index != "events") && (index != "options") && (index != "fields_structure")) {
			var trTmp = document.createElement("TR");
			var tdTmp = document.createElement("TD");
			trTmp.appendChild(tdTmp);
			tableTmp.appendChild(trTmp);
			tdTmp.appendChild(document.createTextNode(groups.forms[index].Name));
			tdTmp.id = index;
			var index2 = index.split("_");
			try {
				groups.forms[index].tittle;
				var divTmp = document.createElement("DIV");
				divTmp.className = "d_description";
				divTmp.appendChild(document.createTextNode(groups.forms[index].Header));
				tdTmp.appendChild(divTmp);
				divTmp.id = "tittle_"+index2[1];
			} catch(JSONException){}
			//console.log(activeListener);
			switch (activeListener) {
				case 0:
					createGroupItemsAction(tdTmp);
				break;
				case 1:
					createGroupForFieldItemAction(tdTmp);
				break;
				case 2:
					document.getElementById("d_center_event_resumer_table").innerHTML = "";
					document.getElementById("right_listener_table_f").innerHTML = "";
					createGroupForEventItemAction(tdTmp);
				break;
				case 3:
					createGroupForEvent2ItemAction(tdTmp);
				break;
				case 4:
					if (groups.forms[index].Parent != "0"){
						$(trTmp).css("display", "none");
					} else {
						createGroupForGeneralAction(tdTmp);
					}
				break;
				case 5:
				console.log("grapphing");
					if ((groups.forms[index].Parent != "0") || (groups.forms[index].Id == "0")){
						console.log(tdTmp);
						$(trTmp).css("display", "none");
					} else {
						console.log(tdTmp);
						createGroupForSecondaryAction(tdTmp);
					}
				break;
			}
			gCount = parseInt(index2[1]);
		}
	}
	gCount++;
}

function fillFields(group) {
	//document.getElementById("d_field_actions").style.display = "block";
	var fields = groups.fields_structure;
	var tableTmp;
	var spanId;
	var cols;
	switch (action) {
		case 3:
			tableTmp = document.getElementById("center_resumer_table_f");
			spanId = "s_group_name";
			cols = 2;
		break;
		case 4:
			tableTmp = document.getElementById("d_center_event_resumer_table");
			spanId = "s_event_group_name";
			cols = 1;
		break;
	}
	if(group == -2){
		tableTmp = document.getElementById("left_resumer_table_field");
	}		
	tableTmp.innerHTML="";
	if(fields == undefined) {
		groups.fields_structure = JSON.parse("[]");
	}
	fields = groups.fields_structure;
	if(group > -1)
		document.getElementById(spanId).innerHTML = inf_gru_sel+": "+groups.forms[group].Name;
	if(fields.length == 0) {
		document.getElementById(spanId).innerHTML += "<br />"+inf_gru_sin_cam;
	} else {
		var trTmp;
		var cc = 0;
		if(group > -1)
			group = group.replace("grupo_", "");
		for (var index in fields) {

			if ((group <= -1) || (fields[index].Form == groups.forms[group].Id)) {
				
				if((cc % cols) == 0) {
					var trTmp = document.createElement("TR");
					tableTmp.appendChild(trTmp);
				}
				var tdTmp = document.createElement("TD");
				var divTmp = document.createElement("DIV");

				tdTmp.width = "50%";

				divTmp.className = "d_description";
				
				tdTmp.appendChild(document.createTextNode(fields[index].Id+". "+fields[index].Label));
				divTmp.appendChild(document.createTextNode(fields[index].Name));

				tdTmp.appendChild(divTmp);
				trTmp.appendChild(tdTmp);

				tdTmp.id = index;
				if(group == -1){
					//createFieldItemActionPro(tdTmp);
				} else if(group == -2){
					//createFieldItemActionPro2(tdTmp);
				}else{
					createFieldItemAction(tdTmp);
				}
				cc++;
			}
		}
	}
}

function fillFields2(group) {
	selected.group = group;
	var form = groups.forms[selected.group].Id
	var container = document.getElementById("right_creator_editor");
	container.innerHTML = "";
	var cc = 0;
	for (var index in groups.fields_structure) {
		if (groups.fields_structure[index].Form == form) {
			cc++;
			container.appendChild(createFieldExistentContainer(groups.fields_structure[index], index));
			document.getElementById(index+'_itField').focus();
		}
	}
	$(document).unbind();
	$(document).mouseup(function(e) {
	    var container = $("#"+selected.menu);
	    if (!container.is(e.target) && container.has(e.target).length === 0) {
	    	try {
		    	var idTmp = selected.menu.split("_");
				$("#"+idTmp[0]+"_up").hide();
				$("#"+idTmp[0]+"_down").hide();
				$("#"+idTmp[0]+"_delete").hide();
			} catch(Exception){}
	    }

	});
	$(container).unbind();
	$(container).scroll(function() {
    	try {
	    	var idTmp = selected.menu.split("_");
			$("#"+idTmp[0]+"_up").hide();
			$("#"+idTmp[0]+"_down").hide();
			$("#"+idTmp[0]+"_delete").hide();
		} catch(Exception){}
	});
	if(cc == 0){
		var index = group;
		var gDivTmp = document.createElement("DIV");
		var iDivTmp = document.createElement("DIV");
		var imgTmp = document.createElement("IMG");
		gDivTmp.appendChild(iDivTmp);
		iDivTmp.appendChild(imgTmp);
		iDivTmp.appendChild(document.createElement("br"));
		iDivTmp.appendChild(document.createTextNode("Agregar pregunta"));
		gDivTmp.id = index;
		iDivTmp.id = index+"_creator";
		iDivTmp.className = "d_add_img_content";
		imgTmp.className = "i_add_Button";
		imgTmp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";
		$(iDivTmp).unbind();
		$(iDivTmp).bind("click", function() {
			var index = $(this);
			var parent = index.context.parentNode;
			var container = document.getElementById("right_creator_editor");
			container.appendChild(createTypeField(index.context.id), parent);
		});
		container.appendChild(gDivTmp);
	}
}

function createFieldExistentContainer(strTmp, index) {
	var gDivTmp = document.createElement("DIV");
	var iDivTmp = document.createElement("DIV");
	var sDivTmp = document.createElement("DIV");
	var divTmp = document.createElement("DIV");
	var mDivTmp = document.createElement("DIV");
	var aDivTmp = document.createElement("DIV");
	var bDivTmp = document.createElement("DIV");
	var dDivTmp = document.createElement("DIV");
	var imgTmp = document.createElement("IMG");
	var itTmp = document.createElement("INPUT");
	var lbRTmp = document.createElement("LABEL");
	var cbRTmp = document.createElement("INPUT");
	var lbETmp = document.createElement("LABEL");
	var cbETmp = document.createElement("INPUT");

	gDivTmp.appendChild(iDivTmp);
	divTmp.appendChild(sDivTmp);
	sDivTmp.appendChild(mDivTmp);
	mDivTmp.appendChild(aDivTmp);
	mDivTmp.appendChild(bDivTmp);
	mDivTmp.appendChild(dDivTmp);
	iDivTmp.appendChild(imgTmp);
	iDivTmp.appendChild(document.createElement("br"));
	iDivTmp.appendChild(document.createTextNode("Agregar pregunta"));
	gDivTmp.appendChild(divTmp);
	divTmp.appendChild(itTmp);
	divTmp.appendChild(document.createElement("br"));
	divTmp.appendChild(lbRTmp);
	divTmp.appendChild(document.createElement("br"));
	divTmp.appendChild(lbETmp);
	
	lbRTmp.appendChild(cbRTmp);
	lbETmp.appendChild(cbETmp);

	gDivTmp.id = index;
	iDivTmp.id = index+"_creator";
	sDivTmp.id = index+"_menu";
	aDivTmp.id = index+"_up";
	bDivTmp.id = index+"_down";
	dDivTmp.id = index+"_delete";
	itTmp.id = index+"_itField";
	
	divTmp.className = "d_form_submit";
	mDivTmp.className = "img_field_settings";
	sDivTmp.className = "d_field_settings";
	aDivTmp.className = "d_any_field_option";
	bDivTmp.className = "d_any_field_option";
	dDivTmp.className = "d_any_field_option";
	iDivTmp.className = "d_add_img_content";
	imgTmp.className = "i_add_Button";
	divTmp.className = "d_fieldset_container";
	aDivTmp.innerHTML = "Mover arriba";
	bDivTmp.innerHTML = "Mover abajo";
	dDivTmp.innerHTML = "Eliminar";
	imgTmp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";
	itTmp.type = "TEXT";
	cbRTmp.type = "CHECKBOX";
	cbETmp.type = "CHECKBOX";
	cbRTmp.value = "true";
	cbETmp.value = "true";
	itTmp.value = strTmp.Label;

	lbRTmp.innerHTML += capitalizeFirstLetter(_OBLIGATORIO);
	lbETmp.innerHTML += capitalizeFirstLetter(_MODIFICABLE);
	((strTmp.Required == "true") ? $(lbRTmp).click() : null);
	((strTmp.ReadOnly == "true") ? null : $(lbETmp).click());

	switch(strTmp.Type) {
		case 'rg':
		case 'sp':
			var pTmp = document.createElement("P");
			var selTmp = document.createElement("SELECT");
			var optTmp1 = document.createElement("OPTION");
			var optTmp2 = document.createElement("OPTION");			
			
			divTmp.appendChild(pTmp);
			divTmp.appendChild(selTmp);
			optTmp1.innerHTML = "Lista";
			optTmp2.innerHTML = "Menú desplegable";
			optTmp1.value = "rg";
			optTmp2.value = "sp";
			selTmp.appendChild(optTmp1);
			selTmp.appendChild(optTmp2);
			pTmp.innerHTML = "Modo de visualización";
			selTmp.id = index+"_slSCView";
			
			switch (strTmp.Type) {
				case "rg":
					optTmp1.selected = "true";			
				break;
				case "sp":
					optTmp2.selected = "true";
				break;
			}

			/*crea las opciones*/
			var divOption = document.createElement("DIV");
			divOption.id = index+'_optionDiv';
			divTmp.appendChild(divOption);

			$(selTmp).unbind();
			$(selTmp).bind("change", function(){
				var dom = $(this);
				var position = dom.context.parentNode.parentNode.id;
				var flag = 0;
				groups.fields_structure[position].Type = dom.context.value;
				sessionStorage.tmpStructure = JSON.stringify(groups);
				var idPre = groups.fields_structure[position].Id;
				if(groups.options.length == 0){
					var indix = groups.options.length;
    				groups.options[indix] = JSON.parse("[]");
					var dom = $(this);
					dom = dom.context.id.split("_");
					dom = dom[0];
					var jsonTmp = JSON.parse("{}");
					var field = parseInt(idPre); 
					field = field.toString();

					jsonTmp.Id = indix;
					jsonTmp.Field = JSON.parse("["+field+"]");
					jsonTmp.Options = JSON.parse("[]");
					jsonTmp.Options[indix] = "-";
					groups.options[indix] = jsonTmp;
				}else{
					for(indice in groups.options){
						if(groups.options[indice].Field[0] == idPre){
							flag++;
							indix = parseInt(indice);
						}
					}
					if(flag == 0){
						var indix = groups.options.length;
	    				groups.options[indix] = JSON.parse("[]");
						var jsonTmp = JSON.parse("{}");
						var field = parseInt(idPre); 
						field = field.toString();

						jsonTmp.Id = indix;
						jsonTmp.Field = JSON.parse("["+field+"]");
						jsonTmp.Options = JSON.parse("[]");
						jsonTmp.Options[0] = "-";
						groups.options[indix] = jsonTmp;
					}else{
					}
				}
				createOptionsSp(divOption, indix, idPre, index, strTmp.Type);				
			});
			$(selTmp).change();
		break;
		case 'tf':
		case 'ac':
			var desTTmp = document.createElement("INPUT");
			var desLTmp = document.createElement("LABEL");
			divTmp.appendChild(document.createElement("BR"));
			divTmp.appendChild(document.createElement("BR"));
			desLTmp.innerHTML = capitalizeFirstLetter(_DESCRIPCION);
			divTmp.appendChild(desLTmp);
			divTmp.appendChild(document.createElement("BR"));
			divTmp.appendChild(desTTmp);
			desTTmp.type = "TEXT";
			desTTmp.value = strTmp.Hint;
			$(desTTmp).unbind();
			$(desTTmp).bind("change", function() {
				var dom = $(this);
				var position = dom.context.parentNode.parentNode.id;
				groups.fields_structure[position].Hint = dom.context.value;
				sessionStorage.tmpStructure = JSON.stringify(groups);
			});


			var typeTTmp = document.createElement("SELECT");
			var typeLTmp = document.createElement("LABEL");
			var oTypeTmp1 = document.createElement("OPTION");
			var oTypeTmp2 = document.createElement("OPTION");

			divTmp.appendChild(document.createElement("BR"));
			divTmp.appendChild(document.createElement("BR"));
			typeLTmp.innerHTML = capitalizeFirstLetter(_TIPO);
			divTmp.appendChild(typeLTmp);
			divTmp.appendChild(document.createElement("BR"));
			divTmp.appendChild(typeTTmp);
			typeTTmp.appendChild(oTypeTmp1);
			typeTTmp.appendChild(oTypeTmp2);
			oTypeTmp1.innerHTML = capitalizeFirstLetter("Texto libre");
			oTypeTmp2.innerHTML = capitalizeFirstLetter("Texto con autocompletado");
			oTypeTmp1.value = "tf";
			oTypeTmp2.value = "ac";

			switch(strTmp.Type) {
				case "tf":
					oTypeTmp1.selected = "true";
				break;
				case "ac":
					oTypeTmp2.selected = "true";
				break;
			}

			$(typeTTmp).unbind();
			$(typeTTmp).bind("change", function() {
				var dom = $(this);
				var position = dom.context.parentNode.parentNode.id;
				groups.fields_structure[position].Type = dom.context.value;
				sessionStorage.tmpStructure = JSON.stringify(groups);

				switch(dom.context.value) {
					case 'ac':
						try {
							$(position+"_slTField").unbind();
							$(position+"_tfLField").unbind();
							dom.context.parentNode.removeChild(document.getElementById(position+"_slTField"));
							dom.context.parentNode.removeChild(document.getElementById(position+"_lbTField"));
							dom.context.parentNode.removeChild(document.getElementById(position+"_tfLField"));
							dom.context.parentNode.removeChild(document.getElementById(position+"_lbLField"));
							groups.fields_structure[position].Rules = "0";
							groups.fields_structure[position].Length = "0";
							sessionStorage.tmpStructure = JSON.stringify(groups);
						} catch(Exception){
							dom.context.parentNode.appendChild(document.createElement("BR"));
							dom.context.parentNode.appendChild(document.createElement("BR"));
						}

						var tfRTable = document.createElement("INPUT");
						var lbRTable = document.createElement("LABEL");

						dom.context.parentNode.appendChild(lbRTable);
						dom.context.parentNode.appendChild(tfRTable);
						lbRTable.appendChild(document.createTextNode(capitalizeFirstLetter(_AUTOCOMPLETADO)));
						lbRTable.appendChild(document.createElement("BR"));

						tfRTable.id = position+"_tfRTable";
						lbRTable.id = position+"_lbRTable";
						tfRTable.type = "TEXT";
						tfRTable.value = groups.fields_structure[position].AutoComplete;

						$(tfRTable).unbind();
						$(tfRTable).bind("change", function() {
							var dom = $(this);
							var position = dom.context.parentNode.parentNode.id;
							groups.fields_structure[position].AutoComplete = dom.context.value;
							sessionStorage.tmpStructure = JSON.stringify(groups);
						});
					break;

					case 'tf':
						try {
							$(position+"_tfRTable").unbind();
							dom.context.parentNode.removeChild(document.getElementById(position+"_tfRTable"));
							dom.context.parentNode.removeChild(document.getElementById(position+"_lbRTable"));
							groups.fields_structure[position].AutoComplete = "0";
							sessionStorage.tmpStructure = JSON.stringify(groups);
						} catch (Exception){
							dom.context.parentNode.appendChild(document.createElement("BR"));
							dom.context.parentNode.appendChild(document.createElement("BR"));
						}
						var TftypeTTmp = document.createElement("SELECT");
						var TftypeLTmp = document.createElement("LABEL");
						var oTfTypeTmp1 = document.createElement("OPTION");
						var oTfTypeTmp2 = document.createElement("OPTION");
						var oTfTypeTmp3 = document.createElement("OPTION");
						var oTfTypeTmp4 = document.createElement("OPTION");

						TftypeLTmp.innerHTML = capitalizeFirstLetter(_TIPO+" "+_DE+" "+_TEXTO);
						dom.context.parentNode.appendChild(TftypeLTmp);
						TftypeLTmp.appendChild(document.createElement("BR"));
						dom.context.parentNode.appendChild(TftypeTTmp);
						TftypeTTmp.appendChild(oTfTypeTmp1);
						TftypeTTmp.appendChild(oTfTypeTmp2);
						TftypeTTmp.appendChild(oTfTypeTmp3);
						TftypeTTmp.appendChild(oTfTypeTmp4);
						oTfTypeTmp1.innerHTML = capitalizeFirstLetter("Texto");
						oTfTypeTmp2.innerHTML = capitalizeFirstLetter("Número");
						oTfTypeTmp3.innerHTML = capitalizeFirstLetter("Email");
						oTfTypeTmp4.innerHTML = capitalizeFirstLetter("Decimal");
						oTfTypeTmp1.value = "vch";
						oTfTypeTmp2.value = "int";
						oTfTypeTmp3.value = "eml";
						oTfTypeTmp4.value = "dec";

						TftypeTTmp.id = position+"_slTField";
						TftypeLTmp.id = position+"_lbTField";

						switch(groups.fields_structure[position].Rules) {
							case "vch":
								oTfTypeTmp1.selected = "true";
							break;
							case "int":
								oTfTypeTmp2.selected = "true";
							break;
							case "eml":
								oTfTypeTmp3.selected = "true";
							break;
							case "dec":
								oTfTypeTmp4.selected = "true";
							break;
						}

						$(TftypeTTmp).unbind();
						$(TftypeTTmp).bind("change", function() {
							var dom = $(this);
							var position = dom.context.parentNode.parentNode.id;
							groups.fields_structure[position].Rules = dom.context.value;
							sessionStorage.tmpStructure = JSON.stringify(groups);
						});

						var lonTTmp = document.createElement("INPUT");
						var lonLTmp = document.createElement("LABEL");
						
						lonLTmp.appendChild(document.createElement("BR"));
						lonLTmp.appendChild(document.createElement("BR"));
						lonLTmp.innerHTML += capitalizeFirstLetter(_LONGITUD+" "+_DE+" "+_TEXTO);
						dom.context.parentNode.appendChild(lonLTmp);
						lonLTmp.appendChild(document.createElement("BR"));
						dom.context.parentNode.appendChild(lonTTmp);

						lonTTmp.id = position+"_tfLField";
						lonLTmp.id = position+"_lbLField";
						lonTTmp.type = "NUMBER";
						lonTTmp.value = groups.fields_structure[position].Length;

						$(lonTTmp).unbind();
						$(lonTTmp).bind("change", function() {
							var dom = $(this);
							var position = dom.context.parentNode.parentNode.id;
							groups.fields_structure[position].Length = dom.context.value;
							sessionStorage.tmpStructure = JSON.stringify(groups);
						});
					break;
				}
			});
			$(typeTTmp).change();
		break;
		case 'tv':
			var pTmp = document.createElement("P");
			var loptions = document.createElement("LABEL");	
			loptions.id = index+'_loption';	
			
			divTmp.appendChild(pTmp);
			divTmp.appendChild(loptions);
			loptions.innerHTML = "Opciones:";

			//crea las opciones
			var divOption = document.createElement("DIV");
			divOption.id = index+'_optionDiv';
			divTmp.appendChild(divOption);				

			$(loptions).unbind();
			$(loptions).bind("click", function(){
				var type = strTmp.Type;
				var dom = $(this);
				var position = dom.context.parentNode.parentNode.id;
				var flag = 0;
				sessionStorage.tmpStructure = JSON.stringify(groups);
				var idPre = groups.fields_structure[position].Id;
				if(groups.options.length == 0){
					var indix = groups.options.length;
    				groups.options[indix] = JSON.parse("[]");
					var dom = $(this);
					dom = dom.context.id.split("_");
					dom = dom[0];
					var jsonTmp = JSON.parse("{}");
					var field = parseInt(idPre); 
					field = field.toString();

					jsonTmp.Id = indix;
					jsonTmp.Field = JSON.parse("["+field+"]");
					jsonTmp.Options = JSON.parse("[]");
					jsonTmp.Options[indix] = "-";
					groups.options[indix] = jsonTmp;
				}else{
					for(indice in groups.options){
						if(groups.options[indice].Field[0] == idPre){
							flag++;
							indix = parseInt(indice);
						}
					}
					if(flag == 0){
						var indix = groups.options.length;
	    				groups.options[indix] = JSON.parse("[]");
						var jsonTmp = JSON.parse("{}");
						var field = parseInt(idPre); 
						field = field.toString();

						jsonTmp.Id = indix;
						jsonTmp.Field = JSON.parse("["+field+"]");
						jsonTmp.Options = JSON.parse("[]");
						jsonTmp.Options[0] = "-";
						groups.options[indix] = jsonTmp;
					}else{
					}
				}
				createOptionsSp(divOption, indix, idPre, index, type);				
			});
			$(loptions).click();
		break;
	}
	$(iDivTmp).unbind();
	$(sDivTmp).unbind();
	$(iDivTmp).bind("click", function() {
		var index = $(this);
		var parent = index.context.parentNode;
		var container = document.getElementById("right_creator_editor");
		container.insertBefore(createTypeField(index.context.id), parent);
	});
	$(sDivTmp).bind("click", function(){
		var dom = $(this);
		selected.menu = dom.context.id;
		var idTmp = selected.menu.split("_");
		$("#"+idTmp[0]+"_up").toggle();
		$("#"+idTmp[0]+"_down").toggle();
		$("#"+idTmp[0]+"_delete").toggle();

		$("#"+idTmp[0]+"_up").unbind();
		$("#"+idTmp[0]+"_up").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[0];
			var formTmp = groups.fields_structure[idTmp2].Form;
			var beforeBrother = -1;
			for (var index in groups.fields_structure) {
				if(parseInt(idTmp2) > index) {
					if (groups.fields_structure[index].Form == formTmp) {
						beforeBrother = index;
					}
				} else {
					if(beforeBrother > -1) {
						var jsonTmp = groups.fields_structure[idTmp2];
						delete groups.fields_structure[idTmp2];
						groups.fields_structure.splice(idTmp2, 1);
						groups.fields_structure.splice((beforeBrother), 0, jsonTmp);
						fillFields2(selected.group);
					} else {
						alert("Ya está en la primera posicion");
					}
					break;
				}
			}
		});
		$("#"+idTmp[0]+"_down").unbind()
		$("#"+idTmp[0]+"_down").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[0];
			var formTmp = groups.fields_structure[idTmp2].Form;
			var nextBrother = -1;
			for (var index in groups.fields_structure) {
				if(parseInt(idTmp2) < index) {
					if (groups.fields_structure[index].Form == formTmp) {
						nextBrother = index;
						formTmp = -10;
						break;
					}
				}
			}
			if(nextBrother > -1) {
				var jsonTmp = groups.fields_structure[idTmp2];
				delete groups.fields_structure[idTmp2];
				groups.fields_structure.splice(idTmp2, 1);
				groups.fields_structure.splice((nextBrother), 0, jsonTmp);
				fillFields2(selected.group);
			} else {
				alert("Ya está en la última posicion");
			}
		});
		$("#"+idTmp[0]+"_delete").unbind();
		$("#"+idTmp[0]+"_delete").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[0];
			if(confirm("¿Está seguro que desea elminar la pregunta "+groups.fields_structure[idTmp2].Label+"?\nEsta accion no se puede deshacer.")) {
				for(indice in groups.options){
					if(groups.options[indice].Field == groups.fields_structure[idTmp2].Id){
						delete groups.options[indice];
						groups.options.splice(indice, 1);
					}
				}
				delete groups.fields_structure[idTmp2];
				groups.fields_structure.splice(idTmp2, 1);
				fillFields2(selected.group);
			}
		});
	});
	$(itTmp).unbind();
	$(cbRTmp).unbind();
	$(cbETmp).unbind();
	$(itTmp).bind("change", function(){
		var dom = $(this);
		var position = dom.context.parentNode.parentNode.id;
		groups.fields_structure[position].Name = dom.context.value.toLowerCase().repare()+groups.fields_structure[position].Id;
		groups.fields_structure[position].Label = dom.context.value;
		sessionStorage.tmpStructure = JSON.stringify(groups);
	});

	/*validar que el nombre del campo no se encuentre vacia*/
	$(itTmp).unbind();
	$(itTmp).bind("focusout", function(){
		var dom = $(this);
		var position = dom.context.parentNode.parentNode.id;
		if(dom.context.value.toLowerCase().repare() != ''){
			//nombre de cada pregunta
			//console.log(position);
			groups.fields_structure[position].Name = dom.context.value.toLowerCase().repare()+groups.fields_structure[position].Id;
			groups.fields_structure[position].Label = dom.context.value;
		}else{
			alert('El campo no puede estar vacio.');
			$(itTmp).focus();
		}
	});

	$(lbRTmp).bind("change", function() {
		var dom = $(this);
		var position = dom.context.parentNode.parentNode.id;
		var cb;
		for(var index2 = 0; index2 < dom.context.childElementCount; index2++) {
			cb = dom.context.children[index2];
			if(cb.type == "checkbox") break;
		}
		groups.fields_structure[position].Required = cb.checked+"";
		sessionStorage.tmpStructure = JSON.stringify(groups);
	})
	$(lbETmp).bind("change", function() {
		var dom = $(this);
		var position = dom.context.parentNode.parentNode.id;
		var cb;
		for(var index2 = 0; index2 < dom.context.childElementCount; index2++) {
			cb = dom.context.children[index2];
			if(cb.type == "checkbox") break;
		}
		groups.fields_structure[position].ReadOnly = (!cb.checked)+"";
		sessionStorage.tmpStructure = JSON.stringify(groups);
	})

	return (gDivTmp);
}

/*función que crea las opciones de las respuestas en lista desplegable*/
function createOptionsSp(divOption, index, idPre, indexUI, type){
	//console.log(divOption, index, idPre, indexUI, type);
	divOption.innerHTML = "";
	var indquestion = index;
	switch(type){
		case 'sp':
		case 'rg':
			if(groups.options[indquestion].Field[0] == idPre){
				if(groups.options[indquestion].Options.length < 2){
					var iDivTmpSP = document.createElement("DIV");
					var imgTmpSp = document.createElement("IMG");

					iDivTmpSP.appendChild(imgTmpSp);
					iDivTmpSP.appendChild(document.createElement("br"));
					iDivTmpSP.appendChild(document.createTextNode("Crear opción"));

					iDivTmpSP.id = indexUI+"_add_option";
					iDivTmpSP.className = "d_add_img_content";

					imgTmpSp.className = "i_add_Button";
					imgTmpSp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";

					divOption.appendChild(iDivTmpSP);
					divOption.style.display = "block";

					//Evento para crear una opcion
					var idOption = 0;
					$(iDivTmpSP).unbind();
					$(iDivTmpSP).bind("click", function() {
						idOption++;
						var index = $(imgTmpSp);
						var parent = index.context.parentNode;
						var container = document.getElementById(indexUI+"_optionDiv");
						container.insertBefore(createOptionField(idOption, indexUI, idPre, indquestion, type), parent);
					});

				}else{
					var iDivTmpSP = document.createElement("DIV");
					var imgTmpSp = document.createElement("IMG");

					iDivTmpSP.appendChild(imgTmpSp);
					iDivTmpSP.appendChild(document.createElement("br"));
					iDivTmpSP.appendChild(document.createTextNode("Crear opción"));

					iDivTmpSP.id = indexUI+"_add_option";
					iDivTmpSP.className = "d_add_img_content";

					imgTmpSp.className = "i_add_Button";
					imgTmpSp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";

					divOption.appendChild(iDivTmpSP);
					divOption.style.display = "block";

					var idOption = groups.options[indquestion].Options.length-1;
					$(iDivTmpSP).unbind();
					$(iDivTmpSP).bind("click", function() {
						idOption++;
						var index = $(imgTmpSp);
						var parent = index.context.parentNode;
						var container = document.getElementById(indexUI+"_optionDiv");
						container.insertBefore(createOptionField(idOption, indexUI, idPre, indquestion, type), parent);
					});

					for (var indice in groups.options[indquestion].Options){
						if (indice > 0) {
							indice = parseInt(indice);
							var index = $(imgTmpSp);
							var parent = index.context.parentNode;
							var container = divOption;
							container.insertBefore(createOptionField(indice, indexUI, idPre, indquestion, type), parent);
						}
					}
				}
			}
		break;
		case 'tv':
			if(groups.options[indquestion].Field[0] == idPre){
				if(groups.options[indquestion].Options.length < 2){
					var iDivTmpSP = document.createElement("DIV");
					var imgTmpSp = document.createElement("IMG");

					iDivTmpSP.appendChild(imgTmpSp);
					iDivTmpSP.appendChild(document.createElement("br"));
					iDivTmpSP.appendChild(document.createTextNode("Crear opción"));

					iDivTmpSP.id = indexUI+"_add_option";
					iDivTmpSP.className = "d_add_img_content";

					imgTmpSp.className = "i_add_Button";
					imgTmpSp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";

					divOption.appendChild(iDivTmpSP);
					divOption.style.display = "block";

					var idOption = 0;
					$(iDivTmpSP).unbind();
					$(iDivTmpSP).bind("click", function() {
						idOption++;
						var index = $(imgTmpSp);
						var parent = index.context.parentNode;
						var container = document.getElementById(indexUI+"_optionDiv");
						container.insertBefore(createOptionField(idOption, indexUI, idPre, indquestion, type), parent);
					});
				}else{
					var iDivTmpSP = document.createElement("DIV");
					var imgTmpSp = document.createElement("IMG");

					iDivTmpSP.appendChild(imgTmpSp);
					iDivTmpSP.appendChild(document.createElement("br"));
					iDivTmpSP.appendChild(document.createTextNode("Crear opción"));

					iDivTmpSP.id = indexUI+"_add_option";
					iDivTmpSP.className = "d_add_img_content";

					imgTmpSp.className = "i_add_Button";
					imgTmpSp.src = "src/images/png/ic_add_circle_green_48dp_1x.png";

					divOption.appendChild(iDivTmpSP);
					divOption.style.display = "block";

					var idOption = groups.options[indquestion].Options.length-1;
					$(iDivTmpSP).unbind();
					$(iDivTmpSP).bind("click", function() {
						idOption++;
						var index = $(imgTmpSp);
						var parent = index.context.parentNode;
						var container = document.getElementById(indexUI+"_optionDiv");
						container.insertBefore(createOptionField(idOption, indexUI, idPre, indquestion, type), parent);
					});

					for (var indice in groups.options[indquestion].Options){
						if (indice > 0) {
							indice = parseInt(indice);
							var index = $(imgTmpSp);
							var parent = index.context.parentNode;
							var container = divOption;
							container.insertBefore(createOptionField(indice, indexUI, idPre, indquestion, type), parent);
						}
					}
				}
			}
		break;
	}
}

function createOptionField(parentId, indexUI, idPre, indquestion, type){
 	// Crear las opciones 
	var sDivTmpSp = document.createElement("DIV");
	var mDivTmpSp = document.createElement("DIV");
	var aDivTmpSp = document.createElement("DIV");
	var bDivTmpSp = document.createElement("DIV");
	var dDivTmpSp = document.createElement("DIV");

	sDivTmpSp.appendChild(mDivTmpSp);
	mDivTmpSp.appendChild(aDivTmpSp);
	mDivTmpSp.appendChild(bDivTmpSp);
	mDivTmpSp.appendChild(dDivTmpSp);

	sDivTmpSp.id = indexUI+"_"+parentId+"_menu";
	aDivTmpSp.id = indexUI+"_"+parentId+"_up";
	bDivTmpSp.id = indexUI+"_"+parentId+"_down";
	dDivTmpSp.id = indexUI+"_"+parentId+"_delete";

	sDivTmpSp.className = "d_field_settings";
	mDivTmpSp.className = "img_field_settings";
	aDivTmpSp.className = "d_any_field_option";
	bDivTmpSp.className = "d_any_field_option";
	dDivTmpSp.className = "d_any_field_option";

	aDivTmpSp.innerHTML = "Mover arriba";
	bDivTmpSp.innerHTML = "Mover abajo";
	dDivTmpSp.innerHTML = "Eliminar";

	var opDiv = document.createElement("DIV");
	var inputOption = document.createElement("INPUT");

	inputOption.type = "TEXT";

	switch(type){
		case 'sp':
			var lbOption = document.createElement("LABEL");
			lbOption.innerHTML = "Opcion"+parentId+" :";
			lbOption.id = indexUI+"_"+parentId+"_lbopt";
		break;
		case 'rg':
			var lbOption = document.createElement("INPUT");
			lbOption.type = "RADIO";
			lbOption.id = indexUI+"_"+parentId+"_lbopt";
			lbOption.style.display = "block";
			lbOption.disabled = "true";
			lbOption.className = "opcionesRadioClass";

			opDiv.className = "margin_opt";
			inputOption.className = "opcionesClass";
		break;
		case 'tv':
			var lbOption = document.createElement("INPUT");
			lbOption.type = "CHECKBOX";
			lbOption.id = indexUI+"_"+parentId+"_lbopt";
			lbOption.style.display = "block";
			lbOption.disabled = "true";
			lbOption.className = "opcionesRadioClass";

			opDiv.className = "margin_opt";
			inputOption.className = "opcionesClass";
		break;
	}

	opDiv.id = indexUI+"_"+parentId+"_option";
	
	inputOption.id = indexUI+"_"+parentId+"_inopt";

	opDiv.className = "margin_opt";
	inputOption.className = "opcionesClass";

	opDiv.appendChild(lbOption);
	opDiv.appendChild(inputOption);
	opDiv.appendChild(sDivTmpSp);

	
	if(groups.options[indquestion].Options[parentId] == undefined){
		groups.options[indquestion].Options[parentId] = "";
	}else{
		$(inputOption).val(groups.options[indquestion].Options[parentId]);
		var container = document.getElementById(indexUI+"_optionDiv");
	}

	/*validar que la opcion del campo no se encuentre vacia*/
	$(inputOption).unbind();
	$(inputOption).bind("focusout", function(){
		if($(inputOption).val().trim() != ''){
			groups.options[indquestion].Options[parentId] = $(inputOption).val();
		}else{
			alert('El campo no puede estar vacio.');
			$(inputOption).focus();
		}
	});

	/*submenus de las opciones de la estructura*/
	$(sDivTmpSp).bind("click", function(){
		var dom = $(this);
		selected.menu = dom.context.id;
		var idTmp = selected.menu.split("_");
		var divOption = document.getElementById(idTmp[0]+"_optionDiv");

		$("#"+idTmp[0]+"_"+idTmp[1]+"_up").toggle();
		$("#"+idTmp[0]+"_"+idTmp[1]+"_down").toggle();
		$("#"+idTmp[0]+"_"+idTmp[1]+"_delete").toggle();
		
		$("#"+idTmp[0]+"_"+idTmp[1]+"_up").unbind();
		$("#"+idTmp[0]+"_"+idTmp[1]+"_up").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[1];
			var beforeBrother = 0;
			for (var index in groups.options[indquestion].Options) {
				if(index > 0){
					if(parseInt(idTmp2) > index) {
						beforeBrother = index;
					} else {
						if(beforeBrother > 0) {
							var jsonTmp = groups.options[indquestion].Options[idTmp2];
							delete groups.options[indquestion].Options[idTmp2];
							groups.options[indquestion].Options.splice(idTmp2, 1);
							groups.options[indquestion].Options.splice((beforeBrother), 0, jsonTmp);
							divOption.innerHTML = "";
							createOptionsSp(divOption, indquestion, idPre, indexUI, type);
						} else {
							alert("Ya está en la primera posicion");
						}
						break;
					}
				}
			}
		});
		$("#"+idTmp[0]+"_"+idTmp[1]+"_down").unbind();
		$("#"+idTmp[0]+"_"+idTmp[1]+"_down").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[1];
			var nextBrother = 0;
			for (var index in groups.options[indquestion].Options) {
				if(index > 0){
					if(idTmp2 < index) {
						nextBrother = index;
						break;
					}
				}
			}
			if(nextBrother > 0) {
				var jsonTmp = groups.options[indquestion].Options[idTmp2];
				delete groups.options[indquestion].Options[idTmp2];
				groups.options[indquestion].Options.splice(idTmp2, 1);
				groups.options[indquestion].Options.splice((nextBrother), 0, jsonTmp);
				divOption.innerHTML = "";
				createOptionsSp(divOption, indquestion, idPre, indexUI, type);
			} else {
				alert("Ya está en la última posicion");
			}
		});
		$("#"+idTmp[0]+"_"+idTmp[1]+"_delete").unbind();
		$("#"+idTmp[0]+"_"+idTmp[1]+"_delete").bind("click", function() {
			var idTmp2 = $(this).context.id;
			idTmp2 = idTmp2.split("_");
			idTmp2 = idTmp2[1];
			if(confirm("¿Está seguro que desea elminar la opción"+groups.options[indquestion].Options[idTmp2]+"?\nEsta accion no se puede deshacer.")) {
				delete groups.options[indquestion].Options[idTmp2];
				groups.options[indquestion].Options.splice(idTmp2, 1);
				divOption.innerHTML = "";
				createOptionsSp(divOption, indquestion, idPre, indexUI, type);
			}
		});
	});
	/* OCULTAR EL MENU CUANDO SE REALIZA CLIC SOBRE EL DIV*/
	$(document).unbind();
	$(document).mouseup(function(e) {
	    var container = $("#"+selected.menu);
	    if (!container.is(e.target) && container.has(e.target).length === 0) {
	    	try {
		    	var idTmp = selected.menu.split("_");
				$("#"+idTmp[0]+"_"+idTmp[1]+"_up").hide();
				$("#"+idTmp[0]+"_"+idTmp[1]+"_down").hide();
				$("#"+idTmp[0]+"_"+idTmp[1]+"_delete").hide();
			} catch(Exception){}
	    }
	});
	/* OCULTAR EL MENU CUANDO SE REALIZA SCROLL SOBRE EL DIV*/
	var container = document.getElementById("right_creator_editor");
	$(container).unbind();
		$(container).scroll(function() {
	    	try {
		    	var idTmp = selected.menu.split("_");
				$("#"+idTmp[0]+"_"+idTmp[1]+"_up").hide();
				$("#"+idTmp[0]+"_"+idTmp[1]+"_down").hide();
				$("#"+idTmp[0]+"_"+idTmp[1]+"_delete").hide();
			} catch(Exception){}
		});

	return (opDiv);
}

function createTypeField(parentId) {
	//var idgroup = groups.forms[selected.group].Id;
	parentId = parentId.split("_");
	parentId = parentId[0];
	var divTmp = document.createElement("DIV");
	var tbTmp = document.createElement("TABLE");
	var trTmp1 = document.createElement("TR");
	var trTmp2 = document.createElement("TR");
	var tdTmp1 = document.createElement("TD");
	var tdTmp2 = document.createElement("TD");
	var tdTmp3 = document.createElement("TD");
	var tdTmp4 = document.createElement("TD");

	divTmp.appendChild(tbTmp);
	tbTmp.appendChild(trTmp1);
	tbTmp.appendChild(trTmp2);
	trTmp1.appendChild(tdTmp1);
	trTmp1.appendChild(tdTmp2);
	trTmp2.appendChild(tdTmp3);
	trTmp2.appendChild(tdTmp4);

	tdTmp1.className = "td_edit_text_field_selector";
	tdTmp2.className = "td_single_choise_field_selector";
	tdTmp3.className = "td_date_field_selector";
	tdTmp4.className = "td_multiple_choise_field_selector";

	tbTmp.width = "100%";
	tbTmp.border = "0";

	tdTmp1.align = "center";
	tdTmp2.align = "center";
	tdTmp3.align = "center";
	tdTmp4.align = "center";

	tdTmp1.innerHTML = "<br /><br /><br />Campo de texto";
	tdTmp2.innerHTML = "<br /><br /><br />Campo de seleccion única";
	tdTmp3.innerHTML = "<br /><br /><br />Campo de Fecha";
	tdTmp4.innerHTML = "<br /><br /><br />Campo de Seleccion múltiple";

	tdTmp1.id = parentId+"_selector";
	tdTmp2.id = parentId+"_selector";
	tdTmp3.id = parentId+"_selector";
	tdTmp4.id = parentId+"_selector";

	$(tdTmp1).unbind();
	$(tdTmp2).unbind();
	$(tdTmp3).unbind();
	$(tdTmp4).unbind();

	$(tdTmp1).bind("click", function() {
		var dom = $(this);
		dom = dom.context.id.split("_");
		dom = dom[0];
		var jsonTmp = JSON.parse("{}");
		jsonTmp.Id = findLastId(groups.fields_structure);
		jsonTmp.Name = "";
		jsonTmp.Label = "";
		jsonTmp.Type = "tf";
		jsonTmp.Required = "true";
		jsonTmp.Hint = "";
		jsonTmp.Rules = "";
		jsonTmp.Length = ""
		jsonTmp.Parent = "0";
		jsonTmp.Order = "0";
		jsonTmp.ReadOnly = "false";
		jsonTmp.AutoComplete = "0";
		jsonTmp.FreeAdd = "0";
		jsonTmp.Handler = "0";
		//jsonTmp.Form = ""+selected.group;
		jsonTmp.Form = ""+groups.forms[selected.group].Id;

		groups.fields_structure.splice(dom, 0, jsonTmp);
		var parent = $(this).context.parentNode.parentNode.parentNode;
		fillFields2(selected.group);
	});
	$(tdTmp2).bind("click", function() {
		var dom = $(this);
		dom = dom.context.id.split("_");
		dom = dom[0];
		var jsonTmp = JSON.parse("{}");
		jsonTmp.Id = findLastId(groups.fields_structure);
		jsonTmp.Name = "";
		jsonTmp.Label = "";
		jsonTmp.Type = "sp";
		jsonTmp.Required = "true";
		jsonTmp.Hint = "0";
		jsonTmp.Rules = "0";
		jsonTmp.Length = "0"
		jsonTmp.Parent = "0";
		jsonTmp.Order = "0";
		jsonTmp.ReadOnly = "false";
		jsonTmp.AutoComplete = "0";
		jsonTmp.FreeAdd = "0";
		jsonTmp.Handler = "0";
		jsonTmp.Form = ""+groups.forms[selected.group].Id;
		
		groups.fields_structure.splice((dom), 0, jsonTmp);
		var parent = $(this).context.parentNode.parentNode.parentNode;
		fillFields2(selected.group);
	});
	$(tdTmp3).bind("click", function() {
		var dom = $(this);
		dom = dom.context.id.split("_");
		dom = dom[0];
		var jsonTmp = JSON.parse("{}");
		jsonTmp.Id = findLastId(groups.fields_structure);
		jsonTmp.Name = "";
		jsonTmp.Label = "";
		jsonTmp.Type = "dp";
		jsonTmp.Required = "true";
		jsonTmp.Hint = "0";
		jsonTmp.Rules = "0";
		jsonTmp.Length = "0"
		jsonTmp.Parent = "0";
		jsonTmp.Order = "0";
		jsonTmp.ReadOnly = "false";
		jsonTmp.AutoComplete = "0";
		jsonTmp.FreeAdd = "0";
		jsonTmp.Handler = "0";
		jsonTmp.Form = ""+groups.forms[selected.group].Id;
		
		groups.fields_structure.splice((dom), 0, jsonTmp);
		fillFields2(selected.group);
	});
	$(tdTmp4).bind("click", function() {
		var dom = $(this);
		dom = dom.context.id.split("_");
		dom = dom[0];
		var jsonTmp = JSON.parse("{}");
		jsonTmp.Id = findLastId(groups.fields_structure);
		jsonTmp.Name = "";
		jsonTmp.Label = "";
		jsonTmp.Type = "tv";
		jsonTmp.Required = "true";
		jsonTmp.Hint = "0";
		jsonTmp.Rules = "0";
		jsonTmp.Length = "0"
		jsonTmp.Parent = "0";
		jsonTmp.Order = "0";
		jsonTmp.ReadOnly = "false";
		jsonTmp.AutoComplete = "0";
		jsonTmp.FreeAdd = "0";
		jsonTmp.Handler = "0";
		jsonTmp.Form = ""+groups.forms[selected.group].Id;

		groups.fields_structure.splice((dom), 0, jsonTmp);
		fillFields2(selected.group);
	});
	return (divTmp);
}

function findLastId(jsonArr) {
	id = 0;
	for(var index in jsonArr) {
		if(id < parseInt(jsonArr[index].Id)) {
			id = parseInt(jsonArr[index].Id);
		}
	}
	return id+1;
}

function fillEvents() {
	try {
		var events = groups.handler_event;
		if (events == undefined)
			groups.handler_event = JSON.parse("[]");
		else {
			var TB = document.getElementById("left_events_table_f");
			TB.innerHTML = "";
			for(var index in events) {
				var TR = document.createElement("TR");
				var TD = document.createElement("TD");

				TB.appendChild(TR);
				TR.appendChild(TD);

				TD.id = index;
				TD.appendChild(document.createTextNode("Identificador: "+events[index].Id));
				for(var index2 in events[index].Types) {
					var DV = document.createElement("DIV");
					DV.appendChild(document.createTextNode(
						events[index].Types[index2]+" : "+
						events[index].Parameters[index2]
					));
					DV.className = "d_description";
					TD.appendChild(DV);
				}
				createEventItemAction(TD);
			}
		}
		event_c.Types = JSON.parse("[]");
		event_c.Parameters = JSON.parse("[]");
		createActionsForEvent();
	} catch(Exception){
		console.log(Exception);
	}
}

function fillOptions() {
	//try {
		$("#bt_append_option").val(capitalizeFirstLetter(_AGREGAR));
		$("#b_option_saver").val(capitalizeFirstLetter(_GUARDAR));
        if(groups.options == undefined)
        	groups.options = JSON.parse("[]");
        var tbTmp = document.createElement("TABLE");
        tbTmp.className = "d_left_resumer_table";
        tbTmp.cellspacing = "3";
        document.getElementById("d_left_alert_fs").innerHTML = "";
        document.getElementById("d_left_alert_fs").appendChild(tbTmp);
        for(var index in groups.options) {
        	var trTmp = document.createElement("TR");
        	var tdTmp = document.createElement("TD");

        	tbTmp.appendChild(trTmp);
        	trTmp.appendChild(tdTmp);

        	tdTmp.appendChild(document.createTextNode(capitalizeFirstLetter(_IDENTIFICADOR)+": "+groups.options[index].Id));
        	if (groups.options[0].Field.length > 0) {
        		var divTmp = document.createElement("DIV");
        		divTmp.className = "d_description";
        		divTmp.appendChild(document.createTextNode("→ "+_CAMPO2+"s"));
        		tdTmp.appendChild(divTmp);
        	}
        	for (var index2 in groups.options[index].Field) {
        		var divTmp = document.createElement("DIV");
        		divTmp.className = "d_description";
        		divTmp.appendChild(document.createTextNode(groups.options[index].Field[index2]));
        		tdTmp.appendChild(divTmp);
    		}
    		if (groups.options[0].Options.length > 0) {
        		var divTmp = document.createElement("DIV");
        		divTmp.className = "d_description";
        		divTmp.appendChild(document.createTextNode("→ "+_OPCIONES+"s"));
        		tdTmp.appendChild(divTmp);
        	}
        	for (var index2 in groups.options[index].Options) {
        		if (groups.options[index].Options[index2] != "-") {
	        		var divTmp = document.createElement("DIV");
	        		divTmp.className = "d_description";
	        		divTmp.appendChild(document.createTextNode(groups.options[index].Options[index2]));
	        		tdTmp.appendChild(divTmp);
	        	}
        	}
        	tdTmp.id = index;
        	createOptionsForOptionItemAction(tdTmp);
        }
        createActionsForOption();
	/*} catch(Exception){
		console.log(Exception);
	}*/
}

//llena el spinner de proyectos con los proyectos existentes
function fillSelectProject(data) {
	var selProject = document.getElementById('it_nProject');
	data = JSON.parse(data);
	for(var index = 0; index < data.length; index++){
		var option= document.createElement("option");
		if(groups.Document_info['projectId'] == data[index]['Id']){
			option.selected = "true";
		}
		option.text = data[index]['Project'];
		option.value = data[index]['Id'];
		selProject.add(option);
	}
}

function fillPoll() {
	var dvTmp = document.createElement("DIV");
	for (var index in groups.forms) {
		if(groups.forms[index].Parent == "0") {
			if (groups.forms[index].Processed == undefined) {
				dvTmp.innerHTML += fillForm(groups.forms[index], index);
			}
		}
	}
	for(var index in groups.forms) {
		delete(groups.forms[index].Processed);
	}
	return (dvTmp.innerHTML);
}

function fillPolls(id, options) {
	releaseUnload();
	if(sessionStorage.allPollData) {
		$("#d_modifier_loading").css("display", "none");
		var tbTmp = document.getElementById(id);
		groups = JSON.parse(sessionStorage.allPollData);
		for(var index in groups) {
			var trTmp = document.createElement("TR");
			var tdTmp1 = document.createElement("TD");
			var tdTmp2 = document.createElement("TD");
			var tdTmp3 = document.createElement("TD");
			var tdTmp4 = document.createElement("TD");
			var tdTmp5 = document.createElement("TD");
			var tdTmp6 = document.createElement("TD");
			var tdTmp7 = document.createElement("TD");
			var tdTmp8 = document.createElement("TD");

			tbTmp.appendChild(trTmp);

			trTmp.appendChild(tdTmp1);
			trTmp.appendChild(tdTmp2);
			trTmp.appendChild(tdTmp3);
			trTmp.appendChild(tdTmp4);
			trTmp.appendChild(tdTmp5);
			trTmp.appendChild(tdTmp6);
			trTmp.appendChild(tdTmp7);
			trTmp.appendChild(tdTmp8);

			tdTmp1.width = "5%";
			tdTmp2.width = "31%";
			tdTmp3.width = "5%";
			tdTmp4.width = "15%";
			tdTmp5.width = "10%";
			tdTmp6.width = "10%";
			tdTmp7.width = "12%";
			tdTmp8.width = "12%";

			//$(tdTmp5).css("text-align", "center");

			tdTmp1.innerHTML = groups[index].Id;
			tdTmp2.innerHTML = groups[index].Poll.Document_info.structureName;
			tdTmp3.innerHTML = groups[index].Poll.Document_info.projectId;
			tdTmp4.innerHTML = groups[index].User;
			tdTmp5.innerHTML = groups[index].Date;
			tdTmp6.innerHTML = ((groups[index].Status == 0) ? capitalizeFirstLetter(_INACTIVO) : capitalizeFirstLetter(_ACTIVO));
			tdTmp7.innerHTML = capitalizeFirstLetter(_MODIFICAR);
			tdTmp8.innerHTML = capitalizeFirstLetter(_APLICAR);

			tdTmp7.lang = index;
			tdTmp8.lang = index;
			$(tdTmp7).unbind();
			$(tdTmp7).bind("click", function() {
				var dom = $(this);
				sessionStorage.editionName = groups[dom.context.lang].Poll.Document_info.structureName;
				sessionStorage.editionIndex = groups[dom.context.lang].Id;
				sessionStorage.extras = JSON.stringify(groups[dom.context.lang].Poll);
				document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /><input type="hidden" name="request" value="creator" /> </form>';
				document.getElementById("f_requester").submit();
			});
			$(tdTmp8).unbind();
			$(tdTmp8).bind("click", function() {
				var dom = $(this);
				sessionStorage.editionName = groups[dom.context.lang].Poll.Document_info.structureName;
				sessionStorage.editionIndex = groups[dom.context.lang].Id;
				groups = groups[dom.context.lang].Poll;
				delete(sessionStorage.allPollData);
				prepareService(".pollSaver");
				location.reload();
			});
		}
	}
}

function fillForm(form, index) {
	var dvTmp = document.createElement("DIV");
	var gUlTmp = document.createElement("UL");
	var gnLiTmp = document.createElement("LI");

	dvTmp.appendChild(gUlTmp);
	gUlTmp.appendChild(gnLiTmp);

	gnLiTmp.innerHTML = (capitalizeFirstLetter("<b>"+capitalizeFirstLetter(_GRUPO)+":</b> "+form.Header));

	groups.forms[index].Processed = true;

	for (var index2 in groups.fields_structure) {
		if(groups.fields_structure[index2].Form == form.Id){
			var pUlTmp = document.createElement("UL");
			var pLiTmp = document.createElement("LI");

			gUlTmp.appendChild(pUlTmp);
			pUlTmp.appendChild(pLiTmp);

			pLiTmp.innerHTML = "<b>"+capitalizeFirstLetter(_CAMPO)+":</b> "+capitalizeFirstLetter(groups.fields_structure[index2].Label);

			for (var index3 in groups.forms) {
				if(groups.forms[index3].Parent == groups.fields_structure[index2].Id){
					pUlTmp.innerHTML += fillForm(groups.forms[index3], index3);
				}
			}
		}
	}
	return (dvTmp.innerHTML);
}

function fillImportables(id) {
	var divTmp = document.getElementById("r_alert_dialog_content");
	divTmp.innerHTML = "";

	divTmp.className = "d_center_resumer";

	var divTmp1 = document.createElement("DIV");
	var divTmp2 = document.createElement("DIV");
	var divTmp3 = document.createElement("DIV");
	var divTmp4 = document.createElement("DIV");
	var divTmp5 = document.createElement("DIV");
	var divTmp6 = document.createElement("DIV");
	var divTmp7 = document.createElement("DIV");
	var divTmp8 = document.createElement("DIV");
	var divTmp9 = document.createElement("DIV");

	divTmp.appendChild(divTmp1);
	divTmp.appendChild(divTmp2);
	divTmp.appendChild(divTmp3);
	divTmp.appendChild(divTmp4);
	divTmp.appendChild(divTmp5);
	divTmp.appendChild(divTmp6);
	divTmp.appendChild(divTmp7);
	divTmp.appendChild(divTmp8);
	divTmp.appendChild(divTmp9);

	var divTmpS = document.createElement("DIV");
	var btnTmp = document.createElement("INPUT");

	divTmp.appendChild(divTmpS);
	divTmpS.appendChild(btnTmp);

	btnTmp.type = "BUTTON";
	$(btnTmp).val(capitalizeFirstLetter(_IMPORTAR));
	btnTmp.style.display = "none";

	var importablePoll = JSON.parse('{"fields_structure":[{"Name":"Area","Label":"Area","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Poblacion","Label":"Población","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"Familia","Label":"Apellido del núcleo familiar","Type":"tf","Required":"true","Hint":"Apellido del núcleo familiar","Rules":"vch","Length":"60","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"Direccion","Label":"Dirección","Type":"tf","Required":"true","Hint":"Direccion","Rules":"vch","Length":"150","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"Telefono","Label":"Teléfono","Type":"tf","Required":"true","Hint":"Teléfono","Rules":"int","Length":"10","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"cugrfacoesvi","Label":"¿Cuantos grupos familiares comparten esta vivienda?","Type":"tf","Required":"true","Hint":"Grupos familiares","Rules":"int","Length":"1","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"cupehaesvi","Label":"¿Cuantas personas habitan ésta vivienda?","Type":"tf","Required":"true","Hint":"Personas que habitan","Rules":"int","Length":"2","Order":"7","ReadOnly":"0","AutoComplete":"0"},{"Name":"cupepeco","Label":"¿Cuantas de estas personas pertenecen a Confasucre?","Type":"tf","Required":"true","Hint":"Personas de Confasucre","Rules":"int","Length":"2","Order":"8","ReadOnly":"0","AutoComplete":"0"},{"Name":"tipoId","Label":"Tipo de identificación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"documento","Label":"documento de identificación","Type":"tf","Required":"true","Hint":"Documento","Rules":"int","Length":"10","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"nombre","Label":"Primer Nombre","Type":"tf","Required":"true","Hint":"Primer nombre","Rules":"vch","Length":"25","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"sNombre","Label":"Segundo Nombre","Type":"tf","Required":"false","Hint":"Segundo nombre","Rules":"vch","Length":"25","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"apellido","Label":"Primer Apellido","Type":"tf","Required":"true","Hint":"Primer apellido","Rules":"vch","Length":"25","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"sApellido","Label":"Segundo Apellido","Type":"tf","Required":"false","Hint":"Segundo apellido","Rules":"vch","Length":"25","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"fecNac","Label":"Fecha de nacimiento","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"7","ReadOnly":"0","AutoComplete":"0"},{"Name":"Edad","Label":"Edad","Type":"tf","Required":"true","Hint":"Edad","Rules":"int","Length":"3","Order":"8","ReadOnly":"1","AutoComplete":"0"},{"Name":"celular","Label":"Celular y/o Teléfono","Type":"tf","Required":"false","Hint":"Número de Celular","Rules":"int","Length":"10","Order":"9","ReadOnly":"0","AutoComplete":"0"},{"Name":"genero","Label":"Genero","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"10","ReadOnly":"0","AutoComplete":"0"},{"Name":"Parentesco","Label":"Parentesco","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"11","ReadOnly":"0","AutoComplete":"0"},{"Name":"Regimen","Label":"Régimen de salud","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"12","ReadOnly":"0","AutoComplete":"0"},{"Name":"TipoAf","Label":"Tipo de afiliación","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"13","ReadOnly":"0","AutoComplete":"0"},{"Name":"Raza","Label":"Raza","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"14","ReadOnly":"0","AutoComplete":"0"},{"Name":"eps","Label":"EPS","Type":"sp","Required":"true","Hint":"EPS","Rules":"0","Length":"0","Order":"15","ReadOnly":"0","AutoComplete":"0"},{"Name":"carnet","Label":"Número de carnet","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"10","Order":"16","ReadOnly":"0","AutoComplete":"0"},{"Name":"Beneficios","Label":"Beneficios","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"16","ReadOnly":"0","AutoComplete":"0"},{"Name":"faenac","Label":"Familias en acción","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"plmudeal","Label":"Plan mundial de alimentos","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"deceasi","Label":"De cero a siempre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"bees","Label":"Becas estudiantiles","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"icetex","Label":"Créditos ICETEX","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"beneOtros","Label":"Otros","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"Ocupacion","Label":"Ocupación","Type":"ac","Required":"true","Hint":"Ocupacion","Rules":"vch","Length":"900","Order":"24","ReadOnly":"0","AutoComplete":"ciuo"},{"Name":"Desplazado","Label":"Desplazado","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"25","ReadOnly":"0","AutoComplete":"0"},{"Name":"Discapacitado","Label":"Discapacitado","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"26","ReadOnly":"0","AutoComplete":"0"},{"Name":"eleOrtopedico","Label":"¿Requiere uso de algún elemento ortopédico?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoProtesis","Label":"Prótesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoOrtesis","Label":"Órtesis","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoSillaRuedas","Label":"Silla de ruedas","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoCaminador","Label":"Caminador","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoMuletas","Label":"Muleta(s)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"UsoOtroElementoOrtopedico","Label":"Otro elemento ortopédico","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"NivelEst","Label":"Nivel de estudios","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"28","ReadOnly":"0","AutoComplete":"0"},{"Name":"cancer","Label":"¿Algún familiar ha padecido cáncer?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamCancer","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamCancer","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamCancer","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamCancer","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamCancer","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"hnofamCancer","Label":"Hermano(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"hipertension","Label":"¿Algún familiar ha padecido hipertensión?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamHipertencion","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamHipertencion","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamHipertencion","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamHipertencion","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamHipertencion","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"hnofamHipertencion","Label":"Hermano(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"renal","Label":"¿Algún familiar ha padecido enfermedades renales?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamRenal","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamRenal","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamRenal","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamRenal","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamRenal","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"hnofamRenal","Label":"Hermano(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"mental","Label":"¿Algún familiar ha padecido enfermedades mentales?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamMental","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamMental","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamMental","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamMental","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamMental","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"hnofamMental","Label":"Hermano(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"diabetes","Label":"¿Algún familiar ha padecido diabetes?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamDiabetes","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamDiabetes","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamDiabetes","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamDiabetes","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamDiabetes","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"hnofamDiabetes","Label":"Hermano(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"perCancer","Label":"Cáncer","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCancer","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perAzucar","Label":"Diabetes o alto nivel de azúcar en la sangre","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":""},{"Name":"TperAzucar","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perTension","Label":"Tensión arterial alta o Hipertensión","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":""},{"Name":"TperTension","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perSida","Label":"Enfermedades del sistema inmunológico, Lupus, VIH, SIDA, etc","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":""},{"Name":"TperSida","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perEnSex","Label":"Enfermedades de transmisión Sexual","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":""},{"Name":"TperEnSex","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perConv","Label":"Convulsiones","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":""},{"Name":"TperConv","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perDefV","Label":"Defectos visuales","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"7","ReadOnly":"0","AutoComplete":""},{"Name":"TperDefV","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perAsfix","Label":"Asfixia","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"8","ReadOnly":"0","AutoComplete":""},{"Name":"TperAsfix","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perMareos","Label":"Mareos","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"9","ReadOnly":"0","AutoComplete":""},{"Name":"TperMareos","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perHepa","Label":"Hepatitis","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"10","ReadOnly":"0","AutoComplete":""},{"Name":"TperHepa","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perPerPeso","Label":"Pérdida de peso","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"11","ReadOnly":"0","AutoComplete":""},{"Name":"TperPerPeso","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perColesterol","Label":"Colesterol alto","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"12","ReadOnly":"0","AutoComplete":""},{"Name":"TperColesterol","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perMigra","Label":"Dolor de cabeza fuerte (migraña)","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"13","ReadOnly":"0","AutoComplete":""},{"Name":"TperMigra","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perColumna","Label":"Problemas de columna","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"14","ReadOnly":"0","AutoComplete":""},{"Name":"TperColumna","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perInsCardia","Label":"Insuficiencia cardíaca","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"15","ReadOnly":"0","AutoComplete":""},{"Name":"TperInsCardia","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perInfarto","Label":"Infarto agudo del miocardio","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"16","ReadOnly":"0","AutoComplete":""},{"Name":"TperInfarto","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perGasUlc","Label":"Gastritis/úlcera","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"17","ReadOnly":"0","AutoComplete":""},{"Name":"TperGasUlc","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perAnemia","Label":"Anemia","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"18","ReadOnly":"0","AutoComplete":""},{"Name":"TperAnemia","Label":"Código CIE 10","Type":"ac","Required":"true","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perCirugias","Label":"¿Tiene cirugías pendientes?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"19","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perEnf1","Label":"¿Padece de alguna enfermedad actualmente?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"pCodEnf1","Label":"Código de CIE 10","Type":"ac","Required":"false","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"perMedic1_1","Label":"¿Toma algún medicamento para su enfermedad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento1_1","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee1_1","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc1_1","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi1_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"header","Label":"","Type":"tv","Required":"false","Hint":"","Rules":"0","Length":"0","Order":"0","ReadOnly":"0","AutoComplete":"0"},{"Name":"pesoNacer","Label":"¿Cuál fue el peso al nacer (En gramos)?","Type":"tf","Required":"true","Hint":"Gramos","Rules":"int","Length":"4","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"tallaNacer","Label":"¿Cuál fue la talla al nacer (En cms)?","Type":"tf","Required":"true","Hint":"cms","Rules":"int","Length":"2","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"Lactancia","Label":"¿Recibe o recibió lactancia materna exclusiva por los primeros 6 meses?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"OtraAlim","Label":"¿Recibe otro tipo de alimento?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"mesOtroAlim","Label":"¿A los cuantos meses empezó a recibir otro tipo de alimento?","Type":"tf","Required":"true","Hint":"Meses","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"contPre","Label":"¿A cuántos controles prenatales asistió la mamá del niño (a) durante el embarazo?","Type":"tf","Required":"true","Hint":"","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"suplem","Label":"¿Recibe algún tipo de refuerzo de su alimentación por vitaminas o micronutrientes?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ProSuplem","Label":"¿Quién lo provee?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"header61","Label":"","Type":"tv","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"crecDes","Label":"","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"valLen","Label":"¿desarrollo del lenguaje?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"valMot","Label":"¿desarrollo motor?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"valCon","Label":"¿conducta?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"ProVis","Label":"¿visual?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ProAud","Label":"¿auditivo?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"ProCon","Label":"¿conducta?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"Despara","Label":"¿Ha recibido tratamiento para desparasitación en el último año?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"CarVac","Label":"¿Tiene carnet de vacunación?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"VacCom","Label":"¿Tiene esquema de vacunación completo?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"bcg","Label":"BCG","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"bcgCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"AntiHepB","Label":"Antihepatitis B","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"ahBCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"AntiHepA","Label":"Antihepatitis A","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"ahACuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"dpt","Label":"DPT","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"DPTCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"antipolio","Label":"Antipolio","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"aPCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"influenza","Label":"H influenza","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"HICuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"TripleVir","Label":"Triple viral","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"8","ReadOnly":"0","AutoComplete":"0"},{"Name":"trVCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"FiebAma","Label":"Fiebre amarilla","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"9","ReadOnly":"0","AutoComplete":"0"},{"Name":"FACuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Rotavirus","Label":"Rotavirus","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"10","ReadOnly":"0","AutoComplete":"0"},{"Name":"RVCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Neumococo","Label":"Neumococo","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"11","ReadOnly":"0","AutoComplete":"0"},{"Name":"NCCCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"SRP","Label":"SRP","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"12","ReadOnly":"0","AutoComplete":"0"},{"Name":"SRPCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Pentavalente","Label":"Pentavalente","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"13","ReadOnly":"0","AutoComplete":"0"},{"Name":"PVLCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"vph","Label":"VPH","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"14","ReadOnly":"0","AutoComplete":"0"},{"Name":"VPHCuantas","Label":"¿Cual dosis hace falta?","Type":"sp","Required":"true","Hint":"0","Rules":"vch","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ConAduJov","Label":"¿Asiste al programa de control adulto joven?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ConAdu","Label":"¿Asiste al programa de control adulto?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"hatemenstruacion","Label":"¿Ha tenido alguna vez la menstruación?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Menstru","Label":"¿Actualmente tiene menstruaciones?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"EdadMens","Label":"¿A qué edad inicia la menstruación?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"EdadRet","Label":"¿A qué edad termina la menstruación?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"FchUltMens","Label":"Fecha de última menstruación","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"RelCel","Label":"¿Ha tenido relaciones sexuales?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"EdadIniRelSex","Label":"¿A qué edad inicia relaciones sexuales?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"embarazo","Label":"¿Se encuentra en embarazo?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"3","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"contEmb","Label":"¿Asiste a control de embarazo?","Type":"sp","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"CantcontEmb","Label":"¿A cuántos controles ha asistido?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"TDTTEMB","Label":"¿Ha recibido vacunación contra tétano/difteria?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"UltDosisTet","Label":"¿Cuál fue la última dosis que le aplicaron?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Rubeola","Label":"¿Ha recibido vacunación contra la rubeola o MMR?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"UltDosisRube","Label":"¿Cuál fue la última dosis que le aplicaron?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"VfiebreAmar","Label":"¿Ha recibido vacunación contra la fiebre amarilla?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"UltDosisFA","Label":"¿Cuál fue la última dosis que le aplicaron?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Planif","Label":"¿Tiene usted o ha tenido algún método de planificación familiar?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"MedPlan","Label":"¿Qué método de planificación familiar utiliza?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ProvPlan","Label":"¿Quién le provee el método de planificación?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijos","Label":"¿Tiene hijos o ha tenido hijos?","Type":"sp","Required":"true","Hint":"0","Rules":"","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"numHijos","Label":"¿Cuántos hijos tiene?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijos","Label":"¿Dónde nacieron su(s) hijo(s)?","Type":"tv","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosHosp","Label":"Hospital","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosHospCant","Label":"¿Cuantos?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosCasa","Label":"Casa","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosCasaCant","Label":"¿Cuantos?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosOtro","Label":"Otro","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"NacHijosOtroCant","Label":"¿Cuantos?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"abortos","Label":"¿Ha tenido abortos?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"Numabortos","Label":"¿Cuántos abortos ha tenido?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"2","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"citologias","Label":"¿Le han realizado citología cervico vaginal en el último año?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"vphEsp","Label":"¿Se ha aplicado la vacuna contra el virus del papiloma humano?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"uldosapVhpEsp","Label":"Última dosis aplicada","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"Terminarvph","Label":"¿Piensa terminar la aplicación de la vacuna?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"ExamMama","Label":"¿Se ha realizado o le han realizado examen de mama?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"26","ReadOnly":"0","AutoComplete":"0"},{"Name":"ComoExamMama","Label":"¿Sabe cómo realizarse el examen de mama?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"prostata","Label":"¿Se ha realizado el examen de la próstata?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"FechProstata","Label":"¿Cuándo fue?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"peso","Label":"Peso (Kg)","Type":"tf","Required":"true","Hint":"Kg","Rules":"dec","Length":"5","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"talla","Label":"Talla (cm)","Type":"tf","Required":"true","Hint":"cms","Rules":"int","Length":"3","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"TAS","Label":"Tensión arterial sistólica","Type":"tf","Required":"true","Hint":"SS","Rules":"int","Length":"3","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"TAD","Label":"Tensión arterial diastólica","Type":"tf","Required":"true","Hint":"DD","Rules":"int","Length":"3","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"PerAbd","Label":"Perímetro abdominal (cms)","Type":"tf","Required":"true","Hint":"DD","Rules":"int","Length":"3","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"Oximetria","Label":"Oximetría (%)","Type":"tf","Required":"true","Hint":"%","Rules":"int","Length":"2","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"Odonto","Label":"¿Ha asistido a consulta odontológica en los últimos 6 meses?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"placa","Label":"¿Le han realizado control de placa?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"caries","Label":"¿Ha presentado caries?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"cepillado","Label":"¿Cuántas veces se cepilla al día?","Type":"tf","Required":"true","Hint":"0","Rules":"int","Length":"1","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"maltrato","Label":"¿Observa usted maltrato en esta persona?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"consumos","Label":"Normalmente en casa hay","Type":"tv","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"30","ReadOnly":"0","AutoComplete":"0"},{"Name":"consualcohol","Label":"Consumo de alcohol","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"31","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamAlcohol","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamAlcohol","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamAlcohol","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamAlcohol","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamAlcohol","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"consucigarrilo","Label":"Consumo de cigarrillo","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"32","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamCigarrillo","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamCigarrillo","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamCigarrillo","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamCigarrillo","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamCigarrillo","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"consususpsico","Label":"Consumo de sustancias psicotrópicas","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"33","ReadOnly":"0","AutoComplete":"0"},{"Name":"madrefamSusPsico","Label":"Madre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"padrefamSusPsico","Label":"Padre","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"abuefamSusPsico","Label":"Abuelo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"hijofamSusPsico","Label":"Hijo(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"tiofamSusPsico","Label":"Tio(a)","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"vivienda","Label":"Este hogar vive en","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"servicios","Label":"La unidad de vivienda cuenta con servicios públicos de","Type":"tv","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"serEneelectrica","Label":"Energía Eléctrica","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"serAlcantarill","Label":"Alcantarillado","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"serGasNatD","Label":"Gas natural domiciliario","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"serTelefono","Label":"Teléfono","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"serRecoBasu","Label":"Recolección de basura","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"7","ReadOnly":"0","AutoComplete":"0"},{"Name":"serAcueducto","Label":"Acueducto","Type":"cb","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"8","ReadOnly":"0","AutoComplete":"0"},{"Name":"dormitorios","Label":"¿Cuántos dormitorios?","Type":"tf","Required":"true","Hint":"¿Cuantos dormitorios?","Rules":"int","Length":"2","Order":"9","ReadOnly":"0","AutoComplete":"0"},{"Name":"animales","Label":"¿Hay presencia de animales?","Type":"tv","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"11","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanPerro","Label":"Perro","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"12","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanGato","Label":"Gato","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"13","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanEquino","Label":"Equino","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"14","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanAves","Label":"Aves","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"15","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanPorcino","Label":"Porcinos","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"16","ReadOnly":"0","AutoComplete":"0"},{"Name":"preanOtros","Label":"Otros","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"17","ReadOnly":"0","AutoComplete":"0"},{"Name":"origenAgua","Label":"El agua para consumo la obtienen principalmente de:","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"18","ReadOnly":"0","AutoComplete":"0"},{"Name":"ConsumoAgua","Label":"El agua que consume es","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"19","ReadOnly":"0","AutoComplete":"0"},{"Name":"Basura","Label":"Como eliminan principalmente la basura en esta unidad de vivienda","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"20","ReadOnly":"0","AutoComplete":"0"},{"Name":"techo","Label":"Estado del techo","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"21","ReadOnly":"0","AutoComplete":"0"},{"Name":"paredes","Label":"Estado de las paredes","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"22","ReadOnly":"0","AutoComplete":"0"},{"Name":"iluminaSuf","Label":"¿Hay iluminación suficiente?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"23","ReadOnly":"0","AutoComplete":"0"},{"Name":"aguasNegras","Label":"¿Hay presencia de aguas negras?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"24","ReadOnly":"0","AutoComplete":"0"},{"Name":"roedores","Label":"¿Hay presencia de roedores?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"25","ReadOnly":"0","AutoComplete":"0"},{"Name":"MatParedes","Label":"Material predominante en las paredes","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"26","ReadOnly":"0","AutoComplete":"0"},{"Name":"MatPiso","Label":"Material predominante en el piso","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"27","ReadOnly":"0","AutoComplete":"0"},{"Name":"MatTecho","Label":"Material predominante en el techo","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"28","ReadOnly":"0","AutoComplete":"0"},{"Name":"Alumbrado","Label":"¿Qué tipo de alumbrado utiliza?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"29","ReadOnly":"0","AutoComplete":"0"},{"Name":"ducha","Label":"¿La vivienda posee ducha?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"30","ReadOnly":"0","AutoComplete":"0"},{"Name":"letrina","Label":"¿La vivienda posee letrina?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"31","ReadOnly":"0","AutoComplete":"0"},{"Name":"codedor","Label":"¿La vivienda posee cocina dentro de dormitorio?","Type":"rg","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"32","ReadOnly":"0","AutoComplete":"0"},{"Name":"atencion","Label":"¿De qué manera lo atendieron en la EPS?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"respuesta","Label":"¿Cómo fue la respuesta a la atención?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"atencionPersonal","Label":"¿Cómo ha sido la atención por parte del personal de la EPS?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"InfServicio","Label":"¿Cómo es la información para solicitar un servicio?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"Instalaciones","Label":"¿Cómo son las instalaciones físicas de la entidad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"5","ReadOnly":"0","AutoComplete":"0"},{"Name":"AtGeneral","Label":"¿En general, Cómo cree que ha sido la atención?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"6","ReadOnly":"0","AutoComplete":"0"},{"Name":"VisionEPS","Label":"Usted considera que la EPS-S es:","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"7","ReadOnly":"0","AutoComplete":"0"},{"Name":"pEnf2","Label":"¿Padece de otra enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"pCodEnf2","Label":"Código de CIE 10","Type":"ac","Required":"false","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"pMedic2","Label":"¿Toma algún medicamento para su enfermedad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi2_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"pEnf3","Label":"¿Padece de otra enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"pCodEnf3","Label":"Código de CIE 10","Type":"ac","Required":"false","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"pMedic3","Label":"¿Toma algún medicamento para su enfermedad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi3_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"pEnf4","Label":"¿Padece de otra enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"pCodEnf4","Label":"Código de CIE 10","Type":"ac","Required":"false","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"pMedic4","Label":"¿Toma algún medicamento para su enfermedad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi4_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"pEnf5","Label":"¿Padece de otra enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"pCodEnf5","Label":"Código de CIE 10","Type":"ac","Required":"false","Hint":"Código CIE 10","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"diseases"},{"Name":"pMedic5","Label":"¿Toma algún medicamento para su enfermedad?","Type":"sp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento5","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee5","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc5","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi5_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic1_2","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento1_2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee1_2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc1_2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi1_2","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic1_3","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento1_3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee1_3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc1_3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi1_3","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic1_4","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento1_4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee1_4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc1_4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi1_4","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic1_5","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento1_5","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee1_5","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc1_5","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi1_5","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic2_1","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento2_1","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee2_1","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc2_1","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi2_1","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic2_2","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento2_2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee2_2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc2_2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi2_2","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic2_3","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento2_3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee2_3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc2_3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi2_3","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic2_4","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento2_4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee2_4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc2_4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi2_4","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic3_2","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento3_2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee3_2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc3_2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi3_2","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic3_3","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento3_3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee3_3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc3_3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi3_3","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic3_4","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento3_4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee3_4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc3_4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi3_4","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic3_5","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento3_5","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee3_5","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc3_5","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi3_5","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic4_2","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento4_2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee4_2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc4_2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi4_2","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic4_3","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento4_3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee4_3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc4_3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi4_3","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic4_4","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento4_4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee4_4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc4_4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi4_4","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic4_5","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento4_5","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee4_5","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc4_5","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi4_5","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic5_2","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento5_2","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee5_2","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc5_2","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi5_2","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic5_3","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento5_3","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee5_3","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc5_3","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi5_3","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic5_4","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento5_4","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee5_4","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc5_4","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi5_4","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perMedic5_5","Label":"¿Toma otro medicamento para esta enfermedad?","Type":"cb","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"0"},{"Name":"codMedicamento5_5","Label":"Código medicamento","Type":"ac","Required":"false","Hint":"Código medicamento","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"medicaments"},{"Name":"perProvee5_5","Label":"¿Quién se lo provee?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"2","ReadOnly":"0","AutoComplete":"0"},{"Name":"perEvoluc5_5","Label":"¿Cómo siente su evolución con este tratamiento?","Type":"sp","Required":"false","Hint":"0","Rules":"0","Length":"0","Order":"3","ReadOnly":"0","AutoComplete":"0"},{"Name":"iniTomaMedi5_5","Label":"¿Cuando empezó a tomar el medicamento?","Type":"dp","Required":"true","Hint":"0","Rules":"0","Length":"0","Order":"4","ReadOnly":"0","AutoComplete":"0"},{"Name":"perCirugias_2","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_2","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_3","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_3","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_4","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_4","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_5","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_5","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_6","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_6","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_7","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_7","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_8","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_8","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_9","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_9","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"},{"Name":"perCirugias_10","Label":"¿Tiene otra cirugía pendiente?","Type":"rg","Required":"true","Hint":"","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":""},{"Name":"TperCirugias_10","Label":"Descripcion de la cirugía","Type":"ac","Required":"true","Hint":"Descripcion de la cirugía","Rules":"0","Length":"0","Order":"1","ReadOnly":"0","AutoComplete":"cups"}],"options":[{"Field":["3"],"Options":["-","Rural","Urbana"]},{"Field":["4"],"Options":["-","Barrio","Caserío","Corregimiento","Vereda"]},{"Field":["9"],"Options":["Sin sisben asociado","Nivel I","Nivel II","Nivel III","Nivel IV"]},{"Field":["8"],"Options":["-","Menor sin identificación","Adulto sin identificacion","Registro civil","Tarjeta de identidad","Cédula de ciudadanía","Cédula de extranjería","Pasaporte"]},{"Field":["15"],"Options":["-","Femenino","Masculino"]},{"Field":["16"],"Options":["-","Jefe de familia","Cónyuge","Hijo (a)","Otro pariente","Otro no pariente"]},{"Field":["17"],"Options":["-","Ninguno","Contributivo","Subsidiado"]},{"Field":["18"],"Options":["-","Beneficiario","Cotizante","Sin afiliación"]},{"Field":["19"],"Options":["-","Blanca","Afrocolombiana","Indígena","Mestiza","ROM (Gitanos)","Raizal"]},{"Field":["20"],"Options":["-","COMFASUCRE","OTRA"]},{"Field":["22","24","25","29","31","33","35","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","57","59","63","66","67","69","71","72","73","74","75","76","77","78","79","80","95","96","97","100","102","103","105","107","109","113","118","121","124","126","127","129","130","131","132","139","140","141","143","146","147","148","149","522","523","524","172","189","190","191","192","193","194","195","196","197","198","199","200","201","202","203","204","205","206","207","229","235","241","247","331","333","335","337","339","341","343","345","347","529","530","531"],"Options":["-","SI","NO"]},{"Field":["27"],"Options":["-","Ninguno","Primaria","primaria incompleta","Secundaria o Bachillerato","Secundaria incompleta","Técnica o tecnológica","Técnica o tecnólogica incompleta","Superior o Universitaria","Universitaria incompleta","Postgrado"]},{"Field":["28","30","32","34","36"],"Options":["-","Abuelo","Abuela","Tío(a)","Madre","Padre","Hermano (a)","Hijo(a)"]},{"Field":["61","231","237","243","70","120","249","253","257","261","265","269","273","277","281","285","289","293","297","301","305","309","313","317","321","325","329"],"Options":["-","EPS","Ente territorial","Los compra usted"]},{"Field":["62","232","238","244","250","254","258","262","266","270","274","278","282","286","290","294","298","302","306","310","314","318","322","326","330"],"Options":["-","Buena","Regular","Mala"]},{"Field":["119"],"Options":["-","Orales (tableta, grageas, comprimidos)","Inyecciones","Implante sub dérmico","Método de barrera (condones, diafragma, etc.)","Método naturales (ritmo, temperatura, moco, coito interrumpido)","Quirúrgico"]},{"Field":["123"],"Options":["-","Hospital","Casa","Otro"]},{"Field":["500"],"Options":["-","1 Arriendo","2 Propia pagando","3 Propia pagada","4 Otra condición"]},{"Field":["517"],"Options":["-","1 Acueducto","2 Pozo con bomba","3 Pozo sin bomba, jagüey","4 Agua lluvia","5 Rio, quebrada, manantial, nacimiento","6 Pila pública","7 Carrotanque","8 Aguatero","9 Donacion"]},{"Field":["518"],"Options":["-","Hervida","Clorada","Filtrada","Ninguno"]},{"Field":["519"],"Options":["-","1 La recogen los servicios de aseo","2 La entierran","3 La queman","4 La tiran al patio, lote, zanja o baldío","5 La tiran al río, caño, quebrada, laguna","6 La eliminan de otra forma"]},{"Field":["521","520"],"Options":["-","Bueno","Regular","Malo"]},{"Field":["106","108","110","176","177","178","179","180","181","182","183","184","185","186","187","188"],"Options":["-","Dosis 1","Dosis 2","Dosis 3","Dosis 4","Dosis 5"]},{"Field":["128"],"Options":["-","Dosis 1","Dosis 2","Dosis 3"]},{"Field":["525"],"Options":["-","1 Bloque, ladrillo, piedra, madera pulida","2 Tapia pisada, adobe","3 Bahareque","4 Material prefabricado","5 Madera burda, tabla, tablón","6 Guadua, caña, esterilla, otro vegetal","7 Zinc, tela, cartón, latas, desechos, plásticos","8 Sin paredes"]},{"Field":["526"],"Options":["-","1 Alfombra o tapete, mármol, parqué, madera pulida y lacada","2 Baldosa, vinilo, tableta o ladrillo","3 Cemento o gravilla","4 Madera burda, madera en mal estado, tabla o tablón","5 Tierra o arena","0 Otro"]},{"Field":["527"],"Options":["-","Desechos: Cartón, Lata, Sacos","Paja o Palma","Teja de barro, zinc, cemento, Sin Cielo Raso Losa o Plancha, Asbesto, Cemento con cielo Raso"]},{"Field":["528"],"Options":["-","Vela","Kerosene","Petróleo","Gasolina","Eléctrica"]},{"Field":["600","601"],"Options":["-","Muy fácil","Fácil","Regular","Difícil","Muy difícil"]},{"Field":["602","605","606"],"Options":["-","Muy buena","Buena","Regular","Mala","Muy mala"]},{"Field":["603"],"Options":["-","Suficiente","Adecuada","Limitada","Muy limitada","No le dieron información"]},{"Field":["604"],"Options":["-","Muy buenas","Buenas","Regulares","Malas","Muy malas"]}]}');
	var tbTmp = document.getElementById(id);
	tbTmp.innerHTML = "";
	var tagged = importablePoll.fields_structure;
	var importedData = JSON.parse("[]");
	for(var index in importablePoll.fields_structure) {
		if(tagged[index].imported == "1"){

		} else {
			var trTmp = document.createElement("TR");
			var tdTmp = document.createElement("TD");
			divTmp = document.createElement("DIV");

			tbTmp.appendChild(trTmp);
			trTmp.appendChild(tdTmp);
			divTmp.className = "d_description";

			tdTmp.innerHTML = importablePoll.fields_structure[index].Label;
			divTmp.innerHTML = "Name: "+importablePoll.fields_structure[index].Name;
			tdTmp.appendChild(divTmp);
			tdTmp.id = index;

			$(tdTmp).unbind();
			$(tdTmp).bind("click", function() {
				var dom = $(this);
				$(divTmp1).animate({opacity: 0, "padding-left": "+=50"},150, function() {
					divTmp1.innerHTML = capitalizeFirstLetter(_NOMBRE)+": "+importablePoll.fields_structure[dom.context.id].Name;
					$(divTmp1).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp2).animate({opacity: 0, "padding-left": "+=50"},175, function() {
					divTmp2.innerHTML = capitalizeFirstLetter(_ETIQUETA)+": "+importablePoll.fields_structure[dom.context.id].Label;
					$(divTmp2).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp3).animate({opacity: 0, "padding-left": "+=50"},200, function() {
					divTmp3.innerHTML = capitalizeFirstLetter(_TIPO)+": "+importablePoll.fields_structure[dom.context.id].Type;
					$(divTmp3).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp4).animate({opacity: 0, "padding-left": "+=50"},225, function() {
					divTmp4.innerHTML = capitalizeFirstLetter(_OBLIGATORIO)+": "+((importablePoll.fields_structure[dom.context.id].Required == "true") ? capitalizeFirstLetter(_SI):capitalizeFirstLetter(_NO));
					$(divTmp4).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp5).animate({opacity: 0, "padding-left": "+=50"},250, function() {
					divTmp5.innerHTML = capitalizeFirstLetter(_DESCRIPCION)+": "+((importablePoll.fields_structure[dom.context.id].Hint == "0") ? capitalizeFirstLetter(_NO+" "+_APLICA): importablePoll.fields_structure[dom.context.id].Hint);
					$(divTmp5).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp6).animate({opacity: 0, "padding-left": "+=50"},275, function() {
					divTmp6.innerHTML = capitalizeFirstLetter(_REGLAS)+": "+((importablePoll.fields_structure[dom.context.id].Rules == "0") ? capitalizeFirstLetter(_NO+" "+_APLICA):importablePoll.fields_structure[dom.context.id].Rules);
					$(divTmp6).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp7).animate({opacity: 0, "padding-left": "+=50"},300, function() {
					divTmp7.innerHTML = capitalizeFirstLetter(_LONGITUD)+": "+((importablePoll.fields_structure[dom.context.id].Length == "0") ? capitalizeFirstLetter(_NO+" "+_APLICA):importablePoll.fields_structure[dom.context.id].Length);
					$(divTmp7).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp8).animate({opacity: 0, "padding-left": "+=50"},325, function() {
					divTmp8.innerHTML = capitalizeFirstLetter(_MODIFICABLE)+": "+((importablePoll.fields_structure[dom.context.id].ReadOnly == "true") ? capitalizeFirstLetter(_SI):capitalizeFirstLetter(_NO));
					$(divTmp8).animate({opacity: 1, "padding-left": "0"},500);
				});
				$(divTmp9).animate({opacity: 0, "padding-left": "+=50"},375, function() {
					divTmp9.innerHTML = capitalizeFirstLetter(_AUTOCOMPLETADO)+": "+((importablePoll.fields_structure[dom.context.id].AutoComplete == "0") ? capitalizeFirstLetter(_NO+" "+_APLICA):importablePoll.fields_structure[dom.context.id].AutoComplete);
					$(divTmp9).animate({opacity: 1, "padding-left": "0"},500);
					btnTmp.style.display = "block";
				});
				$(btnTmp).unbind();
				$(btnTmp).bind("click", function() {
					importedData.push(tagged[dom.context.id]);
					tagged[dom.context.id].imported = "1";
					dom.context.className = "td_disabled";
					$(dom.context).unbind();
					dom.context.innerHTML += _IMPORTADO.toUpperCase();
				});
			});
		}
	}
	$("#bt_alert_dialog_cancel").val(capitalizeFirstLetter(_ACEPTAR));
	$("#bt_alert_dialog_cancel").unbind();
	$("#bt_alert_dialog_cancel").bind("click", function() {
		for(var index in importedData) {
			var field = importedData[index];
			$("#it_fName").val(field.Name);
			$("#it_fLabel").val(field.Label);
			$('#se_oType option[value="'+field.Type+'"]').attr('selected','selected');
			((field.Required == "true") ? $("#it_fRequired").attr("checked") : $("#it_fRequired").removeAttr("checked"));
			$("#it_fHint").val(((field.Hint != "0") ? field.Hint : ""));
			$('#se_oRules option[value="'+field.Rules+'"]').attr('selected','selected');
			$("#it_fLength").val(((field.Length != "0") ? field.Length : ""));
			((field.ReadOnly == "true") ? $("#it_fReadOnly").attr("checked") : $("#it_fReadOnly").removeAttr("checked"));
			$("#it_fAutocomplete").val(((field.AutoComplete != "0") ? field.AutoComplete : ""));
			$("#se_oType").change();
			$("#bt_save_field").click();
			if (index == (importedData.length-1)) {
				$( "#alert_dialog_bk" ).animate({
				    opacity: 0
				},500, function(){$( "#alert_dialog_bk" ).css("display", "none");});
				$( "#alert_dialog_fs" ).animate({
				    opacity: 0
				},500, function() {
					$( "#alert_dialog_fs" ).css("display", "none");
					document.getElementById("alert_dialog_fs").innerHTML = selected.content;
				});
			}
		}
	});
}

function createGroupItemsAction(tdTmp) {
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		//var count = idTmp.split("_");
		var tdTmpTxt = $(this).text();
		selected.group = idTmp;
		try {
			$("#it_nGrupo").val(groups.forms[idTmp].Name);
			$("#it_tGrupo").val(groups.forms[idTmp].Header);
		} catch(Error){
			console.log(Error)
		}
		isEddit = true;
		editName = (selected.group);
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;

	    document.getElementById("td_context_select").style.display = "none";
	    document.getElementById("td_context_edit").style.display = "block";
	    document.getElementById("td_context_delete").style.display = "block";

	    document.getElementById("td_context_delete").lang = tdTmp.id;
	    document.getElementById("td_context_edit").lang = tdTmp.id;
	    $("#td_context_edit").unbind();
	    $("#td_context_edit").bind("click", function(){
	    	var dom = $(this);
	    	$(tdTmp).click();
	    });
	    $("#td_context_delete").unbind();
	    $("#td_context_delete").bind("click", function(){
	    	var dom = $(this);
	    	idgroup = groups.forms[dom.context.lang].Id;
	    	for(idx in groups.fields_structure){
	    		if(groups.fields_structure[idx].Form == idgroup){
	    			var pregunta = groups.fields_structure[idx].Id;
	    			for(indice in groups.options){
						if(groups.options[indice].Field == pregunta){
							delete groups.options[indice];
							groups.options.splice(indice, 1);
						}
					}
					delete groups.fields_structure[idx];
	    		}
	    	}
	    	cleanArray(groups.fields_structure);
	    	delete groups.forms[dom.context.lang];
	    	groups.forms.splice(dom.context.lang, 1);
			try {
				fillGroups('left_resumer_table_g', 0);
		    } catch(Exception){}
	    })
	}, false);
}

function cleanArray(array){
  	var newArray = new Array();
  	for( var i = 0, j = array.length; i < j; i++ ){
      	if (array[i]){
        	newArray.push( array[ i ] );
    	}
  	}
  	groups.fields_structure = newArray;
}

function createFieldItemAction(tdTmp) {
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		selected.field = idTmp;
		if (action == 4) {
			fillGroups("right_listener_table_f", 3);
		}
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "none";
	    if(action == 3) document.getElementById("td_context_delete").style.display = "block";
    	else if(action == 3) document.getElementById("td_context_delete").style.display = "block";

	    document.getElementById("td_context_select").lang = tdTmp.id;
	    document.getElementById("td_context_delete").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	var dom = $(this);
	    	$(tdTmp).click();
	    	//console.log(dom.context.lang);
	    });
	    $("#td_context_delete").unbind();
	    $("#td_context_delete").bind("click", function(){
	    	var dom = $(this);
	    	var idTmp = $(dom).attr('lang');
	    	if(groups.fields_structure[idTmp].Handler == 0){
	    		alert('La pregunta: '+groups.fields_structure[idTmp].Label+' no cuenta con una relación para eliminar');
	    		//$(tdTmp).click();
	    	}else{
	    		for(idx in groups.forms){
	    			if(groups.fields_structure[idTmp].Id == groups.forms[idx].Parent){
	    				groups.forms[idx].Parent = "0";
	    				groups.forms[idx].Inside = "0";
	    				alert('Se ha eliminado la relación de la pregunta: '+groups.fields_structure[idTmp].Label+' con el grupo: '+groups.forms[idx].Name);
	    			}
	    		}	
	    		groups.fields_structure[idTmp].Handler = "0";
		    	selected.field = undefined;
		    	fillFields(selected.group);
	    	}
	    	$(tdTmp).click();
	    	document.getElementById("right_listener_table_f").innerHTML = "";
	    });
	}, false);
}

function createEventItemAction(tdTmp) {
	console
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		groups.fields_structure[selected.field].Handler = groups.handler_event[idTmp].Id;
		groups.forms[selected.group2].Parent = groups.fields_structure[selected.field].Id;
		groups.forms[selected.group2].Inside = "1";
		selected.group = undefined;
		selected.field = undefined;
		selected.group2 = undefined;
		selected.event = undefined;
		$( "#events_creator_editor" ).animate({
				    opacity: 0,
				    left: "+=250"
				  }, 400, function(){
				  	document.getElementById("events_creator_editor").style.display = "none";
				  	document.getElementById("events_selector_actions").style.display = "block";
				  	$( "#events_selector_actions" ).animate({
					    opacity: 0,
					    left: "-=250"
					},0, function() {
					  	$( "#events_selector_actions" ).animate({
						    opacity: 1,
						    left: "+=250"
						  }, 400, function() {
						  	$( "#events_creator_editor" ).animate({
							    opacity: 0,
							    left: "-=250"
							  }, 0, function () {
							  	fillGroups("left_listener_table_f", 2);
							  });
						  });
					});
				});
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "block";
    	document.getElementById("td_context_delete").style.display = "block";

	    document.getElementById("td_context_select").lang = tdTmp.id;
	    document.getElementById("td_context_edit").lang = tdTmp.id;
	    document.getElementById("td_context_delete").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	var dom = $(this);
	    	$(tdTmp).click();
	    });
	    $("#td_context_edit").unbind();
	    $("#td_context_edit").bind("click", function(){
	    	var dom = $(this);
	    	var idTmp = $(dom).attr('lang');
	    	selected.event = idTmp;
	    	for (index in groups.handler_event[idTmp].Types) {
	    		$('#se_handler_type option[value="'+groups.handler_event[idTmp].Types[index]+'"]').attr('selected','selected');
	    		$("#it_handler_value").val(groups.handler_event[idTmp].Parameters[index]);
	    		$("#bt_append_handler").click();
	    	}
	    });
	    $("#td_context_delete").unbind();
	    $("#td_context_delete").bind("click", function(){
	    	var dom = $(this);
	    	var idTmp = $(dom).attr('lang');
	    	delete(groups.handler_event[idTmp]);
	    	groups.handler_event.splice(idTmp, 1);
	    	fillEvents();
	    });
	}, false);
}

function createGroupForFieldItemAction(tdTmp) {
	document.getElementById("right_creator_editor").innerHTML = "";
	selected.group = undefined;
	selected.field = undefined;
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		selected.group = idTmp;
		//fillFields(idTmp);
		fillFields2(idTmp);
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "none";
	    document.getElementById("td_context_delete").style.display = "none";
	    document.getElementById("td_context_select").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	var dom = $(this);
	    	$("#"+dom.context.lang).click();
	    });
	}, false);
}

function createGroupForEventItemAction(tdTmp) {
	selected.group = undefined;
	selected.field = undefined;
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		selected.group = idTmp;
		selected.group = idTmp;
		fillFields(idTmp);
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "none";
	    document.getElementById("td_context_delete").style.display = "none";
	    document.getElementById("td_context_select").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	//var dom = $(this);
	    	$(tdTmp).click();
	    });
	}, false);
}

function createOptionsForOptionItemAction(tdTmp) {
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		var idTmp = $(this).attr('id');
		selected.activeoption = idTmp;
		$("#bt_alert_dialog_cancel").click();
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "block";
	    document.getElementById("td_context_delete").style.display = "none";
	    document.getElementById("td_context_select").lang = tdTmp.id;
	    document.getElementById("td_context_edit").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	var dom = $(this);
	    	$(tdTmp).click();
	    });
	    $("#td_context_edit").unbind();
	    $("#td_context_edit").bind("click", function(){
	    	var dom = $(this);
	    	var idTmp = $(dom).attr('lang');
	    	selected.option = idTmp;
	    	document.getElementById("t_option_lister").innerHTML = "";
	    	option_c = JSON.parse("{}");
	    	for (var index in groups.options[idTmp].Options) {
	    		$("#it_name_option").val(groups.options[idTmp].Options[index]);
	    		$("#bt_append_option").click();
	    	}

	    	option_c.Id = groups.options[idTmp].Id;
	    	option_c.Field = groups.options[idTmp].Field;
	    });
	}, false);
}

function createGroupForEvent2ItemAction(tdTmp) {
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		document.getElementById("right_listener_table_f").innerHTML = "";
		var idTmp = $(this).attr('id');
		if (confirm(inf_conf_rel_cam_gru+" "+_DE+" "+_LA+" "+_CAMPO+": "+
			groups.fields_structure[selected.field].Label+" "+_CON+
			" "+_EL+" "+_GRUPO+": "+groups.forms[idTmp].Name)) {
			selected.group2 = idTmp;
			document.getElementById("l_relation_event_description").innerHTML = (capitalizeFirstLetter(_RELACION+"::"+_CAMPO+": "+
							groups.fields_structure[selected.field].Label+" → "+_GRUPO+": "+groups.forms[idTmp].Name));
			$( "#events_selector_actions" ).animate({
			    opacity: 0,
			    left: "-=250"
			  }, 400, function(){
			  	document.getElementById("events_selector_actions").style.display = "none";
			  	document.getElementById("events_creator_editor").style.display = "block";
			  	$( "#events_creator_editor" ).animate({
				    opacity: 0,
				    left: "+=250"
				},0, function() {
				  	$( "#events_creator_editor" ).animate({
					    opacity: 1,
					    left: "-=250"
					  }, 400, function() {
					  	$( "#events_selector_actions" ).animate({
						    left: "+=250"
						}, 0, fillEvents());
					  });
				});
			});

		} else {
			selected.group2 = undefined;
		}
	});
	tdTmp.addEventListener('contextmenu', function(e) {
		document.getElementById("rmenu").className = "show";  
	    document.getElementById("rmenu").style.top =  mouseY(event) + 'px';
	    document.getElementById("rmenu").style.left = mouseX(event) + 'px';
	    window.event.returnValue = false;
	    document.getElementById("td_context_select").style.display = "block";
	    document.getElementById("td_context_edit").style.display = "none";
	    document.getElementById("td_context_delete").style.display = "none";
	    document.getElementById("td_context_select").lang = tdTmp.id;
	    $("#td_context_select").unbind();
	    $("#td_context_select").bind("click", function(){
	    	var dom = $(this);
	    	$(tdTmp).click();
	    });
	}, false);
}

function createGroupForGeneralAction(tdTmp) {
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		if(confirm(inf_acc_irr+".\n"+
			(inf_dec_hac_gen.replace($_GRUPO, groups.forms[tdTmp.id].Header.toUpperCase())))) {
				var idTmp = groups.forms[tdTmp.id].Id;
				var index2 = 0;
				for(var index in groups.fields_structure) {
					if ((groups.fields_structure[index].Form == idTmp)){
						groups.fields_structure[index].Form = "0";
						index2++;
					}
				}
				if(index2 == 0) {
					alert("La acción no se ha podido completar.\nPor favor crear las preguntas para cada grupo.");
				} else {
					groups.forms[tdTmp.id].Id = "0";
					document.getElementById("r_alert_dialog_content").innerHTML = selected.content;
					$("#bt_alert_dialog_cancel").click();
					selected.general = tdTmp.id;
					write(action);
				}
		}
	});
}

function createGroupForSecondaryAction(tdTmp) {
	console.log(tdTmp.id);
	$(tdTmp).unbind();
	$(tdTmp).bind("click", function() {
		if(confirm(inf_acc_irr+".\n"+
			(inf_dec_hac_sec.replace($_GRUPO, groups.forms[tdTmp.id].Header.toUpperCase())))) {
			for(var index in groups.fields_structure) {
				if ((groups.fields_structure[index].Form == "0")){
					groups.forms[tdTmp.id].Parent = groups.fields_structure[index].Id;
				}
			}
			groups.forms[tdTmp.id].Inside = "1";
			document.getElementById("r_alert_dialog_content").innerHTML = selected.content;
			$("#bt_alert_dialog_cancel").click();
			selected.secondary = tdTmp.id;
			write(action);
		}
	});
}

function createActionsForField() {
	$( "#bt_create_field" ).unbind();
	$( "#bt_update_field" ).unbind();
	$( "#bt_import_field" ).unbind();
	$( "#bt_save_field" ).unbind();
	$( "#se_oType" ).unbind();

	$("#it_fLength").attr("disabled", "disabled");
	$("#it_fHint").attr("disabled", "disabled");
	$("#it_fAutocomplete").attr("disabled", "disabled");
	$("#se_oRules").attr("disabled", "disabled");

	$("#se_oType").bind("change", function() {
		switch($("#se_oType").val()) {
			case 'dp':
			//modificada cambio cb por tv
			case 'tv':
				$("#it_fLength").attr("disabled", "disabled");
				$("#it_fHint").attr("disabled", "disabled");
				$("#it_fAutocomplete").attr("disabled", "disabled");
				$("#se_oRules").attr("disabled", "disabled");
			break
			case 'rg':
			case 'sp':
				$("#it_fLength").attr("disabled", "disabled");
				$("#it_fHint").attr("disabled", "disabled");
				$("#it_fAutocomplete").attr("disabled", "disabled");
				$("#se_oRules").attr("disabled", "disabled");

				$( "#alert_dialog_bk" ).css("display", "block");
				$( "#alert_dialog_bk" ).animate({
				    opacity: 0.5
				},500);
				$( "#alert_dialog_fs" ).css("display", "block");
				$( "#alert_dialog_fs" ).animate({
				    opacity: 1
				},500, fillOptions());
			break;
			default:
				$("#it_fLength").removeAttr("disabled", "disabled");
				$("#it_fHint").removeAttr("disabled", "disabled");
				$("#it_fAutocomplete").removeAttr("disabled", "disabled");
				$("#se_oRules").removeAttr("disabled", "disabled");
			break;
		}
	});

	$("#se_oType").change();

	$("#bt_create_field").bind("click", function() {
		selected.field = tmpField;
		$( "#fields_selector_actions" ).animate({
		    opacity: 0,
		    left: "-=250"
		  }, 400, function(){
		  	document.getElementById("fields_selector_actions").style.display = "none";
		  	document.getElementById("fields_creator_editor").style.display = "block";
		  	$( "#fields_creator_editor" ).animate({
			    opacity: 0,
			    left: "+=250"
			},0, function() {
			  	$( "#fields_creator_editor" ).animate({
				    opacity: 1,
				    left: "-=250"
				  }, 400, function() {
				  	$( "#fields_selector_actions" ).animate({
					    left: "+=250"
					}, 0);
					started_f = true;
				  });
			});
		});
	});	

	$("#bt_update_field").bind("click", function() {
		if(selected.field != undefined) {
			tmpField = selected.field;
			var field = groups.fields_structure[selected.field];
			$("#it_fName").val(field.Name);
			$("#it_fLabel").val(field.Label);
			$('#se_oType option[value="'+field.Type+'"]').attr('selected','selected');
			((field.Required == "true") ? $("#it_fRequired").attr("checked") : $("#it_fRequired").removeAttr("checked"));
			$("#it_fHint").val(((field.Hint != "0") ? field.Hint : ""));
			$('#se_oRules option[value="'+field.Rules+'"]').attr('selected','selected');
			$("#it_fLength").val(((field.Length != "0") ? field.Length : ""));
			$("#it_fReadOnly").attr(((field.ReadOnly == "true") ? "checked" : "unchecked"));
			$("#it_fAutocomplete").val(((field.AutoComplete != "0") ? field.AutoComplete : ""));
			$("#se_oType").change();
			$("#bt_create_field").click();
		} else {
			alert(capitalizeFirstLetter(inf_par_mod_sel+" "+_UNA+" "+_CAMPO));
		}
	});

	$("#bt_import_field").bind("click", function() {
		selected.content = document.getElementById("alert_dialog_fs").innerHTML;
		document.getElementById("r_alert_dialog_content").innerHTML = "";
		$( "#alert_dialog_bk" ).css("display", "block");
		$( "#alert_dialog_fs" ).css("display", "block");
		$( "#alert_dialog_bk" ).animate({
		    opacity: 0.5
		},500);
		$( "#alert_dialog_fs" ).animate({
		    opacity: 1
		},500, function() {
			var tbTmp = document.createElement("TABLE");
			tbTmp.id = "lContentTable";
			tbTmp.className = "d_left_resumer_table";
			document.getElementById("d_left_alert_fs").appendChild(tbTmp);
			fillImportables('lContentTable');
		});
	});

	$("#bt_save_field").bind("click", function() {
		if (($("#it_fName").val() != "") &&
			($("#se_oType").val() != "") &&
			($("#it_fLabel").val() != "")) {
			//try {
				tmpField = undefined;
				var fields = groups.fields_structure;
				var field = JSON.parse("{}");
				var woOptions = false;
				if (selected.field != undefined) field.Id = groups.fields_structure[selected.field].Id;
				else field.Id = ((fields.length == 0) ? "1" : (parseInt(fields[fields.length-1].Id)+1)+"");
				field.Name = $("#it_fName").val();
				field.Label = $("#it_fLabel").val();
				field.Type = $("#se_oType").val();
				field.Required = ""+$("#it_fRequired").prop("checked");
				field.Hint = (($("#it_fHint").val() != "") ? $("#it_fHint").val() : "0");
				field.Rules = (($("#se_oRules").val() != "") ? $("#se_oRules").val() : "0");
				field.Length = (($("#it_fLength").val() != "") ? $("#it_fLength").val() : "0");
				field.Parent = "0";
				field.Order = "0";
				field.ReadOnly = ""+(!$("#it_fReadOnly").prop("checked"));
				field.AutoComplete = (($("#it_fAutocomplete").val() != "") ? $("#it_fAutocomplete").val() : "0");
				field.FreeAdd = "0";
				field.Handler = "0";

				try {
					if ((field.Type == "sp") || (field.Type == "rg")) {
						var cc = false;
						if(groups.options[selected.activeoption].Field != undefined) {
							for (var index in groups.options[selected.activeoption].Field) {
								if(groups.options[selected.activeoption].Field[index] == field.Id) {
									cc = true;
								}
							}
						} else {
							groups.options[selected.activeoption].Field = JSON.parse("[]");
						}
						if(!cc) {
							groups.options[selected.activeoption].Field.push(field.Id);
							woOptions = true;
						}
					} else
						woOptions = true;
				} catch(Exception) {
					var cc = false;
					for (var index in groups.options) {
						for(var index2 in groups.options[index].Field) {
							if(groups.options[index].Field[index2] == field.Id) {
								cc = true;
								woOptions = true;
							}
						}
					}
					if(!cc)
						if(confirm(inf_des_cre_sin_opc))
							woOptions = true;
				}
				selected.activeoption = undefined;
				
				field.Form = groups.forms[selected.group].Id;

				if(woOptions) {
					if (selected.field != undefined) fields[selected.field] = field;
					else fields[fields.length] = field;
					groups.fields_structure = fields;
					$( "#fields_creator_editor" ).animate({
					    opacity: 0,
					    left: "+=250"
					  }, 400, function(){
					  	document.getElementById("fields_creator_editor").style.display = "none";
					  	document.getElementById("fields_selector_actions").style.display = "block";
					  	$( "#fields_selector_actions" ).animate({
						    opacity: 0,
						    left: "-=250"
						},0, function() {
						  	$( "#fields_selector_actions" ).animate({
							    opacity: 1,
							    left: "+=250"
							  }, 400, function() {
							  	$( "#fields_creator_editor" ).animate({
								    opacity: 0,
								    left: "-=250"
								  }, 0, function () {
								  	$("#it_fName").val("");
									$("#it_fLabel").val("");
									$("#se_oType option[value=\"\"]").attr('selected','selected');
									$("#it_fRequired").attr( "checked" );
									$("#it_fHint").val("");
									$("#se_oRules option[value=\"\"]").attr('selected','selected');
									$("#it_fLength").val("");""
									$("#it_fReadOnly").attr( "checked" );
									$("#it_fAutocomplete").val("");
									started_f = false;
								  	fillFields(selected.group);
								  });
							  });
						});
					});
				} else {
					$("#se_oType").change();
				}
			/*} catch(Exception) {
				console.log("error"+Exception);
			}*/
		} else {
			console.log("no");
		}
	});
}

function createActionsForEvent() {
	$("#bt_append_handler").unbind();
	$("#bt_append_handler").bind("click", function() {
		if (($("#se_handler_type").val() != "") && ($("#it_handler_value").val() != "")) {
			document.getElementById("s_handler_lister").style.display = "none";
			var tbTmp = document.getElementById("t_handler_lister");
			var trTmp = document.createElement("TR");
			var tdTmp1 = document.createElement("TD");
			var tdTmp2 = document.createElement("TD");

			tbTmp.appendChild(trTmp);
			trTmp.appendChild(tdTmp1);
			trTmp.appendChild(tdTmp2);

			tdTmp1.className = "td_event";
			tdTmp2.className = "td_event";

			tdTmp1.appendChild(document.createTextNode($("#se_handler_type option[value='"+$("#se_handler_type").val()+"']").text()));
			tdTmp2.appendChild(document.createTextNode($("#it_handler_value").val()));

			event_c.Types[event_c.Types.length] = $("#se_handler_type").val();
			event_c.Parameters[event_c.Parameters.length] = $("#it_handler_value").val();

			$("#it_handler_value").val("");
			$("#se_handler_type option[value='']").attr('selected','selected');

			return false;
		}
	});

	$("#b_event_saver").unbind();
	$("#b_event_saver").bind("click", function() {
		if(event_c.Types.length > 0) {
			if(selected.event != undefined) {
				event_c.Id = groups.handler_event[selected.event].Id;
				delete(groups.handler_event[selected.event]);
	    		groups.handler_event.splice(selected.event, 1);
				groups.handler_event[selected.event] = event_c;
			} else {
				try {
					var lastId = groups.handler_event[groups.handler_event.length-1].Id;
				} catch (Exception){
					var lastId = "0";
				}
				event_c.Id = (""+(parseInt(lastId)+1));
				groups.handler_event.push(event_c);
			}
			selected.event = undefined;
			event_c = JSON.parse("{}");
			document.getElementById("t_handler_lister").innerHTML = "";
			fillEvents();
		}
	});
}

function createActionsForOption() {
	$("bt_append_option").unbind();
    $("#bt_append_option").bind("click", function() {
	    tbTmp = document.getElementById("t_option_lister");
	    var optCount = tbTmp.childNodes.length;
    	if ($("#it_name_option").val() != "") {
	    	document.getElementById("s_options_lister").style.display = "none";

	    	trTmp = document.createElement("TR");
	    	tdTmp1 = document.createElement("TD");

	    	tbTmp.appendChild(trTmp);
	    	trTmp.appendChild(tdTmp1);

	    	tdTmp1.className = "td_event";

	    	tdTmp1.appendChild(document.createTextNode($("#it_name_option").val()));

	    	if(option_c.Options == undefined)
	    		option_c.Options = JSON.parse("[]");
	    	option_c.Options.push($("#it_name_option").val());

	    	$("#it_name_option").val("");

	    	return false;
	    }
    });

    $("#b_option_saver").unbind();
    $("#b_option_saver").bind("click", function() {
    	try {
	    	if(option_c.Options.length > 0) {
		    	if(selected.option == undefined) {
		    		try {
			    		option_c.Id = (parseInt(groups.options[groups.options.length-1].Id)+1)+"";
			    	} catch (Exception){
			    		option_c.Id = "0";
			    	}
			    	option_c.Field = JSON.parse("[]");
			    	groups.options.push(option_c);
			    } else {
			    	groups.options[selected.option] = JSON.parse("{}");
			    	groups.options[selected.option] = option_c;
			    }
			    document.getElementById("s_options_lister").style.display = "block";
			    document.getElementById("t_option_lister").innerHTML = "";
		    	option_c = JSON.parse("{}");
		    	selected.option = undefined;
		    	fillOptions();
		    }
		} catch(Exception){}
    });
}

//función que asigna un evento para mostrar el secundario
function findGeneralAndSecondary() {
	for(var index in groups.forms) {
		if(groups.forms[index].Id == "0") {
			selected.general = index;
		}

		var secondary_handler = undefined;

		for(var index in groups.handler_event) {
			for(var index2 in groups.handler_event[index].Parameters) {
				if(groups.handler_event[index].Parameters[index2] == "4d186321c1a7f0f354b297e8914ab240") {
					secondary_handler = groups.handler_event[index].Id;
				}
			}
		}

		for (var index in groups.fields_structure) {
			if(groups.fields_structure[index].Form == "0") {
				if(groups.fields_structure[index].Handler == secondary_handler) {
					for(var index2 in groups.forms) {
						if(groups.forms[index2].Parent == groups.fields_structure[index].Id) {
							selected.secondary = index2;
						}
					}
				}
			}
		}
	}
}

function contMenu() {
    if (document.addEventListener) {
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        }, false);
        document.addEventListener('click', function(e) {
        	try {
	        	document.getElementById("rmenu").className = "hide";
	        } catch(Exception){}
        }, false);
    } else {
        document.attachEvent('oncontextmenu', function() {
            alert("You've tried to open context menu");
            window.event.returnValue = false;
        });
    }
}

function mouseX(evt) {
    if (evt.pageX) {
        return evt.pageX;
    } else if (evt.clientX) {
       return evt.clientX + (document.documentElement.scrollLeft ?
           document.documentElement.scrollLeft :
           document.body.scrollLeft);
    } else {
        return null;
    }
}

function mouseY(evt) {
    if (evt.pageY) {
        return evt.pageY;
    } else if (evt.clientY) {
       return evt.clientY + (document.documentElement.scrollTop ?
       document.documentElement.scrollTop :
       document.body.scrollTop);
    } else {
        return null;
    }
}

function createUnload() {
	$(window).bind('beforeunload', function() {
	 	if(sessionStorage.extras) {
	      return 'Si continuas los cambios en la modificacion actual se eliminarán.';
	 	}
	});

	$( window ).unload(function() {
	  delete(sessionStorage.extras);
	  delete(sessionStorage.importDefault);
	  delete(sessionStorage.tmpStructure);
	  delete(sessionStorage.editionIndex);
	  delete(sessionStorage.allPollData);
	});
}

function releaseUnload() {
	$(window).unbind('beforeunload');
	$(window).unload(function() {});
}

String.prototype.repare=function(){
	return this.split(' ').join('').replace().replace(/[^a-zA-Z ]/g, '');
};

String.prototype.toUnicode = function(){
    var result = "";
    for(var i = 0; i < this.length; i++){
        result += "\\u" + ("000" + this[i].charCodeAt(0).toString(16)).substr(-4);
    }
    return result;
};