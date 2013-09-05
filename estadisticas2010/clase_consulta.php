<?php
class consulta{
	var $year;
	var $meses;
	function __construct(){
		$this->year='2010';
		$this->meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	}
	
	function consulta_no($n,$id_area,$area){
		//echo "<br>consulta_no($n)";
		if($n==1) $this->consulta1($id_area,$area);
		if($n==2) $this->consulta2($id_area,$area);
	}
	
	
	function consulta1($id_areaX,$areaX){
		//echo "<hr>";
		$id_area=$id_areaX;
		$area=$areaX;
		
		$year=$this->year;
		$pendientes_mes_anterior=0;
		$xml_mi_grafica='';
			// Totales ...
			$TS=0;
			$TTS=0;
			$TA=0;
			$TC=0;
			$TFT=0;
			$TEF=0;
			$xml_mi_grafica.="<chart palette='2' caption='Eficiencia del area de $area' shownames='1' showvalues='0' numberPrefix='' sYAxisValuesDecimals='2' connectNullData='0' PYAxisName='No. Tickets' SYAxisName='% Eficiencia'   formatNumberScale='0' sNumberSuffix='%25' >";
				//color='AFD8F8'numDivLines='10'
			$xml_categorias="<categories>";
			$xml_solicitadosT="<dataset seriesName='T. Solicitados'  showValues='0' >";
				//<dataset seriesName='Product A' color='AFD8F8' showValues='0'>color='AFD8F8'numDivLines='10'
			$xml_atendidos="<dataset seriesName='T. Atendidos'  showValues='0' >";
			//$xml_eficiencia="<dataset seriesName='Eficiencia' color='8BBA00' showValues='0' parentYAxis='S' >";color='F6BD0F'
			$xml_eficiencia="<dataset seriesName='Eficiencia' color='8BBA00' showValues='0' parentYAxis='S' >";
		?>
		
		<div id="div_graficaX" align="center">&nbsp;</div> 
		<br><table align="center" width="900" cellpadding="2" cellspacing="0">
		<tr>
			<th>mes</th>
			<th>solicitados</th>
			<th>pend. MA</th>
			<th>total_solicitados</th>
			<th>atendidos</th>
			<th>cancelados</th>
			<th>pendientes</th>
			<th>fuera de tiempo </th>
			<th>% eficiencia</th>
		</tr>
		<?php
		foreach($this->meses as $mes_indice=>$mes_descripcion){
			//echo "<br>$mes_indice=>$mes_descripcion";
			$sql_solicitados=0;
			$sql_pendientes=0;
			$eficiencia=0;
			
			$sql_solicitados=$this->dame_no_resultados("SELECT count(id) FROM reg_tickets WHERE area='$id_area' AND fecha BETWEEN '$year-$mes_indice-01' AND '$year-$mes_indice-31'; ");
			$total_solicitados=$pendientes_mes_anterior+$sql_solicitados;
			$sql_atendidos=$this->dame_no_resultados("SELECT count(id) FROM reg_tickets WHERE area='$id_area' AND fecha_fin BETWEEN '$year-$mes_indice-01' AND '$year-$mes_indice-31'; ");
			$sql_cancelados=$this->dame_no_resultados("SELECT count(id) as no_tickets_cancelados FROM reg_tickets WHERE status='CANCELADO' AND fecha_fin BETWEEN '$year-$mes_indice-01' AND '$year-$mes_indice-31' AND  area='$id_area' ;");
			$sql_pendientes=$total_solicitados-($sql_atendidos+$sql_cancelados);
			$sql_fuera_tiempo="SELECT count(reg_tickets.id) as no_tfdt 
				FROM reg_tickets,cat_tipo_ticket
				WHERE cat_tipo_ticket.id=reg_tickets.tipo_ticket 
					AND reg_tickets.area='$id_area' 
					AND reg_tickets.status='FINALIZADO'
					AND horas_consumidas>horas_definidas
					AND fecha BETWEEN '$year-$mes_indice-01' AND '$year-$mes_indice-31'; ";			
			$T_fuera_tiempo=$this->dame_no_resultados($sql_fuera_tiempo);
			
			// MIS AJUSTES CLAVE ...
			if($sql_pendientes<0)$sql_pendientes=0;
			if($sql_atendidos>$total_solicitados)$sql_atendidos=$total_solicitados;
			
			if($sql_atendidos>0) $eficiencia=round(($sql_atendidos-$T_fuera_tiempo)/$sql_atendidos*100,2);				
			//echo "&nbsp;&nbsp; &rarr; Sol=$sql_solicitados + PMA ($pendientes_mes_anterior) = ($total_solicitados) Ate=$sql_atendidos CANC ($sql_cancelados) PEND ($sql_pendientes) ";
			
			// Si el mes es pesterior al actual, pend. MA =0
			/*
			if($mes_indice>date("m")){
				$pendientes_mes_anterior=0;
				$total_solicitados=0;
				$sql_pendientes=0;
			}
			*/
			if($eficiencia<=0)$eficiencia=0;
			?>
			<tr style="text-align:center;" onMouseOver="this.style.background='#efefef'" onMouseOut="this.style.background='#ffffff'">
				<td align="left">&nbsp;<?=$mes_descripcion?></td>
				<td>&nbsp;<?=$sql_solicitados?></td>
				<td>&nbsp;<?=$pendientes_mes_anterior?></td>
				<td>&nbsp;<?=$total_solicitados?></td>
				<td>&nbsp;<?=$sql_atendidos?></td>
				<td>&nbsp;<?=$sql_cancelados?></td>
				<td>&nbsp;<?=$sql_pendientes?></td>
				<td>&nbsp;<?=$T_fuera_tiempo?></td>
				<td align="right"><?=number_format($eficiencia,2,'.',',')?> %</td>
			</tr>
			<?php
			// ACUMULAR TOTALES ANUALES ...
			$TS+=$sql_solicitados;
			$TTS+=$total_solicitados;
			$TA+=$sql_atendidos;
			$TC+=$sql_cancelados;
			$TFT+=$T_fuera_tiempo;
			$TEF+=$eficiencia;
			
			// DATOS XML ...
			$xml_mi_grafica.='';
				$xml_categorias.="<category label='$mes_descripcion' />";
				$xml_solicitadosT.="<set value='$total_solicitados' />";
				$xml_atendidos.="<set value='$sql_atendidos' />";
				$xml_eficiencia.="<set value='$eficiencia' />";
			$pendientes_mes_anterior=$sql_pendientes;			
		}
			$xml_categorias.="</categories>";
			$xml_solicitadosT.="</dataset>";
			$xml_atendidos.="</dataset>";
			$xml_eficiencia.="</dataset>";
				$xml_mi_grafica.=$xml_categorias;
				$xml_mi_grafica.=$xml_solicitadosT;
				$xml_mi_grafica.=$xml_atendidos;
				$xml_mi_grafica.=$xml_eficiencia;
					$xml_mi_grafica.="<trendlines><line startValue='95' endValue='95' color='ff0000' displayValue='Objetivo' dashed='1' thickness='1' dashGap='6' alpha='100' showOnTop='1' parentYAxis='S' /></trendlines>";
			$xml_mi_grafica.="</chart>";
		?>
		<tr>
			<th>Total</th>
			<th><?=$TS?></th>
			<th>&nbsp;</th>
			<th><?=$TTS?></th>
			<th><?=$TA?></th>
			<th><?=$TC?></th>
			<th><?=$sql_pendientes?></th>
			<th><?=$TFT?></th>
			<th align="right"><?=round(($TA-$TFT)/$TA*100,2)?> %</th>
		</tr>
		</table>
		<br>
		
		<script type="text/javascript">
			  var myChart = new FusionCharts("swf/MSColumn3DLineDY.swf", "div_graficaX", "900", "500", "0", "0");
			  myChart.setDataXML("<?=$xml_mi_grafica?>");
			  myChart.render("div_graficaX");
		</script>		
		<?php	
	}
	
