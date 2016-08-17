var AWS = require('aws-sdk'),
    awsCredentialsPath = './aws.credentials.json',
    sqsQueueUrl = 'https://sqs.us-west-2.amazonaws.com/948221686519/ANALIIZO',
    sqs;

// Load credentials from local json file
AWS.config.loadFromPath(awsCredentialsPath);
// Instantiate SQS client
sqs = new AWS.SQS().client;

sqs.receiveMessage({
   QueueUrl: sqsQueueUrl,
   MaxNumberOfMessages: 1, // how many messages do we wanna retrieve?
   VisibilityTimeout: 60, // seconds - how long we want a lock on this job
   WaitTimeSeconds: 3 // seconds - how long should we wait for a message?
 }, function(err, data) {
   // If there are any messages to get
   if (data.Messages) {
      // Get the first message (should be the only one since we said to only get one above)
      var message = data.Messages[0],
          body = JSON.parse(message.Body);
      // Now this is where you'd do something with this message
      doSomethingCool(body, message);  // whatever you wanna do
      // Clean up after yourself... delete this message from the queue, so it's not executed again
      removeFromQueue(message);  // We'll do this in a second
   }
 });