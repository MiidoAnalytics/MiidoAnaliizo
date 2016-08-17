
var createConnection =  require('./connection.js');

//Ejecta la consulta de archivos
exports.executeQuery = function (vStoredProcedure,callback) 
//function executeQuery(vQuery,callback) 
{
	var mysql = require('mysql');
	
	var connection = createConnection.connection();
	 
	connection.connect();		
	
	var resultado='';
	
	//connection.query('call' + vStoredProcedure + '(' + vParameters + ');', function(err, rows) 
	//connection.query('call' + vStoredProcedure, function(err, rows) 
	connection.query(vStoredProcedure, function(err, rows) 
	{
		resultado = rows;
		callback(rows);
	});
	 	
	connection.end();	
}