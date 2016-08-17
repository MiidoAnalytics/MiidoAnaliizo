<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if($flag == 1) {
    require_once '../modelo/classperfil.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];

        $proyectos = Perfil::ObtenerProyectosUsuerId($idusuario);

    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Perfil::ObtenerProyectosUsuerId($idusuario);
    } 
    require_once '../vista/perfil.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>