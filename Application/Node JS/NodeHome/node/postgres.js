var pg = require('pg');

var conString = "pg://postgres:Miido2015@localhost:5432/postgres";
var client = new pg.Client(conString);
client.connect();

var query = client.query('CREATE TABLE prueba.items(id SERIAL PRIMARY KEY, text jsonb not null)');
query.on('end', function() { client.end(); });