<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classinterviewer.php';

    $interviewers = Interviewer::recuperarTodos();

    require_once '../vista/interviewer.php';
           } else {
   require_once '../../sitemedia/html/pageerror.php';
}
?>