<?php

define('CONTROLADOR', TRUE);

require_once '../../../Administrador/menu/modelo/classroles.php';

$Roles = new Roles();

echo $idusuarios = (isset($_REQUEST['idusuarios'])) ? $_REQUEST['idusuarios'] : null;
if ($idusuarios) {
    
	$Roles->setIdusuarioRol($idusuarios);
    $Roles->deleteUserRol($idusuarios);

    header('Location: ../controlador/usuarioxrol.php');
}
?>