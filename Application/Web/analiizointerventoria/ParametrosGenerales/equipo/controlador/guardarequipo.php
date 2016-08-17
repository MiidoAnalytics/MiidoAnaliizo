<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
@session_start();

require_once '../modelo/classequipo.php';

$equipo_id = (isset($_REQUEST['equipo_id'])) ? $_REQUEST['equipo_id'] : null;

if ($equipo_id) {
    $equipo = Equipo::buscarPorIdEquipo($equipo_id);
} else {
    $equipo = new Equipo();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strNombreEquipo = (isset($_POST['strNombreEquipo'])) ? $_POST['strNombreEquipo'] : null;

    $equipo->setNombreEquipo($strNombreEquipo);
    $equipo->guardar();

    header('Location: equipo.php');
} else {
    include '../vista/guardarequipo.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>
