<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classsatisfaccion.php';

    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;

    if ($municipio_id) {

        $TablaAtencionEPS = Satisfaccion::recuperarAtencionEPSxMun($municipio_id);
        $TablaRespuestaAtencion = Satisfaccion::recuperarRespuestaAtencionxMun($municipio_id);
        $TablaRespuestaPersonalEPS = Satisfaccion::recuperarRespuestaPersonalEPSxMun($municipio_id);
        $TablaInformacionServicio = Satisfaccion::recuperarInformacionServicioxMun($municipio_id);
        $TablaInstalacionesFisicas = Satisfaccion::recuperarInstalacionesFisicasxMun($municipio_id);
        $TablaAtencionGeneral = Satisfaccion::recuperarAtencionGeneralxMun($municipio_id);
        $TablaEpsPercepcion = Satisfaccion::recuperarEpsPercepcionxMun($municipio_id);
    } else {

        //$TablaMedicamentosAfiliados = new Morbilidad();recuperarMetodosPlanificacionFamiliar

        $MunicipiosEncuestados = Satisfaccion::recuperarMunicipiosEncuestados();

        $TablaAtencionEPS = Satisfaccion::recuperarAtencionEPS();
        $TablaRespuestaAtencion = Satisfaccion::recuperarRespuestaAtencion();
        $TablaRespuestaPersonalEPS = Satisfaccion::recuperarRespuestaPersonalEPS();
        $TablaInformacionServicio = Satisfaccion::recuperarInformacionServicio();
        $TablaInstalacionesFisicas = Satisfaccion::recuperarInstalacionesFisicas();
        $TablaAtencionGeneral = Satisfaccion::recuperarAtencionGeneral();
        $TablaEpsPercepcion = Satisfaccion::recuperarEpsPercepcion();

        $MunicipiosEncuestadosxMun = Satisfaccion::recuperarMunicipiosEncuestadosxMun(0);
    }

    $MunicipiosEncuestados = Satisfaccion::recuperarMunicipiosEncuestados();

    $MunicipiosEncuestadosxMun = Satisfaccion::recuperarMunicipiosEncuestadosxMun($municipio_id);

    require_once '../vista/satisfaccion.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>