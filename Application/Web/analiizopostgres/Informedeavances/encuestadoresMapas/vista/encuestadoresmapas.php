<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <title>Miido - Analiizo v2.0</title>

        <!-- Bootstrap Core CSS -->
        <link href="../../../sitemedia/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="../../../sitemedia/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../../../sitemedia/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../../../sitemedia/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


        <script>
            function showMunicipio() {
                t1 = document.getElementById('encuestadores').value;
                if (t1 == 0)
                {

                    window.location = ("encuestadoresmapas.php");
                }
                else
                {
                    window.location = ("encuestadoresmapas.php?encuestador_id=" + t1);
                }
            }

        </script>


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


    <style>
        html, body, #map-canvas 
        {
            height: 100%;
            margin: 0px;
            padding: 0px
        }
        #panel 
        {
            position: absolute;
            top: 5px;
            left: 50%;
            margin-left: -180px;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
        }
    </style>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>


    <script>
            var map;
            function initialize()
            {

<?php
$lat_long = "";

if (count($TablaEncuestasxEncuestador) > 0):
    foreach ($TablaEncuestasxEncuestador as $item):
        $lat_long = $lat_long . "['" . $item["nombre_familia"] . "'," . $item["latitud"] . "," . $item["longitud"] . '],';
    endforeach;
else:

endif;

$lat_long;
?>

                var marcadores = [
<?php echo($lat_long); ?>


                ];


                var mapOptions =
                        {
                            zoom: 16,
                            //center: new google.maps.LatLng(9.39736500,-75.06241000),
                            center: new google.maps.LatLng(marcadores[0][1], marcadores[0][2]),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };

                map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                var infowindow = new google.maps.InfoWindow();
                var marker, i, j;

                j = 0;

                for (i = 0; i < marcadores.length; i++)
                {

                    marker = new google.maps.Marker
                            ({
                                position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
                                map: map,
                                title: marcadores[i][0],
                                animation: google.maps.Animation.DROP
                            });

                    google.maps.event.addListener(marker, 'click', (function (marker, i)
                    {
                        return function ()
                        {
                            infowindow.setContent(marcadores[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));

                    j++;
                }

                var puntoA = new google.maps.LatLng(marcadores[0][1], marcadores[0][2]);
                var puntoB = new google.maps.LatLng(marcadores[j - 1][1], marcadores[j - 1][2]);

                var bounds = new google.maps.LatLngBounds();

                bounds.extend(puntoA);
                bounds.extend(puntoB);

                map.fitBounds(bounds);
            }

            google.maps.event.addDomListener(window, 'load', initialize);

    </script>

</head>  
<body onmousemove ="contador();">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                <div class="panel-body">
                    <div class="row">
                        <!--<label for="Encuestador" class="col-lg-2 control-label">Seleccione el Encuestador:</label>-->
                        <div class="col-lg-8">
                            <select name='encuestadores' id='encuestadores' class='form-control' >                
                                <option value="<?php echo '0'; ?>" selected="selected">Seleccionar encuestador</option> 
                                <?php
                                if (count($TablaEncuestadorxEncuesta) > 0):
                                    foreach ($TablaEncuestadorxEncuesta as $item):
                                        ?>
                                        <option value="<?php echo $item['id_encuestador']; ?>"><?php echo $item['username']; ?></option>
                                        <?php
                                    endforeach;
                                else:
                                    ?>
                                    <p> No hay registros para mostrar </p>
                                <?php
                                endif;
                                ?>  
                            </select>

                        </div>

                        <button type="button" class="btn btn-success" onclick="showMunicipio()">Aceptar</button>

                    </div>
                </div>

                <div class="panel-body">

                </div>	
            </div>
            <div class="col-lg-4">                   
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <a href="../../informeAvances/controlador/informeAvances.php"><i class="fa fa-table fa-fw"></i> Informe de Avances</a>	
                </div>                    
            </div>
        </div>
    </div>

    <div id="map-canvas"></div>

    <!-- jQuery -->
    <script src="../../../sitemedia/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../../sitemedia/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../../sitemedia/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../../sitemedia/js/sb-admin-2.js"></script>


    <script type="text/javascript">
                            function VerElementoSeleccionado()
                            {
                                var index = mapas.encuestadores.selectedIndex;
                                var val = mapas.encuestadores[index].value;

                                return val;
                            }
    </script> 

</body>


</html>