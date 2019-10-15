<?php
	@session_start();
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $permitir_moderacion_foro, $permitir_iconos_como_enlaces;
	
	if (!session_is_registered('login_usuario')) {
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		echo "	alert ('Acceso denegado. Inicie sesión para acceder a esta sección.');";
		echo "	document.location='index.php';\n";
		echo "</script>\n";
		exit;
	}
	
	//Si se ha realizado submit grabamos los datos del nuevo tema en la base de datos
	if (@$HTTP_POST_VARS) {
		//Recogemos los datos del formulario	
		$tema = @$HTTP_POST_VARS['tema'];
		$descripcion = @$HTTP_POST_VARS['descripcion'];
		
		//Creamos la sentencia sql y la ejecutamos para dar de alta el nuevo mensaje
		$sql = "INSERT INTO temas_privados (titulo_tema, descripcion) VALUES ('$tema', '$descripcion')";
		consulta ($sql);
		
		//Volvemos al índice de temas
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		if ($permitir_moderacion_foro)
			echo "	alert ('Su tema será publicado en breve cuando sea aceptado por el administrador');";
		echo "	document.location='index.php';\n";
		echo "</script>\n";
		exit;
	}
	$javascript = "
<script language=\"JavaScript\" src=\"includes/funciones.js\" type=\"text/javascript\"></script>
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function comprobarFormulario(form) {
	//Comprueba si se ha introducido asunto
	if (form.tema.value==\"\") {
		alert (\"No puedes dejar el tema vacío\");
		form.tema.focus();
		return (false);
	}
		
	//Comprueba si se ha introducido la descripción
	if (form.descripcion.value==\"\") {
		alert (\"No puedes dejar la descripción del tema vacío\");
		form.descripcion.focus();
		return (false);
	}
	
	form.submit();
	return (true);
}

//-->
</script>\n";
	
	//Encabezado de página
	imprime_encabezado_de_pagina("Escribir un nuevo tema privado en el foro", $javascript, false);
?>
<!-- Principio de la fila de enlaces -->
    <tr> 
      <td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="encabezado_de_foro">
              ESCRIBIR UN NUEVO TEMA PRIVADO
            </td>
            <td width="150"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
				<?php if ($permitir_iconos_como_enlaces) { ?>
                  	<td align="right"><a href="javascript:history.back();"><img src="img/volver1.gif" alt="Volver atr&aacute;s" width="20" height="20"></a></td>
				<?php } else { ?>
					<td align="right"><a href="javascript:history.back();">Volver atr&aacute;s</a></td>
				<?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con el FORMULARIO DE ENVÍO -->
    <tr>
      <td><form name="mensaje" method="POST" action="<?=$PHP_SELF;?>?id=<?=$id_tema;?>&titulo=<?=$titulo?>&padre=<?=$padre?>&pag=<?=$pag?>" enctype="multipart/form-data">
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><table width="100%" border="0" cellspacing="5" cellpadding="0">
              <tr> 
                <td width="30%" class="campo_oblig"><div align="right">Tema: </div></td>
                <td width="70%"><input name="tema" type="text" class="datos_form" size="50" maxlength="255" onBlur="this.value=this.value.toUpperCase();"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Descripci&oacute;n: 
                    </font></div></td>
                <td><textarea name="descripcion" cols="50" rows="5" class="datos_form" onBlur="this.value=this.value.toUpperCase();"></textarea></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
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
                