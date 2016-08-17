var resumeContainer = document.createElement("FIELDSET");
var Case;

function showFormG(){
	currentForm == 0;
	$( formContainer[currentForm].cluster ).hide();
	$( dNBButton ).hide();
	changeToFinishButton(1);
	var direction = "rigth";
	dNBButton.style.display = "block";
	formContainer[currentForm].cluster.appendChild(dNBButton);
	targetContainer.appendChild(formContainer[currentForm].cluster);
	$( formContainer[currentForm].cluster ).show( "slide", { direction: direction}, "medium");
}

function showFormH() {
	Case = -1;
	if(currentForm < formContainer.length) {
		if((formContainer[currentForm].id < 0)){
			$( formContainer[currentForm].cluster ).hide();
			createNextBackButton();
			addNBButtonHandler();
			$( dNBButton ).hide();
			var direction;
			if(ss == 1)
				direction = "rigth";
			else
				direction = "left"
		    dNBButton.style.display = "block";
			formContainer[currentForm].cluster.appendChild(dNBButton);
			targetContainer.appendChild(formContainer[currentForm].cluster);
			$( formContainer[currentForm].cluster ).show( "slide", { direction: direction}, "medium");
		} else {
			if(ss == 1)
				currentForm++;
			else
				currentForm--;
			if(currentForm < 0){
				ss = 1;
				currentForm++;
			}
			showFormH();
		}
	} else if (currentForm == formContainer.length) {
		changeToFinishButton(2);
		resumeContainer = document.createElement("FIELDSET");
		var String = "";
		for (var key in personContainer) {
			if (personContainer.hasOwnProperty(key)) {
				for (var a = 0; a < fields_structure.length; a++){
					if(fields_structure[a].Name == key){
						String += ("<label><b>"+fields_structure[a].Label + ' :</b></label> ' +personContainer[key]+"<br />");
					}
				}
		  	}
		}
		if(ss == 1)
			direction = "left";
		else
			direction = "rigth"
		$( resumeContainer ).hide( "slide", { direction: direction}, "medium");
		resumeContainer.innerHTML = String;
		dNBButton.style.display = "block";
		resumeContainer.style.textAlign = "left";
		resumeContainer.appendChild(dNBButton);
		targetContainer.appendChild(resumeContainer);
		if(ss == 1)
				direction = "rigth";
			else
				direction = "left"
		$( resumeContainer ).show( "slide", { direction: direction}, "medium");
	}
}

function showFormP() {
	Case = 1;
	try {
		if(currentForm < formContainer.length) {
			if (	//(validateVisibleForm()) &&
					(formContainer[currentForm].cluster.style['display'] != 'none') &&
					(formContainer[currentForm].insider == 0) &&
					(formContainer[currentForm].id > 0)) {
				createNextBackButton();
				addNBButtonHandler();
				enableObject(fButton);
				$( formContainer[currentForm].cluster ).hide();
				$( dNBButton ).hide();
				var direction;
				if(ss == 1)
					direction = "rigth";
				else
					direction = "left"
			    dNBButton.style.display = "block";
				formContainer[currentForm].cluster.appendChild(dNBButton);
				targetContainer.appendChild(formContainer[currentForm].cluster);
				$( formContainer[currentForm].cluster ).show( "slide", { direction: direction}, "medium", validateVisibleForm);
			} else {
				if(ss == 1)
					currentForm++;
				else
					currentForm--;
				if(currentForm < 0){
					ss = 1;
					currentForm++;
				}
				showFormP();
			}
		} else if (currentForm == formContainer.length) {
			changeToFinishButton(0);
			resumeContainer = document.createElement("FIELDSET");
			var String = "";
			for (var key in personContainer) {
				if (personContainer.hasOwnProperty(key)) {
					for (var a = 0; a < fields_structure.length; a++){
						if(fields_structure[a].Name == key){
							String += ("<label><b>"+fields_structure[a].Label + ' :</b></label> ' +personContainer[key]+"<br />");
						}
					}
			  	}
			}
			if(ss == 1)
				direction = "left";
			else
				direction = "rigth"
			$( resumeContainer ).hide( "slide", { direction: direction}, "medium");
			resumeContainer.innerHTML = String;
			dNBButton.style.display = "block";
			resumeContainer.style.textAlign = "left";
			resumeContainer.appendChild(dNBButton);
			targetContainer.appendChild(resumeContainer);
			if(ss == 1)
					direction = "rigth";
				else
					direction = "left"
			$( resumeContainer ).show( "slide", { direction: direction}, "medium");
		} else {
			ss = -1;
			currentForm--;
			showFormP();
		}
	} catch(e){
		
	}
}

function validateVisibleForm(){
	var visibles = 0;
	for(var a = 0; a < formContainer[currentForm].cluster.childElementCount; a++){
		var node = formContainer[currentForm].cluster.children[a];
		//console.log(node.style.display+"."+currentForm);
		//if(node.type == 'fieldset'){
			if(node.offsetLeft > 0)
				visibles++;
		//}
	}
	//console.log(visibles);
	if (visibles < 3){
		if(ss == 1)
				direction = "left";
			else
				direction = "rigth"
		$( formContainer[currentForm].cluster ).hide();
		$( dNBButton ).hide();
		if(ss == 1)
			currentForm++;
		else
			currentForm--;
		if(currentForm < 0){
			ss = 1;
			currentForm++;
		}
		showFormP();
	}
}

function changeForm(){
	if(currentForm < formContainer.length) {
		var validatioResults = objectValidaror();
		if(validatioResults == 0){
			var direction;
			if(ss == 1)
				direction = "left";
			else
				direction = "rigth"
		    $( formContainer[currentForm].cluster ).hide( "slide", { direction: direction}, "medium");
		    $( dNBButton ).hide( "slide", { direction: direction }, "medium", ready);
		} else if(validatioResults > 0) {
			alert("Revisa los valores ingresados.\nTodos los campos subrayados son obligatorios")
		}
	} else if (currentForm == formContainer.length) {
		var direction;
		if(ss == 1)
			direction = "left";
		else
			direction = "rigth"
	    $( resumeContainer ).hide( "slide", { direction: direction}, "medium");
	    $( dNBButton ).hide( "slide", { direction: direction }, "medium", ready);
	}
}

function ready(){
	if(currentForm < formContainer.length) {
		targetContainer.removeChild(formContainer[currentForm].cluster);
		formContainer[currentForm].cluster.removeChild( dNBButton );
		$( formContainer[currentForm].cluster ).show( "slide" );
		if(ss == 1)
			currentForm++;
		else
			currentForm--;
		if(currentForm < 0){
			ss = 1;
			currentForm++;
		}
		if(Case == 1)
			showFormP();
		else
			showFormH();
	} else if (currentForm == formContainer.length) {
		resumeContainer.removeChild(dNBButton);
		targetContainer.removeChild(resumeContainer);
		if(ss == 1)
			currentForm++;
		else
			currentForm--;
		if(currentForm < 0){
			ss = 1;
			currentForm++;
		}
		if(Case == 1)
			showFormP();
		else
			showFormH();
	}
}