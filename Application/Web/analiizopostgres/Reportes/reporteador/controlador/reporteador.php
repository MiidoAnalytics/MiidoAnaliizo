<?php

define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';
    /* Recupera los municicpios encuestados */
    require_once '../../../Informedeindicadores/morbilidad/modelo/classmorbilidad.php';
    $MunicipiosEncuestados = Morbilidad::recuperarMunicipiosEncuestados();
    /* Recupera los encuestadores */
    require_once '../../../Informedeavances/encuestadoresMapas/modelo/classencuestadoresmapas.php';
    $TablaEncuestadorxEncuesta = EncuestadoresMapas::recuperarEncuestadorxEncuesta();
    /* Recupera las enfermedades con el codigo */
    $TablaEnfermedadesReportadas = Report::recuperarEnfermedadesReportadas();
    /* Recupera los medicamentos */
    $TablaMedicinaReportada = Report::recuperarMedicamentosReportados();
    /* Recupera las razas reportadas */
    $TablaRazaReportada = Report::recuperarRazasReportadas();
    /* Recupera el nivel de Educacion Reportado */
    require_once '../../../Informedeindicadores/promocionyprevencion/modelo/classpromocionyprevencion.php';
    $TablaAfiliadosEducacion = PromocionPrevencion::recuperarAfiliadosEducacion();
    /* Recupera la vivienda es */
    require_once '../../../Informedeindicadores/vivienda/modelo/classvivienda.php';
    $TablaViviendaEs = Vivienda::recuperarViviendaEs();
    /* Llama la vista reporteador */
    require_once '../vista/reporteador.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>
