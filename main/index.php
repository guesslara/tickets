<?php
session_start();
//print_r($_SESSION);
//header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IQe. Sisco - &Oacute;rdenes de Servicio</title>
<link href="../css/css_tickets.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>
<?php  include("../js/js_tickets.php");  ?>
<script type="text/javascript">
$(document).ready(function(){
	$("body").css("background-image","url()");
	$("#txt_usuario").focus();	
});
</script>

</head>
<body>
<div id="transparente"></div>
<div id="login">
	<div id="login_01">IQe. Sisco - &Oacute;rdenes de Servicio</div>
    <div id="login_02"><br /><img src="../img/kuser.png" id="img_login_01" /></div>
    <div id="login_03">
    	<p>Usuario:</p>
        <p align="center"><label><input type="text" id="txt_usuario" style="text-align:center;" <!--onkeyup="tecla(0,event)"--> /></label></p>
	<p>Password:</p>
	<p align="center"><label><input type="password" id="txt_nde" style="text-align:center;" <!--onkeyup="tecla(1,event)"--> /></label></p>
        <p id="p_login_mensaje" style=" display:none; color:#F00; font-size:12px;">datos incorrectos</p>
        <p><a href="#" class="link_02" onclick="login()">entrar</a></p>
    </div>
</div>
<div id="d00">
	<div id="d01">
		<div id="d11">
			<div id="d111" class="tabx"><div class="link_01">Administracion</div></div>
			<div id="d112" class="tabx"><div class="link_01">Tickets</div></div>
			<div id="d113" class="tabx"><div class="link_01">Consultas</div></div>
			<div id="d114" class="tabx"><div class="link_01">Sistema</div></div>
		</div>
		<div id="d12">
			<div id="d121"></div>
		</div>
	</div>
    <div id="d02">
		<div id="d21"></div>
		<div id="d22">
			<div id="d221"></div>
		</div>
		<div id="d23"></div>
	</div>
	<br /><div id="d03"></div>
</div>
</body>
</html>