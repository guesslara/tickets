<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Administraci&oacute;n de Tecnolog&iacute;as de Informaci&oacute;n.</title>
<link href="../css/solicitud_servicio.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../js/jquery.js"></script>
<script language="javascript" src="../js/solicitud_servicio.js"></script>
</head>
<body>
<div id="all">
	<div id="encabezado">
		<div id="encabezado_logo">SATI</div>
		<div id="encabezado_titulo">Sistema de Administraci&oacute;n de Tecnolog&iacute;as de Informaci&oacute;n. </div>
		<div id="encabezado_usuario">Usuario: Administrador del Sistema</div>
	</div>
	
	<div id="cuerpo">
		<div id="cuerpo_fondo">&nbsp;</div>
		<div id="cuerpo_menu">
			<div>
				<div class="menu_items" id="menu_A" onclick="muestra_menu_vinculos('A')">Solicitud de Servicio </div>
				<div id="menu_A_items" class="menu_items_vinculos">
					<a href="#" onclick="ajax('cuerpo_contenidoA_2','accion=nueva_solicitud');">Nueva</a><br />
					<a href="#">Ver mis solicitudes</a><br />
					<a href="#">Seguimiento</a><br />
					<a href="#">Otros</a><br />
				</div>
			</div>
			<div>
				<div class="menu_items" id="menu_D" onclick="muestra_menu_vinculos('D')">Admin</div>
				<div id="menu_D_items" class="menu_items_vinculos">
					<a href="#" onclick="ajax('cuerpo_contenidoA_2','accion=ver_usuarios0'); ajax('cuerpo_contenidoB_2','accion=ver_usuarios1')">Usuarios</a><br />
					<a href="#" onclick="ajax('cuerpo_contenidoA_2','accion=ver_catalogos0'); ajax('cuerpo_contenidoB_2','accion=ver_catalogos1')">Cat&aacute;logos</a><br />
					<a href="#">Otros</a><br />
				</div>
			</div>
			<div>
				<div class="menu_items" id="menu_E" onclick="muestra_menu_vinculos('E')">Reportes</div>
				<div id="menu_E_items" class="menu_items_vinculos">
					<a href="#">Inventarios</a><br />
					<a href="#">Asignaci&oacute;n</a><br />
					<a href="#">Prog. Mantto.</a><br />
					<a href="#">Prog. Respaldo</a><br />
					<a href="#">Sol. Servicios</a><br />
				</div>
			</div>												
		</div>
		<div id="cuerpo_contenido">
			<div id="cuerpo_contenidoA">
				<div id="cuerpo_contenidoA_1">&nbsp;<!--<a id="vin_contraer_A" href="javascript:contraer_capa('A')" title="Contraer este capa.">&lt;-</a>//--></div>
				<div id="cuerpo_contenidoA_2">
					<div id="div_login">
						<div id="div_login0"><img src="../img/usuarios.png" /></div>
						<div id="div_login1">
							<br /><br /><table align="center">
							<tr>
								<td class="campo_vertical">Usuario:</td>
								<td><input type="text" /></td>
							</tr>
							<tr>
								<td class="campo_vertical">Contrase&ntilde;a:</td>
								<td><input type="text" /></td>
							</tr>
							<tr>
							  <td height="29" colspan="2" align="right"><input type="button" value="Entrar" /></td>
							</tr>														
							</table>
						</div>
						<div id="div_login2"></div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div id="pie">&copy; 2009  - IQ Electronics International.</div>
</div>
</body>
</html>
