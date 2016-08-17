<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
@session_start();

require_once '../modelo/classpais.php';

$pais_id = (isset($_REQUEST['pais_id'])) ? $_REQUEST['pais_id'] : null;

if ($pais_id) {
    $pais = Pais::buscarPorId($pais_id);
} else {
    $pais = new Pais();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $strCodidPais = (isset($_POST['strCodidPais'])) ? $_POST['strCodidPais'] : null;
    $strNombreidPais = (isset($_POST['strNombreidPais'])) ? $_POST['strNombreidPais'] : null;
    $pais->setCodidPais($strCodidPais);
    $pais->setNombreidPais($strNombreidPais);
    $pais->guardar();
    //$pais->guardarAuditoria();
    header('Location: pais.php');
} else {
    include '../vista/guardarpais.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>
