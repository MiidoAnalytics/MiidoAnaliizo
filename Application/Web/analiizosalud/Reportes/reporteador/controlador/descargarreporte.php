<?php
define('CONTROLADOR', TRUE);
require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buscar = ""; $queBet = "";
    $strQue = "select DISTINCT upper(pollcontent->'GROUP'->>'Area') as AREA, pollcontent->'DOCUMENTINFO'->>'Lat' AS LATITUD,
            pollcontent->'DOCUMENTINFO'->>'Lng' AS LONGITUD,
            upper(pns1->>'nombre') AS PRIMER_NOMBRE, upper(pns1->>'sNombre') AS SEGUNDO_NOMBRE, 
            upper(pns1->>'apellido') AS PRIMER_APELLIDO, upper(pns1->>'sApellido') AS SEGUNDO_APELLIDO, 
            (pns1->>'tipoId') AS TIPO_DOC, (pns1->>'documento') AS NUM_IDENTIFICACI0N, 
            upper(D.strdepartamentname) AS DEPARTAMENTO, 
            upper(T.strtownname) AS MUNICIPIO, upper(pollcontent->'GROUP'->>'Poblacion') AS BARRIO, upper(pollcontent->'GROUP'->>'Direccion') AS DIRECCION,
            (CASE WHEN (pollcontent->'GROUP'->>'Telefono') = '' THEN 'NO TIENE' WHEN  (pollcontent->'GROUP'->>'Telefono') = '0' THEN 'NO TIENE' ELSE (pollcontent->'GROUP'->>'Telefono') END) AS TELEFONO,
            (CASE WHEN pns1->>'celular'  = '' THEN 'NO TIENE' WHEN pns1->>'celular'  = '0' THEN 'NO TIENE' ELSE pns1->>'celular'  END) AS TELEFONO_PERSONA,
            concat(pns1->>'Edad', ' AÑOS') AS EDAD, upper(pns1->>'genero') AS GENERO "; 
    $counterUS = 0; $counterCols = 0;
    $flag = 0; $flagColAux = 0; $flagCol = 0;
    $aux = "";
    $aux2 = "";
    $nuevasCols = "";
    $arrayGroup = ["Area", "Poblacion", "cupehaesvi", "cugrfacoesvi", "cupepeco"];
    $arrayDocInfo = ["Lat", "Lng", "pollCity", "pollDate", "interviewer"];
    $arrayHouse = ["ducha", "techo", "Basura", "MatPiso", "codedor","letrina", "paredes", "MatTecho", "atencion", "roedores","vivienda", "Alumbrado", "AtGeneral", "VisionEPS", "respuesta","MatParedes", "iluminaSuf", "origenAgua", "serGasNatD", "ConsumoAgua","InfServicio", "aguasNegras","dormitorios", "serRecoBasu", "consualcohol","serAcueducto", "Instalaciones", "consususpsico", "consucigarrilo", "serAlcantarill","serEneelectrica","atencionPersonal"];
    $from = "";
    //print_r($_POST); die();
    $nivel = "";
    foreach ($_POST as $clave=>$valor){
        if($clave != "project" && $clave != "poll"){
            if(is_array($valor)){
                $counterMul = 0;
                $flag2 = 0;
                foreach ($valor as $key => $value) {
                    $valor2 = $value;
                    $buscar = "opcionesS_";
                    $resultado = strpos($clave, $buscar);

                    if($resultado !== FALSE){
                        $resOp = str_replace($buscar, '', $clave);
                        $colSel = strpos($strQue, $resOp);

                        //buscar a que nivel pertenece en la estructura
                        for ($i=0; $i < count($arrayGroup); $i++) { 
                            if($arrayGroup[$i] == $resOp){
                                $nivel = "pollcontent->'GROUP'->>'";
                                break;
                            }else{
                            }
                        }
                        for ($j=0; $j < count($arrayHouse); $j++) {
                            if($arrayHouse[$j] == $resOp){
                                $nivel = "pollcontent->'HOUSE'->>'";
                                break;
                            }else{
                            }
                        }
                        for ($j=0; $j < count($arrayDocInfo); $j++) {
                            if($arrayDocInfo[$j] == $resOp){
                                $nivel = "pollcontent->'DOCUMENTINFO'->>'";
                                break;
                            }else{
                            }
                        }
                        if ($nivel == "") {
                            $nivel = "pns1->>'";
                        }

                        //si encuentra al campo no lo agrega
                        if ($colSel) { 
                        }else{
                            $column = str_replace("_", " ", $resOp);
                            if ($column == 'pollCity') {
                            }elseif ($column == 'disCode' || $column == 'medDesc') {
                                $column = strtoupper($column);
                                $strQue.= ", ".$nivel.$resOp."' AS ".$column;
                            }else{
                                $nuevasCols .= ", ".strtolower($column)." text";
                                $column = strtoupper($column);
                                $strQue.= ", ".$nivel.$resOp."' AS ".$column;
                            }
                        }
                        if ($counterUS == count($_POST)-1) {
                            $flag = 1;
                        }

                        if ($counterCols == 0) {
                            $flagCol = 1;
                        }else{
                            $flagCol = 0;
                        }
                        switch ($resOp) {
                            case 'disCode':
                                $flagDis = 1;
                                $enfer = strpos($strQue, $resOp);
                                if ($enfer) {
                                    //$from = " CROSS JOIN LATERAL jsonb_array_elements(pns1->'diseases') AS pns2 ";
                                    $strQue = str_replace("pns1->>'disCode' AS DISCODE", " pns2->>'disCode' AS ENFERMEDADES ", $strQue);
                                    $queBet.= "and pns2->>'disCode' like '".$valor2."' ";
                                    $nuevasCols .= ", enfermedades text";
                                }
                                break;
                            case 'medDesc':
                                $flagMed = 1;
                                $medi = strpos($strQue, $resOp);
                                if ($medi) {
                                   $strQue = str_replace("pns1->>'medDesc' AS MEDDESC", " pns3->>'medDesc' AS MEDICAMENTOS ", $strQue);
                                   $queBet.= "and pns3 ->> 'medDesc' like '".$valor2."' ";
                                   $nuevasCols .= ", medicamentos text";
                                }
                                break;
                            default:
                                if ($flag == 1) {
                                    if ($counterMul == count($valor)-1) {
                                        $flag2 = 1;
                                    }
                                    if ($flag2 == 1) {
                                        if ($counterMul > 0) {
                                            $queBet.= $nivel.$resOp."' like '".$valor2."') ";
                                        }else{
                                            if ($flagCol == 1) {
                                                $queBet.= $nivel.$resOp."' like '".$valor2."'";
                                            }else{
                                                $queBet.= " and ".$nivel.$resOp."' like '".$valor2."'";
                                            }
                                        }
                                    }elseif($counterMul == 0){
                                        if ($flagCol == 1) {
                                            $queBet.= " (".$nivel.$resOp."' like '".$valor2."' or ";
                                        }else{
                                            $queBet.= " and (".$nivel.$resOp."' like '".$valor2."' or ";
                                        }
                                    }else{
                                        $queBet.= $nivel.$resOp."' like '".$valor2."' or ";
                                    }
                                }else{
                                    if ($counterMul == count($valor)-1) {
                                        $flag2 = 1;
                                    }
                                    //echo $counterMul;
                                    if ($flag2 == 1) {
                                        if ($counterMul > 0) {
                                            $queBet.=  $nivel.$resOp."' like '".$valor2."') ";
                                        }else{
                                            if ($flagCol == 1) {
                                                $queBet.= " and ".$nivel.$resOp."' like '".$valor2."' ";
                                            }else{
                                                $queBet.= " and ".$nivel.$resOp."' like '".$valor2."' ";
                                            }
                                        }
                                    }else{
                                        if($counterMul < 1){
                                            if ($flagCol == 1) {
                                                $queBet.= " (".$nivel.$resOp."' like '".$valor2."' or ";
                                            }else{
                                                $queBet.= " and (".$nivel.$resOp."' like '".$valor2."' or ";
                                            }
                                        }else{
                                            $queBet.= $nivel.$resOp."' like '".$valor2."' or "; 
                                        }
                                    }
                                }
                                break;
                        }
                        if ($flagMed == 1) {
                            $from = " CROSS JOIN LATERAL jsonb_array_elements(pns1->'diseases') AS pns2 CROSS JOIN LATERAL jsonb_array_elements(pns2->'medicaments') AS pns3 ";
                        }elseif($flagDis == 1 && $flagMed == 0){
                            $from = " CROSS JOIN LATERAL jsonb_array_elements(pns1->'diseases') AS pns2 ";
                        }
                    }
                    $counterMul++;
                }
            }else{
                if ($clave == "SelFilter" and $valor == null) {
                    $queBet = " 1 ";
                }elseif($clave != "SelFilter"){
                    $difBool2 = explode("_", $clave);
                    if ($difBool2[0] != "opS") {
                        //verificar si es el ultimo elemento
                        if ($counterUS == count($_POST)-1) {
                            $flag = 1;
                        }else{
                            $flag = 0;
                        }

                        //agregar campos al select
                        $resVal =  preg_replace('/[0-9]+/', '', $clave);
                        $separaClave = explode("_", $resVal);
                        if($separaClave[2]){
                            if ($separaClave[3]) {
                                $campobd = $separaClave[1]."_".$separaClave[2]."_".$separaClave[3];
                            }else{
                                $campobd = $separaClave[1]."_".$separaClave[2];
                            }
                        }else{
                            $campobd = $separaClave[1];
                        }

                        //buscar a que nivel pertenece en la estructura
                        for ($i=0; $i < count($arrayGroup); $i++) { 
                            if($arrayGroup[$i] == $campobd){
                                $nivel = "pollcontent->'GROUP'->>'";
                                break;
                            }else{
                            }
                        }
                        for ($j=0; $j < count($arrayHouse); $j++) {
                            if($arrayHouse[$j] == $campobd){
                                $nivel = "pollcontent->'HOUSE'->>'";
                                break;
                            }else{
                            }
                        }
                        if ($nivel == "") {
                            $nivel = "pns1->>'";
                        }
                        //verifica si el campo se repite en la consulta
                        $aux = $campobd;
                        //echo ": $aux2 ***";
                        if($aux == $aux2){
                            $flagColAux = 0;
                            $flagCol = 0;
                        }else{
                            //verifica si es el primer campo
                            if($counterCols == 0){
                                $flagCol = 1;
                                $flagColAux = 0;
                            }else{
                                $flagCol = 0;
                                $flagColAux = 1;
                            }
                            $aux2 = $aux;
                        }
                        //echo $flagCol;

                        $colSel = strpos($strQue, $campobd);
                        if ($colSel) {
                             
                        }else{
                            $nuevasCols .= ", ".strtolower($campobd)." text";
                            //$column = strtoupper($column);
                            $column = strtoupper($campobd);
                            $column = str_replace("_", " ", $column);
                            $strQue.= ", ".$nivel.$campobd."' AS ".$column;
                        }

                        //agregar filtros al where
                        $opeWhere = Report::operacionWhere($clave, $valor, $flag, $flagCol, $flagColAux, $nivel);
                        $queBet .= $opeWhere;
                    }
                } 
            }
            $difBool = explode("_", $clave);
            if ($difBool[0] != "opS") {
                if ($clave != "SelFilter") {
                    $counterCols++;
                }
            }
            $counterUS++;
        } 
    }
    $from;
    $queBet;
    $strQue;
    $nuevasCols;
    //die();
    
    $proyecto = (isset($_POST['project'])) ? $_POST['project'] : null;
    $encuesta = (isset($_POST['poll'])) ? $_POST['poll'] : null;

    $datos = Report::consultaReporteador($proyecto, $encuesta, $strQue, $queBet, $nuevasCols, $from);
    //print_r($datos); 
    $titles = Report::titulosConsultaReporteador($proyecto, $encuesta, $strQue, $queBet, $nuevasCols, $from);

    //die();

    if (count($datos) > 0) {
        // Se agrega la libreria 
        require_once '../../../core/lib/PHP_XLSXWriter-master/xlsxwriter.class.php';

        // Se asignan las propiedades del libro
        $filename = "Reporte.xlsx";
        $campos = array_fill_keys($titles, 'String');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        ob_end_clean();
        header('Content-Disposition: attachment;filename="' . $filename);
        header('Cache-Control: max-age=0');
        $writer = new XLSXWriter();
        $writer->setAuthor('Desarrollo Miido');
        $writer->writeSheet($datos,'Hoja1',$campos);
        setcookie("desIni","1",time()+3600);
        $writer->writeToStdOut();     
    } else {
        //si no existen datos enviamos una alerta y regresamos a la página de reportes excel
        ?>
        <script language="JavaScript" type="text/javascript">
            alert("No hay resultados para mostrar");
            location.href='reporteador.php';
        </script>
        <?php
    }

} else {
    //include 'vistas/reporteador.php';
}

?>