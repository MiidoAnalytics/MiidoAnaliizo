<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Construccion {

    private $intidactividad;
    private $proyecto;
    private $semana;
    private $porPrograAct;
    private $fechaInicio;
    private $fechaFin;
    private $intidregistereduser;
    private $intistatus;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'etapaconstruccion';

    public function __construct($semana = null, $fechaInicio = null, $fechaFin = null,
        $intidregistereduser = null, $intistatus = null, $dtCreateDate = null, $dtModifiedDate = null, $intidactividad = null, $proyecto = null, $porPrograAct = null) {

        $this->intidactividad = $intidactividad;
        $this->proyecto = $proyecto;
        $this->semana = $semana;
        $this->porPrograAct = $porPrograAct;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->intidregistereduser = $intidregistereduser;
        $this->intistatus = $intistatus;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getintidactividad() {
        return $this->intidactividad;
    }

    public function getIdProyecto() {
        return $this->proyecto;
    }

    public function getSemana() {
        return $this->semana;
    }

    public function getporPrograAct() {
        return $this->porPrograAct;
    }

    public function getfechaInicio() {
        return $this->fechaInicio;
    }

    public function getfechaFin() {
        return $this->fechaFin;
    }

    public function getRegisteredUser() {
        return $this->intidregistereduser;
    }

    public function getintistatus() {
        return $this->intistatus;
    }

    public function getCreateDate() {
        return $this->dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->dtModifiedDate;
    }

    public function setintidactividad($intidactividad){
        $this->intidactividad = $intidactividad;
    }

    public function setIdProyecto($proyecto){
        $this->proyecto = $proyecto;
    }

    public function setSemana($semana){
        $this->semana = $semana;
    }

    public function setporPrograAct($porPrograAct){
        $this->porPrograAct = $porPrograAct;
    }

    public function setfechaInicio($fechaInicio){
        $this->fechaInicio = $fechaInicio;
    }

    public function setfechaFin($fechaFin){
        $this->fechaFin = $fechaFin;
    }

    public function setRegisteredUser($intidregistereduser) {
        $this->intidregistereduser = $intidregistereduser;
    }

    public function setintistatus($intistatus) {
        $this->intistatus = $intistatus;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    //****************************************************************************
    //Recupera la información
    //****************************************************************************
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

    //****************************************************************************
    //Recupera todas las actividades por proyecto y por estrucutura de la encuesta de campo
    //****************************************************************************

    public static function recuperarTodasActividades($proyecto, $pollstr_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.buscartodasActividadesbdsp2(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera el subtotal por cada actividad para el porcentaje ejecutado
    //****************************************************************************

    public static function recuperarSubtotalporactividad($idAct, $proyecto, $pollstr_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.recuperarSubtotalporactividadsp(?,?,?);');
        $consulta->bindParam(1, $idAct, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $pollstr_id, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera proyecto por id de proyecto
    //****************************************************************************
    public static function searchById($proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedproyectosxId(?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetchAll();
        $conexion = null;
        return $registro;
    }

    //****************************************************************************
    //Recupera todas las semanas creadas por proyecto y por estrucutura de la encuesta de campo
    //****************************************************************************

    public static function obtenerTodoSemanasCons() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.obtenerSemanasConsPro();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Crea las semanas en la base de datos en la tablaprogramacionconstruccion
    //****************************************************************************
    public function insertSemanas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 

        $intidregistereduser = $_SESSION['userid'];
        $intistatus = '1';
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.insertsemanassp(?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $this->semana, PDO::PARAM_STR);
        $consulta->bindParam(2, $this->fechaInicio, PDO::PARAM_STR);
        $consulta->bindParam(3, $this->fechaFin, PDO::PARAM_STR);
        $consulta->bindParam(4, $intidregistereduser, PDO::PARAM_STR);
        $consulta->bindParam(5, $intistatus, PDO::PARAM_INT);
        $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($conexion->errorInfo());
        }
        $conexion = null;
    }

    //****************************************************************************
    //Recuperar actividades
    //****************************************************************************
    public static function recuperarActividades() {

        $pollstr_id = 1;
        $proyecto = 2;

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

    //buscar si la actividad existe en la base de datos
    public static function buscarcampobd($idactstr) {
        $pollstr_id = 2; 
        $proyecto = 2;
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

    //****************************************************************************
    //Recupera semana por id de semana
    //****************************************************************************
    public static function searchWeekById($semana) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.selectedsemanasxIdsp(?);');
        $consulta->bindParam(1, $semana, PDO::PARAM_INT);
        //$consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        /*if ($registro) {
            return new self($semana, $registro['nombre'], $registro['dtfechainicio'], $registro['dtfechafin'], $registro['intidregistereduser']);
        } else {
            return false;
        }*/
    }

    //buscar si la actividadprogramada en una semana existe en la base de datos
    public static function validarActSemporid($idsemana, $intidactividad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.validarActSemExisteSp(?,?);');
        $consulta->bindParam(1, $idsemana, PDO::PARAM_STR);
        $consulta->bindParam(2, $intidactividad, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Inserta las actividades por semana
    //****************************************************************************
    public function insertActSem($idsemana) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 

        $intidregistereduser = $_SESSION['userid'];
        $intistatus = '1';
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.insertactsemsp(?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $idsemana, PDO::PARAM_STR);
        $consulta->bindParam(2, $this->intidactividad, PDO::PARAM_STR);
        $consulta->bindParam(3, $this->porPrograAct, PDO::PARAM_STR);
        $consulta->bindParam(4, $intidregistereduser, PDO::PARAM_STR);
        $consulta->bindParam(5, $intistatus, PDO::PARAM_INT);
        $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($conexion->errorInfo());
        }
        $conexion = null;
    }
}
