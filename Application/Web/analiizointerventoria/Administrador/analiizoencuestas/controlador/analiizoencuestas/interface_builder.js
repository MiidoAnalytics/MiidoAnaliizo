function createInterface(data){
	for (var i = 0; i < data.length; i++) {
		var iForm = getFormIndex(data[i].form);

		var div = document.createElement("DIV");
		var div2 = document.createElement("DIV");
		if(data[i].Type == "cb") {
			div1 = document.createElement(_SP);
			//div1.appendChild(data[i].dom);
			//data[i].dom.appendChild(div1);
		} else {
			var div1 = document.createElement("H2");
		}
		div2.appendChild(data[i].dom);
		div1.appendChild(document.createTextNode(data[i].label));
		div1.class = "span5";
		div2.class = "span5";
		div.class  = "row";
		if(data[i].rOnly == "1")
			data[i].dom.disabled = true;
		if(data[i].Type == "cb")
			div2.appendChild(div1);
		else
			div.appendChild(div1);
		div.appendChild(div2);

		formContainer[iForm].cluster.appendChild(div);
		putInsiders({
			oId : data[i].id,
			fId : iForm
		});
		objectListener({
			oId : data[i].id,
			pos : i
		});
		if(data[i].name == "fecNac"){
			data[i].dom.title = i;
			$( data[i].dom ).change(function(){
				var dom_tmp = $( this );
				dom_tmp= (dom_tmp[0]);
				var value = dom_tmp.value;
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1;
				var yyyy = today.getFullYear();
				var date = value.split("-");
				if(parseInt(dd) < parseInt(date[2])){
					date[1]++;
				}
				if(parseInt(mm) < parseInt(date[1])){
					date[0]++;
				}
				objectContainer[parseInt(dom_tmp.title)+1].dom.value = (yyyy - date[0]);
				$( objectContainer[parseInt(dom_tmp.title)+1].dom ).change();

			});
		}
	};
	//personsContainer["Person"personsCounter] = JSON.parse({});
}

function putInsiders(data) {
	for (var i = 0; i < formContainer.length; i++) {
		if (formContainer[i].parent == data.oId) {
			if(formContainer[i].insider == "1"){
				try {/*
					if(formContainer[data.fId].id == 0){
						formContainer[i].id = formContainer[i].id * -1;
					} else {*/
						formContainer[data.fId].cluster.appendChild(formContainer[i].cluster);
					//}
				} catch (e) {
					console.log(e);
				}
			}
		}
	}
}

function prepareElements() {
	for (var i = 0; i < formContainer.length; i++) {
		if ((formContainer[i].parent != 0) ||
			(formContainer[i].insider != 0)) {
				$(formContainer[i].cluster).hide("blind");
		}
	};
}

function showSubForm(index){
	//for (a in index) {
		try {
			$( formContainer[index].cluster ).show( "blind", {}, "medium");
		} catch (e){ console.log("Error in index "+index); }
	//}
}

function hideSubForm(index){
    try{
		$( formContainer[index].cluster ).hide( "blind", {}, "medium");
	} catch(e){
		console.log("Error in "+e);
	}
}