<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
            require_once '../../menu/modelo/classroles.php';

            $rolesAll = Roles::getAllRoles();
            
            require_once '../vista/roles.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>