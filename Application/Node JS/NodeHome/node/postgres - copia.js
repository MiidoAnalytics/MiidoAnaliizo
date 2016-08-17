var pg = require('pg');

var conString = "pg://postgres:Miido2015@localhost:5432/postgres";
var client = new pg.Client(conString);
client.connect();

////var query = client.query('CREATE TABLE prueba.items(id SERIAL PRIMARY KEY, text jsonb not null)');
//query.on('end', function() { client.end(); });

client.query('CREATE TABLE IF NOT EXISTS prueba.items(id SERIAL PRIMARY KEY, text jsonb not null)');
// client.query("INSERT INTO emps(firstname, lastname) values($1, $2)", ['Ronald', 'McDonald']);
// client.query("INSERT INTO emps(firstname, lastname) values($1, $2)", ['Mayor', 'McCheese']);


client.query("INSERT INTO prueba.items(text) values($1)", [{  
   "GROUP":{  
      "Poblacion":"Barrio",
      "Familia":"apellido",
      "Direccion":"direccion",
      "Telefono":"123",
      "cugrfacoesvi":"1",
      "cupehaesvi":"3"
   },
   "HOUSE":{  
      "vivienda":"2 Propia pagando",
      "dormitorios":"4",
      "origenAgua":"2 Pozo con bomba",
      "ConsumoAgua":"Hervida",
      "Basura":"1 La recogen los servicios de aseo",
      "techo":"Bueno",
      "MatParedes":"1 Bloque, ladrillo, piedra, madera pulida",
      "MatPiso":"1 Alfombra o tapete, mármol, parqué, madera pulida y lacada",
      "MatTecho":"Desechos: Cartón, Lata, Sacos",
      "Alumbrado":"Vela",
      "atencion":"Muy fácil",
      "respuesta":"Muy fácil",
      "atencionPersonal":"Muy buena",
      "InfServicio":"Suficiente",
      "Instalaciones":"Muy buenas",
      "AtGeneral":"Muy buena",
      "VisionEPS":"Muy buena"
   },
   "PERSONS":[  
      {  
         "tipoId":"Menor sin identificación",
         "documento":"123",
         "nombre":"asd",
         "apellido":"asd",
         "fecNac":"2015-01-01",
         "Edad":"1",
         "genero":"Femenino",
         "Parentesco":"Jefe de familia",
         "Regimen":"Ninguno",
         "TipoAf":"Beneficiario",
         "Raza":"Blanca",
         "eps":"COMFASUCRE",
         "carnet":"1",
         "Ocupacion":"2421,Abogados ",
         "Desplazado":"SI",
         "NivelEst":"Ninguno",
         "perEnf1":"NO",
         "peso":"1",
         "talla":"2",
         "Oximetria":"3",
         "Odonto":"NO",
         "placa":"SI",
         "caries":"SI",
         "cepillado":"1",
         "maltrato":"NO"
      }
   ]
}]);

var query = client.query("SELECT id, text FROM prueba.items ORDER BY id");
query.on("row", function (row, result) {
    result.addRow(row);
});
query.on("end", function (result) {
    console.log(JSON.stringify(result.rows, null, "    "));
    client.end();
});