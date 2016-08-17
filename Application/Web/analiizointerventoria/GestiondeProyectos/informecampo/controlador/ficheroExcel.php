<?php

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

define('CONTROLADOR', TRUE);
require_once '../modelo/classinformecampo.php';

$pollid = $_POST['pollid'];
$project_id = $_POST['projectid'];
$pollstr_id = $_POST['pollstrid'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descriptionpoll'];
$subtitulo = "GERENCIA DE PROYECTOS";

$costIndi = 12728148;

$informecampo = new InformeCampo();
$totalEje = 0;
$proTotal = 0;
$total = 0;
if ($pollid) {

//recupera la estructura de la encuesta recibida
$respuestasTodasEncuesta = InformeCampo::ObtenerRespuestasEncuesta($project_id, $pollstr_id);
//Reccore el json con los datos
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

//Recorre las actividades
$actividades = InformeCampo::recuperarTodasActividades($project_id);
$preguntas = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id);
$diferencia = 0;
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
                    //echo "1entro - : - ";
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
                        //echo $CantEjeAct;
                        array_push($cantidadesAct, $CantEjeAct);
                        array_push($actinombres, $descripcionAct);
                    }
                }
            }
            $arraynopro[$actNoName[$i]] = $canActNop[$i];
        }
    }

}

    //obtener informacion del document info
    $documeninfo = InformeCampo::documentinfobypollid($pollid);
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
    $perini = date_format($perini, 'Y-m-d');
    $perfin = date_create($semana[0]['dtfechafin']);
    $perfin = date_format($perfin, 'Y-m-d');

    $proyecto = InformeCampo::searchprojectById($project_id);
    $proyectonombre = $proyecto[0]['nombre'];
    $fechainietacon = date_create($proyecto[0]['dtfechainicon']);
    $fechainietacon = date_format($fechainietacon, 'Y-M-d');
    $supervisor = $proyecto[0]['supervisor'];
    $codMun = $proyecto[0]['codmunicipio'];
    $DeparMuninombres = InformeCampo::buscarMunicipioPorCod($codMun);
    $localizacion = $DeparMuninombres[0]['strdepartamentname']." - ".$DeparMuninombres[0]['strtownname']." - ".$proyecto[0]['barrio'];
    $numcontinter = $proyecto[0]['numconint']; 
    $numcontobra = $proyecto[0]['numcontobra']; 
    $plazoini = $proyecto[0]['plazoini'];
    $fechaini = date_create($proyecto[0]['fechaini']);
    $fechaini = date_format($fechaini, 'Y-M-d');
    $fechasus = $proyecto[0]['fechasus'];
    if ($fechasus) {
        date_create($fechasus);
        $fechasus = date_format($fechasus, 'Y-M-d');
    }else{
        $fechasus = 0;
    }
    
    $fechareini = $proyecto[0]['fechareini'];
    if ($fechareini) {
        date_create($fechareini);
        $fechareini = date_format($fechasus, 'Y-M-d');
    }else{
        $fechareini = 0;
    }

    $fechafin = date_create($proyecto[0]['fechafin']);
    $fechafin = date_format($fechafin, 'Y-M-d');

    $segundos = strtotime($semana[0]['dtfechafin']) - strtotime($proyecto[0]['dtfechainicon']);
    $plazotranscurrido = intval($segundos/60/60/24);
    $equivale = ($plazotranscurrido / ($plazoini * 30)) * 100;
    $equivale = round($equivale, 2);

    $valorInint = $proyecto[0]['valiniint']; 
    $valorIniObr = $proyecto[0]['valiniobra'];
    $vadadiint = $proyecto[0]['vadadiint'];
    $valadobra = $proyecto[0]['valadobra'];
    $valActInt = $valorInint + $vadadiint;
    $valActObra = $valorIniObr + $valadobra;

    $nomInt = $proyecto[0]['nombreint'];
    $actividades = InformeCampo::recuperarTodasActividades($project_id);
    $valorTotSemEje = 0;
    $porceTotal = 0;
    $semanaid = $semana[0]['intidsemana'];
    $ValorTotalActPro = 0;
    $CostoTotalActiPorgramadas = 0;
    foreach ($actividades as $key) {
        $idact = $key['intidactividad'];
        /*$semanasPorPro = InformeCampo::recuperarPorProgramadoporSemana($semanaid, $idact);
        $porsemsub = $semanasPorPro[0]['porsempro'];
        $porceTotal = $porceTotal + $porsemsub;
        if (count($semanasPorPro) > 0) {
            $ValorTotalActPro += $key['cantidadcontractual']*$key['valorunitario'];
        }*/
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
    /*$valorcostdir = 553121309;
    $valorProgramadoInd = ($valorcostdir * $porceTotal)/100;
    $porceTotalEje = ($ValorTotalActPro * 100)/ $valorcostdir;
    $diferencia = $porceTotalEje - $porceTotal ;*/

    $preguntasEstructura = InformeCampo::recuperarPreguntasEncuesta($pollstr_id, $project_id); 
    foreach ($preguntasEstructura as $key) {
        if ($key['pregunta'] == 'observacionesparticulares60') {
            $observParti = InformeCampo::recuperarObservacionesparticulares($key['pregunta'], $project_id, $pollstr_id, $pollid);
            break;
        }    
    }

    foreach ($preguntasEstructura as $key) {
        if ($key['pregunta'] == 'actividadesnoprogramadas74') {
            $actividadNoProgramadas = InformeCampo::recuperarActividadesNoProgramadas($key['pregunta'], $project_id, $pollstr_id, $pollid);
            break;
        }    
    } 

    $observaciones = InformeCampo::obtenerObservacionesEncuesta($pollid);
    $planaccion = $observaciones[0]['strplanaccion'];
    $comentarios = $observaciones[0]['strobsinterventor'];
    $responsable = $observaciones[0]['strresponsable'];
    $fechaproplan = $observaciones[0]['dtfechaprogramada'];

    //Obtiene las imagenes de la encuesta
    $fotos = InformeCampo::obtenerFotosporEncuesta($pollid);
}

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../../../core/lib/PHPExcel/Classes/PHPExcel.php';
include '../../../core/lib/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';


