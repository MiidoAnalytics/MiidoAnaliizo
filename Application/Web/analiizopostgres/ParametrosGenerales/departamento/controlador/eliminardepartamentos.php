<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classdepartamento.php';

$departamento_id = (isset($_REQUEST['departamento_id'])) ? $_REQUEST['departamento_id'] : null;

if ($departamento_id) {
    $departamento = Departament::buscarPorId($departamento_id);
    $departamento->eliminar();
    header('Location: departamento.php');
}
?>