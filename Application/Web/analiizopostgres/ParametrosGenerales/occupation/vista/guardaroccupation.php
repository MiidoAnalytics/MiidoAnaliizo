<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

    <h1> Guardar Ocupaciones </h1>

</head>

<body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

    <div id="wrapper">

        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

        <div id="page-wrapper">

            <div id="banner">

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administración Ocupaciones</h1>
                </div>                    
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Guardar Ocupaciones
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form method="post" action="../controlador/guardaroccupation.php" role="form">
                                        <div class="form-group">
                                            <label>Código</label>
                                            <input type="text" class="form-control" name="strCodeOccupation" id="strCodeOccupation" placeholder="Código Ocupación" value="<?php echo $Occupation->getCodeOccupation() ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" class="form-control" name="strNameOccupation" id="strNameOccupation" placeholder="Nombre Ocupación" value="<?php echo $Occupation->getNameOccupation() ?>" required>
                                        </div>

                                        <?php if ($Occupation->getIdOccupation()): ?>
                                            <input type="hidden" name="Occupation_id" value="<?php echo $Occupation->getIdOccupation() ?>" />
                                        <?php endif; ?>

                                        <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                        <a href="../controlador/occupation.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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