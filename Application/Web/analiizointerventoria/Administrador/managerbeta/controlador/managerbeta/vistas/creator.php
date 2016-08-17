<link rel="stylesheet" type="text/css" href="src/css/tab.css">

<div class="tabs">
    <div class="tab">
        <input type="radio" id="r_pollname" name="tab-group-1" checked>
        <label for="r_pollname"><script>dw(_NOMBRE+" "+_ENCUESTA);</script></label>
        <div class="content">
            <div class="d_full_executor">
                <div class="d_info">
                    <span>
                        <script>
                            dw(inf_cam_ast_obl);
                        </script>
                    </span>
                </div>
                <form id="f_nGrupo">
                    <div class="horizontal_row">
                        <span><script >dw(_PROYECTO)</script>: <r>*</r></span>
                        <select  id="it_nProject" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="horizontal_row">
                        <span><script >dw(_NOMBRE+" "+_DE+" "+_LA+" "+_ENCUESTA)</script>: <r>*</r></span>
                        <input type="text" id="it_nPoll" required />
                    </div>
                    <div class="horizontal_row">
                        <span><script >dw(_DESCRIPCION+" "+_DE+" "+_LA+" "+_ENCUESTA)</script>:  </span>
                        <input type="text" id="it_desPoll" />
                    </div>
                    <div class="d_submit_center">
                        <input type="submit" id="bt_save_pollname"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tab">
        <input type="radio" id="r_groups" name="tab-group-1">
        <label for="r_groups"><script>dw(_CREAR+" "+_GRUPO);</script></label>
        <div class="content">
            <div class="d_left_resumer" id="left_group_resumer">
                <table id="left_resumer_table_g" class="d_left_resumer_table" cellspacing="3">
                </table>
            </div>
            <div class="d_rigth_executor">
                <div class="d_info">
                    <span>
                        <script>
                            dw(inf_cam_ast_obl);
                        </script>
                    </span>
                </div>
                <form id="f_nGrupo">
                    <div class="horizontal_row">
                        <span><script >dw(_NOMBRE+" "+_DEL+" "+_GRUPO)</script>: <r>*</r></span>
                        <input type="text" id="it_nGrupo" required />
                    </div>
                    <div class="horizontal_row">
                        <span><script >dw(_TITULO+" "+_DEL+" "+_GRUPO)</script>:  </span>
                        <input type="text" id="it_tGrupo" />
                    </div>
                    <div class="d_submit">
                        <input type="submit" id="bt_save_group"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="tab">
        <input type="radio" id="r_fields" name="tab-group-1" >
        <label for="r_fields">
        <script>dw(_CREAR+" "+_CAMPO);</script></label>
        <div class="content">
            <div id="fields_selector_actions">
                <div class="d_left_resumer" id="left_fields_resumer">
                    <span><script>dw(_ELEGIR+" "+_UN+" "+_GRUPO)</script></span>
                    <table id="left_resumer_table_f" class="d_left_resumer_table" cellspacing="3">
                    </table>
                </div>
                <div class="d_right_creator_editor" id="right_creator_editor">
                </div>
            </div>
        </div>
    </div>
    <div class="tab">
        <input type="radio" id="r_listener" name="tab-group-1">
        <label for="r_listener"><script>dw(_CREAR+" "+_RELACION);</script></label>
        <div class="content">
            <div id="events_selector_actions">
                <div class="d_left_resumer" id="left_group_relation_resumer">
                    <span><script>dw(_ELEGIR+" "+_UN+" "+_GRUPO)</script></span>
                    <table id="left_listener_table_f" class="d_left_resumer_table" cellspacing="3">
                    </table>
                </div>
                <div class="d_left_resumer d_margin_events" id="left_fields_relation_resumer">
                    <span id="s_event_group_name"><script>dw(inf_pri_sel_gru);</script></span>
                    <table id="d_center_event_resumer_table" class="d_left_resumer_table" cellspacing="3">
                    </table>
                </div>
                <div class="d_left_resumer d_margin_events" id="right_group_relation_resumer">
                    <span><script>dw(_ELEGIR+" "+_UN+" "+_GRUPO)</script></span>
                    <table id="right_listener_table_f" class="d_left_resumer_table" cellspacing="3">
                    </table>
                </div>
            </div>
            <div id="events_creator_editor" class="d_rigth_executor d_full_executor">
                <div class="d_left_resumer" id="left_group_relation_resumer">
                    <span><script>dw(_EVENTOS+" "+_DISPONIBLES)</script></span>
                    <table id="left_events_table_f" class="d_left_resumer_table" cellspacing="3">
                    </table>
                </div>
                <div class="d_rigth_executor">
                    <span id="l_relation_event_description"></span>
                    <form id="f_nEvent">
                        <div class="d_left_form_container">
                            <div class="d_form_row">
                                <div class="d_form2_label">
                                    <span><script >dw(_TIPO)</script>: <r>*</r></span>
                                </div>
                                <div class="d_form2_field">
                                    <select id="se_handler_type" required>
                                        <option value="">-</option>
                                        <option value=">">Mayor que</option>
                                        <option value="<">Menor que</option>
                                        <option value="=">Igual a</option>
                                        <option value="!=">Diferente a</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d_rigth_form_container">
                            <div class="d_form_row">
                                <div class="d_form2_label">
                                    <span><script >dw(_VALOR)</script>: <r>*</r></span>
                                </div>
                                <div class="d_form2_field">
                                    <input type="text" id="it_handler_value"  required />
                                </div>
                            </div>
                        </div>
                        <div class="d_form_submit extra_padding_botton">
                            <input type="submit" id="bt_append_handler"></input>
                        </div>
                    </form>
                    <div class="login_form d_full_fill">
                        <fieldset>
                            <legend><script>dw(_EVENTOS)</script></legend>
                            <div class="handler_lister" id="d_handler_lister">
                                <span id="s_handler_lister">
                                    <script>dw(inf_agr_con)</script>
                                </span>
                                <table id="t_handler_lister" width="100%" cellspacing="0">
                                </table>
                            </div>
                            <div class="d_event_submit">
                                <input type="button" id="b_event_saver" value="guardar" />
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab">
        <input type="radio" id="r_finish" name="tab-group-1">
        <label for="r_finish"><script>dw(_FINALIZAR);</script></label>
        <div class="content">
            <div class="d_left_resumer d_mapper_resumer" id="d_left_mapper"></div>
            <div class="d_rigth_actions">
                <div>
                    <span id="l_maker"></span>
                </div>
                <input type="button" id="makeGeneralBlok" style="margin-left: 4em"/>
                <input type="button" id="makeSubBlock" style="margin-left: 4em"/>
                <input type="button" id="closePoll" style="margin-left: 4em"/>
            </div>
        </div>
    </div>    
</div>
<script>action = 8;</script>
