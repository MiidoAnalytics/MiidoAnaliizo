// Require the demo configuration. This contains settings for this demo, including
// the AWS credentials and target queue settings.
//var config = require( "./caws.credentials.json" );


// Require libraries.
var aws = require( "aws-sdk" );
var Q = require( "q" );
var chalk = require( "chalk" );
//var chalk = require( "json" );

// Create an instance of our SQS Client.
var sqs = new aws.SQS({
	/*region: config.aws.region,
	accessKeyId: config.aws.accessID,
	secretAccessKey: config.aws.secretKey,*/
	//region: config.aws.region,
	region: 'us-west-2',
	//accessKeyId: config.aws.accessID,
	accessKeyId: 'AKIAIJGKJHAHCQLJ5CIQ',
	//secretAccessKey: config.aws.secretKey,
	secretAccessKey: '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',

	// For every request in this demo, I'm going to be using the same QueueUrl; so,
	// rather than explicitly defining it on every request, I can set it here as the
	// default QueueUrl to be automatically appended to every request.
	params:	{
		//QueueUrl: config.aws.queueUrl
		QueueUrl: 'https://sqs.us-west-2.amazonaws.com/948221686519/ANALIIZO'
	}
});

// Proxy the appropriate SQS methods to ensure that they "unwrap" the common node.js
// error / callback pattern and return Promises. Promises are good and make it easier to
// handle sequential asynchronous data.
var receiveMessage = Q.nbind( sqs.receiveMessage, sqs );
var deleteMessage = Q.nbind( sqs.deleteMessage, sqs );


// ---------------------------------------------------------- //
// ---------------------------------------------------------- //


// When pulling messages from Amazon SQS, we can open up a long-poll which will hold open
// until a message is available, for up to 20-seconds. If no message is returned in that
// time period, the request will end "successfully", but without any Messages. At that
// time, we'll want to re-open the long-poll request to listen for more messages. To
// kick off this cycle, we can create a self-executing function that starts to invoke
// itself, recursively.
(function pollQueueForMessages() 
{
	console.log( chalk.yellow( "Starting long-poll operation." ) );

	// Pull a message - we're going to keep the long-polling timeout short so as to
	// keep the demo a little bit more interesting.
	receiveMessage({
		WaitTimeSeconds: 3, // Enable long-polling (3-seconds).
		VisibilityTimeout: 10
	})
	.then(
		function handleMessageResolve( data ) 
		{
			// If there are no message, throw an error so that we can bypass the
			// subsequent resolution handler that is expecting to have a message
			// delete confirmation.
			if ( ! data.Messages ) 
			{
				throw(
					workflowError(
						"EmptyQueue",
						new Error( "No hay mensajes para procesar." )
					)
				);
			}

			
			//console.log(data);
			//console.log( chalk.green( "Identificador:", data.Messages[ 0 ].MessageId ) );
			//console.log( chalk.green( "Mensaje:", data.Messages[ 0 ].Body ) );
		
		
		//console.log(data.Messages[ 0 ].Body);
		
		/*
		//require node modules (see package.json)
var MongoClient = require('mongodb').MongoClient, format = require('util').format;

//connect away
MongoClient.connect('mongodb://localhost:27017/pruebas', function(err, db) {
  if (err) throw err;
  console.log("Connected to Database");

  //simple json record
	//var document = {"name":"maria", "title":"About MongoDB"};
	var document = JSON.parse(data.Messages[ 0 ].Body);
  
	//insert record
	db.collection('personas').insert(document, function(err, records) {
		if (err) throw err;
		//console.log("Record added as "+records[0]._id);
		console.log("Record added as ");
	});
});
		*/
		
		
		//conexiòn postgres
		var pg = require('pg'); 
		//cadena conexion postgres
		var conString = "pg://postgres:Miido2015@localhost:5432/postgres";
		var client = new pg.Client(conString);
		client.connect();
		
		client.query('CREATE TABLE IF NOT EXISTS prueba.items(id SERIAL PRIMARY KEY, text jsonb not null)');
		
		client.query("INSERT INTO prueba.items(text) values($1)", [JSON.parse(data.Messages[ 0 ].Body)]);
		
		//var query = client.query("SELECT id, text FROM prueba.items ORDER BY id");
		/*query.on("row", function (row, result) {
			result.addRow(row);
		});*/
		/*query.on("end", function (result) {
			console.log(JSON.stringify(result.rows, null, "    "));
			client.end();
		});*/
		
		
		
			//lets require/import the mongodb native drivers.
			var mongodb = require('mongodb');
			
			//We need to work with "MongoClient" interface in order to connect to a mongodb server.
			var MongoClient = mongodb.MongoClient;
			

			// Connection URL. This is where your mongodb server is running.
			var url = 'mongodb://localhost:27017/pruebas';

			// Use connect method to connect to the Server
			MongoClient.connect(url, function (err, db) 
			{
				if (err) 
				{
					console.log('No se puede conectar al servidor MongoDB. Error:', err);
				} 
				else 
				{    
					console.log('Connection established to', url);

					// Get the documents collection
					var collection = db.collection('personas');

					//Create some personas    
					//var persona1 = data;
					//var message = JSON.stringify(data);
					//message = JSON.parse(message);
					
										
					
					//var message = JSON.stringify(chalk.green( "Mensaje:", data.Messages[ 0 ].Body ) );
					//message = JSON.parse(message);
					
					
					//var persona1 = message;
					//var persona1 = data;
					var persona1 = JSON.parse(data.Messages[ 0 ].Body);
	
					//console.log(persona1);

					// Insert some personas
					//collection.insert([user1, user2, user3], function (err, result) {
					collection.insert([persona1], function (err, result) 
					{
						if (err) 
						{
							console.log(err);
						} 
						else 
						{
							//console.log('Inserted %d documents into the "personas" collection. The documents inserted with "_id" are:', result.length, result);
							console.log('El registro se guardo satisfactoriamente');
						}
						
						//Close connection
						db.close();
					});
				}
			});		

			
			
			
			
			
			
			
			
						
			console.log( chalk.green( "Deleting:", data.Messages[ 0 ].MessageId ) );

			// Now that we've processed the message, we need to tell SQS to delete the
			// message. Right now, the message is still in the queue, but it is marked
			// as "invisible". If we don't tell SQS to delete the message, SQS will
			// "re-queue" the message when the "VisibilityTimeout" expires such that it
			// can be handled by another receiver.
			return(
				deleteMessage({
					ReceiptHandle: data.Messages[ 0 ].ReceiptHandle
				})
			);

		}
	)
	.then(
		function handleDeleteResolve( data ) 
		{
			console.log( chalk.green( "Message Deleted!" ) );
		}
	)

	// Catch any error (or rejection) that took place during processing.
	.catch(
		function handleError( error ) 
		{
			// The error could have occurred for both known (ex, business logic) and
			// unknown reasons (ex, HTTP error, AWS error). As such, we can treat these
			// errors differently based on their type (since I'm setting a custom type
			// for my business logic errors).
			switch ( error.type ) 
			{
				case "EmptyQueue":
					console.log( chalk.cyan( "Error esperado:", error.message ) );
				break;
				default:
					console.log( chalk.red( "Error Inesperado:", error.message ) );
				break;
			}

		}
	)

	// When the promise chain completes, either in success of in error, let's kick the
	// long-poll operation back up and look for moar messages.
	.finally( pollQueueForMessages );

})();

// When processing the SQS message, we will use errors to help control the flow of the
// resolution and rejection. We can then use the error "type" to determine how to
// process the error object.
function workflowError( type, error ) 
{
	error.type = type;
	return( error );
}