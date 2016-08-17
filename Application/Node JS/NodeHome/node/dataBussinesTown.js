
var createConnection =  require('./dataLayer.js');

var resultado;

//Envia las consultas al datalayer
var http = require("http");

http.createServer(function(request, response) 
{
  response.writeHead(200, {"Content-Type": "text/html"});

  createConnection.executeQuery('call spTownNew',function(b)
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

      client.set('Town', resultado, function(err, reply) 
      {
        console.log(reply);
      });

      client.get('Town', function(err, object) 
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

/*
var vKey = '123';
var vResultado = resultado;
//var vResultado = '[{"CC", NULL, NULL, 64580464, NULL, "SONIA ANDREA", "GONZALEZ GOMEZ", "1978-01-26"}, {"CC", NULL, NULL, 64588368, NULL, "MARIA YULET", "BELTRAN MARTINEZ", "1972-08-16"} ]';
var redis = require("redis")
    , client = redis.createClient();
 
client.on("error", function (err) {
    console.log("Error " + err);
});
 
client.on("connect", runSample);
 
function runSample() {
    // Set a value
    client.set(vKey, vResultado, function (err, reply) {
        console.log(reply.toString());
    });
    // Get a value
    client.get(vKey, function (err, reply) {
        console.log(reply.toString());
    });
}
*/