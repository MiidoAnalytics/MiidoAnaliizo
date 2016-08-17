<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classactividadpresupuesto.php';
    $actpre = new ActividadPresupuesto();
    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    if ($proyecto) {
	    $idusuario = $_SESSION['userid'];
	    $proyectos = ActividadPresupuesto::ObtenerProyectosUsuerId($idusuario);
	    $pollstru = ActividadPresupuesto::ObtenerEstructuraProyectoId($proyecto);
	    
	    $idpollstru = $pollstru[0]['intidestructura'];
	    $actividades = ActividadPresupuesto::recuperarTodos($idpollstru, $proyecto);

	    /*foreach ($actividades as $key) {
	    	$idcamest = $key['id'];
	    	$existebd = ActividadPresupuesto::buscarcampobd($idcamest, $idpollstru, $proyecto);
	    	print_r($existebd); die();
	    	if (count($existebd) > 0 && $key['grupo'] != 0) {
	    	}else if($key['grupo'] != 0 && $key['tipo'] == 'tf' && $key['descripcion'] != 'VALOR COSTOS DIRECTOS'){
	    		$descripcion = $key['descripcion'];

	    		$actpre->setStrNombreActividad($descripcion);
	    		$actpre->setintCampoEstructura($idcamest);
	    		$actpre->setIdProyecto($proyecto);
	    		$actpre->setIdEncuesta($idpollstru);
	    		//$actpre->guardar();
	    	}
	    }*/
	    $actividadesDos = ActividadPresupuesto::obtenerAllActividadesbd(1, $proyecto);
	}else {
        $idusuario = $_SESSION['userid'];
        $proyectos = ActividadPresupuesto::ObtenerProyectosUsuerId($idusuario);
        $actividadesDos = [];
    }
    require_once '../vista/actividadpresupuesto.php';
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>