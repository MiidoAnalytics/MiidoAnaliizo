<?php
    
    define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    
    require_once '../modelo/classinterviewer.php';
    
    $interviewer_id = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;
    
    if($interviewer_id){        
        $interviewer = Interviewer::buscarPorId($interviewer_id);
    }else{
        $interviewer = new Interviewer();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {      
        $strNames = (isset($_POST['strNames'])) ? $_POST['strNames'] : null;
        $strSurnames = (isset($_POST['strSurnames'])) ? $_POST['strSurnames'] : null;
        $strUsername = (isset($_POST['strUsername'])) ? $_POST['strUsername'] : null;
        $strHashPassword = (isset($_POST['strHashPassword'])) ? $_POST['strHashPassword'] : null;
        $strHashPassword = md5($strHashPassword);
        $interviewer->setNames($strNames);
        $interviewer->setSurnames($strSurnames);
        $interviewer->setUsername($strUsername);
        $interviewer->setHashPassword($strHashPassword);
        $interviewer->guardar();
        header('Location: ../controlador/interviewer.php');
    }else
    {     
        include '../vista/guardarinterviewer.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>