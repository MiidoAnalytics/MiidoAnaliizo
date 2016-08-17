<?php
define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classinformeAvances.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeAvances::ObtenerProyectosUsuerId($idusuario);

        $TablaInformeEncuestadoresTiempo = InformeAvances::recuperarInformeEncuestadoresTiempo($proyecto, $encuesta);

    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = InformeAvances::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/informeAvances.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>