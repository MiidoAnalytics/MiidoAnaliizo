<?php

define('CONTROLADOR', TRUE);

require_once '../modelo/classencinterviewer.php';

$intidinterviewer = (isset($_REQUEST['intidinterviewer'])) ? $_REQUEST['intidinterviewer'] : null;
$intidestructura = (isset($_REQUEST['intidestructura'])) ? $_REQUEST['intidestructura'] : null;
$intidProyecto = (isset($_REQUEST['intidProyecto'])) ? $_REQUEST['intidProyecto'] : null;

if($intidinterviewer && $intidestructura && $intidProyecto) {
    EncuestaInterviewer::deleterelEstructura($intidinterviewer, $intidestructura);
    $conteo = EncuestaInterviewer::contarInterviewerEncuesta($intidinterviewer);
    foreach ($conteo as $key) {
    	//echo $key['cantidad']; die();
    	if($key['cantidad'] == 0){
    		EncuestaInterviewer::deleterelProyectoEncues($intidinterviewer, $intidProyecto);
    	}
    }
    header('Location: asignacionencuestas.php');
}
?>