	function consulta2($id_area,$area){
		//echo "<hr>consulta2($id_area,$area)";
		include("../conf/conexion.php");	
		
		$total_tickets_atendidos=0;
		// EMPIEZA EL GRAFICO ...
		$datos_grafica_swf="";
			$datos_grafica_swf.="<chart palette='2' showBorder='0' formatNumberScale='0'>";		
		
		
		//echo "<br>NDRT=".
		$no_total_tickets=$this->dame_no_resultados("SELECT COUNT(id) FROM reg_tickets WHERE area='$id_area' AND fecha BETWEEN '".$this->year."-01-01' AND '".$this->year."-12-31' AND tipo_ticket<>0 ");
		//echo "<br>".
		$sql="SELECT  
			cat_tipo_ticket.*,
			reg_tickets.fecha
		FROM cat_tipo_ticket, reg_tickets  
		
		WHERE 
			reg_tickets.tipo_ticket=cat_tipo_ticket.id
			
			
			AND cat_tipo_ticket.id_area='$id_area' 
			AND reg_tickets.fecha BETWEEN '".$this->year."-01-01' AND '".$this->year."-12-31' 
		GROUP BY cat_tipo_ticket.id	
		ORDER BY cat_tipo_ticket.id_area ASC,cat_tipo_ticket.id ASC
			
		; ";
		if ($res=mysql_db_query($db_actual,$sql,$link)){ 
			$ndr=mysql_num_rows($res);
			if($ndr>0){
				?>
				<h3 align="center">Tipos de ticket del area de &laquo; <?=$area?> &raquo;</h3>
				<div id="div_graficaX" align="center" style="display:block;">&nbsp;</div> 
				<div id="div_graficaX2" align="center">&nbsp;</div> 
				<br>
				<table align="center" cellspacing="0" cellpadding="3" width="800">
				<tr>
					<th>#</th>
					<th>area</th>
					<th>descripcion</th>
					<th>horas </th>
					<th>no. tickets atendidos </th>
					<th>horas promedio  </th>
					<!--//--><th>%</th>
				</tr>				
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr onMouseOver="this.style.background='#efefef'" onMouseOut="this.style.background='#ffffff'">
						<td align="center"><?=$reg[0]?></td>
						<td><?php
							//echo $reg[1];
							require_once("../clases/area.php");
							$ax=new area();
							echo " ".$ax->dame_nombre_area($reg[1]);							
						?></td>
						<td>&nbsp;<?=strtolower($reg[2])?></td>
						<td align="center"><?=$reg[3]?></td>
						<td align="center"><?php
							$sql2="SELECT COUNT(id) FROM reg_tickets WHERE area='$id_area' AND fecha BETWEEN '".$this->year."-01-01' AND '".$this->year."-12-31' AND tipo_ticket=".$reg[0];
							$no_tickets=$this->dame_no_resultados($sql2);
							echo $no_tickets;
							$total_tickets_atendidos+=$no_tickets; 
							
							
							$datos_grafica_swf.="<set label='".strtolower($reg[2])."' value='".$no_tickets."'/>";
								// isSliced='1' 
							// Horas promedio
							$sql_hp="SELECT AVG(horas_consumidas) FROM reg_tickets WHERE area='$id_area' AND fecha BETWEEN '".$this->year."-01-01' AND '".$this->year."-12-31'  AND tipo_ticket=".$reg[0];
							?></td>
						<td align="right">&nbsp;<?=round($this->dame_no_resultados($sql_hp),2)?></td>
						<!--//--><td align="right">&nbsp;<?=number_format(($no_tickets/$no_total_tickets*100),2,'.',',')?> %</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<th colspan="4" align="right">&nbsp;Total &rarr;</th>
					<th align="center"><?=$total_tickets_atendidos?></th>
					<th align="right">&nbsp;</th>
					<!--<th align="right"><?php /*$no_total_tickets*/ ?>&nbsp;</th>//-->
					<th align="right">100 %&nbsp;</th>
				</tr>
				</table>
				<br />
				<?php
			}else{ echo "<br><div align='center'>Sin resultados.</div>"; }
		} else{ echo "<br>Error SQL (".mysql_error($link).").";	}
		$datos_grafica_swf.="</chart>";	
		//echo "<hr>".htmlentities($datos_grafica_swf);
		?>
		<script type="text/javascript">
			  
			  //var myChart = new FusionCharts("swf/Doughnut3D.swf", "myChartId", "800", "270", "0", "0");
			  var myChart = new FusionCharts("swf/Pie3D.swf", "myChartId", "800", "400", "0", "0");
			  myChart.setDataXML("<?=$datos_grafica_swf?>");		   
			  myChart.render("div_graficaX");
			  /*
			  var myChart = new FusionCharts("swf/Doughnut3D.swf", "myChartId", "800", "600", "0", "0");
			  //var myChart = new FusionCharts("swf/Pie3D.swf", "myChartId", "800", "270", "0", "0");
			  myChart.setDataXML("<?php $datos_grafica_swf?>");		   
			  myChart.render("div_graficaX2");
			  */			  
		</script>		
		<?php
	}
	
	
	
	// =======================================
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
}
?>