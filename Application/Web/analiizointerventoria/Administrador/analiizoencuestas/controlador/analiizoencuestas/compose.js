var personInformation = "";

function formsCreator(){
	for (var i = 0; i < forms.length; i++) {
		formContainer[i] = new Array();
		formContainer[i].id = forms[i].Id;
		formContainer[i].parent = forms[i].Parent;
		formContainer[i].insider = forms[i].Inside;
  /*if(forms[i].Parent == "0")
   formContainer[i].cluster = document.createElement("FORM");
   else*/
   	formContainer[i].cluster = document.createElement("FIELDSET");
   if (forms[i].Header != "") {
   //formContainer[i].cluster.style.width = "200";
   var fs = document.createElement("H1");
   fs.appendChild(document.createTextNode(forms[i].Header));
   formContainer[i].cluster.appendChild(fs);
}
}
}

function createObjects(){
	for(a = 0; a < fields_structure.length; a++){
		objectContainer[a] = new Array();
		objectContainer[a].id = fields_structure[a].Id;
		objectContainer[a].name = fields_structure[a].Name;
		objectContainer[a].label = (fields_structure[a].Label);
  //objectContainer[a].label.className = "demoHeaders";
  objectContainer[a].required = fields_structure[a].Required;
  objectContainer[a].form = fields_structure[a].Form;
  objectContainer[a].order = fields_structure[a].Order;
  objectContainer[a].handler = fields_structure[a].Handler;
  objectContainer[a].Type = fields_structure[a].Type;
  objectContainer[a].length = fields_structure[a].Length;
  objectContainer[a].rOnly = fields_structure[a].ReadOnly;
  objectContainer[a].match  = JSON.parse('[]');
  objectContainer[a].dom = createField({
  	objectType  : fields_structure[a].Type,
  	hint        : fields_structure[a].Hint,
  	rules       : fields_structure[a].Rules,
  	id          : fields_structure[a].Id,
  	required    : fields_structure[a].Required,
  	autocomplete: fields_structure[a].AutoComplete,
  	fLength     : fields_structure[a].Length,
  	index       : a
  });
  objectContainer[a].dom.name = fields_structure[a].Name;
}
}

function objectsCleaner(){
	for(a = 0; a < objectContainer.length; a++){
		switch (objectContainer[a].dom.type){
			case "number":
			case "text":
			case "date":
			case 'select-one':
				objectContainer[a].dom.value = "";
			break;
			case "checkbox":
				objectContainer[a].dom.checked = false;
			break;
			default:
				if(objectContainer[a].Type == "rg"){
				for(var b = 0; b < objectContainer[a].dom.childElementCount; b++){
					var node = objectContainer[a].dom.children[b];
					if(node.type == 'radio'){
						node.checked = false;
					}
				}
			}
			break;
		}
	}
	for(b = 0; b < formContainer.length; b++) {
		$(formContainer[b].cluster).show("blind");
		formContainer[b].cluster.style['display'] = 'block';
	}
	prepareElements();
}

function createField(data){
	var dom;
	switch (data.objectType){
		case "tf":
		dom = document.createElement("INPUT");
		dom.type = setRules(data.rules);
		//console.log(data.fLength+"::AC::"+data.hint+"::ID::"+data.id);
		if(data.fLength > 0) {
			dom.maxLength = data.fLength+"";
		}
		if(data.rules = 'int')
			dom.min = 0;
		dom.setAttribute("placeholder", data.hint);
		dom.className = "ui-corner-all";
		break;
		case "dp":
		dom = document.createElement("INPUT");
		if(ieOn){
			dom.type = "text";
			$(function() {
				$( dom ).datepicker({
					dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
					maxDate: "+0d"
				});
			});
		} else {
			dom.type = "date";
			dom.max  = currDate;
		}
		dom.className = "ui-corner-all";
		break;
		case "cb":
		dom = document.createElement("INPUT");
		dom.type = "checkbox";
		dom.value = "on";
		dom.dirName = objectContainer[data.index].name;
		break;
		case "rg":
		dom = createRadioGroup(data.id, data.index);
		break;
		case "ac":
		dom = document.createElement("INPUT");
		dom.type = "text";
		arr2 = undefined;
		try {
			switch(data.autocomplete){
				case "ciuo":
				arr2 = ciuo.split("||");
				break;
				case "diseases":
				arr2 = diseases.split("||");
				break;
				case "cups":
				arr2 = cups.split("||");
				break;
				case "medicaments":
				arr2 = cums.split("||");
				break;
				default:
				console.log(data.autocomplete);
				break;
			}
			$( dom ).autocomplete({
				source: arr2,
				position: { my: "left bottom", at: "left top", collision: "fit flip" },
				messages: {
					noResults: '',
					results: ''
				}
			});
		} catch (e){}
		dom.className = "ui-corner-all";
		break;
		case "tv":
		dom = document.createElement("P");
		break;
		case "sp":
		dom = document.createElement("SELECT");
		dom = createSpinnerOptions(data.id, dom);
		break;
		default:
   //dom = document.createElement("P");
   dom = document.createTextNode(" El objeto "+data.objectType+" esta pendiente de desarrollar");
   //dom.setAtr
   break;
}
 //if((data.objectType != "rg") && (data.objectType != "cb") && (data.objectType != "tv"))
 //    dom.className = "form-control";
 /*if((data.objectType != "rg") && (data.objectType != "cb"))
 dom.style.width = 200;*/
 
 dom.required = ((data.required == "true") ? true : false);
 dom.id = data.id;
 //addNodeListener(dom);
 return dom;
}

