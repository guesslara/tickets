<?php 
//session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: text/xml; charset=UTF-8");
//print_r($_POST);
$ac=$_POST["ac"];
switch ($ac){
	case "consulta_no":
		require_once("clase_consulta.php");	
		$c=new consulta();
		$c->consulta_no($_POST["n"],$_POST["ia"],$_POST["ad"]);		
		break;
	default:
		echo "<br>&nbsp;&rarr; Accion no Definida. ";
		break;		
}	
?>		