$objPHPExcel = new PHPExcel();
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$titulo.'.xls');
header('Cache-Control: max-age=0');

//Estilos de las filas
$estiloTitulo = array(
    'font' => array(
        'name'      => 'Arial',
        'bold'      => true,
        'italic'    => false,
        'strike'    => false,
        'size' =>16,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array(
            'rgb' => 'FFFFFF')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$estiloSubitulo = array(
    'font' => array(
        'name'      => 'Arial',
        'bold'      => true,
        'italic'    => false,
        'strike'    => false,
        'size' =>11,
        'color'     => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array(
            'rgb' => 'FFFFFF')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(5.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(7);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(22.5);
$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(29.25);
$objPHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(29.25);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:E4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:Y3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z2:AG3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z4:AG4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F4:Y4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B5:AG6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B7:D7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E7:T7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('U7:V7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y7:AB7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD7:AG7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B8:AG8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B9:F9');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G9:AG9');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B10:F10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G10:Q10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('R10:AG10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B11:R11');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('S11:AG11');

for ($i=12; $i < 26; $i++) { 
    if ($i == 19) {
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$i.':F'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$i.':L'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M'.$i.':O'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$i.':R'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('S'.$i.':V'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$i.':AA'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AB'.$i.':AD'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE'.$i.':AG'.$i);
    }else{
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$i.':F'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$i.':R'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('S'.$i.':V'.$i);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$i.':AG'.$i);
    }
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B26:AG26');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B27:AG27');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B28:C29');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D28:U29');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V28:X29');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y28:AA29');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AB28:AD29');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE28:AG29');

$rowcounter = 30;


if (count($actividades) > 0):
    $i = 1;
    foreach ($actividades as $item):

        //Mezclar las celdas para colocar la info
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':C'.$rowcounter);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$rowcounter.':U'.$rowcounter);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('V'.$rowcounter.':X'.$rowcounter);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y'.$rowcounter.':AA'.$rowcounter);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AB'.$rowcounter.':AD'.$rowcounter);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE'.$rowcounter.':AG'.$rowcounter);

        //Buscar la información y estado
        $idfieldestr = $item['intcampoestructura'];
        $idActi = $item['intidactividad'];
        $desfieldestr = $item['strdescripcion'];
        $estado = '';
        foreach ($preguntas as $key) {
            $idpreencuesta = $key['id'];
            $despoll = $key['descripcion'];
            if($desfieldestr == $despoll){
                $fechaentregaact = date_create($item['dtprogramentrega']);
                $fechaentregaact = date_format($fechaentregaact, 'Y-m-d, D');
                $cantidadContractual = $item['cantidadcontractual'];
                $CantEjeAct = $item['cantejecutada'];
                $estado = '';
                if($CantEjeAct < $cantidadContractual){
                    if ($CantEjeAct > 0) {
                        $estado = 'Iniciado';
                    }elseif ($CantEjeAct == 0) {
                        $estado = 'No iniciado';
                    }
                }elseif ($CantEjeAct >= $cantidadContractual) {
                    $estado = 'Terminado';
                }else{
                    $estado = 'No iniciado';
                }
             break;
            }else{
                $estado = 'No iniciado';
            }
            if (count($arraynopro) > 0) {
                for ($i=0; $i < count($actNoName); $i++) { 
                    $nomActEnc = $actNoName[$i];
                    $canActEnc = $canActNop[$i];
                    if ($desfieldestr == $nomActEnc) {
                        if($CantEjeAct < $canActEnc){
                            if ($CantEjeAct > 0) {
                                $estado = 'Iniciado';
                            }elseif ($CantEjeAct == 0) {
                                $estado = 'No iniciado';
                            }
                        }elseif ($CantEjeAct >= $canActEnc) {
                            $estado = 'Terminado';
                        }else{
                            $estado = 'No iniciado';
                        }
                    }
                }
            }
        }
        //insercion de datos al excel de las actividades
       /* echo $i;
        echo $desfieldestr
        echo $fechaentregaact; */
        $diasAtra = 0;
        $perfin2 = '';
        if ($estado == 'Terminado') {
            $perfin2 = $perfin;
        }
        if ($perfin < $fechaentregaact) {
                    
        }else{$diasAtra = 0;}
        //echo $estado;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,$i)
                ->setCellValue('D'.$rowcounter,$desfieldestr)
                ->setCellValue('V'.$rowcounter,$fechaentregaact)
                ->setCellValue('Y'.$rowcounter,$perfin2)
                ->setCellValue('AB'.$rowcounter,$diasAtra)
                ->setCellValue('AE'.$rowcounter,$estado)
                ;
        
        $rowcounter++;
        $i++;
    endforeach;
