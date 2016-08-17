<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classepidemiologicosbasicos.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;

    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = EpidemioBasico::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {
            $TablaPersonaGenero = EpidemioBasico::recuperarPersonaGeneroxMun($proyecto, $encuesta, $municipio);
            $TablaGeneroEdad = EpidemioBasico::recuperarGeneroEdadxMun($proyecto, $encuesta, $municipio);
            //$TablaPoblacionTownEps = EpidemioBasico::recuperarPoblacionTownEps();
            $TablaPertenenciaEtnica = EpidemioBasico::recuperarPertenenciaEtnicaxMun($proyecto, $encuesta, $municipio);
            $TablaChildWoman = EpidemioBasico::recuperarChildWomanxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceInfancia = EpidemioBasico::recuperarIndiceInfanciaxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceJuventud = EpidemioBasico::recuperarIndiceJuventudxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceVejez = EpidemioBasico::recuperarIndiceVejezxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceEnvejecimiento = EpidemioBasico::recuperarIndiceEnvejecimientoxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceDependencia = EpidemioBasico::recuperarIndiceDependenciaxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceDependAM = EpidemioBasico::recuperarIndiceDependAMxMun($proyecto, $encuesta, $municipio);
            $TablaIndiceFriz = EpidemioBasico::recuperarIndiceFrizxMun($proyecto, $encuesta, $municipio);
            $TablaPersonasDiscapacidad = EpidemioBasico::recuperarPersonasDiscapacidadxMun($proyecto, $encuesta, $municipio);
            $TablaNatalidadAno = EpidemioBasico::recuperarNatalidadAnoxMun($proyecto, $encuesta, $municipio);
            $TablaFecundidadAno = EpidemioBasico::recuperarFecundidadAnoxMun($proyecto, $encuesta, $municipio);
            //$TablaFecundidadMuj = EpidemioBasico::recuperarFecundidadMujxMun($proyecto, $encuesta, $municipio);
            $TablaFecundidadMuj10a14 = EpidemioBasico::recuperarFecundidadMuj10a14xMun($proyecto, $encuesta, $municipio);
            $TablaFecundidadMuj15a19 = EpidemioBasico::recuperarFecundidadMuj15a19xMun($proyecto, $encuesta, $municipio);
            $TablaAfiliadosDesplazados = EpidemioBasico::recuperarAfiliadosDesplazadosxMun($proyecto, $encuesta, $municipio);
        } else {
            $TablaPersonaGenero = EpidemioBasico::recuperarPersonaGenero($proyecto, $encuesta);
            $TablaGeneroEdad = EpidemioBasico::recuperarGeneroEdad($proyecto, $encuesta);
            //$TablaPoblacionTownEps = EpidemioBasico::recuperarPoblacionTownEps($proyecto, $encuesta);
            $TablaPertenenciaEtnica = EpidemioBasico::recuperarPertenenciaEtnica($proyecto, $encuesta);
            $TablaChildWoman = EpidemioBasico::recuperarChildWoman($proyecto, $encuesta);
            $TablaIndiceInfancia = EpidemioBasico::recuperarIndiceInfancia($proyecto, $encuesta);
            $TablaIndiceJuventud = EpidemioBasico::recuperarIndiceJuventud($proyecto, $encuesta);
            $TablaIndiceVejez = EpidemioBasico::recuperarIndiceVejez($proyecto, $encuesta);
            $TablaIndiceEnvejecimiento = EpidemioBasico::recuperarIndiceEnvejecimiento($proyecto, $encuesta);
            $TablaIndiceDependencia = EpidemioBasico::recuperarIndiceDependencia($proyecto, $encuesta);
            $TablaIndiceDependAM = EpidemioBasico::recuperarIndiceDependAM($proyecto, $encuesta);
            $TablaIndiceFriz = EpidemioBasico::recuperarIndiceFriz($proyecto, $encuesta);
            $TablaPersonasDiscapacidad = EpidemioBasico::recuperarPersonasDiscapacidad($proyecto, $encuesta);
            $TablaNatalidadAno = EpidemioBasico::recuperarNatalidadAno($proyecto, $encuesta);
            $TablaFecundidadAno = EpidemioBasico::recuperarFecundidadAno($proyecto, $encuesta);
            //$TablaFecundidadMuj = EpidemioBasico::recuperarFecundidadMuj($proyecto, $encuesta);
            $TablaFecundidadMuj10a14 = EpidemioBasico::recuperarFecundidadMuj10a14($proyecto, $encuesta);
            $TablaFecundidadMuj15a19 = EpidemioBasico::recuperarFecundidadMuj15a19($proyecto, $encuesta);
            $TablaAfiliadosDesplazados = EpidemioBasico::recuperarAfiliadosDesplazados($proyecto, $encuesta);
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = EpidemioBasico::ObtenerProyectosUsuerId($idusuario);
    }

    require_once '../vista/epidemiologicosbasicos.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>