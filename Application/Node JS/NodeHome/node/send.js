#!/usr/bin/env node

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var amqp = require('amqplib/callback_api');

io.on('connection', function(socket){
	console.log("Conexión establecida");
});

http.listen(3001, function(){
	console.log('Escuchando comunicacíon en el puerto *:3001');
});

io.on('connection', function(socket){
	socket.on('enqueue', function(msg){
		amqp.connect('amqp://localhost', function(err, conn) {
			conn.createChannel(function(err, ch) {
				var q = 'hello';
				ch.assertQueue(q, {durable: false});
				ch.sendToQueue(q, new Buffer(msg));
				console.log(" [x] Sent '"+msg+"'");
			});
			//setTimeout(function() { conn.close(); process.exit(0) }, 500);
		});
	});
});

