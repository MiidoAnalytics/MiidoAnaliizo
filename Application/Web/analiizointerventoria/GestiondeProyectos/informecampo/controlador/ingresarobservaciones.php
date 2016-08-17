<?php
define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classinformecampo.php';

    $observacion = new InformeCampo();

    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['pollstr_id'])) ? $_REQUEST['pollstr_id'] : null;
    
    if($encuesta){        
        $observaciones = InformeCampo::obtenerObservacionesEncuesta($encuesta);
        $observacion->setIdEncuesta($encuesta);
        $observacion->setIdObservacion($observaciones[0]['intidobser']);
        $plana = $observaciones[0]['strplanaccion'];
        $come = $observaciones[0]['strobsinterventor'];
        $respon = $observaciones[0]['strresponsable'];
        $fechaprogram = $observaciones[0]['dtfechaprogramada'];
        $observacion->setPlanAccion($plana);
        $observacion->setComentarios($come);
        $observacion->setResponsable($respon);
        $observacion->setFechaProgramada($fechaprogram);
    }else{
        $observacion = new InformeCampo();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {      
        $planaccion = (isset($_POST['planaccion'])) ? $_POST['planaccion'] : null;
        $responsable = (isset($_REQUEST['responsable'])) ? $_REQUEST['responsable'] : null;
        $fechapro = (isset($_POST['fechapro'])) ? $_POST['fechapro'] : null;
        if($fechapro != null){
            $datepro = date_create($fechapro);
            $datepro2 = date_format($datepro, 'Ymd');
        }
        $comentarios = (isset($_POST['comentarios'])) ? $_POST['comentarios'] : null;
        $idencuesta = (isset($_POST['encuestaid'])) ? $_POST['encuestaid'] : null;
        $idobser = (isset($_POST['obserid'])) ? $_POST['obserid'] : null;

        $observacion->setIdObservacion($idobser);
        $observacion->setIdEncuesta($idencuesta);
        $observacion->setPlanAccion($planaccion);
        $observacion->setResponsable($responsable);
        $observacion->setFechaProgramada($datepro2);
        $observacion->setComentarios($comentarios);

        $observacion->guardarObservacion();

        header('Location: ../controlador/informecampo.php');
    }else
    {     
        include '../vista/ingresarobservaciones.php';
    }
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>