<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classmuestra.php';
    
    $muestra_id = (isset($_REQUEST['muestra_id'])) ? $_REQUEST['muestra_id'] : null;
    
    if($muestra_id){
        $muestra = Muestra::buscarPorIdMuestra($muestra_id);        
        $muestra->eliminar();
        header('Location: muestra.php');
    }    
?>