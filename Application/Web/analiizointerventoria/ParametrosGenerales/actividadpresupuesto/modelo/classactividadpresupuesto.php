<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class ActividadPresupuesto {

    private $intIdActividad;
    private $strNombreActividad;
    private $intCampoEstructura;
    private $proyecto;
    private $encuesta;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;
    private $cantidadcontractual;
    private $valorunitario;
    private $dtprogramentrega;

    const TABLA = 'Actividad';

    public function __construct($intIdActividad = null, $strNombreActividad = null, $intCampoEstructura = null, $proyecto = null, $encuesta = null,  
            $cantidadcontractual = null, $valorunitario = null, $dtprogramentrega = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {

        $this->intIdActividad = $intIdActividad;
        $this->strNombreActividad = $strNombreActividad;
        $this->intCampoEstructura = $intCampoEstructura;
        $this->proyecto = $proyecto;
        $this->encuesta = $encuesta;
        $this->cantidadcontractual = $cantidadcontractual;
        $this->valorunitario = $valorunitario;
        $this->dtprogramentrega = $dtprogramentrega;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
        
    }

    function getIntIdActividad() {
        return $this->intIdActividad;
    }

    function getStrNombreActividad() {
        return $this->strNombreActividad;
    }

    function getintCampoEstructura() {
        return $this->intCampoEstructura;
    }

    function getStrRegisteredUser() {
        return $this->strRegisteredUser;
    }

    function getIstatusId() {
        return $this->IstatusId;
    }

    function getDtCreateDate() {
        return $this->dtCreateDate;
    }

    function getDtModifiedDate() {
        return $this->dtModifiedDate;
    }

    function getCantidadContractual() {
        return $this->cantidadcontractual;
    }

    function getValorUnitario() {
        return $this->valorunitario;
    }

    function getFechaPrograTermi() {
        return $this->dtprogramentrega;
    }

    function setIntIdActividad($intIdActividad) {
        $this->intIdActividad = $intIdActividad;
    }

    function setStrNombreActividad($strNombreActividad) {
        $this->strNombreActividad = $strNombreActividad;
    }

    function setintCampoEstructura($intCampoEstructura) {
        $this->intCampoEstructura = $intCampoEstructura;
    }

    function setStrRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    function setIstatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    function setDtCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    function setDtModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    function getIdEncuesta() {
        return $this->encuesta;
    }

    function getIdProyecto() {
        return $this->proyecto;
    }

    function setIdEncuesta($encuesta){
        $this->encuesta = $encuesta;
    }

    function setIdProyecto($proyecto){
        $this->proyecto = $proyecto;
    }

    function setCantidadContractual($cantidadcontractual){
        $this->cantidadcontractual = $cantidadcontractual;
    }

    function setValorUnitario($valorunitario){
        $this->valorunitario = $valorunitario;
    }

    function setFechaPrograTermi($dtprogramentrega){
        $this->dtprogramentrega = $dtprogramentrega;
    }

    public function guardar() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        if ($this->intIdActividad) /* Modifica */ {
            try {
                $strRegisteredUser = $_SESSION['userid'];
                $dtModifiedDate = date("Ymd");
                //echo $this->dtprogramentrega;

                $consulta = $conexion->prepare('select * from analiizo_interventoria.UpdateActividadProyectosp(?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdActividad, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->proyecto, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->encuesta, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->cantidadcontractual, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->valorunitario, PDO::PARAM_STR);
                $consulta->bindParam(6, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(8, $this->dtprogramentrega, PDO::PARAM_STR);
                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                $strRegisteredUser = $_SESSION['userid'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $cantidadcontractual = null;
                $valorunitario = 0;
                //echo $act = $this->strNombreActividad;

                $consulta = $conexion->prepare('select * from analiizo_interventoria.InsertActividadProyectosp(?,?,?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNombreActividad, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->intCampoEstructura, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->proyecto, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->encuesta, PDO::PARAM_STR);
                $consulta->bindParam(5, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(6, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(8, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(9, $cantidadcontractual, PDO::PARAM_STR);
                $consulta->bindParam(10, $valorunitario, PDO::PARAM_STR);
                
                $consulta->execute();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    //buscar actividad por id
    public static function buscarPorIdActividad($intIdActividad, $pollstr_id, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.buscaractividadporidsp(?,?,?);');
        $consulta->bindParam(1, $intIdActividad, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(3, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($intIdActividad, $registro['strnombreactividad'], $registro['intcampoestructura'], $registro['proyecto'], $registro['encuesta'], 
                        $registro['cantidadcontractual'], $registro['valorunitario'], $registro['dtprogramentrega']);
        } else {
            return false;
        }
    }

    public static function recuperarTodos($pollstr_id, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.preguntasEncuesta(?,?);');
        $consulta->bindParam(1, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //funcion que trae los proyectos asociados al usuario que ingreso a la aplicación
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

    //funcion que trae la estructura que contiene las actividades del proyecto
    public static function ObtenerEstructuraProyectoId($proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getestruActividadesxidproyectosp(?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //buscar si la actividad existe en la base de datos
    public static function buscarcampobd($idactstr, $pollstr_id, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.buscarcampobdsp(?,?,?);');
        $consulta->bindParam(1, $idactstr, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(3, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //Obtener todas las actividades activas que corresponden al proyecto seleccionado
    public static function obtenerAllActividadesbd($pollstr_id, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.buscartodasActividadesbdsp(?,?);');
        $consulta->bindParam(1, $pollstr_id, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
