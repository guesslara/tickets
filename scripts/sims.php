<?php
if(!empty($_POST)){ 
	//print_r($_POST); exit;
	include("../conf/conexion.php");
	mysql_select_db($db_actual);
	
	if($_POST["action"]=="sim_listar"){
		echo "<br>".$sql="SELECT * FROM reg_sims ORDER BY id;";	
		if ($res=mysql_query($sql,$link)){
			$ndr=mysql_num_rows($res);
			echo "<div align='center'>$ndr resultado(s).</div>";
			while($reg=mysql_fetch_array($res)){
				echo "<br>"; print_r($reg); 
			}
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; exit;	}			
	}
	exit;
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SIMS</title>
<script language="javascript" src="../js/jquery.js"></script>
<script language="javascript">
$(document).ready(function (){ 
	//alert("OK");
	actualiza_alto_capas(); 
});
function actualiza_alto_capas(){
	var document_ancho=$("#div_main").width();
	var document_alto=$("#div_main").height();
	var alto_cuerpo=document_alto-40;
	var ancho_b=document_ancho-220;
	
	var alto_textarea=alto_cuerpo-30;
	$("#div_a").css("height",alto_cuerpo+"px");	
	$("#div_b").css("height",alto_cuerpo+"px");
	
	$("#div_b").css("width",ancho_b+"px");
	$("#txt_archivo_excel").css("width","180px");
	$("#txt_archivo_excel").css("height",alto_textarea+"px");	

}
window.onresize=actualiza_alto_capas;
function ajax(capa,datos,ocultar_capa){
	if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
	var url="<?=$_SERVER['PHP_SELF']?>";
	$.ajax({
		async:true, type: "POST", dataType: "html", contentType: "application/x-www-form-urlencoded",
		url:url, data:datos, 
		beforeSend:function(){ 
			$("#"+capa).show().html('<center>Procesando, espere un momento.</center>'); 
		},
		success:function(datos){ 
			$("#"+capa).show().html(datos); 
		},
		timeout:90000000,
		error:function() { $("#"+capa).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});

}
</script>
</head>

<body>
<div id="div_main">
	<div id="div_encabezado">SIMS</div>
	<div id="div_menu">
		<a href="#" onclick="ajax('div_area','ac=sim_listar')">listar</a> | 
		<a href="#" onclick="agregar()">agregar</a>
	</div>
	<div id="div_area">...</div>
</div>	
</body>
</html>
