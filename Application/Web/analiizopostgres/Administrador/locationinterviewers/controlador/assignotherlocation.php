<?php
    define('CONTROLADOR', TRUE);
    
    require_once '../modelo/classlocationinterviewers.php';
    $locationInterviewer = new LocationInterviewers;
    $intIdInterviewer = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;
    $town_id = (isset($_REQUEST['town_id'])) ? $_REQUEST['town_id'] : null; 
    $departament_id = (isset($_REQUEST['departament_id'])) ? $_REQUEST['departament_id'] : null; 
    //print_r($_REQUEST); die();

    $locationInterviewer->setCodDepartament($intIdInterviewer);
    $locationInterviewer->setCodTown($town_id);
    $locationInterviewer->setIdInterviewer($departament_id);
    
    $locationInterviewer->updateInterviewerLocation($intIdInterviewer, $town_id, $departament_id);
    header('Location:locationinterviewers.php');
?>
