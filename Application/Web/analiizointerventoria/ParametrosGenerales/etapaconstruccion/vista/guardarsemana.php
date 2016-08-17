<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fSemanas.porProSem73.focus();
                document.fSemanas.removeEventListener('submit', validarFormulario);
                document.fSemanas.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fSemanas;
                validartexto(formulario);
            }  
            
        </script>
        <title> Guardar Programación Semanal </title>

    </head>
    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
        <?php
            include("../../../Administrador/menu/controlador/menu.php");
        ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Parámetros Generales - Programación por semana</h1>
                    </div>                    
                </div>
                <form method="post" action="../controlador/guardarsemana.php" role="form" name="fSemanas">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                            <?php
                                $aux = 71;
                                $idPre = 73;
                                $idPre2 = 0;
                                for ($i = 0; $i < count($titulos); $i++) {
                            ?>
                                <div class="panel-heading">
                                    <?php echo $titulos[$i]; ?>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-10">
                                        <?php
                                        $tipo = '';
                                            do {
                                                //$idPre = $key['id'];
                                                $idPre2 = $aux;
                                                
                                                if ($idPre2 < 152) {
                                                    $tipo = $preguntas[$idPre2]['tipo']; 
                                                    //echo '-'.$idPre.'-';   
                                                    if ($tipo != 'rg') {
                                                        $existebd = Construccion::buscarcampobd($idPre); 
                                                        //echo count($existebd);
                                                        if (count($existebd) > 0) {
                                                            $tit = $existebd[0]['strdescripcion'];
                                                
                                        ?>
                                            <div class="form-group">
                                                <label><?php echo $tit; ?></label>
                                                <input type="text" class="form-control" name="porProSem<?php echo $existebd[0]['intidactividad']; ?>" id="porProSem<?php echo $existebd[0]['intidactividad']; ?>"
                                                placeholder="Porcentaje Programado" value="<?php //echo $proyecto->getNombre(); ?>">
                                                <input type="hidden" name="intidactividad<?php echo $existebd[0]['intidactividad']; ?>" value="<?php echo $existebd[0]['intidactividad']; ?>" />
                                            </div>
                                        <?php   
                                                     }
                                                    }else{
                                                        $idPre++;
                                                        $aux++;
                                                        break;
                                                    } 
                                                    
                                                }else{
                                                }
                                                $idPre++;
                                                $aux++;
                                            } while ($aux < count($preguntas));
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="col-lg-12">
                                            <?php //if ($proyecto->getintidproyecto()): ?>
                                                
                                            <?php //endif; ?> 
                                            <input type="hidden" name="idsemana" value="<?php echo $semana_id; ?>" />
                                            <input type="submit" class="btn btn-success" value="Guardar">                  
                                            <a href="etapaconstruccion.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

    </body>

</html>
