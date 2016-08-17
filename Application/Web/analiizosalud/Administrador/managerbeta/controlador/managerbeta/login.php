<body>
	<div class="main" id="d_main">
		<div class="login_header">
			<img src="src/images/png/analiizoLogo.png">
		</div>
		<div class="login_form">
			<fieldset>
				<legend id="login_legend"><script >dw(_INICIAR_SESION)</script></legend>
				<form id="f_login">
					<div class="form_1_row">
						<div class="horizontal_row">
							<label><script >dw(_NOMBRE_USUARIO)</script></label>
							<input type="text" id="it_usuario" required />
						</div>
						<div class="horizontal_row">
							<label><script >dw(_CONTRASENA_USUARIO)</script></label>
							<input type="password" id="ip_contrasena" required />
						</div>
						<div id="d_notice">
							<p id="p_notice"></p>
							<img id="i_loading" src="src/images/gif/loading2.gif" height="24">
						</div>
						<div class="d_submit">
							<input type="submit" id="bt_inic" />
						</div>
					</div>
				</form>
			</fieldset>
		</div>
	</div>
	<script>var action=0;</script>
</body>