function getNodeHandler(dom, callback){
	var id = dom.id;
	for( b in objectContainer){
		if( objectContainer[b].id == id ){
			for( a in handler_event ) {
				if (objectContainer[b].handler == handler_event[a].Id){
					callback(0, matchHandlerRequest(handler_event[a], dom), getFormsMap(id));
				}
			}
			return 0;
		}
	}
	return 0;
}

function getNodeHandlerJoiner(dom, callback){
	var id = dom.id;
	var index = "";
	var match = "";
	//console.log(id);
	for ( g in handlerFieldJoiner ){
		for( h in handlerFieldJoiner[g].idFields){
			if (handlerFieldJoiner[g].idFields[h] == id){
				if(index.length > 0) {index += ","; match += ",";}
				//console.log(handlerFieldJoiner[g].TargetForm);
				index += getFormIndex2(handlerFieldJoiner[g].TargetForm);
				match += matchHandlerJoinerRequest(g);
			}
		}
	}
	//console.log(index);
	//console.log(match);
	if((index.length > 0) && (match.length > 0)){
		callback(1, match, index);
	}
}

function matchHandlerRequest(hCopy, val){
	//var val = dom.value;
	if(hCopy.Types.length == 0) return false;
	/*if(val == undefined){
		var children = dom.childElementCount;
		for( c = 0; c < children; c++){
			if(dom.children[c].checked)
				val = dom.children[c].value;
		}
	}*/
	//console.log(val);
	if(val != undefined) {
		var e = 0;
		for ( d in hCopy.Types ){
			switch(hCopy.Types[d]){
				case "<":
					try {
						if(parseInt(val) < parseInt(hCopy.Parameters[d]))
							e++;
					} catch (ex){ console.log(ex); }
				break;
				case ">":
					try {
						if(parseInt(val) > parseInt(hCopy.Parameters[d]))
							e++;
					} catch (ex){ console.log(ex); }
				break;
				case "!=":
					if(val != hCopy.Parameters[d])
						e++;
				break;
				case "=":
					if(val == hCopy.Parameters[d])
						e++;
				break;
			}
		}
		//console.log(e);
		return  (e == hCopy.Types.length);
	}
	return false;
}

function getFormsMap(id){
	if(id == undefined) return id;
	var index = "";
	for( f in forms) {
		if( (forms[f].Handler == 0) && (forms[f].Parent == id) ){
			if(index.length > 0) index += ",";
			index += ""+getFormIndex2(forms[f].Id);
		}
	}
	return index;
}

function getFormIndex2(id){
	if(id == undefined) return id;
	for( l in formContainer ){
		if( formContainer[l].id == id ){
			//console.log(l+"---"+id);
			return l;
		}
	}
	//console.log("returning null");
	return null;
}

function matchHandlerJoinerRequest(jhIndex){
	//var jhe = handlerFieldJoiner[jhIndex];
	var count = 0;
	for(i in handlerFieldJoiner[jhIndex].idFields) {
		for(j in objectContainer){
			if(handlerFieldJoiner[jhIndex].idFields[i] == objectContainer[j].id){
				for (k in handler_event){
					if(handler_event[k].Id == handlerFieldJoiner[jhIndex].idHandlers[i]){
						if (matchHandlerRequest(handler_event[k], objectContainer[j].dom)){
							count++;
						}
					}
				}
			}
		}
	}
	return (handlerFieldJoiner[jhIndex].idFields.length == count);
}

function formsLister(data) {
	var forms_tmp = new Array();
	for (var i = 0; i < formContainer.length; i++) {
		if (formContainer[i].parent == data.oId) {
			if(formContainer[i].insider == "1"){
				try {
					if(formContainer[data.fId].id == 0){
						formContainer[i].id = formContainer[i].id * -1;
					} else {
						formContainer[data.fId].cluster.appendChild(formContainer[i].cluster);
					}
				} catch (e) {
					console.log(e);
				}
			}
		}
	}
}

