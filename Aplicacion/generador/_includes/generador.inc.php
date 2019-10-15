<?php
/**
 * Librería de funciones del gerador:
 * Funciones para la generación de formularios
 * del asistente y del objeto final.
 */

// Cargar la librería de conexión a la base de datos
include_once ("conexionbd.inc.php");

  /////////////////////////////////////
 // Implementación de las funciones //
/////////////////////////////////////

/**
 * cabHTML()
 * Genera una cabecera html completa.
 * @param $titulo
 * @param $hestilo
 * @param $logo
 * @param $sonido
 * @return 
 */
function cabHTML($titulo,$hestilo="",$logo="",$sonido="") {
	global $archivos;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="content-language" content="en">
<META NAME="author" content="Sixto Suaña Moreno">
<META HTTP-EQUIV="Reply-to" CONTENT="@.com">
<META NAME="generator" content="PHPEd 0.6">
<META NAME="description" CONTENT="">
<meta name="keywords" content="">
<META NAME="Creation_Date" CONTENT="<?
	$fecha = date("d\/m\/Y",time());
	echo $fecha;
?>">
<meta name="revisit-after" content="15 days">
        <title><?=$titulo;?></title>
<?
if ($hestilo != '') {
?>
<link rel="stylesheet" type="text/css" href="<? echo $archivos.$hestilo;?>">
<?
}
if ($logo != '') {
?>
<link href="<? echo $archivos.$logo;?>" rel="SHORTCUT ICON">
<?
}
if ($sonido != '') {
?>
<bgsound src="<? echo $archivos.$sonido;?>" loop="1">
<?
}
}

/**
 * ver()
 * Para depuración del código.
 * Muestra el contenido de una variable.
 * Puede detener la ejecución del programa.
 * @param $x
 * @param $y
 * @return 
 */
function ver($x,$y=0){
	$t = gettype($x);
	switch ($t) {
		case "array":
			echo "Array:<br>";
			reset ($x);
			while (list ($k, $v) = each ($x)) {
				echo $k." => ";
				ver($v);
			}
			break;
		default:
			echo "{".$x."}<br>";
	}
	if ($y != 0) exit;
}

/**
 * postDiccionario()
 * Realiza ls relativas a los diccionarios.
 * @param $dat $HTTP_POST_VARS
 * @return 
 */
function postDiccionario($dat) {
	if($dat){
		$cualo = $dat["accion"];
		switch($cualo) {
			case "crear":
				$idioma = $dat["idioma"];
				$nombre = $dat["nuevoidioma"];
				$res = bd("select * from idiomas where nombre='$nombre'");
				if (mysql_num_rows($res) == 0) {
					$res = bd("select * from idiomas order by id DESC");
					$row = mysql_fetch_assoc($res);
					mysql_free_result($res);
					$id = $row["id"];
					$id++;
					bd("INSERT INTO idiomas (id, nombre) VALUES ('$id','$nombre')");
				}
				header ("Location: diccionario.php?i=$id");
				exit;
				break;
			case "eliminar":
				$idioma = $dat["idioma"];
				bd("DELETE FROM idiomas WHERE id='$idioma'");
				bd("DELETE FROM palabras WHERE idioma='$idioma'");
				header ("Location: diccionario.php");
				exit;
				break;
			case "insertar":
				$idioma = $dat["idioma"];
				$id = $dat["termino"];
				$trad = $dat["traduccion"];
				bd("INSERT INTO palabras (id, idioma, traduccion) VALUES ('$id','$idioma' , '$trad')");
				break;
			case "modificar": 
				if(isset($dat["palabra"])) {
					$idioma = $dat["idioma"];
					$id = $dat["palabra"];
					$trad = $dat["traduccion"];
					bd("DELETE FROM palabras WHERE id='$id' AND idioma='$idioma'");
					bd("INSERT INTO palabras (id, idioma, traduccion) VALUES ('$id','$idioma' , '$trad')");
				}
				break;
			case "borrar":
				if(isset($dat["palabra"])) {
					$idioma = $dat["idioma"];
					$id = $dat["palabra"];
					bd("DELETE FROM palabras WHERE id='$id' AND idioma='$idioma'");
				}
				break;
		}
	}
}


