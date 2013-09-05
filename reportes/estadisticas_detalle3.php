<?php
session_start();
//print_r($_SESSION); echo "<hr>"; print_r($_GET);
$tipo=$_GET["tipo"];
$mes=$_GET["mes"];
$area=$_GET["area"];
$ndr0=$_GET["ndr"];

$m_meses=array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
$year=date("Y");
$campos_excepciones=array('fecha_hora_pausa');


if(empty($tipo)||empty($mes)||empty($area)||($_SESSION["usuario_grupo"]!==$area)){
	echo "<h3 align='center'>Error: No se reciben los parametros correctos</h3>";
	exit;
}


if($tipo=="solicitados"){
	$sql="SELECT * FROM reg_tickets WHERE fecha BETWEEN '$year-$mes-01' AND '$year-$mes-31' AND area=$area ORDER BY id; ";
}
//echo "<br>$sql";
include("../conf/conexion.php");
//if($_SESSION["usuario_nivel"]==0) $sql="SELECT id,area,servicio FROM cat_areas WHERE servicio=1 ORDER BY id; ";
//if($_SESSION["usuario_nivel"]==1) $sql="SELECT id,area,servicio FROM cat_areas WHERE servicio=1 AND id=".$_SESSION["usuario_grupo"]." ORDER BY id; ";
//$sql="SELECT id,area,servicio FROM cat_areas ORDER BY id; ";
if ($res=mysql_db_query($db_actual,$sql,$link)){ 
	$ndr=mysql_num_rows($res);
	//echo "<br>NDR0 [$ndr0] [$ndr]";
	if(!($ndr0==$ndr)){
		echo "<h3 align='center'>Error: No se reciben los parametros correctos</h3>";
		exit;		
	}
	if($ndr>0){	

	}else{ echo " sin resultados "; exit; }
} else{ return "<br>Error SQL (".mysql_error($link).").";	}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>IQe. Sisco - &Oacute;rdenes de Servicio - Estadisticas - detalle 1.</title>
<link href="../css/css_tickets.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body{ background-color:#efefef; margin:15px 15px 15px; overflow:auto; }
.div_ticket_detalle2{ position:relative; width:49%; height:550px; background-color:#FFFFFF; float:left; padding:2px;
 margin:2px; border:#CCCCCC 1px solid; font-size:small; overflow:auto;
}
</style>
</head>

<body>
<?php
	echo "<h3 align='center'>Tickets $tipo en el mes de ".$m_meses[$mes]." $year</h3>"; 
	while($reg=mysql_fetch_array($res)){
		echo "<div class='div_ticket_detalle2'><table border='0' cellspacing='1'  cellpadding='3' width='100%' height='100%'>";
			//echo "<br><br><br>"; 	print_r($reg);
			
			$contador_interno=1;
			$colorX="#ffffff";
			foreach($reg as $campo=>$valor){
				if($contador_interno%2==0){
					if(!in_array($campo,$campos_excepciones)){
						// Obtener el nombre del area ...
						if($campo=="area"){
							require_once("../clases/area.php");
							$ax=new area();
							$valor=" ".$ax->dame_nombre_area($valor);							
						}
						
						
						
						echo "<tr bgcolor='$colorX'><td width='30%'><b>&nbsp;$campo</b></td><td width='70%'>&nbsp;".$valor."</td></tr>";
						($colorX=="#ffffff")?$colorX="#efefef":$colorX="#ffffff";
					}	
				}
				++$contador_interno;
			}	
		echo "</table></div>";
	}
?>
<table width="200" border="1">
  <tr>
    <td bgcolor="#EFEFEF">id</td>
    <td bgcolor="#EFEFEF">area</td>
    <td bgcolor="#EFEFEF">tipo ticket</td>
    <td bgcolor="#EFEFEF">tema</td>
    <td bgcolor="#EFEFEF">Descripcion</td>
    <td bgcolor="#EFEFEF">acciones</td>
    <td bgcolor="#EFEFEF">status</td>
    <td bgcolor="#EFEFEF">Fecha </td>
    <td bgcolor="#EFEFEF">Hora </td>
    <td bgcolor="#EFEFEF">Fecha Inicio</td>
    <td bgcolor="#EFEFEF">Hora Inicio</td>
    <td bgcolor="#EFEFEF">Fecha fin</td>
    <td bgcolor="#EFEFEF">Hora fin</td>
    <td bgcolor="#EFEFEF">Usuario</td>
    <td bgcolor="#EFEFEF">Observaciones</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
 <?php 
		echo $tipo=$_GET["tipo"];
		echo $mes=$_GET["mes"];
		echo $area=$_GET["area"];
		echo $ndr0=$_GET["ndr"];
?>
<p>&nbsp;</p>
</body>
</html>
