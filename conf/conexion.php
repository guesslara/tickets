<?
$link=@mysql_connect("localhost","root","xampp") or die("No se pudo conectar al servidor.");// usuarioSistema  phm2013-01-10
$db_actual="iqe_tic_chile";
$db_inventario="iqe_inv_2010";
if(!$link){
    echo "Error al conectar con el servidor";
}else{
    mysql_select_db($db_actual);
}
?>
