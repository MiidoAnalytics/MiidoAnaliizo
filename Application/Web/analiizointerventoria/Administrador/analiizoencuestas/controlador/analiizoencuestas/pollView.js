
function selectInterviewer(){
	if(r){
		var dispositivo = navigator.userAgent.toLowerCase();
	    if( dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
	    	getLocation();
	    }
		var tmp_div = document.createElement(_D);
		var tmp_btn = document.createElement(_B);
		var tmp_cdep = document.createElement(_B);
		var tmp_sel1 = document.createElement(_S);
		var tmp_sel2 = document.createElement(_S);
		var tmp_lat  = document.createElement(_I);
		var tmp_lon  = document.createElement(_I);
		var tmp_viv  = document.createElement(_I);
		var tmp_fam  = document.createElement(_I);
		
		tmp_div.innerHTML += "<h2>"+_MSJ02+"</h2>";
		var tmp_iv = interviewer.split("||");
		for( var a in tmp_iv ){
			if(tmp_iv[a] != "") {
				var tmp_opt = document.createElement(_O);
				tmp_opt.value = tmp_opt.textContent = tmp_iv[a];
				tmp_sel1.appendChild(tmp_opt);
			}
		}
		var tmp_c = city.split("||");
		for( var a in tmp_c ){
			if(tmp_c[a] != "") {
				try{
					var tmp_opt2 = document.createElement(_O);
					var tmp_each_c = tmp_c[a].split(",");
					tmp_opt2.value = tmp_each_c[0];
					tmp_opt2.textContent = tmp_each_c[1];
					tmp_sel2.appendChild(tmp_opt2);
				} catch(e){console.log(e)};
			}
		}
		tmp_sel2.id = "citySelect";
		tmp_btn.appendChild(document.createTextNode(bt_save));
		tmp_btn.className = "boton";

		tmp_viv.type = setRules('int');
		tmp_fam.type = setRules('int');
		tmp_viv.id = "dwelling";
		tmp_fam.id = "family";

		tmp_lat.id = "latitudF";
		tmp_lon.id = "longitudF"
		tmp_lat.type = setRules('dec');
		tmp_lon.type = setRules('dec');
		tmp_lon.max = 180;
		tmp_lon.min = -180;
		tmp_lat.max = 90;
		tmp_lat.min = -90;

		tmp_cdep.appendChild(document.createTextNode(bt_cdep));
		tmp_cdep.className = "boton";
		
		$( tmp_btn ).click(function(){
			documentContainer.interviewer = tmp_sel1.value;
			documentContainer.pollCity = document.getElementById("citySelect").value;
			documentContainer.dwelling = document.getElementById('dwelling').value;
			documentContainer.family  = document.getElementById('family').value;
			documentContainer.pollDate = (
				new Date().getFullYear()+
				"/"+
				((new Date().getMonth()+1 < 10) ? "0"+(new Date().getMonth()+1) : (new Date().getMonth()+1))+
				"/"+
				((new Date().getDate() < 10) ? "0"+(new Date().getDate()) : (new Date().getDate()))+
				"  "+
				((new Date().getHours() < 10) ? "0"+(new Date().getHours()) : (new Date().getHours()))+
				":"+
				((new Date().getMinutes() < 10) ? "0"+(new Date().getMinutes()) : (new Date().getMinutes()))+
				":"+
				((new Date().getSeconds() < 10) ? "0"+(new Date().getSeconds()) : (new Date().getSeconds()))
			);
			if( dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
				$( tmp_div ).hide( "slide", { direction: "left" }, "medium", showFormG);
			} else {
				if(document.getElementById("latitudF").value != ''){
					if (document.getElementById("longitudF").value != '') {
						if (document.getElementById("longitudF").value > 180 ||
						    document.getElementById("longitudF").value < -180){
							alert(_MSJ09);
						}
						else if (document.getElementById("latitudF").value > 90 ||
						    	 document.getElementById("latitudF").value < -90){
							alert(_MSJ10);
						} else {
							if(document.getElementById("longitudF").value > 0)
								document.getElementById("longitudF").value = (document.getElementById("longitudF").value*-1);
							if(document.getElementById("latitudF").value < 0)
								document.getElementById("latitudF").value = (document.getElementById("latitudF").value*-1);
							locationContainer.latitude = document.getElementById("latitudF").value;
						    locationContainer.longitude= document.getElementById("longitudF").value;
						    locationContainer.status   = "ok";
							$( tmp_div ).hide( "slide", { direction: "left" }, "medium", showFormG);
						}
					} else {
						alert(_MSJ08);
					}
				} else {
					alert(_MSJ08);
				}
			}
		});

		$( tmp_cdep ).click(function(){
			if(confirm(_MSJ04)) {
				 $(window).bind('beforeunload', true);
				targetContainer.removeChild(tmp_div);
				selectDepto();
			}
		})

		tmp_div.appendChild(tmp_sel2);
		tmp_div.innerHTML += "<h2>"+_MSJ01+"</h2>";
		tmp_div.appendChild(tmp_sel1);
		tmp_div.innerHTML += "<h2>"+_MSJ13+"</h2>";
		tmp_div.appendChild(tmp_viv);
		tmp_div.innerHTML += "<h2>"+_MSJ14+"</h2>";
		tmp_div.appendChild(tmp_fam);
		if(!dispositivo.search(/iphone|ipod|ipad|android/) > -1 ) {
			tmp_div.innerHTML += "<h2>"+_MSJ05+"</h2>";
			tmp_div.innerHTML += "<h2>"+_MSJ06+"</h2>";
			tmp_div.appendChild(tmp_lat);
			tmp_div.innerHTML += "<h2>"+_MSJ07+"</h2>";
			tmp_div.appendChild(tmp_lon);
		}
		tmp_div.appendChild(tmp_btn);
		tmp_div.appendChild(tmp_cdep);
		targetContainer.appendChild(tmp_div);
		resumer();
	} else {
		selectDepto();
	}
}

