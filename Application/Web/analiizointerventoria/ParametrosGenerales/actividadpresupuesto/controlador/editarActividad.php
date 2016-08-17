<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classactividadpresupuesto.php';
    $actpre = new ActividadPresupuesto();

    $actividad_id = (isset($_REQUEST['actividad_id'])) ? $_REQUEST['actividad_id'] : null;
    $proyecto_id = (isset($_REQUEST['proyecto_id'])) ? $_REQUEST['proyecto_id'] : null;
    $encuesta_id = (isset($_REQUEST['encuesta_id'])) ? $_REQUEST['encuesta_id'] : 1;

    if ($actividad_id && $proyecto_id) {
       $actpre =  ActividadPresupuesto::buscarPorIdActividad($actividad_id, 1, $proyecto_id);
       //print_r($actpre); die();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $actividad_id = (isset($_POST['actividad_id'])) ? $_POST['actividad_id'] : null;
        $cantidad = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : null;
        $valoruni = (isset($_POST['valoruni'])) ? $_POST['valoruni'] : null;
        $fechafinaprogra = (isset($_REQUEST['fechafinaprogra'])) ? $_REQUEST['fechafinaprogra'] : null;

        if($fechafinaprogra != null){
            $fechafinaprogra2 = date_create($fechafinaprogra);
            $fechafinaprogra3 = date_format($fechafinaprogra2, 'Ymd');
        }
        $proyecto_id = (isset($_POST['proyecto_id'])) ? $_POST['proyecto_id'] : null;
        $encuesta_id = (isset($_POST['encuesta_id'])) ? $_POST['encuesta_id'] : null;

        if(!$valoruni){
            $valoruni = 0;
        }

        $actpre->setIntIdActividad($actividad_id);
        $actpre->setIdProyecto($proyecto_id);
        $actpre->setIdEncuesta($encuesta_id);
        $actpre->setCantidadContractual($cantidad);
        $actpre->setValorUnitario($valoruni);
        $actpre->setFechaPrograTermi($fechafinaprogra3);

        $actpre->guardar();
        //die();
        header('Location: actividadpresupuesto.php');
    } else {
        include '../vista/editarActividad.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>