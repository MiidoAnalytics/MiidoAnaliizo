<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';
    
    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;

    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $namePro = (isset($_REQUEST['namePro'])) ? $_REQUEST['namePro'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = Report::ObtenerProyectosUsuerId($idusuario);
        
        $datosBD = Report::obtenerColumnasBD($proyecto, $encuesta); 
        //print_r($datosBD); die();      
    }else{
        $idusuario = $_SESSION['userid'];
        $proyectos = Report::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/reporteador.php';
    
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>
