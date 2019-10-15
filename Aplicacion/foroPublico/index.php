<?php 
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $permitir_varios_foros, $titulo_foro_unico, $nombre_del_foro, $permitir_iconos_como_enlaces;

	if ($permitir_varios_foros) {
		//Encabezado de página
		imprime_encabezado_de_pagina("Lista de foros","", false);
?>
	<tr><td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="encabezado_de_foro"><?=htmlentities($nombre_del_foro)?></td>
				<?php if ($permitir_iconos_como_enlaces) { ?>
					<td class="encabezado_de_foro" align="center" width="25"><a href="admin/"><img src="img/admin.gif" alt="Administrador" width="20" height="20"></a></td>
					<td align="right" width="25"><a href="tema.php"><img src="img/nuevo.gif" alt="Nuevo tema" width="20" height="20"></a></td>
				<?php } else { ?>
					<td class="encabezado_de_foro" align="center" width="50"><a href="admin/">Administrador</a></td>
					<td align="right" width="80"><a href="tema.php">Nuevo tema</a></td>
				<?php } ?>
			</tr>
		</table>
	</td></tr>
<?php
		//Crea la fila con la lista de foros
		listar_foros(false);

		//Pie de página
		imprime_pie_de_pagina();
	} else {
		header ("Location: foro.php?id=1&titulo=$titulo_foro_unico&pag=1");
		exit();
	}
?>