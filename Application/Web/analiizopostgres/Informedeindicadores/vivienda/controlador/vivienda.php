<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classvivienda.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {

        $TablaViviendaEs = Vivienda::recuperarViviendaEsxMun($municipio_id);
        $TablaServicioAgua = Vivienda::recuperarServicioAguaxMun($municipio_id);
        $TablaServiciosAlcantarillado = Vivienda::recuperarServiciosAlcantarilladoxMun($municipio_id);
        $TablaServiciosGasNatural = Vivienda::recuperarServiciosGasNaturalxMun($municipio_id);
        $TablaServiciosElectricidad = Vivienda::recuperarServiciosElectricidadxMun($municipio_id);
        $TablaServiciosDucha = Vivienda::recuperarServiciosDuchaxMun($municipio_id);
        $TablaCasaLetrina = Vivienda::recuperarCasaLetrinaxMun($municipio_id);
        $TablaCocinaDormitorio = Vivienda::recuperarCocinaDormitorioxMun($municipio_id);
        $TablaNumDormitorios = Vivienda::recuperarNumDormitoriosxMun($municipio_id);
        $TablaAnimales = Vivienda::recuperarAnimalesxMun($municipio_id);
        $TablaPisoTierra = Vivienda::recuperarPisoTierraxMun($municipio_id);
        $TablaPoseeMascota = Vivienda::recuperarPoseeMascotaxMun($municipio_id);
        $TablaAguaConsume = Vivienda::recuperarAguaConsumexMun($municipio_id);
        $TablaTratamientoBasura = Vivienda::recuperarTratamientoBasuraxMun($municipio_id);
        $TablaEstadoTecho = Vivienda::recuperarEstadoTechoxMun($municipio_id);
        $TablaEstadoParedes = Vivienda::recuperarEstadoParedesxMun($municipio_id);
        $TablaSuficienteLuz = Vivienda::recuperarSuficienteLuzxMun($municipio_id);
        $TablaAguasNegras = Vivienda::recuperarAguasNegrasxMun($municipio_id);
        $TablaRoedores = Vivienda::recuperarRoedoresxMun($municipio_id);
        $TablaMaterialParedes = Vivienda::recuperarMaterialParedesxMun($municipio_id);
        $TablaMaterialPiso = Vivienda::recuperarMaterialPisoxMun($municipio_id);
        $TablaMaterialTecho = Vivienda::recuperarMaterialTechoxMun($municipio_id);
        $TablaAlumbrado = Vivienda::recuperarAlumbradoxMun($municipio_id);
        $TablaspGruposFamiliaresVivienda = Vivienda::recuperarPromedioGrupoFamiliarViviendaxMun($municipio_id);
        $TablaPersonasVivienda = Vivienda::recuperarPromedioPersonasPorViviendaxMun($municipio_id);
        $TablaPersonasViviendaSeparado = Vivienda::recuperarPersonasViviendaSeparadoxMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();recuperarMetodosPlanificacionFamiliar

        $MunicipiosEncuestados = Vivienda::recuperarMunicipiosEncuestados();

        $TablaViviendaEs = Vivienda::recuperarViviendaEs();
        $TablaServicioAgua = Vivienda::recuperarServicioAgua();
        $TablaServiciosAlcantarillado = Vivienda::recuperarServiciosAlcantarillado();
        $TablaServiciosGasNatural = Vivienda::recuperarServiciosGasNatural();
        $TablaServiciosElectricidad = Vivienda::recuperarServiciosElectricidad();
        $TablaServiciosDucha = Vivienda::recuperarServiciosDucha();
        $TablaCasaLetrina = Vivienda::recuperarCasaLetrina();
        $TablaCocinaDormitorio = Vivienda::recuperarCocinaDormitorio();
        $TablaNumDormitorios = Vivienda::recuperarNumDormitorios();
        $TablaAnimales = Vivienda::recuperarAnimales();
        $TablaPisoTierra = Vivienda::recuperarPisoTierra();
        $TablaPoseeMascota = Vivienda::recuperarPoseeMascota();
        $TablaAguaConsume = Vivienda::recuperarAguaConsume();
        $TablaTratamientoBasura = Vivienda::recuperarTratamientoBasura();
        $TablaEstadoTecho = Vivienda::recuperarEstadoTecho();
        $TablaEstadoParedes = Vivienda::recuperarEstadoParedes();
        $TablaSuficienteLuz = Vivienda::recuperarSuficienteLuz();
        $TablaAguasNegras = Vivienda::recuperarAguasNegras();
        $TablaRoedores = Vivienda::recuperarRoedores();
        $TablaMaterialParedes = Vivienda::recuperarMaterialParedes();
        $TablaMaterialPiso = Vivienda::recuperarMaterialPiso();
        $TablaMaterialTecho = Vivienda::recuperarMaterialTecho();
        $TablaAlumbrado = Vivienda::recuperarAlumbrado();
        $TablaspGruposFamiliaresVivienda = Vivienda::recuperarPromedioGrupoFamiliarVivienda();
        $TablaPersonasVivienda = Vivienda::recuperarPromedioPersonasPorVivienda();
        $TablaPersonasViviendaSeparado = Vivienda::recuperarPersonasViviendaSeparado();

        $MunicipiosEncuestadosxMun = Vivienda::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = Vivienda::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = Vivienda::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/vivienda.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>