<?php

    define('CONTROLADOR', TRUE);
    //se llama a la clase que realiza las consultas de los reportes
    require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';

    $intIdDiv = (isset($_GET['intIdDiv'])) ? $_GET['intIdDiv'] : null;
    $proyecto = $_GET["proyecto"];
    $encuesta = $_GET["encuesta"];
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
            $quitar = "AND (pollcontent->'DOCUMENTINFO'->>'pollCity') like ?";
            $strQuery = str_replace($quitar, "", $strQuery);        
            break;
        default:    
            $codMun = "'".$intIdTown."'";
            $strQuery = str_replace('?', $codMun, $strQuery);  
    }
    //obtenemos los titulos del reporte
    $titles = Report::TitlesCols($strQuery);
    $titlesF = array();
    for ($t=0; $t < count($titles); $t++) { 
        $title = $titles[$t];
        $title = str_replace("_", " ", $title);
        $titlesF[] = strtoupper($title);
    }
    $titlesF[] = 'MEDICAMENTOS';
    $titlesF[] = 'ENFERMEDADES';

    //contamos el numero de columnas
    $numCols = count($titlesF);

    set_time_limit(60);
    //obtenemos los datos del reporte
    $data = $ResultReport->Query($strQuery);
    
    $i = 0;
    foreach ($data as $key=>$value) {
        $tipoid = $key['tipo_identificacion'];
        $docNum = $key['documento_identificacion'];
        $diseases = $ResultReport->obtenerEnfermedadesPorDocumento($proyecto, $encuesta, $tipoid, $docNum);
        $enf = '';
        if (count($diseases) > 0) {
            foreach ($diseases as $keyD => $valueD) {
                $med = '';
                $medicines = $ResultReport->obtenerMedicamentosPorDocumento($proyecto, $encuesta, $docNum, $tipoid, $valueD);
                if (count($medicines) > 0) {
                    foreach ($medicines as $keyM => $valueM) {
                        $med .= $valueM;
                    }
                }else{ $med = 'NO PRESENTA'; }
                $data[$i]['MEDICAMENTOS'] = $med;
                $enf .= $valueD; 
            }
            $enf;
        }else{ 
            $enf = "NO PRESENTA"; 
            $med = 'NO PRESENTA';
            $data[$i]['MEDICAMENTOS'] = $med;
        }
        $data[$i]['ENFERMEDADES'] = $enf;
        $i++;
    }
    
    //Verifica si existen datos
    if (count($data) != 0) {

        /** Se agrega la libreria */
        require_once '../../../core/lib/PHP_XLSXWriter-master/xlsxwriter.class.php';
        
        // Se asignan las propiedades del libro        
        $campos = array_fill_keys($titlesF, 'String');

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
        $limit = 'LIMIT 4000';
        $incOffset = 'limit 4000 OFFSET ';

        do{
            switch ($contador) {
                case 1:

                    $strQuery = str_replace($limit, $incOffset.$offset, $strQuery); 
                    //die($strQuery);
                    break;
                
                default:
                    $strQuery = str_replace($incOffset.$offset2, $incOffset.$offset, $strQuery); 
                    break;
            }
            $contador++;
            $offset2 = $offset;

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
