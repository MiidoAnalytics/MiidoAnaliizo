
var redis = require('redis');
var REDIS_URL = 'localhost';
var REDIS_PORT = 6379;
var client = redis.createClient(REDIS_PORT, REDIS_URL);
 
client.on('connect', function() {
    console.log('connected');
});

client.get('Departament', function(err, object) {
    console.log(object);
});
