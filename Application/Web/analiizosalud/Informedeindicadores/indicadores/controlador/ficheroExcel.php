<?php

$titulo = $_POST['titulo'];

header("Content-type: application/vnd.ms-excel; charset=UTF-8'; name='excel'");
header("Content-Disposition: filename="."\"".$titulo.".xls". "\"");
header("Pragma: no-cache");
header("Expires: 0");

$datos= iconv("utf-8","iso-8859-1",$_POST['datos_a_enviar']);
echo $datos;

?>