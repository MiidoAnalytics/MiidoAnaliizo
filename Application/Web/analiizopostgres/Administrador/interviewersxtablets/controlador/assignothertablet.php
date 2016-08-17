<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classinterviewersxtablets.php';
$interviewersxtablets = new Interviewersxtablets();

$intIdInterviewers = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;
$intIdTablet = (isset($_REQUEST['tablet_id'])) ? $_REQUEST['tablet_id'] : null;

$interviewersxtablets->setIdInterviewer($intIdInterviewers);
$interviewersxtablets->setIdTablet($intIdTablet);

$interviewersxtablets->updateTablexInterviewer($intIdInterviewers, $intIdTablet);
header('Location: http://ec2-52-27-125-67.us-west-2.compute.amazonaws.com/analiizopostgres/Administrador/interviewersxtablets/controlador/interviewersxtablets.php');
?>
