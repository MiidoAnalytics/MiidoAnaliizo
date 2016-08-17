function setJSONStructure(data){
	structure = JSON.parse(data);
}

function parseStructure(){
	fields_structure 	= structure.fields_structure;
	options 			= structure.options;
	forms 				= structure.forms;
	forms_order			= structure.forms_order;
	handler_event		= structure.handler_event;
	handlerFieldJoiner	= structure.HandlerFieldJoiner;
	fieldsJoiner		= structure.fieldsJoiner;
	aditionalFieldsRules= structure.AditionalFieldsRules;
	documentInfo		= structure.Document_info;
}