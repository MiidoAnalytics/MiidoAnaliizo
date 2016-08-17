<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
	require_once '../modelo/classtablets.php';

	$tablets_id = (isset($_REQUEST['tablet_id'])) ? $_REQUEST['tablet_id'] : null;

	if ($tablets_id) {
	    $tablets = Tablet::searchById($tablets_id);
	} else {
	    $tablets = new Tablet();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $strNomtablets = (isset($_POST['strNomtablets'])) ? $_POST['strNomtablets'] : null;
	    $strKeytablets = (isset($_POST['strKeytablets'])) ? $_POST['strKeytablets'] : null;

	    $tablets->setNomtablets($strNomtablets);
	    $tablets->setsKeytablets($strKeytablets);

	    $tablets->insert();

	    header('Location: ../controlador/tablets.php');
	} else {
	    include '../vista/guardartablets.php';
	}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>