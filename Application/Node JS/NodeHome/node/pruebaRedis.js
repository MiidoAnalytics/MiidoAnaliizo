var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var redis = require("redis");
var client = redis.createClient();

http.listen(1508, function(){
  console.log('listening on *:1508');
});

io.on('connection', function(socket){
	socket.on('pruebaPollIncoming', function(poll){
	    client.set ("pruebaPoll", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('pruebaPollIncoming Data saved!');
	      	}
	    });
	});
	socket.on('pruebaPollOutgoing', function(poll){
		if (poll == "requestPollData") {
			client.get("pruebaPoll", function(err, value) {
			    try {
			    	console.log((value));
			    	io.emit('pruebaPollOutgoing', (value));
			    } catch (e) {
			    	console.log(e);
			    }
			});
		}
	});
});