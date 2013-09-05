<?
	session_start();
	//print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>IQe Sisco - Ordenes de Servicio - Estadisticas</title>
<style type="text/css">
body{ font-family:Arial, Helvetica, sans-serif; font-size:small; /*background-color:#000000; color:#FFFFFF;*/ }

	#div_A{ text-align:center; font-weight:bold; padding:5px; }
	#div_B{ text-align:center; padding:5px; }


table{ border-top:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; }
th,td{ border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid; }
th{ background-color:#efefef; }

#div_graficaX{ /*position:relative; width:900px; left:50%; margin-left:-450px;  border:#CCCCCC 1px solid;*/ }
</style>
<script language="javascript" src="../js/jquery.js"></script>
<script language="javascript">
function ajax(capa,datos,ocultar_capa){
	if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
	var url="acciones.php";
	$.ajax({
		async:true,
		type: "POST",
		dataType: "html",
		contentType: "application/x-www-form-urlencoded",
		url:url,
		data:datos,
		beforeSend:function(){ 
			$("#"+capa).html('<div style="text-align:center;">Procesando, espere un momento</div>'); 
		},
		success:function(datos){ 
			$("#"+capa).show().html(datos);
		},
		timeout:99999999,
		error:function() { $("#"+capa).show().html('<div style="text-align:center;">Error: El servidor no responde. <br>Por favor intente mas tarde. </div>'); }
	});
}	
function tipo_ticket(id_area,area){
	ajax('div_C','ac=consulta_no&n=2&ia='+id_area+'&ad='+area);
}

</script>
<link rel="stylesheet" href="css/Style2.css" type="text/css" />
<script language="JavaScript" src="js/FusionCharts.js"></script>
</head>

<body>
<div id="div_main">
	<div id="div_A">IQe Sisco - Ordenes de Servicio - Estadisticas 2011</div>
	<div id="div_B">
		&laquo;
<?
		switch($_SESSION['usuario_grupo']){
			case 1:
?>
				<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=1&ad=Sistemas')">Sistemas</a> | 
				<!--<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=2&ad=Servicios Generales')">Servicios Generales</a> |
				<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=5&ad=Logistica')">Logistica</a>	-->	
<?			
			break;
			case 2:
?>
				<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=2&ad=Servicios Generales')">Servicios Generales</a>
<?			
			break;
			case 5:
?>
				<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=5&ad=Logistica')">Logistica</a>	
<?			
			break;
			case 10:
?>
				<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=10&ad=Investigacion')">Investigacion y Desarrollo</a>
<?			
			break;
		}
?>		
		<!--<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=1&ad=Sistemas')">Sistemas</a> | 
		<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=2&ad=Servicios Generales')">Servicios Generales</a> |
		<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=5&ad=Logistica')">Logistica</a>
		<!--<a href="#" onclick="ajax('div_C','ac=consulta_no&n=1&ia=10&ad=Investigacion y Desarrollo')">Investigacion y Desarrollo</a>//-->-->
		&raquo;
		Tipo de Ticket &rarr; 
		<label>
		<!--
		<select>
			<option selected="selected"> Seleccione Area : </option>
			<option onclick="tipo_ticket('1','Sistemas')">Sistemas</option>
			<option onclick="tipo_ticket('2','Servicios Generales')">Servicios Generales</option>
			<option onclick="tipo_ticket('5','Logistica')">Logistica</option>
		</select>
		//-->
		<!--<select>
			<option selected="selected"> Seleccione Area : </option>
			<option onclick="tipo_ticket('1','Sistemas')">Sistemas</option>
			<option onclick="tipo_ticket('2','Servicios Generales')">Servicios Generales</option>
			<option onclick="tipo_ticket('5','Logistica')">Logistica</option>
		</select>-->		
		</label>
		<div id="div_B1">
			
		</div>
	</div>
	<div id="div_C"></div>
</div>
</body>
</html>
