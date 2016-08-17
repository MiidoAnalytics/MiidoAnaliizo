<?php
    
define('CONTROLADOR', TRUE);
    
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    
    require_once '../modelo/classlocationinterviewers.php';
    
    $locationinterviewer_id = (isset($_REQUEST['locationinterviewer_id'])) ? $_REQUEST['locationinterviewer_id'] : null;
   // print_r($locationinterviewer_id); die();
    if($locationinterviewer_id){        
        $locationInterviewer = LocationInterviewers::buscarPorId($locationinterviewer_id);
    }else{
        $locationInterviewer = new LocationInterviewers();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {    
        //print_r($_POST); die();  
        $strCodDepartament = (isset($_POST['strCodDepartament'])) ? $_POST['strCodDepartament'] : null;
        $strCodTown = (isset($_POST['strCodTown'])) ? $_POST['strCodTown'] : null;
        $intIdInterviewer = (isset($_POST['locationinterviewer_id'])) ? $_POST['locationinterviewer_id'] : null;
        //print_r($_POST); die(); 
        //$locationInterviewer->setCodDepartament($strCodDepartament);
        //$locationInterviewer->setCodTown($strCodTown);
        //$locationInterviewer->setIdInterviewer($intIdInterviewers);
        //die('eee');

        $resultado = LocationInterviewers::searchUserWithLocation($intIdInterviewer);
        //print_r($resultado); die();
        // die('otro');
        if($resultado <> 0)

        { //die('otro');
            ?>
            <script language="javascript" type="text/javascript">
               
                Yes = confirm ("¡El usuario ya cuenta con una Sitio Asignado! ¿Desea asignarle otro ahora?");
                if(Yes){
                  location.href = "http://52.27.125.67/analiizopostgres/Administrador/locationinterviewers/controlador/assignotherlocation.php?interviewer_id=<?php echo $intIdInterviewer ?>&town_id=<?php echo $strCodTown ?>&departament_id=<?php echo $strCodDepartament ?>";
                }else{
                    //location.href = 'http://localhost/analiizopostgres/locationinterviewers/controlador/locationinterviewers.php';
                    window.location = self.location;
                }
            </script>
        <?php
        }else{
            LocationInterviewers::insert($intIdInterviewer, $strCodTown, $strCodDepartament);
        }        
    }else
    {     
        include '../vista/guardarlocationinterviewers.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>