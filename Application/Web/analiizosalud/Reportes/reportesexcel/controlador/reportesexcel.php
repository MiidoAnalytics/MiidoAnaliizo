<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
	require_once '../modelo/classreportesmapas.php';
	$proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;

    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;

    if ($encuesta && $proyecto) {
    	$idusuario = $_SESSION['userid'];
    	$proyectos = ReportesMapas::ObtenerProyectosUsuerId($idusuario);
    	require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';
    	$reports = Report::getAll();
    }else{
    	$idusuario = $_SESSION['userid'];
        $proyectos = ReportesMapas::ObtenerProyectosUsuerId($idusuario);
    }
    require_once '../vista/reports.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>