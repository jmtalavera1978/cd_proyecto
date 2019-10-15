<?php
	//INCLUIMOS EL FICHERO CON LA LIBRERÍA DEL FORO
	include "includes/funciones.inc.php";
	//Y LA DE CONFIGURACIÓN DEL MISMO
	include "includes/config.inc.php";
	
	global $autor_mensaje, $formato_foro, $permitir_filtro_foro, $permitir_varios_foros, $permitir_paginado_foro, 
			$num_mensajes_x_pagina_foro, $permitir_buscador_foro, $mostrar_usuarios_conectados_foro;

	if ($mostrar_usuarios_conectados_foro) {	
		//INCLUIMOS E INICIALIZAMOS LA CLASE PARA LOS USUARIOS ONLINE
		include('includes/usuariosConectados.class.inc.php');
		$ol = new UsuariosConectados();
	}
	
	//RECUPERAMOS LAS VARIABLES GET Y POST
	$id_tema = @$HTTP_GET_VARS['id'];
	$padre = @$HTTP_GET_VARS['id_mensaje'];
	$titulo = @$HTTP_GET_VARS['titulo'];
	$pag = @$HTTP_GET_VARS['pag'];
	
	if ($permitir_filtro_foro) {	
		//FILTRAMOS Y ORDENAMOS, en su caso
		$filtro = @$HTTP_POST_VARS['filtro'];
		if (!$filtro)
			$filtro = @$HTTP_GET_VARS['filtro'];
		$orden = @$HTTP_POST_VARS['orden'];
		if (!$orden)
			$orden = @$HTTP_GET_VARS['orden'];
		$tabla = array();
		$tabla = filtroYordenacion($filtro, $orden);
	}
	
	if ($mostrar_usuarios_conectados_foro) {
		//OBTENEMOS LA IP O MÁQUINA DEL CLIENTE PARA PRESENTARLA
		$cliente_host = gethostbyaddr("$REMOTE_ADDR"); //Obtiene la IP del cliente
	}
	
	//IMPRIMIMOS EL ENCABEZADO DE PÁGINA
	imprime_encabezado_de_pagina("Foro $titulo","", false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 75;
	else
		$tam_enlaces = 250;
	
?>
<!-- Principio de la fila de enlaces -->
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="encabezado_de_foro"><?=$titulo;?></td>
            <td width="<?=$tam_enlaces?>">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
				<?php if ($permitir_iconos_como_enlaces) { ?>
					<?php if ($permitir_varios_foros) { ?>
                  		<td><a href="index.php"><img src="img/volver1.gif" alt="Volver a la lista tem&aacute;tica" width="20" height="20"></a></td>
				  	<?php } ?>
                  		<td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="escribir.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&padre=0"><img src="img/nuevo.gif" alt="Nuevo mensaje" width="20" height="20"></a></td>
				  	<?php if ($permitir_buscador_foro) { ?>
                  		<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>'><img src="img/buscar.gif" alt="Buscar" width="20" height="20"></a></td>
				  <?php } ?>
				<?php } else { ?>
					<?php if ($permitir_varios_foros) { ?>
                  		<td><a href="index.php">Lista tem&aacute;tica</a></td>
				  	<?php } ?>
                  		<td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="escribir.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&padre=0">Nuevo mensaje</a></td>
				  	<?php if ($permitir_buscador_foro) { ?>
                  		<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>'>Buscar</a></td>
				  <?php } ?>
				<?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila de los filtros -->
	<?php if ($permitir_filtro_foro || $mostrar_usuarios_conectados_foro) { ?>
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
		  <?php if ($permitir_filtro_foro) { ?>
          <form name="filtrar" action="foro.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>" enctype="multipart/form-data" method="post">
	        <td class="campo">Mostrar: 
	          <select name="filtro" class="datos_form">
	          	<option value="hoy" <?php if ($filtro=="hoy") echo "selected";?>>S&oacute;lo las de hoy</option>
	          	<option value="semana" <?php if ($filtro=="semana") echo "selected";?>>&Uacute;ltima semana</option>
	          	<option value="mes" <?php if ($filtro=="mes") echo "selected";?>>&Uacute;ltimo mes</option>
	          	<option value="anyo" <?php if ($filtro=="anyo") echo "selected";?>>&Uacute;ltimo a&ntilde;o</option>
	          	<option value="todas" <?php if (!$filtro || $filtro=="todas") echo "selected";?>>Todas</option>
	          </select>
          , ordenados por: 
          <select name="orden" class="datos_form">
            <option value="asunto" <?php if ($orden=="asunto") echo "selected";?>>Asunto</option>
			<?php if ($formato_foro == "tabla" && $autor_mensaje) { ?>
            <option value="autor" <?php if ($orden=="autor") echo "selected";?>>Autor</option>
			<?php } ?>
            <option value="fecha_publicacion" <?php if (!$orden || $orden=="fecha_publicacion") echo "selected";?>>Fecha</option>
			<?php if ($formato_foro == "tabla") { ?>
            <option value="lecturas" <?php if ($orden=="lecturas") echo "selected";?>>Lecturas</option>
            <option value="respuestas" <?php if ($orden=="respuestas") echo "selected";?>>Respuestas</option>
            <option value="ultima_resp" <?php if ($orden=="ultima_resp") echo "selected";?>>&Uacute;ltima respuesta</option>
			<?php } ?>
          </select>
	          <input type="submit" name="Submit" value="Aplicar" class="boton">
        </td></form>
		<?php } if ($mostrar_usuarios_conectados_foro) { ?>
			 <td align="right" class="campo">Usuario: </font><font class="valor"><?=$cliente_host?></font> | Total conectados: </font><font class="valor"><?=$ol->get_num_usuarios()?></font><font class="campo"></td>
		<?php } ?>
          </tr>
        </table></td>
    </tr>
	<?php } ?>
<!-- Fin de la fila de los filtros -->
<?php
	if ($permitir_paginado_foro) {
		//Calculamos los datos necesarios para realizar la paginación
		$limite_inf = ($pag * $num_mensajes_x_pagina_foro) - $num_mensajes_x_pagina_foro;
		$limite_sup = (($pag + 1) * $num_mensajes_x_pagina_foro) - $num_mensajes_x_pagina_foro ;
		
		//Creamos la sentencia sql y consultamos según el filtro si se da el caso
		$sql = "select id_mensaje, asunto, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes where id_tema='$id_tema' and id_padre='0'";
		if ($permitir_filtro_foro) $sql .= " ".$tabla['filtro']." ";
		$sql .= "order by fecha_publicacion DESC";
		$res = consulta ($sql);
		
		$num_mensajes_total = @mysql_num_rows($res);
		$num_paginas = (int)(($num_mensajes_total-1) / $num_mensajes_x_pagina_foro) + 1;
		@mysql_free_result($res);
	}
	
	//Creamos la sentencia sql de consulta con el filtro y el orden en su caso
	$sql = "select id_mensaje, asunto,";
	if ($autor_mensaje) $sql .= " autor,";
	$sql .= " fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes where id_tema='$id_tema' and id_padre='0'";
	if ($permitir_filtro_foro) $sql .= " ".$tabla['filtro']." ".$tabla['orden']." ";
	if ($permitir_paginado_foro) $sql .= "LIMIT $limite_inf,$num_mensajes_x_pagina_foro";
	
	//Creamos la fila con la lista de mensajes del foro
	if (!isset($filtro)) $filtro = "";
	if (!isset($orden)) $orden = "";
	listar_mensajes($sql, $id_tema, $titulo, $pag, $filtro, $orden, false);

	if ($permitir_paginado_foro) {
		//Creamos la fila de la paginación
		crear_paginacion($limite_inf, $limite_sup, $num_paginas, $num_mensajes_total, $id_tema, $titulo, $pag, $filtro, $orden);
	}
	
	//Imprimimos el pie de página
	imprime_pie_de_pagina();
?>