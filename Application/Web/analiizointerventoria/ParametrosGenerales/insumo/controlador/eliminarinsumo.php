<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classinsumo.php';
    
    $insumo_id = (isset($_REQUEST['insumo_id'])) ? $_REQUEST['insumo_id'] : null;
    
    if($insumo_id){
        $insumo = Insumo::buscarPorIdInsumo($insumo_id);        
        $insumo->eliminar();
        header('Location: insumo.php');
    }    
?>