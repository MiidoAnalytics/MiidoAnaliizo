<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classdepartamento.php';

    $departamentos = Departament::recuperarTodos();

    require_once '../vista/departamento.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>