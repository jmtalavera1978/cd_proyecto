<?php
	@session_start();
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $nombre_del_foro, $autor_mensaje, $email_mensaje, $web_mensaje, $permitir_iconos_como_enlaces, $tipo_verificacion_usuario, $email_administrador;
	
	if (!session_is_registered('login_usuario')) {
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		echo "	alert ('Acceso denegado. Inicie sesión para acceder a esta sección.');";
		echo "	document.location='index.php';\n";
		echo "</script>\n";
		exit;
	}
	
	//Resuperamos los datos del usuario
	$res = consulta ("SELECT * FROM usuarios WHERE login='".$_SESSION['login_usuario']."'");			
	$reg = @mysql_fetch_array($res);
	$nombre = $reg ['nombre_usuario'];
	$apellidos = $reg ['apellidos_usuario'];
	if ($email_mensaje) $email = $reg ['email_usuario'];
	if ($web_mensaje) $web = $reg ['web_usuario'];
	
	//COMPROBAMOS LOS SUBMITS
	if (@$HTTP_POST_VARS['email_usuario']) {			
		//Recogemos los datos del formulario	
		if ($autor_mensaje) {
			$nombre = @$HTTP_POST_VARS['nombre_usuario'];
			$apellidos = @$HTTP_POST_VARS['apellidos_usuario'];
		 }
		if ($email_mensaje)
			$email = @$HTTP_POST_VARS['email_usuario'];
		if ($web_mensaje)
			$web = @$HTTP_POST_VARS['web_usuario'];			
		
		//Creamos la sentencia sql y la ejecutamos para modificar los datos del usuario
		$sql = "UPDATE usuarios SET ";
		if ($autor_mensaje) $sql .= "nombre_usuario='$nombre', apellidos_usuario='$apellidos', ";
		if ($email_mensaje) $sql .= "email_usuario='$email', ";		
		if ($web_mensaje) $sql .= "web_usuario='$web', ";
		$sql .= "login='".@$_SESSION['login_usuario']."' WHERE login='".@$_SESSION['login_usuario']."'";

		consulta ($sql);
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		echo "	alert ('Datos guardados correctamente.');";
		echo "	document.location = 'usuarios.php';\n";
		echo "</script>\n";
		
		//volvemos a resuperar los datos del usuario
		$res = consulta ("SELECT * FROM usuarios WHERE login='".$_SESSION['login_usuario']."'");			
		$reg = @mysql_fetch_array($res);
		$nombre = $reg ['nombre_usuario'];
		$apellidos = $reg ['apellidos_usuario'];
		if ($email_mensaje) $email = $reg ['email_usuario'];
		if ($web_mensaje) $web = $reg ['web_usuario'];

	} 
	if (@$HTTP_POST_VARS['password']) {
		$passwd = md5(@$HTTP_POST_VARS['password']);
		consulta("UPDATE usuarios SET password='$passwd' WHERE login='".$_SESSION['login_usuario']."'");
		//MANDAR EMAIL PARA VERIFICACIÓN DE USUARIO, en su caso
		if ($tipo_verificacion_usuario == 'email') {
			$mensaje = "Hola $nombre!!\n\nSus datos de acceso en el foro $nombre_del_foro han sido modificados:\n\n";
			$mensaje.= "Usuario: ".$_SESSION['login_usuario']."\n";
			$mensaje.= "Password: ".$HTTP_POST_VARS['password']."\n\n";
			//$mensaje.= "\nPara activar su usuario y poder utilizar el servicio debe acceder a la siguiente direcci&oacute;n:\n\n";
			//$mensaje.= "http://".$HTTP_SERVER_VARS['SERVER_NAME']."registrar.php?user=".$_SESSION['login_usuario']."&cod=$passwd";
			@mail($email, "Acceso al foro $nombre_del_foro", $mensaje, "From: ".$email_administrador."\nReply-To: ".$email_administrador."\nX-Mailer: PHP/" . phpversion());
		}
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		echo "	alert ('Password cambiado correctamente.";
		if ($tipo_verificacion_usuario == 'email') echo "\\n\\nEn breve recibirá un email con los datos para acceder al foro.";
		echo "');\n";
		echo "	document.location = 'usuarios.php';\n";
		echo "</script>\n";
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
	form.submit();
	return (true);
}

