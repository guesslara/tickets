// JavaScript Document
$(document).ready(function(){ 
	$("#txt_nde").focus();
});
function start(){
	$(".tabx").click( function (){ 
		var mtabs=new Array('d111','d112','d113','d114');
		var mhtml=new Array("<br><a href='#'>configuracion</a> | <a href=\"javascript:ajax(\'d221\',\'ac=listar_areas\')\">areas</a> | <a href='javascript:frm_nvo_usuario()'>nuevo usuario</a> | <a href=\"javascript:ajax(\'d221\',\'ac=listar_usuarios\')\">listar usuarios</a>","<br><a href='javascript:listar_tic()'>listar</a> | <a href='javascript:frm_nvo_tic()'>nuevo</a>","<br><a href='#'>estadisticas</a> | <a href='#'>tickets</a> | <a href='#'>usuarios</a> | <a href='#'>reportes</a>","<br><a href='#'>finalizar sesion</a>");
		var tab_seleccionado;
		for(var i=0;i<mtabs.length;i++){
			if(mtabs[i]==$(this).attr("id")){
				tab_seleccionado=mtabs[i];
				$("#d121").html(mhtml[i]);	
			}else{
				$("#"+mtabs[i]).removeClass("tabx_1");
			}
		}
		$("#"+tab_seleccionado).addClass("tabx_1");
	});	
}

function login(){
	/*
	 *Las modificaciones se hacen para hacer posible que se haga un LogIn con un nombre de usuario y password
	*/
	var usuarioT=$("#txt_usuario").attr("value");
	var nde=$("#txt_nde").attr("value");
	if(nde==""||nde==undefined||nde==null || usuarioT=="" || usuarioT==undefined || usuarioT==null){ alert("Por favor, introduzca sus Datos Completos"); return; }
	//if(isNaN(nde)){ alert("\"Numero de Empleado\" debe ser un numero"); return; }
	var datos="ac=login&nde="+nde+"&usuarioT="+usuarioT;
	//alert(datos);
	ajax('p_login_mensaje',datos);	
}
function salir(){
	$("body").css('background-image','url(../img/transparente.png)');
	ocultar_mostrar_capa('d00','login');	
	$("#txt_nde").attr("value","");
	$("#txt_nde").focus();
}
function frm_nvo_tic(){
	//alert("Nuevo ticket");
	var html_01="";
	html_01+="<h3 align=center>nuevo ticket</h3";
	html_01+="<form name='frm_01'><table border='0' align='center' width='60%'>";
		html_01+="<tr>";
			html_01+="<td width='50%'>&nbsp;</td>	<td width='50%'>&nbsp;</td>";
		html_01+="</tr>";
		html_01+="<tr>";
			//html_01+="<td>Area</td>	<td><label><input type='text'></label></td>";
			html_01+="<td>Area de Servicio</td>	<td><label><select>";
				html_01+="<option value=''>...</option>";
				<?php
				include("../conf/conexion.php");
				$sql="SELECT * FROM cat_areas WHERE servicio=1 ORDER BY id; ";
				if ($res=mysql_db_query($db_actual,$sql,$link)){ 
					$ndr=mysql_num_rows($res);
					if($ndr>0){	
						while($reg=mysql_fetch_array($res)){
							echo "html_01+=\"<option value='".$reg[0]."'>".$reg[0].". ".$reg[1]."</option>\";\n"; 	//print_r($reg);
						}
					}else{ echo "html_01+=\"<option value=''>Sin resultados</option>\";\n"; }
				} else{ echo "html_01+=\"<option value=''>Error SQL (".mysql_error($link).")</option>\";\n"; }						
				?>				
				/*
				html_01+="<option value='1'> Servicios Generales 1 </option>";
				html_01+="<option value='2'> Sistemas 2 </option>";
				html_01+="<option value='3'> Logistica 3 </option>";
				*/
			html_01+="</select></label></td>";			
		html_01+="</tr>";
		html_01+="<tr style='display:none;'>";
			html_01+="<td>Usuario</td>	<td><label><select>";
				html_01+="<option value=''>...</option>";
				<?php
				include("../conf/conexion.php");
				$sql="SELECT id,nombre FROM cat_usuarios ORDER BY id; ";
				if ($res=mysql_db_query($db_actual,$sql,$link)){ 
					$ndr=mysql_num_rows($res);
					if($ndr>0){	
						while($reg=mysql_fetch_array($res)){
							echo "html_01+=\"<option value='".$reg[0]."'>".$reg[0].". ".$reg[1]."</option>\";\n"; 	//print_r($reg);
						}
					}else{ echo "html_01+=\"<option value=''>Sin resultados</option>\";\n"; }
				} else{ echo "html_01+=\"<option value=''>Error SQL (".mysql_error($link).")</option>\";\n"; }						
				?>				
				
				/*
				html_01+="<option value='1'> Usuario 1 </option>";
				html_01+="<option value='2'> Usuario 2 </option>";
				html_01+="<option value='3'> Usuario 3 </option>";
				*/
			html_01+="</select></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Tema</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Descripcion</td>	<td><label><textarea cols='40' rows='7'></textarea></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Observaciones</td>	<td><label><textarea cols='40' rows='7'></textarea></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td colspan='2' align='center'><br>";
				html_01+="<label><input type='button' value='Guardar' onclick='guardar_ticket()'></label>&nbsp;";
				html_01+="<label><input type='reset' value='Limpiar'></label>";
			html_01+="</td>";	
		html_01+="</tr>";										
		
	html_01+="</table></form>";
	$("#d221").html(html_01);
}
function guardar_ticket(){
	//alert("guardar_ticket");
	var valores=""; 
	for(var i=0;i<document.frm_01.elements.length;i++){
		if(!(document.frm_01.elements[i].type=="button"||document.frm_01.elements[i].type=="reset")){
			var v_actual=document.frm_01.elements[i].value;
			(valores=="")? valores=v_actual : valores+="|"+v_actual;
		}	
	}
	//alert("Valores="+valores);
	if(confirm("¿Desea generar el Ticket?")){
		ajax('d121','ac=ticket_guardar&v='+valores);
		document.frm_01.reset();
	}
}
function listar_tic(){
	//alert("listar");
	ajax('d221','ac=listar_tickets');
}

