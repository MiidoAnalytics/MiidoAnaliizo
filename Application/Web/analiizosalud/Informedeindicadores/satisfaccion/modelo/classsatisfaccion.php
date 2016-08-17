<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Satisfaccion {

    private $strCodidMunicipio;

    const TABLA = 'satisfaccion';

    public function __construct($strCodidMunicipio = null) {
        $this->strCodidMunicipio = $strCodidMunicipio;
    }

    public function getCodidMunicipio() {
        return $this->strCodidPais;
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

    //****************************************************************************
    //Recupera la información por código de municipio
    //****************************************************************************

    public static function recuperarAtencionEPSxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAtencionEPSxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestaAtencionxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRespuestaAtencionxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestaPersonalEPSxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRespuestaPersonalEPSxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInformacionServicioxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInformacionServicioxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInstalacionesFisicasxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInstalacionesFisicasxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarAtencionGeneralxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAtencionGeneralxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEpsPercepcionxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEpsPercepcionxMun(?,?,?);');
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

    public static function recuperarAtencionEPS($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAtencionEPS(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestaAtencion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRespuestaAtencion(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestaPersonalEPS($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRespuestaPersonalEPS(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInformacionServicio($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInformacionServicio(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInstalacionesFisicas($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInstalacionesFisicas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarAtencionGeneral($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAtencionGeneral(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEpsPercepcion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEpsPercepcion(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
