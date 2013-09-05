<?php
class evaluacion{
	var $m_parametros=array('5'=>'Excelente','4'=>'Bueno','3'=>'Regular','2'=>'Aceptable','1'=>'Malo');
	public function insertar($id_ticket,$tiempo,$servicio,$obs){
		//echo "<br>insertar($id_ticket,$tiempo,$servicio,$obs)";
		$sql="INSERT INTO reg_evaluaccion(`id`,`fecha`,`hora`, `id_ticket`,`evaluacion_automatica`, `parametro_tiempo`,`parametro_servicio`,`parametro_obs`) VALUES (NULL,'".date("Y-m-d")."','".date("H:i:s")."','$id_ticket','0','$tiempo','$servicio','$obs'); ";
		$sql2="UPDATE reg_tickets SET evaluado=1 WHERE id=$id_ticket LIMIT 1; ";
		//echo "<br>$sql<br>$sql2";
		
		if($this->ejecuta_sql($sql)&&$this->ejecuta_sql($sql2)){
			echo "<h3 align='center'>Datos guardados correctamente.</h3>";
		}else{
			echo "<h3 align='center'>Error SQL.</h3>";
		}
	}
	public function listar(){
		//$this->estadisticas();
		//exit;
		
		$sql="SELECT reg_evaluaccion.*,reg_tickets.area,reg_tickets.id_usuario,reg_tickets.atiende 
		FROM reg_evaluaccion,reg_tickets 
		WHERE 
			reg_evaluaccion.id_ticket=reg_tickets.id 
			AND reg_tickets.area='".$_SESSION["usuario_grupo"]."'
		ORDER BY reg_evaluaccion.id DESC;";
		include("../conf/conexion.php");
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
                <div id="div_ticket_detalle"></div>
                <div id="div_ticket_lista">
                <h3 align='center'>evaluaci&oacute;n de tickets</h3>
                <table align="center" width="98%" cellspacing="0" cellpadding="3" class="tabla_bordes">
                <tr>
                    <th>#</th>
                    <th>fecha</th>
                    <th>id_ticket</th>
                    <th>tiempo</th>
                    <th>servicio</th>
                    <th>obs</th>
                    <th>area</th>
					<th>realiz&oacute;</th>
					<th>evalu&oacute;</th>
                </tr><?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr>
						<td align="center"><?=$reg["id"]?></td>
						<td align="center"><?=$reg["fecha"]." <a href='#' title='".$reg["hora"]."'  style='text-decoration:none;'>...</a>"?></td>
						<td align="center"><?=$reg["id_ticket"]?></td>
						<td>&nbsp;<?=$this->m_parametros[$reg["parametro_tiempo"]]?></td>
						<td>&nbsp;<?=$this->m_parametros[$reg["parametro_servicio"]]?></td>
					  	<td>&nbsp;<a href="#" title="<?=$reg["parametro_obs"]?>" style="text-decoration:none;"> ... </a></td>
                        <td align="center">&nbsp;<?php
							require_once("area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg["area"]);						
							//$reg["area"]
						
						?></td>
						<td align="left">&nbsp;<?php
							require_once("../clases/usuario.php");
							$u1=new usuario();
							echo " ".$u1->dame_nombre_usuario($reg["atiende"]);
							//$reg["atiende"]
						?></td>
						<td align="left">&nbsp;<?php
							require_once("../clases/usuario.php");
							$u1=new usuario();
							echo " ".$u1->dame_nombre_usuario($reg["id_usuario"]);						
						//$reg["id_usuario"]
						?></td>
                    </tr><?php					
				}
				?></table></div><?php
			}else{ echo "<br><div align='center'>no existen evaluaciones de &Oacute;rdenes de Servicio.</div>"; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}		
	}
	function estadisticas(){
		$m_meses=array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
		$year=2013;
		$criterios_evaluacion=array(1=>'Malo',2=>'Aceptable',3=>'Regular',4=>'Bueno',5=>'Excelente');
		//echo $sql_malos="select count(id) from reg_evaluaciones where fecha between '$year-$indice-01' and '$year-$indice-31';";
		?>
		<h3 align="center" >Evaluaciones</h3>
		<table align="center" width="98%" cellspacing="0" cellpadding="3" class="tabla_bordes">
		<tr>
			<th rowspan="2">mes</th>
			<th colspan="5">nivel de servicio</th>
			<th colspan="5">tiempo de respuesta</th>
		</tr>
		<tr>
			<th>malo</th>
			<th>aceptable</th>
			<th>regular</th>
			<th>bueno</th>
			<th>excelente</th>
			<!--<th>total</th>//-->
			
			<th>malo</th>
			<th>aceptable</th>
			<th>regular</th>
			<th>bueno</th>
			<th>excelente</th>
			<!--<th>total</th>//-->			
		</tr>
		
		<?php foreach($m_meses as $indice=>$mesX){ 
			$malos=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_servicio=1");
			$aceptables=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_servicio=2");
			$regulares=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_servicio=3");
			$buenos=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_servicio=4");
			$excelentes=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_servicio=5");
			$total=$malos+$aceptables+$regulares+$buenos+$excelentes;
		?>
		<tr align="center">
			<td align="left"><?=$mesX?></td>
			<td style="border-left:#999 1px dashed;">&nbsp;<?=$malos?></td>
			<td>&nbsp;<?=$aceptables?></td>
			<td>&nbsp;<?=$regulares?></td>
			<td>&nbsp;<?=$buenos?></td>
			<td>&nbsp;<?=$excelentes?></td>
			<!--<td>&nbsp;<?=$total?></td>//-->
			
			<?php
			$malos=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_tiempo=1");
			$aceptables=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_tiempo=2");
			$regulares=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_tiempo=3");
			$buenos=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_tiempo=4");
			$excelentes=$this->dame_no_resultados("select count(id) from reg_evaluaccion where fecha between '$year-$indice-01' and '$year-$indice-31' and parametro_tiempo=5");
			$total=$malos+$aceptables+$regulares+$buenos+$excelentes;			
			?>
			<td style="border-left:#999 1px dashed;">&nbsp;<?=$malos?></td>
			<td>&nbsp;<?=$aceptables?></td>
			<td>&nbsp;<?=$regulares?></td>
			<td>&nbsp;<?=$buenos?></td>
			<td>&nbsp;<?=$excelentes?></td>
			<!--<td>&nbsp;<?=$total?></td>//-->			
		</tr>
		<?php } ?>		
		</table>
		<p align="center"><a href="#" onclick="consulta(5)" class="link_02">ver detalles</a></p>
		
		
		
		
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
	protected function ejecuta_sql($sql){
		include("../conf/conexion.php");
		if (mysql_db_query($db_actual,$sql,$link)){ return true;
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	return false; }
	}	
}
?>		