else:
    ///echo "No hay Actividades para mostrar";
endif;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$rowcounter++;

//Mezcla de celdas para los indicadores
$objPHPExcel->getActiveSheet()->getRowDimension($rowcounter)->setRowHeight(40);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':C'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$rowcounter.':U'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V'.$rowcounter.':X'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y'.$rowcounter.':AC'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,"No.")
                ->setCellValue('D'.$rowcounter,"INDICADORES")
                ->setCellValue('V'.$rowcounter,"VALORES")
                ->setCellValue('Y'.$rowcounter,"% SOBRE EL VALOR TOTAL")
                ->setCellValue('AD'.$rowcounter,"DIFERENCIA")
                ;
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->getAlignment()->setWrapText(true);                

$rowcounter++;
$objPHPExcel->getActiveSheet()->getRowDimension($rowcounter)->setRowHeight(28);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':C'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$rowcounter.':U'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V'.$rowcounter.':X'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y'.$rowcounter.':AC'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD'.$rowcounter.':AG'.$rowcounter);

//$diferencia = $porceTotal - $porceTotalEje;
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,"1")
                ->setCellValue('D'.$rowcounter,"Valor acumulado de las actividades o productos conforme a la programación vigente.")
                ->setCellValue('V'.$rowcounter, $valorProgramadoInd)
                ->setCellValue('Y'.$rowcounter, $porceTotal)
                ->setCellValue('AD'.$rowcounter, $diferencia)
                ;
