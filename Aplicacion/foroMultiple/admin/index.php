<?php
	session_start();
	include "../includes/funciones.inc.php";
	
	//Cierra la sesi�n en su caso y recarga la p�gina
	if (@$HTTP_GET_VARS['logout']=='si') {
		session_destroy();
		header("Location: index.php");
		exit;
	}

	imprime_encabezado_de_pagina('Administrador', '', true); //Crea el encabezado y abre la tabla
	
	//COMPROBAMOS CAMBIO DE VISIBILIDAD DE TEMA
	if (@$HTTP_GET_VARS['publicar']  && $permitir_moderacion_foro) {
		if (consulta ("UPDATE temas_privados SET visible='".@$HTTP_GET_VARS['publicar']."' WHERE id_tema='".@$HTTP_GET_VARS['id']."'"))
			echo "<tr><td class=\"fila_de_tabla\">La visibilidad del foro con id=".@$HTTP_GET_VARS['id']." se ha cambiado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al cambiar la visibilidad del tema con id=".@$HTTP_GET_VARS['id'].".</td></tr>\n";
	}
	
	//COMPROBAMOS CAMBIO DE VISIBILIDAD DE MENSAJE
	if (@$HTTP_GET_VARS['publicarM']  && $permitir_moderacion_foro) {
		if (consulta ("UPDATE mensajes_privados SET visible='".@$HTTP_GET_VARS['publicarM']."' WHERE id_tema='".@$HTTP_GET_VARS['id_tema']."' AND id_mensaje=".@$HTTP_GET_VARS['id']))
			echo "<tr><td class=\"fila_de_tabla\">La visibilidad del mensaje con id=".@$HTTP_GET_VARS['id']." se ha cambiado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al cambiar la visibilidad del mensaje con id=".@$HTTP_GET_VARS['id'].".</td></tr>\n";
	}
	
	//COMPROBAMOS BORRADO DE FORO
	if (strstr(@$HTTP_GET_VARS['borrar'],'foro') && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('�Est� seguro de que quiere borrar el foro ";
		if ($HTTP_GET_VARS['borrar']=='foro_publico')
			echo "p�blico";
		else if ($HTTP_GET_VARS['borrar']=='foro_protegido')
			echo "protegido";
		else
			echo "privado";
		echo " con id=".@$HTTP_GET_VARS['id']."?\\nSi lo borra se eliminar�n todos los mensajes de este foro\\n\\n���Recuerde que por ley los mensajes deben permanecer un a�o en el foro antes de ser borrados!!!\\nCOMPRUEBE LA FECHA DEL �LTIMO MENSAJE'))\n";
        echo "		document.location='index.php?borrar=".$HTTP_GET_VARS['borrar']."&id=".@$HTTP_GET_VARS['id']."&id_tema=".@$HTTP_GET_VARS['id_tema']."&afirmar=si';\n";
		echo "	else	document.location='index.php';\n";
		echo "</script>\n";
	} else if (strstr(@$HTTP_GET_VARS['borrar'],'foro') && @$HTTP_GET_VARS['afirmar']=='si') {
		if ($HTTP_GET_VARS['borrar']=='foro_publico') {
			$tabla_foro = "temas";
			$tabla_mensajes = "mensajes";
		} else if ($HTTP_GET_VARS['borrar']=='foro_protegido') {
			$tabla_foro = "temas_protegidos";
			$tabla_mensajes = "mensajes_protegidos";
		} else {
			$tabla_foro = "temas_privados";
			$tabla_mensajes = "mensajes_privados";
		}
		if (consulta ("DELETE FROM $tabla_foro WHERE id_tema='".@$HTTP_GET_VARS['id']."'") && consulta("DELETE FROM $tabla_mensajes WHERE id_tema='".@$HTTP_GET_VARS['id']."'"))
			echo "<tr><td class=\"fila_de_tabla\">El foro se ha borrado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al borrar el foro.</td></tr>\n";
	}
	
	//COMPROBAMOS BORRADO DE MENSAJE
	if (strstr(@$HTTP_GET_VARS['borrar'],'mensaje') && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('�Est� seguro de que quiere borrar el mensaje con id=".@$HTTP_GET_VARS['id']."?\\nSi lo borra se eliminar�n todas las respuestas al mismo\\n\\n���Recuerde que por ley los mensajes deben permanecer un a�o en el foro antes de ser borrados!!!\\nCOMPRUEBE LA FECHA DEL �LTIMO MENSAJE'))\n";
        echo "		document.location='index.php?borrar=".$HTTP_GET_VARS['borrar']."&id=".@$HTTP_GET_VARS['id']."&id_tema=".@$HTTP_GET_VARS['id_tema']."&afirmar=si';\n";
		echo "	else	document.location='index.php?id_tema=".@$HTTP_GET_VARS['id_tema']."';\n";
		echo "</script>\n";
	} else if (strstr(@$HTTP_GET_VARS['borrar'],'mensaje') && @$HTTP_GET_VARS['afirmar']=='si') {
		if ($HTTP_GET_VARS['borrar']=='mensaje_publico')
			$tabla_mensajes = "mensajes";
		else if ($HTTP_GET_VARS['borrar']=='mensaje_protegido')
			$tabla_mensajes = "mensajes_protegidos";
		else
			$tabla_mensajes = "mensajes_privados";
		if (consulta("DELETE FROM $tabla_mensajes WHERE id_mensaje='".$HTTP_GET_VARS['id']."' AND id_tema='".$HTTP_GET_VARS['id_tema']."'") && borrar_hijos($HTTP_GET_VARS['id'],$HTTP_GET_VARS['id_tema'],$tabla_mensajes))
			echo "<tr><td class=\"fila_de_tabla\">El mensaje se ha borrado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">Se ha producido un error al borrar el foro.</td></tr>\n";
	}
	
	//COMPROBAMOS BORRADO DE USUARIO
	if (@$HTTP_GET_VARS['borrar']=='usuario' && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('�Est� seguro de que quiere borrar el usuario \'".@$HTTP_GET_VARS['id']."\'?\\nSi lo borra se eliminar�n todos sus mensajes\\n\\n���Recuerde que por ley los mensajes deben permanecer un a�o en el foro antes de ser borrados!!!\\nCOMPRUEBE LA FECHA DE SU �LTIMO MENSAJE'))\n";
        echo "		document.location='index.php?borrar=usuario&id=".@$HTTP_GET_VARS['id']."&afirmar=si';\n";
		echo "	else	document.location='index.php';\n";
		echo "</script>\n";
	} else if (@$HTTP_GET_VARS['borrar']=='usuario' && @$HTTP_GET_VARS['afirmar']=='si') {
		if (consulta("DELETE FROM mensajes_privados WHERE usuario='".$HTTP_GET_VARS['id']."'") && consulta("DELETE FROM mensajes_protegidos WHERE usuario='".$HTTP_GET_VARS['id']."'") && consulta("DELETE FROM usuarios WHERE login='".$HTTP_GET_VARS['id']."'"))
			echo "<tr><td class=\"fila_de_tabla\">El usuario se ha borrado correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">ERROR al borrar el usuario ".$HTTP_GET_VARS['id'].".</td></tr>\n";
	}
	
	//COMPROBAMOS ASIGNACI�N DE NUEVO CLAVE DE USUARIO
	if (@$HTTP_GET_VARS['nuevaClave']=='SI' && !@$HTTP_GET_VARS['afirmar']) {
		echo "<script language='JavaScript'>\n";
      	echo "	if (confirm('�Est� seguro de que quiere asignar una nueva clave al usuario con id=".@$HTTP_GET_VARS['id']."?\\nSi le cambia la clave deber� informar por email al usuario su nueva clave.'))\n";
        echo "		document.location='index.php?nuevaClave=SI&id=".@$HTTP_GET_VARS['id']."&afirmar=si';\n";
		echo "	else	document.location='index.php';\n";
		echo "</script>\n";
	} else if (@$HTTP_GET_VARS['nuevaClave']=='SI' && @$HTTP_GET_VARS['afirmar']=='si') {
		include "../includes/mod_claves.inc.php";
		$password_nuevo = crear_clave_aleatoria (10, 12); //Creamos una clave aleatoria nueva para el usuario
		$passwd = md5($password_nuevo); //La codificamos y se la asignamos...
		if (consulta("UPDATE usuarios SET password='$passwd' WHERE id_usuario='".$HTTP_GET_VARS['id']."'"))
			echo "<tr><td class=\"fila_de_tabla\">La clave nueva para el usuario ".$HTTP_GET_VARS['id']." es: <b>$password_nuevo</b>. Copie este password y env&iacute;eselo por email al usuario antes de pasar esta p&aacute;gina.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">ERROR al cambiar la clave al usuario ".$HTTP_GET_VARS['id'].".</td></tr>\n";
	}
	
	//COMPROBAMOS ACTIVACI�N DE USUARIO
	if (@$HTTP_GET_VARS['user']) {
		if (consulta ("UPDATE usuarios SET tiene_permiso='SI' WHERE id_usuario='".@$HTTP_GET_VARS['user']."'"))
			echo "<tr><td class=\"fila_de_tabla\">Se ha activado un usuario correctamente.</td></tr>\n";
		else
			echo "<tr><td class=\"fila_de_tabla\">ERROR al activar el usuario.</td></tr>\n";
	}
	
	//COMPROBAMOS SI HAY OPERACIONES A REALIZAR	
	if (@$HTTP_POST_VARS) { //Seg�n la operaci�n a realizar
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
	
	//Creaci�n de la p�gina del administrador
	if (@session_is_registered('login')){
		if (@$HTTP_GET_VARS['cambio_clave']=='si') {
			presentar_formulario_clave ();
		} else {
			presentar_pagina($HTTP_GET_VARS);
		}
	} else
		login();
		
	imprime_pie_de_pagina(); //Cierra la tabla e imprime el pie de p�gina
		
	
	//============================================
	//PRESENTACION DE LAS FUNCIONES DE ESTA P�GINA
	//============================================
	function presentar_pagina($gets) {
		include "../includes/config.inc.php";
	
		global $autor_mensaje, $permitir_varios_foros, $tipo_verificacion_usuario;
		
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
			//Comprobamos si se permiten varios foros o no, en caso negativo mostramos los mensajes del �nico foro
			if (!$permitir_varios_foros) $id_tema = 1;
			else $id_tema = $gets['id_tema'];
			
			//Presentamos la lista de mensajes seg�n el tipo de foro
			if ($gets['foro']=='privado') {
				//Creamos la sentencia sql de consulta
				$sql = "select id_mensaje, asunto, usuario, ";
				if ($permitir_moderacion_foro) $sql .= "visible, ";
				$sql .= " fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes_privados where id_tema='$id_tema' and id_padre='0'";
				listar_mensajes_privados($sql, $id_tema, '', 1, '', '', true);
			} else if ($gets['foro']=='protegido') {
				//Creamos la sentencia sql de consulta
				$sql = "select id_mensaje, asunto, usuario, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes_protegidos where id_tema='$id_tema' and id_padre='0'";
				listar_mensajes_protegidos($sql, $id_tema, '', 1, '', '', true);
			} else {
				//Creamos la sentencia sql de consulta
				$sql = "select id_mensaje, asunto, autor, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta from mensajes where id_tema='$id_tema' and id_padre='0'";
				listar_mensajes($sql, $id_tema, '', 1, '', '', true);
			}
		}
		else {//MENSAJES DE UN FORO
?>
	<tr><td class="encabezado_de_temas">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="encabezado_de_temas">FOROS P&Uacute;BLICOS</td>
			</tr>
		</table>
	</td></tr>
<?php
			listar_foros(true);
?>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="encabezado_de_temas">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="encabezado_de_temas">FOROS PROTEGIDOS</td>
			</tr>
		</table>
	</td></tr>
<?php
			listar_foros_protegidos(true);
?>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="encabezado_de_temas">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="encabezado_de_temas">FOROS PRIVADOS</td>
			</tr>
		</table>
	</td></tr>
<?php
			listar_foros_privados(true);
?>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="encabezado_de_temas">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="encabezado_de_temas">USUARIOS</td>
			</tr>
		</table>
	</td></tr>
<?php
			listar_usuarios();
		}
	}
	
	
	//FUNCION PARA LISTAR LOS USUARIOS
	function listar_usuarios() {
		global $tipo_verificacion_usuario;
		$res = consulta ("SELECT * FROM usuarios ORDER BY tiene_permiso, id_usuario DESC");
				
		echo "<tr><td>\n";
		if (@mysql_num_rows($res)>0) {
		?>
		<table width="100%" border="0" cellspacing="2" cellpadding="1" class="tabla_de_temas">
			  <tr class="encabezado_de_temas">
				<td align="center">ID.</td>
				<td>USUARIO</td>
				<td>NOMBRE</td>
				<td align="center">EMAIL</td>
				<td align="center">ALTA</td>
				<td align="center">ULT.ACCESO</td>
				<td align="center">ACTIVO</td>
				<td align="center">&nbsp;</td>
				<?php if ($tipo_verificacion_usuario=='administrador') { ?><td align="center">&nbsp;</td><?php } ?>
			  </tr>
		<?php
			while ($reg = @mysql_fetch_array($res)) {
				$id_usuario = $reg ['id_usuario'];
				$login = $reg ['login'];
				$nombre = $reg ['nombre_usuario']." ".$reg ['apellidos_usuario'];
				$email = $reg ['email_usuario'];
				$fecha_alta = $reg ['fecha_alta'];
				$fecha_ultimo_acceso = $reg ['fecha_ultimo_acceso'];
				$estado = $reg ['tiene_permiso'];
				@mysql_free_result($res_num_mensajes);
				echo "			<tr class=\"fila_de_tabla\">\n";
				echo "            <td align=\"center\">$id_usuario</td>\n";
				echo "            <td>$login</td>\n";
				echo "            <td>$nombre</td>\n";
				echo "            <td align=\"center\"><a href=\"mailto:$email\">$email</a></td>\n";
				echo "            <td align=\"center\">".formatear_fecha($fecha_alta)."</td>\n";
				if ($fecha_ultimo_acceso)
					echo "            <td align=\"center\">".formatear_fecha($fecha_ultimo_acceso)."</td>\n";
				else
					echo "            <td align=\"center\">NUNCA</td>\n";	
				if ($estado=='NO' && $tipo_verificacion_usuario=='administrador') echo "            <td align=\"center\"><a href=\"index.php?user=$id_usuario\">Activar usuario</a></td>\n";
				else echo "            <td align=\"center\">$estado</td>\n";
				echo "            <td align=\"center\"><a href=\"index.php?borrar=usuario&id=$login\">borrar usuario</a></td>\n";
				if ($tipo_verificacion_usuario=='administrador')
					echo "            <td align=\"center\"><a href=\"index.php?nuevaClave=SI&id=$id_usuario\">Nueva clave</a></td>\n";
				echo "</tr>\n";
			}
			echo "		</table>\n";
		} else {
			echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\" class=\"tabla_de_temas\"><tr class=\"fila_de_tabla\"><td>No hay usuarios</td></tr></table>\n";
		}
		@mysql_free_result($res);
		echo "</td></tr>\n";
	}
	
	
	//FUNCIONES ESPEC�FICAS DEL ADMINISTRADOR
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
<!-- Principio de la fila con el FORMULARIO DE ENV�O -->
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
<!-- Fin de la fila con el FORMULARIO DE ENV�O -->
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
<!-- Principio de la fila con el FORMULARIO DE ENV�O -->
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
                <td><input name="nuevo_password" type="password" class="datos_form" size="20" maxlength="12"> (entre 4 y 12 caracteres)</td>
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
<!-- Fin de la fila con el FORMULARIO DE ENV�O -->
<?php
	}
	
	function borrar_hijos($id_padre,$id_tema,$tabla_mensajes) {
		$correcto = true;
		$res = consulta ("SELECT id_mensaje FROM $tabla_mensajes WHERE id_padre='$id_padre' && id_tema='$id_tema'");
		while ($reg = @mysql_fetch_array($res)) {
			$id_mensaje = $reg ['id_mensaje'];
			$correcto = $correcto && consulta("DELETE FROM $tabla_mensajes WHERE id_mensaje='$id_mensaje' && id_tema='$id_tema'") && borrar_hijos($id_mensaje,$id_tema,$tabla_mensajes);
		}
		@mysql_free_result($res);
		return $correcto;
	}
?>