function setRules(rule){
	switch (rule) {
		case "int":
		return "number";
		case "eml":
		return "email";
		case "dec":
		return "number";
		default:
		return "text";
	}
}

function createRadioGroup(id, index){
	var tmp = document.createElement("DIV");
	for( i in options){
		for (x in options[i].Field){
			if(options[i].Field[x] == id){
	//if(options[i].Options.length > 4)
	//    return (createSpinnerOptions(id, document.createElement("SELECT")));
	for(b in options[i].Options){
		if(options[i].Options[b] != "-") {
			var tmpDiv = document.createElement("DIV");
			var rb_tmp = document.createElement("INPUT");
			var option = document.createElement("SPAN");
			option.textContent  = (options[i].Options[b]);
			rb_tmp.type = "RADIO";
			rb_tmp.name = id;
			rb_tmp.value = options[i].Options[b];
			rb_tmp.dirName = objectContainer[index].name;
			rb_tmp.id = (options[i].Options[b]);
			tmpDiv.appendChild(rb_tmp);
			tmpDiv.appendChild((option));
			tmp.appendChild(rb_tmp);
			tmp.appendChild((option));
	  //tmp.appendChild(tmpDiv);
	  //tmp.appendChild(document.createTextNode(""));
	}
}
}
}
}
return tmp;
}

function createSpinnerOptions(id, node){
	for( i in options){
		for (x in options[i].Field){
			if(options[i].Field[x] == id){
				for(b in options[i].Options){
					var opt = document.createElement("OPTION");
					opt.value = opt.textContent = options[i].Options[b];
					if(options[i].Options[b] == "-") {
						opt.value = "";
						opt.textContent = "Seleccione ...";
					}
					node.appendChild(opt);
				}
			}
		}
	}
 //node.className = "ui-selectmenu-button ui-widget ui-state-default ui-corner-all";
 return node;
}

function getFormIndex(formId){
	var a;
	for ( a = forms.length - 1 ; a >= 0  ; a-- ) {
		if(forms[a].Id == formId)
			break;
	}
	if(a == -1)
		return 0;
	return a;
}

function createNextBackButton(){
	dNBButton   = document.createElement("DIV");
	nButton     = document.createElement("BUTTON");
	bButton     = document.createElement("BUTTON");

	nButton.appendChild(document.createTextNode(bt_next));
	bButton.appendChild(document.createTextNode(bt_back));

	nButton.className = "boton";
	bButton.className = "boton";

	try {
		dNBButton.removeChild(fButton);
	} catch(e){}
	dNBButton.appendChild(bButton);
	dNBButton.appendChild(nButton);
}

function addNBButtonHandler(){
	$( nButton ).click(function() {
		ss = 1;
		//objectValidaror();
		changeForm();
	});
	$( bButton ).click(function() {
		ss = -1;
		changeForm();

	});
}

