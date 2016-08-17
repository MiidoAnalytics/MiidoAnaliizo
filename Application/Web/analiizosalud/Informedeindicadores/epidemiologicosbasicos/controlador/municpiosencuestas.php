<?php

define('CONTROLADOR', TRUE);

$proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
$encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;

if ($proyecto > 0) {

    require_once '../modelo/classepidemiologicosbasicos.php';
    $MunicipiosEncuestados = EpidemioBasico::recuperarMunicipiosEncuestados($proyecto, $encuesta);
    ?>
    <select name='municipio' id='municipio' class='form-control' required>
        <option value="<?php echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
    <?php
        foreach ($MunicipiosEncuestados as $item):
        ?>                                     
            <option value="<?php echo $item['codmunicipio']; ?>"><?php echo $item['nombremunicipio']; ?></option>  
        
        <?php
    endforeach;
    ?> 
    </select>
<?php
} else {
    ?>                                  
    <select name='municipio' id='municipio' class='form-control' required disabled="">
        <option value="<?php echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
    </select>
    <?php
}
?>