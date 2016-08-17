<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classinterviewersxtablets.php';

$interviewer_id = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;
$tablet_id = (isset($_REQUEST['tablet_id'])) ? $_REQUEST['tablet_id'] : null;
//print_r($_REQUEST); die();
$interviewersxtablets = new Interviewersxtablets();

if ($interviewer_id && $tablet_id) {

    $interviewersxtablets->freeTablets($interviewer_id, $tablet_id);
    $interviewersxtablets->freeTablets2($interviewer_id, $tablet_id);
}
header('Location: ../controlador/interviewersxtablets.php');
?>
