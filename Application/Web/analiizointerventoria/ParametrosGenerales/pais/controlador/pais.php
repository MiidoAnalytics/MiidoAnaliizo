<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classpais.php';

    $paises = Pais::recuperarTodos();

    require_once '../vista/pais.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>