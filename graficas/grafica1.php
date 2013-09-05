<?php
	/*
	$secciones=$_GET["areas"];
	$cantidades=$_GET["valores"];
	*/
	$secciones=array("Sistemas","Serv Generales",$_GET["action"]);
	$cantidades=array(4,5,12);	
	
	/*
	echo "<br><hr>";	print_r($secciones);
	echo "<br><hr>";	print_r($cantidades);
	echo "<br><hr>Total=";	echo array_sum($cantidades);
	*/
	
	$total=array_sum($cantidades);
	
	for($i=0;$i<count($cantidades);$i++){
		$porcentajes[]=round(($cantidades[$i]/$total)*100,2);
		$angulos[]=round(($porcentajes[$i]*360)/100);
	}
	
	header("Content-type: image/png");
	$imagen=imagecreate(580,240);
	$bg=imagecolorallocate($imagen,255,255,255);
	$gris=imagecolorallocate($imagen,100,100,100);
	
	$color1=imagecolorallocate($imagen,93,169,227);
	$color2=imagecolorallocate($imagen,227,93,93);
	$color3=imagecolorallocate($imagen,93,227,144);
	
	
	//$color1=imagecolorallocate($imagen,227,203,93);
	//$color2=imagecolorallocate($imagen,93,227,144);
	//$color3=imagecolorallocate($imagen,93,169,227);
	
	
	//$color4=imagecolorallocate($imagen,207,93,227);
	//$color5=imagecolorallocate($imagen,227,93,93);
	
	$colores=array($color1,$color2,$color3);
	
	$cx=120;
	$cy=120;
	
	$ancho=200;
	$alto=200;
	
	$inicio=0;
	for($i=0;$i<count($cantidades);$i++){
		imagefilledarc($imagen,$cx,$cy,$ancho,$alto,$inicio,$angulos[$i]+$inicio,$colores[$i],IMG_ARC_PIE);
		imagefilledrectangle($imagen,250,120+($i*20),264,134+($i*20),$colores[$i]);
		imagestring($imagen,3,276,122+($i*20),$secciones[$i]." (".$porcentajes[$i]." %)",$gris);
		$inicio+=$angulos[$i];
	}
	imagepng($imagen);
	imagedestroy($imagen);
?>
