<?php
//session_start();

 /****************************************************
        MÓDULO GENERAL DE FUNCIONES EN PHP
        ----------------------------------
  Este módulo contiene funciones útiles que se usan en
  la mayoría de las páginas de nuestra aplización.
  A continuación describiremos cada función de forma
  detallada:
 ****************************************************/

require "datos_conexion.inc.php";
include "config.inc.php";

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

/****************************************************
 La siguiente función formatea una fecha de entrada
 de la forma siguiente:'dia'-'mes'-'año' | 'hora'
 ****************************************************/
function formatear_fecha ($fecha_ent) {
	$fecha_sal = "";
	
	//Separamos la fecha y la hora de la entrada
	$separar_fecha_hora = @explode(' ', $fecha_ent);
	$fecha = $separar_fecha_hora[0];
	$hora = $separar_fecha_hora[1];
	
	//Recogemos los datos de la fecha de entrada
	$fecha = @explode('-', $fecha);
	
	//Formateamos la fecha y la devolvemos
	$fecha_sal = (int)$fecha[2]."-".(int)$fecha[1]."-".$fecha[0]." | $hora";
	return $fecha_sal;
}

/****************************************************
 La siguiente función devuelve el mes dado el número
 ****************************************************/
function mes_cadena($mes_numero){
	switch($mes_numero){
		case(1):$meshoy="Enero";break;
		case(2):$meshoy="Febrero";break;
		case(3):$meshoy="Marzo";break;
		case(4):$meshoy="Abril";break;
		case(5):$meshoy="Mayo";break;
		case(6):$meshoy="Junio";break;
		case(7):$meshoy="Julio";break;
		case(8):$meshoy="Agosto";break;
		case(9):$meshoy="Septiembre";break;
		case(10):$meshoy="Octubre";break;
		case(11):$meshoy="Noviembre";break;
		case(12):$meshoy="Diciembre";break;
		default:$meshoy="";break;
	}	
	return $meshoy;
}

/****************************************************
 La siguiente función escribe la tabla formateada de
 una consulta de un conjunto de mensajes del foro.
 ****************************************************/
function listar_mensajes($sql, $id_tema, $titulo, $pag, $filtro, $orden, $es_acceso_administrador) {
	global $formato_foro;
	
	$res = consulta ($sql);
	echo "	<tr><td>\n";

	if ($formato_foro == "tabla" || $es_acceso_administrador) {
		listar_mensajes_tabla ($res, $id_tema, $titulo, $pag, $filtro, $orden, $es_acceso_administrador);
	} else if ($formato_foro== "lista") {
		listar_mensajes_lista ($res, $id_tema, $titulo, $pag, $filtro, $orden);
	}
	@mysql_free_result($res);
	echo "	</td></tr>\n";
}

function listar_mensajes_tabla ($res, $id_tema, $titulo, $pag, $filtro, $orden, $es_acceso_administrador)
{
	global $autor_mensaje;
	if (@mysql_num_rows($res)>0) {
	?>
    	<table width="100%" border="0" cellspacing="2" cellpadding="1" class="tabla_de_temas">
          <tr class="encabezado_de_temas">
		  <?php if ($es_acceso_administrador) echo "            <td align=\"center\">ID.</td>\n"; ?>
            <td>ASUNTO</td>
		  <?php if ($autor_mensaje) { ?>
            <td>AUTOR</td>
		  <?php } ?>
            <td align="center">FECHA</td>
            <td align="center">LECTURAS</td>
            <td align="center">RESPUESTAS</td>
            <td align="center">&Uacute;LTIMA RESP.</td>
			<?php if ($es_acceso_administrador) echo "            <td align=\"center\">&nbsp;</td>\n"; ?>
          </tr>
	<?php
		while ($reg = @mysql_fetch_array($res)) {
			$id_mensaje = $reg ['id_mensaje'];
			$asunto = htmlentities($reg ['asunto']);
			if ($autor_mensaje) $autor = htmlentities($reg ['autor']);
			$fecha_publicacion = $reg ['fecha_publicacion'];
			$lecturas = $reg ['lecturas'];
			$num_respuestas = $reg ['num_respuestas'];
			$fecha_ult_resp = $reg ['fecha_ultima_respuesta'];
			echo "			<tr class=\"fila_de_tabla\">\n";
			if ($es_acceso_administrador) echo "            <td align=\"center\">$id_mensaje</td>\n";
			if ($es_acceso_administrador)
				echo "            <td><img src=\"../img/folder.gif\" alt=\"Leer mensaje\" width=\"15\" heigth=\"15\"> $asunto</td>\n";
			else
				echo "            <td><a href=\"mensaje.php?id=$id_tema&id_mensaje=$id_mensaje&titulo=$titulo&pag=$pag&filtro=$filtro&orden=$orden\"><img src=\"img/folder.gif\" alt=\"Leer mensaje\" width=\"15\" heigth=\"15\"> $asunto</a></td>\n";
			if ($autor_mensaje) {
				if ($autor)	echo "            <td>$autor</td>\n";
				else echo "            <td>An&oacute;nimo</td>\n";
			}
			echo "            <td align=\"center\">".formatear_fecha($fecha_publicacion)."</td>\n";
			echo "            <td align=\"center\">$lecturas</td>\n";
			echo "            <td align=\"center\">$num_respuestas</td>\n";
			if ($num_respuestas!='0') echo "            <td align=\"center\">".formatear_fecha($fecha_ult_resp)."</td>\n";
			else echo "            <td align=\"center\">No hay respuestas</td>\n";
			if ($es_acceso_administrador) echo "            <td align=\"center\"><a href=\"index.php?borrar=mensaje&id=$id_mensaje&id_tema=$id_tema\">Borrar mensaje</a></td>\n";
			echo "          </tr>\n";
		}
	} else {
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"tabla_de_temas\"><tr class=\"fila_de_tabla\"><td>No hay mensajes</td></tr></table>\n";
	}
	@mysql_free_result($res);
	echo "  	</table>\n";
}

