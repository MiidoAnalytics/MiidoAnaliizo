#!/usr/bin/env node

var amqp = require('amqplib/callback_api');
var pg = require('pg');



amqp.connect('amqp://localhost', function(err, conn) {
  conn.createChannel(function(err, ch) {
    var q = 'invetoryQueue';

    ch.assertQueue(q, {durable: true});
    console.log(" [*] Waiting for messages in %s. To exit press CTRL+C", q);
    ch.consume(q, function(poll) {
      console.log(" <PollConsumer> Received %s </PollConsumer>", poll.content.toString());
      var jsonPoll = JSON.parse(poll.content.toString());

      
      var conString = "postgres://postgres:Miido2015@localhost:5432/postgres";
      var client = new pg.Client(conString);
      client.connect();
      var query = "INSERT INTO analiizo_interventoria.poll (pollcontent,idpollstructure) VALUES ('"+JSON.stringify(jsonPoll)+"',"+jsonPoll.DOCUMENTINFO['structureid']+");";

      var result = client.query(query, function(err, resul){
      	if(err){
      		console.log(err);
      	}else{
      		console.log(resul);
      	}
      });// client.query

      result.on("end",function(){
        client.end();
      });

    }, {noAck: true});// ch.consume
  });// conn.createChannel
});// amp.connect
