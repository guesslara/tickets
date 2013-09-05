<?php
class consulta{
	function opciones($n){
		//echo "<br>O=[$n]";
		if ($n==0) $this->estadisticas();
		if ($n==1) $this->listar_areas();
		if ($n==2) $this->listar_tipo_tickets();
		if ($n==3) $this->listar_usuarios();
		if ($n==4) {
			require_once("../clases/evaluacion.php");	
			$e1=new evaluacion();
			$e1->estadisticas();		
		}
		if ($n==5) {
			require_once("../clases/evaluacion.php");	
			$e1=new evaluacion();
			$e1->listar();		
		}		
	}
	
	public function listar_usuarios(){
		include("../conf/conexion.php");
		$sql="SELECT id,activo,grupo,nivel,usuario,nombre,apellidos FROM cat_usuarios WHERE grupo=".$_SESSION["usuario_grupo"]." ORDER BY id; ";
		if($_SESSION["usuario_nivel"]==0) $sql="SELECT id,activo,grupo,nivel,usuario,nombre,apellidos FROM cat_usuarios ORDER BY id; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
                <div id="div_usuario_detalle"></div>
                <div id="div_usuario_lista">
                <h3 align="center">cat&aacute;logo de usuarios</h3>
                <table align="center" width="95%" cellspacing="0" cellpadding="2" class="tabla_bordes">
                <tr style="font-weight:bold; text-align:center; ">
                    <th width="2%">#</th>
                    <th width="39%">nombre</th>
                    <th width="26%">grupo</th>
                    <th width="3%">nivel</th>
                    <th width="13%">no. tickets emitidos </th>
                    <th width="12%">no. tickets atendidos </th>
                    <!--<th width="5%">acciones</th>//-->
                </tr><?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr>
						<td align="center"><?=$reg["id"]?></td>
						<td><?=$reg["nombre"]." ".$reg["apellidos"]?></td>
						<td>&nbsp;<?php
                        	//echo $reg["grupo"];
							require_once("area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg["grupo"]);                        
						//$reg["grupo"]
						?></td>
						<td align="center"><?=$reg["nivel"]?></td>
						<td align="center"><?php
							$sql2="SELECT count(id) FROM reg_tickets WHERE id_usuario=".$reg["id"]."; ";
							echo $this->dame_no_resultados($sql2);
						?></td>
                        <td align="center"><?php
							$sql3="SELECT count(id) FROM reg_tickets WHERE atiende=".$reg["id"]."; ";
							echo $this->dame_no_resultados($sql3);						
						?></td>
                        <!--<td align="center"><a href="#" class="link_02" onclick="ajax('div_usuario_detalle','ac=ver_usuario&iu=<?=$reg["id"]?>','div_usuario_lista')">ver</a></td>//-->
					</tr><?php					
				}
				?></table>
                </div><?php
			}else{ echo "<br>Sin resultados."; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}		

	}
	function listar_tipo_tickets(){
		include("../conf/conexion.php");	//WHERE id_area=$area
		$sql="SELECT * FROM cat_tipo_ticket  ORDER BY id_area ASC,id ASC; ";
		if($_SESSION["usuario_nivel"]==1) $sql="SELECT * FROM cat_tipo_ticket WHERE id_area=".$_SESSION["usuario_grupo"]." ORDER BY id_area ASC,id ASC; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
				<h3 align="center">tipos de ticket </h3>
				<table width="95%" align="center" cellspacing="0" cellpadding="3" class="tabla_bordes">
				<tr>
					<th width="2%">#</th>
					<th width="23%">area</th>
					<th width="39%">descripcion</th>
					<th width="5%">horas </th>
					<th width="8%">no. tickets </th>
					<th width="12%">horas promedio  </th>
					<th width="11%">fuera de tiempo </th>
				</tr>				
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr>
						<td align="center"><?=$reg[0]?></td>
						<td><?php
							//echo $reg[1];
							require_once("area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg[1]);							
						?></td>
						<td>&nbsp;<?=strtolower($reg[2])?></td>
						<td align="center"><?=$reg[3]?></td>
						<td align="center"><?php
							$sql2="SELECT COUNT(id) FROM reg_tickets WHERE area=".$_SESSION["usuario_grupo"]." AND tipo_ticket=".$reg[0];
							if($_SESSION["usuario_grupo"]==0) $sql2="SELECT COUNT(id) FROM reg_tickets WHERE tipo_ticket=".$reg[0];
							//echo $sql2;
							echo $this->dame_no_resultados($sql2);
							
							// Horas promedio
							$sql_hp="SELECT AVG(horas_consumidas) FROM reg_tickets WHERE area=".$_SESSION["usuario_grupo"]." AND tipo_ticket=".$reg[0];
							if($_SESSION["usuario_grupo"]==0) $sql_hp="SELECT AVG(horas_consumidas) FROM reg_tickets WHERE tipo_ticket=".$reg[0];
			/*				
			// FUERA DE TIEMPO ...
			$sql_fuera_tiempo="SELECT count(reg_tickets.id) as no_tfdt 
			FROM reg_tickets,cat_tipo_ticket
			WHERE cat_tipo_ticket.id=reg_tickets.tipo_ticket 
				AND reg_tickets.area=".$_SESSION["usuario_grupo"]." 
				AND reg_tickets.status='FINALIZADO'
				AND horas_consumidas>horas_definidas
				AND fecha BETWEEN '$year-$indice-01' AND '$year-$indice-31'; ";							
			*/				
							
							?></td>
						<td align="right">&nbsp;<?=round($this->dame_no_resultados($sql_hp),2)?></td>
						<td>&nbsp;</td>
					</tr>
					<?php
				}
				?>
				</table><br />
				<?php
			}else{ echo "<br><div align='center'>Sin resultados.</div>"; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}
		/*?><div style="text-align:center; margin:10px;"><a href="#" class="link_02" onclick="agregar_tipo_ticket()">agregar</a></div><?php*/	
	}
	function estadisticas(){
		
		if($_SESSION["usuario_id"]==1&&$_SESSION["usuario_nivel"]==0){
			echo "<h3 align='center'>eficiencia de las &aacute;reas</h3>";
			$m_indice_area=array();
			$m_descripcion_area=array();
			$m_eficiencia_x_area=array();
			$m_colores=array('#009900','#FF9900','#FF6600','#FF0000');		// rojo [#FF0000] amarillo [#FF9900] naranja [#FF6600]  verde [#009900]
			$m_colores_valores=array('mayor a 95 %','entre 90 % y 95 %','entre 80 % y 90 %','menor a 80 %');		// rojo [#FF0000] amarillo [#FF9900] naranja [#FF6600]  verde [#009900]
			
			
			include("../conf/conexion.php");	
			$sql="SELECT id,area FROM cat_areas WHERE servicio=1  ORDER BY id; ";
			if ($res=mysql_db_query($db_actual,$sql,$link)){ 
				$ndr=mysql_num_rows($res);
				if($ndr>0){			
					while($reg=mysql_fetch_array($res)){
						//echo "<br>"; 	print_r($reg);
						array_push($m_indice_area,$reg["id"]);
						array_push($m_descripcion_area,$reg["area"]);
					}				
				}else{ echo "<br><div align='center'>Sin resultados.</div>"; }
			} else{ echo "<br>Error SQL (".mysql_error($link).").";	}			
			
			/*
			echo " <br> -----------------------------------------";
			echo "<br>"; 	print_r($m_indice_area);
			echo "<br>"; 	print_r($m_descripcion_area);
			echo " <br> -----------------------------------------";
			*/
			
			for($i=0;$i<count($m_indice_area);$i++){
				//echo "<br>".$m_indice_area[$i]." ".$m_descripcion_area[$i]." ".floatval($this->dame_eficiencia_x_area($m_indice_area[$i]));
				$m_eficiencia_x_area[$i][0]=$m_descripcion_area[$i];
				$m_eficiencia_x_area[$i][1]=floatval($this->dame_eficiencia_x_area($m_indice_area[$i]));
			}
			//echo " <br> -----------------------------------------";
			//echo "<br>"; 	print_r($m_eficiencia_x_area);
			
			// ----------------- area del grafico ------------------------------------
				// CSS
				?>
				<style type="text/css">
				/*document,body,html{ position:absolute; overflow:hidden; background-color:#fff; width:100%; height:100%; margin:0px; font-family:"Courier New", Courier, monospace; }
				#B{ position:relative; overflow:auto; width:100%; height:100%; margin:0px; }*/
				#B1{ position:relative; width:700px; height:460px; left:50%; margin-top:10px; margin-left:-350px; border:#CCCCCC 1px solid; background-color:#FFFFFF; }
				
				.grafica_x_area_escala{ position:relative; width:30px; height:453px; margin:2px; float:left;}
					.grafica_x_area_escalaA{ position:relative; height:20px; text-align:center; font-weight:bold; font-size:large; }
					.grafica_x_area_escalaB{ position:relative; height:400px; background-image:url(../img/escala_400px.png); background-repeat:no-repeat;  filter: alpha(opacity=60); opacity: .6; }
					.grafica_x_area_escalaC{ position:relative; height:30px; text-align:center; font-size:small; }
				.grafica_x_area{ position:relative; width:110px; height:453px; margin:2px; float:left; /*border:#CCCCCC 1px solid;*/ }
					.grafica_x_area_valor{ position:relative; height:20px; text-align:center; font-weight:normal; font-size:small; }
					.grafica_x_area_grafica{ position:relative; height:400px; background-color:#efefef; }
						.grafica_x_area_grafica1{ position:relative; height:0px; background-color:#009900; filter: alpha(opacity=60); opacity: .6; }
					.grafica_x_area_descripcion{ position:relative; height:30px; background-color:#efefef; text-align:center; font-size:12px; font-weight:bold; padding-top:2px; }
					
					
				.grafica_acotaciones{ position:relative; width:200px; height:450px; margin:2px; float:left; }
					.grafica_acotacionesA{ position:relative; height:20px; }
					.grafica_acotacionesB{ position:relative; height:150px; margin-top:20px; }	
						.grafica_acotacionesB11_color{ position:relative; float:left; clear:left; width:20px; height:20px; margin:2px 2px 2px 5px; border:#000000 1px solid; }
						.grafica_acotacionesB11_descripcion{  position:relative; float:left; clear:right; width:160px; height:18px; margin:2px; border:#efefef 1px solid; font-size:small; text-align:left; padding:1px; /*background-color:#efefef;*/ }
				</style>
<div id="B">
	<div id="B1">
		<div class="grafica_x_area_escala">
			<div class="grafica_x_area_escalaA">&nbsp;</div>
			<div class="grafica_x_area_escalaB">&nbsp;</div>
			<div class="grafica_x_area_escalaC">&nbsp;</div>
		</div>
		<?php
		$m_areas=$m_eficiencia_x_area;
		//print_r($m_areas);
		$contador_graficas=0;
		for($index=0;$index<count($m_areas);$index++){
			//echo "<br>".$m_areas[$index][0]." - ".$m_areas[$index][1];
			$area=$m_areas[$index][0];
			$eficiencia=$m_areas[$index][1];
			
			$altura_grafica=400;
			$altura_grafica_sombreada=$altura_grafica*$eficiencia/100;
			$margen_superior=$altura_grafica-$altura_grafica_sombreada;
			if ($eficiencia>=95) $nuevo_color_grafica=$m_colores[0];
			else if ($eficiencia>=90&&$eficiencia<95) $nuevo_color_grafica=$m_colores[1];
			else if ($eficiencia>=80&&$eficiencia<90) $nuevo_color_grafica=$m_colores[2];
			else if ($eficiencia<80) $nuevo_color_grafica=$m_colores[3];
			?>
			<div class="grafica_x_area">
				<div id="grafica_x_area_valor<?=$contador_graficas?>" class="grafica_x_area_valor"><?=$eficiencia?> % </div>
				<div id="grafica_x_area_grafica<?=$contador_graficas?>" class="grafica_x_area_grafica">
					<div id="grafica_x_area_grafica1<?=$contador_graficas?>" class="grafica_x_area_grafica1">&nbsp;</div>
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
		<div class="grafica_acotaciones">
			<div class="grafica_acotacionesB">
				<div class="grafica_acotacionesB1">
					<?php
					for($iX=0;$iX<count($m_colores);$iX++){
						//echo "<div class='grafica_acotacionesB11' style='background-color:$acotacion_color; '>$acotacion_color</div>";
						?>
						<div class="grafica_acotacionesB11">
							<div class="grafica_acotacionesB11_color" style="background-color:<?=$m_colores[$iX]?>;">&nbsp;</div>
							<div class="grafica_acotacionesB11_descripcion">&nbsp;&nbsp;<?=$m_colores_valores[$iX]?></div>
						</div>						
						<?php
					}
					?>

				</div>
			</div>
		</div>		
	</div>
</div>
								
				<?php
			
			// ----------------- area del grafico ------------------------------------
			exit;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		echo "<h3 align='center'>estad&iacute;sticas</h3>";
		echo "<h5 align='center'>".strtolower($this->dame_no_resultados("SELECT area FROM cat_areas WHERE id=".$_SESSION["usuario_grupo"].";"))."</h5>";

		/*
		$sql_0="SELECT count(id) FROM reg_tickets; ";
		$sql_1="SELECT count(id) FROM cat_areas; ";
		$sql_2="SELECT count(id) FROM cat_areas WHERE servicio=1; ";
		$sql_3="SELECT count(id) FROM cat_usuarios; ";
		?>
		<h3 align="center">estad&iacute;sticas</h3>
		<table width="400" align="center" cellspacing="0" cellpadding="3" class="tabla_bordes">
		<tr>
			<td width="283">No. Ordenes de Servicio:</td>
			<td width="103" align="right">&nbsp;<?=$this->dame_no_resultados($sql_0)?></td>
		</tr>
		<tr>
		  <td>No. Areas: </td>
		  <td align="right">&nbsp;<?=$this->dame_no_resultados($sql_1)?></td>
		  </tr>
		<tr>
		  <td>No. Areas de Servicio: </td>
		  <td align="right">&nbsp;<?=$this->dame_no_resultados($sql_2)?></td>
		  </tr>
		<tr>
		  <td>No. Usuarios: </td>
		  <td align="right">&nbsp;<?=$this->dame_no_resultados($sql_3)?></td>
		  </tr>
		</table>		
		<?php
		*/
		$year=date("Y");
		$pendientes_mes_anterior=0;
		$total_solicitados=0;
		
		//$m_meses=array("01"=>"Ene","02"=>"Feb","03"=>"Mar","04"=>"Abr","05"=>"May","06"=>"Jun","07"=>"Jul","08"=>"Ago","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic");
		$m_meses=array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
		echo "<table align='center' width='98%' class='tabla_bordes' cellspacing='0' cellpadding='2'>";
		echo "<tr>";
			echo "<th>mes</th><th>solicitados</th><th>pend. mes<br> anterior</th><th>total<br> solicitados</th><th>atendidos</th><th>cancelados</th><th>pendientes</th><th>fuera de<br> tiempo</th><th>eficiencia %</th>";		//<th>eficacia %</th>
		echo "</tr>";
		foreach($m_meses as $indice=>$descripcion){
			//echo "<br>$indice=>$descripcion - $sql_x";
			//echo "<br>"."SELECT count(id) FROM reg_tickets WHERE area=".$_SESSION["usuario_grupo"]." AND fecha BETWEEN '$year-10-01' AND '$year-$indice-31'; ";
			$sql_solicitados=$this->dame_no_resultados("SELECT count(id) FROM reg_tickets WHERE area=".$_SESSION["usuario_grupo"]." AND fecha BETWEEN '$year-$indice-01' AND '$year-$indice-31'; ");
			$sql_atendidos=$this->dame_no_resultados("SELECT count(id) FROM reg_tickets WHERE area=".$_SESSION["usuario_grupo"]." AND fecha_fin BETWEEN '$year-$indice-01' AND '$year-$indice-31'; ");
			
			// FUERA DE TIEMPO ...
			$sql_fuera_tiempo="SELECT count(reg_tickets.id) as no_tfdt 
			FROM reg_tickets,cat_tipo_ticket
			WHERE cat_tipo_ticket.id=reg_tickets.tipo_ticket 
				AND reg_tickets.area=".$_SESSION["usuario_grupo"]." 
				AND reg_tickets.status='FINALIZADO'
				AND horas_consumidas>horas_definidas
				AND fecha BETWEEN '$year-$indice-01' AND '$year-$indice-31'; ";
			$tickets_fdt=$this->dame_no_resultados($sql_fuera_tiempo);				

			$total_solicitados=$sql_solicitados+$pendientes_mes_anterior;
			$sql_cancelados=$this->dame_no_resultados("SELECT count(id) as no_tickets_cancelados FROM reg_tickets WHERE status='CANCELADO' AND fecha BETWEEN '$year-$indice-01' AND '$year-$indice-31' AND area=".$_SESSION["usuario_grupo"].";");
			
			if($total_solicitados==$sql_atendidos){
				$sql_pendientes=0;
			}else{
				$sql_pendientes=$total_solicitados-$sql_atendidos-$sql_cancelados;
			}
			
			// -------------------- EFICACIA --------------------
			$eficacia=0;
			if($sql_atendidos>0) $eficacia=round($sql_atendidos/$total_solicitados*100,2);
			// -------------------- EFICIENCIA --------------------
			$eficiencia=0;
			if($sql_atendidos>0) $eficiencia=round(($sql_atendidos-$tickets_fdt)/$sql_atendidos*100,2);			
	
			// -------------------- AJUSTES --------------------
			
			if(date("m")<$indice){
				$total_solicitados=0;
				$pendientes_mes_anterior=0;
				$sql_pendientes=0;
			}
			
			if($tickets_fdt>0) $tickets_fdt="<span style='color:#ff0000;'>".$tickets_fdt."</span>";	
			if($sql_solicitados>0){ $a_sol="<a href='../reportes/estadisticas_detalle1.php?tipo=solicitados&mes=".$indice."&area=".$_SESSION["usuario_grupo"]."&ndr=$sql_solicitados' class='link_03' title='ver mas ...' target='_blank'>$sql_solicitados</a>"; }else{ $a_sol="$sql_solicitados"; }
			// Si pendientes es negativo, ajustar a $pendientes=$total_solicitados-$sql_atendidos;
			if($sql_pendientes<0){
				$sql_pendientes=$total_solicitados-$sql_atendidos;
			}
			if($sql_pendientes<0) $sql_pendientes=0;			
			
			
			
			echo "<tr>";
				echo "<td> $descripcion </td>";
				echo "<td align='right'> $a_sol </td>";
				echo "<td align='right'> $pendientes_mes_anterior </td>";
				echo "<td align='right'> <b>$total_solicitados</b> </td>";
				echo "<td align='right'> $sql_atendidos </td>";
				echo "<td align='right'> $sql_cancelados </td>";
				echo "<td align='right'> $sql_pendientes</td>";
				echo "<td align='right'> $tickets_fdt </td>";
				//echo "<td align='right'> <b>$eficacia</b> </td>";
				echo "<td align='right'> <b>$eficiencia</b> </td>";
			echo "</tr>";
			$pendientes_mes_anterior=$sql_pendientes;
		}
		echo "</table>";
		?><h3 align="center"><a href="../estadisticas/" target="_blank" style="padding:5px; font-size:small;" class="link_01">ver mas ...</a></h3><?php		
	}
	function listar_areas(){
		include("../conf/conexion.php");
		$m_areas=array();
		$m_valores_emitidos=array();
		$txt_areas="";
		$txt_emitidos="";
		$txt_solicitados="";
		$txt_atendidos="";
		$txt_pendientes="";
		
		if($_SESSION["usuario_nivel"]==0) $sql="SELECT id,area,servicio FROM cat_areas WHERE servicio=1 ORDER BY id; ";
		if($_SESSION["usuario_nivel"]==1) $sql="SELECT id,area,servicio FROM cat_areas WHERE servicio=1 AND id=".$_SESSION["usuario_grupo"]." ORDER BY id; ";
		//$sql="SELECT id,area,servicio FROM cat_areas ORDER BY id; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
                <div id="div_consulta_detalle_01"></div>
				<div id="div_consulta_detalle_02">
				<h3 align="center">tickets por &aacute;reas</h3>
                <table align="center" cellspacing="0" cellpadding="3" class="tabla_bordes">
                <tr>
                	<th>area</th>
                    <th>emitidos</th>
                    <th>solicitados</th>
                    <th>atendidos</th>
                    <th>pendientes</th>
                    <th>eficacia</th>
                    <th>eficiencia</th>
                    <th>acciones</th>
                </tr>
				<?php
				while($reg=mysql_fetch_array($res)){
					array_push($m_valores_emitidos,$this->dame_no_tickets_emitidos_x_area($reg[0]));
					
					$emitidos=$this->dame_no_tickets_emitidos_x_area($reg[0]);
					$solicitados=$this->dame_no_tickets_solicitados_x_area($reg[0]);
					$atendidos=$this->dame_no_tickets_atendidos_x_area($reg[0]);
					$pendientes=$solicitados-$atendidos;
					if($reg["servicio"]==1){
						$eficacia_x_area=round($atendidos/$solicitados*100)."%";
						$eficiencia_x_area=$this->dame_eficiencia_x_area($reg["id"]);
					}else{
						$eficacia_x_area="&nbsp;";
						$eficiencia_x_area="&nbsp;";
					}
					
					
					($txt_areas=="")? $txt_areas=$reg["area"]:$txt_areas.=",".$reg["area"];
					
					($txt_emitidos=="")? $txt_emitidos=$emitidos:$txt_emitidos.=",".$emitidos;
					($txt_solicitados=="")? $txt_solicitados=$solicitados:$txt_solicitados.=",".$solicitados;
					($txt_atendidos=="")? $txt_atendidos=$atendidos:$txt_atendidos.=",".$atendidos;
					($txt_pendientes=="")? $txt_pendientes=$pendientes:$txt_pendientes.=",".$pendientes;
					

					
					
					/*
					echo "<br><br>"; 	print_r($reg);
					echo "<br>NDT emitidos=".$this->dame_no_tickets_emitidos_x_area($reg[0]);
					echo "<br>=================================================================";
					echo "<br>NDT solicitados=".$this->dame_no_tickets_solicitados_x_area($reg[0]);
					echo "<br>NDT atendidos=".$this->dame_no_tickets_atendidos_x_area($reg[0]);
					echo "<br>NDT pedientes=".$this->dame_no_tickets_solicitados_x_area($reg[0])."-".$this->dame_no_tickets_atendidos_x_area($reg[0]);
					*/
					?>
                    <tr>
                        <td><?php 
						if($reg["servicio"]==1){ 
							echo "<u>".$reg[1]."</u>"; 
						}else{
							echo "".$reg[1];
						} ?></td>
                        <td align="center"><?=$emitidos?></td>
                        <td align="center"><?=$solicitados?></td>
                        <td align="center"><?=$atendidos?></td>
                        <td align="center"><?=$pendientes?></td>
                        <td align="right"><?=$eficacia_x_area?></td>
                        <td align="right">&nbsp;<?=$eficiencia_x_area?></td>
                        <td align="center">
						<?php if($reg["servicio"]==1){ ?>
						<a href="#" class="link_02" onclick="ajax('div_consulta_detalle_01','ac=ver_detalle_consulta_area&area=<?=$reg[0]?>','div_consulta_detalle_02')">ver mas</a>
						<?php }else{ echo "&nbsp;"; }?>
						</td>
                    </tr>					
					<?php
				}
				/*
				if($_SESSION["usuario_nivel"]==0){
				?>
				<tr>
				  <td>&nbsp;</td>
				  <td align="center"><a href="../graficas/grafica_pastel.php?titulo=Tickets emitidos&valores=<?=$txt_emitidos?>&areas=<?=$txt_areas?>" target="_blank" class="link_02">graficar</a></td>
				  <td align="center"><a href="../graficas/grafica_pastel.php?titulo=Tickets solicitados&valores=<?=$txt_solicitados?>&areas=<?=$txt_areas?>" target="_blank" class="link_02">graficar</a></td>
				  <td align="center"><a href="../graficas/grafica_pastel.php?titulo=Tickets atendidos&valores=<?=$txt_atendidos?>&areas=<?=$txt_areas?>" target="_blank" class="link_02">graficar</a></td>
				  <td align="center"><a href="../graficas/grafica_pastel.php?titulo=Tickets pendientes&valores=<?=$txt_pendientes?>&areas=<?=$txt_areas?>" target="_blank" class="link_02">graficar</a></td>
				  <td align="right">&nbsp;</td>
				  <td align="right">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				</tr>
				<?php } */ ?>				
				</table></div>
                <?php
			}else{ echo "<br>Sin resultados."; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}
	
	}
	
	function dame_no_resultados($sql){
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

	function dame_no_tickets_emitidos_x_area($id_area){
		include("../conf/conexion.php");
		//$sql="SELECT count(id) FROM reg_tickets WHERE area=$id_area; ";
		$sql="SELECT count(reg_tickets.id) 
		FROM cat_areas,cat_usuarios,reg_tickets 
		WHERE
			reg_tickets.id_usuario=cat_usuarios.id
			AND cat_areas.id=cat_usuarios.grupo
			AND cat_areas.id=$id_area; ";		
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
	function dame_no_tickets_solicitados_x_area($id_area){
		include("../conf/conexion.php");
		$sql="SELECT count(id) FROM reg_tickets WHERE area=$id_area; ";
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
	function dame_no_tickets_atendidos_x_area($id_area){
		include("../conf/conexion.php");
		//$sql="SELECT count(id) FROM reg_tickets WHERE area=$id_area; ";
		//echo "<br>".
		$sql="SELECT count(reg_tickets.id) 
		FROM cat_areas,cat_usuarios,reg_tickets 
		WHERE
			reg_tickets.atiende=cat_usuarios.id
			AND cat_areas.id=cat_usuarios.grupo
			AND cat_areas.id=$id_area; ";
			
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
	
	function detalle_area($id_area){
		?><h4 align="center">eficiencia del area <?=$id_area?></h4><?php
		$solicitados=$this->dame_no_tickets_solicitados_x_area($id_area);
		$tickets_a_tiempo=0;
		$tickets_fuera_tiempo=0;
		$tickets_cancelados=$this->dame_no_resultados("SELECT count(id) as no_tickets_cancelados FROM reg_tickets WHERE status='CANCELADO';");
		include("../conf/conexion.php");
		//echo "<br>".
		$sql="SELECT reg_tickets.id,reg_tickets.area,reg_tickets.status,reg_tickets.tipo_ticket,reg_tickets.horas_consumidas, cat_tipo_ticket.horas_definidas,cat_tipo_ticket.descripcion
		FROM reg_tickets,cat_tipo_ticket
		WHERE cat_tipo_ticket.id=reg_tickets.tipo_ticket AND reg_tickets.area=$id_area AND reg_tickets.status='FINALIZADO'; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				?>
				<table align="center" width="95%" cellspacing="0" cellpadding="2" class="tabla_bordes" id="tbl_eficiencia_detalle" style="display:none;">
				<tr style="font-size:small;">
					<th width="9%">id ticket</th>
					<th width="6%">status</th>
					<th width="44%">tipo</th>
					<th width="11%">horas definidas</th>
					<th width="11%">horas consumidas</th>
					<th width="19%">obs</th>
				</tr>
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br><br>"; 	print_r($reg);
					//return $reg[0];
					($reg["horas_consumidas"]<=$reg["horas_definidas"])? ++$tickets_a_tiempo:++$tickets_fuera_tiempo;
					?>
					<tr style="font-size:small;">
						<td align="center"><?=$reg["id"]?></td>
						<td><?=$reg["status"]?></td>
						<td><?=$reg["descripcion"]?></td>
						<td align="center"><?=$reg["horas_definidas"]?></td>
						<td align="center"><?=$reg["horas_consumidas"]?></td>
						<td align="center"><?php
							if($reg["horas_consumidas"]<=$reg["horas_definidas"]){
								echo "<span style='color:green; font-size:small;'>EN TIEMPO</span>";
							}else{
								echo "<span style='color:red; font-size:small;'>FUERA DE TIEMPO</span>";
							}
							
						?></td>
					</tr>
					<?php
				}
				?></table><?php
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}			
		if($solicitados>0) $eficiencia=round(($tickets_a_tiempo/$ndr*100),2);
		else $eficiencia=0;
		echo "<br><div align='center'><b>eficiencia</b>=(tickets finalizados a tiempo /no. de tickets finalizados)</div>";
		?>
		<br><div align="center">
			<a href="#" class="link_02" onClick="mostrar_capa('tbl_eficiencia_detalle')">ver detalle</a> | 
			<a href="#" class="link_02" onClick="ocultar_capa('tbl_eficiencia_detalle')">ocultar detalle</a>
		</div>
		<br /><table align="center" width="50%" cellspacing="0" cellpadding="2" class="tabla_bordes">
		<!--<tr><th width="73%">id ticket</th><th width="27%">status</th></tr>-->		
		
		<tr><td>no. tickets solicitados</td><td align="center">&nbsp;<?=$solicitados?></td></tr>
		<tr><td>no. tickets finalizados</td><td align="center">&nbsp;<?=$ndr?></td></tr>
		<tr><td>no. tickets finalizados en tiempo</td><td align="center">&nbsp;<?=$tickets_a_tiempo?></td></tr>
		<tr><td>no. tickets finalizados fuera de tiempo</td><td align="center">&nbsp;<?=$tickets_fuera_tiempo?></td></tr>		
		<tr><td>no. tickets cancelados </td><td align="center">&nbsp;<?=$tickets_cancelados?></td></tr>
		<tr><td>eficiencia</td><td align="center">&nbsp;<span style="font-size:xx-large; color:<?php if($eficiencia>=90) echo 'green'; else echo 'red'; ?>;"><?=$eficiencia?>%</span></td></tr>		

		</table>
		<div align="center"><br><a href="#" class="link_02" onClick="ocultar_mostrar_capa('div_consulta_detalle_01','div_consulta_detalle_02')">cerrar</a></div>
		<?php
		//echo "<br><br>A=$id_area S=[$solicitados] F=[$ndr] AT=[$tickets_a_tiempo] FT=[$tickets_fuera_tiempo] EF=[$eficiencia]";
		//return $eficiencia;
	}
	function dame_eficiencia_x_area($id_area){
		$solicitados=$this->dame_no_tickets_solicitados_x_area($id_area);
		$tickets_a_tiempo=0;
		$tickets_fuera_tiempo=0;
		include("../conf/conexion.php");
		//echo "<br>".
		$sql="SELECT reg_tickets.id,reg_tickets.area,reg_tickets.status,reg_tickets.tipo_ticket,reg_tickets.horas_consumidas, cat_tipo_ticket.horas_definidas 
		FROM reg_tickets,cat_tipo_ticket
		WHERE cat_tipo_ticket.id=reg_tickets.tipo_ticket AND reg_tickets.area=$id_area AND reg_tickets.status='FINALIZADO'; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){	
				while($reg=mysql_fetch_array($res)){
					//echo "<br><br>"; 	print_r($reg);
					//return $reg[0];
					($reg["horas_consumidas"]<=$reg["horas_definidas"])? ++$tickets_a_tiempo:++$tickets_fuera_tiempo;
				}
			}else{ return " "; }
		} else{ return "<br>Error SQL (".mysql_error($link).").";	}			
		if($solicitados>0) $eficiencia=round($tickets_a_tiempo/$ndr*100,2);
		else $eficiencia=0;
		//echo "<br><br>A=$id_area S=[$solicitados] F=[$ndr] AT=[$tickets_a_tiempo] FT=[$tickets_fuera_tiempo] EF=[$eficiencia]";
		return $eficiencia."%";
	}	
}
?>