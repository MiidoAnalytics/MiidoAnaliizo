<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classmorbilidad.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {
        $TablaMedicamentosAfiliados = Morbilidad::recuperarMedicamentosAfiliadosxMun($municipio_id);
        $TablaEnfermedadesPersonales = Morbilidad::recuperarEnfermedadesPersonalesxMun($municipio_id);
        $TablaPrevaAnemiMuj3a10 = Morbilidad::recuperarPrevaAnemiMuj3a10xMun($municipio_id);
        $TablaDiabetesMellitus18a69 = Morbilidad::recuperarDiabetesMellitus18a69xMun($municipio_id);
        $TablaEnfermedadRenal = Morbilidad::recuperarEnfermedadRenalxMun($municipio_id);
        $TablaHipertensionArterial18a69 = Morbilidad::recuperarHipertensionArterial18a69xMun($municipio_id);
        $TablaObesidadMuj18a64 = Morbilidad::recuperarObesidadMuj18a64xMun($municipio_id);
        $TablaObesidad18a64 = Morbilidad::recuperarObesidad18a64xMun($municipio_id);
        $TablaObesidadHom18a64 = Morbilidad::recuperarObesidadHom18a64xMun($municipio_id);
        $TablaVIHSida = Morbilidad::recuperarVIHSidaxMun($municipio_id);
        $TablaVIHPersonas15a49 = Morbilidad::recuperarVIHPersonas15a49xMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();

        $MunicipiosEncuestados = Morbilidad::recuperarMunicipiosEncuestados();

        $TablaMedicamentosAfiliados = Morbilidad::recuperarMedicamentosAfiliados();
        $TablaEnfermedadesPersonales = Morbilidad::recuperarEnfermedadesPersonales();
        $TablaPrevaAnemiMuj3a10 = Morbilidad::recuperarPrevaAnemiMuj3a10();
        $TablaDiabetesMellitus18a69 = Morbilidad::recuperarDiabetesMellitus18a69();
        $TablaEnfermedadRenal = Morbilidad::recuperarEnfermedadRenal();
        $TablaHipertensionArterial18a69 = Morbilidad::recuperarHipertensionArterial18a69();
        $TablaObesidadMuj18a64 = Morbilidad::recuperarObesidadMuj18a64();
        $TablaObesidad18a64 = Morbilidad::recuperarObesidad18a64();
        $TablaObesidadHom18a64 = Morbilidad::recuperarObesidadHom18a64();
        $TablaVIHSida = Morbilidad::recuperarVIHSida();
        $TablaVIHPersonas15a49 = Morbilidad::recuperarVIHPersonas15a49();

        $MunicipiosEncuestadosxMun = Morbilidad::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = Morbilidad::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = Morbilidad::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/morbilidad.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>