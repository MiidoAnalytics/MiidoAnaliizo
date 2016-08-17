<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classproyectosuser.php';

$idusuarios = (isset($_REQUEST['idusuarios'])) ? $_REQUEST['idusuarios'] : null;
$intidproyecto = (isset($_REQUEST['intidproyecto'])) ? $_REQUEST['intidproyecto'] : null;

if($idusuarios && $intidproyecto) {

    ProyectosUser::deleterelUserProyecto($idusuarios, $intidproyecto);

    header('Location: asignarproyectos.php');
}
?>