<!DOCTYPE html>
<html lang="es">

    <head>
        <title>Informe Semanal</title>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <!-- <link href="../../../sitemedia/css/full-slider.css" rel="stylesheet">
        <link href="../../../sitemedia/css/bootstrap2.min.css" rel="stylesheet"> -->
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                var obser = document.getElementById('counterObser').value;
                if (obser > 0) {
                    //document.getElementById('planaccion').disabled = false;
                }else{
                    //document.getElementById('planaccion').disabled = true;
                };
            }
        </script>
    </head>

<?php
    /**
    * @author Edinson Ordoñez Date: 20160330
    * 
    */
    @session_start();

    if (empty($_SESSION['user'])) {
        ?>
        <div class="alert alert-danger">
            El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
        </div>
        <?php
    }
    ?>

    <body onmousemove ="contador();" onkeydown="contador();" >
        <div id="wrapper">
        <?php //include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">INFORME SEMANAL DE INTERVENTORIA - GERENCIA DE PROYECTOS</h1>
                    </div>  
                    <div class="panel-heading" style="min-height: 82px;">
                        <div class="panel-heading-text">
                            INFORME SEMANAL DE INTERVENTORIA - GERENCIA DE PROYECTOS - <?php echo "SEMANA ".$periodo; ?>
                            <input id="titulos" type="hidden" value="INFORME SEMANAL DE INTERVENTORIA - GERENCIA DE PROYECTOS" />
                            <input id="idpoll" type="hidden" value="<?php echo $poll_id; ?>" />
                            <input id="idproject" type="hidden" value="<?php echo $project_id; ?>" />
                            <input id="idpollstr" type="hidden" value="<?php echo $pollstr_id; ?>" />
                            <input id="description" type="hidden" value="<?php echo $descripcion; ?>" />
                        </div>
                        <div class="panel-heading-menu">
                            <ul class="nav navbar-top-links">
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="fa fa-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a class="botonExcel" id="botonExcel"><i class="fa fa-file-excel-o fa-fw"></i> Descargar en Excel</a>
                                        </li>
                                    </ul>            
                                </li> 
                            </ul>  
                        </div>
                    </div>                 
                </div>
                <div class="row" id="Exportar_a_Excel">
                    <div class="col-lg-12">
                        <div class="panel panel-default"> 
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr class="odd gradeX">
                                                <td>Fecha :</td>
                                                <td><?php echo $fechaCreacion;?></td>
                                                <td>Periodo N° :</td>
                                                <td><?php echo $periodo; ?></td>
                                                <td>Del :</td>
                                                <td><?php echo date_format($perini, 'Y-m-d'); ?></td>
                                                <td>Al :</td>
                                                <td><?php echo date_format($perfin, 'Y-m-d'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            <div class="panel-heading">
                                1. Información General
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr class="odd gradeX">
                                                <td>OBJETO DEL CONTRATO :</td>
                                                <td><?php echo $proyectonombre; ?></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>LOCALIZACIÓN DEL PROYECTO :</td>
                                                <td><?php echo $localizacion; ?></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>ETAPA DE CONSTRUCCIÓN </td>
                                                <td>Fecha de Inicio</td>
                                                <td><?php echo date_format($fechainietacon, 'Y-M-d'); ?></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr class="odd gradeX">
                                                <td>CONTRATO DE INTERVENTORÍA </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>CONTRATO DE OBRA </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Número de Contrato :</td>
                                                <td><?php echo $numcontinter; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Número de Contrato :</td>
                                                <td><?php echo $numcontinter; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Plazo Inicial :</td>
                                                <td><?php echo $plazoini." Meses"; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Plazo Inicial :</td>
                                                <td><?php echo $plazoini; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Fecha de Iniciación :</td>
                                                <td><?php echo date_format($fechaini, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Fecha de Iniciación :</td>
                                                <td><?php echo date_format($fechaini, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Fecha de Suspensión :</td>
                                                <td><?php echo date_format($fechasus, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Fecha de Suspensión :</td>
                                                <td><?php echo date_format($fechasus, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Fecha de Reiniciación :</td>
                                                <td><?php echo date_format($fechareini, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Fecha de Reiniciación :</td>
                                                <td><?php echo date_format($fechareini, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Fecha de Terminación :</td>
                                                <td><?php echo date_format($fechafin, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Fecha de Terminación :</td>
                                                <td><?php echo date_format($fechafin, 'Y-M-d'); ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Plazo Actualizado :</td>
                                                <td><?php echo $plazoini." Meses"; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Plazo Actualizado :</td>
                                                <td><?php echo $plazoini." Meses"; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Plazo Transcurrido :</td>
                                                <td><?php echo $plazotranscurrido." dias"; ?></td>
                                                <td>Equivale :</td>
                                                <td><?php echo round($equivale, 2)." %"; ?></td>
                                                <td>Plazo Transcurrido :</td>
                                                <td><?php echo $plazotranscurrido." dias"; ?></td>
                                                <td>Equivale :</td>
                                                <td><?php echo round($equivale,2)." %"; ?></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Valor Inicial :</td>
                                                <td><?php echo $valorInint; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Valor Inicial :</td>
                                                <td><?php echo $valorIniObr; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Valor Adición(es) :</td>
                                                <td><?php echo $vadadiint; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Valor Adición(es) :</td>
                                                <td><?php echo $valadobra; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Valor Actualizado :</td>
                                                <td><?php echo $valActInt; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Valor Actualizado :</td>
                                                <td><?php echo $valActObra; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Valor Pagado :</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Valor Pagado :</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>Valor a Pagar :</td>
                                                <td><?php echo $vadadiint; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>Valor a Pagar :</td>
                                                <td><?php echo $valActObra; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td>SUPERVISOR DEL PROYECTO :</td>
                                                <td><?php echo $supervisor; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td>INTERVENTOR :</td>
                                                <td><?php echo $nomInt; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                            <div class="panel-heading">
                                2. Control de Hitos
                            </div>
                            <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Descripción del hito</th>
                                                    <th>Fecha Programada</th>
                                                    <th>Fecha de Cumplimiento</th>
                                                    <th>Días de retraso</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (count($actividades) > 0):
                                                        $i = 1;
                                                        foreach ($actividades as $item):
                                                            $idfieldestr = $item['intcampoestructura'];
                                                            $idActi = $item['intidactividad'];
                                                            $desfieldestr = $item['strdescripcion'];
                                                            $estado = '';
                                                            foreach ($preguntas as $key) {
                                                                $idpreencuesta = $key['id'];
                                                                $despoll = $key['descripcion'];
                                                                if($desfieldestr == $despoll){
                                                                    $fechaentregaact = date_create($item['dtprogramentrega']);
                                                                    $fechaentregaact = date_format($fechaentregaact, 'Y-m-d, D');
                                                                    $cantidadContractual = $item['cantidadcontractual'];
                                                                    $CantEjeAct = $item['cantejecutada'];
                                                                    $estado = '';
                                                                    if($CantEjeAct < $cantidadContractual){
                                                                        if ($CantEjeAct > 0) {
                                                                            $estado = 'Iniciado';
                                                                        }elseif ($CantEjeAct == 0) {
                                                                            $estado = 'No iniciado';
                                                                        }
                                                                    }elseif ($CantEjeAct >= $cantidadContractual) {
                                                                        $estado = 'Terminado';
                                                                    }else{
                                                                        $estado = 'No iniciado';
                                                                    }
                                                                 break;
                                                                }else{
                                                                    $estado = 'No iniciado';
                                                                }
                                                                if (count($arraynopro) > 0) {
                                                                    for ($i=0; $i < count($actNoName); $i++) { 
                                                                        $nomActEnc = $actNoName[$i];
                                                                        $canActEnc = $canActNop[$i];
                                                                        if ($desfieldestr == $nomActEnc) {
                                                                            if($CantEjeAct < $canActEnc){
                                                                                if ($CantEjeAct > 0) {
                                                                                    $estado = 'Iniciado';
                                                                                }elseif ($CantEjeAct == 0) {
                                                                                    $estado = 'No iniciado';
                                                                                }
                                                                            }elseif ($CantEjeAct >= $canActEnc) {
                                                                                $estado = 'Terminado';
                                                                            }else{
                                                                                $estado = 'No iniciado';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }     
                                                ?>  
                                                            <tr class="odd gradeX" id="count($informes)">
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $desfieldestr ?></td>
                                                                <td><?php echo $fechaentregaact; ?></td>
                                                                <td><?php if ($estado == 'Terminado') {
                                                                        echo date_format($perfin, 'Y-m-d');
                                                                    } ?>
                                                                </td>
                                                                <td><?php if ($perfin < $fechaentregaact) {
                                                                                
                                                                            }else{echo '0';}?> 
                                                                </td>
                                                                <td><?php 
                                                                        //$cantidadContractual = $item['cantidadcontractual'];
                                                                        echo $estado;
                                                                    ?></td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                          endforeach;
                                                    else:
                                                        ?>
                                                        <p> No hay Actividades para mostrar </p>
                                                    <?php
                                                    endif;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                            </div> 
                            <div class="panel-heading">
                                Indicadores
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Indicador</th>
                                                <th>Valor</th>
                                                <th>Porcentaje sobre el valor total del Contrato</th>
                                                <th>Diferencia (+ Adelanto) (- Retraso)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd gradeX">
                                                <td><?php echo " 1 "; ?></td>
                                                <td><?php echo "Valor acumulado de las actividades o productos conforme a la programación vigente."; ?></td>
                                                <td><?php echo $valorProgramadoInd; ?></td>
                                                <td><?php echo $porceTotal; ?></td>
                                                <td rowspan="2"><?php echo  $diferencia;?></td>
                                            </tr>
                                            <tr class="odd gradeX">
                                                <td><?php echo " 2 "; ?></td>
                                                <td><?php echo "Valor acumulado de las actividades o productos ejecutados y aprobados por la Interventoría."; ?></td>
                                                <td><?php echo $valorTotSemEje; ?></td>
                                                <td><?php echo $porceTotalEje; ?></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            <div class="panel-heading">
                                3. IDENTIFICACIÓN DE SITUACIONES PROBLEMÁTICAS - ANÁLISIS DE CAUSAS
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if (count($observParti) > 0):
                                                $i = 1;
                                                foreach ($observParti as $itemObser):
                                        ?>  
                                                    <tr class="odd gradeX" id="count($informes)">
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $itemObser['recuperarresobservaciones']; ?></td>
                                                        <input type="hidden" id="counterObser" value="<?php echo count($observParti); ?>">
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                  endforeach;
                                            else:
                                                ?>
                                                <p> No hay Situaciones problemáticas para mostrar </p>
                                            <?php
                                            endif;
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-heading">
                                4. PLAN DE ACCIÓN RESULTADO DEL ANÁLISIS DE CAUSAS REGISTRADO EN EL NUMERAL ANTERIOR - CON EL OBJETIVO DE ELIMINAR LA CAUSA RAÍZ DE LA SITUACIÓN PROBLEMÁTICA
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Actividades</th>
                                                <th>Responsable</th>
                                                <th>Fecha Programada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd gradeX">
                                                <td><?php echo $planaccion; ?></td>
                                                <td><?php echo $responsable; ?></td>
                                                <td><?php echo $fechaPla; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            <div class="panel-heading">
                                5. ACTIVIDADES NO PREVISTAS Y MAYORES CANTIDADES
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr class="odd gradeX">
                                        <?php 
                                            if (count($actinombres) > 0):
                                                $j = 1;
                                                for ($i=0; $i < count($actinombres); $i++) { 
                                        ?>  
                                                    <tr class="odd gradeX" id="count($informes)">
                                                        <td><?php echo $j; ?></td>
                                                        <td><?php echo "El contratista realiza mayores cantidades en: ".$actinombres[$i]; ?></td>
                                                        <td><?php echo $cantidadesAct[$i]; ?></td>
                                                    </tr>
                                                    <?php
                                                    $j++;
                                                }
                                            else:
                                                ?>
                                            <p> No hay actividades No programadas Realizadas por el Constructor </p>
                                    <?php
                                            endif;
                                        ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-heading">
                                6. COMENTARIOS DEL INTERVENTOR: 
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                             <tr class="odd gradeX">
                                                <td>
                                                    <?php echo $comentarios; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-heading">
                                7. REGISTRO FOTOGRAFICO DEL AVANCE DURANTE LA SEMANA : 
                            </div>  
                            <!-- <div class="panel-body"> -->
                                <!-- Full Page Image Background Carousel Header -->
                                <div id="myCarousel" class="carousel slide">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                        <li data-target="#myCarousel" data-slide-to="1"></li>
                                        <li data-target="#myCarousel" data-slide-to="2"></li>
                                    </ol>

                                    <!-- Wrapper for Slides -->
                                    <div class="carousel-inner" style="margin: 0 auto; width: 40%">
                                        <?php 
                                            $i = 0;
                                            foreach ($fotos as $key): 
                                            $name = "../../../".$key['strname'];
                                            if ($i == 0) {
                                        ?>
                                        <div class="item active" >
                                            <img src="<?php echo $name; ?>" alt="Third slide">
                                            <div class="carousel-caption">
                                                <h2><?php echo $key['strdescription']; ?></h2>
                                            </div>
                                        </div>
                                        <?php 
                                            }else{
                                                ?>
                                        <div class="item" >
                                            <img src="<?php echo $name; ?>" alt="Third slide">
                                            <div class="carousel-caption">
                                                <h2><?php echo $key['strdescription']; ?></h2>
                                            </div>
                                        </div>
                                        <?php 
                                            }
                                            $i++;
                                            endforeach; ?>
                                    </div>

                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="icon-prev"></span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="icon-next"></span>
                                    </a>
                                </div>
                            <!-- </div>  -->                                         
                        </div>                            
                    </div>
                </div> 
                <form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
                    <input type="hidden" id="titulo" name="titulo" />
                    <input type="hidden" id="pollid" name="pollid" />
                    <input type="hidden" id="projectid" name="projectid" />
                    <input type="hidden" id="pollstrid" name="pollstrid" />
                    <input type="hidden" id="descriptionpoll" name="descriptionpoll" />
                </form>
                <script>
                    $("#botonExcel").unbind();
                    $("#botonExcel").click(function(event) {
                        //$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
                        $("#titulo").val($("#titulos").val());
                        $("#pollid").val($("#idpoll").val());
                        $("#projectid").val($("#idproject").val());
                        $("#pollstrid").val($("#idpollstr").val());
                        $("#descriptionpoll").val($("#description").val());
                        $("#FormularioExportacion").submit();
                    });
                </script>         
            </div>   
        </div>
        <script src="../../../sitemedia/js/jquery2.js"></script>
        <script src="../../../sitemedia/js/bootstrap2.js"></script>
        <script type="text/javascript">
            $('.carousel').carousel({
              interval: 3000
            })
        </script>
        
        <?php //include("../../../sitemedia/html/scriptpie.php"); ?>         
    </body>
</html>