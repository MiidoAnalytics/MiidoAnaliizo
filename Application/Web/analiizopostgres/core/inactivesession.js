//var num=300;
var intervalo;
function contador() {
    //num--;
	//console.log('text');
	try{
		window.clearInterval(intervalo);
	}catch(e){console.log(e)};
	intervalo = setInterval(function (){location='http://52.27.125.67/analiizopostgres/Administrador/login/controlador/logout.php';},300000);

}

	/* Funcion que valida los formularios en navegadores que no admiten html*/
function validartexto(formulario){
   
	var todoCorrecto = true;
    var contr = 0;
    for (var i=0; i<formulario.length; i++) {
        if(formulario[i].required == true || formulario[i].type =='text') {
            
            if (formulario[i].value == null || formulario[i].value.length == 0 || /^\s*$/.test(formulario[i].value)){
                contr++;
            }
        }
    }
    if(contr > 0){
        alert ('El Formulario no puede estar vacío o contener sólo espacios en blanco');
        todoCorrecto=false;
    }
    if (todoCorrecto ==true) {formulario.submit();}
}