function objectListener(data){
	var match 	= false;
	var pos 	= 0;
	var event 	= new Array();
	var events	= new Array();
	for(var a in handler_event){
		if(objectContainer[data.pos].handler == handler_event[a].Id){
			event[pos] = a;
			pos++;
			match = true;
		}
	}
	pos = 0;
	for(var b in handlerFieldJoiner){
		for(var c in handlerFieldJoiner[b].idFields){
			if(objectContainer[data.pos].id == handlerFieldJoiner[b].idFields[c]){
				events[pos] = b;
				pos++;
				match = true;
			}
		}
	}
	if(match){
		objectContainer[data.pos].dom.title = data.pos;
		$( objectContainer[data.pos].dom ).change(function(){
			var dom_tmp = $( this );
			dom_tmp= (dom_tmp[0]);
			var dt = new Array();
			dt.oId = objectContainer[dom_tmp.title].id;
			dt.pos = dom_tmp.title;
			//console.log("1::"+	event+"- 2::"+events);
			for(var d in event){
				if(objectContainer[dt.pos].Type == 'rg'){
					for(var e = 0; e < objectContainer[dt.pos].dom.childElementCount; e++){
						var node = objectContainer[dt.pos].dom.children[e];
						if(node.checked){
							if(matchHandlerRequest(handler_event[event[d]], node.value)){
								for(var f in forms){
									if((forms[f].Parent == dt.oId) &&
										((forms[f].Handler == handler_event[event[d]].Id) ||
											(forms[f].Handler == "0"))) {
										showSubForm(f);
									}
								}
							} else {
								for(var g in forms){
									if((forms[g].Parent == dt.oId) &&
										((forms[g].Handler == handler_event[event[d]].Id) ||
											(forms[g].Handler == "0"))) {
										hideSubForm(g);
									}
								}
							}
						}
						
					}
				} else {
					var val;
					if(objectContainer[dt.pos].Type == 'cb')
						val = ((objectContainer[dt.pos].dom.checked) ? 'on' : 'off');
					else
						val = objectContainer[dt.pos].dom.value;
					if(matchHandlerRequest(handler_event[event[d]], val)){
						for(var f in forms){
							if((forms[f].Parent == dt.oId) &&
								((forms[f].Handler == handler_event[event[d]].Id) ||
									(forms[f].Handler == "0"))) {
								showSubForm(f);
							}
						}
					} else {
						for(var g in forms){
							if((forms[g].Parent == dt.oId) &&
								((forms[g].Handler == handler_event[event[d]].Id) ||
									(forms[g].Handler == "0"))) {
								hideSubForm(g);
							}
						}
					}
				}
			}
			for(var h in events){
				var open = true;
				for(var i in handlerFieldJoiner[events[h]].idFields){
					if(dt.oId == handlerFieldJoiner[events[h]].idFields[i]){
						for(var j in handler_event){
							if(handler_event[j].Id == handlerFieldJoiner[events[h]].idHandlers[i]){
								if(objectContainer[dt.pos].Type != 'rg'){
									if(matchHandlerRequest(handler_event[j], 
										objectContainer[dt.pos].dom.value))
										objectContainer[dt.pos].match[events[h]] = true;
									else
										objectContainer[dt.pos].match[events[h]] = false;
								}
							}
						}
					}
					if(open){
						for(var k in objectContainer){
							if(objectContainer[k].id == handlerFieldJoiner[events[h]].idFields[i]){
								if(objectContainer[k].match[events[h]] == undefined)
									objectContainer[k].match[events[h]] = false;
								if(!objectContainer[k].match[events[h]])
									open = false;
							}
						}
					}
				}
				if(open){
					for(var l in forms){
						if(forms[l].Id == handlerFieldJoiner[events[h]].TargetForm){
							showSubForm(l);
							//console.log("s=>"+l);
						}
					}
				} else {
					for(var m in forms){
						if(forms[m].Id == handlerFieldJoiner[events[h]].TargetForm){
							hideSubForm(m);
							//console.log("h=>"+forms[m].Id+"__"+events);
						}
					}
				}
			}
		});
	}
}

function objectValidaror(){
	var x = 0;
	var cb = 0;
	var cbu = 0;
	for(var a in objectContainer) {
		if(objectContainer[a].dom.offsetLeft > 0){
			if((objectContainer[a].Type != 'cb') && (cb > 0)){
				if(cb == cbu){
					x++;
				}
				cb = 0;
				cbu= 0;
			}
			if(objectContainer[a].Type == "rg"){
				var rg = 0;
				var rgu= 0;
				for(var b = 0; b < objectContainer[a].dom.childElementCount; b++){
					var node = objectContainer[a].dom.children[b];
					if(node.type == 'radio'){
						rg++;
						if(!node.checked){
							rgu++;
						} else {
							personContainer[node.dirName] = node.value;
						}
					}
				}
				if(rgu == rg){
					if(objectContainer[a].required == 'true')
						x++;
				}
			} else {
				if(objectContainer[a].Type == 'cb'){
					cb++;
					if(!objectContainer[a].dom.checked){
						delete personContainer[objectContainer[a].dom.name];
						cbu++;
						if(objectContainer[a].required == "false") cbu--;
					} else {
						personContainer[objectContainer[a].dom.name] = "true";//objectContainer[a].dom.value;
					}
				}
				else {
					if(objectContainer[a].dom.value == ''){
						delete personContainer[objectContainer[a].dom.name];
						if(objectContainer[a].required == 'true')
							x++;
					} else {
						if ((objectContainer[a].Type == 'tf') || (objectContainer[a].Type == 'ac')) {
							if((parseInt(objectContainer[a].length) > 0)){
								if(parseInt(objectContainer[a].dom.value.length) > parseInt(objectContainer[a].length)){
									alert("El valor ingresado en el campo "+objectContainer[a].label+" es demasiado largo.");
									objectContainer[a].dom.focus();
									objectContainer[a].dom.className = "ui-corner-all ui-state-error";
									//return -1;
									x++;
								} else if(objectContainer[a].dom.value != undefined) {
									personContainer[objectContainer[a].name] = objectContainer[a].dom.value;
									objectContainer[a].dom.className = "ui-corner-all";
								}
							}
						} else {
							if(objectContainer[a].dom.value != undefined) {
								personContainer[objectContainer[a].name] = objectContainer[a].dom.value;
								objectContainer[a].dom.className = "ui-corner-all";
							}
						}
					}
				}
			}
		}
	}
	//console.log(x);
	return (x);
	//console.log(x);
}