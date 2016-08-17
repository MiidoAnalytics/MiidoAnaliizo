<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
require_once '../../../Administrador/menu/modelo/classroles.php';

$role_id = (isset($_REQUEST['role_id'])) ? $_REQUEST['role_id'] : null;

if ($role_id) {
    $roles = Roles::searchByIddos($role_id);
    
} else {
    $roles = new Roles();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rolName = (isset($_POST['rolName'])) ? $_POST['rolName'] : null;
    $Consult = 1;
    
    $roles->setRolName($rolName);
    $roles->setIntConsult($Consult);

    $roles->insert();
    $role_id = Roles::getLastRolId();
    //print_r($role_id); die();
    foreach ($role_id as $key) {
        $roleid = $key['spgetlastrolid'];
    }

    $menus = Roles::ObternerMenus();
    foreach ($menus as $key) {
    	$menu_id = $key['menu_id'];
        $parent = $key['parent'];
        
            $IstatusId = 0;
            $roles->setIdRol($roleid);
            $roles->setIstatusId($IstatusId);
    	    $roles->setMenuIdHijo($menu_id);
    	    $roles->insertMenuRol();
        
    }

    header('Location: ../controlador/roles.php');
} else {
    include '../vista/guardarroles.php';
}

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>