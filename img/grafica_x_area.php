<?php
$m_areas=array("sistemas"=>98.65,"servicios generales"=>94.25,"logistica"=>82.45,);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Eficiencia por Area</title>
<style type="text/css">
document,body,html{ position:absolute; overflow:hidden; background-color:#fff; width:100%; height:100%; margin:0px; font-family:"Courier New", Courier, monospace; }
#B{ position:relative; overflow:auto; width:100%; height:100%; margin:0px; }
#B h3{ color:#333; text-align:center; }
#B1{ position:relative; width:798px; height:460px; left:50%; margin-top:10px; margin-left:-399px; border:#CCCCCC 1px solid; background-color:#FFFFFF; }

.grafica_x_area_escala{ position:relative; width:30px; height:453px; margin:2px; float:left;}
	.grafica_x_area_escalaA{ position:relative; height:20px; text-align:center; font-weight:bold; font-size:large; }
	.grafica_x_area_escalaB{ position:relative; height:400px; text-align:center; font-weight:bold; background-image:url(escala_400px.png); background-repeat:no-repeat; }
	.grafica_x_area_escalaC{ position:relative; height:30px; text-align:center; font-size:small; }
.grafica_x_area{ position:relative; width:150px; height:453px; margin:2px; float:left; /*border:#CCCCCC 1px solid;*/ }
	.grafica_x_area_valor{ position:relative; height:20px; text-align:center; font-weight:bold; }
	.grafica_x_area_grafica{ position:relative; height:400px; background-color:#efefef; }
		.grafica_x_area_grafica1{ position:relative; height:0px; background-color:#009900; }
	.grafica_x_area_descripcion{ position:relative; height:30px; background-color:#efefef; text-align:center; font-size:small; font-weight:bold; }
</style>
<script language="javascript" src="../jquery_1_4.js"></script>
<script language="javascript">
$("document").ready(function(){
	//alert("Ok");
});
</script>
</head>

<body>
<div id="A"></div>
<div id="B">
	<h3>Eficiencia por Area</h3>
	<div id="B1">
		<div class="grafica_x_area_escala">
			<div class="grafica_x_area_escalaA">%</div>
			<div class="grafica_x_area_escalaB"></div>
			<div class="grafica_x_area_escalaC">&nbsp;</div>
		</div>
		<?php
		//print_r($m_areas);
		$contador_graficas=0;
		foreach($m_areas as $area=>$eficiencia){
			//echo "<br>$area -> $eficiencia";
			
			$altura_grafica=400;
			$altura_grafica_sombreada=$altura_grafica*$eficiencia/100;
			$margen_superior=$altura_grafica-$altura_grafica_sombreada;
			if ($eficiencia>=95) $nuevo_color_grafica="#009900";
			else if ($eficiencia>=90&&$eficiencia<95) $nuevo_color_grafica="#FF6600";
			else if ($eficiencia>=80&&$eficiencia<90) $nuevo_color_grafica="#FF9900";
			else if ($eficiencia<80) $nuevo_color_grafica="#FF0000";
					
					// rojo [#FF0000] amarillo [#FF9900] naranja [#FF6600]  verde [#009900]
			?>
			<div class="grafica_x_area">
				<div id="grafica_x_area_valor<?=$contador_graficas?>" class="grafica_x_area_valor"><?=$eficiencia?></div>
				<div id="grafica_x_area_grafica<?=$contador_graficas?>" class="grafica_x_area_grafica">
					<div id="grafica_x_area_grafica1<?=$contador_graficas?>" class="grafica_x_area_grafica1"></div>
				</div>
				<div class="grafica_x_area_descripcion"><?=$area?></div>
				<script language="javascript">
					$("#grafica_x_area_grafica1<?=$contador_graficas?>").height(<?=$altura_grafica_sombreada?>);
					$("#grafica_x_area_grafica1<?=$contador_graficas?>").css('top','<?=$margen_superior?>px');
					$("#grafica_x_area_grafica1<?=$contador_graficas?>").css('background-color','<?=$nuevo_color_grafica?>');
				</script>
			</div>
			<?php
			++$contador_graficas;
		}
		?>
	</div>
</div>
<div id="C"></div>
</body>
</html>
