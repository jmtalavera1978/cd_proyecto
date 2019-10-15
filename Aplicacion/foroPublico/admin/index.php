<?php
	session_start();
	include "../includes/funciones.inc.php";
	
	//Cierra la sesión en su caso y recarga la página
	if (@$HTTP_GET_VARS['logout']=='si') {
		session_destroy();
		header("Location: index.php");
		exit;
	}

	imprime_encabezado_de_pagina('Administrador', '', true); //Crea el encabezado y abre la tabla
	
	//COMPROBAMOS BORRADO DE FORO
	if (@$HTTP_GET_VARS['borrar']=='foro' && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('¿Está seguro de que quiere borrar el foro con id=".@$HTTP_GET_VARS['id']."?\\nSi lo borra se eliminarán todos los mensajes de este foro\\n\\n¡¡¡Recuerde que por ley los mensajes deben permanecer un año en el foro antes de ser borrados!!!\\nCOMPRUEBE LA FECHA DEL ÚLTIMO MENSAJE'))\n";
        echo "		document.location='index.php?borrar=foro&id=".@$HTTP_GET_VARS['id']."&id_tema=".@$HTTP_GET_VARS['id_tema']."&afirmar=si';\n";
		echo "	else	document.location='index.php';\n";
		echo "</script>\n";
	} else if (@$HTTP_GET_VARS['borrar']=='foro' && @$HTTP_GET_VARS['afirmar']=='si') {
		if (consulta ("DELETE FROM temas WHERE id_tema='".@$HTTP_GET_VARS['id']."'") && consulta("DELETE FROM mensajes WHERE id_tema='".@$HTTP_GET_VARS['id']."'"))
			echo "<tr><td class=\"fila_de_tabla\">El foro se ha borrado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al borrar el foro.</td></tr>\n";
	}
	
	//COMPROBAMOS BORRADO DE MENSAJE
	if (@$HTTP_GET_VARS['borrar']=='mensaje' && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('¿Está seguro de que quiere borrar el mensaje con id=".@$HTTP_GET_VARS['id']."?\\nSi lo borra se eliminarán todas las respuestas al mismo\\n\\n¡¡¡Recuerde que por ley los mensajes deben permanecer un año en el foro antes de ser borrados!!!\\nCOMPRUEBE LA FECHA DEL ÚLTIMO MENSAJE'))\n";
        echo "		document.location='index.php?borrar=mensaje&id=".@$HTTP_GET_VARS['id']."&id_tema=".@$HTTP_GET_VARS['id_tema']."&afirmar=si';\n";
		echo "	else	document.location='index.php?id_tema=".@$HTTP_GET_VARS['id_tema']."';\n";
		echo "</script>\n";
	} else if (@$HTTP_GET_VARS['borrar']=='mensaje' && @$HTTP_GET_VARS['afirmar']=='si') {
		if (consulta("DELETE FROM mensajes WHERE id_mensaje='".$HTTP_GET_VARS['id']."' && id_tema='".$HTTP_GET_VARS['id_tema']."'") && borrar_hijos($HTTP_GET_VARS['id'],$HTTP_GET_VARS['id_tema']))
			echo "<tr><td class=\"fila_de_tabla\">El mensaje se ha borrado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al borrar el foro.</td></tr>\n";
	}
	
	//COMPROBAMOS SI HAY OPERACIONES A REALIZAR	
	if (@$HTTP_POST_VARS) { //Según la operación a realizar
		if (@$HTTP_POST_VARS['login'] || @$HTTP_POST_VARS['password']) { //LOGIN
			$login = $HTTP_POST_VARS['login'];
			$passwd = md5($HTTP_POST_VARS['password']);
			$res = consulta ("SELECT id_administrador, login FROM administrador WHERE login='$login' AND password='$passwd'");
			if (@mysql_num_rows($res)==1){
				$reg = @mysql_fetch_array($res);
				$id_administrador = $reg ['id_administrador'];
				session_register('login');
				session_register('id_administrador');
			} else
				echo "<tr><td class=\"fila_de_tabla\">Usuario o password incorrectos.</td></tr>\n";
			@mysql_free_result($res);
		} else if (@$HTTP_POST_VARS['nuevo_login'] || @$HTTP_POST_VARS['nuevo_password'] || @$HTTP_POST_VARS['nuevo_password_rep']) {
			$login = $HTTP_POST_VARS['nuevo_login'];
			$passwd = $HTTP_POST_VARS['nuevo_password'];
			if ($passwd==$HTTP_POST_VARS['nuevo_password_rep'] && strlen($passwd)>3 && $login!=""){
				$passwd = md5($passwd); //codificamos el password
				if (consulta ("UPDATE administrador SET login='$login', password='$passwd' WHERE id_administrador='".$_SESSION['id_administrador']."'"))
					echo "<tr><td class=\"fila_de_tabla\"><b>Datos de entrada de administrador cambiados con &eacute;xito.</b></td></tr>";
				else
					echo "<tr><td class=\"fila_de_tabla\"><b>Error al cambiar los datos de entrada de administrador.</b></td></tr>\n";
			} else {
				echo "<tr><td class=\"fila_de_tabla\"><b>Error al cambiar los datos de entrada de administrador: </b>Faltan datos o no coinciden los passwords o su longitud es menor de 4.</td></tr>\n";
			}
		}
	}
	
	if (@session_is_registered('login')){
		if (@$HTTP_GET_VARS['cambio_clave']=='si') {
			presentar_formulario_clave ();
		} else {
			presentar_pagina($HTTP_GET_VARS);
		}
	} else
		login();
		
	imprime_pie_de_pagina(); //Cierra la tabla e imprime el pie de página
		
	function presentar_pagina($gets) {
		include "../includes/config.inc.php";
	
		global $autor_mensaje, $permitir_varios_foros;
		
?>
<!-- Principio de la fila de enlaces -->
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
        <td class="encabezado_de_foro">ADMINISTRACI&Oacute;N</td>
            <td width="240">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			  <td align="left"><a href='index.php'>Principal</a></td>
			  <td align="center"><a href='index.php?cambio_clave=si'>Cambiar clave</a></td>
              <td align="right"><a href='index.php?logout=si'>Cerrar sesi&oacute;n</a></td>
            </tr>
          </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<?php
		//COMPROBAMOS SI NOS ENCONTRAMOS CONSULTANDO LOS FOROS O LOS MENSAJES DE UN FORO
		if (@$gets['id_tema'] || !$permitir_varios_foros) { //FOROS
			//Comprobamos si se permiten varios foros o no, en caso negativo mostramos los mensajes del único foro
			if (!$permitir_varios_foros) $id_tema = 1;
			else $id_tema = $gets['id_tema'];

			//Creamos la sentencia sql de consulta
			$sql = "select id_mensaje, asunto,";
			if ($autor_mensaje) $sql .= " autor,";
			$sql .= " fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes where id_tema='$id_tema' and id_padre='0'";
			listar_mensajes($sql, $id_tema, '', 1, '', '', true);
		}
		else //MENSAJES DE UN FORO
			listar_foros(true);
	}
	
	
	//FUNCIONES ESPECÍFICAS DEL ADMINISTRADOR
	function login () {
?>
<!-- Principio de la fila de enlaces -->
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            
        <td class="encabezado_de_foro">ADMINISTRACI&Oacute;N</td>
            <td width="250">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>			  
              <td align="right"><a href='../'>Salir</a></td>
            </tr>
          </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td><form name="login" method="POST" action="index.php" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td width="45%" class="campo_oblig"><div align="right">Login: 
                  </div></td>
                <td width="55%"><input name="login" type="text" class="datos_form" size="20" maxlength="25"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Password: </font></div></td>
                <td><input name="password" type="password" class="datos_form" size="20" maxlength="12"></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"> <div align="center"> 
                    <input type="button" name="enviar" value="Enviar" class="boton" onClick="javascript:document.login.submit();">
                    &nbsp;&nbsp;&nbsp; 
                    <input type="reset" name="borrar" value="Borrar" class="boton">
                  </div></td>
              </tr>
            </table></td>
            </tr>
          </table>
        </form></td></tr>
<!-- Fin de la fila con el FORMULARIO DE ENVÍO -->
<?php
	}
	function presentar_formulario_clave () {
?>
<!-- Principio de la fila de enlaces -->
	<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            
        <td class="encabezado_de_foro">ADMINISTRACI&Oacute;N</td>
            <td width="150">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			  <td align="left"><a href='index.php'>Volver</a></td>			  
              <td align="right"><a href='index.php?logout=si'>Cerrar sesi&oacute;n</a></td>
            </tr>
          </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td><form name="login" method="POST" action="index.php" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td width="45%" class="campo_oblig"><div align="right">Login: 
                  </div></td>
                <td width="55%"><input name="nuevo_login" type="text" class="datos_form" size="20" maxlength="25" value="<?=$_SESSION['login']?>"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Password: </font></div></td>
                <td><input name="nuevo_password" type="password" class="datos_form" size="20" maxlength="12"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Repetir password: 
                    </font></div></td>
                <td><input name="nuevo_password_rep" type="password" class="datos_form" size="20" maxlength="12"></td>
              </tr>
              <tr> 
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"> <div align="center"> 
                    <input type="button" name="enviar" value="Enviar" class="boton" onClick="javascript:document.login.submit();">
                    &nbsp;&nbsp;&nbsp; 
                    <input type="reset" name="borrar" value="Borrar" class="boton">
                  </div></td>
              </tr>
            </table></td>
            </tr>
          </table>
        </form></td></tr>
<!-- Fin de la fila con el FORMULARIO DE ENVÍO -->
<?php
	}
	
	function borrar_hijos($id_padre,$id_tema) {
		$correcto = true;
		$res = consulta ("SELECT id_mensaje FROM mensajes WHERE id_padre='$id_padre' && id_tema='$id_tema'");
		while ($reg = @mysql_fetch_array($res)) {
			$id_mensaje = $reg ['id_mensaje'];
			$correcto = $correcto && consulta("DELETE FROM mensajes WHERE id_mensaje='$id_mensaje' && id_tema='$id_tema'") && borrar_hijos($id_mensaje,$id_tema);
		}
		@mysql_free_result($res);
		return $correcto;
	}
?>