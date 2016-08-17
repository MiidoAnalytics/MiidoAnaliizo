<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classmujeryedadfertil.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;
    
    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = MujerEdadFertil::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {

            $TablaMujerAnticonceptivo = MujerEdadFertil::recuperarMujerAnticonceptivoxMun($proyecto, $encuesta, $municipio);
            $recuperarEdadPromedioMenstruacion = MujerEdadFertil::recuperarEdadPromedioMenstruacionxMun($proyecto, $encuesta, $municipio);
            $TablaEdadRetiroMenstruacion = MujerEdadFertil::recuperarEdadRetiroMenstruacionxMun($proyecto, $encuesta, $municipio);
            $TablaEdadPromedioVidaSexual = MujerEdadFertil::recuperarEdadPromedioVidaSexualxMun($proyecto, $encuesta, $municipio);
            $TablaMujerEmbarazo = MujerEdadFertil::recuperarMujerEmbarazoxMun($proyecto, $encuesta, $municipio);
            $TablaMujerEmbarazadaSinControl = MujerEdadFertil::recuperarMujerEmbarazadaSinControlxMun($proyecto, $encuesta, $municipio);
            $TablaConsultasEmbarazo = MujerEdadFertil::recuperarConsultasEmbarazoxMun($proyecto, $encuesta, $municipio);
            $TablaMujerSinSumplementoNutricional = MujerEdadFertil::recuperarMujerSinSumplementoNutricionalxMun($proyecto, $encuesta, $municipio);
            $TablaMujerEdadFertilPlanificacion = MujerEdadFertil::recuperarMujerEdadFertilPlanificacionxMun($proyecto, $encuesta, $municipio);
            $TablaMetodosPlanificacionFamiliar = MujerEdadFertil::recuperarMetodosPlanificacionFamiliarxMun($proyecto, $encuesta, $municipio);
            $TablaProveedorMetodosPF = MujerEdadFertil::recuperarProveedorMetodosPFxMun($proyecto, $encuesta, $municipio);
            $TablaHijosPorMujer = MujerEdadFertil::recuperarHijosPorMujerxMun($proyecto, $encuesta, $municipio);
            $TablaMujerSinCitologia = MujerEdadFertil::recuperarMujerSinCitologiaxMun($proyecto, $encuesta, $municipio);
            $TablaMujerSusceptibleVacunacion = MujerEdadFertil::recuperarMujerSusceptibleVacunacionxMun($proyecto, $encuesta, $municipio);
            $TablaConoceExamenMama = MujerEdadFertil::recuperarConoceExamenMamaxMun($proyecto, $encuesta, $municipio);
            $TablaEmbarazosRangoEdad = MujerEdadFertil::recuperarEmbarazosRangoEdadxMun($proyecto, $encuesta, $municipio);
        } else {

            $TablaMujerAnticonceptivo = MujerEdadFertil::recuperarMujerAnticonceptivo($proyecto, $encuesta);
            $recuperarEdadPromedioMenstruacion = MujerEdadFertil::recuperarEdadPromedioMenstruacion($proyecto, $encuesta);
            $TablaEdadRetiroMenstruacion = MujerEdadFertil::recuperarEdadRetiroMenstruacion($proyecto, $encuesta);
            $TablaEdadPromedioVidaSexual = MujerEdadFertil::recuperarEdadPromedioVidaSexual($proyecto, $encuesta);
            $TablaMujerEmbarazo = MujerEdadFertil::recuperarMujerEmbarazo($proyecto, $encuesta);
            $TablaMujerEmbarazadaSinControl = MujerEdadFertil::recuperarMujerEmbarazadaSinControl($proyecto, $encuesta);
            $TablaConsultasEmbarazo = MujerEdadFertil::recuperarConsultasEmbarazo($proyecto, $encuesta);
            $TablaMujerSinSumplementoNutricional = MujerEdadFertil::recuperarMujerSinSumplementoNutricional($proyecto, $encuesta);
            $TablaMujerEdadFertilPlanificacion = MujerEdadFertil::recuperarMujerEdadFertilPlanificacion($proyecto, $encuesta);
            $TablaMetodosPlanificacionFamiliar = MujerEdadFertil::recuperarMetodosPlanificacionFamiliar($proyecto, $encuesta);
            $TablaProveedorMetodosPF = MujerEdadFertil::recuperarProveedorMetodosPF($proyecto, $encuesta);
            $TablaHijosPorMujer = MujerEdadFertil::recuperarHijosPorMujer($proyecto, $encuesta);
            $TablaMujerSinCitologia = MujerEdadFertil::recuperarMujerSinCitologia($proyecto, $encuesta);
            $TablaMujerSusceptibleVacunacion = MujerEdadFertil::recuperarMujerSusceptibleVacunacion($proyecto, $encuesta);
            $TablaConoceExamenMama = MujerEdadFertil::recuperarConoceExamenMama($proyecto, $encuesta);
            $TablaEmbarazosRangoEdad = MujerEdadFertil::recuperarEmbarazosRangoEdad($proyecto, $encuesta);
        }

    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = MujerEdadFertil::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/mujeryedadfertil.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>