<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classequipo.php';

    $equipos = Equipo::recuperarTodos();

    require_once '../vista/equipo.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>