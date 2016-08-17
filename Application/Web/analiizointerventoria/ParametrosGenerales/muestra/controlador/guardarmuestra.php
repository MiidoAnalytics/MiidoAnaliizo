<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
@session_start();

require_once '../modelo/classmuestra.php';

$muestra_id = (isset($_REQUEST['muestra_id'])) ? $_REQUEST['muestra_id'] : null;

if ($muestra_id) {
    $muestra = Muestra::buscarPorIdMuestra($muestra_id);
} else {
    $muestra = new Muestra();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strNombreMuestra = (isset($_POST['strNombreMuestra'])) ? $_POST['strNombreMuestra'] : null;

    $muestra->setNombreMuestra($strNombreMuestra);
    $muestra->guardar();

    header('Location: muestra.php');
} else {
    include '../vista/guardarmuestra.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>
