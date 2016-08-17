<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classmedicine.php';

    $medicines = Medicine::recuperarTodos();

    require_once '../vista/medicine.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>