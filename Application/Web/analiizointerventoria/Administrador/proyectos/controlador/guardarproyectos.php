<?php

    define('CONTROLADOR', TRUE);

    require_once '../../../core/classSession.php';

    $flag = Session::TiempoSession();
    if ($flag == 1) {

        require_once '../modelo/classproyectos.php';

        $intidproyecto = (isset($_REQUEST['intidproyecto'])) ? $_REQUEST['intidproyecto'] : null;

        if ($intidproyecto) {
            $proyecto = Proyectos::searchById($intidproyecto);
            //print_r($proyecto); die();
        } else {
            $proyecto = new Proyectos();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            /*datos del proyecto*/
            $cateproject = (isset($_POST['cateproject'])) ? $_POST['cateproject'] : null;
            $nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : null;
            $supervisor = (isset($_POST['supervisor'])) ? $_POST['supervisor'] : null;
            $plazoIni = (isset($_POST['plazoIni'])) ? $_POST['plazoIni'] : null;

            $fechaIni = (isset($_POST['fechaIni'])) ? $_POST['fechaIni'] : null;
            if($fechaIni != null){
                $dateini = date_create($fechaIni);
                $dateini2 = date_format($dateini, 'Ymd');
            }//else { $dateini2 = ''}

            $fechaSus = (isset($_POST['fechaSus'])) ? $_POST['fechaSus'] : null;
            if($fechaSus != null){
                $dateSus = date_create($fechaSus);
                $dateSus2 = date_format($dateSus, 'Ymd');
            }//else {$dateSus2 = null};

            $fechaReini = (isset($_POST['fechaReini'])) ? $_POST['fechaReini'] : null;
            if($fechaReini != null){
                $dateRei = date_create($fechaReini);
                $dateRei2 = date_format($dateRei, 'Ymd');
            }//else {$dateRei2 = null};

            $fechaFin = (isset($_POST['fechaFin'])) ? $_POST['fechaFin'] : null;
            if($fechaFin != null){
                $dateEnd = date_create($fechaFin);
                $dateEnd2 = date_format($dateEnd, 'Ymd');
            }//else {$dateEnd2 = null};

            $strCodTown = (isset($_POST['strCodTown'])) ? $_POST['strCodTown'] : null;
        	$barrioVereda = (isset($_POST['barrioVereda'])) ? $_POST['barrioVereda'] : null;
            $intidproyecto = (isset($_POST['intidproyecto'])) ? $_POST['intidproyecto'] : null;

            /*datos del contrato de interventoria*/
            $numContInter = (isset($_POST['numContInter'])) ? $_POST['numContInter'] : null;
            $nominter = (isset($_POST['nominter'])) ? $_POST['nominter'] : null;
            $valorIniInter = (isset($_POST['valorIniInter'])) ? $_POST['valorIniInter'] : null;


            $valorAdiInter = (isset($_POST['valorAdiInter'])) ? $_POST['valorAdiInter'] : null;
            if(!$valorAdiInter){
                $valorAdiInter = 0;
            }
            /*datos del contrato de obra*/
            $numContratista = (isset($_POST['numContratista'])) ? $_POST['numContratista'] : null;
            $nomContratista = (isset($_POST['nomContratista'])) ? $_POST['nomContratista'] : null;
            $valorIniObra = (isset($_POST['valorIniObra'])) ? $_POST['valorIniObra'] : null;
            $valorAdiObra = (isset($_POST['valorAdiObra'])) ? $_POST['valorAdiObra'] : null;
            if(!$valorAdiObra){
                $valorAdiObra = 0;
            }

            $plazoEtaCon = (isset($_POST['plazoEtaCon'])) ? $_POST['plazoEtaCon'] : null;
            $plazoEtaPre = (isset($_POST['plazoEtaPre'])) ? $_POST['plazoEtaPre'] : null;

            $fechaIniCon = (isset($_POST['fechaIniCon'])) ? $_POST['fechaIniCon'] : null;
            if($fechaIniCon != null){
                $dateIniCon = date_create($fechaIniCon);
                $dateIniCon2 = date_format($dateIniCon, 'Ymd');
            }

            $proyecto->setintidproyecto($intidproyecto);
            $proyecto->setNombre($nombre);
            $proyecto->setCategoria($cateproject);
            $proyecto->setSupervisor($supervisor);
            $proyecto->setPlazoInicial($plazoIni);
            $proyecto->setFechaInicial($dateini2);
            $proyecto->setFechaSuspension($dateSus2);
            $proyecto->setFechaReinicio($dateRei2);
            $proyecto->setFechaFin($dateEnd2);
            $proyecto->setcodTown($strCodTown);
            $proyecto->setBarrioVereda($barrioVereda);
            $proyecto->setNumContraInter($numContInter);
            $proyecto->setInterName($nominter);
            $proyecto->setValorIniInter($valorIniInter);
            $proyecto->setValorAdiInter($valorAdiInter);
            $proyecto->setNumContraObra($numContratista);
            $proyecto->setObraName($nomContratista);
            $proyecto->setValorIniObra($valorIniObra);
            $proyecto->setValorAdiObra($valorAdiObra);
            $proyecto->SetPlazoEtaPre($plazoEtaPre);
            $proyecto->SetPlazoEtaCons($plazoEtaCon);
            $proyecto->SetaDtFechaIniCon($dateIniCon2);
            
            $proyecto->insert();
            header('Location: ../controlador/proyectos.php');
        } else {
            include '../vista/guardarproyectos.php';
        }
    } else {
       require_once '../../../sitemedia/html/pageerror.php';
    }
?>