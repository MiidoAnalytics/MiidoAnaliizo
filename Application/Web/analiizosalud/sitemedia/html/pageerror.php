<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title> Página error </title>
        
        <?php include("scriptencabezado.php"); ?>
        
    </head>
    <body>
        
        <div class="container">
        
        <h2>Administración - Página error</h2>
        
        <form class="form-horizontal" role="form">            
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Message
                        </div>                        
                        <div class="panel-body">
                            <div class="alert alert-danger">
                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="../../../index.php" class="alert-link">Login</a>.
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>      
      
    </div>
      
   <?php include("scriptpie.php"); ?>

  </body>
     
    
</html>