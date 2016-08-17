<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('CONTROLADOR',true);

require_once '../conexiones/classconexion.php';

$TIME_ZONE = "America/Lima";
$DATE_FORMAT = "Y-m-d H:i:s";
$CHARSET_QUERY = "set names utf8";
$INSERT_RESOURCE_QUERY = "INSERT INTO analiizo_interventoria.pollresources (strname,strdescription,strmimetype,intidpoll,dtcreateddate) VALUES(?,?,?,?,?)";

$PARAM_DESCRIPTION = 'description';
$PARAM_POLL_ID = 'pollid';
$PARAM_RESOURCE = 'UpImage'; 

$ruta = basename($_FILES[$PARAM_RESOURCE]['name']);

if(move_uploaded_file($_FILES[$PARAM_RESOURCE]['tmp_name'], $ruta)){

	try{
		$connection = new ConexionAnaliizoPostgres();
		$connection->exec($CHARSET_QUERY);
		$query = $connection->prepare($INSERT_RESOURCE_QUERY);

		date_default_timezone_set($TIME_ZONE);
		$date = date($DATE_FORMAT);

		$query->bindParam(1, $_FILES[$PARAM_RESOURCE]['name'], PDO::PARAM_STR);
		$query->bindParam(2, $_POST[$PARAM_DESCRIPTION], PDO::PARAM_STR);
		$query->bindParam(3, $_FILES[$PARAM_RESOURCE]['type'], PDO::PARAM_STR);
		$query->bindParam(4, $_POST[$PARAM_POLL_ID], PDO::PARAM_INT);
		$query->bindParam(5, $date, PDO::PARAM_STR);

		$isInserted = $query->execute();
		//print_r($connection->errorInfo()); 
		if($isInserted){
			echo 'imagen subida a '.$ruta;
		}else{
			echo "Error al insertar el recurso";
		}
	}catch(Exception $ex){
		echo $ex;
	}finally{
		$connection->close_con();
	}

    
}else{
	print_r($_FILES);
}