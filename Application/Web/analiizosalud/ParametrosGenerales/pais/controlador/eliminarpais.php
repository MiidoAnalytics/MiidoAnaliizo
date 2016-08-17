<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classpais.php';
    
    $pais_id = (isset($_REQUEST['pais_id'])) ? $_REQUEST['pais_id'] : null;
    
    if($pais_id){
        $pais = Pais::buscarPorId($pais_id);        
        $pais->eliminar();
        header('Location: pais.php');
    }    
?>