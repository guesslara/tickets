<?php 
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Content-Type: text/xml; charset=ISO-8859-1");
header("Content-Type: text/xml; charset=UTF-8");
//header('Content-Type: text/html; charset=UTF-8');

//echo "<br>Sessiones=";	print_r($_SESSION);		echo "<br>";
//print_r($_POST);
$ac=$_POST["ac"];
// ------------ V A L I D A C I O N E S -----------------------------------------------------------------
	// 1. verificar si existe sesion, de lo contrario avisar y mandar a login ... 
	
	// 2. verificar si la accion es login ... 
if($ac!=="login"){
	// verificar si existe sesion ...
	if(!$_SESSION){
		?>
		<script language="javascript">
			alert("Su sesion ha caducado por inactividad.");
			location.href="../main/";
		</script>
		<?php
		exit;
	}
	// verificar si la sesion es correcta ...
	if($_SESSION["usuario_sistema"]!=='tickets'){
		session_destroy();
		?>
		<script language="javascript">
			alert("Sesion NO valida.");
			location.href="../main/";
		</script>
		<?php
		exit;
	}	
}else{
	require_once("../clases/usuario.php");	
	$u1=new usuario();
	$u1->login($_POST["nde"],$_POST["usuarioT"]);		
}	
// ------------ V A L I D A C I O N E S -----------------------------------------------------------------
switch ($ac){
	/*
	case "login":
		require_once("../clases/usuario.php");	
		$u1=new usuario();
		$u1->login($_POST["nde"]);		
		break;
	*/		
	case "usuario_perfil":
		require_once("../clases/usuario.php");	
		$u1=new usuario();
		$u1->perfil();		
		break;			
	// -------------------------------------------------------------------------
	case "ticket_guardar":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->guardar($_POST["v"]);		
		break;
	case "listar_tickets":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
			(isset($_POST["pagina"]))? $pagina=$_POST["pagina"]:$pagina=1;
			(isset($_POST["campo"]))? $campo=$_POST["campo"]:$campo='status';
			(isset($_POST["operador"]))? $operador=$_POST["operador"]:$operador='LIKE';
			(isset($_POST["criterio"]))? $criterio=$_POST["criterio"]:$criterio='';
			(isset($_POST["orden"]))? $orden=$_POST["orden"]:$orden='id';
			(isset($_POST["ad"]))? $ad=$_POST["ad"]:$ad='DESC';
			$t1->listar2($pagina,$campo,$operador,$criterio,$orden,$ad);		
	break;
	case "ver_ticket":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->ver_ticket($_POST["it"]);		
		break;
	case "asignar_ticket":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->asignar($_POST["it"],$_POST["ia"]);		
		break;
	case "guardar_asignacion_ticket":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->guardar_asignar($_POST["i"],$_POST["t"],$_POST["a"],$_POST["o"]);		
		break;
	case "asignacion_aviso_guardar":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->asignacion_aviso_guardar($_POST["it"],$_POST["aviso"]);		
		break;		
	case "procesar_ticket":		//$it,$usuario,$tema,$descripcion
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->procesar_ticket($_POST["it"],$_POST["usuario"]);		
		break;
		case "registro_materiales_buscar_colocar_productos":	
			require_once("../clases/registro_materiales.php");	
			$rm1=new registro_materiales();
			$rm1->buscar_colocar_productos($_POST["idp"]);		
			break;		
	case "procesar_guardar":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->procesar_guardar($_POST["it"],$_POST["a"],$_POST["s"]);		
		break;
	case "cancelar_ticket":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->ticket_cancelar($_POST["it"],$_POST["obs"]);		
		break;			
	case "ver_mis_tickets":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->ver_mis_tickets();		
		break;
	case "ver_mis_tickets_atender":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->ver_mis_tickets_atender();		
		break;	
	case "nuevo_tipo_ticket":
		require_once("../clases/ticket.php");	
		$t1=new ticket();
		$t1->nuevo_tipo_ticket($_POST["valor"],$_POST["horas"],$_POST["requiere_materiales"]);		
		break;				
	// -------------------------------------------------------------------------
	case "usuario_guardar":
		require_once("../clases/usuario.php");	
		$u1=new usuario();
		$u1->insertar($_POST["v"]);		
		break;	
	case "listar_usuarios":
		require_once("../clases/usuario.php");	
		$u1=new usuario();
			(isset($_POST["pagina"]))? $pagina=$_POST["pagina"]:$pagina=1;
			(isset($_POST["campo"]))? $campo=$_POST["campo"]:$campo='id';
			(isset($_POST["operador"]))? $operador=$_POST["operador"]:$operador='LIKE';
			(isset($_POST["criterio"]))? $criterio=$_POST["criterio"]:$criterio='';
			(isset($_POST["orden"]))? $orden=$_POST["orden"]:$orden='id';
			(isset($_POST["ad"]))? $ad=$_POST["ad"]:$ad='ASC';
		$u1->listar2($pagina,$campo,$operador,$criterio,$orden,$ad);		
		//$u1->listar();
		
		break;			
	case "ver_usuario":
		require_once("../clases/usuario.php");	
		$u1=new usuario();
		$u1->ver_usuario($_POST["iu"]);		
		break;
	// -------------------------------------------------------------------------
	case "listar_areas":
		/*
		require_once("../clases/area.php");	
		$a1=new area();
		$a1->listar();		
		break;
		*/
		require_once("../clases/area.php");	
		$a1=new area();
			(isset($_POST["pagina"]))? $pagina=$_POST["pagina"]:$pagina=1;
			(isset($_POST["campo"]))? $campo=$_POST["campo"]:$campo='area';
			(isset($_POST["operador"]))? $operador=$_POST["operador"]:$operador='LIKE';
			(isset($_POST["criterio"]))? $criterio=$_POST["criterio"]:$criterio='';
			(isset($_POST["orden"]))? $orden=$_POST["orden"]:$orden='id';
			(isset($_POST["ad"]))? $ad=$_POST["ad"]:$ad='ASC';
			$a1->listar($pagina,$campo,$operador,$criterio,$orden,$ad);
		break;			
	case "area_insertar":
		require_once("../clases/area.php");	
		$a1=new area();
		$a1->insertar($_POST["a"],$_POST["s"],$_POST["o"]);		
		break;

	// -------------------------------------------------------------------------
	case "ver_estadisticas":
		require_once("../clases/consulta.php");	
		$c1=new consulta();
		$c1->listar();		
		break;
	case "ver_detalle_consulta_area":
		require_once("../clases/consulta.php");	
		$c1=new consulta();
		$c1->detalle_area($_POST["area"]);		
		break;
	case "ver_consulta":
		require_once("../clases/consulta.php");	
		$c1=new consulta();
		$c1->opciones($_POST["n"]);		
		break;	
	// ---------------------- INVENTARIO DE EQUIPOS ---------------------------------------------------	
	case "inventario_equipos":
		require_once("../clases/inventario_equipos.php");	
		$i1=new inventario();
		$i1->menu();		
		break;
	case "inventario_insertar":
		require_once("../clases/inventario_equipos.php");	
		$i1=new inventario();
		$i1->insertar($_POST["f"],$_POST["d"],$_POST["t"],$_POST["r"],$_POST["c"],$_POST["o"]);		
		break;
	case "inventario_listar":
		require_once("../clases/inventario_equipos.php");	
		$i1=new inventario();
		$i1->listar_equipos();		
		break;
	case "inventario_ver_detalle":
		require_once("../clases/inventario_equipos.php");	
		$i1=new inventario();
		$i1->inventario_ver_detalle($_POST["id_equipo"]);		
		break;
	case "inv_guardar_campos":
		require_once("../clases/inventario_equipos.php");	
		$i1=new inventario();
		$i1->inv_guardar_campos($_POST["id_tipo_equipo"],$_POST["valores"]);		
		break;
		// --------------------- E V A L U A C I O N ------------------------------------
	case "retroalimentacion_guardar":
		require_once("../clases/evaluacion.php");	
		$e1=new evaluacion();
		$e1->insertar($_POST["id_ticket"],$_POST["tiempo"],$_POST["servicio"],$_POST["obs"]);		
		break;
	/*
	case "retroalimentacion_listar":
		require_once("../clases/evaluacion.php");	
		$e1=new evaluacion();
		$e1->insertar($_POST["id_ticket"],$_POST["tiempo"],$_POST["servicio"],$_POST["obs"]);		
		break;												
	*/				
	default:
		"&nbsp;Accion no registrada.";		
}
?>