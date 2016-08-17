<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classmuestra.php';

    $muestras = Muestra::recuperarTodos();

    require_once '../vista/muestra.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>