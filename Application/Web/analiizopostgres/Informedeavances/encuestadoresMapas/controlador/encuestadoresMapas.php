<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classencuestadoresmapas.php';

    $encuestador_id = (isset($_REQUEST['encuestador_id'])) ? $_REQUEST['encuestador_id'] : null;

    if ($encuestador_id) {
        $TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestador($encuestador_id);
    } else {

        //$TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestadores();        
    }

    $TablaEncuestadorxEncuesta = EncuestadoresMapas::recuperarEncuestadorxEncuesta();

    require_once '../vista/encuestadoresmapas.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>