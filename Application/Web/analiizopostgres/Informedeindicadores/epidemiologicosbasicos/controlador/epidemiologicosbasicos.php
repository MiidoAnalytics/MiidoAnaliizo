<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classepidemiologicosbasicos.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {

        $TablaPersonaGenero = EpidemioBasico::recuperarPersonaGeneroxMun($municipio_id);
        $TablaGeneroEdad = EpidemioBasico::recuperarGeneroEdadxMun($municipio_id);
        $TablaPoblacionTownComfasucre = EpidemioBasico::recuperarPoblacionTownComfasucre();
        $TablaPertenenciaEtnica = EpidemioBasico::recuperarPertenenciaEtnicaxMun($municipio_id);
        $TablaChildWoman = EpidemioBasico::recuperarChildWomanxMun($municipio_id);
        $TablaIndiceInfancia = EpidemioBasico::recuperarIndiceInfanciaxMun($municipio_id);
        $TablaIndiceJuventud = EpidemioBasico::recuperarIndiceJuventudxMun($municipio_id);
        $TablaIndiceVejez = EpidemioBasico::recuperarIndiceVejezxMun($municipio_id);
        $TablaIndiceEnvejecimiento = EpidemioBasico::recuperarIndiceEnvejecimientoxMun($municipio_id);
        $TablaIndiceDependencia = EpidemioBasico::recuperarIndiceDependenciaxMun($municipio_id);
        $TablaIndiceDependAM = EpidemioBasico::recuperarIndiceDependAMxMun($municipio_id);
        $TablaIndiceFriz = EpidemioBasico::recuperarIndiceFrizxMun($municipio_id);
        $TablaPersonasDiscapacidad = EpidemioBasico::recuperarPersonasDiscapacidadxMun($municipio_id);
        $TablaNatalidadAno = EpidemioBasico::recuperarNatalidadAnoxMun($municipio_id);
        $TablaFecundidadAno = EpidemioBasico::recuperarFecundidadAnoxMun($municipio_id);
        $TablaFecundidadMuj = EpidemioBasico::recuperarFecundidadMujxMun($municipio_id);
        $TablaFecundidadMuj10a14 = EpidemioBasico::recuperarFecundidadMuj10a14xMun($municipio_id);
        $TablaFecundidadMuj15a19 = EpidemioBasico::recuperarFecundidadMuj15a19xMun($municipio_id);
        $TablaAfiliadosDesplazados = EpidemioBasico::recuperarAfiliadosDesplazadosxMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();recuperarMetodosPlanificacionFamiliar

        $MunicipiosEncuestados = EpidemioBasico::recuperarMunicipiosEncuestados();

        $TablaPersonaGenero = EpidemioBasico::recuperarPersonaGenero();
        $TablaGeneroEdad = EpidemioBasico::recuperarGeneroEdad();
        $TablaPoblacionTownComfasucre = EpidemioBasico::recuperarPoblacionTownComfasucre();
        $TablaPertenenciaEtnica = EpidemioBasico::recuperarPertenenciaEtnica();
        $TablaChildWoman = EpidemioBasico::recuperarChildWoman();
        $TablaIndiceInfancia = EpidemioBasico::recuperarIndiceInfancia();
        $TablaIndiceJuventud = EpidemioBasico::recuperarIndiceJuventud();
        $TablaIndiceVejez = EpidemioBasico::recuperarIndiceVejez();
        $TablaIndiceEnvejecimiento = EpidemioBasico::recuperarIndiceEnvejecimiento();
        $TablaIndiceDependencia = EpidemioBasico::recuperarIndiceDependencia();
        $TablaIndiceDependAM = EpidemioBasico::recuperarIndiceDependAM();
        $TablaIndiceFriz = EpidemioBasico::recuperarIndiceFriz();
        $TablaPersonasDiscapacidad = EpidemioBasico::recuperarPersonasDiscapacidad();
        $TablaNatalidadAno = EpidemioBasico::recuperarNatalidadAno();
        $TablaFecundidadAno = EpidemioBasico::recuperarFecundidadAno();
        $TablaFecundidadMuj = EpidemioBasico::recuperarFecundidadMuj();
        $TablaFecundidadMuj10a14 = EpidemioBasico::recuperarFecundidadMuj10a14();
        $TablaFecundidadMuj15a19 = EpidemioBasico::recuperarFecundidadMuj15a19();
        $TablaAfiliadosDesplazados = EpidemioBasico::recuperarAfiliadosDesplazados();

        $MunicipiosEncuestadosxMun = EpidemioBasico::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = EpidemioBasico::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = EpidemioBasico::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/epidemiologicosbasicos.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>