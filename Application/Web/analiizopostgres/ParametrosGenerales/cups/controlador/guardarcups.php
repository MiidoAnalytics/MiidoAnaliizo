<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

	require_once '../modelo/classcups.php';

	$cups_id = (isset($_REQUEST['cups_id'])) ? $_REQUEST['cups_id'] : null;

	if ($cups_id) {
	    $cups = Cups::searchById($cups_id);
	} else {
	    $cups = new Cups();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $strCodCups = (isset($_POST['strCodCups'])) ? $_POST['strCodCups'] : null;
	    $strCupsName = (isset($_POST['strCupsName'])) ? $_POST['strCupsName'] : null;

	    $cups->setCodCups($strCodCups);
	    $cups->setCupsName($strCupsName);

	    $cups->insert();

	    header('Location: ../controlador/cups.php');
	} else {
	    include '../vista/guardarcups.php';
	}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>