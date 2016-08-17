<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';
    $reports = Report::getAll();

    /* Recupera los municicpios encuestados */
    require_once '../../../Informedeindicadores/morbilidad/modelo/classmorbilidad.php';
    $MunicipiosEncuestados = Morbilidad::recuperarMunicipiosEncuestados();

    require_once '../vista/reports.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>