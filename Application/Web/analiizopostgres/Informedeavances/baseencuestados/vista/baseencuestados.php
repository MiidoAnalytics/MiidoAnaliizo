<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <title>Miido - Analiizo</title>

    </head>

    <?php
    @session_start();

    if (empty($_SESSION['user'])) {
        ?>

        <div class="alert alert-danger">
            El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
        </div>
        <?php
    }
    ?>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Relación de personas encuestadas
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>                                            
                                            <th>area</th>
                                            <th>latitud</th>
                                            <th>longitud</th>                                            
                                            <th>tipo_casa</th>
                                            <th>nombre_familia</th>
                                            <th>nombres y apellidos</th>
                                            <th>direccion</th>
                                            <th>telefono</th>
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
                                        if (count($TablaRelacionPersonasEncuestadas) > 0):
                                            foreach ($TablaRelacionPersonasEncuestadas as $item):
                                                ?>
                                                <tr class="odd gradeX">                                                    
                                                    <td><?php echo $item['area']; ?></td>
                                                    <td><?php echo $item['latitud']; ?></td>
                                                    <td><?php echo $item['longitud']; ?></td>                                                    
                                                    <td><?php echo $item['tipo_casa']; ?></td>                                                    
                                                    <td><?php echo $item['nombre_familia']; ?></td>
                                                    <td><?php echo $item['nombreapellido']; ?></td>
                                                    <td><?php echo $item['direccion']; ?></td>
                                                    <td><?php echo $item['telefono']; ?></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <p> No hay registros para mostrar </p>
                                        <?php
                                        endif;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>

        </div>            

    </div>

    <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

</body>

</html>
