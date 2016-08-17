<?php

define('CONTROLADOR', TRUE);

include_once '../../../core/classSession.php';

$flag = Session::TiempoSession();

if ($flag == 1) {
    require_once '../modelo/classetapaconstruccion.php';
    $semanas = 0;
    $construccion = new Construccion();
    
    $proyecto = (isset($_REQUEST['proyecto'])) ? $_REQUEST['proyecto'] : null;
    $encuesta = (isset($_REQUEST['encuesta'])) ? $_REQUEST['encuesta'] : null;
    $namePoll = (isset($_REQUEST['namePoll'])) ? $_REQUEST['namePoll'] : null;
    $nameProj = (isset($_REQUEST['nameProj'])) ? $_REQUEST['nameProj'] : null;

    if ($proyecto) {
        $semanas = 0;

        $idusuario = $_SESSION['userid'];
        $proyectos = Construccion::ObtenerProyectosUsuerId($idusuario);

        $proyecto2 = Construccion::searchById($proyecto);
        $semanasProgra = 1;
        $semanasProgra = Construccion::obtenerTodoSemanasCons();

        $plazo = $proyecto2[0]['plazocons'];
        $semanas = ($plazo * 4) / 30;
        //echo count($semanasProgra);
        if(count($semanasProgra) != 0){
           
        }else{
            $i = 1;
            $semananame = '';
            while ($i <= $semanas) {
                //$dtinicon2 = null;
                //$fechaFin = null;
                //$semananame = '';
                if($i == 1){
                    $dtinicon = $proyecto2[0]['dtfechainicon'];
                    $phpdate = strtotime( $dtinicon );
                    $dtinicon2 = date( 'Ymd', $phpdate );

                    $fecha = new DateTime($dtinicon2);
                    $fecha->add(new DateInterval('P5D'));
                    $fechaFin = $fecha->format('Ymd') . "\n";  
                    $semananame = 'Semana '.$i;
                }else{
                    $fecha2 = new DateTime($dtinicon2);
                    $fecha2->add(new DateInterval('P7D'));
                    $fechainisem2 = $fecha2->format('Ymd') . "\n";  

                    $fecha3 = new DateTime($fechainisem2);
                    $fecha3->add(new DateInterval('P5D'));
                    $fechaFin = $fecha3->format('Ymd') . "\n";
                    $semananame = 'Semana '.$i;

                    $dtinicon2 = $fechainisem2;
                }
                //echo $semananame." - ".$dtinicon2." - ".$fechaFin. "\n";
                $construccion->setSemana($semananame);
                $construccion->setfechaInicio($dtinicon2);
                $construccion->setfechaFin($fechaFin);
                $construccion->insertSemanas();
                $i++;
            }
        }
    } else {
        $idusuario = $_SESSION['userid'];
        $proyectos = Construccion::ObtenerProyectosUsuerId($idusuario);
    }    
    require_once '../vista/etapaconstruccion.php';
} else {
    require_once '../../../sitemedia/html/pageerror.php';
}
?>