<?php

define('CONTROLADOR', TRUE);

@session_start();

require_once '../modelo/classlogin.php';

$user_id = (isset($_POST['user'])) ? $_POST['user'] : null;
$password_id = (isset($_POST['password'])) ? $_POST['password'] : null;

if ($user_id || $password_id) {

    $TablaUsuarioPassword = Login::recuperarUsuarioPassword($user_id, $password_id);
    //print_r($TablaUsuarioPassword); die();
    $TablaUsuarioActualiza = Login::ActualizarHoraIngresoUsuario($user_id, $password_id);
    //print_r($TablaUsuarioActualiza); die();
    $vTmpLogin = true;
    //die($vTmpLogin);
   
} else {

    $vTmpLogin = false;
    
}
require_once '../vista/login.php';
?>