$rowcounter++;
$objPHPExcel->getActiveSheet()->getRowDimension($rowcounter)->setRowHeight(28);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':C'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$rowcounter.':U'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V'.$rowcounter.':X'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y'.$rowcounter.':AC'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD'.$rowcounter.':AG'.$rowcounter);

$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,"2")
                ->setCellValue('D'.$rowcounter,"Valor acumulado de las actividades o productos ejecutados y aprobados por la Interventoría.")
                ->setCellValue('V'.$rowcounter, $valorTotSemEje)
                ->setCellValue('Y'.$rowcounter, $porceTotalEje)
                ;
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$rowcounter++;

//identificación de situaciones problemáticas
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,"3. IDENTIFICACIÓN DE SITUACIONES PROBLEMÁTICAS - ANÁLISIS DE CAUSAS")
                ;
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->applyFromArray($estiloSubitulo);

$i = 1;
foreach ($observParti as $itemObser):
    $rowcounter++;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,$i." :  ".$itemObser['recuperarresobservaciones'])
                ;
    $i++;
endforeach;
$rowcounter++;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);

//plan de acción
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter,"4. PLAN DE ACCIÓN RESULTADO DEL ANÁLISIS DE CAUSAS REGISTRADO EN EL NUMERAL ANTERIOR - CON EL OBJETIVO DE ELIMINAR LA CAUSA RAÍZ DE LA SITUACIÓN PROBLEMÁTICA")
                ;
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->applyFromArray($estiloSubitulo);

$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':V'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$rowcounter.':AC'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$rowcounter,"ACTIVIDAD")
                ->setCellValue('W'.$rowcounter,"RESPONSABLE")
                ->setCellValue('AD'.$rowcounter,"FECHA PROGRAMADA")
                ;
$rowcounter++;
$rowaum = $rowcounter + 4;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':V'.$rowaum);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$rowcounter.':AC'.$rowaum);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AD'.$rowcounter.':AG'.$rowaum);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'.$rowcounter, $planaccion)
            ->setCellValue('W'.$rowcounter, $responsable)
            ->setCellValue('AD'.$rowcounter, $fechaproplan);

$rowcounter = $rowaum;
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);

//actividades no previstas
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter,"5. ACTIVIDADES NO PREVISTAS Y MAYORES CANTIDADES");
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->applyFromArray($estiloSubitulo);

$rowcounter++;
$j = 1;
for ($i=0; $i < count($actinombres); $i++) { 
    $objPHPExcel->getActiveSheet()->getRowDimension($rowcounter)->setRowHeight(50);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter, $j.". El contratista realiza mayores cantidades en: ".$actinombres[$i]. ". Total realizado:".$cantidadesAct[$i]);

    $rowcounter++;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);

//comentarios
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter,"6. COMENTARIOS DEL INTERVENTOR: ");
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->applyFromArray($estiloSubitulo);
$rowcounter++;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter, $comentarios);
$aux = $rowcounter + 3;
//$objPHPExcel->getActiveSheet()->getRowDimension($aux)->setRowHeight(30);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$aux);
$rowcounter = $aux;
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);

//registro fotografico
$rowcounter++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':AG'.$rowcounter);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowcounter,"7. REGISTRO FOTOGRAFICO DEL AVANCE DURANTE LA SEMANA ");
$objPHPExcel->getActiveSheet()->getStyle('B'.$rowcounter.':AG'.$rowcounter)->applyFromArray($estiloSubitulo);

$rowcounter++;
$aux2 = $rowcounter + 15;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$rowcounter.':K'.$aux2);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$rowcounter.':V'.$aux2);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$rowcounter.':AG'.$aux2);
$aux3 = 0;
$aux3 = $aux2 + 1;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$aux3.':K'.$aux3);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$aux3.':V'.$aux3);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$aux3.':AG'.$aux3);

$arrayCoord = ['B'.$rowcounter, 'L'.$rowcounter, 'W'.$rowcounter];
$arrayCoordDes = ['B'.$aux3, 'L'.$aux3, 'W'.$aux3];

