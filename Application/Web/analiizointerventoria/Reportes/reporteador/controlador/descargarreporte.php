<?php
define('CONTROLADOR', TRUE);
require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $since = (isset($_POST['since'])) ? $_POST['since'] : '0001-01-01';
    $until = (isset($_POST['until'])) ? $_POST['until'] : date("Ymd");
    $town = (isset($_POST['town'])) ? $_POST['town'] : '%';
    $ageSince = (isset($_POST['ageSince'])) ? $_POST['ageSince'] : '-1';
    $ageUntil = (isset($_POST['ageUntil'])) ? $_POST['ageUntil'] : '200';
    $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '%';
    $interviewer = (isset($_POST['interFilter'])) ? $_POST['interFilter'] : '-1';
    $disease = (isset($_POST['diseaseFilter'])) ? $_POST['diseaseFilter'] : '%';
    $medicine = (isset($_POST['medicineFilter'])) ? $_POST['medicineFilter'] : '%';
    $etnia = (isset($_POST['etniaFilter'])) ? $_POST['etniaFilter'] : '%';
    $desplazado = (isset($_POST['desplazadoFilter'])) ? $_POST['desplazadoFilter'] : '%';
    $estudios = (isset($_POST['estudiosFilter'])) ? $_POST['estudiosFilter'] : '%';
    $minusvalido = (isset($_POST['minusvalidoFilter'])) ? $_POST['minusvalidoFilter'] : '%';
    $odontologia = (isset($_POST['odontologiaFilter'])) ? $_POST['odontologiaFilter'] : '%';
    $vivienda = (isset($_POST['viviendaFilter'])) ? $_POST['viviendaFilter'] : '%';
    $agua = (isset($_POST['aguaFilter'])) ? $_POST['aguaFilter'] : '%';
    $alcantarillado = (isset($_POST['alcantarilladoFilter'])) ? $_POST['alcantarilladoFilter'] : '%';
    $gasNatural= (isset($_POST['gasNatFilter'])) ? $_POST['gasNatFilter'] : '%';
    $electricidad = (isset($_POST['eneElecFilter'])) ? $_POST['eneElecFilter'] : '%';
    
    if($disease != '%'){ $disease = $disease.'%';}
    else {$disease;}

    $since = str_replace('-', '', $since);
    $until = str_replace('-', '', $until);
    
    $datos = Report::buscarDatosTodosFiltro($since, $until, $town, $ageSince, $ageUntil,$gender, $interviewer,$disease,$medicine,$etnia,$desplazado,$estudios,$minusvalido,
        $odontologia,$vivienda,$agua,$alcantarillado,$gasNatural,$electricidad);
    $titles = Report::buscarDatosTodosFiltroTitulos($since, $until, $town, $ageSince, $ageUntil, $gender,$interviewer,$disease,$medicine,$etnia,$desplazado,$estudios,$minusvalido,
        $odontologia,$vivienda,$agua,$alcantarillado,$gasNatural,$electricidad);
        //echo count($datos);
        //echo count($titles);
        
    $name_excel = 'Reporte excel';
    $description = 'Reporte excel';
    $numCols = count($titles);

    
    
if (count($datos) > 0) {

    date_default_timezone_set('America/Bogota');
    if (PHP_SAPI == 'cli')
        die('Este archivo solo se puede ver desde un navegador web');

    /** Se agrega la libreria */
    require_once '../../../core/lib/PHP_XLSXWriter-master/xlsxwriter.class.php';

    // Se asignan las propiedades del libro
    $filename = "Reporte.xlsx";
    $campos = array_fill_keys($titles, 'String');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    ob_end_clean();
    header('Content-Disposition: attachment;filename="' . $filename);
    header('Cache-Control: max-age=0');
    $writer = new XLSXWriter();
    $writer->setAuthor('Desarrollo Miido');
    $writer->writeSheet($datos,'Hoja1',$campos);
    $writer->writeToStdOut();     


} else {
    //si no existen datos enviamos una alerta y regresamos a la página de reportes excel
    ?>
    <script language="JavaScript" type="text/javascript">
        alert("No hay resultados para mostrar");
        location.href='reporteador.php';
    </script>
    <?php

        //header('Location:reportesexcel.php');
    //print_r('No hay resultados para mostrar');
}
    
    //header('Location: vistas/reporteador.php');

} else {

    include 'vistas/reporteador.php';
}

?>