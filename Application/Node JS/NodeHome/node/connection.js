
var constants =  require('./constants.js');

exports.connection = function () 
{
	var mysql = require('mysql');
	var connectionTemp = mysql.createConnection({
		host     : constants.host,
		user     : constants.user,
		password : constants.password,
		database : constants.database
	});
	
	return(connectionTemp);
}

