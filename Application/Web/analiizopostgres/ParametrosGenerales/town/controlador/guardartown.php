<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

require_once '../modelo/classtown.php';
require_once '../../departamento/modelo/classdepartamento.php';

$municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

if ($municipio_id) {
    $municipio = Town::buscarPorId($municipio_id);

} else {
    $municipio = new Town();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strCodDepartament = (isset($_POST['strCodDepartament'])) ? $_POST['strCodDepartament'] : null;
    $strCodTown = (isset($_POST['strCodTown'])) ? $_POST['strCodTown'] : null;
    $strTownName = (isset($_POST['strTownName'])) ? $_POST['strTownName'] : null;

    $municipio->setCodTown($strCodTown);
    $municipio->setNombreTown($strTownName);
    $municipio->setCodDepartment($strCodDepartament);

    $municipio->guardar();
    header('Location: town.php');
} else {
    include '../vista/guardartown.php';
}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>