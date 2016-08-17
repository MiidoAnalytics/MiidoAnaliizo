<?php

    // datos para la coneccion a mysql

    define('DB_SERVER', 'localhost');
    define('DB_NAME', 'analiizo');
    define('DB_USER', 'root');
    define('DB_PASS', 'Miido2015');

    $con = mysql_connect(DB_SERVER, DB_USER, DB_PASS);

    mysql_select_db(DB_NAME, $con);
    mysql_query("SET NAMES 'utf8'");

    
    //Conexión postgresSQL
    
    $user = "postgres";
    $password = "Miido2015";
    $dbname = "postgres";
    $port = "5432";
    $host = "localhost";   
    
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
    pg_query("SET NAMES 'utf8'");     
    
?>
