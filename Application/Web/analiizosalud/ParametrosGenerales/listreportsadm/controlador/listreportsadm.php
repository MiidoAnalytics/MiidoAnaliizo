<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {           
    require_once '../modelo/classreports.php';
    
    $reports = Report::getAll();
    
    require_once '../vista/listreportsadm.php';
       } else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>