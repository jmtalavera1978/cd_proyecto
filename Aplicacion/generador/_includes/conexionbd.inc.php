<?php

// Nombre del servidor
$CONEXIONBD["SERVIDOR"] = "localhost";

// Usuario de la base de datos.
$CONEXIONBD["USUARIO"] = "sixto";
$CONEXIONBD["USUARIO"] = "root";

// Contraseña del usuario de la base de datos

$CONEXIONBD["CLAVE"] = "sixto";
$CONEXIONBD["CLAVE"] = "madeira";
$CONEXIONBD["CLAVE"] = "cdnov02";

// Nombre de la base de datos
$CONEXIONBD["BD"] = "proyecto_generador";

  ////////////////////////////////
 // Funciones de aceso a datos //
////////////////////////////////
function conectar() {

	global $link, $CONEXIONBD;

	$link = @mysql_connect ($CONEXIONBD["SERVIDOR"],$CONEXIONBD["USUARIO"],$CONEXIONBD["CLAVE"]);

	if (!$link) {

		die ("ERROR al conectar con la base de datos.");

		//exit;

	} else {

		$sel = mysql_select_db($CONEXIONBD["BD"],$link);

		if (!$sel){

			die("No se encuentra la base de datos.");

		}

	}

	return $link;

}

function desconectar() {

	global $link;

	mysql_close ($link);

}

function bd_ok() {
	global $CONEXIONBD;
	$ret = false;
	conectar();
	$res = mysql_list_tables($CONEXIONBD["BD"],$GLOBALS["link"]);
	desconectar();
	if($row=mysql_fetch_array($res)) $ret = true;
	return $ret;
}

function bd($str){

	global $link;

	conectar();

	$res = mysql_query($str,$link);

	desconectar();

	return $res;

}

function reg($res,$array = false) {
	$row = ($array)? mysql_fetch_array($res) : mysql_fetch_assoc($res);
	return $row;
}

function numreg($res) {
	return @mysql_num_rows($res);
}

function bdfree($res) {
	return mysql_free_result($res);
}
?>