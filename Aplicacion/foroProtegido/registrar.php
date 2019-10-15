<?php
	@session_start();
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $nombre_del_foro, $autor_mensaje, $email_mensaje, $web_mensaje, $permitir_iconos_como_enlaces, $tipo_verificacion_usuario, $email_administrador;
	
	//Para verificacion de código por email y alta definitiva del usuario
	if ($tipo_verificacion_usuario && @$HTTP_GET_VARS['user'] && @$HTTP_GET_VARS['cod']) {
		$res = consulta ("SELECT login FROM usuarios WHERE login='".@$HTTP_GET_VARS['user']."' AND password='".@$HTTP_GET_VARS['cod']."'");
		imprime_encabezado_de_pagina("Alta de usuario", '', false);
		if (@mysql_num_rows($res)==1) {
			consulta ("UPDATE usuarios SET tiene_permiso='SI' WHERE login='".@$HTTP_GET_VARS['user']."'");
			echo "<tr class=\"fila_de_tabla\"><td align=\"center\">USUARIO ACTIVADO CORRECTAMENTE. YA PUEDES ACCEDER AL FORO.<BR><BR><a href=\"index.php\">Ir al foro</a></td></tr>\n";
		} else
			echo "<tr class=\"fila_de_tabla\"><td align=\"center\">ERROR AL ACTIVAR AL USUARIO. ALGUNO DE LOS DATOS ES INCORRECTO.<BR><BR><A href=\"index.php\">Ir al foro</A></td></tr>\n";
		imprime_pie_de_pagina();
		exit;
	}
	
	//Si se ha realizado submit grabamos los datos del nuevo usuario
	if (@$HTTP_POST_VARS) {
		//Recogemos los datos del formulario	
		if ($autor_mensaje) {
			$nombre = @$HTTP_POST_VARS['nombre_usuario'];
			$apellidos = @$HTTP_POST_VARS['apellidos_usuario'];
		 }
		if ($email_mensaje)
			$email = @$HTTP_POST_VARS['email_usuario'];
		if ($web_mensaje)
			$web = @$HTTP_POST_VARS['web_usuario'];
		$login = @$HTTP_POST_VARS['login'];
		$passwd = md5(@$HTTP_POST_VARS['password']);
		
		//Creamos la sentencia sql y la ejecutamos para dar de alta el usuario
		$sql = "INSERT INTO usuarios (login, fecha_alta, ";
		if ($autor_mensaje) $sql .= "nombre_usuario, apellidos_usuario, ";
		if ($email_mensaje) $sql .= "email_usuario, ";		
		if ($web_mensaje) $sql .= "web_usuario, ";
		$sql .= "password) VALUES ( '$login', '".date("Y-m-d H:i:s")."', ";
		if ($autor_mensaje) $sql .= "'$nombre', '$apellidos', ";
		if ($email_mensaje) $sql .= "'$email', ";		
		if ($web_mensaje) $sql .= "'$web', ";
		$sql .= "'$passwd')";
		
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		$res = consulta("SELECT login FROM usuarios WHERE login='$login'");
		$res2 = consulta("SELECT email_usuario FROM usuarios WHERE email_usuario='$email'");
		if (@mysql_num_rows($res)>0 || @mysql_num_rows($res2)>0){
			if (@mysql_num_rows($res)>0) {
				echo "<script language=\"javascript\">\n";
				echo "	alert ('El usuario ya existe, eliga otro nombre de usuario');";
				echo "	history.back();\n";
				echo "</script>\n";
				@mysql_free_result($res);
				exit;
			}
			if (@mysql_num_rows($res2)>0) {
				echo "<script language=\"javascript\">\n";
				echo "	alert ('El email ya existe, escriba otra dirección email');";
				echo "	history.back();\n";
				echo "</script>\n";
				@mysql_free_result($res2);
				exit;
			}
		} else {
			//MANDAR EMAIL PARA VERIFICACIÓN DE USUARIO, en su caso
			if ($tipo_verificacion_usuario == 'email') {
				$mensaje = "Hola $nombre!!\n\nHa sido dado de alta como usuario en el foro $nombre_del_foro con los siguientes datos:\n\n";
			    $mensaje.= "Usuario: $login\n";
			   	$mensaje.= "Password: ".$HTTP_POST_VARS['password']."\n\n";
				$mensaje.= "\nPara activar su usuario y poder utilizar el servicio debe acceder a la siguiente direcci&oacute;n:\n\n";
				$mensaje.= "http://".$HTTP_SERVER_VARS['SERVER_NAME']."$PHP_SELF?user=$login&cod=$passwd";
			   	@mail($email, "Alta en el foro $nombre_del_foro", $mensaje, "From: ".$email_administrador."\nReply-To: ".$email_administrador."\nX-Mailer: PHP/" . phpversion());
			}
			consulta ($sql);
			echo "<script language=\"javascript\">\n";
			echo "	alert ('Usuario dado de alta correctamente.";
			if ($tipo_verificacion_usuario == 'email') echo "\\n\\nEn breve recibirá un email con los datos para acceder al foro.";
			else echo "\\n\\nEn breve su cuenta de usuario será activada.";
			echo "');\n";
			echo "	document.location = 'index.php';\n";
			echo "</script>\n";
		}
		@mysql_free_result($res);
		@mysql_free_result($res2);
	}
	$javascript = "
