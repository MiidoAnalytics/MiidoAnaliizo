<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title> Listar Eps </title>
    </head>
    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Eps</h1>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                EPS
                            </div>
                            <div class="panel-body">
                                <p> <a href="../controlador/guardareps.php"> Crear nuevo Eps </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Eps</th>
                                                <th>Código Eps</th>
                                                <th>Nombre Eps</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificación</th>
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
                                            //echo ($_SESSION['userId']);                                    
                                            ?>

                                            <?php if (count($epsAll) > 0): ?>
                                                <?php foreach ($epsAll as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intideps']; ?></td>
                                                        <td><?php echo $item['strcodeps']; ?></td>
                                                        <td><?php echo $item['strnameps']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardareps.php?intideps=<?php echo $item['intideps'] ?>"> Editar </a></td>
                                                        <td><a href="../controlador/deleteeps.php?intideps=<?php echo $item['intideps'] ?>"> Eliminar </a></td> 
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p> No hay Eps para mostrar </p>
                                            <?php endif; ?>
                                        <?php } ?>
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
