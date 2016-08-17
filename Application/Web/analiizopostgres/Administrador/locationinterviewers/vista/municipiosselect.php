<?php

define('CONTROLADOR', TRUE);


$queryString = $_POST['queryString'];

if ($queryString > 0) {

   require_once  '../../../ParametrosGenerales/town/modelo/classtown.php';
   $municipios = Town::searchByCodDepto($queryString);
   ?>
    <select name='strCodTown' id='strCodTown' class='form-control' required>
    <?php
        foreach ($municipios as $item):
        ?>                                     
            <option value="<?php echo $item['strcodtown']; ?>"><?php echo $item['strtownname']; ?></option>  
        
        <?php
    endforeach;
    ?> 
    </select>
<?php
} else {
    ?>                                  
    <select name='strCodTown' id='strCodTown' class='form-control' required disabled="">

    </select>
    <?php
}
?>


