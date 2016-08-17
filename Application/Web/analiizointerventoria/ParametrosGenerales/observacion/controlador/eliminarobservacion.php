<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classobservacion.php';
    
    $observacion_id = (isset($_REQUEST['observacion_id'])) ? $_REQUEST['observacion_id'] : null;
    
    if($observacion_id){
        $observacion = Observacion::buscarPorIdObservacion($observacion_id);        
        $observacion->eliminar();
        header('Location: observacion.php');
    }    
?>