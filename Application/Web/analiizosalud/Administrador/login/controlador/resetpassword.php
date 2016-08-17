<?php

define('CONTROLADOR', TRUE);

@session_start();

require_once '../modelo/classlogin.php';

$user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : null;
$passwordOld_id = (isset($_REQUEST['passwordOld_id'])) ? $_REQUEST['passwordOld_id'] : null;

$passwordNew_id = (isset($_REQUEST['passwordNew_id'])) ? $_REQUEST['passwordNew_id'] : null;
$passwordConfirm_id = (isset($_REQUEST['passwordConfirm_id'])) ? $_REQUEST['passwordConfirm_id'] : null;


if ($passwordNew_id != $passwordConfirm_id) {
    $vTmpLogin = 2;
} else {


    if ($user_id || $passwordOld_id) {

        $TablaUsuarioPassword = Login::recuperarUsuarioPassword($user_id, $passwordOld_id);
        $TablaUsuarioActualiza = Login::ActualizarPasswordUsuario($user_id, $passwordOld_id, $passwordNew_id);

        $vTmpLogin = 0;
    } else {

        $vTmpLogin = 1;
    }
}
require_once '../vista/resetpassword.php';
?>