<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fIntTab.intIdInterviewers.focus();
                document.fIntTab.removeEventListener('submit', validarFormulario);
                document.fIntTab.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fIntTab;
                validartexto(formulario);
            }    
        </script>
        <title> Asignar Tablets </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            require_once '../../../Administrador/interviewer/modelo/classinterviewer.php';
            $interviewers = Interviewer::recuperarTodos();
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Asignación Tabletas</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Asignación Tabletas
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action=""  role="form" name="fIntTab">
                                            <div class="form-group">
                                                <label>Nombre Usuario</label>
                                                <select name='intIdInterviewers' id='intIdInterviewers' class='form-control' required> 
                                                    <?php foreach ($interviewers as $item): ?>
                                                        <option value="<?php echo $item['intidinterviewer']; ?>"><?php echo $item['strusername']; ?></option>
                                                    <?php endforeach; ?>
                                                </select> 
                                            </div>

                                            <?php
                                            require_once '../../../ParametrosGenerales/tablets/modelo/classtablets.php';

                                            $tabletsAll = Tablet::getAllTabletsFree();
                                            ?>

                                            <div class="form-group">
                                                <label>Nombre Tablet</label>
                                                <select name='intIdTablet' id='intIdTablet' class='form-control' required> 
                                                    
                                                    <?php foreach ($tabletsAll as $item): ?>
                                                        <option value="<?php echo $item['intidtablet']; ?>"><?php echo $item['strtabletname']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="../controlador/interviewersxtablets.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
