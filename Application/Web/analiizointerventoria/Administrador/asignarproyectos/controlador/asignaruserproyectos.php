<?php

define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

    $flag = Session::TiempoSession();
    if ($flag == 1) {
		require_once '../modelo/classproyectosuser.php';
    	$proyectos = ProyectosUser::getAllProyectos();

    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{      
	        $intidproyecto = (isset($_POST['intidproyecto'])) ? $_POST['intidproyecto'] : null;
	        $idusuarios = (isset($_POST['idusuarios'])) ? $_POST['idusuarios'] : null;

	        $validar = ProyectosUser::validarUserProyecto($intidproyecto, $idusuarios);
	        foreach ($validar as $key) {
	        	if ($key['cantidad'] > 0) {
	        		?>
	        		<script type="text/javascript">
	        			alert('Este Usuario ya cuenta con el proyecto asignado. Por favor valide la información.');
	        			window.location = self.location;
	        		</script>
	        		<?php
	        	}else{
	        		ProyectosUser::guardar($intidproyecto, $idusuarios);
	        		header('Location: ../controlador/asignarproyectos.php');
	        	}
	        }
	    }else
	    {     
	        include '../vista/asignaruserproyectos.php';
	    }
    }else {
	   require_once '../../../sitemedia/html/pageerror.php';
	}   
?>