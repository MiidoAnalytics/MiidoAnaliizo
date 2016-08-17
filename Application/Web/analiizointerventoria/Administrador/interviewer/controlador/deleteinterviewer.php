<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classinterviewer.php';

$interviewer_id = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;

if ($interviewer_id) {
    $interviewer = Interviewer::buscarPorId($interviewer_id);
    $interviewer->delete();
   	$idTablet = $interviewer->tabletAsignada($interviewer_id);

   	require_once '../../interviewersxtablets/modelo/classinterviewersxtablets.php';

   	Interviewersxtablets::freeTablets($interviewer_id, $idTablet);
    Interviewersxtablets::freeTablets2($interviewer_id, $idTablet);
    $interviewer->deleteRelTablet();
    $interviewer->deleteRelLocation();
    header('Location: ../controlador/interviewer.php');
}
?>