<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../../../Administrador/menu/modelo/classroles.php';

    $userrolAll = Roles::getAllUsuariosRol();
            
    require_once '../vista/usuarioxrol.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>