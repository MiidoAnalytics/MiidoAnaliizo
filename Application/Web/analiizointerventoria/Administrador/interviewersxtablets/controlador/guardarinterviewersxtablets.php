<?php
define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {

    require_once '../modelo/classinterviewersxtablets.php';

    $interviewer_id = (isset($_REQUEST['interviewer_id'])) ? $_REQUEST['interviewer_id'] : null;
    $tablet_id = (isset($_REQUEST['tablet_id'])) ? $_REQUEST['tablet_id'] : null;
    if ($interviewer_id && $tablet_id) {
        $interviewersxtablets = Interviewersxtablets::buscarPorId($interviewer_id);
    } else {
        $interviewersxtablets = new Interviewersxtablets();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $intIdInterviewers = (isset($_POST['intIdInterviewers'])) ? $_POST['intIdInterviewers'] : null;
        $intIdTablet = (isset($_POST['intIdTablet'])) ? $_POST['intIdTablet'] : null;

        $interviewersxtablets->setIdInterviewer($intIdInterviewers);
        $interviewersxtablets->setIdTablet($intIdTablet);
        $resultado = $interviewersxtablets->searchUserWithTablet($intIdInterviewers);
        //print_r($resultado); die();
        if ($resultado <> false) {
            ?>
            <script language="javascript" type="text/javascript">

                Yes = confirm("¡El usuario ya cuenta con una tablet Asignada! ¿Desea asignarle otra ahora?");
                if (Yes) {
                    location.href = "http://ec2-52-27-125-67.us-west-2.compute.amazonaws.com/analiizopostgres/Administrador/interviewersxtablets/controlador/assignothertablet.php?interviewer_id=<?php echo $intIdInterviewers ?>&tablet_id=<?php echo $intIdTablet ?>";
                } else {
                    window.location = self.location;
                }
            </script>
            <?php
        } else {
            $interviewersxtablets->insert();
            header('Location: ../controlador/interviewersxtablets.php');
        }
    } else {
        include '../vista/guardarinterviewersxtablets.php';
    }
} else {
   require_once '../../../sitemedia/html/pageerror.php';
}
?>