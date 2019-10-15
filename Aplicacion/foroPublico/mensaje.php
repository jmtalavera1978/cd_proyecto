<?php
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $autor_mensaje ,$email_mensaje, $web_mensaje, $imagen_mensaje, $permitir_codigo_html, $permitir_valoraciones_foro;
	
	$id_tema = @$HTTP_GET_VARS['id'];
	$id_mensaje = @$HTTP_GET_VARS['id_mensaje'];
	$titulo = @$HTTP_GET_VARS['titulo'];
	$pag = @$HTTP_GET_VARS['pag'];
	$filtro = @$HTTP_GET_VARS['filtro'];
	$orden = @$HTTP_GET_VARS['orden'];
	
	if ($id_mensaje) {
		if ($permitir_valoraciones_foro) {
			//Comprobamos si se está realizando una votación, en cuyo caso actualizamos la media añadiendo el voto
			if (@$HTTP_POST_VARS['voto']) {
				$num_v = @$HTTP_POST_VARS['valoraciones'];
				$val_t = @$HTTP_POST_VARS['valoracion'];
				$voto = @$HTTP_POST_VARS['voto'];
				$valoracion_total = (($val_t * $num_v) + $voto) / ($num_v + 1);
				$sql = "UPDATE mensajes SET num_valoraciones='$num_v'+1, valoracion_total='$valoracion_total' 
						WHERE id_mensaje='$id_mensaje' AND id_tema='$id_tema'";
				consulta($sql);
			} else {
				//En caso contrario, actualizamos las lecturas de este mensaje
				consulta("UPDATE mensajes SET lecturas=lecturas+1 WHERE id_mensaje='$id_mensaje' AND id_tema='$id_tema'");
			}
		} else {
			//En caso contrario, actualizamos las lecturas de este mensaje
			consulta("UPDATE mensajes SET lecturas=lecturas+1 WHERE id_mensaje='$id_mensaje' AND id_tema='$id_tema'");
		}
		
		//Consultamos los datos del mensaje
		$sql = "SELECT id_mensaje, id_padre, num_respuestas, asunto,";
		if ($autor_mensaje) $sql .= " autor,";
		if ($email_mensaje) $sql .= " autor_email,";
		if ($web_mensaje) $sql .= " web,";
		if ($imagen_mensaje) $sql .= " imagen_name,";
		if ($permitir_valoraciones_foro) $sql .= " valoracion_total, num_valoraciones,";
		$sql .= "descripcion, fecha_publicacion, lecturas FROM mensajes WHERE id_mensaje='$id_mensaje' AND id_tema='$id_tema'";
		$res = consulta ($sql);
	}
	
	//Comprobamos si tiene padre
	if ($reg = @mysql_fetch_array($res)) {
		$id_padre = $reg ['id_padre'];
		$asunto = $reg['asunto'];
	}
	
	if ($permitir_iconos_como_enlaces) {
		if ($id_padre!="0")
			$tam_enlaces = "75";
		else
			$tam_enlaces = "50";
	} else {
		if ($id_padre!="0")
			$tam_enlaces = "250";
		else
			$tam_enlaces = "170";
	}
		
	imprime_encabezado_de_pagina("Mensajes del foro $titulo","", false);