$counter = 0;
foreach ($fotos as $key ) {
    $nameFoto = "../../../".$key['strname'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arrayCoordDes[$counter], $key['strdescription']);
    $objDrawing = new PHPExcel_Worksheet_Drawing();

    $objDrawing->setName($key['strdescription']);
    $objDrawing->setDescription($key['strdescription']);
    $pathimage = $nameFoto;
    $objDrawing->setPath($pathimage); 
    $objDrawing->setCoordinates($arrayCoord[$counter]);
    $objDrawing->setWidth(400);
    $objDrawing->setHeight(300);
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

    $counter++;
    if(($counter % 3) == 0){
        $aux = $aux2+3;
        $aux2 = $aux + 15;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$aux.':K'.$aux2);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$aux.':V'.$aux2);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$aux.':AG'.$aux2);
        $aux3 = 0;
        $aux3 = $aux2 + 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$aux3.':K'.$aux3);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$aux3.':V'.$aux3);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W'.$aux3.':AG'.$aux3);
        array_push($arrayCoord, 'B'.$aux);
        array_push($arrayCoord, 'L'.$aux);
        array_push($arrayCoord, 'W'.$aux);
        array_push($arrayCoordDes, 'B'.$aux3);
        array_push($arrayCoordDes, 'L'.$aux3);
        array_push($arrayCoordDes, 'W'.$aux3);

        /*$objDrawing = new PHPExcel_Worksheet_Drawing();

        $objDrawing->setName($key['strdescription']);
        $objDrawing->setDescription($key['strdescription']);
        $pathimage = $nameFoto;
        $objDrawing->setPath($pathimage); 
        $objDrawing->setCoordinates($arrayCoord[$counter]);
        $objDrawing->setWidth(400);
        $objDrawing->setHeight(300);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        unset($objDrawing);*/ 
    }
    unset($objDrawing); 
}



