<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classcups.php';

$cups_id = (isset($_REQUEST['cups_id'])) ? $_REQUEST['cups_id'] : null;

if ($cups_id) {
    $cups = Cups::searchById($cups_id);
    $cups->delete();

    header('Location: ../controlador/cups.php');
}
?>