function selectDepto(){
	var tmp_div = document.createElement(_D);
	var dpto = document.createElement(_S);
	var send_btn = document.createElement(_B);
	
	tmp_div.innerHTML += "<h2>"+_MSJ03+"</h2>";

	deptos = deptos.split("||");

	for(var d in deptos){
		var tmp_depto = deptos[d].split(",");
		var opt = document.createElement(_O);
		opt.value = tmp_depto[0];
		opt.textContent = tmp_depto[1];
		dpto.appendChild(opt);
	}
	send_btn.appendChild(document.createTextNode(bt_ok));
	send_btn.className = 'boton';

	$( send_btn ).click(function(){
		location.href = "http://"+document.domain+"/"+defaultLocation+"?dep="+dpto.value;
	});

	tmp_div.appendChild(dpto);
	tmp_div.appendChild(send_btn);
	targetContainer.appendChild(tmp_div);
}

function resumer(){
	var tmp_div = document.createElement(_D);
	var inf_div = document.createElement(_D);
	lister = lister.split('||');
	var pollsC = 0;
	for(var a in lister){
		if(lister[a] != ''){
			pollsC++;
			inf_div.innerHTML += "Encuesta N° "+(pollsC)+' tiene '+lister[a]+' personas encuestadas<br />';
		}
	}
	inf_div.style.textAlign = "right";
	if(pollsC == 0)
		tmp_div.innerHTML = "<div style=\"text-align: right;\">Ha realizado hasta ahora "+pollsC+" encuestas.";
	else
		tmp_div.innerHTML = "<div style=\"text-align: right;\">Ha realizado hasta ahora "+pollsC+" encuestas con los siguientes datos.";
	tmp_div.appendChild(inf_div);
	tmp_div.innerHTML += "</div>";
	targetContainer.appendChild(tmp_div);
}

function pollViewer(){
	targetContainer.innerHTML = '';
	groupLabel();
	personLabel();
	homeLabel();
	finishButton();
}

function groupLabel(){
	tmp_div = document.createElement(_D);
	tmp_div.innerHTML = "<h1>GRUPO FAMILIAR</h1>";
	tmp_div_c = new Array();//document.createElement(_D);
	var rs = extractor(groupContainer, (colGroup*rowGroup));
	for(var a = 0; a < colGroup; a++){
		tmp_div_c[a] = document.createElement(_D);
		var content = "";
		for(var b = 0; b < rowGroup; b++){
			//console.log((rowGroup*a)+b);
			try {
				content += "<"+_L+">"+rs[(rowGroup*a)+b][0]+": </"+_L+">"+rs[(rowGroup*a)+b][1]+"<br />";
			} catch (Exception){}
		}
		tmp_div_c[a].innerHTML = content;
		tmp_div.appendChild(tmp_div_c[a]);
		tmp_div_c[a].style.textAlign = "left";
	}
	targetContainer.appendChild(tmp_div);
	tmp_div.appendChild(document.createElement("LEGEND"));
}

