<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classdisease.php';

    $diseases = Disease::getAll();

    require_once '../vista/disease.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>