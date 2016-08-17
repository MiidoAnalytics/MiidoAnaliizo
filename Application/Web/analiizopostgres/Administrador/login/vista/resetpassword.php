<!DOCTYPE html>
<html lang="en">

    <head>

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

        <script>

            function showUsuario() {

                t1 = document.getElementById('user').value;
                t2 = document.getElementById('passwordOld').value;
                t3 = document.getElementById('passwordNew').value;
                t4 = document.getElementById('confirmePassword').value;

                if (t1 == 0)
                {
                    window.location = ("resetpassword.php");

                }
                else
                {
                    window.location = ("resetpassword.php?user_id=" + t1 + "&passwordOld_id=" + t2 + "&passwordNew_id=" + t3 + "&passwordConfirm_id=" + t4);
                }
            }

        </script>

    </head>

    <?php
    @session_start();

    if ($vTmpLogin == 2) {
        ?>
        <tr class="odd gradeX">
            <td><?php echo '<div class="error">La contraseña nueva y su confirmación no coinciden, intente nuevamente.</div>'; ?></td>
        </tr>
        <?php
    }

    if ($vTmpLogin == 0) {

        header("location:../controlador/login.php");
    }
    ?>

    <body style="background-image: url('/analiizo/images/background_02.png'); background-repeat: no-repeat; background-position: right top; background-size: 100% auto;">

        <div class="container">
            <div class="logo" style="text-align: center; padding-top: 60px; "><img src="images/analiizo_logo.png"></div>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Cambiar Contraseña</h3>
                        </div>
                        <div class="panel-body">


                            <div class="form-group">
                                <input class="form-control" placeholder="Usuario" name="user" id="user" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Contraseña Anterior" name="passwordOld" id="passwordOld" type="password" value="">
                            </div>                            
                            <div class="form-group">
                                <input class="form-control" placeholder="Contraseña Nueva" name="passwordNew" id="passwordNew" type="password" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Confirme Contraseña" name="confirmePassword" id="confirmePassword" type="password" value="">
                            </div>

                            <button type="button" class="btn btn-lg btn-success btn-block" onclick="showUsuario()">Aceptar</button>    

                        </div>
                    </div>
                    <div><span style="display: block; text-align: center; font-size: 10pt; color: rgb(149, 149, 149); font-style: italic;">Analiizo V2.0 - Miido 2015</span></div>
                </div>
            </div>

        </div>

        <!-- jQuery -->
        <script src="../../../sitemedia/js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../../../sitemedia/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../../../sitemedia/js/plugins/metisMenu/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../../../sitemedia/js/sb-admin-2.js"></script>

    </body>

</html>
