<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
        require_once '../modelo/classencinterviewer.php';
   	    $allRegisters = EncuestaInterviewer::getAllRegisters();
        require_once '../vista/asignacionencuestas.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}

?>