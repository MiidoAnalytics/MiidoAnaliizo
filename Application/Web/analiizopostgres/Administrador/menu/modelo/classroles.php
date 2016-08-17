<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Roles {

    private $role_id;
    private $role_name;
    private $intConsult;
    private $intCreater;
    private $intEditer;
    private $intDeleter;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;
    private $menu_idP;
    private $menu_idH;
    private $idusuario;
    private $parent;

    const TABLA = 'Roles';

    public function __construct($role_id = null, $role_name = null, $intConsult=null, 
        $intCreater = null, $intEditer = null, $intDeleter = null, $strRegisteredUser = null, 
        $dtFechaRegistro = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null, $menu_idP = null, $menu_idH = null,
        $idusuario = null, $parent = null) {

        $this->role_id = $role_id;
        $this->role_name = $role_name;
        $this->intConsult = $intConsult;
        $this->intCreater = $intCreater;
        $this->intEditer = $intEditer;
        $this->intDeleter = $intDeleter;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
        $this->menu_idP = $menu_idP;
        $this->menu_idH = $menu_idH;
        $this->idusuario = $idusuario;
        $this->parent = $parent;
    }

    public function getIdRol(){
        return $this->role_id;
    }

    public function getNameRol(){
        return $this->role_name;
    }

    public function getIntConsult(){
        return $this->intConsult;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

    public function getFechaRegistro() {
        return $this->$dtFechaRegistro;
    }

    public function getIstatusId() {
        return $this->$IstatusId;
    }

    public function getCreateDate() {
        return $this->$dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->$dtModifiedDate;
    }

    public function getMenuId() {
        return $this->$menu_idP;
    }

    public function getMenuIdHijo() {
        return $this->$menu_idH;
    }

    public function getIdUsuarioRol() {
        return $this->$idusuario;
    }

    public function getParent() {
        return $this->$parent;
    }

    public function setIdRol($role_id) {
        $this->role_id = $role_id;
    }

    public function setRolName($role_name) {
        $this->role_name = $role_name;
    }

    public function setIntConsult($intConsult) {
        $this->intConsult = $intConsult;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setFechaRegistro($dtFechaRegistro) {
        $this->dtFechaRegistro = $dtFechaRegistro;
    }

    public function setIstatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function setMenuId($menu_idP) {
        $this->menu_idP = $menu_idP;
    }

    public function setMenuIdHijo($menu_idH) {
        $this->menu_idH = $menu_idH;
    }

    public function setIdusuarioRol($idusuario) {
        $this->idusuario = $idusuario;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    //****************************************************************************
    //Inserta o actualiza un rol
    //****************************************************************************

    public function insert() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        if ($this->role_id) /* Modifica */ {
            try {

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from administracion.spUpdateRoles(?,?,?,?,?);');
                $consulta->bindParam(1, $this->role_id, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->role_name, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->intConsult, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->execute();

                if (!$consulta) {
                    echo "\nPDO::errorInfo():\n";
                    print_r($consulta->errorInfo());
                }
                

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {

                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from administracion.spinsertroles(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->role_name, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->intConsult, PDO::PARAM_STR);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->role_id = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que precarga los menus para el rol con istatusis en cero
    //****************************************************************************

    public function insertMenuRol() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        if ($this->role_id) /* Modifica */ {
            
            try {
                
                $strRegisteredUser = $_SESSION['user'];
                //$IstatusId = '0';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                

                $consulta = $conexion->prepare('select * from administracion.spPrecargarMenusRol(?,?,?,?,?,?);');
                
                $consulta->bindParam(1, $this->menu_idH, PDO::PARAM_STR);
                $consulta->bindParam(2, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $this->role_id , PDO::PARAM_STR);

                $consulta->execute();
                if (!$consulta) {
                    echo "\nPDO::errorInfo():\n";
                    print_r($consulta->errorInfo());
                }
                //$this->role_id = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que actualiza el estado de un role en 3
    //****************************************************************************

    public function delete($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spdeleteroles(?,?);');
        $consulta->bindParam(1, $this->role_id, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que actualiza el estado de la realacion rol y usuario en 3
    //****************************************************************************

    public function deleteRelRolUser($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spdeleteRelRolUser(?,?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que busca un rol por id
    //****************************************************************************
    public static function searchById($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from administracion.spSelectedRolestxId(?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return  array(
                'role_id' => $role_id, 
                'role_name' => $registro['role_name'],
                'consult' => $registro['consult']);
        } else {
            return false;
        }
    }


    //****************************************************************************
    //Funcion que busca un rol por id
    //****************************************************************************
    public static function searchByIddos($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from administracion.spSelectedRolestxId(?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
           return new self($role_id, $registro['role_name'], $registro['consult']);
        } else {
            return false;
        }
    }

    //****************************************************************************
    //Funcion que obtiene todos los roles
    //****************************************************************************
    public static function getAllRoles() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from administracion.spSelectedDRoles();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que obtiene los menus padres para cada rol
    //****************************************************************************
    public static function MenusPadres() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedMenuPadreRol();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que obtiene los menus hijos para cada rol
    //****************************************************************************
    public static function MenusHijos($menu_idP) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedMenuHijoRol(?);');
        $consulta->bindParam(1, $menu_idP, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que verifica si existe un menu por Rol
    //****************************************************************************
    public static function VerificarMenuxRol($role_id, $menu_idH) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spValidarMenuxRol(?,?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(2, $menu_idH, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que actualiza la tabla menu rol
    //****************************************************************************
    public static function ActualizarMenuRol($menu_idH, $IstatusId, $role_id) {
        $dtModifiedDate = date("Ymd");
        $strRegisteredUser = $_SESSION['user'];
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spactualizarmenuxrol(?,?,?,?,?);');
        $consulta->bindParam(1, $menu_idH, PDO::PARAM_STR);
        $consulta->bindParam(2, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(3, $IstatusId, PDO::PARAM_STR);
        $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
        $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
    }

    //****************************************************************************
    //Funcion que ingresa en la tabla menu rol
    //****************************************************************************
    public static function IngresarMenuRol($role_id, $menu_idH, $menu_idP, $IstatusId) {

        $strRegisteredUser = $_SESSION['user'];
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spInsertMenuRol(?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(2, $menu_idH, PDO::PARAM_STR);
        $consulta->bindParam(3, $menu_idP, PDO::PARAM_STR);
        $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
        $consulta->bindParam(5, $IstatusId, PDO::PARAM_STR);
        $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
    }
    
    
    //****************************************************************************
    //Funcion que busca los usuarios con el respectivo rol activos
    //****************************************************************************
    public static function getAllUsuariosRol() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from administracion.spselecteduserrol();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        //print_r($registros); die();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que busca los usuarios con el respectivo rol por idusuario
    //****************************************************************************
    public static function rolUserxId($idusuario) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spvalidaruserxrol(?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        $registros = $consulta->fetchAll();
        //print_r($registros); die();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que busca el menu de inicio para cada rol padre
    //****************************************************************************
    public static function obtenerMenuIni($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spPrimerMenuIngreso(?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

     //****************************************************************************
    //Funcion que busca el menu de inicio para cada rol hijo
    //****************************************************************************
    public static function obtenerMenuIniHijo($menu_idH, $rolePermiso) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spPrimerMenuIngresoHijo(?,?);');
        $consulta->bindParam(1, $menu_idH, PDO::PARAM_STR);
        $consulta->bindParam(2, $rolePermiso, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //funcion que obtiene los menus padres para crear el menu segun el rol
    //****************************************************************************
    public static function buscarMenusPadres($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedMenuPadre(?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //funcion que obtiene los menus hijos para crear el menu segun el rol y el menu padre
    //****************************************************************************
    public static function buscarMenusHijos($parent, $role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedMenuHijo(?,?);');
        $consulta->bindParam(1, $parent, PDO::PARAM_STR);
        $consulta->bindParam(2, $role_id, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que verifica si el menu padre se muestra
    //****************************************************************************
    public static function contarSubmenuActivo($parent, $role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spContarSubmenus(?,?);');
        $consulta->bindParam(1, $parent, PDO::PARAM_STR);
        $consulta->bindParam(2, $role_id, PDO::PARAM_STR);
        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
    //****************************************************************************
    //funcion que obtiene los menus
    //****************************************************************************
    public static function ObternerMenus() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spselectedmenus();');
        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //funcion que obtiene los menus asignados a cada rol para visualizacion
    //****************************************************************************
    public static function ObternerMenusAsignadosRol($role_id,$menu_idH) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedMenuAsignadoRol(?,?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(2, $menu_idH, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
    //****************************************************************************
    //Funcion que agrega el nuevo 
    //****************************************************************************
    public static function insertUserRol($idusuario, $role_id, $IstatusId) {

        $strRegisteredUser = $_SESSION['user'];
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spinsertuserrol(?,?,?,?,?,?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->bindParam(2, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
        $consulta->bindParam(4, $IstatusId, PDO::PARAM_STR);
        $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que actualiza el estado del registro en 1
    //****************************************************************************
    public static function updateUserRol($idusuario, $role_id, $IstatusId) {

        $strRegisteredUser = $_SESSION['user'];
        $dtModifiedDate = date("Ymd");

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spupdateuserrol(?,?,?,?,?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_STR);
        $consulta->bindParam(2, $role_id, PDO::PARAM_STR);
        $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
        $consulta->bindParam(4, $IstatusId, PDO::PARAM_STR);
        $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

        $consulta->execute();
        if (!$consulta) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que coloca el registro user x rol en estado 3//
    //****************************************************************************
    public function deleteUserRol($idusuario) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spDeletedRolUser(?,?);');
        $consulta->bindParam(1, $idusuario, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }
    //Function que guarda en la tabla audit
    public function guardarAuditoria($vStrProcedimiento) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        /* Inserta */ {

            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento; //'procedimiento';
            $strRegisteredUser = $_SESSION['user'];
            $dtFechaRegistro = date("Ymd");

            $consulta = $conexion->prepare('call spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtFechaRegistro, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdPais = $conexion->lastInsertId();
        }
        $conexion = null;
    }

    //****************************************************************************
    //Funcion que obtiene el ultimo rol insertado
    //****************************************************************************
    public static function getLastRolId() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from administracion.spgetlastrolid();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
