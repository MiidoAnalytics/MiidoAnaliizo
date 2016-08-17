<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classpromocionyprevencion.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = PromocionPrevencion::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {

            $TablaNacidoVivoCuatroConsultas = PromocionPrevencion::recuperarNacidoVivoCuatroConsultasxMun($proyecto, $encuesta, $municipio);
            $TablaControlesPrenatalesUnAno = PromocionPrevencion::recuperarControlesPrenatalesUnAnoxMun($proyecto, $encuesta, $municipio);
            $TablaAfiliadosEducacion = PromocionPrevencion::recuperarAfiliadosEducacionxMun($proyecto, $encuesta, $municipio);
            $TablaNinosCreciDesa = PromocionPrevencion::recuperarNinosCreciDesaxMun($proyecto, $encuesta, $municipio);
            $TablaNinosLenguajeConducta = PromocionPrevencion::recuperarNinosLenguajeConductaxMun($proyecto, $encuesta, $municipio);
            $TablaNinosProblemaVisualAudi = PromocionPrevencion::recuperarNinosProblemaVisualAudixMun($proyecto, $encuesta, $municipio);
            $TablaNinosDesparacitados = PromocionPrevencion::recuperarNinosDesparacitadosxMun($proyecto, $encuesta, $municipio);
            $TablaEsquemaCompletoVacunacion = PromocionPrevencion::recuperarEsquemaCompletoVacunacionxMun($proyecto, $encuesta, $municipio);
            $TablaBajoPesoMenorAno = PromocionPrevencion::recuperarBajoPesoMenorAnoxMun($proyecto, $encuesta, $municipio);
            $TablaEdadPromAblactacion = PromocionPrevencion::recuperarEdadPromAblactacionxMun($proyecto, $encuesta, $municipio);
            $TablaPromedioConsultaPrenatal = PromocionPrevencion::recuperarPromedioConsultaPrenatalxMun($proyecto, $encuesta, $municipio);
            $TablaInfanteRefuerzoNutricional = PromocionPrevencion::recuperarInfanteRefuerzoNutricionalxMun($proyecto, $encuesta, $municipio);
            $TablaProveedorRefuerzoNutrional = PromocionPrevencion::recuperarProveedorRefuerzoNutrionalxMun($proyecto, $encuesta, $municipio);
            $TablaControlPlaca = PromocionPrevencion::recuperarControlPlacaxMun($proyecto, $encuesta, $municipio);
            $TablaConsultaOdotologica = PromocionPrevencion::recuperarConsultaOdotologicaxMun($proyecto, $encuesta, $municipio);
            $TablaCepilladoDiario = PromocionPrevencion::recuperarCepilladoDiarioxMun($proyecto, $encuesta, $municipio);
            $TablaCaries = PromocionPrevencion::recuperarCariesxMun($proyecto, $encuesta, $municipio);
        } else {

            $TablaNacidoVivoCuatroConsultas = PromocionPrevencion::recuperarNacidoVivoCuatroConsultas($proyecto, $encuesta);
            $TablaControlesPrenatalesUnAno = PromocionPrevencion::recuperarControlesPrenatalesUnAno($proyecto, $encuesta);
            $TablaAfiliadosEducacion = PromocionPrevencion::recuperarAfiliadosEducacion($proyecto, $encuesta);
            $TablaNinosCreciDesa = PromocionPrevencion::recuperarNinosCreciDesa($proyecto, $encuesta);
            $TablaNinosLenguajeConducta = PromocionPrevencion::recuperarNinosLenguajeConducta($proyecto, $encuesta);
            $TablaNinosProblemaVisualAudi = PromocionPrevencion::recuperarNinosProblemaVisualAudi($proyecto, $encuesta);
            $TablaNinosDesparacitados = PromocionPrevencion::recuperarNinosDesparacitados($proyecto, $encuesta);
            $TablaEsquemaCompletoVacunacion = PromocionPrevencion::recuperarEsquemaCompletoVacunacion($proyecto, $encuesta);
            $TablaBajoPesoMenorAno = PromocionPrevencion::recuperarBajoPesoMenorAno($proyecto, $encuesta);
            $TablaEdadPromAblactacion = PromocionPrevencion::recuperarEdadPromAblactacion($proyecto, $encuesta);
            $TablaPromedioConsultaPrenatal = PromocionPrevencion::recuperarPromedioConsultaPrenatal($proyecto, $encuesta);
            $TablaInfanteRefuerzoNutricional = PromocionPrevencion::recuperarInfanteRefuerzoNutricional($proyecto, $encuesta);
            $TablaProveedorRefuerzoNutrional = PromocionPrevencion::recuperarProveedorRefuerzoNutrional($proyecto, $encuesta);
            $TablaControlPlaca = PromocionPrevencion::recuperarControlPlaca($proyecto, $encuesta);
            $TablaConsultaOdotologica = PromocionPrevencion::recuperarConsultaOdotologica($proyecto, $encuesta);
            $TablaCepilladoDiario = PromocionPrevencion::recuperarCepilladoDiario($proyecto, $encuesta);
            $TablaCaries = PromocionPrevencion::recuperarCaries($proyecto, $encuesta);
        }

    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = PromocionPrevencion::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/promocionyprevencion.php';
    
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>