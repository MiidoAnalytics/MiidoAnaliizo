<?php
    
    define('CONTROLADOR', TRUE);

require_once '../../../core/classSession.php';

$flag = Session::TiempoSession();
if ($flag == 1) {
    require_once '../modelo/classencinterviewer.php';
    $encuestaInterviewer = new EncuestaInterviewer();
    $proyectos = EncuestaInterviewer::ObtenerProyectos();
    $encuestadores = EncuestaInterviewer::ObtenerEncuestadores();

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {      
        $proyecto = (isset($_POST['proyecto'])) ? $_POST['proyecto'] : null;
        $encuesta = (isset($_POST['encuesta'])) ? $_POST['encuesta'] : null;
        $encuestador = (isset($_POST['encuestador'])) ? $_POST['encuestador'] : null;

        $validar = EncuestaInterviewer::validarInterviewerEncuesta($encuestador, $encuesta);
        foreach ($validar as $item) {
            if($item['spselectedvalidarencuestainterviewer'] < 1){
                $encuestaInterviewer->guardarEncuesaInterviewer($encuesta, $encuestador);
                $conteo = EncuestaInterviewer::contarProyectoEncues($encuestador, $proyecto);
                foreach ($conteo as $key) {
                    //echo $key['spselectedcontarproyectoencuestador']; die();
                    if($key['spselectedcontarproyectoencuestador'] < 1){
                        $encuestaInterviewer->guardarProyectoInterviewer($proyecto, $encuestador);
                    }
                }
                header('Location: ../controlador/asignacionencuestas.php');
            }else{
                ?>
                <script type="text/javascript">
                    alert('Este encuestador ya se encuentra asignado a esta encuesta. Por favor valide la información.');
                    window.location = self.location;
                </script>
                <?php
            }
        }

    }else
    {     
        include '../vista/guardarinterencuesta.php';
    }

} else {
   require_once '../../../sitemedia/html/pageerror.php';
}    
?>