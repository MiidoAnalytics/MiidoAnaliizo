var resultado;

var mysql = require('mysql');
var connection = mysql.createConnection({
   host: 'localhost',
   user: 'root',
   password: 'Miido2015',
   database: 'batman',
   port: 3306
});
connection.connect(function(error)
{
   if(error)
   {
      throw error;
   }
   else
   {
      console.log('Conexion correcta.');
   }
});


for(var i = 0; i < 3200;i++)
{

var query = connection.query('select detalleFecha from json where idjson = '+i+';', function(error, result)
//var query = connection.query('select detalleFecha from json limit 1;', function(error, result)
{
	if(error)
	{
		throw error;
	}else
	{
		
		//resultado = JSON.stringify(result[0].detalleFecha);         
		//resultado = JSON.stringify(result);         
         
		 if(result.length > 0)
		//if(resultado.length > 0)
		{			
	
			resultado = JSON.stringify(result[0].detalleFecha);         
	
			// Require libraries.
			var aws = require( "aws-sdk" );
			var Q = require( "q" );
			var chalk = require( "chalk" );
			
			
			var sqs = new aws.SQS({
				//region: config.aws.region,
				region: 'us-west-2',
				//accessKeyId: config.aws.accessID,
				accessKeyId: 'AKIAIJGKJHAHCQLJ5CIQ',
				//secretAccessKey: config.aws.secretKey,
				secretAccessKey: '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',

				// For every request in this demo, I'm going to be using the same QueueUrl; so,
				// rather than explicitly defining it on every request, I can set it here as the
				// default QueueUrl to be automatically appended to every request.
				params: {
					//QueueUrl: config.aws.queueUrl
					QueueUrl: 'https://sqs.us-west-2.amazonaws.com/948221686519/ANALIIZO'
				}
			});
			
			var sendMessage = Q.nbind( sqs.sendMessage, sqs );
			
			
			// Now that we have a Q-ified method, we can send the message.
			sendMessage({
				//MessageBody: "This is my first ever SQS request... evar!"
				MessageBody: JSON.parse(resultado)
			})
			.then(
				function handleSendResolve( data ) {

					console.log( chalk.green( "Message sent:", data.MessageId ) );

				}
			)

			// Catch any error (or rejection) that took place during processing.
			.catch(
				function handleReject( error ) {

					console.log( chalk.red( "Unexpected Error:", error.message ) );

				}
			);
			
			
			
		}
		else
		{
			console.log('Registro no encontrado');
		}
	}
}
);

}

connection.end();