<?php
/**
  * @author Edinson Ordoñez Date: 20160330
  * 
  */
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
    
    $totalEje = 0;
    $proTotal = 0;
    $total = 0;

    $costIndi = 12728148;

    if ($poll_id) {

        $respuestasTodasEncuesta = InformeCampo::ObtenerRespuestasEncuesta($project_id, $pollstr_id);
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

        $actividades = InformeCampo::recuperarTodasActividades($project_id);
        $preguntas = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id);
        $cantidadesAct = [];
        $actinombres = [];
        foreach ($actividades as $key) {
            $idfieldestr = $key['intcampoestructura'];
            $idActi = $key['intidactividad'];
            $CantEjeAct = $key['cantejecutada'];
            $cantidadcontractual = $key['cantidadcontractual'];
            $descripcionAct = $key['strdescripcion'];

            $cansubir = 0;
            /*recuperar actividades de la encuesta*/
            foreach ($preguntas as $item) {
                $idpreencuesta = $item['id'];
                $pregunta = $item['descripcion'];
                if($descripcionAct == $pregunta){
                    $preEncuestaName = $item['pregunta'];
                    $obtieneCant = InformeCampo::obtenervalorporcampo($preEncuestaName, $project_id, $pollstr_id);
                    $cantidadEje = $obtieneCant[0]['recuperarresprueba'];
                    $cantidadEje = str_replace('"','',$cantidadEje); 

                    if ($cantidadEje != $CantEjeAct ) {
                        $cansubir = $CantEjeAct + $cantidadEje;
                        InformeCampo::actualizaCantidadActEjecutada($idActi, $project_id, $cansubir);
                        if ($cansubir > $cantidadcontractual) {
                            array_push($cantidadesAct, $cansubir);
                            array_push($actinombres, $descripcionAct);
                        }
                    }else{
                    }
                    break;
                }
            }

            //recupera las actividades no programadas
            //se crea array con actividades no programadas y sus valores
            $arraynopro = array();
            if ($resActNoP == 'SI') {
                for ($i=0; $i < count($actNoName); $i++) { 
                    $nomActEnc = $actNoName[$i];
                    $canActEnc = $canActNop[$i];
                    if ($descripcionAct == $nomActEnc) {

                        if ($canActEnc != $CantEjeAct ) {
                            
                            $cansubir = $CantEjeAct + $canActEnc;
                            InformeCampo::actualizaCantidadActEjecutada($idActi, $project_id, $cansubir);
                            if ($cansubir > $cantidadcontractual) {
                                //echo "3entro - : - ";
                                array_push($cantidadesAct, $cansubir);
                                array_push($actinombres, $descripcionAct);
                            }
                        }else{
                            if ($CantEjeAct > $cantidadcontractual) {
                                array_push($cantidadesAct, $CantEjeAct);
                                array_push($actinombres, $descripcionAct);
                            }
                        }
                    }
                    $arraynopro[$actNoName[$i]] = $canActNop[$i];
                }
            }
        }

        //obtener info informe semanal
        $documeninfo = InformeCampo::documentinfobypollid($poll_id);
        foreach ($documeninfo as $key) {
            if($key['campo'] == 'Finished'){
                $tmp = str_replace('"', '', $key['valor']);
                $fechaCreacion2 = strtotime($tmp); 
                $fechaCreacion = date('Y-m-d',$fechaCreacion2);
            }
        }
        $descripcion = strtolower($descripcion);
        $descripcion = ucwords($descripcion);
        $semana = InformeCampo::buscarSemana($descripcion);
        //print_r($semana); die();
        $numsem = $semana[0]['nombre'];
        $periodo = intval(preg_replace('/[^0-9]+/', '', $numsem), 10);
        $perini = date_create($semana[0]['dtfechainicio']);
        $perfin = date_create($semana[0]['dtfechafin']);

        $proyecto = InformeCampo::searchprojectById($project_id);
        $proyectonombre = $proyecto[0]['nombre'];
        $fechainietacon = date_create($proyecto[0]['dtfechainicon']);
        $supervisor = $proyecto[0]['supervisor'];
        $codMun = $proyecto[0]['codmunicipio'];
        $DeparMuninombres = InformeCampo::buscarMunicipioPorCod($codMun);
        $localizacion = $DeparMuninombres[0]['strdepartamentname']." - ".$DeparMuninombres[0]['strtownname']." - ".$proyecto[0]['barrio'];
        $numcontinter = $proyecto[0]['numconint']; 
        $numcontobra = $proyecto[0]['numcontobra']; 
        $plazoini = $proyecto[0]['plazoini'];
        $fechaini = date_create($proyecto[0]['fechaini']);
        $fechasus = $proyecto[0]['fechasus'];
        if ($fechasus) {
            date_create($fechasus);
        }else{
            $fechasus = 0;
        }
        
        $fechareini = $proyecto[0]['fechareini'];
        if ($fechasus) {
            date_create($fechareini);
        }else{
            $fechareini = 0;
        }
        $fechafin = date_create($proyecto[0]['fechafin']);

        $segundos = strtotime($semana[0]['dtfechafin']) - strtotime($proyecto[0]['dtfechainicon']);
        $plazotranscurrido = intval($segundos/60/60/24);
        $equivale = ($plazotranscurrido / ($plazoini * 30)) * 100;

        $valorInint = $proyecto[0]['valiniint']; 
        $valorIniObr = $proyecto[0]['valiniobra'];
        $vadadiint = $proyecto[0]['vadadiint'];
        $valadobra = $proyecto[0]['valadobra'];
        $valActInt = $valorInint + $vadadiint;
        $valActObra = $valorIniObr + $valadobra;

        $nomInt = $proyecto[0]['nombreint'];
        $actividades = InformeCampo::recuperarTodasActividades($project_id);
        //print_r($actividades); die();
        $porceTotal = 0;
        $semanaid = $semana[0]['intidsemana'];
        $ValorTotalActPro = 0;
        $CostoTotalActiPorgramadas = 0;
        $int = 2;
        $porsemsub = 0;

        //no programadas
        while($pollstr_id2 <= $pollstr_id){
            $respuestasTodasEncuesta = InformeCampo::ObtenerRespuestasEncuesta($project_id, $pollstr_id2);
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
            $pollstr_id2++;
        }
        $subValorEjeNo2 = 0;
        //Actividades programadas
        foreach ($actividades as $key) {
            $idact = $key['intidactividad'];
            $descripcionAct = $key['strdescripcion'];
            while ($semanaid >= 2) {
                $semanasPorPro = InformeCampo::recuperarPorProgramadoporSemana($semanaid, $idact);
                $porsemsub = $semanasPorPro[0]['porsempro'];
                $porceTotal = $porceTotal + $porsemsub;
                if (count($semanasPorPro) > 0) {
                    $ValorTotalActPro = ($key['cantidadcontractual']*$key['valorunitario']);
                } 
                $semanaid--;
            }
            $pollstr_id2 = 3;
            //actividades no programadas
            if ($resActNoP == 'SI') {
                for ($i=0; $i < count($actNoName); $i++) { 
                    $nomActEnc = $actNoName[$i];
                    $canActEnc = $canActNop[$i];
                    if ($descripcionAct == $nomActEnc) {
                        $cantidadEje = $canActEnc; 
                        $ValorEje = $key['valorunitario'];
                        if ($cantidadEje > 0) {
                            $subValorEjeNo += ($cantidadEje * $ValorEje);
                        }else{
                        }
                    }
                }
            }
            $subValorEjeNo2 = $subValorEjeNo + $costIndi;
        }
        $ValorTotalActPro += $costIndi;
        $valorTotSemEje = $ValorTotalActPro + $subValorEjeNo2;

        $valorcostdir = 731315374;
        $valorProgramadoInd = ($valorcostdir * $porceTotal)/100;
        $porceTotalEje = ($valorTotSemEje * 100)/ $valorcostdir;
        $diferencia = $porceTotalEje - $porceTotal ;
        
        //die();

        $preguntasEstructura = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id); 
        foreach ($preguntasEstructura as $key) {
            if ($key['pregunta'] == 'observacionesparticulares60') {
                $observParti = InformeCampo::recuperarObservacionesparticulares($key['pregunta'], $project_id, $pollstr_id, $poll_id);
                break;
            }    
        }

        foreach ($preguntasEstructura as $key) {
            if ($key['pregunta'] == 'actividadesnoprogramadas74') {
                $actividadNoProgramadas = InformeCampo::recuperarActividadesNoProgramadas($key['pregunta'], $project_id, $pollstr_id, $poll_id);
                break;
            }    
        } 

        $observaciones = InformeCampo::obtenerObservacionesEncuesta($poll_id);
        $planaccion = $observaciones[0]['strplanaccion'];
        $responsable = $observaciones[0]['strresponsable'];
        $fechaPla = $observaciones[0]['dtfechaprogramada'];
        $comentarios = $observaciones[0]['strobsinterventor'];

        //Obtiene las imagenes de la encuesta
        $fotos = InformeCampo::obtenerFotosporEncuesta($poll_id);
             
    } else {
       
    }
    //die();
    require_once '../vista/descargarinforme.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}

?>