function personLabel(){
	var tmp_div = document.createElement(_D);
	tmp_div.innerHTML = "<h1>PERSONAS</h1>";
	var tmp_div_c = new Array();
	for (var a = 0; a < personsContainer.length; a++){
		//console.log(personsContainer[a]);
		var rs = extractor(personsContainer[a], rowPerson);
		tmp_div_c[a] = document.createElement(_D);
		var content = "";
		for(var b = 0; b < rowPerson; b++){
			try {
				content += "<"+_L+">"+rs[b][0]+": </"+_L+">"+rs[b][1]+"<br />";
			} catch(Exception){}
		}
		//console.log(content);
		tmp_div_c[a].innerHTML = content;
		tmp_div.appendChild(tmp_div_c[a]);
		tmp_div_c[a].style.textAlign = "left";
		tmp_div.appendChild(document.createElement("LEGEND"));
	}
	var addDiv = document.createElement(_D);
	addDiv.innerHTML = "<b>+ Agregar</b>"
	tmp_div.appendChild(addDiv);
	targetContainer.appendChild(tmp_div);
	targetContainer.appendChild(document.createElement("LEGEND"));

	$( addDiv ).click(function(){
		createFilterInterface();
	})
}

function homeLabel(){
	var tmp_div = document.createElement(_D);
	tmp_div.innerHTML = "<h1>INFORMACION DE VIVIENDA Y SATISFACCIÓN</h1>";
	var addDiv = document.createElement(_D);
	addDiv.innerHTML = "<b>+ Agregar</b>"
	tmp_div.appendChild(addDiv);
	targetContainer.appendChild(tmp_div);
	if(isEmptyJSON(homeContainer)){
		$(addDiv).click(function(){
			targetContainer.innerHTML = '';
			showFormH();
		});
	} else {
		addDiv.innerHTML = "<b>Informacion completa...</b>"
	}
	targetContainer.appendChild(document.createElement("LEGEND"));
}

function finishButton(){
	var tmp_div = document.createElement(_D);
	var tmp_btn = document.createElement(_B);
	tmp_btn.appendChild(document.createTextNode(bt_fin));
	targetContainer.appendChild(tmp_btn);
	tmp_btn.className = "boton";
	$( tmp_btn ).click(function(){
		disableObject(tmp_btn);
		if(!isEmptyJSON(personsContainer)){
			if(!isEmptyJSON(homeContainer)){
				var poll = JSON.parse('{}');
				poll.GROUP = groupContainer;
				poll.HOUSE = homeContainer;
				poll.PERSONS = personsContainer;
				poll.DOCUMENTINFO = documentContainer;
				//poll.DOCUMENTINFO.INTERVIEWER = iSelected;
				//var dispositivo = navigator.userAgent.toLowerCase();
				//if( dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
					poll.DOCUMENTINFO.LOCATION = locationContainer;
				//}
				console.log(poll);
				_tester(poll, 3);
			} else {
				enableObject(tmp_btn);
				alert("No se ha llenado la informacion de vivienda y satisfaccion");
			}
		} else {
			enableObject(tmp_btn);
			alert("No se han agregado personas a la encuesta");
		}
	});
}

function enableObject(object){
	object.disabled = false;
}
function disableObject(object){
	object.disabled = true;
}

function extractor(jsonObject, w){
	var c = 0;
	var rs = new Array();
	for (var key in jsonObject) {
		if (jsonObject.hasOwnProperty(key)) {
			for (var a = 0; a < fields_structure.length; a++){
				if(fields_structure[a].Name == key){
					rs[c] = new Array();
					rs[c][0] = fields_structure[a].Label;
					rs[c][1] = jsonObject[key];
				}
			}
		}
		c++;
		if(c == w)
			return rs;
	}
}