function postObjeto($dat) {
	if($dat){
		$cualo = $dat["accion"];/*
		ver($dat,1);//*/
		switch($cualo) {
			case "crearobjeto":
				$id = "";
				$obj = $dat["nuevoobjeto"];
				$res = bd("select * from objetos where nombre='$obj'");
				if (numreg($res) == 0) {
					$res = bd("select * from objetos order by id DESC");
					$row = reg($res);
					bdfree($res);
					$id = $row["id"];
					$id++;
					$clase = $dat["clase"];
					$desc = $dat["descripcionobjeto"];
					bd("INSERT INTO objetos (id, nombre, clase, descripcion) VALUES ('$id','$obj','$clase','$desc')");
				}
				reset($dat);
				while(list($clave,$val) = each($dat)){
					if($val == "__fichero-sel__") bd("INSERT INTO contenidos (objeto, fichero) VALUES ($id, $clave)");
				}
				header ("Location: panelcontrol.php?ob=$id");
				exit;
				break;
			case "eliminarobjeto":
				$objeto = $dat["objeto"];
				bd("DELETE FROM objetos WHERE id='$objeto'");
				bd("DELETE FROM contenidos WHERE objeto='$objeto'");
				header ("Location: panelcontrol.php");
				exit;
				break;
			case "modificarobjeto":
				//ver($dat,1);
				$objeto = $dat["objeto"];
				$descripcion = $dat["descripcionobjeto"];
				bd("UPDATE objetos SET descripcion = '$descripcion'  WHERE id='$objeto'");
				$mensaje = "";
				if (isset($dat["nuevoobjeto"])) {
					$nombre = $dat["nuevoobjeto"];
					$res = bd("select * from objetos where nombre='$nombre'");
					if (numreg($res) == 0) $res = bd("UPDATE objetos SET nombre= '$nombre'  WHERE id='$objeto'");
					else {
						bdfree($res);
						$mensaje .= "<font color=\"#00ff00\">¡Nombre repetido!</font><br>\n";
					}
				}
				bd("DELETE FROM contenidos WHERE objeto='$objeto'");
				reset($dat);
				reset($dat);
				while(list($clave,$val) = each($dat)){
					if($val == "__fichero-sel__") bd("INSERT INTO contenidos (objeto, fichero) VALUES ($objeto, $clave)");
				}
				if($mensaje != "") echo $mensaje;
				else header("Location: panelcontrol.php?ob=$objeto");
				break;
/*			case "insertar":
				$idioma = $dat["idioma"];
				$id = $dat["termino"];
				$trad = $dat["traduccion"];
				bd("INSERT INTO palabras (id, idioma, traduccion) VALUES ('$id','$idioma' , '$trad')");
				break;
			case "modificar": 
				if(isset($dat["palabra"])) {
					$idioma = $dat["idioma"];
					$id = $dat["palabra"];
					$trad = $dat["traduccion"];
					bd("DELETE FROM palabras WHERE id='$id' AND idioma='$idioma'");
					bd("INSERT INTO palabras (id, idioma, traduccion) VALUES ('$id','$idioma' , '$trad')");
				}
				break;
			case "borrar":
				if(isset($dat["palabra"])) {
					$idioma = $dat["idioma"];
					$id = $dat["palabra"];
					bd("DELETE FROM palabras WHERE id='$id' AND idioma='$idioma'");
				}
				break;
*/		}
	}
}
function postClase($dat) {
	if($dat){
		$cualo = $dat["accion"];/*
		ver($dat,1);//*/
		switch($cualo) {
			case "crear":
				$id = "";
				$clase = $dat["nombre"];
				$res = bd("select * from clases where nombre='$clase'");
				if (numreg($res) == 0) {
					$res = bd("select * from clases order by id DESC");
					$row = reg($res);
					bdfree($res);
					$id = $row["id"];
					$id++;
					bd("INSERT INTO clases (id, nombre) VALUES ('$id','$clase')");
				} else {
					$row=reg($res);
					bdfree($res);
					$id = $row["id"];
				}
				header ("Location: clases.php?cl=$id");
				exit;
				break;
			case "eliminar":
				$clase = $dat["clase"];
				bd("DELETE FROM clases WHERE id='$clase'");
				$res = bd("select id from objetos where clase='$clase'");
				while($row = reg($res)){
					$objeto = $row["id"];
					bd("DELETE FROM objetos WHERE id='$objeto'");
					bd("DELETE FROM descriptores WHERE objeto='$objeto'");
				}
				bdfree($res);
				header ("Location: clases.php");
				exit;
				break;
			case "modificar":
				//ver($dat,1);
				$clase = $dat["clase"];
				$nombre = $dat["nombre"];
				bd("UPDATE clases SET nombre= '$nombre' WHERE id='$clase'");
				header ("Location: clases.php");
				exit;
				break;
		}
	}
}