?>
<!-- Principio de la fila de enlaces -->
<tr>
      <td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="encabezado_de_foro"><?=$titulo;?></td>
            
          <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
			  <?php if ($permitir_iconos_como_enlaces) { ?>
					<?php if ($id_padre!="0") {
							echo "                  <td><a href=\"javascript:history.back();\"><img src=\"img/volver1.gif\" alt=\"Volver atr&aacute;s\" width=\"20\" height=\"20\"></a></td>\n"; //mensaje.php?id=$id_tema&id_mensaje=$id_padre&titulo=$titulo
						  } 
					?>
					<td><div align="center"><a href="foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>"><img src="img/volver2.gif" alt="Volver al foro" width="20" height="20"></a></div></td>
					<td align="right"><a href='escribir.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&padre=<?=$id_mensaje?>&asunto=<?=$asunto?>&pag=<?=$pag?>'><img src="img/responder.gif" alt="Responder" width="20" height="20"></a></td>
				<?php } else { ?>
										<?php if ($id_padre!="0") {
							echo "                  <td><a href=\"javascript:history.back();\">Volver atr&aacute;s</a></td>\n"; //mensaje.php?id=$id_tema&id_mensaje=$id_padre&titulo=$titulo
						  } 
					?>
					<td><div align="center"><a href="foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtro?>&orden=<?=$orden?>">Volver 
						al foro</a></div></td>
					<td align="right"><a href='escribir.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&padre=<?=$id_mensaje?>&asunto=<?=$asunto?>&pag=<?=$pag?>'>Responder</a></td>
				<?php } ?>
              </tr>
            </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila de DATOS DEL MENSAJE -->
    <tr class="fila_de_tabla">
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabla_de_temas">
          <tr> 
            <td height="18"> 
              <?php
			  		if ($reg) {
						if ($autor_mensaje) $autor = htmlentities($reg['autor']);
						if ($email_mensaje) $email = htmlentities($reg['autor_email']);
						if ($web_mensaje) $web = htmlentities($reg['web']);
						if ($permitir_codigo_html)
							$descripcion = nl2br($reg['descripcion']);
						else
							$descripcion = nl2br(htmlentities($reg['descripcion']));
						$fecha_publicacion = $reg['fecha_publicacion'];
						$lecturas = $reg['lecturas'];
						if ($permitir_valoraciones_foro) {
							$valoracion_total = $reg['valoracion_total'];
							//construimos el grafico de la valoración
							$valoracion_img = "";
							for ($i=0;$i<(int)$valoracion_total;$i++)
								$valoracion_img.="<img src=\"img/estrella.gif\">";
							$valoraciones = $reg ['num_valoraciones'];
						}
						if ($imagen_mensaje) $imagen_name = $reg ['imagen_name'];
			  ?>
              
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="encabezado_de_temas"> 
                  <?=$asunto;?>
                </td>
                <td class="encabezado_de_temas" align="right"> 
                  <?=formatear_fecha($fecha_publicacion)?>
                </td>
              </tr>
              <tr> 
              <td colspan="2"><font class="valor"><?=$descripcion?></font><br><br>
			  </td>
              </tr>
			<?php if (($autor_mensaje && $autor) || ($permitir_iconos_como_enlaces && (($email_mensaje && $email) || ($web_mensaje && $web)))) { ?>
              <tr> 
                <td colspan="2"><font class="campo">Autor:</font> <font class="valor"> 
                  <?=$autor?>
				  <?php if ($permitir_iconos_como_enlaces && ($email_mensaje && $email)) echo "<a href=\"mailto:$email\" target=\"_blank\"><img src=\"img/email.gif\" alt=\"Enviar correo\" align=\"absmiddle\"></a>"; ?>
				  <?php if ($permitir_iconos_como_enlaces && ($web_mensaje && $web)) echo "<a href=\"$web\" target=\"_blank\"><img src=\"img/web.gif\" alt=\"Web personal\" align=\"absmiddle\"></a>"; ?>
                  </font> </td>
              </tr>
              <?php } if ($email_mensaje && $email && !$permitir_iconos_como_enlaces) { ?>
              <tr> 
                <td colspan="2"><font class="campo">E-mail: </font><font class="valor"> 
                  <a href="mailto:<?=$email?>"> 
                  <?=$email?>
                  </a> </font></td>
              </tr>
              <?php } if ($web_mensaje && $web && !$permitir_iconos_como_enlaces) { ?>
              <tr> 
                <td colspan="2"><font class="campo">Web: </font><font class="valor"> 
                  <a href="<?=$web?>" target="_blank"> 
                  <?=$web?>
                  </a> </font></td>
              </tr>
              <?php } ?>
              <tr> 
                <td colspan="2"><font class="campo">Lecturas: </font><font class="valor"> 
                  <?=$lecturas?>
                  </font></td>
              </tr>
			                <?php if ($imagen_mensaje && $imagen_name) { //Si hay una imagen relacionada la presentamos ?>
              <tr> 
                <td colspan="2"><font class="campo">Imagen relacionada: </font><font class="valor"><?=$imagen_name?></font>&nbsp;&nbsp;&nbsp;
				<a href="consultaImagen.php?id_mensaje=<?=$id_mensaje?>&ver=si"> [ver] </a>
				<a href="consultaImagen.php?id_mensaje=<?=$id_mensaje?>&ver=no"> [Descargar] </a>
                </td>
              </tr>
              <?php } //fin de la presentación de la imagen ?>
			  <?php if ($permitir_valoraciones_foro) { ?>
              <tr> 
                <td><font class="campo">Valoraci&oacute;n: </font><font class="valor"> 
                  <?php if ($valoracion_total) echo $valoracion_img." ($valoraciones votos)"; else echo "Este mensaje no ha sido valorado"; ?>
                  </font></td>
                <td width="200">
				<?php if (!@$HTTP_POST_VARS['voto']) { ?>
				<form name="valorar" action="mensaje.php?id=<?=$id_tema?>&id_mensaje=<?=$id_mensaje?>&titulo=<?=$titulo?>&pag=<?=$pag?>" enctype="multipart/form-data" method="post">
                    Valora este mensaje<br>
					<input type="hidden" name="valoracion" value="<?=$valoracion_total?>">
					<input type="hidden" name="valoraciones" value="<?=$valoraciones?>">
					<select name="voto">
                      <option value="1">1 (Nada interesante)</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10" selected>10 (Muy interesante)</option>
                    </select>
                    <input type="button" name="votar" value="Votar" onClick="javascript:document.valorar.submit();" class="boton">
                </form>
				<?php } ?>
				</td>
              </tr>
			  <?php } ?>
            </table> 
			  <?php
					}
					@mysql_free_result($res);
			   ?>
            </td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de DATOS DEL MENSAJE -->
<?php
	//Consulta de las respuestas de este mensaje
	$sql = "SELECT id_mensaje, asunto, ";
	if ($autor_mensaje) $sql .= "autor, ";
	$sql .= "fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta FROM mensajes WHERE id_padre='$id_mensaje' AND id_tema='$id_tema' ORDER BY fecha_publicacion DESC";

	//Creamos la tabla de respuestas del mensaje
	listar_mensajes($sql, $id_tema, $titulo, $pag, $filtro, $orden, false);

	//Pie de página
	imprime_pie_de_pagina();
?>
                