// JavaScript Document
function ajax(capa,datos,ocultar_capa){
	if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
	var url="../scripts/acciones.php";
	//alert("URL="+url+"\nCAPA="+capa+"\nDATOS="+datos);
	$.ajax({
		async:true,
		type: "POST",
		dataType: "html",
		contentType: "application/x-www-form-urlencoded",
		url:url,
		data:datos,
		beforeSend:function(){ 
			//$("#div_transparente").show();
			$("#d03").html('<img src=\'../img/loading4.gif\'>'); 
			$("#d03").html('Procesando, espere un momento'); 

		},
		success:function(datos){ 
			//$("#div_transparente").hide();
			//$("#div_loading").hide().html(''); 			
			$("#"+capa).show().html(datos);
			$("#d03").html('');
		},
		timeout:90000000,
		error:function() { $("#"+capa).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}

function ajax2(capa,datos,ocultar_capa){
	if (!(ocultar_capa==""||ocultar_capa==undefined||ocultar_capa==null)) { $("#"+ocultar_capa).hide(); }
	var url="../scripts/acciones.php";
	//alert("URL="+url+"\nCAPA="+capa+"\nDATOS="+datos);
	$.ajax({
		async:true,
		type: "POST",
		dataType: "html",
		contentType: "application/x-www-form-urlencoded",
		url:url,
		data:datos,
		beforeSend:function(){ 
			$("#div_transparente").show();
			$("#div_loading").show().html('<center><img src=\'../img/loading4.gif\'></center>'); 
			//$("#"+capa).show().html('<center>Procesando, espere un momento.</center>'); 
		},
		success:function(datos){ 
			//$("#div_transparente").hide();
			$("#div_loading").hide().html(''); 			
			$("#"+capa).show().html(datos); 
		},
		timeout:90000000,
		error:function() { $("#"+capa).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function ocultar_capa(capa){	$("#"+capa).hide();		}
function mostrar_capa(capa){	$("#"+capa).show();		}
function ocultar_mostrar_capa(capa1,capa2){	
	//alert("OCULTAR="+capa1+","+"MOSTRAR="+capa2);	
	$("#"+capa1).hide();
	$("#"+capa2).show();
}
function limpiar_capa(capa){
	$("#"+capa).html("&nbsp;");
}
function ventana_cancelar(){
	ocultar_capa('div_transparente');
	ocultar_capa('div_ventana');
}
/*
document.onkeypress = function (elEvento){
	var evento = elEvento || window.event;
	var codigo = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigo);	
	//alert("Evento: "+evento+" Codigo: "+codigo+" Caracter: "+caracter);
	if (codigo==27){  ventana_cancelar(); }
}
*/