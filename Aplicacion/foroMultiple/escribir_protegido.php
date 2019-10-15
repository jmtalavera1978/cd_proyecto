<?php
	@session_start();
	
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $autor_mensaje, $email_mensaje, $web_mensaje, $imagen_mensaje, $permitir_buscador_foro;
	
	if (!session_is_registered('login_usuario')) {
		echo "<link rel=\"stylesheet\" href=\"estiloForo.css\" type=\"text/css\"><body></body>\n";
		echo "<script language=\"javascript\">\n";
		echo "	alert ('Es necesario estar registrado para escribir en el foro.');";
		echo "	history.back();\n";
		echo "</script>\n";
		exit;
	}
		
	$id_tema = @$HTTP_GET_VARS['id'];
	$titulo = @$HTTP_GET_VARS['titulo'];
	$padre = @$HTTP_GET_VARS['padre'];
	$asunto = @$HTTP_GET_VARS['asunto'];
	$pag = @$HTTP_GET_VARS['pag'];
	if (!$pag) $pag=1;
	
	//Si es una respuesta a un mensaje le añadimos el encabezado de respuesta al asunto
	if ($asunto)
		$asunto = "RE: ".$asunto;
	
	//Si se ha realizado submit grabamos los datos del nuevo mensaje en la base de datos
	if (@$HTTP_POST_VARS) {
		//Recogemos los datos del formulario	
		$asunto = @$HTTP_POST_VARS['asunto'];
		$id_usuario = @$_SESSION['login_usuario'];
		$descripcion = @$HTTP_POST_VARS['descripcion'];
		
		//Datos de la imagen
		if ($imagen_mensaje) {
			$imagen_type=$HTTP_POST_FILES['imagen']['type'];
			$imagen_name=$HTTP_POST_FILES['imagen']['name'];
			$tmp_data=$HTTP_POST_FILES['imagen']['tmp_name'];
			$filesize=$HTTP_POST_FILES['imagen']['size'];
			$imagen_data = @addslashes(fread(fopen($tmp_data, "rb"), $filesize));
		}
		
		//Datos auxiliares
		$fecha = date("Y-m-d H:i:s"); //Obtiene la fecha actual
		$autor_host = gethostbyaddr("$REMOTE_ADDR"); //Obtiene la IP del autor del mensaje
		
		//Creamos la sentencia sql y la ejecutamos para dar de alta el nuevo mensaje
		$sql = "INSERT INTO mensajes_protegidos (id_tema, id_padre, asunto, usuario, ";
		if ($imagen_mensaje) $sql .= "imagen_data, imagen_name, imagen_type, ";
		$sql .= "autor_host, descripcion, fecha_publicacion) VALUES ('$id_tema', '$padre', '$asunto', '$id_usuario', ";
		if ($imagen_mensaje) $sql .= "'$imagen_data', '$imagen_name', '$imagen_type', ";
		$sql .= "'$autor_host', '$descripcion', '$fecha')";
		consulta ($sql);

		//Ahora creamos la sentencia para actualizar al padre, en su caso
		if ($padre!='0') {
			$sql = "UPDATE mensajes_protegidos SET num_respuestas=num_respuestas+1, fecha_ultima_respuesta='$fecha' WHERE id_mensaje='$padre' AND id_tema='$id_tema'";
			consulta ($sql);
		}
		
		header ("Location: foro_protegido.php?id=$id_tema&titulo=$titulo&pag=$pag");
		exit;
	}
	$javascript = "
<script language=\"JavaScript\" src=\"includes/funciones.js\" type=\"text/javascript\"></script>
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function comprobarFormulario(form) {
	//Comprueba si se ha introducido asunto
	if (form.asunto.value==\"\") {
		alert (\"No puedes dejar el asunto vacío\");
		form.asunto.focus();
		return (false);
	}

	//Comprueba si se ha introducido la descripción
	if (form.descripcion.value==\"\") {
		alert (\"No puedes dejar la descripción del mensaje vacía\");
		form.descripcion.focus();
		return (false);
	}
	";
	
if ($imagen_mensaje) {
	$javascript .= "
	//Comprueba si la imagen introducida es una jpg o una gif
	if (form.imagen.value!=\"\") {
		var dir = new String(form.imagen.value);
	    var long = dir.length;
		
		dir = dir.substr(long-3,3);
	    dir = dir.toLowerCase();
		
		if ((dir!='gif' && dir!='jpg')){
			alert(\"La imagen seleccionada no es jpg o gif.\");
			form.imagen.focus();
			return (false);
		}
	}
	";
}
$javascript .= "
	form.submit();
	return (true);
}
//-->
</script>
";
	
	//Encabezado de página
	imprime_encabezado_de_pagina("Escribir un mensaje en el foro $titulo", $javascript, false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 50;
	else
		$tam_enlaces = 150;
?>
<!-- Principio de la fila de enlaces -->
    <tr> 
      <td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="encabezado_de_foro">
              <?=$titulo?>
            </td>
            <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
				<?php if ($permitir_iconos_como_enlaces) { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="javascript:history.back();"><img src="img/volver1.gif" alt="Volver atr&aacute;s" width="20" height="20"></a></td>
					  <!-- foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?> -->
					  <?php if ($permitir_buscador_foro) { ?>
					  <td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&tipo=protegido'><img src="img/buscar.gif" alt="Buscar" width="20" height="20"></a></td>
					  <?php } ?>
				 <?php } else { ?>
					  <td align="<?php if ($permitir_buscador_foro) echo "center"; else echo "right"; ?>"><a href="javascript:history.back();">Volver atr&aacute;s</a></td>
					  <!-- foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?> -->
					  <?php if ($permitir_buscador_foro) { ?>
					  <td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&tipo=protegido'>Buscar</a></td>
					  <?php } ?>
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
                <td width="30%" class="campo_oblig"><div align="right">Asunto: 
                  </div></td>
                <td width="70%"><input name="asunto" type="text" class="datos_form" size="50" maxlength="255" value="<?=$asunto?>" onBlur="this.value=this.value.toUpperCase();"></td>
              </tr>
              <tr> 
                <td><div align="right"><font class="campo_oblig">Descripci&oacute;n: 
                    </font></div></td>
                <td><textarea name="descripcion" cols="50" rows="5" class="datos_form" onBlur="this.value=this.value.toUpperCase();"></textarea>
					<?php if ($permitir_codigo_html) echo "<br><font class=\"valor\">(Se puede escribir c&oacute;digo html si se desea)</font>"; ?>
					</td>
              </tr>
              <tr> 
                <td><div align="right"></div></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <?php if ($imagen_mensaje) {?>
                <td><div align="right"><font class="campo">Imagen relacionada: 
                    </font></div></td>
                <td><input name="imagen" type="file" class="datos_form" size="50" accept="image/jpeg,image/gif"></td>
              </tr>
              <tr> 
                <td colspan="2">&nbsp;</td>
              </tr>
              <?php }?>
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
                