<?php
class area{
	public function listar($pagina,$campo,$operador,$criterio,$orden,$ad){
		($operador=='LIKE')? $campo_operador_criterio=" $campo LIKE '%$criterio%' ":$campo_operador_criterio=" $campo$operador'$criterio' ";
		// Calculo y paginacion ...
		//echo "<br>(pagina,campo,operador,criterio,orden,ad)=($pagina,$campo,$operador,$criterio,$orden,$ad)";
		$paginacion_sql_total="SELECT count(id) FROM cat_areas $whereX; ";
		$paginacion_total_resultados=$this->dame_no_resultados($paginacion_sql_total);
		$paginacion_resultados_x_pagina=20;
		$paginacion_no_paginas_resultantes=round(ceil($paginacion_total_resultados/$paginacion_resultados_x_pagina));	
		($pagina<=1)? $limite_inferior=0 : $limite_inferior=($pagina-1)*$paginacion_resultados_x_pagina;	
		($paginacion_no_paginas_resultantes<=1)?$paginacion_requerida=false:$paginacion_requerida=true;
		include("../conf/conexion.php");
		$sql="SELECT * FROM cat_areas $whereX ORDER BY $orden $ad LIMIT $limite_inferior,$paginacion_resultados_x_pagina; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){ ?>
                <div id="div_usuario_detalle"></div>
                <div id="div_usuario_lista">
                <div style="text-align:center; font-weight:bold; margin:2px 2px 5px 2px; ">cat&aacute;logo de areas</div>
                <table align="center" width="80%" cellspacing="0" cellpadding="3" class="tabla_bordes">
                <tr>
                    <th>#</th>
                    <th>descripci&oacute;n</th>
                    <th>servicio</th>
                    <th>obs</th>
                </tr><?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr onmouseover="this.style.background='#ffffff';" onmouseout="this.style.background=''">
						<td align="center"><?=$reg["id"]?></td>
						<td><?=$reg["area"]?></td>
						<td align="center"><?php if($reg["servicio"]==1) echo "SI"; else echo "NO"; ?></td>
						<td><?=$reg["obs"]?></td>
					</tr><?php
				}
				?></table>
				<div style="text-align:center; font-size:small; margin-top:3px;">
					<?php
					echo $paginacion_total_resultados." resultados  &nbsp;&nbsp;&nbsp;";
					if($paginacion_requerida){
echo "<b>"; 
if($pagina>1){
	?>
	<a href="#" onclick="ajax('d221','ac=listar_areas&pagina=1&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="primera pagina">&nbsp;|&lt;&nbsp;</a><?php
}else{ echo "&nbsp; |&lt; &nbsp;"; }					
if($pagina>1){
	?><a href="#" onclick="ajax('d221','ac=listar_areas&pagina=<?=$pagina-1?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="pagina anterior">&nbsp;&lt;&nbsp;</a><?php }else{ echo " &nbsp;&lt;&nbsp; "; }					
echo " &nbsp;$pagina / $paginacion_no_paginas_resultantes&nbsp; ";
if($pagina<$paginacion_no_paginas_resultantes){
	?><a href="#" onclick="ajax('d221','ac=listar_areas&pagina=<?=$pagina+1?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="pagina siguiente">&nbsp;&gt;&nbsp;</a><?php }else{ echo " &nbsp;&gt;&nbsp; "; }
if($pagina<$paginacion_no_paginas_resultantes){
	?><a href="#" onclick="ajax('d221','ac=listar_areas&pagina=<?=$paginacion_no_paginas_resultantes?>&campo=<?=$campo?>&operador=<?=$operador?>&criterio=<?=$criterio?>&orden=<?=$orden?>&ad=<?=$ad?>')" class="link_02" title="ultima pagina">&nbsp;&gt;|&nbsp;</a><?php
}else{ echo " &nbsp;&gt;|&nbsp; "; }					
echo " </b>";
					}

					?>
					<a href="#" onclick="nueva_area()">nueva &aacute;rea</a>	
				</div>				
				
				
				
				
				
				
				</div><?php
			}else{ echo "<br>Sin resultados."; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}
	
	}
	function dame_nombre_area($i){
		include("../conf/conexion.php");
		$sql="SELECT area FROM cat_areas WHERE id=$i LIMIT 1; ";
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
	
	function insertar($a,$s,$o){
		//echo "insertar($a,$s,$o)";
		
		$sql="INSERT INTO cat_areas(id,area,servicio,obs) VALUES (NULL,'$a','$s','$o'); ";
		
		include("../conf/conexion.php");
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			?>
			<script language="javascript">
			ajax('d221','ac=listar_areas');
			</script>
			<?php
			exit;
		} else{ return "<br>Error SQL (".mysql_error($link)."). <br> Los datos no se guardaron. "; exit; 	}		
		
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