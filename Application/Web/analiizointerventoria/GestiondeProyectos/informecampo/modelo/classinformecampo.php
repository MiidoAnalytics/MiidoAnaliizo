<?php

 /**
  * @author Edinson Ordoñez Date: 20160329
  * 
  */


if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class InformeCampo {
    private $idobser;
    private $planaccion;
    private $comentarios;
    private $responsable;
    private $fechaProgramada;
    private $encuesta;
    private $proyecto;

    const TABLA = 'informescampo';

    public function __construct($idobser = null, $planaccion = null, $responsable = null, $fechaProgramada = null, $comentarios = null, $encuesta = null, $proyecto = null) {
        $this->idobser = $idobser;
        $this->planaccion = $planaccion;
        $this->responsable = $responsable;
        $this->fechaProgramada = $fechaProgramada;
        $this->comentarios = $comentarios;
        $this->encuesta = $encuesta;
        $this->proyecto = $proyecto;
    }

    public function getIdEncuesta() {
        return $this->encuesta;
    }

    public function getIdProyecto() {
        return $this->proyecto;
    }

    public function getIdObservacion() {
        return $this->idobser;
    }

    public function getResponsable() {
        return $this->responsable;
    }

    public function getFechaProgramada() {
        return $this->fechaProgramada;
    }

    public function getPlanAccion() {
        return $this->planaccion;
    }

    public function getComentarios() {
        return $this->comentarios;
    }

    public function setIdEncuesta($encuesta){
        $this->encuesta = $encuesta;
    }

    public function setIdProyecto($proyecto){
        $this->proyecto = $proyecto;
    }

    public function setIdObservacion($idobser){
        $this->idobser = $idobser;
    }

    public function setResponsable($responsable){
        $this->responsable = $responsable;
    }

    public function setFechaProgramada($fechaProgramada){
        $this->fechaProgramada = $fechaProgramada;
    }

    public function setPlanAccion($planaccion){
        $this->planaccion = $planaccion;
    }

    public function setComentarios($comentarios){
        $this->comentarios = $comentarios;
    }


    /**
    * @method ObtenerProyectosUsuerId($idusuario) funcion que trae los proyectos asociados al usuario que ingreso a la aplicación
    */
    public static function ObtenerProyectosUsuerId($idusuario) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedProyectosUsuerId(?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarInformes($encuesta, $proyecto)  funcion que trae los informes generados de cada formato 
    */
    public static function recuperarInformes($encuesta, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedInformes(?,?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }


    /**
    * @method recuperarInformes($encuesta, $proyecto) funcion que trae los datos del interventor para cada informe
    */
    public static function ObtenerInterventorporInforme($strInterventor) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spselectedinterviewerxid(?);');
        $consulta->bindParam(1, $strInterventor, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarInformes($encuesta, $proyecto) Recupera la información de las preguntas
    */
    public static function recuperarPreguntasEncuesta($pollstr_id, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.preguntasEncuesta2(?,?);');
        $consulta->bindParam(1, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenervalorporcampo($campo, $proyecto, $encuesta) Recupera la respuesta de las preguntas
    */
    public static function obtenervalorporcampo($campo, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarResPrueba(?,?,?);');
        $consulta->bindParam(1, $campo, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        //$consulta->bindParam(4, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarTodasActividades($proyecto) Recupera todas las actividades por proyecto y por estrucutura de la encuesta de campo
    */
    public static function recuperarTodasActividades($proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.buscartodasActividadesbdsp2(?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        // $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarTodasActividades($proyecto) Recupera el subtotal por cada actividad para el porcentaje programado
    */
    public static function recuperarSubtotalporactividad($idAct, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarSubtotalporactividadsp(?,?);');
        $consulta->bindParam(1, $idAct, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        //$consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarPorProgramadoporSemana($nomSema, $intidact) Recupera el porcentaje semanal programadao por actividad
    * @return array .
    */
    public static function recuperarPorProgramadoporSemana($nomSema, $intidact) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarPorceSemaProporacti(?,?);');
        $consulta->bindParam(1, $nomSema, PDO::PARAM_INT);
        $consulta->bindParam(2, $intidact, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method searchprojectById($intidproyecto) Recupera el proyecto segun el id
    * @return array .
    */
    public static function searchprojectById($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedproyectosxId(?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method buscarMunicipioPorCod($codMunicipio) Recupera el Municipio por el codigo de Municipio
    * @return array .
    */
    public static function buscarMunicipioPorCod($codMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarUbicacionProyecto(?);');
        $consulta->bindParam(1, $codMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method buscarSemana($dessemana) Recupera semana por nombre de la semana
    * @return array .
    */
    public static function buscarSemana($dessemana) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarSemanaporNombre(?);');
        $consulta->bindParam(1, $dessemana, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method estadoporactiviadad($encuesta, $intidproyecto) Recupera estado para cada actividad
    * @return String  .
    */
    public function estadoporactividad($encuesta, $intidproyecto, $desfieldestr, $cantidadContractual, $idAct){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.preguntasEncuesta2(?,?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_INT);
        $consulta->bindParam(2, $intidproyecto, PDO::PARAM_INT);
        $consulta->execute();
        $preguntas = $consulta->fetchAll();
        $conexion = null;
        $counter = 0;
        foreach ($preguntas as $key) {
            $despreencuesta = $key['descripcion'];
            if($despreencuesta == $desfieldestr){
                $preEncuestaName = $key['pregunta'];
                $obtieneCant = InformeCampo::obtenervalorporcampo($preEncuestaName, $intidproyecto, $encuesta);
                $cantidadEje = $obtieneCant[0]['recuperarresprueba'];
                $cantidadEje += str_replace('"','',$cantidadEje); 

                if($cantidadEje < $cantidadContractual){
                    if ($cantidadEje > 0) {
                        return $estado = 'Iniciado';
                    }
                }elseif ($cantidadEje >= $cantidadContractual) {

                    return $estado = 'Terminado';
                }else{
                    return $estado = 'No iniciado';
                }
                break;
            }
            $counter++;
        }
        if($counter == count($preguntas)){
            return $estado = "No Iniciado";
        }
    }

     /**
    * @method documentinfobypollid($encuesta) Recupera la informacion de ubicación, nombre, etc por id de la estructura
    * @return array .
    */
    public static function documentinfobypollid($encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getalldocumentinfo(?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method recuperarObservacionesparticulares($campo, $proyecto, $encuesta) Recupera la respuesta de las preguntas
    */
    public static function recuperarObservacionesparticulares($campo, $proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarresObservaciones(?,?,?,?);');
        $consulta->bindParam(1, $campo, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(4, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

     /**
    * @method recuperarActividadesNoProgramadas($campo, $proyecto, $encuesta) Recupera las activiadades No programadas
    */
    public static function recuperarActividadesNoProgramadas($campo, $proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarresActividadNoProgramada(?,?,?,?);');
        $consulta->bindParam(1, $campo, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(4, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenervalorporcampo2($campo, $proyecto, $encuesta) Recupera la respuesta de las preguntas
    */
    public static function obtenervalorporcampo2($campo, $proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarAct1(?,?,?,?);');
        $consulta->bindParam(1, $campo, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(4, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

     /**
    * @method recuperarSubtotalporactividad2($proyecto) Recupera el subtotal por cada actividad para el porcentaje programado
    */
    public static function recuperarSubtotalporactividad2($idAct, $proyecto, $pollstr_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarSubtotalporactividadsp2(?,?,?);');
        $consulta->bindParam(1, $idAct, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenerPersonalObra($proyecto, $pollstr_id, $encuesta) Recupera la respuesta del personal de obra
    */
    public static function obtenerPersonalObra($proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarpersonalzona(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenerObservacionesEncuesta($encuesta)  Recupera las observaciones segun la encuesta (semana)
    */
    public static function obtenerObservacionesEncuesta($encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getobservacionbypoll(?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenerPersonalObra($proyecto, $pollstr_id, $encuesta) No devuelve datos
    */
    public function guardarObservacion() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
         
        if ($this->idobser) // Modifica  
        {
            try
            {            
                $consulta = $conexion->prepare('select * from analiizo_interventoria.updateObservacionManual(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->idobser, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->planaccion, PDO::PARAM_STR);            
                $consulta->bindParam(3, $this->comentarios, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->encuesta, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->responsable, PDO::PARAM_STR);
                $consulta->bindParam(6, $this->fechaProgramada, PDO::PARAM_STR);
                
                $consulta->execute();
            } 
            catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        } 
        else // Inserta
        {
            try 
            {
                $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertObservacion(?,?,?,?,?);');          
                $consulta->bindParam(1, $this->planaccion, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->comentarios, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->encuesta, PDO::PARAM_INT);  
                $consulta->bindParam(4, $this->responsable, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->fechaProgramada, PDO::PARAM_STR);              
                $consulta->execute();

            } catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    /**
    * @method obtenerPersonalInterventoria($proyecto, $pollstr_id, $encuesta) Recupera la respuesta del personal de obra
    */
    public static function obtenerPersonalInterventoria($proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarpersonalinterventoria(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenerSisomaPgio($proyecto, $pollstr_id, $encuesta) Recupera la respuesta del sisoma y pgio
    */
    public static function obtenerSisomaPgio($proyecto, $pollstr_id, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarsisomapgio(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

     /**
    * @method ObtenerRespuestasEncuesta($proyecto, $pollstr_id) Recupera la respuestas de la encuesta
    */
    public static function ObtenerRespuestasEncuesta($proyecto, $pollstr_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperartodasrespuestapoll(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method actualizaCantidadActEjecutada($proyecto, $pollstr_id) Recupera la respuestas de la encuesta
    */
    public static function actualizaCantidadActEjecutada($idAct, $proyecto, $cantidadEje) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.actualizarcantidadejecutadaxactividad(?,?,?);');
        $consulta->bindParam(1, $idAct, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $cantidadEje, PDO::PARAM_STR);
        $consulta->execute();

        $conexion = null;
    }

    /**
    * @method obtenerFotosporEncuesta($encuesta) Recupera las fotos de la encuesta
    */
    public static function obtenerFotosporEncuesta($encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.obtenerimagenesporencuesta(?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /**
    * @method obtenerEstrcuturaPorProyecto($proyecto) Recupera las estructuras por proyecto
    */
    public static function obtenerEstrcuturaPorProyecto($proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getestructurebyproject(?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
