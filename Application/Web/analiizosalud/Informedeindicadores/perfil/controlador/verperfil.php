<?php
define('CONTROLADOR', TRUE);
require_once '../modelo/classperfil.php';

    $tipoDoc = (isset($_GET['tipoDoc'])) ? $_GET['tipoDoc'] : null;
    $numDoc = (isset($_GET['numDoc'])) ? $_GET['numDoc'] : null;

    $proyecto = (isset($_GET['proyecto'])) ? $_GET['proyecto'] : null;
    $encuesta = (isset($_GET['encuesta'])) ? $_GET['encuesta'] : null;

    $perfilPersona = Perfil::obtenerPerfilPersonaDocumento($proyecto, $encuesta, $tipoDoc, $numDoc);
    $enfermedades = Perfil::obtenerEnfermedadesPorDocumento($proyecto, $encuesta, $numDoc, $tipoDoc);
    //print_r($enfermedades); die();
    $perfilPersonaTit = Perfil::obtenerPerfilPersonaDocumentoTit($proyecto, $encuesta,$tipoDoc, $numDoc);
    $flagGenero; $flagAdulJoven; $flagAdulto;
    $flagEdad; $recienNacido; $menorDiez;
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

                if (count($perfilPersona) > 0):
                    foreach ($perfilPersona as $item):
                        $latitud = $item["latitud"];
                        $longitud = $item["longitud"];
                        $lat_long = $lat_long . "['" . $item["primer_nombre"] . " " . $item["primer_apellido"] . "'," . $latitud . "," . $longitud . ']';
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
                        <h1 class="page-header">Perfil Persona</h1>
                    </div>
                </div>
                <div class="row">   
                    <div class="col-lg-6">
                        <h1 class="page-header">Información Básica</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $i = 0;
                                    for ($i=0; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'DEPARTAMENTO') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                    </tr>
                                <?php 
                                        }else{
                                            break;
                                        }
                                        if ($perfilPersona[0][$i] == 'MASCULINO') {
                                            $flagGenero = 0;
                                        }elseif($perfilPersona[0][$i] == 'FEMENINO'){
                                            $flagGenero = 1;
                                        }
                                        if(strtoupper($perfilPersonaTit[$i]) == 'EDAD'){
                                            $edad = "";
                                            $cadena = $perfilPersona[0][$i];
                                            for( $index = 0; $index < strlen($cadena); $index++ )
                                            {
                                                if( is_numeric($cadena[$index]) )
                                                {
                                                    $edad .= $cadena[$index];
                                                }
                                            }
                                            if ($edad <= 15) {
                                                if ($edad < 1) {
                                                  $recienNacido = 1; 
                                                }
                                                if ($edad <= 11){
                                                    $menorDiez = 1;
                                                }
                                                $flagEdad = 1;
                                            }elseif ($edad > 15) {
                                                $flagEdad = 0;
                                                $recienNacido = 0;
                                                $menorDiez = 0;
                                            }elseif ($edad <= 30) {
                                                $flagAdulJoven = 1;
                                            }elseif ($edad > 30) {
                                                $flagAdulto = 1;
                                            }
                                        }
                                    } 
                                else: ?>
                                    <p> No hay resultados para mostrar </p>
                                <?php endif; ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h1 class="page-header">Ubicación</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'DESPLAZADO') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
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
                    <div class="col-lg-6">
                    <div class="panel-body">
                        <h2 class="page-header">Mapa</h2>
                        
                        <div id="map-canvas" class="panel-body" style="height: 25em"></div>
                    </div>
                </div>
                </div>
                <div class="row" class="col-lg-6">
                    <div class="col-lg-6">
                        <h1 class="page-header">Información Adicional</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'MALTRATO') {
                                            if ($perfilPersona[0][$i] == '') {
                                            }elseif ($perfilPersonaTit[$i] == 'beneficios') {
                                                $beneficio = $perfilPersona[0][$i];
                                                ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php
                                            }elseif ($beneficio == 'SI') {
                                                if ($perfilPersona[0][$i] != 'NO') {
                                                    ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php
                                                }
                                            }elseif ($beneficio == 'NO') {
                                            }else{
                                                ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php
                                            }
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

                    <?php 
                    //Abuso
                    $abuso = $perfilPersona[0][$i];
                    if ($abuso == 'SI') { ?>
                    <div class="col-lg-6">
                        <h1 class="page-header">Abuso</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'PESO') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
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
                    <?php }else{
                        $control = $i;
                        for ($i=$control; $i < count($perfilPersonaTit); $i++) {
                            if (strtoupper($perfilPersonaTit[$i]) != 'PESO') {
                            }else{
                                break;
                            }
                        }
                    }?>  
                </div>
                <div class="row" class="col-lg-6">
                    <div class="col-lg-6">
                        <h1 class="page-header">Mediciones Antropométricas</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'CONTROL_DENTAL_ULTIMO_SEMESTRE') {
                                            if ($perfilPersona[0][$i] == '') { 
                                            }else{
                                                ?>                              
                                                <tr class="odd gradeX">
                                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                    <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                </tr>
                                            <?php
                                            }
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

                    <div class="col-lg-6">
                        <h1 class="page-header">Información Odontológica</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'ACTIVIDAD_MENSTRUAL') {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
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
                <div class="row" class="col-lg-6">
                    <?php
                    if ($flagGenero == 1 && $edad > 9) { ?>
                    <div class="col-lg-6">
                        <h1 class="page-header">Mujer y edad Fértil</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                </thead>
                                <tbody>  
                                <?php if (count($perfilPersona) > 0):
                                    $control = $i;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if (strtoupper($perfilPersonaTit[$i]) != 'PESO_RECIEN_NACIDO') {
                                            if (strtoupper($perfilPersonaTit[$i]) == 'ACTIVIDAD_MENSTRUAL') {
                                                if ($perfilPersona[0][$i] == 'NO') {
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                    $i= $i + 2;
                                                }else{
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                }
                                            }elseif (strtoupper($perfilPersonaTit[$i]) == 'ACTUALMENTE_PRESENTA_MENSTRUACION') {
                                                if ($perfilPersona[0][$i] == 'NO') {
                                                    $edadFinMens = $perfilPersona[0][$i];
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                    $i= $i + 2;
                                                }else{
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                }
                                            }elseif (strtoupper($perfilPersonaTit[$i]) == 'EDAD_FINAL_MENSTRUACION') {
                                                if ($edadFinMens == 'NO' && $edad > 40) {
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                    $i++;
                                                }else{
                                                    $edadFinMens = '';
                                                }
                                            }elseif (strtoupper($perfilPersonaTit[$i]) == 'FECHA_ULTIMA_MENSTRUACION') {
                                                if ($edadFinMens == 'NO' && $edad > 40) {
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                    $i++;
                                                }else{
                                                    $edadFinMens = '';
                                                }
                                            }elseif (strtoupper($perfilPersonaTit[$i]) == 'ACTIVIDAD_SEXUAL') {
                                                $actSexual = $perfilPersona[0][$i];
                                                if ($perfilPersona[0][$i] == 'SI') {
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php 
                                                }else{
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                }
                                            }
                                            if ($actSexual == 'SI') {
                                                if (strtoupper($perfilPersonaTit[$i]) == 'EMBARAZO') {
                                                    if ($perfilPersona[0][$i] == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }else{
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo "NO"; ?></td>
                                                        </tr>
                                                        <?php
                                                        $i = $i + 2;
                                                    }
                                                }elseif (strtoupper($perfilPersonaTit[$i]) == 'PLANIFICACION_FAMILIAR') {
                                                    if ($perfilPersona[0][$i] == 'SI' && $actSexual == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $perfilPersonaTit[$i]; ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }else{
                                                        $i = $i + 2;
                                                    }
                                                }elseif (strtoupper($perfilPersonaTit[$i]) == 'TIENE_HIJOS') {
                                                    $tieneHij = $perfilPersona[0][$i];
                                                    if ($tieneHij == 'SI') {
                                                        $control = $i;
                                                        for ($i=$control; $i < count($perfilPersonaTit); $i++) {
                                                            if (strtoupper($perfilPersonaTit[$i]) != 'ABORTO') {
                                                                if ($perfilPersona[0][$i] != '') {
                                                                    ?>                              
                                                                    <tr class="odd gradeX">
                                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }else{ break; }
                                                        }
                                                    }
                                                }elseif (strtoupper($perfilPersonaTit[$i]) == 'ABORTO') {
                                                    if ($perfilPersona[0][$i] == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }else{
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                        $i++;
                                                    }
                                                }elseif (strtoupper($perfilPersonaTit[$i]) == 'CITOLOGIA') {
                                                    if ($actSexual == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }else{
                                                        //$i++;
                                                    }
                                                }elseif(strtoupper($perfilPersonaTit[$i]) == 'EXAMEN_MAMA') {
                                                    echo $exaMama = $perfilPersona[0][$i];
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                    <?php
                                                }elseif(strtoupper($perfilPersonaTit[$i]) == 'CONOCE_EXAMEN_MAMA' && $exaMama == 'SI') {
                                                    ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            if (strtoupper($perfilPersonaTit[$i]) == 'VACUNA_VPH') {
                                                if ($actSexual == 'NO' && $edad < 17) {
                                                    if ($perfilPersona[0][$i] == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php
                                                    }else{
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                        $i = $i + 2;
                                                    }
                                                }
                                            }elseif ($perfilPersonaTit[$i] == 'NUM_DOSIS_VACUNA_VPH') {
                                                ?>                              
                                                    <tr class="odd gradeX">
                                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                    </tr>
                                                <?php
                                                if ($perfilPersona[0][$i] == '3') {
                                                    $i++;
                                                }
                                            }elseif ($perfilPersona[0][$i] == '') {
                                            }
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
                    <?php 
                    }else{
                        $control = $i;
                        for ($i=$control; $i < count($perfilPersonaTit); $i++) {
                            if (strtoupper($perfilPersonaTit[$i]) != 'PESO_RECIEN_NACIDO') {
                            }else{
                                break;
                            }
                        }
                    }
                    //Información Menores de 15 años
                    if ($edad <= 15) :
                        if ($edad < 1) : ?>
                            <div class="col-lg-6">
                                <h1 class="page-header">Información Niño - Recién Nacido</h1>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            
                                        </thead>
                                        <tbody>  
                                        <?php if (count($perfilPersona) > 0):
                                            $control = $i;
                                            for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                                if ($perfilPersonaTit[$i] != 'carnet_vacunacion') {
                                        ?>                              
                                            <tr class="odd gradeX">
                                                <td><?php echo $perfilPersonaTit[$i]; ?></td>
                                                <td><?php echo $perfilPersona[0][$i]; ?></td>
                                            </tr>
                                        <?php 
                                                }else{
                                                    break;
                                                }
                                            }  
                                        endif; ?> 
                                        </tbody>
                                    </table>
                                </div>
                            </div> <?php
                        else:
                            for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                if ($perfilPersonaTit[$i] != 'carnet_vacunacion') {
                                }else{
                                    break;
                                }
                            } 
                        endif;
                        if ($edad <= 11) :
                            ?>
                            <div class="col-lg-6">
                                <h1 class="page-header">Información Niño - Vacunación</h1>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            
                                        </thead>
                                        <tbody>  
                                        <?php if (count($perfilPersona) > 0):
                                            $control = $i;
                                            for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                                if ($perfilPersonaTit[$i] != 'refuerzo_alimentos_ninos') {
                                                    if ($perfilPersonaTit[$i] =='carnet_vacunacion') {
                                                        $carnetVac = $perfilPersona[0][$i];
                                                        ?>                              
                                                            <tr class="odd gradeX">
                                                                <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                                <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                            </tr>
                                                        <?php 
                                                    }elseif ($perfilPersonaTit[$i] =='vacunacion_completa') {
                                                        $vacCom = $perfilPersona[0][$i];
                                                        if ($carnetVac == 'SI') {
                                                            ?>                              
                                                                <tr class="odd gradeX">
                                                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                                    <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                                </tr>
                                                            <?php 

                                                        }else{ $i = $i+27; }
                                                    }elseif ($vacCom != 'SI') {
                                                        $i = $i+26;
                                                    }else{
                                                        if ($perfilPersona[0][$i] != '') {
                                                            ?>                              
                                                                <tr class="odd gradeX">
                                                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                                    <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                                </tr>
                                                            <?php
                                                        }
                                                    }
                                                }else{
                                                    break;
                                                }
                                            }  
                                        endif; ?> 
                                        </tbody>
                                    </table>
                                </div>
                            </div> <?php 
                        else:
                            for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                if ($perfilPersonaTit[$i] != 'refuerzo_alimentos_ninos') {
                                }else{
                                    break;
                                }
                            }  
                        endif;
                        ?>
                        <div class="col-lg-6">
                            <h1 class="page-header">Información Niño - Crecimiento Y desarrollo </h1>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        
                                    </thead>
                                    <tbody>  
                                    <?php if (count($perfilPersona) > 0):
                                        $control = $i;
                                        for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                            if ($perfilPersonaTit[$i] != 'ant_cancer') {
                                                if ($perfilPersonaTit[$i] == 'refuerzo_alimentos_ninos') {
                                                    $refueAliNin = $perfilPersona[0][$i];
                                                    ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                        </tr>
                                                    <?php 
                                                }elseif (($perfilPersonaTit[$i] == 'proveedor_refuerzo_nutricional')) {
                                                    if ($refueAliNin == 'SI') {
                                                        ?>                              
                                                            <tr class="odd gradeX">
                                                                <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                                <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                            </tr>
                                                        <?php 
                                                    }
                                                }else{
                                                    ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                        </tr>
                                                    <?php 
                                                }
                                            }else{
                                                break;
                                            }
                                        }  
                                    endif; ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div> <?php
                    else:
                        $control = $i;
                        for ($i=$control; $i < count($perfilPersonaTit); $i++) {
                            if ($perfilPersonaTit[$i] != 'ant_cancer') {
                            }else{
                                break;
                            }
                        }
                    endif;
                    ?>
                </div>
                <div class="row" class="col-lg-6">
                    <div class="col-lg-6">
                        <h1 class="page-header">Antecedentes Familiares</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php
                                    $control = $i;
                                    $control2 = 0;
                                    $control3 = 0;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if ($perfilPersonaTit[$i] != 'prostata') {
                                            if ($perfilPersona[0][$i] != '' and $perfilPersona[0][$i] != 'NO') {
                                                ?>                              
                                                <tr class="odd gradeX">
                                                    <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                    <td><?php echo strtoupper($perfilPersona[0][$i]); ?></td>
                                                </tr>
                                                <?php 
                                            }else{
                                                $control3++;
                                            }
                                        }else{
                                            break;
                                        }
                                        $control2++;
                                    } 
                                
                                    if ($control2 == $control3) {
                                        ?>
                                        <p> La persona no presenta enfermedades reportadas </p>
                                        <?php
                                    }  
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <div class="col-lg-6">
                        <h1 class="page-header">Medicamentos</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php 
                                    /*$control = $i;
                                    $control2 = 0;
                                    $control3 = 0;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if ($perfilPersonaTit[$i] != 'EXAMEN PROSTATA') {
                                            if ($perfilPersona[0][$i] != '' and $perfilPersona[0][$i] != 'NO' and $perfilPersona[0][$i] != null) {*/
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php //echo $perfilPersonaTit[$i]; ?></td>
                                        <td><?php //echo $perfilPersona[0][$i]; ?></td>
                                    </tr>
                                <?php 
                                        /*    }else{
                                                $control3++;
                                            }
                                        }else{
                                            break;
                                        }
                                        $control2++;
                                    } 
                                
                                if ($control2 == $control3) {*/
                                    ?>
                                    <p> La persona no presenta medicamentos reportadas </p>
                                <?php
                                //}  ?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                </div>
                <div class="col-lg-12">
                    <h1 class="page-header">Patologías y Medicamentos</h1>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            </thead>
                            <tbody>  
                            <?php 
                                if (count($enfermedades) > 0) {
                                    foreach ($enfermedades as $key) {
                                        ?>                              
                                            <tr class="odd gradeX">
                                                <td style="font-weight:bold;"><?php echo strtoupper($key['enfermedad']); ?></td>
                                            </tr>
                                        <?php 
                                        $enfermedad = $key['enfermedad'];
                                        $medicamentos = Perfil::obtenerMedicamentosPorDocumento($proyecto, $encuesta, $numDoc, $tipoDoc, $enfermedad);
                                        if (count($medicamentos > 0)) {
                                            foreach ($medicamentos as $key2) {
                                            ?>                              
                                                <tr class="odd gradeX">
                                                    <td><?php echo strtoupper($key2['medicamento']); ?></td>
                                                    <td><?php echo strtoupper($key2['evolucion_tratamiento']); ?></td>
                                                    <td><?php echo strtoupper($key2['proveedor']); ?></td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                    }
                                }else{
                                    ?>
                                        <p> La persona no presenta patologías reportadas </p>
                                    <?php
                                }  ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" class="col-lg-6">
                <?php              
                    if ($flagGenero == 0 && $edad > 40) { ?>
                        <div class="col-lg-6">
                            <h1 class="page-header">Información Relacionada a la Próstata</h1>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        
                                    </thead>
                                    <tbody>  
                                    <?php 
                                    if (count($perfilPersona) > 0):
                                        $control = $i;
                                        for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                            if ($perfilPersonaTit[$i] != 'vivienda_es') {
                                                if ($perfilPersonaTit[$i] == 'prostata') {
                                                    if ($perfilPersona[0][$i] == 'SI') {
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                    }else{
                                                        ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                        $i = $i + 2;
                                                    }
                                                }else{
                                                    ?>                              
                                                        <tr class="odd gradeX">
                                                            <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                                            <td><?php echo $perfilPersona[0][$i]; ?></td>
                                                        </tr>
                                                    <?php 
                                                }
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
                        <?php 
                    }else{
                        $control = $i;
                        for ($i=$control; $i < count($perfilPersonaTit); $i++) {
                            if ($perfilPersonaTit[$i] != 'vivienda_es') {
                            }else{
                                break;
                            }
                        }
                    }?>

                    <div class="col-lg-6">
                        <h1 class="page-header">Características de la vivienda</h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    
                                </thead>
                                <tbody>  
                                <?php 
                                    $control = $i;
                                    $control2 = 0;
                                    $control3 = 0;
                                    for ($i=$control; $i < count($perfilPersonaTit); $i++) { 
                                        if ($perfilPersonaTit[$i] != 'fecha_encuesta') {
                                            if ($perfilPersona[0][$i] != '' and $perfilPersona[0][$i] != 'NO' and $perfilPersona[0][$i] != null) {
                                ?>                              
                                    <tr class="odd gradeX">
                                        <td><?php echo strtoupper(str_replace("_", " ", $perfilPersonaTit[$i])); ?></td>
                                        <td><?php echo $perfilPersona[0][$i]; ?></td>
                                    </tr>
                                <?php 
                                            }else{
                                                $control3++;
                                            }
                                        }else{
                                            break;
                                        }
                                        $control2++;
                                    } 
                                
                                if ($control2 == $control3) {
                                    ?>
                                    <p> La persona no presenta medicamentos reportadas </p>
                                <?php
                                }  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php include("../../../sitemedia/html/scriptpie.php"); ?>
    </body>
</html>