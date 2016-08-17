<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classtown.php';

    $municipios = Town::recuperarTodos();

    require_once '../vista/town.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>