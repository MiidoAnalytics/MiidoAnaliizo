<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class PromocionPrevencion {

    private $strCodidMunicipio;

    const TABLA = 'promocionyprevencion';

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

    public static function recuperarNacidoVivoCuatroConsultasxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNacidoVivoCuatroConsultasxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarControlesPrenatalesUnAnoxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedControlesPrenatalesUnAnoxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarAfiliadosEducacionxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAfiliadosEducacionxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosCreciDesaxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosCreciDesaxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosLenguajeConductaxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosLenguajeConductaxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosProblemaVisualAudixMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosProblemaVisualAudixMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosDesparacitadosxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosDesparacitadosxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEsquemaCompletoVacunacionxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEsquemaCompletoVacunacionxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarBajoPesoMenorAnoxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedBajoPesoMenorAnoxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEdadPromAblactacionxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEdadPromAblactacionxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarPromedioConsultaPrenatalxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedPromedioConsultaPrenatalxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInfanteRefuerzoNutricionalxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInfanteRefuerzoNutricionalxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarProveedorRefuerzoNutrionalxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedProveedorRefuerzoNutrionalxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarControlPlacaxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedControlPlacaxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarConsultaOdotologicaxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedConsultaOdotologicaxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarCepilladoDiarioxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCepilladoDiarioxMun(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $municipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarCariesxMun($proyecto, $encuesta, $municipio){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCariesxMun(?,?,?);');
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

    public static function recuperarNacidoVivoCuatroConsultas($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectednacidovivocuatroconsultas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarControlesPrenatalesUnAno($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedControlesPrenatalesUnAno(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarAfiliadosEducacion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedAfiliadosEducacion(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosCreciDesa($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosCreciDesa(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosLenguajeConducta($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosLenguajeConducta(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosProblemaVisualAudi($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosProblemaVisualAudi(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarNinosDesparacitados($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedNinosDesparacitados(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEsquemaCompletoVacunacion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEsquemaCompletoVacunacion(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarBajoPesoMenorAno($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedBajoPesoMenorAno(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEdadPromAblactacion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEdadPromAblactacion(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarPromedioConsultaPrenatal($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedPromedioConsultaPrenatal(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarInfanteRefuerzoNutricional($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInfanteRefuerzoNutricional(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarProveedorRefuerzoNutrional($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedProveedorRefuerzoNutrional(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarControlPlaca($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedControlPlaca(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarConsultaOdotologica($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedConsultaOdotologica(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarCepilladoDiario($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCepilladoDiario(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarCaries($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCaries(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
