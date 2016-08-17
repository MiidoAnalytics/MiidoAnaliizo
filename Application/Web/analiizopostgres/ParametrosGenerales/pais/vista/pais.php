<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Listar paises </title>

    </head>

    <body onmousemove ="contador();" onkeydown ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Paises</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Paises
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarpais.php"> Crear nuevo pais </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Pais</th>
                                                <th>Código Pais</th>
                                                <th>Nombre Pais</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificado</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            //include_once "conexion.php";

                                            if (empty($_SESSION['user'])) {
                                                ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            if (count($paises) > 0):
                                                foreach ($paises as $item):
                                                    ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidcountry']; ?></td>
                                                        <td><?php echo $item['strcodcountry']; ?></td>
                                                        <td><?php echo $item['strcountryname']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardarpais.php?pais_id=<?php echo $item['intidcountry'] ?>"> Editar </a></td>
                                                        <td><a href="../controlador/eliminarpais.php?pais_id=<?php echo $item['intidcountry'] ?>"> Eliminar </a></td> 
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay paises para mostrar </p>
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
