<?php

    define('CONTROLADOR', TRUE);
    //se llama a la clase que realiza las consultas de los reportes
    require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';

    $intIdDiv = (isset($_GET['intIdDiv'])) ? $_GET['intIdDiv'] : null;
    $intIdTown = $_GET["town_id"];
    $intIdReports = (isset($_GET['intIdReport'])) ? $_GET['intIdReport'] : null;

    $ResultReport = new Report();
    if ($intIdDiv) {
        $resultado = Report::recuperarIdReporte($intIdDiv);
        foreach ($resultado as $key) {
            $intIdReports = $key['intidreport'];
        }
    }
    $datos = Report::searchById($intIdReports);
    $name_excel = $datos->getReportName();
    $description = $datos->getDescription();
    $strQuery = $datos->getQuery();

    //reemplaza en el query dependiendo el codigo de municipio
    switch ($intIdTown) {
        case 0:
            //$strQuery = str_replace("AND (encuestacasas->'DOCUMENTINFO'->>'pollCity') = ?", "", $strQuery1); 
            //die($strQuery);        
            break;
        default:       //echo 'mayor Cero';
           // $strQuery = str_replace('?', "'$intIdTown'", $strQuery1);  
    }

    //obtenemos los titulos del reporte
    $titles = Report::TitlesCols($strQuery);
    //contamos el numero de columnas
    $numCols = count($titles);

    set_time_limit(60);
    //obtenemos los datos del reporte
    $data = $ResultReport->Query($strQuery);
    //echo count($data); die();
    //Verifica si existen datos
    if (count($data) != 0) {

        /** Se agrega la libreria */
        require_once '../../../core/lib/PHP_XLSXWriter-master/xlsxwriter.class.php';
        
        // Se asignan las propiedades del libro        
        $campos = array_fill_keys($titles, 'String');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            ob_end_clean();
            header('Content-Disposition: attachment;filename="' . $name_excel . '".xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new XLSXWriter();
            $writer->setAuthor('Desarrollo Miido');

        $writer->writeSheet($data,'Hoja1',$campos);  

        $offset = 4000;
        $contador = 1;
        $flag = 0;

        do{
            switch ($contador) {
                case 1:
                    $strQuery = str_replace('limit 4000', 'limit 4000 offset '.$offset, $strQuery); 
                    //die($strQuery);
                    break;
                
                default:
                    $strQuery = str_replace('limit 4000 offset '.$offset2, 'limit 4000 offset '.$offset, $strQuery); 
                    break;
            }
            $contador++;
            $offset2 = $offset;
            //sleep(5);
            $data2 = $ResultReport->Query($strQuery);

            $writer->writeSheet($data2,'Hoja'.$contador,$campos);  

            $auc = count($data2);
            $offset = $offset+4000;
            if($auc > 0){
                $flag = 1;
                $auc;
            }else{$flag = 0;}

        }while($flag == 1);

        setcookie("desIni","1",time()+3600);
        $writer->writeToStdOut();
        
        exit(0);

    }else{
        ?>
        <script language="JavaScript" type="text/javascript">
            alert("No se encontraron datos para este reporte");
            location.href='reportesexcel.php';
        </script>
        <?php
    }
?>
