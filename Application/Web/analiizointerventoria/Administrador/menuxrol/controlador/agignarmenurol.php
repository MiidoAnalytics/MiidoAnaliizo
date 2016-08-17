<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

require_once '../../../Administrador/menu/modelo/classroles.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$role_id = (isset($_POST['role_id'])) ? $_POST['role_id'] : null;
    $menu_id = (isset($_POST['menu_id'])) ? $_POST['menu_id'] : null;
    $nameCheck = (isset($_POST['nameCheck'])) ? $_POST['nameCheck'] : null;

    $nameCheck = explode(',', substr($nameCheck, 0, (strlen($nameCheck)-1)));
    
    //print_r($_POST);
    foreach ($nameCheck as $key) {
        //echo $key;
        try{
            $menuxrol = new Roles();
            $submenu = (isset($_POST[$key])) ? $_POST[$key] : '';
            
            if($submenu and $key){
                
                $menuxrol->setIdRol($role_id);
                $menuxrol->setMenuIdHijo($submenu);
                $menuxrol->setMenuId($menu_id);
                $RolesMenu = Roles::VerificarMenuxRol($role_id,$submenu);
                
                //if(count($RolesMenu) > 0){
                    foreach ($RolesMenu as $key2) {
                        if($key2['istatusid'] == 1){
                           //echo 'el menu ya esta asignado:'.$submenu.'<br>';
                        }
                        else{
                            $IstatusId = 1;
                            $menuxrol->setIstatusId($IstatusId);
                            Roles::ActualizarMenuRol($submenu,$IstatusId, $role_id);
                            //echo 'se asigno el menu'.$submenu;
                        }
                    }
            }elseif($key){
                $menuxrol->setIdRol($role_id);
                $menuxrol->setMenuIdHijo($key);
                $menuxrol->setMenuId($menu_id);
                $RolesMenu = Roles::VerificarMenuxRol($role_id,$key);
                //if(count($RolesMenu) > 0){
                    foreach ($RolesMenu as $key2) {
                        if($key2['istatusid'] == 1){
                            $IstatusId = 0;
                            $menuxrol->setIstatusId($IstatusId);
                            Roles::ActualizarMenuRol($key,$IstatusId,$role_id);
                        }
                    }
            }
            
        }catch(Error $e){echo $e;}   
    }
    $menuxrol->setParent($menu_id);
    $menuxrol->setIdRol($role_id);
    // $IstatusId = 1;
    // $menuxrol->setIstatusId($IstatusId);
    $menupactivo = Roles::contarSubmenuActivo($menu_id, $role_id);
    $contador = count($menupactivo);
                
    if ($contador > 0) {
        $menuxrol->setIdRol($role_id);
        $IstatusId = 1;
        $menuxrol->setIstatusId($IstatusId);
        $menuxrol->setMenuId($menu_id);
        Roles::ActualizarMenuRol($menu_id,$IstatusId,$role_id);
    }else{
        $menuxrol->setIdRol($role_id);
        $IstatusId = 0;
        $menuxrol->setIstatusId($IstatusId);
        $menuxrol->setMenuId($menu_id);
        Roles::ActualizarMenuRol($menu_id,$IstatusId,$role_id);
    }

    header('Location: ../controlador/menuxrol.php');
} 
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>