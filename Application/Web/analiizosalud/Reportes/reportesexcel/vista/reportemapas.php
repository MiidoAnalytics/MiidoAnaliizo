<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Miido - Analiizo v2.0</title>
        <link href="../../../sitemedia/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../../sitemedia/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../../../sitemedia/css/sb-admin-2.css" rel="stylesheet">
        <link href="../../../sitemedia/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
        $sqlTmp = '';
        $strQuery = '';

        if (count($TablaReportesxId) > 0):
            foreach ($TablaReportesxId as $item):
                $sqlTmp = $item[0];
                switch ($intIdTown) {
                    case 0:
                        $quitar = "AND (pollcontent->'DOCUMENTINFO'->>'pollCity') like ?";
                        $strQuery = str_replace($quitar, "", $sqlTmp);        
                        break;
                    default:
                        $codMun = "'".$intIdTown."'";
                        $strQuery = str_replace('?', $codMun, $sqlTmp);
                }

            endforeach;
        else:

        endif;

        class reportemapas {

            var $lat_long;

            public function __construct($TablaReportesxId, $TablaQueryxidReporte) {
                foreach ($TablaReportesxId as $item):
                    if (count($TablaQueryxidReporte) > 0):
                        foreach ($TablaQueryxidReporte as $item):
                            $this->lat_long = $this->lat_long . "['" . $item["primer_apellido"] . "'," . $item["latitud"] . "," . $item["longitud"] . '],';
                        endforeach;
                    else:
                    endif;
                endforeach;
            }

            public function setMarcadores() {
                echo "var marcadores = [$this->lat_long];";                
            }

            function buildScript() {
                echo "var mapOptions =
                        {
                            zoom: 16,
                            center: new google.maps.LatLng(marcadores[0][1], marcadores[0][2]),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };

                var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

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

    </script>";
            }

            function drawPage() {
                echo '</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
            </div>
            <div class="col-lg-4">                   
                
                <div class="panel-body">
                    <a href="../../../Informedeavances/informeAvances/controlador/informeAvances.php"><i class="fa fa-table fa-fw"></i> Informe de Avances</a>   
                </div>                    
            </div>
        </div>
    </div>

    <div id="map-canvas"></div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <script type="text/javascript">
            function VerElementoSeleccionado()
            {
                var index = mapas.encuestadores.selectedIndex;
                var val = mapas.encuestadores[index].value;
                return val;
            }
    </script> 

</body>
</html>';
            }

        }

        $lat_long;

        /*
          if ($row = mysql_fetch_array($resultMapa)) {
          do {
          $sqlTmp = $row[0];

          switch ($intIdTown) {
          case 0:
          if ($intIdReports == 1) {
          //echo '26 y 27 con Cero';
          $strQuery = str_replace('where codMunicipioDane = ?', '', $sqlTmp);
          } else {
          //echo 'otro query con Cero';
          $strQuery = str_replace('and codMunicipioDane = ?', '', $sqlTmp);
          }
          break;
          default:
          //echo 'mayor Cero';
          $strQuery = str_replace('?', $intIdTown, $sqlTmp);
          }

          $resultMapaTmp = mysql_query($strQuery);

          if ($rowTmp = mysql_fetch_array($resultMapaTmp)) {
          do {
          $lat_long = $lat_long . "['" . $rowTmp["APELLIDOS"] . "'," . $rowTmp["LATITUD"] . "," . $rowTmp["LONGITUD"] . '],';
          } while ($rowTmp = mysql_fetch_array($resultMapaTmp));
          }
          } while ($row = mysql_fetch_array($resultMapa));
          }

          $lat_long;
         * 
          //               var marcadores = [<?php// echo($lat_long); ?>
          // ];

         */
        ?>






