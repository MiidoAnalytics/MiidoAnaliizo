<?php

define('CONTROLADOR', TRUE);

extract($_POST);
//echo $menuid;
//echo $roleid;


if ($menuid > 0) {

   require_once  '../../../Administrador/menu/modelo/classroles.php';
   $menus = Roles::ObternerMenusAsignadosRol($roleid, $menuid);
   
   ?>
                
    <?php
    $nameCheck = "";
    $idckb = 1;
        foreach ($menus as $item):
            $nameCheck .= $item['menu_id'].',';
            if($item['istatusid'] == 1){
                ?>  
                <div>                                   
                    <input type='checkbox' value="<?php echo $item['menu_id']; ?>" name="<?php echo $item['menu_id']; ?>" checked="true" id="<?php echo $idckb; ?>">
                    <label>
                        <?php echo $item['menu_name']; ?> 
                    </label>    
                </div>
                <?php
            }else{
                ?>
                <div>                                   
                    <input type='checkbox' value="<?php echo $item['menu_id']; ?>" name="<?php echo $item['menu_id']; ?>" id="<?php echo $idckb; ?>">
                    <label>
                        <?php echo $item['menu_name']; ?> 
                    </label>    
                </div>
                <?php
            }
            $idckb++;
    endforeach;
    ?> 
        <input type='hidden' value='<?php echo $nameCheck; ?>' name='nameCheck'>
    </select>
<?php
} else {
    ?>    
    <p>No ha seleccionado un Menu</p>                              
    <?php
}
?>