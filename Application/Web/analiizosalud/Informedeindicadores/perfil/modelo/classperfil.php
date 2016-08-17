<?php
if (!defined('CONTROLADOR'))
    exit;
require_once '../../../../conexiones/classconexion.php';

class Perfil {

    private $perfil;

    const TABLA = 'perfil';

    public function __construct($perfil = null) {
        $this->perfil = $perfil;
    }

    public function getPerfil() {
        return $this->perfil;
    }

    public function setPerfil($perfil) {
        $this->perfil = $perfil;
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
    //Recupera los tipos de identificaión reportados
    //****************************************************************************
    public static function obtenerTipoIdentificacion($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.tipoIdentificacionSP(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las personas por tipo de documento y num de documento
    //****************************************************************************
    public static function obtenerPerfilPersonaDocumento($proyecto, $pollid, $tipoIdentidad, $documento) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilpersonaxdocumentoSp(?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $tipoIdentidad, PDO::PARAM_STR);
        $consulta->bindParam(4, $documento, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera titulos
    //****************************************************************************
    public static function obtenerPerfilPersonaDocumentoTit($proyecto, $pollid, $tipoDoc, $numDoc){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilpersonaxdocumentoSp(?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $tipoIdentidad, PDO::PARAM_STR);
        $consulta->bindParam(4, $documento, PDO::PARAM_STR);
        $consulta->execute();
        $cuenta_col = $consulta->columnCount();
      
        for ($i = 0; $i < $cuenta_col; $i++) {
            $col = $consulta->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    //****************************************************************************
    //Recupera las personas por nombres
    //****************************************************************************
    public static function obtenerPerfilPersonaNombres($proyecto, $pollid, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilpersonaxnombresSp(?,?,?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $primerNombre, PDO::PARAM_STR);
        $consulta->bindParam(4, $segundoNombre, PDO::PARAM_STR);
        $consulta->bindParam(5, $primerApellido, PDO::PARAM_STR);
        $consulta->bindParam(6, $segundoApellido, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las personas por nombres
    //****************************************************************************
    public static function obtenerPerfilPersonaDocName($proyecto, $pollid, $tipoIdentidad, $documento, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilpersonaxDocnombreSp(?,?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $tipoIdentidad, PDO::PARAM_STR);
        $consulta->bindParam(4, $documento, PDO::PARAM_STR);
        $consulta->bindParam(5, $primerNombre, PDO::PARAM_STR);
        $consulta->bindParam(6, $segundoNombre, PDO::PARAM_STR);
        $consulta->bindParam(7, $primerApellido, PDO::PARAM_STR);
        $consulta->bindParam(8, $segundoApellido, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las familias por nombre familia
    //****************************************************************************
    public static function obtenerFamiliasListadoxNnombre($proyecto, $pollid, $valorBuscar) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilfamiliaxnombresSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $valorBuscar, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las informacion de la familia por id casa
    //****************************************************************************
    public static function obtenerPerfilFamiliaCasa($proyecto, $pollid, $idcasa) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilfamiliaxidcasaSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $idcasa, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera titulos familia
    //****************************************************************************
    public static function obtenerPerfilFamiliaCasaTit($proyecto, $pollid, $idcasa){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.perfilfamiliaxidcasaSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $idcasa, PDO::PARAM_STR);
        $consulta->execute();
        $cuenta_col = $consulta->columnCount();
      
        for ($i = 0; $i < $cuenta_col; $i++) {
            $col = $consulta->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    //****************************************************************************
    //Recupera las informacion de las personas x familia por id casa
    //****************************************************************************
    public static function obtenerPersonasxcasa($proyecto, $pollid, $idcasa) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.personasfamiliaxidcasaSp(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $idcasa, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las enfermedades por persona por documento
    //****************************************************************************
    public static function obtenerEnfermedadesPorDocumento($proyecto, $pollid, $documento, $tipoIdentidad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedenfermedadesporpersona(?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $documento, PDO::PARAM_STR);
        $consulta->bindParam(4, $tipoIdentidad, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las medicamentos por persona por documento y enfermedad reportada
    //****************************************************************************
    public static function obtenerMedicamentosPorDocumento($proyecto, $pollid, $documento, $tipoIdentidad, $enfermedad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedmedicamentoporpersona(?,?,?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $pollid, PDO::PARAM_STR);
        $consulta->bindParam(3, $documento, PDO::PARAM_STR);
        $consulta->bindParam(4, $tipoIdentidad, PDO::PARAM_STR);
        $consulta->bindParam(5, $enfermedad, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
