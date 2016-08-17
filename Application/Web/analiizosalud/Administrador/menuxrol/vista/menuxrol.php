<!DOCTYPE html>
<html lang="en">

    <head>
        
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                document.fMenuRol.role_id.focus();
                document.fMenuRol.removeEventListener('submit', validarFormulario);
                document.fMenuRol.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fMenuRol;
                validartexto(formulario);
            }   
            function Menuxrol(inputString) {
                $.post("../controlador/menuasignadorol.php", {
                    menuid:document.getElementById('menu_id').value ,
                    roleid:document.getElementById('role_id').value 
            }, function (data) {
                    $('#ListamenusxRol').html(data);
                });
            }

            function habilitar(value){
                var rol = document.getElementById('role_id').value;
                var menupadre = document.getElementById('menu_id').value;
                if(rol != '' && menupadre != ''){
                    if(value==true)
                    {   
                        i=1;
                        while(document.getElementById(i) != null){
                            document.getElementById(i).checked = true;
                            i++;
                        };
                    }else if(value==false){
                        i=1;
                        while(document.getElementById(i) != null){
                            document.getElementById(i).checked=false;
                            i++;
                        };
                    }
                }else{
                    alert('Por favor seleccione un rol y un menu para continuar');
                }
            }
        </script>
        
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title> Listar Roles </title>
    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Roles</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Roles
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <!-- <a href="agignarmenurol.php">Asignar Menu a un Rol</a> --> </p>
                                <div class="col-lg-6">
                                    <form method="post" action="../controlador/agignarmenurol.php" role="form" name="fMenuRol" id="fMenuRol">
                                            <div class="form-group">
                                                <label>Roles</label>
                                                <select name='role_id' id='role_id' class='form-control' required onchange="Menuxrol();">
                                                    <option value="" selected="selected">SELECCIONE</option> 
                                                    <?php foreach ($rolesAll as $item): ?>
                                                        <option value="<?php echo $item['role_id']; ?>"><?php echo $item['role_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <?php
                                            if (empty($_SESSION['user'])) {
                                                ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            //echo ($_SESSION['userId']);                                    
                                            ?>
                                            <div class="form-group">
                                                <label>Menu</label>
                                                <select name='menu_id' id='menu_id' class='form-control' onchange="Menuxrol();" required>
                                                    <option value="" selected="selected">SELECCIONE</option> 
                                                <?php if (count($RolMenuPadre) > 0): ?>
                                                <?php foreach ($RolMenuPadre as $item): ?>
                                                    <option value="<?php echo $item['menu_id']; ?>"><?php echo $item['menu_name']; ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Submenu</label>
                                                <div id="dCheckBox">                                   
                                                    <input type='checkbox' value="" name="cktodos" id="cktodos" onchange="habilitar(this.checked);">
                                                    <label>
                                                        Seleccionar/Quitar Todos
                                                    </label>    
                                                </div>
                                                <div id="ListamenusxRol">
                                                </div>
                                            </div>
                                            <input id="guardar" type="submit" class="btn btn-success" value="Guardar">                 
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?> 
        <script type="text/javascript">
        $('#guardar').unbind();
            $('#guardar').bind('click',function(){
                if(!confirm('Esta seguro que desea continuar?\nLa configuracion actual reemplazara la anterior.'))
                return false;
            })
        </script>  

    </body>

</html>
