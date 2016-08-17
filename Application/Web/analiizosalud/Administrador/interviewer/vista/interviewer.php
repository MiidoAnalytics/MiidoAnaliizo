<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro? Si elimina este registro se eliminarán las relaciones con las tabletas y lugares asignados");
                if (r == true) {
                    window.location.href = 'deleteinterviewer.php?interviewer_id='+document.getElementById('idint'+data).value;
                } else {
                    window.location = self.location;
                } 
            }

            function redireccionar(){
                document.getElementById('frameActualizar').src="http://52.27.125.67/node/userupdatePos.php";
            }

        </script>
        <title> Listar Encuestadores </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Encuestadores</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Encuestadores
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarinterviewer.php"> Crear nuevo encuestador </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Encuestador</th>
                                                <th>Nombres Encuestador</th>
                                                <th>Apellidos Encuestador</th>
                                                <th>Username</th>
                                                <th>Id Operador</th>
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

                                            <?php if (count($interviewers) > 0):
                                                $i = 1;
                                                foreach ($interviewers as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidinterviewer']; ?></td>
                                                        <td><?php echo $item['strnames']; ?></td>
                                                        <td><?php echo $item['strsurnames']; ?></td>
                                                        <td><?php echo $item['strusername']; ?></td>
                                                        <td><?php echo $item['intidoperator']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardarinterviewer.php?interviewer_id=<?php echo $item['intidinterviewer'] ?>"> Editar </a></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $i; ?>" value="<?php echo $item['intidinterviewer'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="" onclick="eliminar(<?php //echo $i; ?>)"> Eliminar </a> -->
                                                            <!-- <a href="../controlador/deleteinterviewer.php?interviewer_id=<?php //echo $item['intidinterviewer'] ?>"> Eliminar </a> -->
                                                        </td> 
                                                    </tr>
                                                <?php
                                                    $i++; 
                                                endforeach; ?>
                                            <?php else: ?>
                                                <p> No hay encuestadores para mostrar </p>
                                            <?php endif; ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                    <!-- <div class="col-lg-12" align ="center">
                        <form action="" method="post" target="frameActualizar">
                            <button type="button" class="btn btn-success" id="ActuaRe" onclick="redireccionar()">Actualizar</button>
                        </form>
                    </div>  
                     <iframe src="" frameborder="0" name="frameActualizar" id="frameActualizar" style="display:none;">

                    </iframe>     -->              
                </div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

    </body>

</html>
