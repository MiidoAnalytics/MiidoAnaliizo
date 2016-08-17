<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
@session_start();

require_once '../modelo/classobservacion.php';

$observacion_id = (isset($_REQUEST['observacion_id'])) ? $_REQUEST['observacion_id'] : null;

if ($observacion_id) {
    $observacion = Observacion::buscarPorIdObservacion($observacion_id);
} else {
    $observacion = new Observacion();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strNombreObservacion = (isset($_POST['strNombreObservacion'])) ? $_POST['strNombreObservacion'] : null;

    $observacion->setNombreObservacion($strNombreObservacion);
    $observacion->guardar();

    header('Location: observacion.php');
} else {
    include '../vista/guardarobservacion.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>
