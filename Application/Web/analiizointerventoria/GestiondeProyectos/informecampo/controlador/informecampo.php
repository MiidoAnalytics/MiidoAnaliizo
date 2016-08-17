<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classinformecampo.php';
    
    $informecampo = new InformeCampo();

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $nameProj = (isset($_REQUEST['nameProj'])) ? $_REQUEST['nameProj'] : null;
    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeCampo::ObtenerProyectosUsuerId($idusuario);

        $informecampo->setIdEncuesta($encuesta);
        $informecampo->setIdProyecto($proyecto);
        $informes = InformeCampo::recuperarInformes($encuesta, $proyecto);
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeCampo::ObtenerProyectosUsuerId($idusuario);
        $informes = InformeCampo::recuperarInformes($encuesta);

    }
    
    require_once '../vista/informecampo.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>