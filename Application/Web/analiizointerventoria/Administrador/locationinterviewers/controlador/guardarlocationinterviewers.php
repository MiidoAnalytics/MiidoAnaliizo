<?php
    
define('CONTROLADOR', TRUE);
    
require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    
    require_once '../modelo/classlocationinterviewers.php';
    
    $locationinterviewer_id = (isset($_REQUEST['locationinterviewer_id'])) ? $_REQUEST['locationinterviewer_id'] : null;

    if($locationinterviewer_id){        
        $locationInterviewer = LocationInterviewers::buscarPorId($locationinterviewer_id);
    }else{
        $locationInterviewer = new LocationInterviewers();
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {    
        $strCodDepartament = (isset($_POST['strCodDepartament'])) ? $_POST['strCodDepartament'] : null;
        $strCodTown = (isset($_POST['strCodTown'])) ? $_POST['strCodTown'] : null;
        $intIdInterviewer = (isset($_POST['locationinterviewer_id'])) ? $_POST['locationinterviewer_id'] : null;
        $resultado = LocationInterviewers::searchUserWithLocation($intIdInterviewer);

        if($resultado <> 0)

        { 
            ?>
            <script language="javascript" type="text/javascript">
               
                Yes = confirm ("¡El usuario ya cuenta con una Sitio Asignado! ¿Desea asignarle otro ahora?");
                if(Yes){
                    //ocation='../../../../Administrador/locationinterviewers/controlador/assignotherlocation.php?interviewer_id=<?php echo $intIdInterviewer ?>&town_id=<?php echo $strCodTown ?>&departament_id=<?php echo $strCodDepartament ?>"';
                    location.href = "http://52.27.125.67/analiizointerventoria/Administrador/locationinterviewers/controlador/assignotherlocation.php?interviewer_id=<?php echo $intIdInterviewer ?>&town_id=<?php echo $strCodTown ?>&departament_id=<?php echo $strCodDepartament ?>";
                }else{
                    //location.href = 'http://localhost/analiizopostgres/locationinterviewers/controlador/locationinterviewers.php';
                    window.location = self.location;
                }
            </script>
        <?php
        }else{
            $locationInterviewer2 = new LocationInterviewers;
            $locationInterviewer2->insert($intIdInterviewer, $strCodTown, $strCodDepartament);
            include 'locationinterviewers.php';
        }    
            
    }else
    {   
        include '../vista/guardarlocationinterviewers.php';  
    }
    
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>