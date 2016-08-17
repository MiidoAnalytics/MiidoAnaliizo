<?php

define('CONTROLADOR', TRUE);
require_once '../modelo/classperfil.php';
	$proyecto = (isset($_POST['proyecto'])) ? $_POST['proyecto'] : null;
	$encuesta = (isset($_POST['encuesta'])) ? $_POST['encuesta'] : null;

   $tipoIdentidades = Perfil::obtenerTipoIdentificacion($proyecto, $encuesta);
   ?>
   <option value="">Seleccione</option>  
   <?php
    foreach ($tipoIdentidades as $item):
    ?>                                     
        <option value="<?php echo $item['tipo']; ?>"><?php echo $item['tipo']; ?></option>  
    
    <?php
    endforeach;
?>