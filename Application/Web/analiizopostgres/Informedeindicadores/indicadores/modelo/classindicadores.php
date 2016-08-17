<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Indicadores {

    private $encuesta;
    private $proyecto;

    const TABLA = 'indicadoresBa';

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
    //Recupera la información
    //****************************************************************************

    public static function recuperarPreguntasEncuesta($encuesta, $proyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.preguntasEncuesta(?,?);');
        $consulta->bindParam(1, $encuesta, PDO::PARAM_INT);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestasPregunta($pregunta, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.recuperarRespuestasPregunta(?,?,?);');
        $consulta->bindParam(1, $pregunta, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarRespuestasPreguntaLB() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.recuperarRespuestasPreguntalb();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarOpcionesPreguntaCB($pregunta, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.recuperaropcionespreguntacb(?,?,?);');
        $consulta->bindParam(1, $pregunta, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        /*echo '<pre>';
        print_r($registros);
        echo '</pre>';*/
        return $registros;
    }

    public static function recuperarrespuestaspreguntacheckbox($pregunta, $proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.recuperarrespuestaspreguntacheckbox(?,?,?);');
        $consulta->bindParam(1, $pregunta, PDO::PARAM_STR);
        $consulta->bindParam(2, $proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
