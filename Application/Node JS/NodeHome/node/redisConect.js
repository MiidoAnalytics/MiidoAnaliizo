
var redis = require('redis');
var client = redis.createClient();
 
client.on('connect', function() {
    console.log('connected');
});

/*client.set('framework', 'AngularJS', function(err, reply) {
  console.log(reply);
});
*/

client.hmset('frameworks', 'javascript', 'AngularJS', 'css', 'Bootstrap', 'node', 'Express');
 
client.hgetall('frameworks', function(err, object) {
    console.log(object);
});