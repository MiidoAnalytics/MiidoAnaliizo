var filterContainer;
var filterBackground;
var filterSubContainer1;
var filterSubContainer2;
var filterField;
var filterCloseButton;
var filterExistent;
var filterNew;
var filterEdd;
var closeInterval;
var filteredData;
var filterSelected;

function createFilterInterface(){
	filterContainer    = document.createElement(_D);
	filterBackground   = document.createElement(_D);
	filterSubContainer1 = document.createElement(_D);
	filterSubContainer2 = document.createElement(_D);

	var filterLabel    = document.createElement(_L)
	filterField        = document.createElement(_I);

	filterCloseButton  = document.createElement(_B);
	filterExistent 	   = document.createElement(_B);
	filterNew		   = document.createElement(_B);
	filterEdd		   = document.createElement(_B);

	filterField.id = "filterFieldId";
	filterContainer.id = "filterContainer";
	filterCloseButton.id = "filterCloseButton";
	
	filterLabel.innerHTML = ((_MSJ15));
	filterCloseButton.appendChild(document.createTextNode(bt_exit));
	filterExistent.appendChild(document.createTextNode(bt_exis));
	filterNew.appendChild(document.createTextNode(bt_new));
	filterEdd.appendChild(document.createTextNode(bt_errd));

	filterField.type = "text";

	filterBackground.className = "filterBackground";
	filterSubContainer1.className = "filterSubContainer1";
	filterSubContainer2.className = "filterSubContainer2";
	filterLabel.className = "filterLabel";
	filterField.className = "ui-corner-all";
	filterCloseButton.className = "filterCloseButton";
	filterExistent.className = "boton";
	filterNew.className = "boton";
	filterEdd.className = "boton";

	filterContainer.style.height = document.body.scrollHeight+"px";

	filterSubContainer1.appendChild(filterCloseButton);
	filterSubContainer2.appendChild(filterLabel);
	filterSubContainer2.innerHTML += "<br />";
	filterSubContainer2.appendChild(filterField);
	filterSubContainer2.innerHTML += "<br />";
	filterSubContainer2.appendChild(filterExistent);
	filterSubContainer2.appendChild(filterNew);
	filterSubContainer2.appendChild(filterEdd);

	filterSubContainer1.appendChild(filterSubContainer2);
	filterContainer.appendChild(filterBackground);
	filterContainer.appendChild(filterSubContainer1);
	targetContainer.appendChild(filterContainer);

	window.scrollTo(0, 0);

	$('#filterCloseButton').click(function () {
        $("#filterContainer").toggleClass('closing');
        closeInterval = setInterval(function(){
        	try{
        		targetContainer.removeChild(filterContainer);
        	} catch (e) {
        		clearInterval(closeInterval);
        		console.log("intervalCleaned");
        	}
        }, 1000);
    });
    buttonListeners();
}

function buttonListeners(){
	filterSelected = 0;
	document.getElementById("filterFieldId").addEventListener("keyup", function(){
		filterSelected = -1;
		$.post("lib/27052015/$010620151159.php",
	        {
	            call:       "FINDPERSON",
	            arguments:  document.getElementById("filterFieldId").value,
	            home:       "",
	            extras:     documentContainer.pollCity
	        },
	        function (data) {
	        	data = JSON.parse(data);
	        	filteredData = data;
	        	var arr = "";
			    for(var x in data){
			    	arr += ($.map(data[x], function (val, name){ return assignValue(val, name);})+"||").replace(/\,/g,' ');
			    }
			    var arr2 = arr.split("||");
			    $(document.getElementById("filterFieldId")).autocomplete({
					source: arr2,
					position: { my: "left bottom", at: "left top", collision: "fit flip" },
					autoFocus: true
				});
				var acObject = $(document.getElementById("filterFieldId")).autocomplete( "widget" );
				$( acObject ).click(function(){
					filterSelected = 1;
				});
	        }
        );
	});

	$( filterNew ).click(function(){
		targetContainer.innerHTML = '';
		showFormP();
	});
	$( filterExistent ).click(function(){
		if (filterSelected == 1) {
			if(seekFilteredPerson()){
				targetContainer.innerHTML = '';
				showFormP();
				for(var tmpObject in objectContainer) {
					var matches;
					tmpMap = mapAppFilter.split(",");
					for(var tmpInMap in tmpMap) {
						var tmpInMap = tmpMap[tmpInMap].split(':');
						if(tmpInMap[1] == objectContainer[tmpObject].name){
							switch (objectContainer[tmpObject].Type) {
								case 'tf':
									objectContainer[tmpObject].dom.value = filteredData[tmpInMap[0]];
								break;
								case 'sp':
									var option = optionParser(filteredData[tmpInMap[0]]);
									$( objectContainer[tmpObject].dom ).val(option);
								break;
							}
							objectContainer[tmpObject].dom.disabled = 'disabled';
						}
					}
				}
			}
		} else {
			alert("Debe seleccionar una persona");
			document.getElementById("filterFieldId").focus();
		}
	});
	$( filterEdd ).click(function(){
		if (filterSelected == 1) {
			if(seekFilteredPerson()){
				targetContainer.innerHTML = '';
				showFormP();
				for(var tmpObject in objectContainer) {
					var matches;
					tmpMap = mapAppFilter.split(",");
					for(var tmpInMap in tmpMap) {
						var tmpInMap = tmpMap[tmpInMap].split(':');
						if(tmpInMap[1] == objectContainer[tmpObject].name){
							switch (objectContainer[tmpObject].Type) {
								case 'tf':
									objectContainer[tmpObject].dom.value = filteredData[tmpInMap[0]];
								break;
								case 'sp':
									var option = optionParser(filteredData[tmpInMap[0]]);
									$( objectContainer[tmpObject].dom ).val(option);
								break;
								default: break;
							}
						}
					}
				}
			}
		} else {
			alert("Debe seleccionar una persona");
			document.getElementById("filterFieldId").focus();
		}
	});
	document.getElementById("filterFieldId").focus();
}

function assignValue(val, name) {
	if( personACFilterName.search(name) > -1 ){
		return val;
    }
}

function seekFilteredPerson() {
	var selected = document.getElementById("filterFieldId").value;
	selected = selected.split(" ");
	for (var tmp in filteredData) {
		if(selected[1] == filteredData[tmp].identificacion) {
			filteredData = (filteredData[tmp]);
			return true;
		}
	}
	return false;
}

function optionParser(opt) {
	switch (opt) {
		case 'CC':
			return 'Cédula de ciudadanía';
			break;
		case 'RC':
			return 'Registro civil';
			break;
		case 'TI':
			return 'Tarjeta de identidad';
			break;
		case 'MS':
			return 'Menor sin identificación';
			break;
		case 'CE':
			return 'Cédula de extrangería';
			break;
		case 'AS':
			return 'Adulto sin identificacion';
			break;
		case 'PA':
			return 'Pasaporte';
			break;
		case 'M':
			return 'Masculino';
			break;
		case 'F':
			return 'Femenino';
			break;
	}
}
