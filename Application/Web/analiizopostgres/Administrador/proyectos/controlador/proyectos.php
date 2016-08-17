<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
            require_once '../modelo/classproyectos.php';

            $proyectos = Proyectos::getAllProyectos();
            
            require_once '../vista/proyectos.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}

?>