function listar_mensajes_lista ($res, $id_tema, $titulo, $pag, $filtro, $orden)
{
	global $autor_mensaje;
	//$indice = (($pag -1) * $mensajes_x_pag) + 1;
	if (@mysql_num_rows($res)>0) {
	?>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabla_de_temas_lista">
          <tr class="encabezado_de_temas"> 
            <td>ASUNTO</td>
            <td align="right">FECHA</td>
          </tr>
		 </table>
	<?php
		while ($reg = @mysql_fetch_array($res)) {
			$id_mensaje = $reg ['id_mensaje'];
			$asunto = htmlentities($reg ['asunto']);
			if ($autor_mensaje) {
				$autor = htmlentities($reg ['autor']);
				if (!$autor) $autor = "An&oacute;nimo";
			}
			$fecha_publicacion = $reg ['fecha_publicacion'];
			$lecturas = $reg ['lecturas'];
			
			//escribo mensaje y...listo los hijos del nivel 1
			echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla_de_temas_lista\">\n";
			echo "<tr class=\"fila_de_tabla\"><td><a href=\"mensaje.php?id=$id_tema&id_mensaje=$id_mensaje&titulo=$titulo&pag=$pag&filtro=$filtro&orden=$orden\"><img src=\"img/folder.gif\" alt=\"Leer mensaje\" width=\"15\" heigth=\"15\"> $asunto</a></td>\n";
			echo "<td align=\"right\">".formatear_fecha($fecha_publicacion)."</td></tr>\n"; //, por $autor ($lecturas lecturas)
			echo "</table>\n";
			
			$matriz = array(); //Matriz de booleanos que indica donde sigue el camino de directorios
			listar_mensajes_lista_hijos($id_mensaje, $id_tema, $titulo, $pag, 1, $matriz, $filtro, $orden);
		}
	} else {
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"tabla_de_temas\"><tr class=\"fila_de_tabla\"><td>No hay mensajes</td></tr></table>\n";
	}
}

function listar_mensajes_lista_hijos ($id_padre, $id_tema, $titulo, $pag, $nivel, $matriz, $filtro, $orden)
{
	global $autor_mensaje;
	$res = consulta ("select * from mensajes where id_tema='$id_tema' and id_padre='$id_padre' order by fecha_publicacion");
	
	if (@mysql_num_rows($res)>0) {
		$indice = 1;
		while ($reg = @mysql_fetch_array($res)) {
			$id_mensaje = $reg ['id_mensaje'];
			$asunto = htmlentities($reg ['asunto']);
			if ($autor_mensaje) {
				$autor = htmlentities($reg ['autor']);
				if (!$autor) $autor = "An&oacute;nimo";
			}
			$fecha_publicacion = $reg ['fecha_publicacion'];
			$lecturas = $reg ['lecturas'];
			
			//escribo mensaje y...listo los hijos del nivel siguiente
			echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla_de_temas_lista\">\n";
			echo "<tr class=\"fila_de_tabla\">\n";
			
			//Comprobamos si este nivel tiene más elementos
			$hay_mas_elementos = @mysql_data_seek($res, $indice++);
			
			//Insertamos los niveles anteriores
			for ($i=0;$i<$nivel-1;$i++) {
				if ($matriz[$i])
					echo "<td width='30' align='center'><img src=\"img/barraI.gif\"><img src=\"img/blank.gif\"></td>\n";
				else
					echo "<td width='30' align='center'>&nbsp;</td>\n";
			}
			
			//Escribimos el último nivel o nivel del mensaje actual
			if ($hay_mas_elementos) {
				echo "<td width='30' align='center'><img src=\"img/barraT.gif\"><img src=\"img/barra-.gif\"></td>\n";
				$matriz [count($matriz)] = true;
			}
			else {
				echo "<td width='30' align='center'><img src=\"img/barraL.gif\"><img src=\"img/barra-.gif\"></td>\n";
				$matriz [count($matriz)] = false;
			}
			
			echo "<td><a href=\"mensaje.php?id=$id_tema&id_mensaje=$id_mensaje&titulo=$titulo&pag=$pag&filtro=$filtro&orden=$orden\"><img src=\"img/carpeta.gif\" alt=\"Leer mensaje\" width=\"15\" heigth=\"15\"> $asunto</a></td>\n";
			echo "<td align=\"right\">".formatear_fecha($fecha_publicacion)."</td></tr>\n"; //, por $autor ($lecturas lecturas)
			echo "</table>\n";
			listar_mensajes_lista_hijos($id_mensaje, $id_tema, $titulo, $pag, $nivel+1, $matriz, $filtro, $orden);
		}
	}
}

