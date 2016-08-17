<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classlocationinterviewers.php';

    $LocationInterviewersAll = LocationInterviewers::getAll();

    require_once '../vista/locationinterviewers.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>