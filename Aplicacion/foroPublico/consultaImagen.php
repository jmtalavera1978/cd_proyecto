<?php
session_start();
include "includes/funciones.inc.php";
$sql = "SELECT imagen_data,imagen_type,imagen_name FROM mensajes WHERE id_mensaje='".@$HTTP_GET_VARS['id_mensaje']."'";
$res=@consulta($sql);
if ($fila=@mysql_fetch_array($res)) {
   $data = @$fila['imagen_data'];
   $type = @$fila['imagen_type'];
   $filename = @$fila['imagen_name'];
   
   //Introducimos las cabeceras
   header("Content-Disposition: atachment; filename=$filename");
   header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
   header("Pragma: no-cache");
   header("Expires: 0");
   if (@$HTTP_GET_VARS['ver']=='no')
   		header("Content-Type: application/force-download"); //lanzamos una cabecera inexistente para forzar download
   else
   		header("Content-type: ".$type);
   header("Content-Transfer-Encoding: binary");
   
   echo $data; //presenta la imagen
}
?>
