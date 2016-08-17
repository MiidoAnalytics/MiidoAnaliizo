<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../../../Administrador/menu/modelo/classroles.php';
    $rolesAll = Roles::getAllRoles();

    $RolMenuPadre = Roles::MenusPadres();           
            
    require_once '../vista/menuxrol.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>