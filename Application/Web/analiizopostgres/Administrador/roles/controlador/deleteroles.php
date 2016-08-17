<?php

define('CONTROLADOR', TRUE);

require_once '../../menu/modelo/classroles.php';
$Roles = new Roles;
$role_id = (isset($_REQUEST['role_id'])) ? $_REQUEST['role_id'] : null;
if ($role_id) {
    
    $Roles->setIdRol($role_id);
    $Roles->delete();
    Roles::deleteRelRolUser($role_id);

    header('Location: ../controlador/roles.php');
}
?>