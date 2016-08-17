<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classdisease.php';

    $disease_id = (isset($_REQUEST['disease_id'])) ? $_REQUEST['disease_id'] : null;

    if ($disease_id) {
        $disease = Disease::searchById($disease_id);
    } else {
        $disease = new Disease();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $strCodDisease = (isset($_POST['strCodDisease'])) ? $_POST['strCodDisease'] : null;
        $strNombreDisease = (isset($_POST['strNombreDisease'])) ? $_POST['strNombreDisease'] : null;
        //$intIdclassification = (isset($_POST['intIdclassification'])) ? $_POST['intIdclassification'] : null;
        //$intIdComplement = (isset($_POST['intIdComplement'])) ? $_POST['intIdComplement'] : null;

        $disease->setNombreDisease($strNombreDisease);
        $disease->setCodDisease($strCodDisease);
        //$disease->setStrComplementDisease($intIdComplement);
        //$disease->setintIdclassification($intIdclassification);
        $disease->guardar();
        header('Location: disease.php');
    } else {
        include '../vista/guardardisease.php';
    }

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>