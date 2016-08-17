<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Indicadores {

    private $encuesta;
    private $proyecto;

    const TABLA = 'indicadores';

    public function __construct($encuesta = null, $proyecto = null) {
        $this->encuesta = $encuesta;
        $this->proyecto = $proyecto;
    }

    public function getIdEncuesta() {
        return $this->encuesta;
    }

    public function getIdProyecto() {
        return $this->proyecto;
    }

    public function setIdEncuesta($encuesta){
        $this->encuesta = $encuesta;
    }

    public function setIdProyecto($proyecto){
        $this->proyecto = $proyecto;
    }

    //****************************************************************************
    //Recupera los proyectos por id usuario
    //****************************************************************************
    public static function ObtenerProyectosUsuerId($idusuario) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedProyectosUsuerId(?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
   
    public static function recuperarEstadoNutricionalAdultosxMun($proyecto, $encuesta, $municipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.estadoNutricionalSpadultosxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarEstadoNutNinos519xMun($genero, $proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.EstadoNutricionalNinos519xMunsp(?,?,?,?);');
        $consulta->bindParam(1, $genero, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(4, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEstadoNutNinos05xMun($genero, $proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.estadoNutricionalninos05xMunSp(?,?,?,?);');
        $consulta->bindParam(1, $genero, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(4, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarPresionArterialGeneroxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.presionarterialtageneroxMunSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRiesgoEnfeCardioMujerxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.riesgoEnfeCardioMujerxMunSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRiesgoEnfeCardioHombrexMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.riesgoEnfeCardioHombrexMunSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarOximetriaxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.indicadorOximetriaxMunicipioSP(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de todos los municipios
    //****************************************************************************

    public static function recuperarEstadoNutricionalAdultos($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.estadoNutricionalSpadultos(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarEstadoNutricionalNinos519($genero, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.EstadoNutricionalNinos519sp(?,?,?);');
        $consulta->bindParam(1, $genero, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarEstadoNutricionalNinos05($genero, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.estadoNutricionalninos05Sp(?,?,?);');
        $consulta->bindParam(1, $genero, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarPresionArterialGenero($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.presionarterialtageneroSp(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarRiesgoEnfeCardioMujer($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.riesgoEnfeCardioMujerSp(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }


    public static function recuperarRiesgoEnfeCardioHombre($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.riesgoEnfeCardioHombreSp(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    public static function recuperarBajaOximetria($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.indicadorOximetriaSP(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    /*public static function recuperarBajaOximetria($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('call indicadorOximetriaSP();');
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

   

    public static function recuperarZsocrepesoedad($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('call zscorepesoedadsp();');
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la desviación estandar y la media niñas 0 a 5 años
    //****************************************************************************

    public static function recuperarMedDesZpesoedadNinas($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('call MedDesZpesoedadspNinas();');
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la desviación estandar y la media niños 0 a 5 años
    //****************************************************************************

    public static function recuperarMedDesZpesoedadNinos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('call MedDesZpesoedadspNinos();');
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
    }*/
}
