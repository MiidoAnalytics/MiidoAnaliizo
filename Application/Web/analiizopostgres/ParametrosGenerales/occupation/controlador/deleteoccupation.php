<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classoccupation.php';

$ocupacion_id = (isset($_REQUEST['Occupation_id'])) ? $_REQUEST['Occupation_id'] : null;

if ($ocupacion_id) {
    $Occupation = Occupation::searchById($ocupacion_id);
    $Occupation->delete();

    header('Location: ../controlador/occupation.php');
}
?>