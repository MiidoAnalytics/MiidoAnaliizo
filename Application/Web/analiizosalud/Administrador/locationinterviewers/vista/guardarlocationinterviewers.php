<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fLocInt.strCodDepartament.focus();
                document.fLocInt.removeEventListener('submit', validarFormulario);
                document.fLocInt.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fLocInt;
                validartexto(formulario);
            }    
        </script>
        <script type="text/javascript">
            function lookup(inputString) {
                $.post("../vista/municipiosselect.php", {queryString: "" + inputString + ""}, 
                    function (data) {
                    $('#strCodTown').html(data);
                });
            }
        </script>
        
        <title> Asignar Lugar Encuestador </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            require_once '../../../ParametrosGenerales/departamento/modelo/classdepartamento.php';
            $departamentos = Departament::recuperarTodos();
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Asignación de Lugar Encuestador</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Asignación de Lugar Encuestador
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6"> 
                                        <form name="fLocInt" method="post" action="../controlador/guardarlocationinterviewers.php" class="form-horizontal" role="form" >       
                                            <div class="form-group">
                                                <label for="Departament" class="col-lg-2 control-label">Departamento</label>
                                                <div class="col-lg-6">
                                                    <select name='strCodDepartament' id='strCodDepartament' class='form-control' onchange="lookup(this.value);" required>
                                                        <option value="<?php echo '0'; ?>" selected="selected">SELECCIONE</option> 
                                                        <?php foreach ($departamentos as $item): ?>
                                                            <option value="<?php echo $item['strcoddepartament']; ?>"><?php echo $item['strdepartamentname']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>   
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="strCodTowns" class="col-lg-2 control-label">Municipio</label>
                                                <div class="col-lg-6" >
                                                    <select name='strCodTown' id='strCodTown' class='form-control' required>

                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            require_once '../../interviewer/modelo/classinterviewer.php';
                                            $interviewers = Interviewer::recuperarTodos();
                                            ?>
                                            <div class="form-group">
                                                <label for="InterviewerName" class="col-lg-2 control-label">Nombre Usuario</label>
                                                <div class="col-lg-6">
                                                    <select name='locationinterviewer_id' id='locationinterviewer_id' class='form-control' required> 
                                                        <?php foreach ($interviewers as $item): ?>
                                                            <option value="<?php echo $item['intidinterviewer']; ?>"><?php echo $item['strusername']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>   
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input type="submit" class="btn btn-success" value="Guardar">              
                                                    <a href="../controlador/locationinterviewers.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                                </div>
                                            </div>
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
