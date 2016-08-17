<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classproyectos.php';

$intidproyecto = (isset($_REQUEST['intidproyecto'])) ? $_REQUEST['intidproyecto'] : null;

if($intidproyecto) {

    Proyectos::deleterelProyecto($intidproyecto);

    Proyectos::deleterelProyectoUser($intidproyecto);
    
    header('Location: proyectos.php');
}
?>