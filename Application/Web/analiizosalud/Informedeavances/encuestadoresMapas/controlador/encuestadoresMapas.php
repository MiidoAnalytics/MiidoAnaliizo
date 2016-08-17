<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classencuestadoresmapas.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $encuestador_id = (isset($_REQUEST['encuestador_id'])) ? $_REQUEST['encuestador_id'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = EncuestadoresMapas::ObtenerProyectosUsuerId($idusuario);

        if ($encuestador_id) {
            $TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestador($encuestador_id, $proyecto, $encuesta);
            //print_r($TablaEncuestasxEncuestador); die();
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = EncuestadoresMapas::ObtenerProyectosUsuerId($idusuario);
    }
    /*$encuestador_id = (isset($_REQUEST['encuestador_id'])) ? $_REQUEST['encuestador_id'] : null;

    if ($encuestador_id) {
        $TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestador($encuestador_id);
    } else {

        //$TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestadores();        
    }

    $TablaEncuestadorxEncuesta = EncuestadoresMapas::recuperarEncuestadorxEncuesta();*/

    require_once '../vista/encuestadoresmapas.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>