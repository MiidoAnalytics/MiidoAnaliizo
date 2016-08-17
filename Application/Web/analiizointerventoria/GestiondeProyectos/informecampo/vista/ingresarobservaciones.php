<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fInter.planaccion.focus();
                document.fInter.removeEventListener('submit', validarFormulario);
                document.fInter.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fInter;
                validartexto(formulario);
            }    
        </script>
        <title> Ingresar Observaciones </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Ingresar Observaciones</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Ingresar Observaciones
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/ingresarobservaciones.php" role="form" name="fInter" >
                                            <div class="form-group">
                                                <label>Plan de Acción</label>
                                                <textarea name="planaccion" id="planaccion" rows="8" cols="150" placeholder="Describa el plan de acción para las problemáticas mencionadas en el punto anterior." required>
                                                    <?php echo $observacion->getPlanAccion(); ?> 
                                                </textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Responsable :</label>
                                                <input type="text" name="responsable" id="responsable" placeholder="Responsable de ejecución." class="form-control"
                                                    value="<?php echo $observacion->getResponsable(); ?> " required>
                                            </div>
                                             <div class="form-group">
                                                    <label>Fecha Porgramada</label>
                                                    <?php
                                                        $tem1 = $observacion->getFechaProgramada();
                                                        if($tem1 != null){
                                                            $dateini = date_create($tem1);
                                                            $dateini2 = date_format($dateini, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechapro" id="fechapro" value="<?php echo $dateini2; ?>" required>
                                                </div>
                                            <div class="form-group">
                                                <label>Comentarios</label>
                                                <textarea name="comentarios" id="comentarios" rows="8" cols="150" placeholder="Comentarios del interventor." required>
                                                    <?php echo $observacion->getComentarios(); ?>
                                                </textarea>
                                            </div>
                                            <?php if ($observacion->getIdObservacion()): ?>
                                                <input type="hidden" name="obserid" value="<?php echo $observacion->getIdObservacion(); ?>" />
                                            <?php endif; ?>
                                            <input type="hidden" name="encuestaid" value="<?php echo $encuesta ?>" />
                                            <input type="submit" class="btn btn-success" value="Guardar" >               
                                            <a href="../controlador/informecampo.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
