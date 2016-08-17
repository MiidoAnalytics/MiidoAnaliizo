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
var query = connection.query('select * from occupation limit 5;', function(error, result)
{
	if(error)
	{
		throw error;
	}else
	{
		resultado = JSON.stringify(result);         
         
		if(resultado.length > 0)
		{
			//console.log(resultado);
			
			var redis = require('redis');
			var client = redis.createClient();
 
			client.on('connect', function() 
			{
				console.log('connected');
			});

			client.set('123456', resultado, function(err, reply) 
			{
				console.log(reply);
			});

			client.get('123456', function(err, object) 
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
