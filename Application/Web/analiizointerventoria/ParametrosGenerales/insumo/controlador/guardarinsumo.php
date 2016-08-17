<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
@session_start();

require_once '../modelo/classinsumo.php';

$insumo_id = (isset($_REQUEST['insumo_id'])) ? $_REQUEST['insumo_id'] : null;

if ($insumo_id) {
    $insumo = Insumo::buscarPorIdInsumo($insumo_id);
} else {
    $insumo = new Insumo();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strNombreInsumo = (isset($_POST['strNombreInsumo'])) ? $_POST['strNombreInsumo'] : null;

    $insumo->setNombreInsumo($strNombreInsumo);
    $insumo->guardar();

    header('Location: insumo.php');
} else {
    include '../vista/guardarinsumo.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>
