<?php
	@session_start();
	
	//INCLUIMOS EL FICHERO CON LA LIBRER�A DEL FORO
	include "includes/funciones.inc.php";
	//Y LA DE CONFIGURACI�N DEL MISMO
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
		//OBTENEMOS LA IP O M�QUINA DEL CLIENTE PARA PRESENTARLA
		$cliente_host = gethostbyaddr("$REMOTE_ADDR"); //Obtiene la IP del cliente
	}
	
	//IMPRIMIMOS EL ENCABEZADO DE P�GINA
	imprime_encabezado_de_pagina("Foro $titulo","", false);
	
	if (@$HTTP_POST_VARS['login']){
		$login_usuario = @$HTTP_POST_VARS['login'];
		$passwd = md5(@$HTTP_POST_VARS['password']);
			
		$res = consulta("SELECT login FROM usuarios WHERE login='$login_usuario' AND password='$passwd' AND tiene_permiso='SI'");
		if (@mysql_num_rows($res)==1) {
			@session_destroy();
			@session_start();
			@session_register('login_usuario');
			consulta("UPDATE usuarios SET fecha_ultimo_acceso='".date("Y-m-d H:i:s")."' WHERE login='$login_usuario'");
		} else
			echo "<tr><td class=\"fila_de_tabla\">Alguno de los datos introducidos no es correcto.</td></tr>\n";
		@mysql_free_result($res);
	}
	
	if ($permitir_iconos_como_enlaces) {
		if ((!$permitir_varios_foros && $permitir_buscador_foro) || ($permitir_varios_foros && !$permitir_buscador_foro))
			$tam_enlaces = 50;
		else
			$tam_enlaces = 75;
	}
	else {
		if (!$permitir_varios_foros && $permitir_buscador_foro)
			$tam_enlaces = 150;
		else if (($permitir_varios_foros && !$permitir_buscador_foro))
			$tam_enlaces = 175;
		else
			$tam_enlaces = 250;
	}
	
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
                  		<td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="escribir_protegido.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&padre=0"><img src="img/nuevo.gif" alt="Nuevo mensaje" width="20" height="20"></a></td>
				  	<?php if ($permitir_buscador_foro) { ?>
                  		<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>&tipo=protegido'><img src="img/buscar.gif" alt="Buscar" width="20" height="20"></a></td>
				  <?php } ?>
				<?php } else { ?>
					<?php if ($permitir_varios_foros) { ?>
                  		<td><a href="index.php">Lista tem&aacute;tica</a></td>
				  	<?php } ?>
                  		<td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="escribir_protegido.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&padre=0">Nuevo mensaje</a></td>
				  	<?php if ($permitir_buscador_foro) { ?>
                  		<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>&tipo=protegido'>Buscar</a></td>
				  <?php } ?>
				<?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<?php 		if (!@session_is_registered('login_usuario')) { ?>
	<!-- Principio de fila de acceso usuarios -->
	<tr><td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
		<form name="acceso" method="post" action="<?=$PHP_SELF?>?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>" enctype="multipart/form-data">
        <td align="left" valign="middle" class="campo">
            Usuario: <input type="text" name="login" size="10" class="form_login" maxlength="12">
			Password: <input type="password" name="password" size="10" class="form_login">
            <input type="submit" name="Submit" value="abrir sesi&oacute;n" class="boton">
            <a href="recuerdaClave.php">&iquest;olvid&oacute; su contrase&ntilde;a?</a> 
          </td>
		</form>		
        <td align="right" class="campo"><a href="registrar.php">Registrar</a></td>
			</tr>
		</table>
	</td></tr>
	<!-- Fin de fila de acceso usuarios -->
<?php
		} else {
?>
<!-- Principio de la fila de los filtros -->
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
		  <?php if ($permitir_filtro_foro) { ?>
          <form name="filtrar" action="foro_protegido.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>" enctype="multipart/form-data" method="post">
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
		<?php } ?>			 
        <td align="right" class="campo">Usuario: </font><font class="valor"><a href="usuarios.php">
          <?=$login_usuario?></a>
          </font>
		  <?php if ($mostrar_usuarios_conectados_foro) { ?>| Conectados: </font><font class="valor"><?=$ol->get_num_usuarios()?><?php } ?>
          </font> | 
		  	<?php if ($permitir_iconos_como_enlaces) { ?>
    		<a href="logout.php"><img src="img/logout.gif" alt="Cerrar sesi&oacute;n" width="20" height="20" align="absmiddle"></a>
			<?php } else { ?>
			<a href="logout.php">Cerrar sesi&oacute;n</a>
			<?php } ?></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de los filtros -->
<?php
	}
	if ($permitir_paginado_foro) {
		//Calculamos los datos necesarios para realizar la paginaci�n
		$limite_inf = ($pag * $num_mensajes_x_pagina_foro) - $num_mensajes_x_pagina_foro;
		$limite_sup = (($pag + 1) * $num_mensajes_x_pagina_foro) - $num_mensajes_x_pagina_foro ;
		
		//Creamos la sentencia sql y consultamos seg�n el filtro si se da el caso
		$sql = "select id_mensaje, asunto, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes_protegidos where id_tema='$id_tema' and id_padre='0'";
		if ($permitir_filtro_foro) $sql .= " ".$tabla['filtro']." ";
		$sql .= "order by fecha_publicacion DESC";
		$res = consulta ($sql);
		
		$num_mensajes_total = @mysql_num_rows($res);
		$num_paginas = (int)(($num_mensajes_total-1) / $num_mensajes_x_pagina_foro) + 1;
		@mysql_free_result($res);
	}
	
	//Creamos la sentencia sql de consulta con el filtro y el orden en su caso
	$sql = "select id_mensaje, asunto, usuario, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes_protegidos where id_tema='$id_tema' and id_padre='0'";
	if ($permitir_filtro_foro) $sql .= " ".$tabla['filtro']." ".$tabla['orden']." ";
	if ($permitir_paginado_foro) $sql .= "LIMIT $limite_inf,$num_mensajes_x_pagina_foro";
	
	//Creamos la fila con la lista de mensajes del foro
	if (!isset($filtro)) $filtro = "";
	if (!isset($orden)) $orden = "";
	listar_mensajes_protegidos($sql, $id_tema, $titulo, $pag, $filtro, $orden, false);

	if ($permitir_paginado_foro) {
		//Creamos la fila de la paginaci�n
		crear_paginacion($limite_inf, $limite_sup, $num_paginas, $num_mensajes_total, $id_tema, $titulo, $pag, $filtro, $orden);
	}
	
	//Imprimimos el pie de p�gina
	imprime_pie_de_pagina();
?>