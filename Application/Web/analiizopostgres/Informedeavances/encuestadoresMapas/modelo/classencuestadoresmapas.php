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

    public static function recuperarEncuestasxEncuestador($strCodidEncuestador) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEncuestasxEncuestador(?);');
        $consulta->bindParam(1, $strCodidEncuestador, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de todos los Encuestadors
    //****************************************************************************

    public static function recuperarEncuestadorxEncuesta() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEncuestadorxEncuesta();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEncuestasxEncuestadores() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEncuestasxEncuestadores();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
