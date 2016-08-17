<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class ReportesMapas {

    private $intIdReports;
    private $intIdTown;

    const TABLA = 'reports';

    public function __construct($intIdReports = null, $intIdTown = null) {
        $this->intIdReports = $intIdReports;
        $this->intIdTown = $intIdTown;
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
    //Recupera la información por código de Encuestador
    //****************************************************************************

    public static function recuperarQueryxidReporte($intIdReports) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedQueryxidReporte(?);');
        $consulta->bindParam(1, $intIdReports, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarUbicacionxReporte($strQuery) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $strQuery;
        $resultado = $conexion->query($consulta);       
        $resultado->execute();
        $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
        if (!$registros) {
            echo "\nPDO::errorInfo():\n";
            print_r($resultado->errorInfo());
        }
        $conexion = null;
        return $registros;
        // $conexion = new ConexionAnaliizo();
        // $conexion->exec("set names utf8");
        // $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedUbicacionxReporte(?);');
        // $consulta->bindParam(1, $strQuery, PDO::PARAM_STR);
        // $consulta->execute();
        // $registros = $consulta->fetchAll();
        // $conexion = null;
        // return $registros;
    }

}
