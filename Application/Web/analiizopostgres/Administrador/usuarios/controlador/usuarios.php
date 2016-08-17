<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
            require_once '../modelo/classusuarios.php';

            $usuariosAll = Usuarios::getAllUsuarios();
            
            require_once '../vista/usuarios.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}

?>