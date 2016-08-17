<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classusuarios.php';


$idusuarios = (isset($_REQUEST['idusuarios'])) ? $_REQUEST['idusuarios'] : null;

if ($idusuarios) {
    $usuarios = Usuarios::searchById($idusuarios);
    Usuarios::delete($idusuarios);
    require_once '../../menu/modelo/classroles.php';
    Roles:: deleteUserRol($idusuarios);
 	
    Usuarios::deleteRelUsuarioProyecto($idusuarios);

    header('Location: ../controlador/usuarios.php');

}
?>