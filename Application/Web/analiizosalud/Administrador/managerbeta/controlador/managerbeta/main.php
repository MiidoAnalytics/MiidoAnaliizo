<body>
	<script>var action=1;</script>
	<div id="d_main"></div>
	<div class="main_header">
		<img id="im_banner" src="src/images/png/analiizoLogo.png">
	</div>
	<div class="d_menu">
		<?php require_once('vistas/menu.php'); ?>
	</div>
	<div class="d_content">
		<fieldset>
			<legend>
				<?php
					echo(constant('leg_tit_'.$hiddenField));
				?>
			</legend>
			<?php
				if (isset($request)) {
					require_once("vistas/".$request.'.php');
				} else {
					echo "<p>Estamos trabajando para mejorar el servicio...</p>";
				}
			?>
		</fieldset>
	</div>
	<div class="hide" id="rmenu">
		<table class="table_context_menu" cellspacing="1">
			<tr>
				<td id="td_context_edit">
					Modificar
				</td>
			</tr>
			<tr>
				<td id="td_context_delete">
					Eliminar
				</td>
			</tr>
			<tr>
				<td id="td_context_select">
					Seleccionar
				</td>
			</tr>
		</table>
    </div>
    <div class="d_alert_dialog_bk" id="alert_dialog_bk"></div>
    <div class="d_alert_dialog_full" id="alert_dialog_fs">
    	<fieldset id="f_alert_dialog">
    		<legend id="l_alert_dialog_tittle1"></legend>
    		<div class="d_left_resumer" id="d_left_alert_fs">
            </div>
            <div class="d_form_row" id="r_alert_dialog_content">
	            <form>
		            <div class="d_rigth_form_container d_width_55">
		                <div class="d_form2_label">
		                    <span><script >dw(_OPCION)</script>: <r>*</r></span>
		                </div>
		                <div class="d_form2_field">
		                    <input type="text" id="it_name_option"  required />
		                </div>
		            </div>
		            <div class="d_alert_dialog_submit">
		                <input type="submit" id="bt_append_option"></input>
		            </div>
	            </form>
	            <div>
                    <div class="login_form d_full_fill">
                        <fieldset>
                            <legend><script>dw(_EVENTOS)</script></legend>
                            <div class="handler_lister" id="d_handler_lister">
                                <span id="s_options_lister">
                                    <script>dw(inf_agr_opc)</script>
                                </span>
                                <table id="t_option_lister" width="100%" cellspacing="0">
                                </table>
                            </div>
                            <div class="d_event_submit">
                                <input type="button" id="b_option_saver" />
                            </div>
                        </fieldset>
                    </div>
	            </div>
            </div>
    	</fieldset>
    	<div class="d_alert_dialog_buttons">
            <input type="submit" id="bt_alert_dialog_cancel"></input>
        </div>
    </div>
</body>