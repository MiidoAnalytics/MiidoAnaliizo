<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classmedicine.php';

$medicine_id = (isset($_REQUEST['medicine_id'])) ? $_REQUEST['medicine_id'] : null;

if ($medicine_id) {
    $medicine = Medicine::searchById($medicine_id);
    $medicine->delete();

    header('Location: ../controlador/disease.php');
}
?>