function postTipos($dat) {
	if($dat){
		$cualo = $dat["accion"];
		switch($cualo) {
			case "creardato":
				$nombre = $dat["nombredato"];
				$res = bd("select * from tiposdatos where nombre='$nombre'");
				if (numreg($res) == 0) {
					$res = bd("select id from tiposdatos order by id DESC");
					$id = ($row=reg($res))? ($row["id"] + 1): 1;
					bdfree($res);
					bd("INSERT INTO tiposdatos (id, nombre) VALUES ('$id','$nombre')");
				} 
				header ("Location: tipos.php");
				exit;
				break;
			case "eliminardato":
				$id = $dat["tipodato"];
				bd("DELETE FROM tiposdatos WHERE id='$id'");/*
				$res = bd("select id from objetos where clase='$clase'");
				while($row = reg($res)){
					$objeto = $row["id"];
					bd("DELETE FROM objetos WHERE id='$objeto'");
					bd("DELETE FROM descriptores WHERE objeto='$objeto'");
				}
				bdfree($res);//*/
				header ("Location: tipos.php");
				exit;
				break;
			case "modificardato":
				$id = $dat["tipodato"];
				$nombre = $dat["nombredato"];
				bd("UPDATE tiposdatos SET nombre= '$nombre' WHERE id='$id'");
				header ("Location: tipos.php");
				exit;
				break;
			case "crearopcion":
				$nombre = $dat["nombreopcion"];
				$res = bd("select * from tiposopciones where nombre='$nombre'");
				if (numreg($res) == 0) {
					$res = bd("select id from tiposopciones order by id DESC");
					$id = ($row=reg($res))? ($row["id"] + 1): 1;
					bdfree($res);
					bd("INSERT INTO tiposopciones (id, nombre) VALUES ('$id','$nombre')");
				} 
				header ("Location: tipos.php");
				exit;
				break;
			case "eliminaropcion":
				$id = $dat["tipoopcion"];
				bd("DELETE FROM tiposopciones WHERE id='$id'");/*
				//*/
				header ("Location: tipos.php");
				break;
			case "modificaropcion":
				//ver($dat,1);
				$id = $dat["tipoopcion"];
				$nombre = $dat["nombreopcion"];
				bd("UPDATE tiposopciones SET nombre= '$nombre' WHERE id='$id'");
				header ("Location: tipos.php");
				exit;
				break;
		}
	}
}


