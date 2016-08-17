<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classoccupation.php';

    $Occupations = Occupation::getAllOccupations();

    require_once '../vista/occupation.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}            
?>
