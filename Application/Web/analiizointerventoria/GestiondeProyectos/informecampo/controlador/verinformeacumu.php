<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {

    require_once '../modelo/classinformecampo.php';
    
    $informecampo = new InformeCampo();

    $project_id = (isset($_REQUEST['project_id'])) ? $_REQUEST['project_id'] : null;

    $proTotal = 0;
    $total = 0;

    $informecampo->setIdEncuesta($pollstr_id);
    $informecampo->setIdProyecto($project_id);

    $porceTotal = 0;
    $actividades = InformeCampo::recuperarTodasActividades($project_id);
    $subtotales = 0;
    $arraySubt = [];
    $valorEtaCons = 731315374;

    //valor total presupuesto programado
    foreach ($actividades as $keyAct) {
        $idAct = $keyAct['intidactividad'];
        $idfieldestr = $keyAct['intcampoestructura'];
        $totAct = InformeCampo::recuperarSubtotalporactividad($idAct, $project_id);
        $subtotales = $totAct[0]['recuperarsubtotalporactividadsp'];
        $total = $total + $subtotales;
        array_push($arraySubt, $subtotales);
    }

    //Recorrer cada informe
    $semana = 2;
    $pgiocountersi = 0;
    $pgiocounterno = 0;
    $costIndi = 12728148;
    //busca las estrcuturas de encuesta por proyecto
    $estructurasbyproject = InformeCampo::obtenerEstrcuturaPorProyecto($project_id);
    
    foreach ($estructurasbyproject as $key) {
        $pollstr_id = $key["intidestructura"];
        //Actividades no programadas
        $respuestasTodasEncuesta = InformeCampo::ObtenerRespuestasEncuesta($project_id, $pollstr_id);
        if (count($respuestasTodasEncuesta) > 0) {
            foreach ($respuestasTodasEncuesta as $obj) {
                $jsonobj = $obj['recuperartodasrespuestapoll']; 
                $jsonIterator = new RecursiveIteratorIterator(
                        new RecursiveArrayIterator(json_decode($jsonobj, TRUE)),
                        RecursiveIteratorIterator::SELF_FIRST);       
                foreach ($jsonIterator as $key => $val) {
                    $arraypre = $val;
                    foreach ($arraypre as $key2 => $value) {
                        //busca las actividades no programadas
                        $pregunta = preg_replace('/[0-9]+/', '', $key2);
                        switch ($pregunta) {
                            case 'actividadesnoprogramadas':
                                $resActNoP = $value[0];
                                break;
                            case 'actividad':
                                $actNoName= $value;
                                break;
                            case 'cantidad':
                                $canActNop = $value;
                                break;
                        }
                    }
                }
            }

            //Actividades programadas
            $totalEje = 0;
            $arrayFechaIni= [];
            $arrayValorEje = [];
            $subValorEjeNo = 0;
            $subValorEjeNo2 = 0;
            $preguntas = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id);
            foreach ($actividades as $key) {
                $cansubir = 0;
                $idfieldestr = $key['intcampoestructura'];
                $cantidadcontractual = $key['cantidadcontractual'];
                $descripcionAct = $key['strdescripcion'];
                $CantEjeAct = $key['cantejecutada'];

                /*recuperar respuestas encuesta*/
                foreach ($preguntas as $item) {
                    $idpreencuesta = $item['id'];
                    if($idfieldestr == $idpreencuesta){
                        $preEncuestaName = $item['pregunta'];
                        $obtieneCant = InformeCampo::obtenervalorporcampo($preEncuestaName, $project_id, $pollstr_id);
                        $cantidadEje = $obtieneCant[0]['recuperarresprueba'];
                        $cantidadEje = str_replace('"','',$cantidadEje); 
                        $ValorEje = $key['valorunitario'];
                        if ($cantidadEje > 0) {
                            $subValorEje = ($cantidadEje * $ValorEje) + $costIndi;
                        }else{
                            //$subValorEje = ($cantidadEje * $ValorEje);
                        }
                        
                        $totalEje = $totalEje + $subValorEje;
                        array_push($arrayValorEje, $subValorEje);

                        //fechas
                        $idact = $key['intidactividad'];
                        $semanasPorEje = InformeCampo::recuperarPorProgramadoporSemana($semana, $idact);
                        if (count($semanasPorEje) > 0) {
                            $porsemsub = $semanasPorEje[0]['porsempro'];
                            $porceTotal = $porceTotal + $porsemsub;
                            if($semana == 2){
                                $fechaIniProyCons = $semanasPorEje[0]['dtfechainicio'];
                                array_push($arrayFechaIni, $fechaIniProyCons);
                                $fechaFinSem = date_create($semanasPorEje[0]['dtfechafin']);
                                //echo date_format($fechaFinSem, 'Y-m-d')." --- ";
                            }else{
                                $fechaFinSem = date_create($semanasPorEje[0]['dtfechafin']);
                                //echo date_format($fechaFinSem, 'Y-m-d')." *** ";
                            }
                        }
                        break;
                    }
                }

                //recupera las actividades no programadas
                //se crea array con actividades no programadas y sus valores
                if ($resActNoP == 'SI') {
                    for ($i=0; $i < count($actNoName); $i++) { 
                        $nomActEnc = $actNoName[$i];
                        $canActEnc = $canActNop[$i];
                        if ($descripcionAct == $nomActEnc) {
                            $cantidadEje = $canActEnc; 
                            $ValorEje = $key['valorunitario'];
                            if ($cantidadEje > 0) {
                                $subValorEjeNo += ($cantidadEje * $ValorEje);
                                //echo $subValorEjeNo." - ";
                            }else{
                                //$subValorEjeNo = ($cantidadEje * $ValorEje);
                            }
                        }
                    }
                    //$subValorEjeNo2 = $subValorEjeNo + $costIndi;
                }
                $subValorEjeNo2 = $subValorEjeNo + $costIndi;
                $valorTotSemEje = $subValorEje + $subValorEjeNo2;
            }

            //obtiene la proporcion de las actividades ejecutadas 
            $proporcion2 = 0;
            $proporcion2 = ($valorTotSemEje / $valorEtaCons) * 100;
            $proporcion = 0;
            $arraypro = [];
            for ($i=0; $i < count($arrayValorEje); $i++) { 
                $arrayValorEje[$i];
                $proporcion = ($arrayValorEje[$i] / $valorEtaCons) * 100;
                $proTotal = $proTotal + $proporcion;
                array_push($arraypro, $proporcion);
            }

            $feinise = date_create($arrayFechaIni[0]);
            $feinise2 = date_format($feinise, 'Y-m-d');

            $date = date_create($informeItem['fechacreacion']);

            $AcumuladoGrafico[] = json_decode(
                "{".
                "\"date\":\"".date_format($fechaFinSem, 'Y-m-d')."\",".
                "\"Programado\":\"".$porceTotal."\",".
                "\"Ejecutado\":".round($proporcion2,2).
                "}");
            $semana++;

            //Datos de sisoma y pgio
            $sisomapgio = InformeCampo::obtenerSisomaPgio($project_id, $pollstr_id, $pollstr_id);
            foreach ($sisomapgio as $obj) {
                $jsonobj = $obj['recuperarsisomapgio']; 
                $jsonIterator = new RecursiveIteratorIterator(
                        new RecursiveArrayIterator(json_decode($jsonobj, TRUE)),
                        RecursiveIteratorIterator::SELF_FIRST);       
                foreach ($jsonIterator as $key => $val) {
                    if ($val[0] == 'SI') {
                        $pgiocountersi++;
                    }else{
                        $pgiocounterno++;
                    }
                    break;
                }
            }
        }
    }
    
    //die();

    $category = "Evaluación";
    $full = 100;
    $PorcentajeSI = ($pgiocountersi * 100) / ($pgiocountersi + $pgiocounterno);

    $DataSisomaPgio[] = json_decode(
        "{".
        "\"category\":\"".$category."\",".
        "\"full\":\"".$full."\",".
        "\"bullet\":".round($PorcentajeSI,2).
        "}");

    $malo = 40;
    $bueno = 40;
    $excele = 20;
    $limite = 82;
    
    $DataSisomaPgio2[] = json_decode(
        "{".
        "\"category\":\"".$category."\",".
        "\"malo\":\"".$malo."\",".
        "\"bueno\":\"".$bueno."\",".
        "\"excelente\":\"".$excele."\",".
        "\"limit\":\"".$limite."\",".
        "\"full\":\"".$full."\",".
        "\"bullet\":".round($PorcentajeSI,2).
        "}");
    //echo $pgiocountersi;

    require_once '../vista/verinformeacumu.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}

?>