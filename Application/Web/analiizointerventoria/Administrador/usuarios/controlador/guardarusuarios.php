<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

require_once '../modelo/classusuarios.php';

$idusuarios = (isset($_REQUEST['idusuarios'])) ? $_REQUEST['idusuarios'] : null;

if ($idusuarios) {
    $usuarios = Usuarios::searchById($idusuarios);
    //print_r($usuarios); die();
} else {
    $usuarios = new Usuarios();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$idusuarios = (isset($_POST['idusuarios'])) ? $_POST['idusuarios'] : null;
    $strFirstName = (isset($_POST['strFirstName'])) ? $_POST['strFirstName'] : null;
    $strLastName = (isset($_POST['strLastName'])) ? $_POST['strLastName'] : null;
    $strPhone = (isset($_POST['strPhone'])) ? $_POST['strPhone'] : null;
    $strEmail = (isset($_POST['strEmail'])) ? $_POST['strEmail'] : null;
    $strLogin = (isset($_POST['strLogin'])) ? $_POST['strLogin'] : null;

    $usuarios->setIdUsuarios($idusuarios);
    $usuarios->setFirstName($strFirstName);
    $usuarios->setLastName($strLastName);
    $usuarios->setUserPhone($strPhone);
    $usuarios->setUserEmail($strEmail);
    $usuarios->setUserLogin($strLogin);

    $usuarios->insert();

    header('Location: ../controlador/usuarios.php');
} else {
    include '../vista/guardarusuarios.php';
}
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>