function comprobarPassword(form) {
	//Comprueba si se ha introducido el password correcto
	if (form.password.value==\"\" || form.password.value.length<5 || form.password.value!=form.password_rep.value) {
		alert (\"No puedes dejar el campo password vacío o el número de caracteres es menor a 5 o no coincide con el repetido\");
		form.password.focus();
		return (false);
	}
	
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
	imprime_encabezado_de_pagina("Datos de usuario", $javascript, false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 25;
	else
		$tam_enlaces = 75;
?>
<!-- Principio de la fila de enlaces -->
    <tr> 
      <td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            
        <td class="encabezado_de_foro">DATOS DE USUARIO</td>
            <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
				<?php if ($permitir_iconos_como_enlaces) { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="<?php if (@$HTTP_REFERER) echo $HTTP_REFERER; else echo "index.php"; ?>"><img src="img/volver1.gif" alt="<?php if (@$HTTP_REFERER) echo "Volver atr&aacute;s"; else echo "Salir"; ?>" width="20" height="20"></a></td>
				 <?php } else { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="<?php if (@$HTTP_REFERER) echo $HTTP_REFERER; else echo "index.php"; ?>"><?php if (@$HTTP_REFERER) echo "Volver atr&aacute;s"; else echo "Salir"; ?></a></td>
				 <?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td>
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><form name="datos" method="POST" action="<?=$PHP_SELF;?>" enctype="multipart/form-data"><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td colspan="2" align="center"><font size="+1"><b>Datos personales</b></font></td>
              </tr>
              <?php if ($autor_mensaje) { ?>
              <tr> 
                <td width="35%" class="campo_oblig"><div align="right">Nombre: 
                  </div></td>
                <td width="65%"><input name="nombre_usuario" type="text" class="datos_form" size="50" maxlength="255" value="<?=$nombre?>"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Apellidos: </font></div></td>
                <td><input name="apellidos_usuario" type="text" class="datos_form" size="50" maxlength="255" value="<?=$apellidos?>"></td>
              </tr>
              <?php } if ($email_mensaje) {?>
              <tr> 
                <td><div align="right"><font class="campo_oblig">E-mail: </font></div></td>
                <td><input name="email_usuario" type="text" class="datos_form" size="50" maxlength="255" value="<?=$email?>"></td>
              </tr>
              <?php } if ($web_mensaje) { ?>
              <tr> 
                <td><div align="right"><font class="campo">Web: </font></div></td>
                <td><input name="web_usuario" type="text" class="datos_form" size="50" maxlength="255" onBlur="comprobarHTTP(document.datos.web_usuario);"  value="<?=$web?>"></td>
              </tr>
              <tr> 
                <td colspan="2"><div align="center">
                    <input type="button" name="grabar_datos" value="Grabar datos" class="boton" onClick="javascript:comprobarFormulario(document.datos);">
                    &nbsp;&nbsp;&nbsp; 
                    <input type="reset" name="borrar2" value="Cancelar cambios" class="boton">
                  </div></td>
              </tr>
              <?php }?>
            </table>
			</form>
			 <form name="password" method="POST" action="<?=$PHP_SELF;?>" enctype="multipart/form-data"> 
            <table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td colspan="2" align="center">&nbsp;</td>
              </tr>
                <td colspan="2" align="center"><font size="+1"><b>Datos de acceso</b></font></td>
              </tr>
              <tr> 
                <td width="45%"><div align="right"><font class="campo_oblig">Nombre 
                    de usuario: </font></div></td>
                <td width="55%" class="valor">
                  <?=$_SESSION['login_usuario']?>
                </td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Nuevo password: 
                    </font></div></td>
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
                    <input type="button" name="cambiar_password" value="Cambiar password" class="boton" onClick="javascript:comprobarPassword(document.password);">
                    &nbsp;&nbsp;&nbsp; 
                    <input type="reset" name="borrar" value="Borrar" class="boton">
                  </div></td>
              </tr>
            </table></form></td>
            </tr>
          </table>
        </td></tr>
<!-- Fin de la fila con el FORMULARIO DE ENVÍO -->
<?php imprime_pie_de_pagina(); ?>
                