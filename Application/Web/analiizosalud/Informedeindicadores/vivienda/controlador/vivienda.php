<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classvivienda.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;
    
    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = Vivienda::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {

            $TablaViviendaEs = Vivienda::recuperarViviendaEsxMun($proyecto, $encuesta, $municipio);
            $TablaServicioAgua = Vivienda::recuperarServicioAguaxMun($proyecto, $encuesta, $municipio);
            $TablaServiciosAlcantarillado = Vivienda::recuperarServiciosAlcantarilladoxMun($proyecto, $encuesta, $municipio);
            $TablaServiciosGasNatural = Vivienda::recuperarServiciosGasNaturalxMun($proyecto, $encuesta, $municipio);
            $TablaServiciosElectricidad = Vivienda::recuperarServiciosElectricidadxMun($proyecto, $encuesta, $municipio);
            $TablaServiciosDucha = Vivienda::recuperarServiciosDuchaxMun($proyecto, $encuesta, $municipio);
            $TablaCasaLetrina = Vivienda::recuperarCasaLetrinaxMun($proyecto, $encuesta, $municipio);
            $TablaCocinaDormitorio = Vivienda::recuperarCocinaDormitorioxMun($proyecto, $encuesta, $municipio);
            $TablaNumDormitorios = Vivienda::recuperarNumDormitoriosxMun($proyecto, $encuesta, $municipio);
            $TablaAnimales = Vivienda::recuperarAnimalesxMun($proyecto, $encuesta, $municipio);
            $TablaPisoTierra = Vivienda::recuperarPisoTierraxMun($proyecto, $encuesta, $municipio);
            $TablaPoseeMascota = Vivienda::recuperarPoseeMascotaxMun($proyecto, $encuesta, $municipio);
            $TablaAguaConsume = Vivienda::recuperarAguaConsumexMun($proyecto, $encuesta, $municipio);
            $TablaTratamientoBasura = Vivienda::recuperarTratamientoBasuraxMun($proyecto, $encuesta, $municipio);
            $TablaEstadoTecho = Vivienda::recuperarEstadoTechoxMun($proyecto, $encuesta, $municipio);
            $TablaEstadoParedes = Vivienda::recuperarEstadoParedesxMun($proyecto, $encuesta, $municipio);
            $TablaSuficienteLuz = Vivienda::recuperarSuficienteLuzxMun($proyecto, $encuesta, $municipio);
            $TablaAguasNegras = Vivienda::recuperarAguasNegrasxMun($proyecto, $encuesta, $municipio);
            $TablaRoedores = Vivienda::recuperarRoedoresxMun($proyecto, $encuesta, $municipio);
            $TablaMaterialParedes = Vivienda::recuperarMaterialParedesxMun($proyecto, $encuesta, $municipio);
            $TablaMaterialPiso = Vivienda::recuperarMaterialPisoxMun($proyecto, $encuesta, $municipio);
            $TablaMaterialTecho = Vivienda::recuperarMaterialTechoxMun($proyecto, $encuesta, $municipio);
            $TablaAlumbrado = Vivienda::recuperarAlumbradoxMun($proyecto, $encuesta, $municipio);
            $TablaspGruposFamiliaresVivienda = Vivienda::recuperarPromedioGrupoFamiliarViviendaxMun($proyecto, $encuesta, $municipio);
            $TablaPersonasVivienda = Vivienda::recuperarPromedioPersonasPorViviendaxMun($proyecto, $encuesta, $municipio);
            $TablaPersonasViviendaSeparado = Vivienda::recuperarPersonasViviendaSeparadoxMun($proyecto, $encuesta, $municipio);
        } else {

            $TablaViviendaEs = Vivienda::recuperarViviendaEs($proyecto, $encuesta);
            $TablaServicioAgua = Vivienda::recuperarServicioAgua($proyecto, $encuesta);
            $TablaServiciosAlcantarillado = Vivienda::recuperarServiciosAlcantarillado($proyecto, $encuesta);
            $TablaServiciosGasNatural = Vivienda::recuperarServiciosGasNatural($proyecto, $encuesta);
            $TablaServiciosElectricidad = Vivienda::recuperarServiciosElectricidad($proyecto, $encuesta);
            $TablaServiciosDucha = Vivienda::recuperarServiciosDucha($proyecto, $encuesta);
            $TablaCasaLetrina = Vivienda::recuperarCasaLetrina($proyecto, $encuesta);
            $TablaCocinaDormitorio = Vivienda::recuperarCocinaDormitorio($proyecto, $encuesta);
            $TablaNumDormitorios = Vivienda::recuperarNumDormitorios($proyecto, $encuesta);
            $TablaAnimales = Vivienda::recuperarAnimales($proyecto, $encuesta);
            $TablaPisoTierra = Vivienda::recuperarPisoTierra($proyecto, $encuesta);
            $TablaPoseeMascota = Vivienda::recuperarPoseeMascota($proyecto, $encuesta);
            $TablaAguaConsume = Vivienda::recuperarAguaConsume($proyecto, $encuesta);
            $TablaTratamientoBasura = Vivienda::recuperarTratamientoBasura($proyecto, $encuesta);
            $TablaEstadoTecho = Vivienda::recuperarEstadoTecho($proyecto, $encuesta);
            $TablaEstadoParedes = Vivienda::recuperarEstadoParedes($proyecto, $encuesta);
            $TablaSuficienteLuz = Vivienda::recuperarSuficienteLuz($proyecto, $encuesta);
            $TablaAguasNegras = Vivienda::recuperarAguasNegras($proyecto, $encuesta);
            $TablaRoedores = Vivienda::recuperarRoedores($proyecto, $encuesta);
            $TablaMaterialParedes = Vivienda::recuperarMaterialParedes($proyecto, $encuesta);
            $TablaMaterialPiso = Vivienda::recuperarMaterialPiso($proyecto, $encuesta);
            $TablaMaterialTecho = Vivienda::recuperarMaterialTecho($proyecto, $encuesta);
            $TablaAlumbrado = Vivienda::recuperarAlumbrado($proyecto, $encuesta);
            $TablaspGruposFamiliaresVivienda = Vivienda::recuperarPromedioGrupoFamiliarVivienda($proyecto, $encuesta);
            $TablaPersonasVivienda = Vivienda::recuperarPromedioPersonasPorVivienda($proyecto, $encuesta);
            $TablaPersonasViviendaSeparado = Vivienda::recuperarPersonasViviendaSeparado($proyecto, $encuesta);
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Vivienda::ObtenerProyectosUsuerId($idusuario);
    }  

    require_once '../vista/vivienda.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>