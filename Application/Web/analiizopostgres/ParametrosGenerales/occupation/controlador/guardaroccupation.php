<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
	require_once '../modelo/classoccupation.php';
	//require_once 'modelos/classPais.php';

	$ocupacion_id = (isset($_REQUEST['Occupation_id'])) ? $_REQUEST['Occupation_id'] : null;

	if ($ocupacion_id) {
	    $Occupation = Occupation::searchById($ocupacion_id);
	} else {
	    $Occupation = new Occupation();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $strCodeOccupation = (isset($_POST['strCodeOccupation'])) ? $_POST['strCodeOccupation'] : null;
	    $strNameOccupation = (isset($_POST['strNameOccupation'])) ? $_POST['strNameOccupation'] : null;

	    $Occupation->setCodeOccupation($strCodeOccupation);
	    $Occupation->setNameOccupation($strNameOccupation);

	    $Occupation->insert();

	    header('Location: ../controlador/occupation.php');
	} else {
	    include '../vista/guardaroccupation.php';
	}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>