<?php
@session_start();
 /****************************************************
        MÓDULO GENERAL DE FUNCIONES EN PHP
        ----------------------------------
 Este módulo contiene funciones útiles que se usan en
 la mayoría de las páginas de nuestra aplización.
 A continuación describiremos cada función de forma
 detallada:
 ****************************************************/

include "datos_conexion.inc.php";

/****************************************************
 La siguiente función realiza una consulta sobre la
 base de datos, dada una sentencia sql que se le pasa
 como parámetro. Utiliza para ello las variables
 globales de conexión a la base de datos.
 ****************************************************/
function consulta ($consulta_sql) {
	global $basedatos, $host, $usuario, $password;

	//accedemos a la base de datos
	$conexion = mysql_connect($host, $usuario, $password);
	mysql_select_db($basedatos,$conexion);
        	
	//realizamos la consulta de la tabla
	$res=mysql_query($consulta_sql,$conexion);

	//devolvemos el resultado de la consulta
	mysql_close($conexion);
	return $res;
}

/********************************************
 Devuelve el lenguaje activo en este momento.
 ********************************************/
function selecciona_lenguaje($idioma_nuevo) {
	if ($idioma_nuevo) {
		$idioma = $idioma_nuevo;
		if (session_is_registered('idioma'))
			session_unregister('idioma');
	} elseif (@isset($_SESSION['idioma']))
		$idioma = $_SESSION['idioma'];
	else
		$idioma = '1';

	return ($idioma);
}

function pal($pal,$lang){
	$traduccion = "nada";

	$res = consulta ("SELECT traduccion FROM palabras WHERE id='$pal' AND idioma='$lang'");
	if (mysql_num_rows($res) > 0) {
		$reg = mysql_fetch_array($res);
		$traduccion = $reg['traduccion'];
	} else
		$traduccion = $pal;
		
	return (htmlentities($traduccion));
}
?>