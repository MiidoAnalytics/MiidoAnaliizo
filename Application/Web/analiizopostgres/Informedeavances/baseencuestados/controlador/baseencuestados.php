<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../../informeAvances/modelo/classinformeAvances.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {
        
    } else {

        $TablaRelacionPersonasEncuestadas = InformeAvances::recuperarRelacionPersonasEncuestadas();
    }

    require_once '../vista/baseencuestados.php';
} else {    
    require_once './../../sitemedia/html/pageerror.php';
}
?>