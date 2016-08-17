<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title>Miido - Analiizo</title>
        
        <script>
            function enviar(inputString) {
                for (var i = 0; i < 200; i++) {
                    document.getElementById('D'+i).value = inputString;
                    document.getElementById('M'+i).value = inputString;
                }
            }
            function MostrarPreloader () {
                document.getElementById('preload').style.display = 'block';
            }
            function descargar(data){
                var intervalo = setInterval(function(){
                var cookie = document.cookie;
                if (cookie.search('desIni') != -1) {
                    document.getElementById('preload').style.display = 'none';    
                    document.cookie = 'desIni=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                    clearInterval(intervalo);       
                };    
                },1000)
                location.href = 'reportExcel.php?town_id='+document.getElementById('D'+data).value+'&intIdReport='+document.getElementById('D_'+data).value;
                
                return false;       
            }

        </script>

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

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informes Excel - {$client_name}</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->              
                <!-- <div class="panel-body">
                    <div class="row">
                        <label for="Municipio" class="col-lg-2 control-label">Municipio</label>
                        <div class="col-lg-6">

                            <select name='municipio' id='municipio' class='form-control' required onchange="enviar(this.value)">                
                                <option value="<?php //echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
                                <?php
                                /*if (count($MunicipiosEncuestados) > 0):
                                    foreach ($MunicipiosEncuestados as $item):*/
                                        ?>
                                        <option value="<?php //echo $item['codmunicipio']; ?>"><?php //echo $item['nombremunicipio']; ?></option>
                                        <?php
                                    //endforeach;
                                //else:
                                    ?>
                                    <p> No hay registros para mostrar </p>
                                <?php
                                //endif;
                                ?>   
                            </select>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Reportes Excel
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">

                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nombre Reporte</th>
                                                <th>Descripción</th>
                                                <th style="display: none">Usuario Registra</th>
                                                <th style="display: none">Fecha Registro</th>
                                                <th style="display: none">Fecha Modificado</th>                                                
                                                <th>Descargar</th>
                                                <th>Mapa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($reports) > 0):
                                                $i = 0;
                                                ?>
                                                <?php foreach ($reports as $item): ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidreport']; ?></td>
                                                        <td><?php echo $item['strreportname']; ?></td>
                                                        <td><?php echo $item['strdescription']; ?></td>
                                                        <td style="display: none"><?php echo $item['strregistereduser']; ?></td>
                                                        <td style="display: none"><?php echo $item['dtcreatedate']; ?></td>
                                                        <td style="display: none"><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td align="center">
                                                            <form name="fVerPre" action="javascript:descargar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" name="intIdReport" id="D_<?php echo $i; ?>" value="<?php echo $item['intidreport']; ?>">
                                                                <input type="hidden" name="town_id" id="D<?php echo $i; ?>" />
                                                                <input type="submit" name="VerPre" value="Descargar" style="width:95%" class="odd gradeX" onclick="MostrarPreloader();"/>       
                                                            </form>  
                                                        </td>

                                                        <td align="center">
                                                            <form name="FverMapa" action="reportemapas.php" method="GET"> 
                                                                <input type="hidden" name="intIdReport" value="<?php //cho $item['intidreport']; ?>">
                                                                <input type="hidden" name="town_id" id="M<?php //echo $i; ?>" />
                                                                <input type="submit" name="VerPre" formtarget="_blank" value="Ver" style="width:95%" class="odd gradeX"/>       
                                                            </form>  
                                                        </td>

                                                    </tr>
                                                    <?php $i++;
                                                endforeach;
                                                ?>
                                        <?php else: ?>
                                            <p> No hay Reportes para mostrar </p>
<?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>            
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>


        </div>
        <!-- /#wrapper -->

<?php include("../../../sitemedia/html/scriptpie.php"); ?>

        <!-- jQuery -->
        <script src="../../../sitemedia/js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../../../sitemedia/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../../../sitemedia/js/plugins/metisMenu/metisMenu.min.js"></script>

        <!-- DataTables JavaScript -->
        <script src="../../../sitemedia/js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="../../../sitemedia/js/plugins/dataTables/dataTables.bootstrap.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../../../sitemedia/js/sb-admin-2.js"></script>

        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
           });
        </script>
        <div id="preload" class = "d_preloader">
            <div class ="d_background">
            </div>
            <div class ="d_container">
                <p>
                    Cargando...
                </p>
            </div>
        </div>
    </body>

</html>
