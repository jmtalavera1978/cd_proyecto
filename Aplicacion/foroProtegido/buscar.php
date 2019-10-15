<?php
	@session_start();
	
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $permitir_filtro_foro;
	
	$id_tema = @$HTTP_GET_VARS['id'];
	$titulo = @$HTTP_GET_VARS['titulo'];
	$busqueda = @$HTTP_POST_VARS['busqueda'];
	$filtro = @$HTTP_POST_VARS['filtro'];
	$pag = @$HTTP_GET_VARS['pag'];
	
	if ($permitir_filtro_foro) {
		$filtrar = @$HTTP_GET_VARS['filtro'];
		$orden = @$HTTP_GET_VARS['orden'];
	}
	
	if (!$pag) $pag = 1;
	
	imprime_encabezado_de_pagina ("Busqueda en el foro $titulo","", false);
	
	if ($permitir_iconos_como_enlaces)
		$tam_enlaces = 50;
	else
		$tam_enlaces = 200;
?>
<!-- Principio de la fila de enlaces -->
    <tr><td class="encabezado_de_foro"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="encabezado_de_foro">
              <?=$titulo?>
            </td>
            <td width="<?=$tam_enlaces?>"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
				<?php if ($permitir_iconos_como_enlaces) { ?>
                  	<td><a href="foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtrar?>&orden=<?=$orden?>"><img src="img/volver1.gif" alt=" Volver al foro" width="20" height="20"></a></td>
                  	<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtrar?>&orden=<?=$orden?>'><img src="img/buscar.gif" alt="Nueva b&uacute;squeda" width="20" height="20"></a></td>
				<?php } else { ?>
					<td><a href="foro.php?id=<?=$id_tema;?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtrar?>&orden=<?=$orden?>">Volver al foro</a></td>
                  	<td align="right"><a href='buscar.php?id=<?=$id_tema?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtrar?>&orden=<?=$orden?>'>Nueva b&uacute;squeda</a></td>
				<?php } ?>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
<!-- Fin de la fila de enlaces -->
<!-- Principio de la fila con la TABLA DE BÚSQUEDA -->
    <tr>
      <td>
          <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tabla_de_temas">
            <tr> 
              <td><form method="POST" action="<?=$PHP_SELF;?>?id=<?=$id_tema;?>&titulo=<?=$titulo?>&pag=<?=$pag?>&filtro=<?=$filtrar?>&orden=<?=$orden?>" enctype="multipart/form-data"><br>
              	<table border="0" cellspacing="0" cellpadding="3" width="400" class="tabla_de_temas" align="center">
                  <tr> 
                    <td height="25"><b> &nbsp;&nbsp;Escriba la cadena a buscar: </b></td>
                  </tr>
                  <tr> 
                    <td height="60" valign="middle" align="center">
                    <input type="text" name="busqueda" size="30" maxlength="50" value="<?=$busqueda;?>">
                    &nbsp; 
                    <input type="submit" value="Buscar en el foro" name="buscar" class="boton"><br>
                    <input type="radio" name="filtro" value="asunto" <?php if (!$filtro || $filtro=='asunto') echo "checked"; ?>>
                    En el asunto
                    <input type="radio" name="filtro" value="descripcion"<?php if ($filtro=='descripcion') echo "checked"; ?>>
                    En la descripci&oacute;n
                    <input type="radio" name="filtro" value="ambos"<?php if ($filtro=='ambos') echo "checked"; ?>>
                    En ambos</td>
                  </tr>
                </table></form></td>
            </tr>
          </table>
        </td>
    </tr>
	<tr><td>
<!-- Fin de la fila con la TABLA DE BÚSQUEDA -->
<?php
	//Realizamos la búsqueda en su caso y presentamos los resultados
	if ($busqueda) {
		//REALIZAMOS LA BÚSQUEDA SÓLO EN MAYÚSCULAS
		$busqueda_may = strtoupper($busqueda);
		
		//FILTRAMOS LA BÚSQUEDA POR ASUNTO O DESCRIPCIÓN, EN SU CASO
		if ($filtro=="asunto")
			$filtrar_por = "asunto LIKE '%$busqueda_may%'";
		else if ($filtro=="descripcion")
			$filtrar_por = "descripcion LIKE '%$busqueda_may%'";
		else
			$filtrar_por = "asunto LIKE '%$busqueda_may%' OR descripcion LIKE '%$busqueda_may%'";
	
		//CREAMOS LA SENTENCIA SQL DE LA BÚSQUEDA Y MOSTRAMOS LOS RESULTADOS
		$sql = "SELECT id_mensaje, asunto, usuario, fecha_publicacion, lecturas, num_respuestas, fecha_ultima_respuesta 
				FROM mensajes WHERE id_tema='$id_tema' 
				AND ($filtrar_por) 
				ORDER BY fecha_publicacion DESC LIMIT 0, 10";
				
		//Listamos los resultados de la búsqueda
		listar_mensajes($sql, $id_tema, $titulo, $pag, $filtro, $orden, false);
	}

	//Pie de página
	imprime_pie_de_pagina();
?>         