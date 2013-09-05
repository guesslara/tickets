<?php
class usuario{
	public function __destruct(){
		//echo "<br>Objeto destruido.";
	}
	public function opciones(){
		?>
		<style type="text/css">
		
		</style>
		<div id="div_usuario_opciones">
		
		</div>
		<?php
	}
	
	public function validar_usuario($u,$p){
		session_start();
		require("conf/conexion.php");
		//echo "<br>[$sql_inv]=".
		$sql_usuarios="SELECT * FROM cat_usuarios WHERE usuario='$u' AND contrasena='".md5($p)."' ";
		if (!$usuario_consulta = mysql_db_query($db_actual,$sql_usuarios,$link))
		{
			echo "Error del sistema. (".mysql_error($link).")";
			exit();
		}
		$ndr=mysql_num_rows($usuario_consulta);
		
		if ($ndr > 0) {
			unset($u);		unset ($p);
			while ($row_usuario=mysql_fetch_array($usuario_consulta))
			{
				//print_r($row_usuario);
				$uir=$row_usuario["id_usuario"];
				$unr=$row_usuario["nivel"];
				$uor=$row_usuario["nombre_completo"];
				$gru=$row_usuario["grupo"];
			}
			session_name('iqesisco_origen_mat');
			session_cache_limiter('nocache,private');
			$_SESSION['usuario_id']=$uir;
			$_SESSION['usuario_nivel']=$unr;
			$_SESSION['nombre']=$uor;
			$_SESSION['sistema']="iqe_inventario";
			//echo "OK";
			return true;
		} else {
			//echo "Error: Nombre de Usuario o Contraseña incorrectos.";
			return false;
		}
		mysql_free_result($usuario_consulta);
	}
	public function listar(){
		$clase_zebra="tabla1_non";
		require("../conf/conexion.php");
		$sql="SELECT id_usuario,activo,usuario,grupo,nivel_acceso, nombre_completo FROM cat_usuarios";
		if ($res=mysql_db_query($db_actual,$sql,$link)){
			$ndr=mysql_num_rows($res);
			if ($ndr>0){
				?><table cellspacing="0" class="tabla1">
				<tr>
					<td class="tabla1_titulo" colspan="6">Usuarios del Sistema</td>
				</tr>				
				<tr>
					<td class="tabla1_campos">id</td>
					<td class="tabla1_campos">activo</td>
					<td class="tabla1_campos">usuario</td>
					<td class="tabla1_campos">grupo</td>
					<td class="tabla1_campos">nivel</td>
					<td class="tabla1_campos">nombre</td>
				</tr>
				<?php
				while($reg=mysql_fetch_array($res)){
					//echo "<br>"; 	print_r($reg);
					?>
					<tr class="<?=$clase_zebra?>">
						<td align="center">&nbsp;<?=$reg["id_usuario"]?></td>
						<td align="center">&nbsp;<?=$reg["activo"]?></td>
						<td>&nbsp;<?=$reg["usuario"]?></td>
						<td>&nbsp;<?=$reg["grupo"]?></td>
						<td align="center">&nbsp;<?=$reg["nivel_acceso"]?></td>
						<td>&nbsp;<?=$reg["nombre_completo"]?></td>
					</tr>					
					<?php
					($clase_zebra=="tabla1_non")? $clase_zebra="tabla1_par" : $clase_zebra="tabla1_non";
				}
				?>
				<tr>
					<td class="tabla1_campos_inferiores" colspan="6"><?=$ndr?> resultados</td>
				</tr>				
				<table><?php	
			} else { echo "<br>No se encontraron resultados"; }
			mysql_free_result($res);
			 
		} else {
			echo "<br>".mysql_error($link);
		}
	}
}
?>
