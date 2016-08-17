<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- jQuery -->
        <script src="../../../sitemedia/js/jquery.js"></script>
        <script>
            $(document).ready(function () {
                var _MSJ11 = "Por motivos de compatibilidad e integridad de la información, nos hemos visto forzados a inhabilitar la navegacion con Internet Explorer.";
                var _MSJ12 = "Para que la aplicacion Anallizo funcione correctamente, descarga e instala el navegador Google Chrome desde el siguiente sitio: <a href=\"https://www.google.es/chrome/\" >Sitio de Google</a>";
                if ((navigator.userAgent.indexOf('MSIE') != -1) || (navigator.userAgent.indexOf('rv:11') != -1)) {
                    document.getElementById('resetDiv').style.display = 'none';
                    document.getElementById('LoginDiv').innerHTML = ("<h2>" + _MSJ11 + "</h2>" + "<h3>" + _MSJ12 + "</h3>");
                    return;
                    //ieOn = true;
                } //else
                //ieOn = false;
            });
        </script>    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Miido - Analiizo v2.0</title>

        <!-- Bootstrap Core CSS -->
        <link href="../../../sitemedia/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="../../../sitemedia/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="../../../sitemedia/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../../../sitemedia/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <?php
    @session_start();
    echo '<div id="resetDiv">';
    echo 'Cambiar contraseña.';
    echo '<a href="../controlador/resetpassword.php">ResetPassword</a>';
    echo '</div>';
    //die($vTmpLogin);
    if ($vTmpLogin == 1) {
        if (count($TablaUsuarioPassword) > 0){
            foreach ($TablaUsuarioPassword as $item):
                $_SESSION['userid'] = $item['idusuarios'];
                $_SESSION['CREDENTIALS']=$item['idusuarios'];
                $idusuario = $item['idusuarios'];
                //die();
                require_once "../../menu/modelo/classroles.php";
                $rol = Roles::rolUserxId($idusuario);
                //print_r($rol); die();
                foreach ($rol as $item2) {
                    $rolePermiso = $item2['role_id']; 
                    //die();
                    $permiso = Roles::searchById($rolePermiso);
                    //print_r($permiso);
                    //die();
                    if ($permiso['consult'] == 1) {
                        $menuIniP = Login::obtenerMenuIni($rolePermiso);
                        //print_r($menuIniP);
                        //die();
                        foreach ($menuIniP as $key1) {
                            $mPadre = $key1['menu_id'];
                            //echo $mPadre = $key1['menu_name'];
                            $key1['menu_name'] = str_replace(" ", "",$key1['menu_name']);
                            //die();
                        }
                        
                        $menuIni = Roles::obtenerMenuIniHijo($mPadre, $rolePermiso);                        
                        foreach ($menuIni as $key) {
                            $key['page_link'] = trim($key['page_link']);
                            
                            $carpeta = str_replace(".php", "",$key['page_link']);
                            //echo '../../'.$key1["menu_name"].'/'.$carpeta.'/controlador/'.$key["page_link"].'><i class="fa fa-table fa-fw"></i>'.$key["page_link"];
                            //die();
                            header('location: ../../../'.$key1["menu_name"].'/'.$carpeta.'/controlador/'.$key["page_link"]);  
                        }
                        
                    } else {
                        require_once 'sitemedia/html/errorpermiso.php';
                    }
                }
                $_SESSION['userId'] = $item['strlogin'] . date("Ymd") . date("His");

                $_SESSION['user'] = $item['strlogin'];

                $_SESSION["ultimoAcceso"] = date("Y-m-d H:i:s");
                $signinDate = date("YmdHis");
                $id_usuario = $item['idusuarios'];
            endforeach;
        }else{
            ?>
            <tr class="odd gradeX">
                <td><?php echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>'; ?></td>
            </tr>
        <?php
        }
    }
    ?>

    <body style="background-image: url('/analiizo/images/background_02.png'); background-repeat: no-repeat; background-position: right top; background-size: 100% auto;">

        <div class="container">
            <!--<div class="logo" style="text-align: center; padding-top: 60px; "><img src="images/analiizo_logo.png"></div>-->
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default" id="LoginDiv">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ingreso al Sistema</h3>
                        </div>
                        <div class="panel-body">
                            <form name="loginForm" id="loginForm" action="login.php" method="post">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Usuario" name="user" id="user" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña" name="password" id="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Recordarme
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-lg btn-success btn-block">Aceptar</button>    
                            </form>
                        </div>
                    </div>
                    <div><span style="display: block; text-align: center; font-size: 10pt; color: rgb(149, 149, 149); font-style: italic;">Analiizo V2.0 - Miido 2015</span></div>                    
                </div>
            </div>

        </div>

        <!-- Bootstrap Core JavaScript -->
        <script src="../../../sitemedia/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../../../sitemedia/js/plugins/metisMenu/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../../../sitemedia/js/sb-admin-2.js"></script>

    </body>

</html>
