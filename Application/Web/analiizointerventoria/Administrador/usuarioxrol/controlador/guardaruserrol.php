<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

require_once '../../../Administrador/menu/modelo/classroles.php';
require_once '../../../Administrador/usuarios/modelo/classusuarios.php';

$Roles = new Roles();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idusuarios = (isset($_POST['idusuarios'])) ? $_POST['idusuarios'] : null;
    $role_id = (isset($_POST['role_id'])) ? $_POST['role_id'] : null;
    //print_r($_POST); die();
    $Roles->setIdusuarioRol($idusuarios);
    $Roles->setIdRol($role_id);

    $userRole= Roles::rolUserxId($idusuarios);
    
    if(count($userRole) > 0){
        $IstatusId = 1;
        $Roles->setIstatusId($IstatusId);
        Roles::updateUserRol($idusuarios, $role_id, $IstatusId);
    }else{
        $IstatusId = 1;
        $Roles->setIstatusId($IstatusId);
        Roles::insertUserRol($idusuarios, $role_id, $IstatusId);
    }   
   
    header('Location: ../controlador/usuarioxrol.php');

} else {
    include '../vista/guardaruserrol.php';
}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>