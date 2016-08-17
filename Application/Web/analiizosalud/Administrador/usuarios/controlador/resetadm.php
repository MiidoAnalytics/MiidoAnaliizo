<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classusuarios.php';

$idusuarios = (isset($_REQUEST['idusuarios'])) ? $_REQUEST['idusuarios'] : null;

if ($idusuarios) {
    $usuarios = Usuarios::searchById($idusuarios);
    $usuarios->resetPass();

    header('Location: ../controlador/usuarios.php');
}
?>