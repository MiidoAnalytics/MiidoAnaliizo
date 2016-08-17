<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Report {

    private $intIdReports;
    private $strreportname;
    private $strdescription;
    private $strquery;
    private $strregistereduser;
    private $istatusid;
    private $dtcreatedate;
    private $dtmodifieddate;
    private $since;
    private $until;
    private $town;
    private $interviewer;
    private $disease;
    private $medicine;
    private $etnia;
    private $desplazado;
    private $estudios;
    private $minusvalido;
    private $odontologia;
    private $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad;

    const TABLA = 'reportes';

    public function __construct($strreportname = null, $strdescription = null, $strquery = null, $intIdReports = null, $interviewer = null, $disease = null, $medicine = null, $strregistereduser = null, $istatusid = null, $dtcreatedate = null, $dtmodifieddate = null, $since = null, $until = null, $town = null, $etnia = null, $desplazado = null, $estudios = null, $minusvalido = null, $odontologia = null, $vivienda = null, $agua = null, $alcantarillado = null, $gasNatural = null, $electricidad = null, $intIdDiv = null) {
        $this->intIdReports = $intIdReports;
        $this->strreportname = $strreportname;
        $this->strdescription = $strdescription;
        $this->strquery = $strquery;
        $this->strregistereduser = $strregistereduser;
        $this->istatusid = $istatusid;
        $this->dtcreatedate = $dtcreatedate;
        $this->dtmodifieddate = $dtmodifieddate;
        $this->until = $until;
        $this->since = $since;
        $this->town = $town;
        $this->interviewer = $interviewer;
        $this->disease = $disease;
        $this->medicine = $medicine;
        $this->etnia = $etnia;
        $this->desplazado = $desplazado;
        $this->estudios = $estudios;
        $this->minusvalido = $minusvalido;
        $this->odontologia = $odontologia;
        $this->vivienda = $vivienda;
        $this->agua = $agua;
        $this->alcantarillado = $alcantarillado;
        $this->gasNatural = $gasNatural;
        $this->electricidad = $electricidad;
        $this->intIdDiv = $intIdDiv;
    }

    public function getIdReport() {
        return $this->intIdReports;
    }

    public function getReportName() {
        return $this->strreportname;
    }

    public function getDescription() {
        return $this->strdescription;
    }

    public function getQuery() {
        return $this->strquery;
    }

    public function getRegisteredUser() {
        return $this->$strregistereduser;
    }

    public function getistatusid() {
        return $this->$istatusid;
    }

    public function getCreateDate() {
        return $this->$dtcreatedate;
    }

    public function getModifiedDate() {
        return $this->$dtmodifieddate;
    }

    public function getDateSince() {
        return $this->$since;
    }

    public function getDateUntil() {
        return $this->$until;
    }

    public function getTown() {
        return $this->$town;
    }

    public function setIdReport($intIdReports) {
        $this->intIdReports = $intIdReports;
    }

    public function setReportName($strreportname) {
        $this->strreportname = $strreportname;
    }

    public function setDescription($strdescription) {
        $this->strdescription = $strdescription;
    }

    public function setQuery($strquery) {
        $this->strquery = $strquery;
    }

    public function setRegisteredUser($strregistereduser) {
        $this->strregistereduser = $strregistereduser;
    }

    public function setistatusid($istatusid) {
        $this->istatusid = $istatusid;
    }

    public function setCreateDate($dtcreatedate) {
        $this->dtcreatedate = $dtcreatedate;
    }

    public function setModifiedDate($dtmodifieddate) {
        $this->dtmodifieddate = $dtmodifieddate;
    }

    public function setUntilDate($until) {
        $this->until = $until;
    }

    public function setSinceDate($since) {
        $this->since = $since;
    }

    public function setTownDate($town) {
        $this->town = $town;
    }

    public function insert() {
        //require_once 'classConexion.php';
        $conexion = new ConexionAnaliizoPostgres();
        //$conexion->exec("set names utf8");

        if ($this->intIdReports) /* Modifica */ {
            try {

                $strregistereduser = $_SESSION['user'];
                $dtmodifieddate = date("Ymd");
                $dtcreatedate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_salud.spUpdateReport(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdReports, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strreportname, PDO::PARAM_INT);
                $consulta->bindParam(3, $this->strdescription, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strquery, PDO::PARAM_STR);
                $consulta->bindParam(5, $strregistereduser, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtmodifieddate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {

                $strregistereduser = $_SESSION['user'];
                $istatusid = 1;
                $dtcreatedate = date("Ymd");
                $dtmodifieddate = date("Ymd");

                $consulta = $conexion->prepare('select analiizo_salud.insertreport(:strreportname::VARCHAR,:strdescription::VARCHAR,
                                                    :strquery::TEXT,:strregistereduser::VARCHAR,:istatusid::INTEGER,
                                                    :dtcreatedate::TIMESTAMP,:dtmodifieddate::TIMESTAMP);');
                $consulta->bindParam('strreportname', $this->strreportname);
                $consulta->bindParam('strdescription', $this->strdescription);
                $consulta->bindParam('strquery', $this->strquery);
                $consulta->bindParam('strregistereduser', $strregistereduser);
                $consulta->bindParam('istatusid', $istatusid);
                $consulta->bindParam('dtcreatedate', $dtcreatedate);
                $consulta->bindParam('dtmodifieddate', $dtmodifieddate);
                $consulta->execute();
                $this->intIdReports = $conexion->lastInsertId();
            } catch (Exception $ex) {

                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public static function searchById($intIdReports) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedReportxId(?);');
        $consulta->bindParam(1, $intIdReports, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        //echo count($registro);
        if ($registro) {
            return new self($registro['strreportname'], $registro['strdescription'], $registro['strquery'], $intIdReports);
        } else {

            return false;
        }
    }

    public static function getAll() {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedReport();');
        //$consulta->query();
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

   public static function Query($strQuery){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $strQuery;
        $resultado = $conexion->query($consulta);       
        $resultado->execute();
        ini_set('memory_limit', '-1');
        $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
        $resultado->closeCursor();
        $registros = null;
    } 
  
    public static function TitlesCols($strquery) {
        $conexion = new ConexionAnaliizoPostgres();
        $consulta = $strquery;
        $rs = $conexion->query($consulta);
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    public static function buscarDatosTodosFiltro($since, $until, $town, $ageSince, $ageUntil, $gender, $interviewer, $disease, $medicine, $etnia, $desplazado, $estudios, $minusvalido, $odontologia, $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spbuscardatosreportetodosfiltro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $since, PDO::PARAM_STR);
        $consulta->bindParam(2, $until, PDO::PARAM_STR);
        $consulta->bindParam(3, $town, PDO::PARAM_STR);
        $consulta->bindParam(4, $ageSince, PDO::PARAM_STR);
        $consulta->bindParam(5, $ageUntil, PDO::PARAM_STR);
        $consulta->bindParam(6, $gender, PDO::PARAM_STR);
        $consulta->bindParam(7, $interviewer, PDO::PARAM_STR);
        $consulta->bindParam(8, $disease, PDO::PARAM_STR);
        $consulta->bindParam(9, $medicine, PDO::PARAM_STR);
        $consulta->bindParam(10, $etnia, PDO::PARAM_STR);
        $consulta->bindParam(11, $desplazado, PDO::PARAM_STR);
        $consulta->bindParam(12, $estudios, PDO::PARAM_STR);
        $consulta->bindParam(13, $minusvalido, PDO::PARAM_STR);
        $consulta->bindParam(14, $odontologia, PDO::PARAM_STR);
        $consulta->bindParam(15, $vivienda, PDO::PARAM_STR);
        $consulta->bindParam(16, $agua, PDO::PARAM_STR);
        $consulta->bindParam(17, $alcantarillado, PDO::PARAM_STR);
        $consulta->bindParam(18, $gasNatural, PDO::PARAM_STR);
        $consulta->bindParam(19, $electricidad, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registro;
    }

    public static function buscarDatosTodosFiltroTitulos($since, $until, $town, $ageSince, $ageUntil, $gender, $interviewer, $disease, $medicine, $etnia, $desplazado, $estudios, $minusvalido, $odontologia, $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spbuscardatosreportetodosfiltro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $since, PDO::PARAM_STR);
        $consulta->bindParam(2, $until, PDO::PARAM_STR);
        $consulta->bindParam(3, $town, PDO::PARAM_STR);
        $consulta->bindParam(4, $ageSince, PDO::PARAM_STR);
        $consulta->bindParam(5, $ageUntil, PDO::PARAM_STR);
        $consulta->bindParam(6, $gender, PDO::PARAM_STR);
        $consulta->bindParam(7, $interviewer, PDO::PARAM_STR);
        $consulta->bindParam(8, $disease, PDO::PARAM_STR);
        $consulta->bindParam(9, $medicine, PDO::PARAM_STR);
        $consulta->bindParam(10, $etnia, PDO::PARAM_STR);
        $consulta->bindParam(11, $desplazado, PDO::PARAM_STR);
        $consulta->bindParam(12, $estudios, PDO::PARAM_STR);
        $consulta->bindParam(13, $minusvalido, PDO::PARAM_STR);
        $consulta->bindParam(14, $odontologia, PDO::PARAM_STR);
        $consulta->bindParam(15, $vivienda, PDO::PARAM_STR);
        $consulta->bindParam(16, $agua, PDO::PARAM_STR);
        $consulta->bindParam(17, $alcantarillado, PDO::PARAM_STR);
        $consulta->bindParam(18, $gasNatural, PDO::PARAM_STR);
        $consulta->bindParam(19, $electricidad, PDO::PARAM_STR);
        $consulta->execute();
        $cuenta_col = $consulta->columnCount();
        if (!$cuenta_col) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        //$registro = $consulta->fetchAll();
        for ($i = 0; $i < $cuenta_col; $i++) {
            $col = $consulta->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    //****************************************************************************
    //Recupera la información de las Enfermedades Reportadas
    //****************************************************************************
    public static function recuperarEnfermedadesReportadas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEnfermedadReportada();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de los Medicamentos Reportadas
    //****************************************************************************
    public static function recuperarMedicamentosReportados() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedMedicamentoReportado();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de las Razas Reportadas
    //****************************************************************************
    public static function recuperarRazasReportadas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRazaReportada();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera el id de reporste correspondiente al indice que se muestra
    //****************************************************************************
    public static function recuperarIdReporte($intIdDiv) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedRecuperarIdReporte(?);');
        $consulta->bindParam(1, $intIdDiv, PDO::PARAM_STR);
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
    //Recupera la información de todos los municipios
    //****************************************************************************
    public static function recuperarMunicipiosEncuestados($proyecto, $encuesta) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedMunicipiosEncuestados(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las columnas de la tabal informeoperador
    //****************************************************************************
    public static function obtenerColumnasBD($proyecto, $encuesta){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerColumnasEstrucutura(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las opciones chceck box para la el filtro seleccionado
    //****************************************************************************
    public static function obtenerOpcionesColumna($proyecto, $encuesta, $idpre){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerOpcionesColumna(?,?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $idpre, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las opciones para la el filtro seleccionado combo 
    //****************************************************************************
    public static function obtenerOpcionesColumna3($proyecto, $encuesta, $idpre){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerOpcionesColumna3(?,?,?) as (optionsc character varying);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $idpre, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que quita la doble comilla de las opciones
    //****************************************************************************
    public static function quitarComillas($option){
        $nuevaopt = '';
        $nuevaopt = str_replace('"', '', $option);
        return $nuevaopt;
    }

    //****************************************************************************
    //Recupera las eps reportadas 
    //****************************************************************************
    public static function obtenerEpsReportadas($proyecto, $encuesta){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerEpsReportadas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
     //****************************************************************************
    //Recupera las ocupaciones reportadas 
    //****************************************************************************
    public static function obtenerOcupacionesReportadas($proyecto, $encuesta){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerOcupacionesReportadas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

     //****************************************************************************
    //Recupera las Enfeermedades reportadas 
    //****************************************************************************
    public static function obtenerEnfermedadesReportadas($proyecto, $encuesta){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerEnfermedadReportadas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera las Medicamentos reportadas 
    //****************************************************************************
    public static function obtenerMedicamentosReportadas($proyecto, $encuesta){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.obtenerMedicamentosReportadas(?,?);');
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //funcion para buscar la operación que va a llevar el query <, <=, >, >=
    public function operacionWhere($clave, $valor, $flag, $flagCol, $flagColAux, $nivel){
        $queBet = "";
        $resVal =  preg_replace('/[0-9]+/', '', $clave);
        $resVal = explode("_", $resVal);
        $oper = $resVal[0];
        $campo = $resVal[1];
        if($resVal[2]){
            if ($resVal[3]) {
                $campobd = $resVal[1]."_".$resVal[2]."_".$resVal[3];
            }else{
                $campobd = $resVal[1]."_".$resVal[2];
            }
        }else{
            $campobd = $campo;
        }
        
        switch ($oper) {
            case '<':
                if ($flag == 1) {
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) < ".$valor."";
                    }else{
                        if ($flagColAux == 0) {
                           $queBet = " or cast(".$nivel.$campobd."' as numeric) < ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) < ".$valor." ";
                        }
                    }
                }else{
                    if ($flagCol == 1) {
                        //$queBet = $campobd.' < "'.$valor.'" ';
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) < ".$valor;
                    }else{
                        if ($flagColAux == 0) {
                            $queBet = " or cast(".$nivel.$campobd."' as numeric) < ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) < ".$valor." ";
                        }
                    }
                }
                break;
            case '>':
                if ($flag == 1) {
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                           $queBet = " or cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                        }
                    }
                }else{
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                            $queBet = " or cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) > ".$valor." ";
                        }
                    }
                }
                break;
            case '<=':
                if ($flag == 1) {
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                           $queBet = " or cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                        }
                    }
                }else{
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                            $queBet = " or cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) <= ".$valor." ";
                        }
                    }
                }
                break;
            case '>=':
                if ($flag == 1) {
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                           $queBet = " or cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                        }else{
                            $queBet = " and cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                        }
                    }
                }else{
                    if ($flagCol == 1) {
                        $queBet = " and cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                    }else{
                        if ($flagColAux == 0) {
                            $queBet = " or cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                        }else{
                            $queBet = " or cast(".$nivel.$campobd."' as numeric) >= ".$valor." ";
                        }
                    }
                }
                break;
            case 'since':
                if ($campo == "FchUltMens" || $campo == "fecNac" || $campo == "FechProstata") {
                    //$queBet .= " and ".$campobd." between CAST('$valor' AS DATE) ";
                    if ($flagCol == 1) {
                        $queBet .=  " and cast(".$nivel.$campobd."' as date) between CAST('".$valor."' AS DATE) ";  
                    }else{
                        $queBet .=  " and cast(".$nivel.$campobd."' as date) between CAST('".$valor."' AS DATE) ";  
                    }
                }else{
                    if ($flagCol == 1) {
                        $queBet .=  " and cast(".$nivel.$campobd."' as numeric) between $valor ";  
                    }else{
                        if ($flagColAux == 0) {
                            $queBet .= " or cast(".$nivel.$campobd."' as numeric) between ".$valor." ";
                        }else{
                            $queBet .= " and cast(".$nivel.$campobd."' as numeric) between ".$valor." ";
                        }   
                    }
                }
                break;
            case 'until':
                if ($campo == "FchUltMens" || $campo == "fecNac" || $campo == "FechProstata") {
                    $queBet.= "and CAST('".$valor."' AS DATE) ";
                   /* if ($flag == 1) {
                        $queBet.='and CAST("'.$valor.'" AS DATE) ';
                    }else{
                        $queBet.='and CAST("'.$valor.'" AS DATE) ';
                    }*/
                }else{
                    $queBet.=" and ".$valor." ";
                    /*if ($flag == 1) {
                        $queBet.=' and "'.$valor.'"';
                    }else{
                        if ($flagColAux == 0) {
                            $queBet.=' and "'.$valor.'" ';
                        }else{
                            $queBet.=' and "'.$valor.'" ';
                        }   
                    }*/
                }
                break;
            case 'opcionesS':
                if ($flagColAux == 0) {
                    $queBet .= " and ".$nivel.$campobd."' like '".$valor."' ";
                }else{
                    $queBet .= " and ".$nivel.$campobd."' like '".$valor."' ";
                }
                break;
        }
        if($oper != "operacionS"){
            return $queBet;
        }
    }

    //****************************************************************************
    //Recupera las opciones para la el filtro seleccionado
    //****************************************************************************
    public static function consultaReporteador($proyecto, $encuesta ,$columns, $condiciones, $nuevasCols, $from) {
        $conexion = new ConexionAnaliizoPostgres();
        $que = 'select * from analiizo_salud.ejecutarquery(?,?,?,?,?) as (area text, latitud text, longitud text, primer_nombre text, segundo_nombre text, primer_apellido text,segundo_apellido text, tipo_doc text, identificacion text, departamento text, municipio text, barrio text, direccion text,telefono text, telefono_persona text, edad text, genero text';
        if ($nuevasCols != "") {
            $que = $que.$nuevasCols.");";
        }else{
            $que = $que.");";
        }
        $consulta = $conexion->prepare($que);
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $columns, PDO::PARAM_STR);
        $consulta->bindParam(4, $condiciones, PDO::PARAM_STR);
        $consulta->bindParam(5, $from, PDO::PARAM_STR);
        $consulta->execute();
        if (!$consulta) {
            //echo "\nPDO::errorInfo():\n";
            //print_r($conexion->errorInfo());
        }else{
            //echo "\nPDO::errorInfo():\n";
            //print_r($conexion->errorInfo());
        }
        $registro = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registro;
    }

    public static function titulosConsultaReporteador($proyecto, $encuesta ,$columns, $condiciones, $nuevasCols, $from) {
        $conexion = new ConexionAnaliizoPostgres();
        $que = 'select * from analiizo_salud.ejecutarquery(?,?,?,?,?) as (area text, latitud text, longitud text, primer_nombre text, segundo_nombre text, primer_apellido text,segundo_apellido text, tipo_doc text, identificacion text, departamento text, municipio text, barrio text, direccion text,telefono text, telefono_persona text, edad text, genero text';
        if ($nuevasCols != "") {
            $que = $que.$nuevasCols.");";
        }else{
            $que = $que.");";
        }
        $consulta = $conexion->prepare($que);
        $consulta->bindParam(1, $proyecto, PDO::PARAM_STR);
        $consulta->bindParam(2, $encuesta, PDO::PARAM_STR);
        $consulta->bindParam(3, $columns, PDO::PARAM_STR);
        $consulta->bindParam(4, $condiciones, PDO::PARAM_STR);
        $consulta->bindParam(5, $from, PDO::PARAM_STR);
        $consulta->execute();
        if (!$consulta) {
            //echo "\nPDO::errorInfo():\n";
            //print_r($conexion->errorInfo());
        }else{
            //echo "\nPDO::errorInfo():\n";
            //print_r($conexion->errorInfo());
        }
        $conexion = null;
        $columns = array();
        $cuenta_col = $consulta->columnCount();
        for ($i = 0; $i < $cuenta_col; $i++) {
            $col = $consulta->getColumnMeta($i);
            $colunmName = strtoupper($col['name']);
            $columns[$i] = $colunmName;
        }
        return $columns;
    }

}

?>
