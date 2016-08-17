<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classtown.php';
    
    $municipio_id = (isset($_REQUEST['municipio_id'])) ? $_REQUEST['municipio_id'] : null;
    
    if($municipio_id){
        $municipio= Town::buscarPorId($municipio_id);   
        $municipio->delete();
        header('Location: ../controlador/town.php');
    }    
?>