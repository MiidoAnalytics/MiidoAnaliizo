<?php
    
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classequipo.php';
    
    $equipo_id = (isset($_REQUEST['equipo_id'])) ? $_REQUEST['equipo_id'] : null;
    
    if($equipo_id){
        $equipo = Equipo::buscarPorIdEquipo($equipo_id);        
        $equipo->eliminar();
        header('Location: equipo.php');
    }    
?>