function postDatos($dat) {
	if($dat){
		$cualo = $dat["accion"];
		switch($cualo) {
			case "creardato":
				$res = bd("select * from datos order by id DESC");
				$id = ($row = reg($res))? ($row["id"] + 1): 1;
				bdfree($res);
				$nombre = $dat["nombredato"];
				$tipo = $dat["tipodato"];
				$defecto = $dat["defecto"];
				bd("INSERT INTO datos (id, nombre, tipo, defecto) VALUES ('$id','$nombre','$tipo','$defecto')");
				reset($dat);
				while(list($k,$v) = each($dat)) {
					if($v == "__opcion-sel__") 
					$res = bd("INSERT INTO opcionesdatos (dato , opcion) VALUES ('$id','$k')");
				}
				header ("Location: datos.php");
				exit;
				break;
			case "eliminardato":
				$id = $dat["__dato-sel__"];
				bd("DELETE FROM datos WHERE id='$id'");
				bd("DELETE FROM opcionesdatos WHERE dato='$id'");
				header ("Location: datos.php");
				exit;
				break;
			case "modificardato":
				$id = $dat["__dato-sel__"];
				$nombre = $dat["nombredato"];
				$tipo = $dat["tipodato"];
				$defecto = $dat["defecto"];
				bd("UPDATE datos SET nombre= '$nombre', tipo= $tipo, defecto='$defecto' WHERE id=$id");
				bd("DELETE FROM opcionesdatos WHERE dato='$id'");
				reset($dat);
				while(list($k,$v) = each($dat)) {
					if($v == "__opcion-sel__") 
					$res = bd("INSERT INTO opcionesdatos (dato , opcion) VALUES ('$id','$k')");
				}
				header ("Location: datos.php");
				exit;
				break;
			case "crearopcion":
				$res = bd("select * from opciones order by id desc");
				$id = 1;
				if($row = reg($res)) $id = $row["id"] + 1;
				$nombre = $dat["nombreopcion"];
				$tipo = $dat["tipoopcion"];
				$valor = $dat["valoropcion"];
				$maxtam = (((int) $dat["tamopcion"]) < 0)? 0 : (int) $dat["tamopcion"];
				bdfree($res);
				bd("insert into opciones (id, nombre, tipo, maxtam, valor) values ($id, '$nombre', $tipo, $maxtam, '$valor')");
				header("Location: datos.php");
				break;
			case "modificaropcion":
				$id = $dat["opcion"];
				$nombre = $dat["nombreopcion"];
				$tipo = $dat["tipoopcion"];
				$maxtam = $dat["tamopcion"];
				$valor = $dat["valoropcion"];
				bd("UPDATE opciones SET nombre= '$nombre', tipo= $tipo, maxtam= $maxtam, valor= '$valor' WHERE id=$id");
				break;
			case "eliminaropcion": /////////////////////////////////////
				bd("DELETE FROM opciones WHERE id=".$dat["opcion"]);
				header("Location: datos.php");
				break;
		}
	}
}

function postFicheros($dat,$fic) {
	if($dat) {
		$cualo = $dat["accion"];
		switch ($cualo) {
			case "crear":
				$res = bd("select id from ficheros order by id DESC");
				$id = ($row = reg($res))? $row["id"] + 1 : 1;
				$nombre = $dat["nombrefichero"];
				$camino = $dat["caminofichero"];
				$codigo = "";
				$lenguaje = $dat["lenguaje"];
				if(isset($dat["tipocontenido"])) {
					if($dat["tipocontenido"] == "codigo") {
						$codigo = $dat["codigo"];
					} else {
						$tmp_data = $fic['archivo']['tmp_name'];
						$tam = $fic['archivo']['size'];
						$codigo = @addslashes(fread(fopen($tmp_data,"rb"),$tam));
					}
				}
				bd("INSERT INTO ficheros (id, nombre, codigo, camino, lenguaje) VALUES ($id, '$nombre', '$codigo', '$camino', '$lenguaje')");
				header("Location: ficheros.php");
				break;
			case "modificar":
				$id = $dat["fichero"];
				$nombre = $dat["nombrefichero"];
				$camino = $dat["caminofichero"];
				$codigo = "";
				$lenguaje = $dat["lenguaje"];
				if(isset($dat["tipocontenido"])) {
					if($dat["tipocontenido"] == "codigo") {
						$codigo = $dat["codigo"];
					} else {
						$tmp_data = $fic['archivo']['tmp_name'];
						$tam = $fic['archivo']['size'];
						$codigo = @addslashes(fread(fopen($tmp_data,"rb"),$tam));
					}
				}
				$nombre = ($nombre == "")? "": " nombre='$nombre'";
				$codigo = ($codigo == "")? "": (($nombre == "")? "" : ",")." codigo='$codigo'";
				$camino = ($camino == "")? "": (($nombre == "" and $codigo == "")? "" : ",")." camino='$camino'";
				$lenguaje = (($nombre == "" and $codigo == "" and $camino == "")? "" : ",")." lenguaje='$lenguaje'";
				bd("UPDATE ficheros SET ".$nombre.$codigo.$camino.$lenguaje." WHERE id=$id");
				header("Location: ficheros.php");
				break;
			case "eliminar":
				$id = $dat["fichero"];
				bd("DELETE FROM ficheros WHERE id=$id");
				bd("DELETE FROM asociaciones WHERE id=$id");
				header("Location: ficheros.php");
				break;
		}
	}
}

