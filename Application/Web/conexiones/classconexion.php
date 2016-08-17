<?php

if (!defined('CONTROLADOR'))
    exit;

class Conexion_1 extends PDO {

    private $tipo_de_base = 'mysql';
    private $host = 'localhost';
    private $nombre_de_base = 'encuestas2';
    private $usuario = 'root';
    private $contrasena = 'Miido2015';

    public function __construct() {
        //Sobreescribo el método constructor de la clase PDO.
        try {
            parent::__construct($this->tipo_de_base . ':host=' . $this->host . ';dbname=' . $this->nombre_de_base, $this->usuario, $this->contrasena);
        } catch (PDOException $e) {
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
        }
    }

}

class ConexionAnaliizo_1 extends PDO {

    private $tipo_de_base = 'mysql';
    private $host = 'localhost';
    private $nombre_de_base = 'analiizoce';
    private $usuario = 'root';
    private $contrasena = 'Miido2015';

    public function __construct() {
        //Sobreescribo el método constructor de la clase PDO.
        try {
            parent::__construct($this->tipo_de_base . ':host=' . $this->host . ';dbname=' . $this->nombre_de_base, $this->usuario, $this->contrasena);
        } catch (PDOException $e) {
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
        }
    }

}

class ConexionAnaliizoPostgres extends PDO
{
 
	//nombre base de datos
	private $dbname = "postgres";
	//nombre servidor
	private $host = "localhost";
	//nombre usuarios base de datos
	private $user = "postgres";
	//password usuario
	private $pass = 'Miido2015';
	//puerto postgreSql
	private $port = 5432;
	private $dbh;
 
	//creamos la conexión a la base de datos prueba
	public function __construct() 
	{
	    try {
 
	        $this->dbh = parent::__construct("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->pass");
 
	    } catch(PDOException $e) {
 
	        echo  $e->getMessage(); 
 
	    }
 
	}
 
	//función para cerrar una conexión pdo
	public function close_con() 
	{
 
    	$this->dbh = null; 
 
	}
 
}