/****************************************************
 La siguiente función escribe la tabla formateada de
 una consulta del conjunto de temas de los foros.
 ****************************************************/
function listar_foros($es_acceso_administrador) {
	global $autor_mensaje;
	$res = consulta ("select * from temas");
			
	echo "<tr><td>\n";
	if (@mysql_num_rows($res)>0) {
	?>
	<table width="100%" border="0" cellspacing="2" cellpadding="1" class="tabla_de_temas">
          <tr class="encabezado_de_temas">
		    <?php if ($es_acceso_administrador) echo "            <td align=\"center\">ID.</td>\n"; ?>
            <td>LISTA TEM&Aacute;TICA DE FOROS</td>
			<td>DESCRIPCI&Oacute;N</td>
            <td align="center">MENSAJES</td>
            <td align="center">&Uacute;LTIMO MENSAJE</td>
			<?php if ($es_acceso_administrador) echo "            <td align=\"center\">&nbsp;</td>\n"; ?>
          </tr>
	<?php
		while ($reg = @mysql_fetch_array($res)) {
			$id_tema = $reg ['id_tema'];
			$titulo_tema = $reg ['titulo_tema'];
			$descripcion = $reg ['descripcion'];
			$sql = "SELECT ";
			if ($autor_mensaje) $sql .= "autor, ";
			$sql .= "fecha_publicacion FROM mensajes WHERE id_tema = '$id_tema' ORDER BY fecha_publicacion DESC"; //AND id_padre='0'
			$res_num_mensajes = consulta ($sql); 
			$num_mensajes = @mysql_num_rows($res_num_mensajes);
			if ($num_mensajes>0) {
				$reg2 = @mysql_fetch_array ($res_num_mensajes);
				if ($autor_mensaje)  {
					$ultimo_autor = $reg2['autor'];
					if (!$ultimo_autor) $ultimo_autor = "An&oacute;nimo";
				}
				$fecha_ultima_publicacion = $reg2['fecha_publicacion'];
			}
			@mysql_free_result($res_num_mensajes);
			echo "			<tr class=\"fila_de_tabla\">\n";
			if ($es_acceso_administrador) echo "            <td align=\"center\">$id_tema</td>\n";
			if ($es_acceso_administrador)
				echo "            <td><a href=\"index.php?id_tema=$id_tema\">".htmlentities($titulo_tema)."</a></td>\n";
			else
				echo "            <td><a href=\"foro.php?id=$id_tema&titulo=$titulo_tema&pag=1\">".htmlentities($titulo_tema)."</a></td>\n";
			echo "            <td>".htmlentities($descripcion)."</td>\n";
			echo "            <td align=\"center\">$num_mensajes</td>\n";
			if ($num_mensajes>0) {
				echo "            <td align=\"center\">".formatear_fecha($fecha_ultima_publicacion);
				if ($autor_mensaje) echo "<br>escrito por $ultimo_autor";
				echo "</td>\n";
			}
			else
				echo "            <td align=\"center\">No hay mensajes</td>\n";
			if ($es_acceso_administrador) echo "            <td align=\"center\"><a href=\"index.php?borrar=foro&id=$id_tema\">borrar foro</a></td></tr>\n";
			else echo "            </tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"tabla_de_temas\"><tr class=\"fila_de_tabla\"><td>No hay temas de discusión</td></tr></table>\n";
	}
	@mysql_free_result($res);
	echo "</td></tr>\n";
}

/****************************************************
 La siguiente función escribe la fila de la paginación
 ****************************************************/
function crear_paginacion($limite_inf, $limite_sup, $num_paginas, $num_mensajes_total, $id_tema, $titulo, $pag, $filtro, $orden){
	echo "	<tr><td align=\"center\" class=\"fila_de_tabla\">\n";
	if ($num_paginas>1) {
		if ($pag==$num_paginas) $limite_sup = $num_mensajes_total;
		echo "Mensajes del ".($limite_inf+1)." al $limite_sup ($num_mensajes_total en total). Este foro tiene $num_paginas p&aacute;ginas&nbsp;&nbsp;&nbsp;\n";
		if ($pag!="1") {
			$izq = $pag - 1;
			//echo "<font class=\"valor\" onClick=\"document.location='foro.php?id=$id_tema&titulo=$titulo&pag=$izq'\" onMouseOver=\"this.style.cursor = 'hand';\">&lt;&lt;</font>&nbsp;\n";
			echo "<a href=\"foro.php?id=$id_tema&titulo=$titulo&pag=$izq&filtro=$filtro&orden=$orden\">&lt;&lt;</a>&nbsp;\n";
		}
		for ($i=1;$i<=$num_paginas;$i++) {
			if ($i==$pag)
				echo " <font class=\"campo\">[$i]</font> &nbsp;";
			else
				//echo " <font class=\"valor\" onClick=\"document.location='foro.php?id=$id_tema&titulo=$titulo&pag=$i'\" onMouseOver=\"this.style.cursor = 'hand';\">[$i]</font> &nbsp;";
				echo " <a href=\"foro.php?id=$id_tema&titulo=$titulo&pag=$i&filtro=$filtro&orden=$orden\">[$i]</a> &nbsp;";
		}
		if ($pag!=$num_paginas) {
			$der = $pag + 1;
			//echo "&nbsp;<font class=\"valor\" onClick=\"document.location='foro.php?id=$id_tema&titulo=$titulo&pag=$der'\" onMouseOver=\"this.style.cursor = 'hand';\">&gt;&gt;</font>\n";
			echo "<a href=\"foro.php?id=$id_tema&titulo=$titulo&pag=$der&filtro=$filtro&orden=$orden\">&gt;&gt;</a>&nbsp;\n";
		}
	}
	echo "	</td></tr>\n";
}

function filtroYordenacion($filtro, $orden) {
	//FILTRADO
	if ($filtro == "hoy") {
		$fecha_hoy = date("Y-m-d 00:00:00");
		$filtro = "AND fecha_publicacion>='$fecha_hoy'";
	}
	else if ($filtro == "semana") {
		$fecha_semana = date("Y-m-d H:i:s",time()-604800); 
		$filtro = "AND fecha_publicacion>='$fecha_semana'";
	}
	else if ($filtro == "mes") {
		if (date("m")>"10")
			$fecha_mes = date("Y-").(date("m")-1).date("-d H:i:s");
		else if (date("m")>"01")
			$fecha_mes = date("Y-0").(date("m")-1).date("-d H:i:s");
		else
			$fecha_mes = (date("Y")-1).date("-12-d H:i:s");
		$filtro = "AND fecha_publicacion>='$fecha_mes'";
	}
	else if ($filtro == "anyo")
		$filtro = "AND fecha_publicacion>='".(date("Y")-1).date("-m-d H:i:s")."'";
	else
		$filtro = "";

	//ORDENACIÓN
	if ($orden == "asunto")
		$orden = "order by asunto";
	else if ($orden == "autor")
		$orden = "order by autor";
	else if ($orden == "lecturas")
		$orden= "order by lecturas DESC";
	else if ($orden == "respuestas")
		$orden= "order by num_respuestas DESC";
	else if ($orden == "ultima_resp")
		$orden= "order by fecha_ultima_respuesta DESC";
	else
		$orden = "order by fecha_publicacion DESC";
		
	$tabla ["filtro"] = $filtro;
	$tabla ["orden"] = $orden;
	
	return $tabla;
}

/****************************************************
 La siguiente función escribe el encabezado de página
 ****************************************************/
function imprime_encabezado_de_pagina($titulo, $javascript, $es_acceso_administrador) {
	echo "<html>\n\n";
	echo "<head>\n";
	echo "<title>$titulo</title>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	if ($es_acceso_administrador)
		echo "<link rel=\"stylesheet\" href=\"../estiloForo.css\" type=\"text/css\">\n";
	else
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\">\n";
	if ($javascript) echo $javascript;
	echo "</head>\n\n";

	echo "<body>\n";
	echo "<center>\n";
	echo "<table width=\"100%\" class=\"tabla_de_foro\" cellspacing=\"2\">\n";
	//echo "<tr><td colspan=\"2\" class=\"encabezado_de_foro\">FORO P&Uacute;BLICO</td></tr>\n";
}

/*********************************************
 La siguiente función escribe el pie de página
 *********************************************/
function imprime_pie_de_pagina() {
	echo "</table>\n";
	echo "</center>\n";
	echo "</body>\n";
	echo "</html>\n";
}
?>