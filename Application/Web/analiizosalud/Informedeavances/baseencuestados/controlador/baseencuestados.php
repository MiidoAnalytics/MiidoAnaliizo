<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../../informeAvances/modelo/classinformeAvances.php';
    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeAvances::ObtenerProyectosUsuerId($idusuario);

        $TablaRelacionPersonasEncuestadas = InformeAvances::recuperarRelacionPersonasEncuestadas($proyecto, $encuesta);
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeAvances::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/baseencuestados.php';
} else {    
    require_once './../../sitemedia/html/pageerror.php';
}
?>