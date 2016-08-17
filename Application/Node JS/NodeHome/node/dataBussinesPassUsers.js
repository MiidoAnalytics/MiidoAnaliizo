
var createConnection =  require('./dataLayer.js');

var resultado;

//Envia las consultas al datalayer
var http = require("http");

http.createServer(function(request, response) 
{
	response.writeHead(200, {"Content-Type": "text/html"});
  	//createConnection.executeQuery('select * from departamento limit 10;');
  	createConnection.executeQuery('call spPasswordUsers',function(b)
    {
		resultado = JSON.stringify(b);
    
		if(resultado.length > 0)
		{
			var redis = require('redis');
			var client = redis.createClient();
	 
			client.on('connect', function() 
			{
				console.log('connected');
			});

			client.set('PasswordUsers', resultado, function(err, reply) 
			{
				console.log(reply);
			});

			client.get('PasswordUsers', function(err, object) 
			{
				console.log(object);
			});
		}
		else
		{
		  console.log('Registro no encontrado');
		}		
  	});
		
		response.end();
		
}).listen(8888);