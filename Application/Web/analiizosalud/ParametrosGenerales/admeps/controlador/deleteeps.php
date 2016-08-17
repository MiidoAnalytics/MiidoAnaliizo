<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classeps.php';

$intIdEps = (isset($_REQUEST['intideps'])) ? $_REQUEST['intideps'] : null;

if ($intIdEps) {
    $eps = Eps::searchById($intIdEps);
    $eps->delete();

    header('Location: ../controlador/admeps.php');
}
?>