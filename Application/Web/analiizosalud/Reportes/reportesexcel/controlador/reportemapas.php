<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';
$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classreportesmapas.php';

    $intIdReports = (isset($_REQUEST['intIdReport'])) ? $_REQUEST['intIdReport'] : null;
    $intIdTown = (isset($_REQUEST['town_id'])) ? $_REQUEST['town_id'] : null;
    $proyecto = (isset($_REQUEST['proje'])) ? $_REQUEST['proje'] : null;
    $encuesta = (isset($_REQUEST['encu'])) ? $_REQUEST['encu'] : null;

    if ($intIdReports) {
        $TablaReportesxId = ReportesMapas::recuperarQueryxidReporte($intIdReports);
    } else {

        //$TablaEncuestasxEncuestador = EncuestadoresMapas::recuperarEncuestasxEncuestadores();        
    }

    require_once '../vista/reportemapas.php';
    if ($strQuery) {
        $TablaQueryxidReporte = ReportesMapas::recuperarUbicacionxReporte($strQuery);
        $reportemapas = new reportemapas($TablaReportesxId, $TablaQueryxidReporte);
        $reportemapas->setMarcadores();
        $reportemapas->buildScript();
        $reportemapas->drawPage();
    } else {

        echo 'no hay registros';
    }
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>