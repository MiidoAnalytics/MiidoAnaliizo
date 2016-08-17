<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro?");
                if (r == true) {
                    window.location.href = 'eliminarequipo.php?equipo_id='+document.getElementById('idint'+data).value;
                } else {
                    window.location = self.location;
                } 
            }
        </script>
        <title> Listar Equipos </title>

    </head>

    <body onmousemove ="contador();" onkeydown ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Equipos</h1>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Equipos
                            </div>
                            <div class="panel-body">
                                <p> <a href="../controlador/guardarequipo.php"> Crear nuevo equipo </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Equipo</th>
                                                <th>Nombre Equipos</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificado</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (empty($_SESSION['user'])) {
                                                ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            if (count($equipos) > 0):
                                                $i = 1;
                                                foreach ($equipos as $item):
                                                    ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidequipo']; ?></td>
                                                        <td><?php echo $item['strnombreequipo']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardarequipo.php?equipo_id=<?php echo $item['intidequipo'] ?>"> Editar </a></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $i; ?>" value="<?php echo $item['intidequipo'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                        </td> 
                                                        <!-- <td><a href="../controlador/eliminarequipo.php?equipo_id=<?php //echo $item['intidequipo'] ?>"> Eliminar </a></td>  -->
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay equipos para mostrar </p>
                                            <?php
                                            endif;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

    </body>

</html>
