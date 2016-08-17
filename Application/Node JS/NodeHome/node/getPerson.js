var redis = require('redis');
var REDIS_URL = 'localhost';
var REDIS_PORT = 6379;
var client = redis.createClient(REDIS_PORT, REDIS_URL);
 
client.on('connect', function() {
    console.log('connected');
});

/*
client.set('123456', resultado, function(err, reply) {
  console.log(reply);
});
*/

client.get('Medicament', function(err, object) {
    console.log(object);
});
