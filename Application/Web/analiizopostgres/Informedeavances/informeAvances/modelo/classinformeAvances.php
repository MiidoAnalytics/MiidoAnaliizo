<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class InformeAvances {

    private $strCodidMunicipio;

    const TABLA = 'InformeAvances';

    public function __construct($strCodidMunicipio = null) {
        $this->strCodidMunicipio = $strCodidMunicipio;
    }

    public function getCodidMunicipio() {
        return $this->strCodidPais;
    }

    public static function ObtenerProyectosUsuerId($idusuario) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedProyectosUsuerId(?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información por código de municipio
    //****************************************************************************

    public static function recuperarInformeEncuestasPorMunicipio($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('call spSelectedInformeEncuestasPorMunicipio(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de todos los municipios
    //****************************************************************************

    public static function recuperarInformePorDepartamento() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");        
        $consulta = $conexion->prepare('select * from analiizo.spSelectedInformePorDepartamento();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInformeDetalladoPorMunicipio() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedInformeDetalladoPorMunicipio();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInformeEncuestadoresPorMunicipio() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedInformeEncuestadoresPorMunicipio();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEncuestadoresPorMunicipio() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEncuestadoresPorMunicipio();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInformeEncuestadoresTiempo($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedInformeEncuestadoresTiempo(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRelacionPersonasEncuestadas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedRelacionPersonasEncuestadas();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}