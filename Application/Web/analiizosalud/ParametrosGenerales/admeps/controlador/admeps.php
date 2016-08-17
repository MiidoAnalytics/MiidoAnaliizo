<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
	
    require_once '../modelo/classeps.php';

    $epsAll = Eps::getAllEps();
            
    require_once '../vista/admeps.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>