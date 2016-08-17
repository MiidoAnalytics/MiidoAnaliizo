<?php
define('CONTROLADOR', TRUE);
require_once '../modelo/classperfil.php';
	$idcasa = (isset($_GET['idcasa'])) ? $_GET['idcasa'] : null;
    $proyecto = (isset($_GET['proyecto'])) ? $_GET['proyecto'] : null;
    $encuesta = (isset($_GET['encuesta'])) ? $_GET['encuesta'] : null;

    $perfilFamilia = Perfil::obtenerPerfilFamiliaCasa($proyecto, $encuesta, $idcasa);
    
    $perfilFamiliaTit = Perfil::obtenerPerfilFamiliaCasaTit($proyecto, $encuesta, $idcasa);
    
    $personasFamilia = Perfil::obtenerPersonasxcasa($proyecto, $encuesta, $idcasa);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title>Miido - Analiizo</title>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script>
            var map;
            function initialize()
            {
                <?php
                $lat_long = "";

                if (count($perfilFamilia) > 0):
                    foreach ($perfilFamilia as $item):
                        $lat_long = $lat_long . "['" . $item["nombre_familia"] . "'," . $item["latitud"] . "," . $item["longitud"] . ']';
                    endforeach;
                else:
                endif;
                $lat_long;
                ?>
                var marcadores = [<?php echo($lat_long); ?> ];
                var marcador, latitud, longitud;

                 for (i = 0; i < marcadores.length; i++)
                {   
                    marcador = marcadores[0][0];
                    latitud = marcadores[0][1];
                    longitud = marcadores[0][2];
                }

                map = new google.maps.Map(document.getElementById('map-canvas'), 
                    {
                        zoom: 16,
                        center : {lat: latitud, lng: longitud},
                        mapTypeId: google.maps.MapTypeId.HYBRID
                    });

                var infowindow = new google.maps.InfoWindow();
                var marker;

                marker = new google.maps.Marker
                   ({
                        position: {lat: latitud, lng: longitud},
                        map: map,
                        title: marcador,
                        animation: google.maps.Animation.DROP
                    });

                google.maps.event.addListener(marker, 'click', (function (marker)
                {
                    return function ()
                    {
                        infowindow.setContent(marcador);
                        infowindow.open(map, marker);
                    }
                })(marker));
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </head>  
<body style="background-image: url('/analiizo/images/background_01.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
    
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Perfil Familia</h1>
                </div>
                <div class="col-lg-6">
                    <div class="panel-body">
                        <h2 class="page-header">Información Básica</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilFamilia) > 0):
                                    $i = 0;
                                    for ($i=0; $i < count($perfilFamiliaTit); $i++) { 
                                        if ($perfilFamiliaTit[$i] != 'vivienda_es') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilFamiliaTit[$i])); ?></td>
                                        <td><?php echo strtoupper($perfilFamilia[0][$i]); ?></td>
                                    </tr>
                                <?php 
                                        }else{
                                            break;
                                        }
                                    } 
                                else: ?>
                                    <p> No hay resultados para mostrar </p>
                                <?php endif; ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-body">
                        <h2 class="page-header">Servicios</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilFamilia) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilFamiliaTit); $i++) { 
                                        if ($perfilFamiliaTit[$i] != 'cocina_dormitorio') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilFamiliaTit[$i])); ?></td>
                                        <td><?php echo strtoupper($perfilFamilia[0][$i]); ?></td>
                                    </tr>
                                <?php 
                                        }else{
                                            break;
                                        }
                                    } 
                                else: ?>
                                    <p> No hay resultados para mostrar </p>
                                <?php endif; ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="panel-body">
                        <h2 class="page-header">Mapa</h2>
                        
                        <div id="map-canvas" class="panel-body" style="height: 30em"></div>
                    </div>
                </div>
            </div>
            <div class="row">  
                <div class="panel-body">
                    <h2 class="page-header">Características físicas</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                
                            </thead>
                            <tbody>  
                            <?php if (count($perfilFamilia) > 0):
                                $control = $i;
                                for ($i=$control; $i < count($perfilFamiliaTit); $i++) { 
                                    if ($perfilFamiliaTit[$i] != 'perros') {
                            ?>                              
                                <tr class="odd gradeX">
                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilFamiliaTit[$i])); ?></td>
                                    <td><?php echo strtoupper($perfilFamilia[0][$i]); ?></td>
                                </tr>
                            <?php 
                                    }else{
                                        break;
                                    }
                                } 
                            else: ?>
                                <p> No hay resultados para mostrar </p>
                            <?php endif; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-body">
                    <h2 class="page-header">Presencia de animales y/o macotas</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                
                            </thead>
                            <tbody>  
                            <?php if (count($perfilFamilia) > 0):
                                $control = $i;
                                for ($i=$control; $i < count($perfilFamiliaTit); $i++) { 
                                    if ($perfilFamiliaTit[$i] != 'departamento') {
                            ?>                              
                                <tr class="odd gradeX">
                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilFamiliaTit[$i])); ?></td>
                                    <td><?php echo strtoupper($perfilFamilia[0][$i]); ?></td>
                                </tr>
                            <?php 
                                    }else{
                                        break;
                                    }
                                } 
                            else: ?>
                                <p> No hay resultados para mostrar </p>
                            <?php endif; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-body">
                    <h2 class="page-header">Ubicación</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                
                            </thead>
                            <tbody>  
                            <?php if (count($perfilFamilia) > 0):
                                $control = $i;
                                for ($i=$control; $i < count($perfilFamiliaTit); $i++) { 
                                    if ($perfilFamiliaTit[$i] != 'fecha_encuesta') {
                            ?>                              
                                <tr class="odd gradeX">
                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilFamiliaTit[$i])); ?></td>
                                    <td><?php echo strtoupper($perfilFamilia[0][$i]); ?></td>
                                </tr>
                            <?php 
                                    }else{
                                        break;
                                    }
                                } 
                            else: ?>
                                <p> No hay resultados para mostrar </p>
                            <?php endif; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-body">
                    <h2 class="page-header">Personas que integran la familia</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                
                            </thead>
                            <tbody>  
                             <thead>
                                <tr>
                                    <th>Tipo Doc</th>
                                    <th>Num Doc</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>Rol</th>
                                    <th>ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($personasFamilia) > 0): ?>
                                    <?php foreach ($personasFamilia as $item): ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo strtoupper($item['tipo_doc']); ?></td>
                                            <td><?php echo $item['num_identificacion']; ?></td>
                                            <td><?php echo strtoupper($item['primer_nombre']); ?></td>
                                            <td><?php echo strtoupper($item['segundo_nombre']); ?></td>
                                            <td><?php echo strtoupper($item['primer_apellido']); ?></td>
                                            <td><?php echo strtoupper($item['segundo_apellido']); ?></td>
                                            <td><?php echo $item['rol_casa']; ?></td>
                                            <td><a href="verperfil.php?tipoDoc=<?php echo $item['tipo_doc'];?>&numDoc=<?php echo $item['num_identificacion'];?>&proyecto=<?php echo $proyecto;?>&encuesta=<?php echo $encuesta;?>" target="_blank"> Ver </a></td> 
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p> No hay resultados para mostrar </p>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include("../../../sitemedia/html/scriptpie.php"); ?>
</body>
</html>