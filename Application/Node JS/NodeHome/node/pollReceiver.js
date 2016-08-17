#!/usr/bin/env node

var amqp = require('amqplib/callback_api');

amqp.connect('amqp://localhost', function(err, conn) {
  conn.createChannel(function(err, ch) {
    var q = 'PollQueue';

    ch.assertQueue(q, {durable: true});
    console.log(" [*] Waiting for messages in %s. To exit press CTRL+C", q);
    ch.consume(q, function(poll) {
      console.log(" <PollConsumer> Received %s </PollConsumer>", poll.content.toString());
      var jsonPoll = JSON.parse(poll.content.toString());

      var pg = require('pg');
      var conString = "postgres://postgres:Miido2015@localhost:5432/postgres";
      var resultado;
      var client = new pg.Client(conString);
      client.connect();

      var query = "INSERT INTO administracion.poll (pollcontent,idpollstructure) VALUES ('"+JSON.stringify(jsonPoll)+"',"+jsonPoll.DOCUMENTINFO['structureid']+");";

      client.query(query, function(err, resul){
      	if(err){
      		console.log(err);
      	}else{
      		console.log(resul);
      	}
      });

    }, {noAck: true});
  });
});
