<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classobservacion.php';

    $observacions = Observacion::recuperarTodos();

    require_once '../vista/observacion.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>