//insercion de datos al excel
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F2',$titulo)
        ->setCellValue('F4',$subtitulo)
        ->setCellValue('B7',"FECHA")
        ->setCellValue('E7',$fechaCreacion)
        ->setCellValue('U7',"PERIODO No.")
        ->setCellValue('W7',$periodo)
        ->setCellValue('X7',"DEL")
        ->setCellValue('Y7', $perini)
        ->setCellValue('AC7',"AL")
        ->setCellValue('AD7', $perfin)
        ->setCellValue('B8',"1. INFORMACIÓN GENERAL")
        ->setCellValue('B9',"OBJETO DEL CONTRATO")
        ->setCellValue('G9', $proyectonombre)
        ->setCellValue('B10',"LOCALIZACIÓN DEL PROYECTO")
        ->setCellValue('G10', $localizacion)
        ->setCellValue('R10', "ETAPA DE CONSTRUCCIÓN - Fecha de Inicio : ".$fechainietacon)
        ->setCellValue('B11', "CONTRATO DE INTERVENTORÍA")
        ->setCellValue('S11', "CONTRATO DE OBRA")
        ->setCellValue('B12', "CONTRATO No.: ")
        ->setCellValue('G12', $numcontinter)
        ->setCellValue('S12', "CONTRATO No.: ")
        ->setCellValue('W12', $numcontinter)
        ->setCellValue('B13', "PLAZO INICIAL: ")
        ->setCellValue('G13', $plazoini." Meses")
        ->setCellValue('S13', "PLAZO INICIAL: ")
        ->setCellValue('W13', $plazoini." Meses")
        ->setCellValue('B14', "FECHA DE INICIACIÓN: ")
        ->setCellValue('G14', $fechaini)
        ->setCellValue('S14', "FECHA DE INICIACIÓN: ")
        ->setCellValue('W14', $fechaini)
        ->setCellValue('B15', "**FECHA DE SUSPENSIÓN: ")
        ->setCellValue('G15', $fechasus)
        ->setCellValue('S15', "**FECHA DE SUSPENSIÓN: ")
        ->setCellValue('W15', $fechasus)
        ->setCellValue('B16', "**FECHA DE REINICIACIÓN: ")
        ->setCellValue('G16', $fechareini)
        ->setCellValue('S16', "**FECHA DE REINICIACIÓN: ")
        ->setCellValue('W16', $fechareini)
        ->setCellValue('B17', "FECHA DE TERMINACIÓN: ")
        ->setCellValue('G17', $fechafin)
        ->setCellValue('S17', "FECHA DE TERMINACIÓN: ")
        ->setCellValue('W17', $fechafin)
        ->setCellValue('B18', "PLAZO ACTUALIZADO: ")
        ->setCellValue('G18', $plazoini." Meses")
        ->setCellValue('S18', "PLAZO ACTUALIZADO: ")
        ->setCellValue('W18', $plazoini." Meses")
        ->setCellValue('B19', "PLAZO TRANSCURRIDO: ")
        ->setCellValue('G19', $plazotranscurrido." dias")
        ->setCellValue('S19', "PLAZO TRANSCURRIDO: ")
        ->setCellValue('W19', $plazotranscurrido." dias")
        ->setCellValue('M19', "EQUIVALE AL: ")
        ->setCellValue('P19', $equivale." %")
        ->setCellValue('AB19', "EQUIVALE AL: ")
        ->setCellValue('AE19', $equivale." %")
        ->setCellValue('B20', "VALOR INICIAL: ")
        ->setCellValue('G20', $valorInint)
        ->setCellValue('S20', "VALOR INICIA: ")
        ->setCellValue('W20', $valorIniObr)
        ->setCellValue('B21', "VALOR ADICION(ES): ")
        ->setCellValue('G21', $vadadiint)
        ->setCellValue('S21', "VALOR ADICION(ES): ")
        ->setCellValue('W21', $valadobra)
        ->setCellValue('B22', "VALOR ACTUALIZADO: ")
        ->setCellValue('G22', $valActInt)
        ->setCellValue('S22', "VALOR ACTUALIZADO: ")
        ->setCellValue('W22', $valActObra)
        ->setCellValue('B23', "VALOR PAGADO: ")
        ->setCellValue('G23', "0")
        ->setCellValue('S23', "VALOR PAGADO: ")
        ->setCellValue('W23', "0")
        ->setCellValue('B24', "VALOR POR PAGAR: ")
        ->setCellValue('G24', $vadadiint)
        ->setCellValue('S24', "VALOR POR PAGAR: ")
        ->setCellValue('W24', $valActObra)
        ->setCellValue('B25', "SUPERVISOR DEL PROYECTO: ")
        ->setCellValue('G25', $supervisor)
        ->setCellValue('S25', "INTERVENTOR : ")
        ->setCellValue('W25', $nomInt)
        ->setCellValue('B27', "2. CONTROL DE HITOS")
        ->setCellValue('B28', "No.")
        ->setCellValue('D28', "DESCRIPCIÓN DEL HITO")
        ->setCellValue('V28', "FECHA PROGRAMADA")
        ->setCellValue('Y28', "FECHA REAL DE CUMPLIMIENTO")
        ->setCellValue('AB28', "DIAS DE RETRASO")
        ->setCellValue('AE28', "ESTADO")
        ;

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setName('Logo');
$objDrawing2->setDescription('Logo');
$logo = '../../../sitemedia/images/table_test.png';
$objDrawing2->setPath($logo); 
$objDrawing2->setCoordinates('C2');
$objDrawing2->setWidth(100);
$objDrawing2->setHeight(80); 
$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet()); 

$objPHPExcel->getActiveSheet()->getStyle('B2:AG6')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->getStyle('B8:AG8')->applyFromArray($estiloSubitulo);
$objPHPExcel->getActiveSheet()->getStyle('B27:AG27')->applyFromArray($estiloSubitulo);
$objPHPExcel->getActiveSheet()->getStyle('B28:AG29')->applyFromArray($estiloSubitulo);
$objPHPExcel->getActiveSheet()->getStyle('B28:AG29')->getAlignment()->setWrapText(true);


//$objPHPExcel->getActiveSheet()->getStyle('F4:Y4')->applyFromArray($estiloTituloReporte);
// Do your stuff here

$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// This line will force the file to download
$writer->save('php://output');
exit;





/*$titulo = $_POST['titulo'];

header("Content-type: application/vnd.ms-excel; charset=UTF-8'; name='excel'");
header("Content-Disposition: filename=".$titulo.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$datos= iconv("utf-8","iso-8859-1",$_POST['datos_a_enviar']);
echo $datos;
*/
?>