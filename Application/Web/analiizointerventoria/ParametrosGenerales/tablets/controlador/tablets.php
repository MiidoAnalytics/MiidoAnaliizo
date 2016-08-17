<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classtablets.php';

    $tabletsAll = Tablet::getAllTablets();

    require_once '../vista/tablets.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}       
?>