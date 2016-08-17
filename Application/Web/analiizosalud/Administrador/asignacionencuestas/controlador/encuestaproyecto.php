<?php

define('CONTROLADOR', TRUE);

extract($_POST);

if ($proyecto > 0) {

    require_once '../modelo/classencinterviewer.php';
    $encuestas = EncuestaInterviewer::ObternerEncuestasProyecto($proyecto);
    ?>
    <select name='encuesta' id='encuesta' class='form-control' required>
        <option value="" selected="selected">SELECCIONE</option>
    <?php
        foreach ($encuestas as $item):
        ?>                                     
            <option value="<?php echo $item['intidestructura']; ?>"><?php echo $item['nombre']; ?></option>  
        
        <?php
    endforeach;
    ?> 
    </select>
<?php
} else {
    ?>                                  
    <select name='encuesta' id='encuesta' class='form-control' required disabled="">
        <option value="" selected="selected">SELECCIONE</option>
    </select>
    <?php
}
?>