function postAsoc($dat) {
	if($dat) {
		$cualo = $dat["accion"];
		switch($cualo) {
			case "guardar":
				$fichero = $dat["fichero"];
				bd("DELETE FROM asociaciones WHERE fichero=$fichero");
				reset($dat);
				while(list($k,$v) = each($dat)) {
					if($k != "accion" and $k != "fichero" and $v == "__dato-sel__")
					bd("INSERT INTO asociaciones (fichero, dato) VALUES ($fichero, $k)");
				}
				header("Location: asociaciones.php");
				break;
			case "eliminar":
				$fichero = $dat["fichero"];
				bd("DELETE FROM asociaciones WHERE fichero=$fichero");
				header("Location: asociaciones.php");
				break;
		}
	}
}

/**
 * pal()
 * Devuelve la traducción al idioma 
 * actual de una palabra.
 * @param $pal
 * @return 
 */
function pal($pal,$idi=null){
	if ($idi == null) $idioma = selecciona_lenguaje($idi);
	else $idioma = $idi;
	$res = bd("select traduccion from palabras where id = '$pal' and idioma='$idioma'");
//	if($row = reg($res,true)) return htmlentities($row[0]);
//	else return htmlentities($pal);
	if($row = reg($res,true)) return $row[0];
	else return $pal;
}

/********************************************
 Devuelve el lenguaje activo en este momento.
 ********************************************/
