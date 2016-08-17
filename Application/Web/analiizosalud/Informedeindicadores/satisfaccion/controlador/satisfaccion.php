<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classsatisfaccion.php';

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;
    
    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = Satisfaccion::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {
            $TablaAtencionEPS = Satisfaccion::recuperarAtencionEPSxMun($proyecto, $encuesta, $municipio);
            $TablaRespuestaAtencion = Satisfaccion::recuperarRespuestaAtencionxMun($proyecto, $encuesta, $municipio);
            $TablaRespuestaPersonalEPS = Satisfaccion::recuperarRespuestaPersonalEPSxMun($proyecto, $encuesta, $municipio);
            $TablaInformacionServicio = Satisfaccion::recuperarInformacionServicioxMun($proyecto, $encuesta, $municipio);
            $TablaInstalacionesFisicas = Satisfaccion::recuperarInstalacionesFisicasxMun($proyecto, $encuesta, $municipio);
            $TablaAtencionGeneral = Satisfaccion::recuperarAtencionGeneralxMun($proyecto, $encuesta, $municipio);
            $TablaEpsPercepcion = Satisfaccion::recuperarEpsPercepcionxMun($proyecto, $encuesta, $municipio);
        } else {
            $TablaAtencionEPS = Satisfaccion::recuperarAtencionEPS($proyecto, $encuesta);
            $TablaRespuestaAtencion = Satisfaccion::recuperarRespuestaAtencion($proyecto, $encuesta);
            $TablaRespuestaPersonalEPS = Satisfaccion::recuperarRespuestaPersonalEPS($proyecto, $encuesta);
            $TablaInformacionServicio = Satisfaccion::recuperarInformacionServicio($proyecto, $encuesta);
            $TablaInstalacionesFisicas = Satisfaccion::recuperarInstalacionesFisicas($proyecto, $encuesta);
            $TablaAtencionGeneral = Satisfaccion::recuperarAtencionGeneral($proyecto, $encuesta);
            $TablaEpsPercepcion = Satisfaccion::recuperarEpsPercepcion($proyecto, $encuesta);
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Satisfaccion::ObtenerProyectosUsuerId($idusuario);
    } 
    require_once '../vista/satisfaccion.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>