<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classpromocionyprevencion.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {

        $TablaNacidoVivoCuatroConsultas = PromocionPrevencion::recuperarNacidoVivoCuatroConsultasxMun($municipio_id);
        $TablaControlesPrenatalesUnAno = PromocionPrevencion::recuperarControlesPrenatalesUnAnoxMun($municipio_id);
        $TablaAfiliadosEducacion = PromocionPrevencion::recuperarAfiliadosEducacionxMun($municipio_id);
        $TablaNinosCreciDesa = PromocionPrevencion::recuperarNinosCreciDesaxMun($municipio_id);
        $TablaNinosLenguajeConducta = PromocionPrevencion::recuperarNinosLenguajeConductaxMun($municipio_id);
        $TablaNinosProblemaVisualAudi = PromocionPrevencion::recuperarNinosProblemaVisualAudixMun($municipio_id);
        $TablaNinosDesparacitados = PromocionPrevencion::recuperarNinosDesparacitadosxMun($municipio_id);
        $TablaEsquemaCompletoVacunacion = PromocionPrevencion::recuperarEsquemaCompletoVacunacionxMun($municipio_id);
        $TablaBajoPesoMenorAno = PromocionPrevencion::recuperarBajoPesoMenorAnoxMun($municipio_id);
        $TablaEdadPromAblactacion = PromocionPrevencion::recuperarEdadPromAblactacionxMun($municipio_id);
        $TablaPromedioConsultaPrenatal = PromocionPrevencion::recuperarPromedioConsultaPrenatalxMun($municipio_id);
        $TablaInfanteRefuerzoNutricional = PromocionPrevencion::recuperarInfanteRefuerzoNutricionalxMun($municipio_id);
        $TablaProveedorRefuerzoNutrional = PromocionPrevencion::recuperarProveedorRefuerzoNutrionalxMun($municipio_id);
        $TablaControlPlaca = PromocionPrevencion::recuperarControlPlacaxMun($municipio_id);
        $TablaConsultaOdotologica = PromocionPrevencion::recuperarConsultaOdotologicaxMun($municipio_id);
        $TablaCepilladoDiario = PromocionPrevencion::recuperarCepilladoDiarioxMun($municipio_id);
        $TablaCaries = PromocionPrevencion::recuperarCariesxMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();recuperarMetodosPlanificacionFamiliar

        $MunicipiosEncuestados = PromocionPrevencion::recuperarMunicipiosEncuestados();

        $TablaNacidoVivoCuatroConsultas = PromocionPrevencion::recuperarNacidoVivoCuatroConsultas();
        $TablaControlesPrenatalesUnAno = PromocionPrevencion::recuperarControlesPrenatalesUnAno();
        $TablaAfiliadosEducacion = PromocionPrevencion::recuperarAfiliadosEducacion();
        $TablaNinosCreciDesa = PromocionPrevencion::recuperarNinosCreciDesa();
        $TablaNinosLenguajeConducta = PromocionPrevencion::recuperarNinosLenguajeConducta();
        $TablaNinosProblemaVisualAudi = PromocionPrevencion::recuperarNinosProblemaVisualAudi();
        $TablaNinosDesparacitados = PromocionPrevencion::recuperarNinosDesparacitados();
        $TablaEsquemaCompletoVacunacion = PromocionPrevencion::recuperarEsquemaCompletoVacunacion();
        $TablaBajoPesoMenorAno = PromocionPrevencion::recuperarBajoPesoMenorAno();
        $TablaEdadPromAblactacion = PromocionPrevencion::recuperarEdadPromAblactacion();
        $TablaPromedioConsultaPrenatal = PromocionPrevencion::recuperarPromedioConsultaPrenatal();
        $TablaInfanteRefuerzoNutricional = PromocionPrevencion::recuperarInfanteRefuerzoNutricional();
        $TablaProveedorRefuerzoNutrional = PromocionPrevencion::recuperarProveedorRefuerzoNutrional();
        $TablaControlPlaca = PromocionPrevencion::recuperarControlPlaca();
        $TablaConsultaOdotologica = PromocionPrevencion::recuperarConsultaOdotologica();
        $TablaCepilladoDiario = PromocionPrevencion::recuperarCepilladoDiario();
        $TablaCaries = PromocionPrevencion::recuperarCaries();

        $MunicipiosEncuestadosxMun = PromocionPrevencion::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = PromocionPrevencion::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = PromocionPrevencion::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/promocionyprevencion.php';
    
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>