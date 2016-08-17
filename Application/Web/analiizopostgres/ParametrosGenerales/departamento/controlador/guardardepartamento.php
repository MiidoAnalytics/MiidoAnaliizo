<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classdepartamento.php';
    require_once '../../pais/modelo/classpais.php';

    $departamento_id = (isset($_REQUEST['departamento_id'])) ? $_REQUEST['departamento_id'] : null;

    if ($departamento_id) {
        $departamento = Departament::buscarPorId($departamento_id);
        $strCodidPais = $departamento->getStrCodCountry();
        $paisSelected = Pais::buscarPorCod($strCodidPais);
        $countryName = $paisSelected->getNombreidPais();
        $codCountry = $paisSelected->getCodidPais();
    } else {
        $departamento = new Departament();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $strCodCountry = (isset($_POST['strCodCountry'])) ? $_POST['strCodCountry'] : null;
        $strCodDepartamento = (isset($_POST['strCodDepartamento'])) ? $_POST['strCodDepartamento'] : null;
        $strNombreDepartamento = (isset($_POST['strNombreDepartamento'])) ? $_POST['strNombreDepartamento'] : null;
        $departamento->setStrCodCountry($strCodCountry);
        $departamento->setStrCodDepartament($strCodDepartamento);
        $departamento->setStrNombreDepartament($strNombreDepartamento);
        $departamento->guardar();

        header('Location: departamento.php');
    } else {
        include '../vista/guardardepartamento.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>