<?php
class registro_materiales{
	function buscar_colocar_productos($ids){
		//echo "<br>buscar_colocar_productos($ids)";
		include("../conf/conexion.php");
		
		$m_ids=explode(',',$ids);
		foreach($m_ids as $idp){
			$m_n_vs_id=explode('|',$idp);
			//echo "<br>".
			$sql="SELECT id,id_prod,descripgral,especificacion,unidad FROM catprod WHERE id=".$m_n_vs_id[1]." AND activo=1 LIMIT 1; ";
			if ($res=mysql_db_query($db_inventario,$sql,$link)){ 
				//echo "<br>NDR=".
				$ndr=mysql_num_rows($res);
				if($ndr>0){	
					while($reg=mysql_fetch_array($res)){
						//echo "<br>"; 	print_r($reg);
						?>
						<script language="javascript">
							$("#txt_consumo_materiales_des<?=$m_n_vs_id[0]?>").attr("value","<?=$reg["descripgral"]?>");
							$("#txt_consumo_materiales_esp<?=$m_n_vs_id[0]?>").attr("value","<?=$reg["especificacion"]?>");
							$("#txt_consumo_materiales_uni<?=$m_n_vs_id[0]?>").attr("value","<?=$reg["unidad"]?>");
						</script>
						<?php
					}
				}else{ echo "<br>Sin resultados."; }
			} else{ echo "<br>Error SQL (".mysql_error($link).").";	}			
			
		}
	}
}
?>
