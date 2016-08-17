<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classinsumo.php';

    $insumos = Insumo::recuperarTodos();

    require_once '../vista/insumo.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>