function frm_nvo_usuario(){
	//alert("frm_nvo_usuario");
	var html_01="";
	html_01+="<h3 align=center>Nuevo Usuario</h3";
	html_01+="<form name='frm_02'><table border='0' align='center' width='60%'>";
		html_01+="<tr>";
			html_01+="<td width='50%'>&nbsp;</td>	<td width='50%'>&nbsp;</td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>grupo</td><td><label><select>";
				html_01+="<option value=''> ... </option>";
				<?php
				include("../conf/conexion.php");
				$sql="SELECT * FROM cat_areas ORDER BY id; ";
				if ($res=mysql_db_query($db_actual,$sql,$link)){ 
					$ndr=mysql_num_rows($res);
					if($ndr>0){	
						while($reg=mysql_fetch_array($res)){
							echo "html_01+=\"<option value='".$reg[0]."'>".$reg[0].". ".$reg[1]."</option>\";\n"; 	//print_r($reg);
						}
					}else{ echo "html_01+=\"<option value=''>Sin resultados</option>\";\n"; }
				} else{ echo "html_01+=\"<option value=''>Error SQL (".mysql_error($link).")</option>\";\n"; }						
				?>
			html_01+="</select></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>nivel</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td colspan='2'>&nbsp;</td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td>usuario</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>contrase&ntilde;a</td>	<td><label><input type='password'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>nombre</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>apellidos</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>no. empleado</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Observaciones</td>	<td><label><textarea cols='40' rows='7'></textarea></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td colspan='2' align='center'><br>";
				html_01+="<label><input type='button' value='Guardar' onclick='guardar_usuario()'></label>&nbsp;";
				html_01+="<label><input type='reset' value='Limpiar'></label>";
			html_01+="</td>";	
		html_01+="</tr>";										
		
	html_01+="</table></form>";
	$("#d221").html(html_01);
}
function guardar_usuario(){
	var valores=""; 
	for(var i=0;i<document.frm_02.elements.length;i++){
		if(!(document.frm_02.elements[i].type=="button"||document.frm_02.elements[i].type=="reset")){
			var v_actual=document.frm_02.elements[i].value;
			(valores=="")? valores=v_actual : valores+="|"+v_actual;
		}	
	}
	//alert("guardar_usuario\nValores="+valores);
	
	if(confirm("¿ Desea guardar la informacion ?")){
		ajax('d121','ac=usuario_guardar&v='+valores);
	}
}
function procesar_guardar(){
	var i=$("#hid_it_procesar").attr("value");
	var a=$("#txt_it_procesar").attr("value");

	var s=$("#sel_it_procesar").attr("value");
	if(a==""||a==undefined||a==null){ alert("\"acciones\" esta vacio."); return; }
	if(s==""||s==undefined||s==null){ alert("Seleccione \"status\"."); return; }
	var datos="ac=procesar_guardar&it="+i+"&a="+a+"&s="+s;
	//alert(datos);
	ajax('d121',datos);
}
function tecla(n,elEvento){
	var evento = elEvento || window.event;
	var codigo = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigo);
	//alert("N="+n+"\nCod="+codigo+"\nCaracter="+caracter);
	if (n==0&&codigo==13){ // Enter o Tabulacion...
		//alert("login");
		$("#txt_nde").focus();
	}else if(n==1&&codigo==13){
		login();
	}	
}
function listar_mis_tickets(){
	//alert("listar_mis_tickets");
	ajax('d221','ac=ver_mis_tickets');
}
function listar_mis_tickets_atender(){
	//alert("listar_mis_tickets_atender");
	ajax('d221','ac=ver_mis_tickets_atender');
}
function estadisticas(){
	ajax('d221','ac=ver_estadisticas');
}