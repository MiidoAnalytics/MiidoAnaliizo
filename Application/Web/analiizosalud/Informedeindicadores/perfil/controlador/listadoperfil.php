<?php
define('CONTROLADOR', TRUE);
require_once '../modelo/classperfil.php';
    $project = (isset($_POST['project'])) ? $_POST['project'] : null;
    $pollid = (isset($_POST['pollid'])) ? $_POST['pollid'] : null;

    $project1 = (isset($_POST['project1'])) ? $_POST['project1'] : null;
    $pollid1 = (isset($_POST['pollid1'])) ? $_POST['pollid1'] : null;

	$perfil = (isset($_POST['perfil'])) ? $_POST['perfil'] : null;
    $tipoIdentidad = (isset($_POST['tipoIdentidad'])) ? $_POST['tipoIdentidad'] : null;
    //echo $tipoIdentidad = strtolower($tipoIdentidad);

    $primerNombre = (isset($_POST['primerNombre'])) ? $_POST['primerNombre'] : "%";
    $segundoNombre = (isset($_POST['segundoNombre'])) ? $_POST['segundoNombre'] : "%";
    $documento = (isset($_POST['documento'])) ? $_POST['documento'] : "%";
    $primerApellido = (isset($_POST['primerApellido'])) ? $_POST['primerApellido'] : "%";
    $segundoApellido = (isset($_POST['segundoApellido'])) ? $_POST['segundoApellido'] : "%";

    $valorBuscar = (isset($_POST['valorBuscar'])) ? $_POST['valorBuscar'] : "%";
    $idFamilia = (isset($_POST['idFamilia'])) ? $_POST['idFamilia'] : "%";

    if ($primerNombre == null || $primerNombre == '')  {
        $primerNombre = "%";
    }else{
        $primerNombre = strtoupper($primerNombre);
        $primerNombre = "%".$primerNombre."%";
    }

    if ($segundoNombre == null || $segundoNombre == '')  {
        $segundoNombre = "%";
    }else{
        $segundoNombre = strtoupper($segundoNombre);
        $segundoNombre = "%".$segundoNombre."%";
    }

    if ($primerApellido == null || $primerApellido == '')  {
        $primerApellido = "%";
    }else{
        $primerApellido = strtoupper($primerApellido);
        $primerApellido = "%".$primerApellido."%";
    }

    if ($segundoApellido == null || $segundoApellido == '')  {
        $segundoApellido = "%";
    }else{
        $segundoApellido = strtoupper($segundoApellido);
        $segundoApellido = "%".$segundoApellido."%";
    }

    if ($valorBuscar == null || $valorBuscar == '')  {
        $valorBuscar = "%";
    }else{
        $valorBuscar = strtoupper($valorBuscar);
        $valorBuscar = "%".$valorBuscar."%"; 
    }

    $perfilPersona = "";
    if($perfil == 1) {
    	if($tipoIdentidad && $documento){
    		if($primerNombre || $segundoNombre || $primerApellido || $segundoApellido){
                $perfilPersona = Perfil::obtenerPerfilPersonaDocName($project, $pollid, $tipoIdentidad, $documento, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido);
                ?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Tipo Doc</th>
                                            <th>Num Doc</th>
                                            <th>Primer Nombre</th>
                                            <th>Segundo Nombre</th>
                                            <th>Primer Apellido</th>
                                            <th>Segundo Apellido</th>
                                            <th>ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($perfilPersona) > 0): ?>
                                            <?php foreach ($perfilPersona as $item): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo strtoupper($item['tipo_doc']); ?></td>
                                                    <td><?php echo $item['num_identificacion']; ?></td>
                                                    <td><?php echo $item['primer_nombre']; ?></td>
                                                    <td><?php echo $item['segundo_nombre']; ?></td>
                                                    <td><?php echo $item['primer_apellido']; ?></td>
                                                    <td><?php echo $item['segundo_apellido']; ?></td>
                                                    <td><a href="verperfil.php?tipoDoc=<?php echo $item['tipo_doc'];?>&numDoc=<?php echo $item['num_identificacion'];?>&proyecto=<?php echo $project; ?>&encuesta=<?php echo $pollid; ?>" target="_blank"> Ver </a></td> 
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p> No hay resultados para mostrar </p>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
    		}else{
    			$perfilPersona = Perfil::obtenerPerfilPersonaDocumento($project, $pollid,$tipoIdentidad, $documento);
    		?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado
                        </div>
            			<div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Tipo Doc</th>
                                            <th>Num Doc</th>
                                            <th>Primer Nombre</th>
                                            <th>Segundo Nombre</th>
                                            <th>Primer Apellido</th>
                                            <th>Segundo Apellido</th>
                                            <th>ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($perfilPersona) > 0): ?>
                                            <?php foreach ($perfilPersona as $item): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo strtoupper($item['tipo_doc']); ?></td>
                                                    <td><?php echo $item['num_identificacion']; ?></td>
                                                    <td><?php echo $item['primer_nombre']; ?></td>
                                                    <td><?php echo $item['segundo_nombre']; ?></td>
                                                    <td><?php echo $item['primer_apellido']; ?></td>
                                                    <td><?php echo $item['segundo_apellido']; ?></td>
                                                    <td><a href="verperfil.php?tipoDoc=<?php echo $item['tipo_doc'];?>&numDoc=<?php echo $item['num_identificacion'];?>&proyecto=<?php echo $project; ?>&encuesta=<?php echo $pollid; ?>" target="_blank"> Ver </a></td> 
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p> No hay resultados para mostrar </p>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
    		}
    	}else{
            $perfilPersona = Perfil::obtenerPerfilPersonaNombres($project, $pollid, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido);
        ?>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Listado
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Tipo Doc</th>
                                        <th>Num Doc</th>
                                        <th>Primer Nombre</th>
                                        <th>Segundo Nombre</th>
                                        <th>Primer Apellido</th>
                                        <th>Segundo Apellido</th>
                                        <th>ver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($perfilPersona) > 0): ?>
                                        <?php foreach ($perfilPersona as $item): ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo strtoupper($item['tipo_doc']); ?></td>
                                                <td><?php echo $item['num_identificacion']; ?></td>
                                                <td><?php echo $item['primer_nombre']; ?></td>
                                                <td><?php echo $item['segundo_nombre']; ?></td>
                                                <td><?php echo $item['primer_apellido']; ?></td>
                                                <td><?php echo $item['segundo_apellido']; ?></td>
                                                <td><a href="verperfil.php?tipoDoc=<?php echo $item['tipo_doc'];?>&numDoc=<?php echo $item['num_identificacion'];?>&proyecto=<?php echo $project; ?>&encuesta=<?php echo $pollid; ?>" target="_blank"> Ver </a></td> 
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p> No hay resultados para mostrar </p>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } 
    }else{
        if ($perfil == 2) {
            $perfilFamilia = Perfil::obtenerFamiliasListadoxNnombre($project1, $pollid1, $valorBuscar);
            ?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Cod Casa</th>
                                            <th>Nombre Familia</th>
                                            <th>Departamento</th>
                                            <th>Municipio</th>
                                            <th>Fecha Encuesta</th>
                                            <th>ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($perfilFamilia) > 0): ?>
                                            <?php foreach ($perfilFamilia as $item): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $item['cod_familia']; ?></td>
                                                    <td><?php echo $item['nombre_familia']; ?></td>
                                                    <td><?php echo $item['departamento']; ?></td>
                                                    <td><?php echo $item['municipio']; ?></td>
                                                    <td><?php echo $item['fecha_encuesta']; ?></td>
                                                    <td><a href="verperfilfamilia.php?idcasa=<?php echo $item['cod_familia'];?>&proyecto=<?php echo $project1; ?>&encuesta=<?php echo $pollid1; ?>" target="_blank"> Ver </a></td> 
                                                </tr>
                                            <?php endforeach; 
                                            setcookie("desIni","1",time()+3600); ?>
                                        <?php else: ?>
                                            <p> No hay resultados para mostrar </p>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }else {
        }
    }
?>