<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fTablet.strNomtablets.focus();
                document.fTablet.removeEventListener('submit', validarFormulario);
                document.fTablet.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fTablet;
                validartexto(formulario);
            }    
        </script>
    <h1> Guardar Tablet </h1>

</head>

<body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

    <div id="wrapper">

        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administración Tablet</h1>
                </div>                    
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Guardar Tablet
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form method="post" action="../controlador/guardartablets.php" role="form" name = "fTablet">
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" class="form-control" name="strNomtablets" id="strCodCups" placeholder="Nombre Tablet" value="<?php echo $tablets->getNomtablets() ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Llave</label>
                                            <input type="text" class="form-control" name="strKeytablets" id="strKeytablets" placeholder="Llave Tablet" value="<?php echo $tablets->getKeytablets() ?>" required>
                                        </div>

                                        <?php if ($tablets->getIdTablet()): ?>
                                            <input type="hidden" name="tablet_id" value="<?php echo $tablets->getIdTablet() ?>" />
                                        <?php endif; ?>

                                        <input type="submit" class="btn btn-success" value="Guardar">                  
                                        <a href="../controlador/tablets.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