<script language=\"JavaScript\" src=\"includes/funciones.js\" type=\"text/javascript\"></script>
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function comprobarFormulario(form) { ";
if ($autor_mensaje) {
	$javascript .= "
	//Comprueba si se ha introducido nombre de usuario
	if (form.nombre_usuario.value==\"\") {
		alert (\"No puedes dejar el campo nombre vacío\");
		form.nombre_usuario.focus();
		return (false);
	}
	
	//Comprueba si se ha introducido apellidos de usuario
	if (form.apellidos_usuario.value==\"\") {
		alert (\"No puedes dejar los campo apellidos vacío\");
		form.apellidos_usuario.focus();
		return (false);
	}
	";
}
	
if ($email_mensaje) {
	$javascript .= "	
	//Comprueba si se ha escrito una dirección de correo válida
	if (form.email_usuario.value==\"\") {
		alert (\"No puedes dejar el campo email vacío\");
		form.email_usuario.focus();
		return (false);
	}
	else if (!validarEmail(form.email_usuario.value)){
		form.email_usuario.focus();
		return (false);
	}
	";
}

$javascript .= "	
	//Comprueba si se ha introducido el nombre de usuario correcto
	if (form.login.value==\"\" || form.login.value.length<4 || form.login.value.indexOf(' ')!=-1) {
		alert (\"No puedes dejar el campo nombre de usuario vacío o el número de caracteres es menor a 4 o hay espacios en blanco\");
		form.login.focus();
		return (false);
	}
	
	//Comprueba si se ha introducido el password correcto
	if (form.password.value==\"\" || form.password.value.length<5 || form.password.value!=form.password_rep.value) {
		alert (\"No puedes dejar el campo password vacío o el número de caracteres es menor a 5 o no coincide con el repetido\");
		form.password.focus();
		return (false);
	}
	";

$javascript .= "
	form.submit();
	return (true);
}

//Comprueba si se ha escrito la dirección web completa, en caso contrario la completa
function comprobarHTTP(src) {
	if (src.value.toUpperCase().indexOf(\"HTTP://\")!=0 && src.value!=\"\")
		src.value=\"http://\"+src.value;
}
//-->
</script>\n";
	
	//Encabezado de página
	imprime_encabezado_de_pagina("Registro de un nuevo usuario", $javascript, false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 25;
	else
		$tam_enlaces = 75;
?>
<!-- Principio de la fila de enlaces -->
    <tr> 
      <td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="encabezado_de_foro">
              REGISTRO DE UN NUEVO USUARIO
            </td>
            <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
				<?php if ($permitir_iconos_como_enlaces) { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="<?=$HTTP_REFERER?>"><img src="img/volver1.gif" alt="Volver atr&aacute;s" width="20" height="20"></a></td>
				 <?php } else { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="<?=$HTTP_REFERER?>">Volver atr&aacute;s</a></td>
				 <?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td><form name="mensaje" method="POST" action="<?=$PHP_SELF;?>" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td colspan="2" align="center"><font size="+1"><b>Datos personales</b></font></td>
              </tr>
			  <?php if ($autor_mensaje) { ?>
              <tr> 
                <td width="35%" class="campo_oblig"><div align="right">Nombre: 
                  </div></td>
                <td width="65%"><input name="nombre_usuario" type="text" class="datos_form" size="50" maxlength="255"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Apellidos: </font></div></td>
                <td><input name="apellidos_usuario" type="text" class="datos_form" size="50" maxlength="255"></td>
              </tr>
              <?php } if ($email_mensaje) {?>
              <tr> 
                <td><div align="right"><font class="campo_oblig">E-mail: </font></div></td>
                <td><input name="email_usuario" type="text" class="datos_form" size="50" maxlength="255"></td>
              </tr>
              <?php } if ($web_mensaje) { ?>
              <tr> 
                <td><div align="right"><font class="campo">Web: </font></div></td>
                <td><input name="web_usuario" type="text" class="datos_form" size="50" maxlength="255" onBlur="comprobarHTTP(document.mensaje.web_usuario);"></td>
              </tr>
              <?php }?>
              <tr></table>
			  <table width="100%" border="0" cellspacing="5" cellpadding="0">
                <td colspan="2" align="center"><font size="+1"><b>Datos de acceso</b></font></td>
              </tr>
              <tr> 
                <td width="45%"><div align="right"><font class="campo_oblig">Nombre de usuario: 
                    </font></div></td>
                <td width="55%"><input name="login" type="text" class="datos_form" size="20" maxlength="12">
                  <font class="valor">(entre 4 y 12 caracteres sin espacios)</font></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Password: </font></div></td>
                <td><input name="password" type="password" class="datos_form" size="20" maxlength="255">
                  <font class="valor">(m&iacute;nimo 5 caracteres)</font></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Repite el password: 
                    </font></div></td>
                <td><input name="password_rep" type="password" class="datos_form" size="20" maxlength="255"></td>
              </tr>
              <tr> 
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"> <div align="center"> 
                    <input type="button" name="enviar" value="Enviar" class="boton" onClick="javascript:comprobarFormulario(document.mensaje);">
                    &nbsp;&nbsp;&nbsp; 
                    <input type="reset" name="borrar" value="Borrar" class="boton">
                  </div></td>
              </tr>
            </table></td>
            </tr>
          </table>
        </form></td></tr>
<!-- Fin de la fila con el FORMULARIO DE ENVÍO -->
<?php imprime_pie_de_pagina(); ?>
                