function selecciona_lenguaje($idioma_nuevo = 1) {
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

/*
function pal($pal,$lang){
	$traduccion = $pal;

	$res = bd("SELECT traduccion FROM palabras WHERE id='$pal' AND idioma='$lang'");
	if (@mysql_num_rows($res) > 0) {
		$reg = @mysql_fetch_array($res);
		$traduccion = $reg['traduccion'];
	}
		
	return (htmlentities($traduccion));
}//*/

function generarObjeto($dat) {
	if($dat) {
		$objeto = $dat["objeto"];
		$res = bd("select * from contenidos where objeto=$objeto order by fichero");
		$preproc = datosGeneracion();
		while($row = reg($res)) {
			$resul = bd("select * from asociaciones where fichero=".$row["fichero"]);
			$codigoextra = "";
			if(numreg($resul) > 0) $codigoextra = codigo_insertar($dat,$row["fichero"],$resul,$preproc);
			bdfree($resul);
			crearFichero($row["fichero"],$codigoextra,"descargas");
		}
		comprime_carpeta("descargas/".session_id(),session_id().".zip");
		echo "<div align=\"center\">\n";
		echo pal("recordatorio")."<br><br>";
		echo "<a href=\"descargas/".session_id().".zip\">&lt;&lt; descargar.zip &gt;&gt;";
		echo "</div>";
		exit;
	}
}

function datosGeneracion() {
	$dat = array();
	// Lenguajes
	$res = bd("select * from lenguajes order by id");
	while($reg = reg($res)) $dat["lenguajes"][$reg["id"]] = $reg["nombre"];
	bdfree($res);
	
	// Tipos de datos
	$res = bd("select * from tiposdatos order by id");
	while($reg = reg($res)) $dat["tiposdatos"][$reg["id"]] = $reg["nombre"];
	bdfree($res);

	// Tipos de opciones
	$res = bd("select * from tiposopciones order by id");
	while($reg = reg($res)) $dat["tiposopciones"][$reg["id"]] = $reg["nombre"];
	bdfree($res);

	return $dat;
}

function codigo_insertar($dat,$fich,$res,$prep) {
	$cod = "";
	if($dat) {
		// Preprocesamiento de las opciones
		$opciones = array();
		while($row=reg($res)) {
			$result = bd("select * from opcionesdatos where dato=".$row["dato"]);
			while($ropcion = reg($result)) $opciones[$row["dato"]][] = $ropcion["opcion"];
		}
		bdfree($result);
		
		$resul = bd("select lenguaje from ficheros where id=$fich");
		$row = reg($resul);
		$lenguaje = $prep["lenguajes"][$row["lenguaje"]];
		
		// Recorremos las opciones del fichero
		reset($opciones);
		while(list ($dato,$ops) = each($opciones)) {
			$resdat = bd("select * from datos where id=$dato");
			$regdat = reg($resdat);
			$tipodat = $prep["tiposdatos"][$regdat["tipo"]];
			$var = "";
			switch ($tipodat) {
				case "radio":
					$ops = array();
					reset($opciones[$dato]);
					$nombre = $regdat["nombre"];
					$var = procesaOpcion(array("nombre" => $regdat["nombre"]),
						$dat["$nombre"],$lenguaje);					
					break;
				case "select":
					break;
				case "checkbox":
					$op = $opciones[$dato][0];
					$result = bd("select * from opciones where id=$op");
					$regop = reg($result);
					$val = (isset($dat["$op"]))? "1" : "0";
					$var = procesaOpcion($regop,$val,$lenguaje,false);					
					break;
				default: // text , estilo
					$op = $opciones[$dato][0];
					$result = bd("select * from opciones where id=$op");
					$regop = reg($result);
					$var = procesaOpcion($regop,$dat["$op"],$lenguaje);
			}
			$cod .= $var;
		}
		
		switch($lenguaje) {
			case "php":
		 		$cod = ($cod != "")? "<"."?php\n".$cod."\n?".">" : "";
				break;
			case "java-html":
				$cod = "<html><head><title>::CHAT::</title></head>\n
						<body><div align=\"center\">\n<applet
  codebase = \".\"
  code     = \"appletclientechat.AppletClienteChat.class\"
  name     = \"clienteChat\"
  width    = \"660\"
  height   = \"415\"
  hspace   = \"0\"
  vspace   = \"0\"
  align    = \"top\"
>".$cod."\n</applet>\n</div></body></html>";
				break;
			case "batch":
				$cod = "@echo off\n".$cod;
				break;
		}
	}
	return $cod;
}

function procesaOpcion($reg,$val,$lenguaje,$com = true) {
	$cod = "";
	$k = $reg["nombre"];
	switch($lenguaje) {
		case "php":
			$cod = "\n\$$k = ";
			if($com) $cod .= "\"";
			$cod .= $val;
			if($com) $cod .= "\"";
			$cod .= ";\n";
			break;
		case "java-html":
			$cod = "\n\t<param name=\"$k\" value=\"$val\">\n";
			break;
		case "batch":
			$cod = "set $k=$val\n";
			break;
	}
	return $cod;
}

function crearFichero($fichero,$precod=null,$destino="descargas") {
	$res = bd("select * from ficheros where id=$fichero");
	$reg = reg($res);
	$camino = $reg["camino"];
	
	$destino = $destino.((substr($destino,strlen($camino) - 1,1) == "/")? "" : "/");
	$destino .= session_id()."/";
	
	if(!is_dir($destino)) mkdir($destino);
 	$camino = $destino.$camino;	
	if(!is_dir(dirname($camino))) {
		$ruta = array();
		$fich = basename($camino);
		$dir = dirname($camino);
		do {
			$ruta [] = $dir;
			$dir = dirname($dir);
		} while($dir != ".");
		reset($ruta);
		//ver($ruta,1);
		while($c = array_pop($ruta)) 
		@mkdir($c);
	}
	$fi = fopen($camino,"wb");
	fwrite($fi,$precod.$reg["codigo"],strlen($precod) + strlen($reg["codigo"]));
	fclose($fi);
}

function arbol($dire = null) {
	$arbol = array();
	if($dire != null) {
		if(is_dir($dire)) {
			$di = dir($dire);
			while($fi = $di->read()) {
				$cam = $dire.((substr($dire,strlen($dire) - 1,1) == "/")? "" : "/").$fi;
				if(is_file($cam)) $arbol[] = $cam;
				else if(is_dir($cam) and $fi != "." and $fi !="..") {
					$rama = arbol($cam);
					reset($rama);
					while(list($k,$v) = each($rama)) $arbol[] = $v;
				}
			}
			$di->close();
		}
	}
	return $arbol;	
}

function comprime_carpeta($carpeta, $nombre_fich="objeto.zip") {
	include "inc/pclzip.lib.php";

	// Creamos el fichero comprimido mediante la clase dada
	@unlink("descargas/".$nombre_fich);
	$zipfile = new PclZIP("descargas/".$nombre_fich);
	
	// Cargamos el contenido de la carpeta
	$ficheros = arbol($carpeta);

	// Encontramos un nombre para el fichero
	$nombre = basename($nombre_fich);
	$nombre = substr($nombre,0,strrpos($nombre,"."));
	
	// Destruimos los ficheros
	

	// Creamos el fichero comprimido
	$zipfile->create($ficheros,'',$carpeta);
	
}
?>