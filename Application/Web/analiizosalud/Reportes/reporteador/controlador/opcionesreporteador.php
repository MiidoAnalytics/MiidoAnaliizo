<?php
    define('CONTROLADOR', TRUE);

    $column = $_POST['column'];
    $proyecto = $_POST['proyecto'];
    $encuesta = $_POST['encuesta'];

    if ($column && $proyecto && $encuesta) {
        require_once '../../../ParametrosGenerales/listreportsadm/modelo/classreports.php';
        $columnasbd = Report::obtenerColumnasBD($proyecto, $encuesta);
        for ($i=0; $i < count($columnasbd); $i++){  
            $tipodato = $columnasbd[$i]['tipo'];
            $len = $columnasbd[$i]['len'];
            if($columnasbd[$i]['pregunta'] == $column):
                $idpre = $columnasbd[$i]['idpre'];
                $handler = $columnasbd[$i]['eventoid'];
                switch ($tipodato) {
                    case 'rg':
                        if ($handler > 0) {
                            $opciones = Report::obtenerOpcionesColumna($proyecto, $encuesta, $idpre);
                            $tipoopt = $opciones[0]['tipo'];
                            if ($tipoopt != 'cb') {
                                $opcionesPre = Report::obtenerOpcionesColumna3($proyecto, $encuesta, $idpre);
                                if (count($opcionesPre) > 3) {
                                    ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                     
                                        <?php  
                                        foreach ($opcionesPre as $item2):
                                            $opt1 = $item2['optionsc'];
                                            $opt = Report::quitarComillas($opt1);
                                            if ($opt == '-') {
                                            }else{
                                                ?>                                     
                                                <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                <?php
                                            }
                                        endforeach;
                                        ?> 
                                    </select>
                                    <?php
                                }else{ 
                                    ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                                        <?php  
                                        foreach ($opcionesPre as $item2):
                                            $opt1 = $item2['optionsc'];
                                            $opt = Report::quitarComillas($opt1);
                                            if ($opt == '-') {
                                            }else{
                                                ?>                                     
                                                <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                <?php
                                            }
                                        endforeach;
                                        ?> 
                                    </select>
                                    <?php
                                }
                            }else{
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>
                                    <?php
                                        foreach ($opciones as $item):
                                    ?>                                     
                                            <option value="<?php echo $item['pregunta']; ?>"><?php echo $item['descripcion']; ?></option>  
                                    <?php
                                        endforeach;
                                    ?> 
                                </select>
                            <?php
                            }
                        }else{
                            $opciones2 = Report::obtenerOpcionesColumna3($proyecto, $encuesta, $idpre);
                            if (count($opciones2) > 3) {
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                   
                                    <?php
                                        foreach ($opciones2 as $item):
                                            $opt1 = $item['optionsc'];
                                            $opt = Report::quitarComillas($opt1);
                                            if ($opt == '-') {
                                            }else{
                                                ?>                                     
                                                        <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                <?php
                                            }
                                        endforeach;
                                    ?> 
                                </select>
                                <?php
                            }else{
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                                    <?php
                                        foreach ($opciones2 as $item):
                                            $opt1 = $item['optionsc'];
                                            $opt = Report::quitarComillas($opt1);
                                            if ($opt == '-') {
                                            }else{
                                                ?>                                     
                                                        <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                <?php
                                            }
                                        endforeach;
                                    ?> 
                                </select>
                                <?php
                            }
                        }                         
                        break;
                    case 'sp':
                        $opciones3 = Report::obtenerOpcionesColumna3($proyecto, $encuesta, $idpre);
                        if (count($opciones3) > 3) {
                            ?>
                            <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>
                                <?php
                                    foreach ($opciones3 as $item3):
                                        $opt3 = $item3['optionsc'];
                                        $opt = Report::quitarComillas($opt3);
                                        if ($opt == '-') {
                                        }else{
                                            ?>                                     
                                                    <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                            <?php
                                        }
                                    endforeach;
                                ?> 
                            </select>
                            <?php
                        }else{
                            ?>
                            <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                                <?php
                                    foreach ($opciones3 as $item3):
                                        $opt3 = $item3['optionsc'];
                                        $opt = Report::quitarComillas($opt3);
                                        if ($opt == '-') {
                                        }else{
                                            ?>                                     
                                                    <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                            <?php
                                        }
                                    endforeach;
                                ?> 
                            </select>
                            <?php 
                        }                      
                        break;
                    case 'dp':
                        ?>
                        <label for="since" class="col-lg-2 control-label">Desde:</label>
                        <input class='form-control' placeholder="dd/mm/aaaa" name="since_<?php echo $column; ?>" id="since_<?php echo $column; ?>" type="date" required>
                        <label for="until" class="col-lg-2 control-label">Hasta:</label>
                        <input class="form-control" placeholder="dd/mm/aaaa" name="until_<?php echo $column; ?>" id="until_<?php echo $column; ?>" type="date" required>
                        <?php
                        break;
                    case 'tf':
                        ?>
                        <label for='opS_<?php echo $column; ?>'  class="col-lg-2 control-label">Operación:</label>
                        <select name='opS_<?php echo $column; ?>' id='opS_<?php echo $column; ?>' class='form-control' onchange="crearOpciones(opS_<?php echo $column; ?>, '<?php echo $column; ?>')" required>
                            <option value="">Seleccione</option>
                            <option value="<">Menor a</option>
                            <option value="<=">Menor o igual</option>
                            <option value=">">Mayor a</option>
                            <option value=">=">Mayor o igual</option>
                            <option value="between">Entre</option>
                        </select>
                        <?php
                        break;
                    case 'tv':
                        switch ($idpre) {
                            case '123':
                                $opciones2 = Report::obtenerOpcionesColumna3($proyecto, $encuesta, $idpre);
                                if (count($opciones2) > 3) {
                                    ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                   
                                        <?php
                                            foreach ($opciones2 as $item):
                                                $opt1 = $item['optionsc'];
                                                $opt = Report::quitarComillas($opt1);
                                                if ($opt == '-') {
                                                }else{
                                                    ?>                                     
                                                            <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                    <?php
                                                }
                                            endforeach;
                                        ?> 
                                    </select>
                                    <?php
                                }else{
                                    ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                                        <?php
                                            foreach ($opciones2 as $item):
                                                $opt1 = $item['optionsc'];
                                                $opt = Report::quitarComillas($opt1);
                                                if ($opt == '-') {
                                                }else{
                                                    ?>                                     
                                                            <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>  
                                                    <?php
                                                }
                                            endforeach;
                                        ?> 
                                    </select>
                                    <?php
                                }
                                break;
                            case '145':
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                   
                                    <option value="consualcohol">Consumo de alcohol</option>
                                    <option value="consucigarrilo">Consumo de cigarrillo</option>
                                    <option value="consususpsico">Consumo de sustancias psicotrópicas</option>
                                </select>
                                <?php 
                                break;
                            case '501':
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                   
                                    <option value="serEneelectrica">Energía Eléctrica</option>
                                    <option value="serAlcantarill">Energía Alcantarillado</option>
                                    <option value="serGasNatD">Gas natural domiciliario</option>
                                    <option value="serTelefono">Servicio Teléfono</option>
                                    <option value="serRecoBasu">Recolección de basura</option>
                                    <option value="serAcueducto">Acueducto</option>
                                </select>
                                <?php 
                                break;
                            case '510':
                                ?>
                                <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>                                   
                                    <option value="preanPerro">Perros</option>
                                    <option value="preanGato">Gatos</option>
                                    <option value="preanEquino">Equinos</option>
                                    <option value="preanAves">Aves</option>
                                    <option value="preanPorcino">Porcinos</option>
                                    <option value="preanOtros">Otros Animales</option>
                                </select>
                                <?php 
                                break;
                            default:
                                # code...
                                break;
                        }                       
                        break;
                    case 'ac': 
                        switch ($columnasbd[$i]['pregunta']) {
                            case 'eps':
                                $opciones = Report::obtenerEpsReportadas($proyecto, $encuesta);
                                ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>
                                <?php
                                    foreach ($opciones as $item):
                                ?>                                     
                                        <option value="<?php echo $item['eps']; ?>"><?php echo $item['eps']; ?></option>  
                                <?php
                                    endforeach;
                                ?> 
                                    </select>
                                <?php
                                break;
                            case 'Ocupacion':
                                $opciones = Report::obtenerOcupacionesReportadas($proyecto, $encuesta);
                                ?>
                                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>
                                <?php
                                    foreach ($opciones as $item):
                                ?>                                     
                                        <option value="<?php echo $item['codocupacion']; ?>"><?php echo $item['stnameoccupation']; ?></option>  
                                <?php
                                    endforeach;
                                ?> 
                                    </select>
                                <?php
                                break;
                            default:
                                # code...
                                break;
                        }  
                        break;
                    default:
                        break;
                }
            else:                
            endif;
        }
        switch ($column) {
            case 'disCode':
                $opciones = Report::obtenerEnfermedadesReportadas($proyecto, $encuesta);
                ?>
                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                <?php
                    foreach ($opciones as $item):
                ?>                                     
                        <option value="<?php echo $item['enfermedad']; ?>"><?php echo $item['enfermedad']; ?></option>  
                <?php
                    endforeach;
                ?> 
                    </select>
                <?php
                break;
            case 'medDesc':
                $opciones = Report::obtenerMedicamentosReportadas($proyecto, $encuesta);
                ?>
                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required>
                <?php
                    foreach ($opciones as $item):
                ?>                                     
                        <option value="<?php echo $item['medicamento']; ?>"><?php echo $item['medicamento']; ?></option>  
                <?php
                    endforeach;
                ?> 
                    </select>
                <?php
                break;
            case 'pollCity':
                $opciones = Report::recuperarMunicipiosEncuestados($proyecto, $encuesta);
                ?>
                    <select name='opcionesS_<?php echo $column; ?>[]' id='opcionesS_<?php echo $column; ?>' class='form-control' required multiple>
                <?php
                    foreach ($opciones as $item):
                ?>                                     
                        <option value="<?php echo $item['codmunicipio']; ?>"><?php echo $item['nombremunicipio']; ?></option>  
                <?php
                    endforeach;
                ?> 
                    </select>
                <?php
                break;
            default:
                # code...
                break;
        }
    }
?>

