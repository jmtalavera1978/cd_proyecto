<?php
	@session_start();
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	global $permitir_varios_foros, $titulo_foro_unico, $nombre_del_foro, $permitir_iconos_como_enlaces, $email_mensaje;
	
	if ($permitir_varios_foros) {
		//Encabezado de página
		imprime_encabezado_de_pagina("Lista de foros","", false);
		
			if (@$HTTP_POST_VARS){
				$login_usuario = @$HTTP_POST_VARS['login'];
				$passwd = md5(@$HTTP_POST_VARS['password']);
				
				$res = consulta("SELECT login FROM usuarios WHERE login='$login_usuario' AND password='$passwd' AND tiene_permiso='SI'");
				if (@mysql_num_rows($res)==1) {
					@session_destroy();
					@session_start();
					@session_register('login_usuario');
					consulta("UPDATE usuarios SET fecha_ultimo_acceso='".date("Y-m-d H:i:s")."' WHERE login='$login_usuario'");
				} else
					echo "<tr><td class=\"fila_de_tabla\">ACCESO DENEGADO.</td></tr>\n";
				@mysql_free_result($res);
			}
			
			if ($permitir_iconos_como_enlaces)
				$tam_enlaces = 55;
			else
				$tam_enlaces = 175;
?>
	<!-- Principio de fila de enlaces -->
	<tr><td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				
        <td class="encabezado_de_foro"> 
          <?=htmlentities($nombre_del_foro)?>
        </td>
		<?php if ($permitir_iconos_como_enlaces) { ?>
			<td class="encabezado_de_foro" align="right"><a href="admin/"><img src="img/admin.gif" alt="Administrador"></a> 
		<?php } else { ?>
			<td class="encabezado_de_foro" align="right"><a href="admin/">Administrador</a>
		<?php } ?>
        </td>
			</tr>
		</table>
	</td></tr>
	<!-- fin de fila de enlaces -->
<?php 		if (!@session_is_registered('login_usuario')) { ?>
	<!-- Principio de fila de acceso usuarios -->
	<tr><td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
		<form name="acceso" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
        <td align="left" valign="middle" class="campo">
            Usuario: <input type="text" name="login" size="10" class="form_login" maxlength="12">
			Password: <input type="password" name="password" size="10" class="form_login">
            <input type="submit" name="Submit" value="abrir sesi&oacute;n" class="boton">
            <a href="recuerdaClave.php">&iquest;olvid&oacute; su contrase&ntilde;a?</a></td>
		</form>
		<?php if ($permitir_iconos_como_enlaces) { ?>
				<td align="right"><a href="registrar.php"><img src="img/registrar.gif" alt="Alta en el servicio" width="20" height="20"></a></td>
		<?php } else { ?>		
        		<td align="right" class="campo"><a href="registrar.php">Registrar</a></td>
		<?php } ?>
			</tr>
		</table>
	</td></tr>
	<!-- Fin de fila de acceso usuarios -->
<?php
		} else {
?>
	<!-- Principio de fila de usuario registrado -->
	<tr><td class="encabezado_de_foro">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
          
        <td align="left" valign="middle" class="campo">Bienvenido usuario <b><a href="usuarios.php"><?=$login_usuario?></a></b></td>
				
        <td align="right" class="campo" width="<?=$tam_enlaces?>">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
		<?php if ($permitir_iconos_como_enlaces) { ?>
				<td align="right"><a href="tema.php"><img src="img/nuevo.gif" alt="Nuevo tema" width="20" height="20"></a></td>
		<?php } else { ?>
				<td align="right"><a href="tema.php">Nuevo tema</a></td>
		<?php } ?>
		<?php if ($permitir_iconos_como_enlaces) { ?>
    		<td align="right"><a href="logout.php"><img src="img/logout.gif" alt="Cerrar sesi&oacute;n" width="20" height="20"></a></td>
		<?php } else { ?>
			<td align="right"><a href="logout.php">Cerrar sesi&oacute;n</a></td>
		<?php } ?>
  		</tr>
		</table></td></tr>
		</table>
	</td></tr>
	<!-- Fin de fila de usuario registrado -->
<?php
		}
		
		//Crea la fila con la lista de foros
		listar_foros(false);

		//Pie de página
		imprime_pie_de_pagina();
	} else {
		header ("Location: foro.php?id=1&titulo=$titulo_foro_unico&pag=1");
		exit();
	}
?>