<?php
// Archivo PHP con codigo JS...

?>
<script type="text/javascript">
// JavaScript Document
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
	if(nde==""||nde==undefined||nde==null|| usuarioT=="" || usuarioT==undefined || usuarioT==null){ alert("Por favor, introduzca su \"Numero de Empleado\""); return; }
	if(isNaN(nde)){ alert("\"Numero de Empleado\" debe ser un numero"); return; }
	//var datos="ac=login&nde="+nde;
	var datos="ac=login&nde="+nde+"&usuarioT="+usuarioT;
	//alert(datos);
	ajax('p_login_mensaje',datos);	
}
function salir(){
	location.href='<?=$_SERVER['PHP_SELF']?>';
}
function frm_nvo_tic(){
	//alert("Nuevo ticket");
	alert("Aviso: \n\n\tEs importante recordar que una vez finalizado su TICKET, \nusted debe de evaluar / retroalimentar el servicio proporcionado. \n\n\tPara evaluar un ticket siga el siguiente procedimiento : \n\n * El ticket debe estar finalizado. \n * Ir a tickets / mis tickets / evaluar. \n * Responder el cuestionario en base a su criterio. \n * Presione 'Enviar Evaluacion'. \n\nImportante : Usted dispondra de 3 dias para evaluar el servicio, \nde lo contario procede la 'evaluacion automatica'.");
	var html_01="";
	html_01+="<h3 align=center>nuevo ticket</h3";
	html_01+="<br><div style='text-align:center; text-decoration:none; font-size:small; color:#f00;'>Por favor, no escriba caracteres especiales ( \',\",&,`,~, etc. ) </div>";
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
			html_01+="<td>Asunto</td>	<td><label><input type='text'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Descripcion</td>	<td><label><textarea cols='40' rows='10'></textarea></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Observaciones</td>	<td><label><textarea cols='40' rows='3'></textarea></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td colspan='2' align='center'><br>";
				html_01+="<label><input type='button' value='Guardar' onclick='guardar_ticket()'></label>&nbsp;";
				html_01+="<label><input type='reset' value='Limpiar'></label>";
			html_01+="</td>";	
		html_01+="</tr>";										
		
	html_01+="</table>";
	html_01+="</form>";
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
	var m_valores=valores.split("|");
	var campos_obligatorios=new Array(1,0,1,1,0);
	var errores=0;
	for( var ix=0;ix<m_valores.length;ix++){
		//alert(ix+" = ["+campos_obligatorios[ix]+"] = "+m_valores[ix]);
		if(campos_obligatorios[ix]==1&&(m_valores[ix]==""||m_valores[ix]==undefined||m_valores[ix]==null)){ 
			++errores;
			alert("Advertencia: No omita campos obligatorios ");
			break;
			return;
		}
	}
	if(errores>0) return;
	if(confirm("Desea generar el Ticket ?")){
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
	html_01+="<form name='frm_02'>";
	html_01+="<table border='0' align='center' width='60%'>";
		html_01+="<tr>";
			html_01+="<td width='50%'>&nbsp;</td>	<td width='50%'>&nbsp;</td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>grupo</td><td><label><select id='sel_gru'>";
				html_01+="<option value=''> ... </option>";
				<?php
				include("../conf/conexion.php");
				$sql="SELECT * FROM cat_areas ORDER BY id; ";  //<<<<---------------------------
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
			html_01+="<td>nivel</td>	<td><label><input type='text' id='txt_niv' value=''></label></td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td colspan='2'>&nbsp;</td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td>usuario</td>	<td><label><input type='text' id='txt_usu' value=''></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>contrase&ntilde;a</td>	<td><label><input type='password' id='txt_psw' value=''></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>nombre</td>	<td><label><input type='text' id='txt_nom' value=''></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>apellidos</td>	<td><label><input type='text' id='txt_ape' value=''></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>no. empleado</td>	<td><label><input type='text' id='txt_ndeXXX' value=''></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Observaciones</td>	<td><label><textarea cols='40' rows='7' id='txt_obs' value=''></textarea></label></td>";
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
	//alert("guardar_usuario()");
	var valores=""; 
	var gru=$("#sel_gru").val();
	var niv=$("#txt_niv").val();
	var usu=$("#txt_usu").val();
	var pas=$("#txt_psw").val();
	var nom=$("#txt_nom").val();
	var ape=$("#txt_ape").val();
	var nde=$("#txt_ndeXXX").val();
	var obs=$("#txt_obs").val();
	valores=gru+"|"+niv+"|"+usu+"|"+pas+"|"+nom+"|"+ape+"|"+nde+"|"+obs;
	//(valores=="")? valores=v_actual : valores+="|"+v_actual;
	/*
	sel_gru
txt_niv'></label></td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td colspan='2'>&nbsp;</td>";
		html_01+="</tr>";

		html_01+="<tr>";
			html_01+="<td>usuario</td>	<td><label><input type='text' id='txt_usu'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>contrase&ntilde;a</td>	<td><label><input type='password' id='txt_psw'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>nombre</td>	<td><label><input type='text' id='txt_nom'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>apellidos</td>	<td><label><input type='text' id='txt_ape'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>no. empleado</td>	<td><label><input type='text' id='txt_nde'></label></td>";
		html_01+="</tr>";
		html_01+="<tr>";
			html_01+="<td>Observaciones</td>	<td><label><textarea cols='40' rows='7' id='txt_obs	
	
	for(var i=0;i<document.frm_02.elements.length;i++){
		if(!(document.frm_02.elements[i].type=="button"||document.frm_02.elements[i].type=="reset")){
			var v_actual=document.frm_02.elements[i].value;
			(valores=="")? valores=v_actual : valores+="|"+v_actual;
		}	
	}
	*/
	alert("guardar_usuario\nValores="+valores);
	if(confirm("Desea guardar la informacion ?")){
		ajax('d121','ac=usuario_guardar&v='+valores);
	}
}
function fn_asignar_ticket(){
	var i=$("#txt_asignacion_it").attr("value");
	var t=$("#sel_tipo_ticket").attr("value");
	var a=$("#txt_atiende_01").attr("value");
	var o=$("#txt_asignacion_obs").attr("value");
	if(i==""||i==undefined||i==null||t==""||t==undefined||t==null||a==""||a==undefined||a==null){
		alert("Advertencia: Los campos id,tipo y atiende son obligatorios");
		return;
	}
	
	var datos="ac=guardar_asignacion_ticket&i="+i+"&t="+t+"&a="+a+"&o="+o;
	//alert("Asignar T. ["+datos+"]");
	if(confirm("Desea guardar la informacion ?")){
		ajax('div_frm_asignacion',datos);
	}	
}

function procesar_guardar(){
	var i=$("#hid_it_procesar").attr("value");
	var a=$("#txt_it_procesar").attr("value");
	var s=$("#sel_it_procesar").attr("value");
	//var req_materiales=$("#spa_requiere_materiales").text();


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
	//if (n==0&&codigo==13){ // Enter o Tabulacion...
		//alert("login");
	//	login();
	//}
	
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

function agregar_tipo_ticket(){
	//alert("agregar_tipo_ticket()");
	var tipo=prompt("Tipo de Ticket: \n");
	var horas=prompt("Horas Estimadas: \n");
	var requiere_materiales;
	if(confirm("El tipo de ticket requiere empleo de Materiales ?\n\nPresione aceptar si el tipo de ticket lo requiere y Cancelar y no los requiere.")){
		requiere_materiales=1;
	}else{
		requiere_materiales=0;
	}
	
	//alert("agregar_tipo_ticket()"+tipo);
	if(isNaN(horas)){ alert("\"Horas Estimadas\" deben de ser un numero."); return; }
	if(tipo==""||tipo==null||tipo==undefined||horas==""||horas==null||horas==undefined) return;
	ajax('div_asignacion_nvo_tipo_ticket','ac=nuevo_tipo_ticket&valor='+tipo+'&horas='+horas+'&requiere_materiales='+requiere_materiales);
}
function consulta(n){
	ajax('d221','ac=ver_consulta&n='+n);
}
function asignacion_aviso(){
	ocultar_mostrar_capa('div_frm_asignacion','div_asignacion_aviso');
}
	function enviar_aviso_de_asignacion(){
		//alert("enviar_aviso_de_asignacion()");
		var i=$("#txt_asignacion_it").attr("value");
		var aviso=$("#txt_asignacion_aviso").attr("value");
		if(aviso==""||aviso==undefined||aviso==null){
			alert("Advertencia: El campo aviso esta vacio.");
			return;
		}
		var datos="ac=asignacion_aviso_guardar&it="+i+"&aviso="+aviso;
		//alert(datos);
		if(confirm("Desea enviar el aviso ?")){
		 	ajax('d121',datos);
		}
	}
function ver_perfil(){
	//alert("Ver mi perfil ()");
	ajax('d221','ac=usuario_perfil');
}
function mostrar_buscar_ticket(){
	//alert('Buscar ... ');
	//$('#a_txt_buscar_x_ndt').hide();
	//$('#txt_buscar_x_ndt').show();
	ocultar_mostrar_capa('a_txt_buscar_x_ndt','txt_buscar_x_ndt');
	$('#txt_buscar_x_ndt').focus();
}
function busca_tecla_enter(numero_relativo,elEvento,valor){
	//alert("login (enter)("+numero_relativo+")("+valor+")");
	if(numero_relativo!==1985) return;
	if(valor==''||valor==undefined||valor==null) return;
		
	var evento = elEvento || window.event;
	var codigo = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigo);
	//alert("\nCod="+codigo+"\nCaracter="+caracter);
	if (codigo==13){ // Enter ...
		if(isNaN(valor)){
			alert('El criterio no es un numero.');
			return;
		}	
		ajax('d221','ac=listar_tickets&campo=id&operador==&criterio='+valor);
	}	
}
function nueva_area(){
	//alert("nueva_area()");
	var x_html="";
	x_html+="<div><form name='frm_nva_area'>";
	x_html+="<h3 align='center'>nueva area</h3>";
	x_html+="<br><table align='center' border='0' width='80%'>";
		x_html+="<tr>";
			x_html+="<td>descripci&oacute;n <sup>*</sup></td>";
			x_html+="<td>&nbsp;<input type='text' id='txt_area_nueva_descripcion'></td>";
		x_html+="</tr>";
		x_html+="<tr>";
			x_html+="<td>servicio <sup>*</sup></td>";
			x_html+="<td>&nbsp;<select id='sel_area_nueva_servicio'>";
				x_html+="<option value=''>...</option>";
				x_html+="<option value='1'>SI es &aacute;rea de servicio</option>";
				x_html+="<option value='0'>NO es &aacute;rea de servicio</option>";
			x_html+="</select></td>";
		x_html+="</tr>";		
		x_html+="<tr>";
			x_html+="<td>observaciones</td>";
			x_html+="<td>&nbsp;<textarea id='txt_area_nueva_observaciones' rows='5' cols='40' ></textarea></td>";
		x_html+="</tr>";		
	x_html+="</table>";	
	x_html+="<div align='center'><br>";		
			x_html+="<input type='button' value='Guardar' onclick='area_insertar_validar()' > &nbsp; <input type='reset' value='Limpiar' >";
	x_html+="<div>";		
	x_html+="</form></div>";
	x_html+="<div align='center' id='div_nueva_area_resultados'><br>";
	
	//alert(x_html);
	$("#d221").html(x_html);
	
}	
function area_insertar_validar(){
	var datos;
	var area=$("#txt_area_nueva_descripcion").attr("value");
	var servicio=$("#sel_area_nueva_servicio").attr("value");
	var obs=$("#txt_area_nueva_observaciones").attr("value");
		// Validar ...
		if(area==''||area==undefined||area==null||servicio==''||servicio==undefined||servicio==null){
			alert('Advertencia: No omita datos obligatorios');
			return;
		}
	
	
	datos="ac=area_insertar&a="+area+"&s="+servicio+"&o="+obs;
	//alert(datos);
	ajax('div_nueva_area_resultados',datos);
	
}

// Consumos de materiales ...
function consumo_materiales_agregar_fila(){
	var ndf=parseInt($("#hid_consumos_num_filas").text());
	var nuevo_numero_fila=ndf+1;
	
	var nueva_fila='';
		nueva_fila+='<tr align="center">';
			nueva_fila+='<td align="center"><input type="text" id="txt_consumo_materiales_idp'+nuevo_numero_fila+'" class="txt_campo_chico" /></td>';
			nueva_fila+='<td><input type="text" id="txt_consumo_materiales_des'+nuevo_numero_fila+'" class="txt_campo_mediano" readonly="1" /></td>';
			nueva_fila+='<td><input type="text" id="txt_consumo_materiales_esp'+nuevo_numero_fila+'" class="txt_campo_mediano" readonly="1"  /></td>';
			nueva_fila+='<td><input type="text" id="txt_consumo_materiales_uni'+nuevo_numero_fila+'" class="txt_campo_chico" readonly="1"  /></td>';
			nueva_fila+='<td><input type="text" id="txt_consumo_materiales_can'+nuevo_numero_fila+'" class="txt_campo_chico"  /></td>';
			nueva_fila+='<td><input type="text" id="txt_consumo_materiales_obs'+nuevo_numero_fila+'" class="txt_campo_mediano"  /></td>';
			nueva_fila+='<td>&nbsp;</td>';
		nueva_fila+='</tr>';
	$("#tab_mat_consumidosX").append(nueva_fila);	
	$("#hid_consumos_num_filas").text(nuevo_numero_fila);
}
function registro_materiales_buscar_colocar_productos(){
	// obtener los valores del formulario de registro de materiales ...
		var valores=new Array();
		var p_id;	var p_cantidad;	var p_obs;	
		var no_controles_x_fila=parseInt($("#tbl_registro_materiales1 input:text").length)/6;
		//alert("no elementos="+$("#tbl_registro_materiales1 input:text").length+"\nno_controles_x_fila="+no_controles_x_fila);
		for(var i=1;i<=no_controles_x_fila;i++){
			p_id=$("#txt_consumo_materiales_idp"+i).attr("value");
			if(isNaN(p_id)){ return; }
			if(!(p_id==''||p_id==undefined||p_id==null)) valores.push(i+"|"+p_id);
		}
		//alert(valores);
		ajax('spa_registro_materiales_buscar_colocar_productos','ac=registro_materiales_buscar_colocar_productos&idp='+valores);
		//return valores;	
		/*
		for(var i=1;i<=no_controles_x_fila;i++){
			p_id=$("#txt_consumo_materiales_idp"+i).attr("value");
			p_cantidad=$("#txt_consumo_materiales_can"+i).attr("value");
			p_obs=$("#txt_consumo_materiales_obs"+i).attr("value");
			//alert(p_id);
			
			//nds=$("#tbl_registro_materiales1 input:text")[i].id;
			if(isNaN(p_id)){ return; }
			if(!(p_id==''||p_id==undefined||p_id==null)) valores.push(p_id+"|"+p_cantidad+"|"+p_obs);
		}		
		*/	
}
function inv_guardar_campos(id_tipo_equipo){
	var valores=new Array();
	for(var i=0;i<$("#tbl_env_campos1 input:text").length;i++){
		valores.push($("#tbl_env_campos1 input:text")[i].value);
	}	
	//alert(id_tipo_equipo+"\nvalores="+valores);
	if(confirm("¿ Desea guardar los datos ?")){
	ajax('div_inv_guardar_campos_resultado','ac=inv_guardar_campos&id_tipo_equipo='+id_tipo_equipo+"&valores="+valores);	
	}
}
function fn_evaluar_ticket(id_ticket){
	//alert('evaluar el ticket : '+id_ticket);
	var x_html='<div>';
		x_html+='<h3 align="center">retroalimentaci&oacute;n del ticket : '+id_ticket+'</h3>';
		x_html+='<table align="center" class="tabla_bordes" cellpadding="3" cellspacing="0" width="75%">';
		x_html+='<tr>';
			x_html+='<th>par&aacute;metro</th>';
			x_html+='<th>evaluaci&oacute;n</th>';
		x_html+='</tr>';

		x_html+='<tr>';
			x_html+='<td>tiempo de respuesta</td>';
			x_html+='<td>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_tiempo" value="5"> Excelente </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_tiempo" value="4"> Bueno </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_tiempo" value="3"> Regular </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_tiempo" value="2"> Aceptable </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_tiempo" value="1"> Malo </label><br>';
			x_html+='</td>';
		x_html+='</tr>';

		x_html+='<tr>';
			x_html+='<td>nivel de servicio</td>';
			x_html+='<td>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_servicio" value="5"> Excelente </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_servicio" value="4"> Bueno </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_servicio" value="3"> Regular </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_servicio" value="2"> Aceptable </label>';
				x_html+='<br><label><input type="radio" name="rad_retroalimentacion_servicio" value="1"> Malo </label><br>';
			x_html+='</td>';			
		x_html+='</tr>';
		x_html+='<tr>';
			x_html+='<td>observaciones</td>';
			x_html+='<td><textarea rows="4" cols="30" id="txt_retroalimentacion_obs"></textarea></td>';
		x_html+='</tr>';				
		/*
		x_html+='';
		x_html+='';
		x_html+='';
		x_html+='';
		x_html+='';
		*/
		x_html+='</table>';
		x_html+='<p align="center"><input type="button" value="Enviar Evaluacion" onclick="fn_evaluar_ticket_guardar('+id_ticket+')"></p>';
		x_html+='<div>';
		x_html+='<div id="div_resultados_remporales"><div>';
		$("#d221").html(x_html);
	//alert('evaluar el ticket : '+id_ticket+'\nHTML : '+x_html);	
}
function fn_evaluar_ticket_guardar(id_ticket){
	var tiempo=$("input[@name=rad_retroalimentacion_tiempo]:checked").val();
	var servicio=$("input[@name=rad_retroalimentacion_servicio]:checked").val();
	if(tiempo==''||tiempo==null||tiempo==undefined||servicio==''||servicio==null||servicio==undefined){
		alert("Por favor evalue los primeros dos parametros.");
		return;
	}
	var obs=$("#txt_retroalimentacion_obs").val();
	var url='ac=retroalimentacion_guardar&id_ticket='+id_ticket+'&tiempo='+tiempo+'&servicio='+servicio+'&obs='+obs;
	//alert('guardar evaluar el ticket : \n'+url);
	if(confirm("¿Desea enviar los datos?")){
		ajax('d221',url);
	}
}	
</script>