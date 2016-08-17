<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

    $flag = Session::TiempoSession();
    if ($flag == 1) {
		require_once '../modelo/classproyectosuser.php';

		$proyectosuser = ProyectosUser::getAllProyectosUser();
		include '../vista/asignarproyectos.php';
		
	 } else {
       require_once '../../../sitemedia/html/pageerror.php';
    }
?>