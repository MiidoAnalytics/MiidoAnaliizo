<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classeps.php';

    $intIdEps = (isset($_REQUEST['intideps'])) ? $_REQUEST['intideps'] : null;
    if ($intIdEps) {
        $eps = Eps::searchById($intIdEps);

    } else {
        $eps = new Eps();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    	$intIdEps = (isset($_POST['intIdEps'])) ? $_POST['intIdEps'] : null;
        $strCodEps = (isset($_POST['strcodeps'])) ? $_POST['strcodeps'] : null;
        $strNamEps = (isset($_POST['strnameps'])) ? $_POST['strnameps'] : null;

        $eps->setintIdEps($intIdEps);
        $eps->setCodEps($strCodEps);
        $eps->setNamEps($strNamEps);

        $eps->insert();

        header('Location: ../controlador/admeps.php');
    } else {
        include '../vista/guardareps.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>