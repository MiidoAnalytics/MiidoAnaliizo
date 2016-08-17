<?php
define('CONTROLADOR', TRUE);
include_once '../../../core/classSession.php';
$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classindicadores.php';

    $indicador = new Indicadores();

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $muniName = (isset($_REQUEST['muniName'])) ? $_REQUEST['muniName'] : null;
    $municipio = (isset($_REQUEST['municipio'])) ? $_REQUEST['municipio'] : null;

    $fem = "Femenino";
    $mas = "Masculino";
    
    if ($encuesta && $proyecto) {
        $idusuario = $_SESSION['userid'];
        $proyectos = Indicadores::ObtenerProyectosUsuerId($idusuario);

        if ($municipio != '0') {
            $TablaEstadoNutricional = Indicadores::recuperarEstadoNutricionalAdultosxMun($proyecto, $encuesta, $municipio);
            $TablaEstadoNutricional519F = Indicadores::recuperarEstadoNutNinos519xMun($fem, $proyecto, $encuesta, $municipio);
            $TablaEstadoNutricional519M = Indicadores::recuperarEstadoNutNinos519xMun($mas, $proyecto, $encuesta, $municipio);
            $TablaEstadoNutricional05F = Indicadores::recuperarEstadoNutNinos05xMun($fem, $proyecto, $encuesta, $municipio);
            $TablaEstadoNutricional05M = Indicadores::recuperarEstadoNutNinos05xMun($mas, $proyecto, $encuesta, $municipio);
            $TablaPresionArterialGenero = Indicadores::recuperarPresionArterialGeneroxMun($proyecto, $encuesta, $municipio);
            $TablaRiesgoEnfeCardioMujer = Indicadores::recuperarRiesgoEnfeCardioMujerxMun($proyecto, $encuesta, $municipio);
            $TablaRiesgoEnfeCardioHombre = Indicadores::recuperarRiesgoEnfeCardioHombrexMun($proyecto, $encuesta, $municipio);
            $TablaBajaOximetria = Indicadores::recuperarOximetriaxMun($proyecto, $encuesta, $municipio);
            
            /*obtener la mediana y la desviacion para calcula la distribucion normal*/
            /*$mediadeszpesoedadF = Indicadores::recuperarMedDesZpesoedadNinas($proyecto, $encuesta);
            $mediadeszpesoedadM = Indicadores::recuperarMedDesZpesoedadNinos($proyecto, $encuesta);*/
        }else{
            $TablaEstadoNutricional = Indicadores::recuperarEstadoNutricionalAdultos($proyecto, $encuesta);
            $TablaEstadoNutricional519F = Indicadores::recuperarEstadoNutricionalNinos519($fem, $proyecto, $encuesta);
            $TablaEstadoNutricional519M = Indicadores::recuperarEstadoNutricionalNinos519($mas, $proyecto, $encuesta);
            $TablaEstadoNutricional05F = Indicadores::recuperarEstadoNutricionalNinos05($fem, $proyecto, $encuesta);
            $TablaEstadoNutricional05M = Indicadores::recuperarEstadoNutricionalNinos05($mas, $proyecto, $encuesta);
            $TablaPresionArterialGenero = Indicadores::recuperarPresionArterialGenero($proyecto, $encuesta);
            $TablaRiesgoEnfeCardioMujer = Indicadores::recuperarRiesgoEnfeCardioMujer($proyecto, $encuesta);
            $TablaRiesgoEnfeCardioHombre = Indicadores::recuperarRiesgoEnfeCardioHombre($proyecto, $encuesta);
            $TablaBajaOximetria = Indicadores::recuperarBajaOximetria($proyecto, $encuesta);

            /*Datos campana de gauss Peso por Edad niñas 0-5 años*/
            //$zscorepesoedad = Indicadores::recuperarZsocrepesoedad($proyecto, $encuesta);

            /*obtener la mediana y la desviacion para calcula la distribucion normal*/
            /*$mediadeszpesoedadF = Indicadores::recuperarMedDesZpesoedadNinas($proyecto, $encuesta);
            $mediadeszpesoedadM = Indicadores::recuperarMedDesZpesoedadNinos($proyecto, $encuesta);*/
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Indicadores::ObtenerProyectosUsuerId($idusuario);
    } 
    require_once '../vista/indicadores.php';

} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>