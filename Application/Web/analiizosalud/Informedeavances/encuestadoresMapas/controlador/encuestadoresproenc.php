<?php

define('CONTROLADOR', TRUE);

$proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
$encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;

if ($proyecto > 0) {

    require_once '../modelo/classencuestadoresmapas.php';
    $TablaEncuestadorxEncuesta = EncuestadoresMapas::recuperarEncuestadorxEncuesta($proyecto, $encuesta);
    ?>
    <select name='encuestador' id='encuestador' class='form-control' required>
        <option value="0" selected="selected">SELECCIONE</option>
    <?php
        foreach ($TablaEncuestadorxEncuesta as $item):
        ?>                                     
            <option value="<?php echo $item['id_encuestador']; ?>"><?php echo $item['username']; ?></option>  
        
        <?php
    endforeach;
    ?> 
    </select>
<?php
} else {
    ?>                                  
    <select name='encuestador' id='encuestador' class='form-control' required disabled="">
        <option value="0" selected="selected">SELECCIONE</option>
    </select>
    <?php
}
?>