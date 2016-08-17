<?php

define('CONTROLADOR', TRUE);
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classinterviewersxtablets.php';

    $InterviewersxtabletsAll = Interviewersxtablets::getAll();

    require_once '../vista/interviewersxtablets.php';
           } else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>