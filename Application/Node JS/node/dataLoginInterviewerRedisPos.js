/*
var resultado;

var mysql = require('mysql');
var connection = mysql.createConnection({
   host: 'localhost',
   user: 'root',
   password: 'Miido2015',
   database: 'encuestas2',
   port: 3306
});
connection.connect(function(error)
{
   if(error)
   {
      throw error;
   }
   else
   {
      console.log('Conexion correcta.');
   }
});
var query = connection.query('call spLoginInterviewer;', function(error, result)
{
	if(error)
	{
		throw error;
	}else
	{
		resultado = JSON.stringify(result);      
         
		if(resultado.length > 0)
		{	
		//	console.log(resultado.length);	
			var redis = require('redis');
			var client = redis.createClient();
 
			client.on('connect', function() 
			{
				console.log('connected');
			});

			client.set('LoginInterviewer', resultado, function(err, reply) 
			{
				console.log(reply);
			});

			client.get('LoginInterviewer', function(err, object) 
			{
				console.log(object);
			});
		}
		else
		{
			console.log('Registro no encontrado');
		}
	}
}
);
connection.end();
*/

///*
var pg = require('pg');
var conString = "postgres://postgres:Miido2015@localhost:5432/postgres";
var resultado;
var client = new pg.Client(conString);
client.connect();

var query = client.query("SELECT * FROM administracion.splogininterviewer();",function(err,result){
	if(err){
		console.log('Error de conexión');
	}else{
	    
	    if(result.rows.length > 0){
	    	var json = JSON.parse("[]");
	    	for(var i =0; i< result.rows.length;i++){
	    		json.push(result.rows[i]);
	    	}
	    	
	    	console.log(json);

	    	var redis = require('redis');
			var clientRedis = redis.createClient();
 
			clientRedis.on('connect', function() 
			{
				console.log('connected');
			});

			clientRedis.set('LoginInterviewer', JSON.stringify(json), function(err, reply) 
			{
				console.log(reply);
			});

			clientRedis.get('LoginInterviewer', function(err, object) 
			{
				console.log(object);
			});

	    }else{
	    	console.log('Registro no encontrado');
	    }
    }
});
//fired after last row is emitted

/*query.on('row', function(row) {
	resultado = JSON.stringify(row);
	if(resultado && resultado.length>0){

		var redis = require('redis');
			var client = redis.createClient();
 
			client.on('connect', function() 
			{
				console.log('connected');
			});

			client.set('LoginInterviewer', resultado, function(err, reply) 
			{
				console.log(reply);
			});

			client.get('LoginInterviewer', function(err, object) 
			{
				console.log(object);
			});
	}else{
		console.log('Registro no encontrado');
	}
});*/

query.on('end', function() { 
  client.end();
});
//*/
