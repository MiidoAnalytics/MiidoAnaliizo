<?php

    define('CONTROLADOR', TRUE);

    require_once '../../../core/classSession.php';

    $flag = Session::TiempoSession();
    if ($flag == 1) {

        require_once '../modelo/classproyectos.php';

        $intidproyecto = (isset($_REQUEST['intidproyecto'])) ? $_REQUEST['intidproyecto'] : null;

        if ($intidproyecto) {
            $proyecto = Proyectos::searchById($intidproyecto);
            //print_r($proyecto); die();
        } else {
            $proyecto = new Proyectos();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        	$intidproyecto = (isset($_POST['intidproyecto'])) ? $_POST['intidproyecto'] : null;
            $nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : null;
            $proyecto->setNombre($nombre);
            $proyecto->setintidproyecto($intidproyecto);

            $proyecto->insert();
            header('Location: ../controlador/proyectos.php');
        } else {
            include '../vista/guardarproyectos.php';
        }
    } else {
       require_once '../../../sitemedia/html/pageerror.php';
    }
?>