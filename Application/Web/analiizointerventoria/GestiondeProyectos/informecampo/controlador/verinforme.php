<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classinformecampo.php';
    
    $informecampo = new InformeCampo();

    $poll_id = (isset($_REQUEST['poll_id'])) ? $_REQUEST['poll_id'] : null;
    $project_id = (isset($_REQUEST['project_id'])) ? $_REQUEST['project_id'] : null;
    $pollstr_id = (isset($_REQUEST['pollstr_id'])) ? $_REQUEST['pollstr_id'] : null;
    $descripcion = (isset($_REQUEST['descripcion'])) ? $_REQUEST['descripcion'] : null;

    $descripcion = strtolower($descripcion);
    $descripcion = ucwords($descripcion);

    $proTotal = 0;
    $total = 0;

    $subtotales = 0;
    $arraySubt = [];
    $valorEtaCons = 731315374;
    $porceTotal = 0;

    $costIndi = 12728148;

    //Recorrer cada informe
    $semanpornombre = InformeCampo::buscarSemana($descripcion);
    $semana = $semanpornombre[0]['intidsemana'];

    $arrayFechaIni= [];
    $arrayValorEje = [];
    
    $preguntas = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id);
    $actividades = InformeCampo::recuperarTodasActividades($project_id);

    //valor total presupuesto programado
    foreach ($actividades as $keyAct) {
        $idAct = $keyAct['intidactividad'];
        $idfieldestr = $keyAct['intcampoestructura'];
        $totAct = InformeCampo::recuperarSubtotalporactividad($idAct, $project_id);
        $subtotales = $totAct[0]['recuperarsubtotalporactividadsp'];
        $total = $total + $subtotales;
        array_push($arraySubt, $subtotales);
    }
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
    }

    //personal de obra
    $counter = 0;
    $countermaestro = 0;
    $counteroficial = 0;
    $counterayudante = 0;
    $counterinspector = 0;
    $countertecnico = 0;
    $counterespecialista = 0;
    $flag = 0;
    $tipoPersonal = [];
    $personalOPbra = InformeCampo::obtenerPersonalObra($project_id, $pollstr_id, $poll_id);
    foreach ($personalOPbra as $obj) {
        $jsonobj = $obj['recuperarpersonalzona']; 
        $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($jsonobj, TRUE)),
                RecursiveIteratorIterator::SELF_FIRST);       
        foreach ($jsonIterator as $key => $val) {
            $name = preg_replace('/[0-9]+/', '', $key);
            switch ($name) {
                case 'maestro':
                    $flag = 1;
                    array_push($tipoPersonal, $name);
                    break;
                case 'oficial':
                    $flag = 2;
                    array_push($tipoPersonal, $name);
                    break;
                case 'ayudante':
                    $flag = 3;
                    array_push($tipoPersonal, $name);
                    break;
                case 'inspector':
                    $flag = 4;
                    array_push($tipoPersonal, $name);
                    break;
                case 'tecnico':
                    $flag = 5;
                    array_push($tipoPersonal, $name);
                    break;
                case 'especialista':
                    $flag = 6;
                    array_push($tipoPersonal, $name);
                    break;
            }

            if ($name == 'dezona') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                   case 1:
                       $countermaestro += $value; 
                       break;
                   case 2:
                       $counteroficial += $value; 
                       break;
                    case 3:
                       $counterayudante += $value; 
                       break;
                    case 4:
                       $counterinspector += $value; 
                       break;
                    case 5:
                       $countertecnico += $value; 
                       break;
                    case 6:
                       $counterespecialista += $value; 
                       break;
                }
            }elseif ($name == 'fueradezona') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                   case 1:
                       $countermaestro += $value; 
                       break;
                   case 2:
                       $counteroficial += $value; 
                       break;
                    case 3:
                       $counterayudante += $value; 
                       break;
                    case 4:
                       $counterinspector += $value; 
                       break;
                    case 5:
                       $countertecnico += $value; 
                       break;
                    case 6:
                       $counterespecialista += $value; 
                       break;
                }
            }elseif ($name == 'vulnerable') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                   case 1:
                       $countermaestro += $value; 
                       break;
                   case 2:
                       $counteroficial += $value; 
                       break;
                    case 3:
                       $counterayudante += $value; 
                       break;
                    case 4:
                       $counterinspector += $value; 
                       break;
                    case 5:
                       $countertecnico += $value; 
                       break;
                    case 6:
                       $counterespecialista += $value; 
                       break;
                }
            }
            break;
        }
    }
    $totalPersonal = [$countermaestro, $counteroficial, $counterayudante, $counterinspector, $countertecnico, $counterespecialista];
    //Crea un json con los datos del gráfico
    for ($i=0; $i < count($tipoPersonal); $i++) { 
        //echo $tipoPersonal[$i]." : ".$totalPersonal[$i];
        $DataPersonalObra[] = json_decode(
           "{".
           "\"personal\":\"".$tipoPersonal[$i]."\",".
           "\"total\":".$totalPersonal[$i].
           "}");
    }

    //Obtener datos personal de interventoría
    $counter = 0;
    $counterinspector = 0;
    $countertecnico = 0;
    $counterespecialista = 0;
    $flag = 0;
    $tipoPersonal = [];
    $personalInterventoria = InformeCampo::obtenerPersonalInterventoria($project_id, $pollstr_id, $poll_id);
    foreach ($personalInterventoria as $obj) {
        $jsonobj = $obj['recuperarpersonalinterventoria']; 
        $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator(json_decode($jsonobj, TRUE)),
                RecursiveIteratorIterator::SELF_FIRST);       
        foreach ($jsonIterator as $key => $val) {
            $name = preg_replace('/[0-9]+/', '', $key);
            switch ($name) {
                case 'inspector':
                    $flag = 1;
                    array_push($tipoPersonal, $name);
                    break;
                case 'tecnico':
                    $flag = 2;
                    array_push($tipoPersonal, $name);
                    break;
                case 'especialista':
                    $flag = 3;
                    array_push($tipoPersonal, $name);
                    break;
            }

            if ($name == 'dezona') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                    case 1:
                       $counterinspector += $value; 
                       break;
                    case 2:
                       $countertecnico += $value; 
                       break;
                    case 3:
                       $counterespecialista += $value; 
                       break;
                }
            }elseif ($name == 'fueradezona') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                    case 1:
                       $counterinspector += $value; 
                       break;
                    case 2:
                       $countertecnico += $value; 
                       break;
                    case 3:
                       $counterespecialista += $value; 
                       break;
                }
            }elseif ($name == 'vulnerable') {
                $value = $val[0];
                $counter += $value;
                switch ($flag) {
                    case 1:
                       $counterinspector += $value; 
                       break;
                    case 2:
                       $countertecnico += $value; 
                       break;
                    case 3:
                       $counterespecialista += $value; 
                       break;
                }
            }
            break;
        }
    }
    $totalPersonal = [$counterinspector, $countertecnico, $counterespecialista];
    //Crea un json con los datos del gráfico
    for ($i=0; $i < count($tipoPersonal); $i++) { 
        //echo $tipoPersonal[$i]." : ".$totalPersonal[$i];
        $DataPersonalInterventoria[] = json_decode(
           "{".
           "\"personal\":\"".$tipoPersonal[$i]."\",".
           "\"total\":".$totalPersonal[$i].
           "}");
    }
    require_once '../vista/verinforme.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}

?>