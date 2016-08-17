<?php

if (!defined('CONTROLADOR'))
    exit;

@session_start();

@$idusuario = $_SESSION['userid'];
require_once "../../../Administrador/menu/modelo/classroles.php";
$rol = Roles::rolUserxId($idusuario);

foreach ($rol as $item) {
	$rolePermiso = $item['role_id'];

	$permiso = Roles::searchById($rolePermiso);

    	if ($permiso['consult'] == 1) {
    		$role_id = $item['role_id'];
            
        	$menus = Roles::buscarMenusPadres($role_id);

        	require_once '../../../Administrador/menu/vista/menu.php';
    	} else {
        	require_once 'errorpermiso.php';
    	}
}
?>