function changeToFinishButton(back){
	fButton = document.createElement("BUTTON");
	fButton.appendChild(document.createTextNode(bt_save));
	fButton.className = "boton";
	$( fButton ).click(function(){
		disableObject(fButton);
		if(back == 1) {
			//if(validateRequired()){
			if(objectValidaror() == 0){
				var poll = JSON.parse('{}');
				documentContainer.Created = (
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
				poll.HOME = personContainer;
				//_tester(personContainer, back);
				//poll.HOUSE = homeContainer;
				//poll.PERSONS = personsContainer;
				poll.DOCUMENTINFO = documentContainer;
				//poll.DOCUMENTINFO.INTERVIEWER = iSelected;
				//var dispositivo = navigator.userAgent.toLowerCase();
				//if( dispositivo.search(/iphone|ipod|ipad|android/) > -1 ){
					//poll.DOCUMENTINFO.LOCATION = locationContainer;
				//}
				//console.log(poll);
				_tester(poll, 3);
			} else {
				enableObject(fButton);
				alert("Revisa los valores ingresados.\nTodos los campos son obligatorios")
			}
		} else {
			enableObject(fButton);
			//_tester(personContainer, back);
			console.log("poll");
		}
	});
	if(back == 1) {
		dNBButton.removeChild(bButton);
	}
	try {
		dNBButton.removeChild(nButton);
	} catch(e){}
	dNBButton.appendChild(fButton);
}

function addNodeListener(dom){
	$( dom ).change(function() {
		getNodeHandler(dom, nodeHandlerMatch);
		getNodeHandlerJoiner(dom, nodeHandlerMatch);
	});
}

function nodeHandlerMatch(cs, match, index){
	if(index == undefined) return;
	index = index.split(",");
	if(cs == 0)
		for(n = 0; n < index.length; n++)
			((match) ? showSubForm(index[n]) : hideSubForm(index[n]));
		else {
			match = match.split(",");
			if(index.length == match.length){
				for( m in index){
					if ((match[m] == "true")) showSubForm(index[m]);
					else hideSubForm(index[m]);
				}
			} else {
				console.log("something wrong");
			}
		}
	}

	function validateRequired(){
 /*jQuery.validator.setDefaults({
   debug: true,
   success: "valido"
 });
 $( formContainer[currentForm].cluster ).validate({
  rules: {
   required: true
  }
 });

 $( formContainer[currentForm].cluster ).validate({
  debug: true,
  rules: {
   required: true
  },
  messages: {
   required: "Este campo es obligatorio"
  }
 });
return;*/
personInformation = "";
var children1 = formContainer[currentForm].cluster.childElementCount;
var count = 0;
for (var a = 0; a < children1; a++ ){
	var children2 = formContainer[currentForm].cluster.children[a].childElementCount;
	for (var b = 1; b < children2; b++ ){
		var children3 = formContainer[currentForm].cluster.children[a].children[1].childElementCount;
		for (var c = 0; c < children3; c++) {
			var dom = formContainer[currentForm].cluster.children[a].children[1].children[c];
			if(dom.value != undefined){
				if(dom.required){
					if(dom.type == "select-one")
						if(dom.value == "") {
							dom.className = "alerta";
							count++;
							delete personContainer[dom.name];
						} else {
							//dom.className = "ui-selectmenu-button ui-widget ui-state-default ui-corner-all";
							personContainer[dom.name] = dom.value;
							//personInformation += "\""+dom.name+"\":\""+dom.value+"\",";
						}
						if(dom.type == "number" || dom.type == "text" || dom.type == "date")
							if(dom.value == "") {
								dom.className = "alerta ui-corner-all";
								count++;
								delete personContainer[dom.name];
							} else {
								dom.className = "ui-corner-all";
								personContainer[dom.name] = dom.value;
							//personInformation += "\""+dom.name+"\":\""+dom.value+"\",";
						}
					}
				} else {
					var tmp9 = validateSubChildren(dom);
					try {
						count += ((tmp9 == 0) ?  0 : 1);
					} catch(e){
						console.log(e);
					}
				}
			}
		}
	}
	//console.log(personContainer);
	return ((count == 0) ? true : false);
}

function validateSubChildren(dom){
	var children4 = dom.childElementCount;
 //console.log(children4);
 if(children4 > 0) {
 	var count2 = 0;
 	var visible = false;
 	try {
 		for (var d = 0; d < children4; d++) {
 			dom2 = dom.children[d];
	//console.log(dom2.offsetLeft+" "+dom2.value+" "+dom2.checked);
	if(dom2.offsetLeft > 0){
	 //console.log("procesado "+dom2.type);
	 if(dom2.tagName == "SPAN"){
	  //console.log("span");
	}
	else if((dom2.type == "checkbox") || (dom2.type == "radio")) {
		visible = true;
		if(dom2.checked){
			count2++;
			personContainer[dom2.dirName] = dom2.id;
	   //personInformation += "\""+dom2.dirName+"\":\""+dom2.id+"\",";
	}
} else if ((dom2.type == "text") && (dom2.type == "number") && (dom2.type == "date")) {
	if(dom2.value != ""){
		count2++;
		personContainer[dom2.name] = dom2.value;
	   //personInformation += "\""+dom2.name+"\":\""+dom2.value+"\",";
	} else {
		delete personContainer[dom.name];
	}
} else if(type == undefined) {
	var rturn = (validateSubChildren(dom2));
	count2 += rturn;
	return rturn;
}
}
}
if((count2 == 0) && (visible)) {
	for(var e = 0; e < children4; e++) {
		dom2 = dom.children[e];
		if(dom2.offsetLeft > 0){
			try{
				delete personContainer[dom2.dirName];
			} catch(e){}
	  //console.log(dom2.type + " -- "+dom2.offsetLeft+" -- "+dom2.id);
	  if(dom2.type == "radio")
	  	dom.className = "ui-state-error";
	  if(dom2.type == "checkbox")
	  	dom.className = "ui-state-error";
	}
}
} else {
	for(var f = 0; f < children4; f++) {
		dom2 = dom.children[f];
		if(dom2.type == "radio")
			dom.className = "";
		if(dom2.type == "checkbox")
			dom.className = "";
	}
}
} catch(ex){}
if(visible)
	return ((count2 == 0) ? 1 : 0);
} 
return 0;
}

function isEmptyJSON(obj) {
	for(var i in obj) { return false; }
		return true;
 //return false;
}