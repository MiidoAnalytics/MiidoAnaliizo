<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

	require_once '../modelo/classmedicine.php';

	$medicine_id = (isset($_REQUEST['medicine_id'])) ? $_REQUEST['medicine_id'] : null;

	if ($medicine_id) {
	    $medicine = Medicine::searchById($medicine_id);
	} else {
	    $medicine = new Medicine();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $strCodMedicine = (isset($_POST['strCodMedicine'])) ? $_POST['strCodMedicine'] : null;
	    $strMedicineName = (isset($_POST['strMedicineName'])) ? $_POST['strMedicineName'] : null;

	    $medicine->setCodMedicine($strCodMedicine);
	    $medicine->setNombreMedicine($strMedicineName);

	    $medicine->guardar();

	    header('Location: ../controlador/medicine.php');
	} else {
	    include '../vista/guardarmedicine.php';
	}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>