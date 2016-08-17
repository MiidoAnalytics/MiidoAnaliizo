<?php

    define('CONTROLADOR', TRUE);

    require_once '../../../core/classSession.php';

    $flag = Session::TiempoSession();
    if ($flag == 1) {

        require_once '../modelo/classetapaconstruccion.php';

        $semana_id = (isset($_REQUEST['semana_id'])) ? $_REQUEST['semana_id'] : null;

        if ($semana_id) {
            //$construccion = Construccion::searchWeekById($semana_id);
            $titulos = [];
            $preguntas = Construccion::recuperarActividades();
            //print_r($preguntas); die();
            foreach ($preguntas as $key) {
                $idPre = $key['id'];
                $tipo = $key['tipo'];
                if($idPre > 71 && $idPre < 152){
                    if ($tipo == 'rg') {
                        $tit = $key['descripcion'];
                        array_push($titulos, $tit);
                    }else{
                        
                    }
                }
            }
        }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
                $construccion = new Construccion();      
                $semana_id = (isset($_POST['idsemana'])) ? $_POST['idsemana'] : null;  
                for ($i=0; $i < count($_POST); $i++) { 
                    $llaveAct = 'intidactividad'.$i;
                    $llavePorce = 'porProSem'.$i;
                    $intidactividad = (isset($_POST[$llaveAct])) ? $_POST[$llaveAct] : null;
                    $porPro = (isset($_POST[$llavePorce])) ? $_POST[$llavePorce] : null;

                    $exisActSem = Construccion::validarActSemporid($semana_id, $intidactividad);
                    if ($exisActSem > 0 && $porPro == null) {

                    }else{
                        $construccion->setintidactividad($intidactividad);
                        $construccion->setporPrograAct($porPro);
                        $construccion->insertActSem($semana_id);
                    }
                }
                header('Location: ../controlador/etapaconstruccion.php');
            } else {
                //echo 'true'; die();
                //include '../vista/guardarsemana.php';
            }
        include '../vista/guardarsemana.php';
    } else {
       require_once '../../../sitemedia/html/pageerror.php';
    }
?>