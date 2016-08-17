<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classtablets.php';

$tablet_id = (isset($_REQUEST['tablet_id'])) ? $_REQUEST['tablet_id'] : null;

if ($tablet_id) {
    $tablets = Tablet::searchById($tablet_id);
    $tablets->delete();
    $tablets->delelteRelTabletsInter($tablet_id);

    header('Location: tablets.php');
}
?>