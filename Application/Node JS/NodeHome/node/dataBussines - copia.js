var resultado;

var mysql = require('mysql');
var connection = mysql.createConnection({
   host: 'localhost',
   user: 'root',
   password: 'Matemavi71',
   database: 'encuestas',
   port: 3306
});
connection.connect(function(error){
   if(error){
      throw error;
   }else{
      console.log('Conexion correcta.');
   }
});
var query = connection.query('call spDepartamentNew', function(error, result){
      if(error){
         throw error;
      }else{
         resultado = JSON.stringify(result);         
         //resultado = result;
         if(resultado.length > 0){
            //console.log(resultado[0].nombre + ' ' + resultado[0].apellido + ' / ' + resultado[0].biografia);
            //console.log(resultado);


//var resultado1 = '[{"codDepartamentoDane":"91","nombreDepartamento":"AMAZONAS"},{"codDepartamentoDane":"05","nombreDepartamento":"ANTIOQUIA"}]';

var redis = require('redis');
var client = redis.createClient();
 
client.on('connect', function() {
    console.log('connected');
});

client.set('123456', resultado, function(err, reply) {
  console.log(reply);
});

client.get('123456', function(err, object) {
    console.log(object);
});






         }else{
            console.log('Registro no encontrado');
         }
      }
   }
);
connection.end();

//console.log(resultado);


//var resultado1 = '[{"codDepartamentoDane":"91","nombreDepartamento":"AMAZONAS"},{"codDepartamentoDane":"05","nombreDepartamento":"ANTIOQUIA"}]';

//console.log(resultado1);
//console.log(resultado1.replace(/blue/g, "red");


//console.log(query);
/*
var redis = require('redis');
var client = redis.createClient();
 
client.on('connect', function() {
    console.log('connected');
});

client.set('12345', resultado1, function(err, reply) {
  console.log(reply);
});

client.get('12345', function(err, object) {
    console.log(object);
});

*/








/*
var createConnection =  require('./dataLayer.js');

var resultado;
var vResultadoTexto;

//Envia las consultas al datalayer
//var http = require("http");

//http.createServer(function(request, response) 
//{
  //response.writeHead(200, {"Content-Type": "text/html"});

  createConnection.executeQuery('call spDepartamentNew',function(b)
  {  
    resultado = b;
    //console.log(resultado);
  });

   
  vResultadoTexto = JSON.stringify(resultado);   
   console.log(vResultadoTexto);

*/
  //response.end();
//}).listen(8888);

/*
var vKey = '123';
var vResultado = resultado;//resultado;

var redis = require('redis');
var client = redis.createClient();

client.on("error", function (err) {
    console.log("Error " + err);
});
 
client.on('connect', function() {
    console.log('connected');
});


client.hmset(vKey, vResultado);
 
client.hgetall(vKey, function(err, object) {
    console.log(object);
});
*/

/*
client.set(vKey, vResultadoTexto, function(err, reply) {
  console.log(reply);
});

console.log(vResultadoTexto);
*/
//console.log(vKey);
//console.log(vResultadoTexto);

//var vResultado = '[{"CC", NULL, NULL, 64580464, NULL, "SONIA ANDREA", "GONZALEZ GOMEZ", "1978-01-26"}, {"CC", NULL, NULL, 64588368, NULL, "MARIA YULET", "BELTRAN MARTINEZ", "1972-08-16"} ]';
/*var redis = require("redis")
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
}*/
