<?php
class usuario{
	function md_soy_de_area_de_servicio(){
		include("../conf/conexion.php");
		$sql="SELECT servicio FROM cat_areas WHERE id=".$_SESSION["usuario_grupo"]."; ";
		$resultado=0;
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					$resultado=$reg[0];
				}
			}else{ return false; }
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; return false;	}
		return $resultado;
	}
	public function login($nde,$usuarioT){
		//echo ":::$nde";
		include("../conf/conexion.php");
		/*
		 *Las modificaciones se hacen para la inclusion de la validacion del nombre de usuario y contraseña en la aplicacion
		*/
		$sql="SELECT id,nombre,apellidos,usuario,grupo,nivel FROM cat_usuarios WHERE no_empleado='$nde' AND activo=1 LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				//echo " $ndr resultados ";
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
						session_name('iqesisco_tickets');
						session_cache_limiter('nocache,private');
						$_SESSION['usuario_id']=$reg["id"];
						$_SESSION['usuario_grupo']=$reg["grupo"];
						$_SESSION['usuario_nivel']=$reg["nivel"];
						$_SESSION['usuario_usuario']=$reg["usuario"];
						$_SESSION['usuario_nombre']=$reg["nombre"];
						$_SESSION['usuario_apellidos']=$reg["apellidos"];
						$_SESSION['nde']=$nde;
						$_SESSION['ads']=$this->md_soy_de_area_de_servicio();
						$_SESSION['usuario_sistema']="tickets";
						
						// ver si pertenece a las areas de servicio.
						$pertenece_area_servicio=$_SESSION['ads'];
						$no_mis_tickets=0;
						$no_mis_tickets_atender=0;
						if($pertenece_area_servicio==1){
							$no_mis_tickets=$this->dame_no_mis_tickets();
							$no_mis_tickets_atender=$this->dame_no_mis_tickets_atender();
						}else{
							$no_mis_tickets=$this->dame_no_mis_tickets();
						}
						?>
						<script language="javascript">
						$("body").css('background-image','url()');
						ocultar_capa('transparente');
						ocultar_mostrar_capa('login','d00');
						/* Links en la pestaña tickets
							concepto			| Administrador	| Usuario de Area de Servicio 	| Solicitantes
							==================================================================================
							nuevo				| 1				|	1							| 	1	
							listar				| todos			|	solo de mi area				| 	0	
							mis tickets			| 0				|	1							| 	1		
							atender tickets		| 0				|	1							| 	0
							==================================================================================
						*/
						//alert(<?=$_SESSION['usuario_grupo']?>);
						var xhtml="<br>";
						<?php if($_SESSION['usuario_grupo']>0){ ?>
							xhtml+="<a href='javascript:frm_nvo_tic()'>nuevo</a>"; <?php 
						}
						// Listar
						if($pertenece_area_servicio){ ?> xhtml+=" | <a href='javascript:listar_tic()'>listar</a>"; <?php }
						if($_SESSION['usuario_grupo']==0){ ?> xhtml+=" <a href='javascript:listar_tic()'>listar</a>"; <?php }
						// mis tickets
						if($_SESSION['usuario_grupo']>0){ ?> xhtml+=" | <a href='#' onclick='listar_mis_tickets()'>mis tickets</a> ";<?php }
						// tickets para atender 
						if($pertenece_area_servicio){ ?> xhtml+=" | <a href='#' onclick='listar_mis_tickets_atender()'>asignados</a>"; <?php } ?>	
						$("#d121").append(xhtml);
						$("#d112").addClass("tabx_1");
						                        
						// Funciones de asignacion y links ...
$(".tabx").click( function (){ 
	var mtabs=new Array('d111','d112','d113','d114');
	var mhtml=new Array("<br><?php if($_SESSION['usuario_grupo']==0){ ?><a href='#'>configuracion</a> | <a href=\"javascript:ajax(\'d221\',\'ac=listar_areas\')\">areas</a> | <a href=\"javascript:ajax(\'d221\',\'ac=listar_usuarios\')\">usuarios</a> | <a href=\"javascript:ajax(\'d221\',\'ac=inventario_equipos\')\">equipos</a>  | <a target=\"_blank\" href=\"../scripts/sims.php\">SIMS</a> <?php }else{ echo "Acceso no Autorizado"; } ?>",xhtml,"<?php 
	if($_SESSION['usuario_nivel']<=1){ 
		echo "<br />";
		/*if($_SESSION['usuario_grupo']==0){*/ ?>
		<a href='#' onclick='consulta(0)'>estadisticas</a> |<?php /*}*/ ?> <a href='#' onclick='consulta(1)'>areas</a> | <a href='#' onclick='consulta(2)'>tipo ticket</a> | <a href='#' onclick='consulta(3)'>usuarios</a>  | <a href='#' onclick='consulta(4)'>evaluaciones </a><?php }else{ echo "<br />Acceso no Autorizado"; }  ?>","<br><a href='../ayuda/Iqe sisco - os introduccion2.pdf' target='_blank'>ayuda</a> | <a href='#' onclick='ver_perfil()'>mi perfil</a> | <a href='#' onclick='salir()'>finalizar sesion</a>");
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
						// -------------------------------------
						$("#d121").html(xhtml);
						$("#d221").html("<div align='center'><br><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p style='font-size:normal; font-weight:nromal;'>Bienvenido: <b><?=$reg["nombre"]." ".$reg["apellidos"]?></b></p><p><img class='transparent_class' src='../img/iq_128x96.jpg'></p><p style='font-size:normal; font-weight:normal;'>IQe. Sisco - &Oacute;rdenes de Servicio.</p><p style='font-size:small;'>versi&oacute;n 1.0</p></div>");
						<?php
						//if($_SESSION['usuario_id']==2){
							//<a href='../estadisticas' target='_blank'>ver estad&iacute;sticas</a>
							?>
							//window.open("../pruebas/ardilla.swf","","width=848,height=720");
							<?php
						//}
						?>
					
// Si no es administrador, ocultar pestañas ...
<?php if($_SESSION['usuario_nivel']>1){ ?>
	$("#d111").hide();
	$("#d113").hide();
<?php } ?>
                        </script>
						
						<?php
				}
			}else{ 
				echo " usuario no registrado ";
				?><script language="javascript"> 
					$("#txt_nde").attr('value','');
					$("#txt_nde").hide('slow');
					$("#txt_nde").show('slow');
					$("#txt_nde").focus();					 
				</script><?php
			}
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}			
		
	}
	public function insertar($v){
		//echo "<br>guardar($v)";
		$mv=explode("|",$v);
		//print_r($mv);
		//exit;
		//echo "".
		$sql="INSERT INTO cat_usuarios(`id`, `fecha_alta`, `activo`, `grupo`, `nivel`, `usuario`, `nip`, `nombre`, `apellidos`, `no_empleado`, `obs`) 
		VALUES (NULL,'".date("Y-m-d")."','1','".$mv[0]."','".$mv[1]."','".$mv[2]."','".md5($mv[3])."','".$mv[4]."','".$mv[5]."','".$mv[6]."','".$mv[7]."'); ";
		require("../conf/conexion.php");
		//echo "<br>Existe (".$mv[2].")? [".$this->existe_usuario($mv[2])."].";
		if($this->existe_usuario($mv[6])){
			echo "<br>Advertencia: El usuario con no_empleado<b>\"".$mv[6]."\"</b> ya existe.";
			exit;
		}
		
		if (mysql_db_query($db_actual,$sql,$link)){ 
			//return true;
			echo "Se inserto el usuario:&nbsp;&nbsp;&nbsp; <span class='spa_no_ticket'>".mysql_insert_id($link)."<span>";
			?><script language="javascript"> document.frm_02.reset(); </script><?php
		} else{ 
			echo "<br>Error SQL (".mysql_error($link).").";	
			//return false; 
		}
		
	}
	public function listar(){
		include("../conf/conexion.php");
		$sql="SELECT id,activo,grupo,nivel,usuario,nombre FROM cat_usuarios ORDER BY id; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
                <div id="div_usuario_detalle"></div>
                <div id="div_usuario_lista">
                <h3 align="center">cat&aacute;logo de usuarios</h3>
                <table align="center" width="80%" cellspacing="0" cellpadding="2" class="tabla_bordes">
                <tr>
                    <th>#</th>
                    <th>activo</th>
                    <th>grupo</th>
                    <th>nivel</th>
                    <th>usuario</th>
                </tr>
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr>
						<td align="center"><?=$reg["id"]?></td>
						<td align="center"><?php if($reg["activo"]==1) echo "SI"; else echo "NO"; ?></td>
						<td>&nbsp;<?php
                        	//echo $reg["grupo"];
							require_once("area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg["grupo"]);                        
						//$reg["grupo"]
						?></td>
						<td align="center"><?=$reg["nivel"]?></td>
						<td>&nbsp;<?=$reg["usuario"]?></td>
                       	<td align="center"><a href="#" class="link_02" onclick="ajax('div_usuario_detalle','ac=ver_usuario&iu=<?=$reg["id"]?>','div_usuario_lista')">ver</a></td>
					</tr><?php					
				}
				?></table></div><?php
			}else{ echo "<br>Sin resultados."; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}		
	}














	
	public function listar2($pagina,$campo,$operador,$criterio,$orden,$ad){
		($operador=='LIKE')? $campo_operador_criterio=" $campo LIKE '%$criterio%' ":$campo_operador_criterio=" $campo$operador'$criterio' ";
		$whereX=" WHERE $campo_operador_criterio ";

		$paginacion_sql_total="SELECT count(id) FROM cat_usuarios $whereX; ";
		$paginacion_total_resultados=$this->dame_no_resultados($paginacion_sql_total);
		$paginacion_resultados_x_pagina=20;
		$paginacion_no_paginas_resultantes=round(ceil($paginacion_total_resultados/$paginacion_resultados_x_pagina));	
		($pagina<=1)? $limite_inferior=0 : $limite_inferior=($pagina-1)*$paginacion_resultados_x_pagina;	
		($paginacion_no_paginas_resultantes<=1)?$paginacion_requerida=false:$paginacion_requerida=true;

		include("../conf/conexion.php");
		$sql="SELECT id,activo,grupo,nivel,usuario,nombre,no_empleado FROM cat_usuarios $whereX ORDER BY $orden $ad LIMIT $limite_inferior,$paginacion_resultados_x_pagina; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			//if($ndr>0){
				?>
                <div id="div_usuario_detalle"></div>
				<div id="div_usuario_lista">
				<div align="center" style="text-align:center; font-size:large; font-weight:bold; margin-bottom:3px;">catalogo de usuarios</div>
                <table align="center" width="95%" cellspacing="0" cellpadding="3" class="tabla_bordes">
                <tr>
                    <th><a href="#" onclick="ajax('d221','ac=listar_usuarios&orden=id&ad=<?=$ad?>')" class="link_02" title="ordenar por id">id</a></th>
                    <th>activo</th>
                    <th><a href="#" onclick="ajax('d221','ac=listar_usuarios&orden=grupo&ad=<?=$ad?>')" class="link_02" title="ordenar por grupo">grupo</a></th>
                    <th>nivel</th>
                    <th>no_empleado</th>
                    <th><a href="#" onclick="ajax('d221','ac=listar_usuarios&orden=nombre&ad=<?=$ad?>')" class="link_02" title="ordenar por nombre">usuario</a></th>
					<th>acciones</th>
                </tr>
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr>
						<td align="center"><?=$reg["id"]?></td>
						<td align="center"><?=$reg["activo"]?></td>
						<td><?php 
							require_once("area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg["grupo"]);
						?></td>
						<td align="center"><?=$reg["nivel"]?></td>
						<td align="center"><?=$reg["no_empleado"]?></td>
						<td><?=$reg["usuario"]?></td>
						<td align="center"><a href="#" class="link_02" onclick="ajax('div_usuario_detalle','ac=ver_usuario&iu=<?=$reg["id"]?>','div_usuario_lista')">ver</a></td>
                    </tr><?php					
				}
				?>
				</table>
                <?php
			//}else{ /*echo "<br><div align='center'>No se encontraron resultados.</div><br>";*/ }
			?>
				<div style="text-align:center; font-size:small; margin-top:3px;">
					<?php
					echo $paginacion_total_resultados." resultados  &nbsp;&nbsp;&nbsp;";
					if($paginacion_requerida){
echo "<b>"; 
if($pagina>1){
	?><a href="#" onclick="ajax('d221','ac=listar_usuarios&pagina=1&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="primera pagina">&nbsp;|&lt;&nbsp;</a><?php
}else{ echo "&nbsp; |&lt; &nbsp;"; }					
if($pagina>1){
	?><a href="#" onclick="ajax('d221','ac=listar_usuarios&pagina=<?=$pagina-1?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="pagina anterior">&nbsp;&lt;&nbsp;</a><?php }else{ echo " &nbsp;&lt;&nbsp; "; }					
echo " &nbsp;$pagina / $paginacion_no_paginas_resultantes&nbsp; ";
if($pagina<$paginacion_no_paginas_resultantes){
	?><a href="#" onclick="ajax('d221','ac=listar_usuarios&pagina=<?=$pagina+1?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="pagina siguiente">&nbsp;&gt;&nbsp;</a><?php }else{ echo " &nbsp;&gt;&nbsp; "; }
if($pagina<$paginacion_no_paginas_resultantes){
	?><a href="#" onclick="ajax('d221','ac=listar_usuarios&pagina=<?=$paginacion_no_paginas_resultantes?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="ultima pagina">&nbsp;&gt;|&nbsp;</a><?php
}else{ echo " &nbsp;&gt;|&nbsp; "; }					
echo " </b>";
					}
// | <a href='javascript:frm_nvo_usuario()'>nuevo usuario</a>
					?>
					<a href="#" onclick="frm_nvo_usuario()">nuevo usuario</a>	
				</div>
				</div>
			<?php
		} else{ echo "<br><div align='center'>Error SQL (".mysql_error($link).").</div>";	}		
		

	}



	protected function existe_usuario($no_empleado){
		include("../conf/conexion.php");
		$sql="SELECT id FROM cat_usuarios WHERE no_empleado='$no_empleado' LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0) return true; else return false;
		}else{ echo "<br>Error SQL (".mysql_error($link).").";	}	
	}
	
	function ver_usuario($iu){
		//echo "ver_usuario($iu).";
		include("../conf/conexion.php");
		$sql="SELECT * FROM cat_usuarios WHERE id=$iu LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<h3 align="center">usuario no. <?=$iu?></h3>
                    <table align="center" width="50%" cellspacing="0" cellpadding="3" class="tabla_bordes">
                    	<tr><td class="campo_vertical">fecha</td><td><?=$reg["fecha_alta"]?></td></tr>
                        <tr>
                          <td class="campo_vertical">activo</td><td><?php if($reg["activo"]==1) echo "SI"; else echo "NO"; ?></td></tr>
                        <tr>
                      <td class="campo_vertical">grupo</td><td><?php
                        	echo $reg["grupo"];
							require_once("area.php");
							$ax=new area();
							echo ". ".$ax->dame_nombre_area($reg["grupo"]);                      
					  //$reg["grupo"]
					  ?></td></tr>
                        <tr>
                          <td class="campo_vertical">nivel</td><td><?=$reg["nivel"]?></td></tr>
                        <tr>
                          <td class="campo_vertical">usuario</td><td><?=$reg["usuario"]?></td></tr>
                        <tr>
                          <td class="campo_vertical">nombre</td><td><?=$reg["nombre"]?></td></tr>
                        <tr>
                          <td class="campo_vertical">apellidos</td><td><?=$reg["apellidos"]?></td></tr>
                        <tr><td class="campo_vertical">obs</td><td><?=$reg["obs"]?></td></tr>
                    </table>
                    <div align="center"><br><a href="#" class="link_02" onClick="ocultar_mostrar_capa('div_usuario_detalle','div_usuario_lista')">cerrar</a></div>
					<?php
				}
			}else{ echo "<br>Sin resultados."; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}		
	}
	function dame_no_mis_tickets(){
		include("../conf/conexion.php");
		$no_mis_tickets=0;
		$sql="SELECT count(id) as no_mis_tickets FROM reg_tickets WHERE id_usuario=".$_SESSION["usuario_id"]."; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){ return $reg[0]; }
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}
	}
	function dame_no_mis_tickets_atender(){
		include("../conf/conexion.php");
		$no_mis_tickets=0;
		$sql="SELECT count(id) as no_mis_tickets FROM reg_tickets WHERE atiende=".$_SESSION["usuario_id"]."; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){ return $reg[0]; }
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}		
	}	
	
	function dame_nombre_usuario($iu){
		include("../conf/conexion.php");
		$sql="SELECT nombre,apellidos FROM cat_usuarios WHERE id=$iu LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					return $reg[0];//." ".$reg[1];
				}
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}				
	}
	function dame_nombre_usuario2($iu){
		include("../conf/conexion.php");
		$sql="SELECT nombre,apellidos FROM cat_usuarios WHERE id=$iu LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					return $reg[0]." ".$reg[1];
				}
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}				
	}
	function perfil(){
		//print_r($_SESSION);
		?>
		<div>
		<h3 align="center">Mi perfil</h3>
		<p>&nbsp;</p><p align="center"><img src="../img/kuser.png" id="img_login_01" /></p>
		<h4 align="center"><?=$_SESSION["usuario_nombre"]." ".$_SESSION["usuario_apellidos"]?></h4><p>&nbsp;</p>
		<table align="center" class="tabla_bordes" width="50%" cellpadding="5" cellspacing="0">
			<tr><td width="50%">id</td><td width="50%">&nbsp;<?=$_SESSION["usuario_id"]?></td></tr>
			<tr><td>usuario</td><td>&nbsp;<?=$_SESSION["usuario_usuario"]?></td></tr>
			<tr><td>grupo</td><td>&nbsp;<?php
				require_once("area.php");
				$ax=new area();
				echo $ax->dame_nombre_area($_SESSION["usuario_grupo"]);			
			?></td></tr>
			<tr><td>nivel</td><td>&nbsp;<?=$_SESSION["usuario_nivel"]?></td></tr>
		</table>
		</div>
		<?php
	}
	
	protected function dame_no_resultados($sql){
		include("../conf/conexion.php");
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					return $reg[0];
				}
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}		
	}		
	
}
?>