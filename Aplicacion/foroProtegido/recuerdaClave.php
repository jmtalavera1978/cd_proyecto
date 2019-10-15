<?php
	@session_start();
	include "includes/funciones.inc.php";
	include "includes/mod_claves.inc.php";
	include "includes/config.inc.php";
	
	global $nombre_del_foro, $autor_mensaje, $web_mensaje, $permitir_iconos_como_enlaces, $tipo_verificacion_usuario, $email_administrador;
	
	//Si se ha realizado submit grabamos los datos del nuevo usuario
	if (@$HTTP_POST_VARS) {
		//Recogemos los datos del formulario	
		$email = @$HTTP_POST_VARS['email_usuario'];
		
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		//Recuperamos los datos del usuario
		$res = consulta ("SELECT * FROM usuarios WHERE email_usuario='$email'");
		if ($reg = @mysql_fetch_array($res)) {
			if ($autor_mensaje) {
				$nombre = $reg['nombre_usuario'];
				$apellidos = $reg['apellidos_usuario'];
			}
			if ($web_mensaje)
				$web = $reg['web_usuario'];
			$login = $reg['login'];
			$password_nuevo = crear_clave_aleatoria (10, 12);
			$passwd = md5($password_nuevo);
			//Insertamos el nuevo password en la base de datos
			consulta ("UPDATE usuarios SET password='$passwd' WHERE email_usuario='$email'");
			
			//MANDAR EMAIL PARA VERIFICACIÓN DE USUARIO, en su caso
			if ($tipo_verificacion_usuario == 'email') {
				$mensaje = "Hola $nombre!!\n\nSus datos de acceso al foro $nombre_del_foro son los siguientes:\n\n";
			    $mensaje.= "Usuario: $login\n";
			   	$mensaje.= "Password: $password_nuevo\n\n";
			   	@mail($email, "Recordatorio de datos de usuario para el foro $nombre_del_foro", $mensaje, "From: ".$email_administrador."\nReply-To: ".$email_administrador."\nX-Mailer: PHP/" . phpversion());
				echo "<script language=\"javascript\">\n";
				echo "	alert ('Se ha creado una nueva contraseña para el usuario.\\n\\nEn breve recibirá un email con los datos para acceder al foro.');\n";
				echo "	document.location = 'index.php';\n";
				echo "</script>\n";
			}
		} else { //NO encontrado
			echo "<script language=\"javascript\">\n";
			echo "	alert ('La dirección de correo no existe en la base de datos.');";
			echo "	document.location = 'recuerdaClave.php';\n";
			echo "</script>\n";
		}
		@mysql_free_result($res);
	}
	if ($tipo_verificacion_usuario=='email') {
		$javascript = "
<script language=\"JavaScript\" src=\"includes/funciones.js\" type=\"text/javascript\"></script>
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function comprobarFormulario(form) { 
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
	
	form.submit();
	return (true);
}
//-->
</script>\n";
	} else $javascript='';
	
	//Encabezado de página
	imprime_encabezado_de_pagina("Recordar clave", $javascript, false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 25;
	else
		$tam_enlaces = 75;
?>
<!-- Principio de la fila de enlaces -->
    <tr> 
      <td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            
        <td class="encabezado_de_foro"> RECORDATORIO DE CLAVE DE USUARIO </td>
            <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
				<?php if ($permitir_iconos_como_enlaces) { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="index.php"><img src="img/volver1.gif" alt="Volver atr&aacute;s" width="20" height="20"></a></td>
				 <?php } else { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="index.php">Volver atr&aacute;s</a></td>
				 <?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<?php if ($tipo_verificacion_usuario=='email') { ?>
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td><form name="mensaje" method="POST" action="<?=$PHP_SELF;?>" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td colspan="2" align="center"><font size="+1"><b>Escriba su email 
                  y le enviaremos sus datos de usuario</b></font></td>
              </tr>
              <tr>
                <td width="35%"><div align="right"><font class="campo_oblig">E-mail: 
                    </font></div></td>
                <td width="65%"><input name="email_usuario" type="text" class="datos_form" size="50" maxlength="255"></td>
              </tr>
              <tr> 
            </table>
            <table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td width="100%"> <div align="center"> 
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
<?php } else { ?>
<!-- Principio de la fila con el mail del administardor -->
<tr>
  <td class="fila_de_tabla" align="center">Escribe un email al <a href="mailto:<?=$email_administrador?>">administrador</a> con el asunto 'SOLICITUD PASSWORD PARA <?=$nombre_del_foro?>' e indicando tu login y/o tu email de registro.</td>
</tr>
<!-- Fin de la fila con el mail del administardor -->
<?php }
	imprime_pie_de_pagina();
?>
                