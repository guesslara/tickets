<?php 
session_start();
if (isset($_POST["u"])){
	//header("Cache-Control: no-store, no-cache, must-revalidate");
	//header("Content-Type: text/xml; charset=ISO-8859-1");
	print_r($_POST);
	$ac=$_POST["accion"];
	if ($ac=="ver_inventario0"){
		require_once("../clases/inventario.php");	
		$i1=new inventario();
		$i1->menu();	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bienvenido</title>
<script language="javascript" src="../js/jquery.js"></script>
<script language="javascript" src="../js/ajax.js"></script>
<script language="javascript">
function validar(){	
	//alert("OCULTAR="+capa1+","+"MOSTRAR="+capa2);	
	var u=$("#txt_u").attr("value");
	var c=$("#txt_u").attr("value");
	if (u==""||u==undefined||u==null||c==""||c==undefined||c==null){
		$("#div_login2").text("Error: Datos Incompletos"); return;
	} else {
		ajax('div_login2','accion=validar_usuario&u='+u+"&c="+c);
	}
	
}
</script>
<style type="text/css">
body,document{ margin:0px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; }
a:link{ text-decoration:none; }
a:active{ text-decoration:none; }
a:hover{ text-decoration:none; }
a:visited{ text-decoration:none; }

#div_login{ position:absolute; width:400px; height:150px; top:50%; left:50%; margin-top:-75px; margin-left:-200px;  background-color:#FFFFCC; border:#FFCC00 1px solid; padding:5px; }
	#div_titulo{ text-align:center; padding:3px; font-weight:bold; font-size:14px;}
	#div_login0{ width:auto; float:left; }
	#div_login1{ width:auto; float:left; }
	#div_login2{ text-align:center; color:#FF0000; background-color:#FFFFFF; }
.campo_vertical{ font-weight:bold; text-align:left; font-size:11px; }	
</style>
</head>

<body>
	<div id="div_login">
		<div id="div_titulo">IQe Sisco - SATI ver. 1.0.0</div>
		<div id="div_login0"><img src="../img/usuarios.png" /></div>
		<div id="div_login1">
			<br /><br /><table align="center">
			<tr>
				<td class="campo_vertical">Usuario:</td>
				<td><input type="text" id="txt_u" /></td>
			</tr>
			<tr>
				<td class="campo_vertical">Contrase&ntilde;a:</td>
				<td><input type="password" id="txt_c" /></td>
			</tr>
			<tr>
			  <td height="29" colspan="2" align="right"><input type="button" value="Entrar" onclick="validar()" /></td>
			</tr>														
			</table>
		</div>
		<div id="div_login2">Respuesta.</div>
	</div>

</body>
</html>
