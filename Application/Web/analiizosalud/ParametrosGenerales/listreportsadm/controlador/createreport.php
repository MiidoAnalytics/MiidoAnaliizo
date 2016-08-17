<?php
    
    define('CONTROLADOR', TRUE);
    
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    
    require_once '../modelo/classreports.php';
    
    $reporte_id = (isset($_REQUEST['reporte_id'])) ? $_REQUEST['reporte_id'] : null;
    
    if($reporte_id){        
        $report = Report::searchById($reporte_id);
        //echo count($report);

        /*foreach ($report as $key) {
            echo $key;
        }*/
    }else{
        $report = new Report();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {   
        $strReportName = (isset($_POST['strReportName'])) ? $_POST['strReportName'] : null;
        $strDescription = (isset($_POST['strDescription'])) ? $_POST['strDescription'] : null;
        $strQuery = (isset($_POST['strQuery'])) ? $_POST['strQuery'] : null;
        
        $report->setReportName($strReportName);
        $report->setDescription($strDescription);
        $report->setQuery($strQuery);
        
        $report->insert();
        
        header('Location: ../controlador/listreportsadm.php');
    }else
    {        
       include '../vista/createreport.php';
    } 
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}     
?>