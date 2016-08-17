<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classcups.php';

    $cupsAll = Cups::getAllCups();

    require_once '../vista/cups.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>