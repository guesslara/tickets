<?php
class inventario{
	function menu(){
		?>
		<style type="text/css">
		#div_ide_menu1{ position:relative; /*width:300px; height:300px; left:50%; top:50%; margin-left:-150px; margin-top:-150px;*/ /*border:#FFFFFF 1px double;*/ z-index:2;  }
		#ul_ide_lista1{ }
		#ul_ide_lista1 li { padding-top:2px; }
		
		
		#div_ide_area_trabajo{ position:relative; display:none; }
		</style>
		<script language="javascript">
		function equipo_alta(){
			ocultar_mostrar_capa('div_ide_menu1','div_ide_area_trabajo');
			
			var x_html;
			x_html="";
			x_html+="<h4 align='center'>alta de equipo</h4>";
			x_html+="<table align='center' border='0' width='500' >";
			x_html+="<tr>";
				x_html+="<td>fecha alta </td>";
				x_html+="<td><input type='text' id='txt_fda' onClick='selecciona_fecha()' value='<?=date("Y-m-d")?>' > (aaaa-mm-dd) <a href='#' class='link_02' title='La fecha debe de ir en el formato: A&ntilde;o (4 digitos) - mes (2 digitos) - dia (2 digitos). Por ejemplo: 2010-01-13 para referirnos al 13 de Enero de 2010. '>?</a></td>";
			x_html+="</tr>";	
			x_html+="<tr>";	
				x_html+="<td>descripcion<br>nombre infraestructura</td>";
				x_html+="<td><input type='text' id='txt_des' ></td>";
			
			x_html+="</tr>";	
			x_html+="<tr>";
				x_html+="<td>tipo</td>";
				x_html+="<td><select id='txt_tip' >";
					x_html+="<option value=''>...</option>";
					<?php
					include("../conf/conexion.php");
					$sql0="SELECT * FROM inv_cat_tipo_equipo ORDER BY id; ";
					if ($res0=mysql_db_query($db_actual,$sql0,$link)){ 
						$ndr0=mysql_num_rows($res0);
						if($ndr0>0){
							while($registro0=mysql_fetch_array($res0)){
								echo "x_html+=\"<option value='".$registro0["id"]."'>".$registro0["prefijo"]." - ".$registro0["descripcion"]."</option>\";";
							}
						}else{
							echo "x_html+=\"<option value=''>sin resultados</option>\";";
						}
					}else{ 
						echo "x_html+=\"<option value=''>Error SQL : (".mysql_error($link).").</option>\";";
						exit;
					}					
					?>
				x_html+="</select></td>";
			x_html+="</tr>";	
			x_html+="<tr>";
				x_html+="<td>responsable</td>";
				x_html+="<td><select id='txt_res' >";
					x_html+="<option value=''>...</option>";
					<?php
					include("../conf/conexion.php");
					$sql0="SELECT * FROM cat_areas WHERE servicio=1  ORDER BY id; ";
					if ($res0=mysql_db_query($db_actual,$sql0,$link)){ 
						$ndr0=mysql_num_rows($res0);
						if($ndr0>0){
							while($registro0=mysql_fetch_array($res0)){
								echo "x_html+=\"<option value='".$registro0["id"]."'>".$registro0["id"]." - ".$registro0["area"]."</option>\";";
							}
						}else{
							echo "x_html+=\"<option value=''>sin resultados</option>\";";
						}
					}else{ 
						echo "x_html+=\"<option value=''>Error SQL : (".mysql_error($link).").</option>\";";
						exit;
					}					
					?>				
				x_html+="</select></td>";
			x_html+="</tr>";	
			x_html+="<tr>";
				x_html+="<td>clave de inventario</td>";
				x_html+="<td><input type='text' id='txt_cla' ></td>";
			x_html+="</tr>";	
			x_html+="<tr>";
				x_html+="<td>ubicacion</td>";
				x_html+="<td><input type='text' id='txt_ubi' ></td>";
			x_html+="</tr>";
			x_html+="<tr>";
				x_html+="<td>obs</td>";
				x_html+="<td><input type='text' id='txt_obs' ></td>";
			x_html+="</tr>";
			x_html+="</table>";
			x_html+="<br><div align='center'><input type='button' value='Guardar' onclick='inventario_alta_validar()' ></div>";

			$("#div_ide_area_trabajo").append(x_html); 
		}
		function inventario_alta_validar(){
			alert("inventario_alta_validar()");
			var f=$("#txt_fda").attr("value");
			var d=$("#txt_des").attr("value");
			var t=$("#txt_tip").attr("value");
			var r=$("#txt_res").attr("value");
			var c=$("#txt_cla").attr("value");
			var u=$("#txt_ubi").attr("value");
			var o=$("#txt_obs").attr("value");
			// Validar los datos.
			
			var datos="ac=inventario_insertar&f="+f+"&d="+d+"&t="+t+"&r="+r+"&c="+c+"&u="+u+"&o="+o;
			//alert(datos);
			ajax('div_ide_area_resultados',datos);
		}
		function inventario_ver_detalle(id_inventario){
			
		}
		</script>
		<div id="div_ide_menu1" >
		<h4 align="center">inventario de equipos</h4>
		<ul id="ul_ide_lista1">
			<li>inventario: 
				<ul>
					<li><a href="#" class="link_02" onClick="equipo_alta()">alta de equipos</a></li>
					<li><a href="#" class="link_02">baja de equipos</a></li>
					<li><a href="#" class="link_02">cambios de equipos</a></li>
					<li><a href="#" class="link_02" onClick="ajax('div_ide_area_resultados','ac=inventario_listar','div_ide_menu1')">consultas</a></li>
				</ul>
			</li>
			<br>
			<li>asignaciones:
				<ul>
					<li><a href="#" class="link_02">nueva asignacion</a></li>
					<li><a href="#" class="link_02">elininar asignacion</a></li>
					<li><a href="#" class="link_02">modificar asignacion</a></li>
					<li><a href="#" class="link_02">consultas</a></li>
				</ul>			
			</li>
		</ul>
		</div>
		<div id="div_ide_area_trabajo"></div>
		<div id="div_ide_area_resultados"></div>
		<?php
	}
	function insertar($fecha_alta,$descripcion,$id_tipo,$id_responsable,$clave,$ubicacion,$obs){
		echo "<br>insertar($fecha_alta,$descripcion,$id_tipo,$id_responsable,$clave,$obs)";
		echo "<br>".$sql="INSERT INTO inv_general(`id`, `activo`, `fecha_alta`, `nombre_infraestructura`, `id_tipo_equipo`, `clave`, `ubicacion`, `id_area_resp_mantto`, `obs`) VALUES (NULL,1,'$fecha_alta','$descripcion','$id_tipo','$clave', '$ubicacion', '$id_responsable','$obs'); ";
		if($this->executa_sql($sql)){
			echo "<br><h5 align='center'>El equipo se inserto correctamente.</h5>";
		}else{
			echo "<br><h5 align='center'>Advertencia: El equipo NO se inserto.</h5>";
		}
	}
	function listar_equipos(){
		//echo "<br>listar_equipos()";
		include("../conf/conexion.php");
		$sql="SELECT inv_general.*,inv_cat_tipo_equipo.descripcion as tipo2,cat_areas.area  FROM inv_general,inv_cat_tipo_equipo,cat_areas 
		WHERE inv_general.id_tipo_equipo=inv_cat_tipo_equipo.id AND inv_general.id_area_resp_mantto=cat_areas.id ORDER BY inv_general.id; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				/*
				while($reg=mysql_fetch_array($res)){
					echo "<br>"; 	print_r($reg);
				}
				*/
			}else{ echo "<br>no se encontraron resultados "; exit; }
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; exit;	}			
		
		?>
		<div id="div_inventario_lista1">
		<h4 align="center">inventario de equipo</h4>
		<table align="center" width="98%"  cellspacing="0" cellpadding="3" class="tabla_bordes">
		<tr>
			<th>id</th>
			<th>activo</th>
			<th>clave</th>
			<th>tipo</th>
			<th>descripcion</th>
			<th>responsable</th>
			<th>ubicacion</th>
			<th>acciones</th>
		</tr>
		<?php while($reg=mysql_fetch_array($res)){ ?>
		<tr>
			<td align="center"><?=$reg["id"]?></td>
			<td align="center"><?php if($reg["activo"]) echo "SI"; else echo "NO";?></td>
			<td>&nbsp;<?=$reg["clave"]?></td>
			<td>&nbsp;<?=$reg["tipo2"]?></td>
			<td>&nbsp;<?=$reg["nombre_infraestructura"]?></td>
			<td>&nbsp;<?=$reg["area"]?></td>
			<td>&nbsp;<?=$reg["ubicacion"]?></td>
			<td align="center"><a href="#" class="link_02" onclick="ajax('div_inventario_detalle1','ac=inventario_ver_detalle&id_equipo=<?=$reg["id"]?>','div_inventario_lista1')">ver mas</a></td>
		</tr>
		<?php } ?>
		</table>
		</div>
		<div id="div_inventario_detalle1"></div>
		<?php
	}
	function inventario_ver_detalle($id_equipo){
		//echo "<br>ver detalle ...";
		include("../conf/conexion.php");
		$sql="SELECT inv_general.*,inv_cat_tipo_equipo.descripcion as tipo2,inv_cat_tipo_equipo.campos,cat_areas.area  FROM inv_general,inv_cat_tipo_equipo,cat_areas 
		WHERE  inv_general.id=$id_equipo AND inv_general.id_tipo_equipo=inv_cat_tipo_equipo.id AND inv_general.id_area_resp_mantto=cat_areas.id ORDER BY inv_general.id 	LIMIT 1; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					?><h4 align="center">detalle del equipo <?=$id_equipo?></h4>
					<table align="center" width="70%" cellspacing="0" cellpadding="3" class="tabla_bordes">
						<tr><td width="27%">id</td>
						<td width="73%">&nbsp;<?=$reg["id"]?></td>
						</tr>
						<tr><td>activo</td><td>&nbsp;<?php if($reg["activo"]) echo "SI"; else echo "NO";?></td></tr>
						<tr><td>fecha alta</td><td>&nbsp;<?=$reg["fecha_alta"]?></td></tr>
						<tr><td>fecha baja</td><td>&nbsp;<?=$reg["fecha_baja"]?></td></tr>
						<tr><td>nombre_infraestructura</td><td>&nbsp;<?=$reg["nombre_infraestructura"]?></td></tr>
						<tr><td>id_tipo_equipo</td><td>&nbsp;<?=$reg["tipo2"]?><div id="div_inv_detalle2"><?php
						$campos=$reg["campos"];
						if($campos!==''){
							$m_campos=explode(',',$campos);
							
							//$m_columnas
							
							?><br /><table align="center" width="99%" cellspacing="0" cellpadding="3" class="tabla_bordes" id="tbl_env_campos1">
								<?php 
								$contadorX1=0;
								foreach($m_campos as $campo){ ?>
								<tr>
									<td>&nbsp;<?=$campo?></td>
									<td><input type="text" id="txt_guardar_campos<?=$contadorX1?>" /></td>
								</tr>
								
								<?php
									++$contadorX1;
								}
							?></table><br />
							<div align="center"><a href="#" class="link_02" onclick="inv_guardar_campos(<?=$reg["id_tipo_equipo"]?>)" >guardar [<?=$reg["id_tipo_equipo"]?>]</a></div>
							
							<?php
						}else{
						
						}
						?></div></td></tr>
						<tr><td>id_area_resp_mantto</td><td>&nbsp;<?=$reg["area"]?></td></tr>
						<tr><td>status</td><td>&nbsp;<?=$reg["status"]?></td></tr>
						<tr><td>ubicacion</td><td>&nbsp;<?=$reg["ubicacion"]?></td></tr>
						<tr><td>obs</td><td>&nbsp;<?=$reg["obs"]?></td></tr>
					</table>
					<div id="div_inv_guardar_campos_resultado"></div>
					<?php
					
					//echo "<br>"; 	print_r($reg);
				}
				
			}else{ echo "<br>no se encontraron resultados "; exit; }
		} else{ echo "<br>Error SQL (".mysql_error($link).")."; exit;	}
		?>
		<br /><br /><div align="center"><a href="#" onclick="ocultar_mostrar_capa('div_inventario_detalle1','div_inventario_lista1')" class="link_02" >cerrar</a></div><br />
		<?php		
	}
	function inv_guardar_campos($id_tipo_equipo,$valores){
		echo "<br>inv_guardar_campos($id_tipo_equipo===$valores)";
		$m_valores=explode(',',$valores);
		//$sql0="INSERT INTO inv_cat_tipo_equipo_campos() VALUES (); ";
		$sql_valores='';
		foreach($m_valores as $valorX){
			//($sql_valores=='')?$sql_valores="'$valorX'":
			$sql_valores.=",'$valorX'";
		}
		echo "<br>".$sql0="INSERT INTO inv_cat_tipo_equipo_campos VALUES (NULL,$id_tipo_equipo$sql_valores); ";
		$this->executa_sql($sql0);
	}
	
	
	// ----------------------------------------------------------------------------------------
	function executa_sql($sql){
		include("../conf/conexion.php");
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			return true; 
		}else{ echo "<br>Error SQL (".mysql_error($link).")."; return false;	}	
	}
}
?>