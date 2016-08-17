<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classmujeryedadfertil.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {

        $TablaMujerAnticonceptivo = MujerEdadFertil::recuperarMujerAnticonceptivoxMun($municipio_id);
        $recuperarEdadPromedioMenstruacion = MujerEdadFertil::recuperarEdadPromedioMenstruacionxMun($municipio_id);
        $TablaEdadRetiroMenstruacion = MujerEdadFertil::recuperarEdadRetiroMenstruacionxMun($municipio_id);
        $TablaEdadPromedioVidaSexual = MujerEdadFertil::recuperarEdadPromedioVidaSexualxMun($municipio_id);
        $TablaMujerEmbarazo = MujerEdadFertil::recuperarMujerEmbarazoxMun($municipio_id);
        $TablaMujerEmbarazadaSinControl = MujerEdadFertil::recuperarMujerEmbarazadaSinControlxMun($municipio_id);
        $TablaConsultasEmbarazo = MujerEdadFertil::recuperarConsultasEmbarazoxMun($municipio_id);
        $TablaMujerSinSumplementoNutricional = MujerEdadFertil::recuperarMujerSinSumplementoNutricionalxMun($municipio_id);
        $TablaMujerEdadFertilPlanificacion = MujerEdadFertil::recuperarMujerEdadFertilPlanificacionxMun($municipio_id);
        $TablaMetodosPlanificacionFamiliar = MujerEdadFertil::recuperarMetodosPlanificacionFamiliarxMun($municipio_id);
        $TablaProveedorMetodosPF = MujerEdadFertil::recuperarProveedorMetodosPFxMun($municipio_id);
        $TablaHijosPorMujer = MujerEdadFertil::recuperarHijosPorMujerxMun($municipio_id);
        $TablaMujerSinCitologia = MujerEdadFertil::recuperarMujerSinCitologiaxMun($municipio_id);
        $TablaMujerSusceptibleVacunacion = MujerEdadFertil::recuperarMujerSusceptibleVacunacionxMun($municipio_id);
        $TablaConoceExamenMama = MujerEdadFertil::recuperarConoceExamenMamaxMun($municipio_id);
        $TablaEmbarazosRangoEdad = MujerEdadFertil::recuperarEmbarazosRangoEdadxMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();recuperarMetodosPlanificacionFamiliar

        $MunicipiosEncuestados = MujerEdadFertil::recuperarMunicipiosEncuestados();

        $TablaMujerAnticonceptivo = MujerEdadFertil::recuperarMujerAnticonceptivo();
        $recuperarEdadPromedioMenstruacion = MujerEdadFertil::recuperarEdadPromedioMenstruacion();
        $TablaEdadRetiroMenstruacion = MujerEdadFertil::recuperarEdadRetiroMenstruacion();
        $TablaEdadPromedioVidaSexual = MujerEdadFertil::recuperarEdadPromedioVidaSexual();
        $TablaMujerEmbarazo = MujerEdadFertil::recuperarMujerEmbarazo();
        $TablaMujerEmbarazadaSinControl = MujerEdadFertil::recuperarMujerEmbarazadaSinControl();
        $TablaConsultasEmbarazo = MujerEdadFertil::recuperarConsultasEmbarazo();
        $TablaMujerSinSumplementoNutricional = MujerEdadFertil::recuperarMujerSinSumplementoNutricional();
        $TablaMujerEdadFertilPlanificacion = MujerEdadFertil::recuperarMujerEdadFertilPlanificacion();
        $TablaMetodosPlanificacionFamiliar = MujerEdadFertil::recuperarMetodosPlanificacionFamiliar();
        $TablaProveedorMetodosPF = MujerEdadFertil::recuperarProveedorMetodosPF();
        $TablaHijosPorMujer = MujerEdadFertil::recuperarHijosPorMujer();
        $TablaMujerSinCitologia = MujerEdadFertil::recuperarMujerSinCitologia();
        $TablaMujerSusceptibleVacunacion = MujerEdadFertil::recuperarMujerSusceptibleVacunacion();
        $TablaConoceExamenMama = MujerEdadFertil::recuperarConoceExamenMama();
        $TablaEmbarazosRangoEdad = MujerEdadFertil::recuperarEmbarazosRangoEdad();

        $MunicipiosEncuestadosxMun = MujerEdadFertil::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = MujerEdadFertil::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = MujerEdadFertil::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/mujeryedadfertil.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>