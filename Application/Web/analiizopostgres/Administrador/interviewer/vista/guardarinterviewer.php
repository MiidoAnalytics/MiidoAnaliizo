<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fInter.strNames.focus();
                document.fInter.removeEventListener('submit', validarFormulario);
                document.fInter.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fInter;
                validartexto(formulario);
            }    
        </script>
        <title> Guardar Encuestador </title>

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
                                Guardar Encuestador
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarinterviewer.php" role="form" name="fInter" >
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input type="text" class="form-control" name="strNames" id="strNames" placeholder="Nombres Encuestador" value="<?php echo $interviewer->getNames() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input type="text" class="form-control" name="strSurnames" id="strSurnames" placeholder="Apellidos Encuestador" value="<?php echo $interviewer->getSurnames() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre Usuario</label>
                                                <input type="text" class="form-control" name="strUsername" id="strUsername" placeholder="username" value="<?php echo $interviewer->getUsername() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Contraseña</label>
                                                <input type="password" class="form-control" name="strHashPassword" id="strHashPassword" placeholder="Contraseña" value="" required>
                                            </div>

                                            <?php if ($interviewer->getIdInterviewer()): ?>
                                                <input type="hidden" name="interviewer_id" value="<?php echo $interviewer->getIdInterviewer() ?>" />
                                            <?php endif; ?>

                                            <input type="submit" class="btn btn-success" value="Guardar" >               
                                            <a href="../controlador/interviewer.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                        </form>                                      
                                    </div>
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
