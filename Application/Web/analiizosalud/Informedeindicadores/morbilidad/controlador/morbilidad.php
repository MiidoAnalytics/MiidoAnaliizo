<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classmorbilidad.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = Morbilidad::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {
            $TablaMedicamentosAfiliados = Morbilidad::recuperarMedicamentosAfiliadosxMun($proyecto, $encuesta, $municipio);
            $TablaEnfermedadesPersonales = Morbilidad::recuperarEnfermedadesPersonalesxMun($proyecto, $encuesta, $municipio);
            $TablaPrevaAnemiMuj3a10 = Morbilidad::recuperarPrevaAnemiMuj3a10xMun($proyecto, $encuesta, $municipio);
            $TablaDiabetesMellitus18a69 = Morbilidad::recuperarDiabetesMellitus18a69xMun($proyecto, $encuesta, $municipio);
            $TablaEnfermedadRenal = Morbilidad::recuperarEnfermedadRenalxMun($proyecto, $encuesta, $municipio);
            $TablaHipertensionArterial18a69 = Morbilidad::recuperarHipertensionArterial18a69xMun($proyecto, $encuesta, $municipio);
            $TablaObesidadMuj18a64 = Morbilidad::recuperarObesidadMuj18a64xMun($proyecto, $encuesta, $municipio);
            $TablaObesidad18a64 = Morbilidad::recuperarObesidad18a64xMun($proyecto, $encuesta, $municipio);
            $TablaObesidadHom18a64 = Morbilidad::recuperarObesidadHom18a64xMun($proyecto, $encuesta, $municipio);
            $TablaVIHSida = Morbilidad::recuperarVIHSidaxMun($proyecto, $encuesta, $municipio);
            $TablaVIHPersonas15a49 = Morbilidad::recuperarVIHPersonas15a49xMun($proyecto, $encuesta, $municipio);
        } else {

            $TablaMedicamentosAfiliados = Morbilidad::recuperarMedicamentosAfiliados($proyecto, $encuesta);
            $TablaEnfermedadesPersonales = Morbilidad::recuperarEnfermedadesPersonales($proyecto, $encuesta);
            $TablaPrevaAnemiMuj3a10 = Morbilidad::recuperarPrevaAnemiMuj3a10($proyecto, $encuesta);
            $TablaDiabetesMellitus18a69 = Morbilidad::recuperarDiabetesMellitus18a69($proyecto, $encuesta);
            $TablaEnfermedadRenal = Morbilidad::recuperarEnfermedadRenal($proyecto, $encuesta);
            $TablaHipertensionArterial18a69 = Morbilidad::recuperarHipertensionArterial18a69($proyecto, $encuesta);
            $TablaObesidadMuj18a64 = Morbilidad::recuperarObesidadMuj18a64($proyecto, $encuesta);
            $TablaObesidad18a64 = Morbilidad::recuperarObesidad18a64($proyecto, $encuesta);
            $TablaObesidadHom18a64 = Morbilidad::recuperarObesidadHom18a64($proyecto, $encuesta);
            $TablaVIHSida = Morbilidad::recuperarVIHSida($proyecto, $encuesta);
            $TablaVIHPersonas15a49 = Morbilidad::recuperarVIHPersonas15a49($proyecto, $encuesta);
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Morbilidad::ObtenerProyectosUsuerId($idusuario);
    }
    require_once '../vista/morbilidad.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>