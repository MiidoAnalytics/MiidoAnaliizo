<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class EncuestadoresMapas {

    private $strCodidEncuestador;

    const TABLA = 'interviewers';

    public function __construct($strCodidEncuestador = null) {
        $this->strCodidEncuestador = $strCodidEncuestador;
    }

//    public function getCodidEncuestador() {
//        return $this->strCodidPais;
//    }
    //****************************************************************************
    //Recupera la información por código de Encuestador
    //****************************************************************************

    public static function recuperarEncuestasxEncuestador($encuestador, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEncuestasxEncuestadores(?,?,?);');
        $consulta->bindParam(1, $encuestador, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de todos los Encuestadors
    //****************************************************************************

    public static function recuperarEncuestadorxEncuesta($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEncuestadorxEncuesta(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEncuestasxEncuestadores() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEncuestasxEncuestadores();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

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

}
