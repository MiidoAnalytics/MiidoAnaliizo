<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classdisease.php';
    
    $disease_id = (isset($_REQUEST['disease_id'])) ? $_REQUEST['disease_id'] : null;
    
    if($disease_id){
        $disease= Disease::searchById($disease_id);  
        $disease->delete();
        header('Location: ../controlador/disease.php');
    }    
?>