<?php 
	include "includes/funciones.inc.php";
	include "includes/config.inc.php";
	
	//ESTAS DOS VARIABLES DEBEN SER SOLICITADAS MEDIANTES FORMULARIOS CON LOS DATOS DE ENTRADA DE MYSQL
	$login_administrador = "josema";
	$password_administrador = "josema";
	
	global $basedatos, $permitir_varios_foros, $nombre_del_foro, $permitir_valoraciones_foro, $mostrar_usuarios_conectados_foro,
			$autor_mensaje, $email_mensaje, $web_mensaje, $imagen_mensaje;

	imprime_encabezado_de_pagina('Instalaci&oacute;n del foro', '');
	
	echo "<tr><td class=\"encabezado_de_foro\">INSTALACI&Oacute;N DEL FORO '".strtoupper($nombre_del_foro)."'...</td></tr>";
	
	//CREACIÓN DE LA 'BASE DE DATOS'
	$sql = "CREATE DATABASE $basedatos";
	if (consulta($sql))
		echo "<tr><td class=\"tabla_de_temas\">La base de datos ".strtoupper($basedatos)." ha sido creada con &eacute;xito.</td></tr>";
	else {
		echo "<tr><td class=\"tabla_de_temas\">ERROR al crear la base de datos ".strtoupper($basedatos).".</td></tr>";
		echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
	}
	
	//CREACIÓN DE LA TABLA DEL 'ADMINISTRADOR'
	$sql = "CREATE TABLE administrador (
  				id_administrador tinyint(3) unsigned NOT NULL auto_increment,
  				login varchar(25) NOT NULL default '',
  				password varchar(32) NOT NULL default '',
  				PRIMARY KEY  (id_administrador),
  				UNIQUE KEY login (login))";
	if (consulta($sql))
		echo "<tr><td class=\"tabla_de_temas\">La tabla ADMINISTRADOR se ha creado con &eacute;xito.</td></tr>";
	else {
		echo "<tr><td class=\"tabla_de_temas\">ERROR al crear la tabla ADMINISTRADOR.</td></tr>";
		echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
	}
	
	//CREACIÓN DEL USUARIO ADMINISTRADOR POR DEFECTO
	$sql = "INSERT INTO administrador(login,password) VALUES ('$login_administrador', '".md5($password_administrador)."')";
	if (consulta($sql))
		echo "<tr><td class=\"tabla_de_temas\">Se ha dado de alta al usuario administrador con &eacute;xito.</td></tr>";
	else {
		echo "<tr><td class=\"tabla_de_temas\">ERROR al dar de alta al usuario administrador.</td></tr>";
		echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
	}
	
	if ($mostrar_usuarios_conectados_foro) {
		//CREACIÓN DE LA TABLA DE 'USUARIOSCONECTADOS'
		$sql = "CREATE TABLE usuariosconectados (
			  		tiempoConexion int(15) NOT NULL default '0',
					ip varchar(40) NOT NULL default '',
			  		archivo varchar(100) NOT NULL default '',
			  		KEY tiempoConexion (tiempoConexion),
			  		KEY ip (ip),
			  		KEY archivo (archivo))";
		if (consulta($sql))
			echo "<tr><td class=\"tabla_de_temas\">La tabla USUARIOSCONECTADOS se ha creado con &eacute;xito.</td></tr>";
		else {
			echo "<tr><td class=\"tabla_de_temas\">ERROR al crear la tabla USUARIOSCONECTADOS.</td></tr>";
			echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
		}
	}
	
	if ($permitir_varios_foros) {
		//CREACION DE LA TABLA DE 'TEMAS' DEL FORO
		$sql = "CREATE TABLE temas (
					  id_tema int(7) unsigned NOT NULL auto_increment,
					  titulo_tema varchar(255) NOT NULL default '',
					  descripcion varchar(255) NOT NULL default '',
					  PRIMARY KEY  (id_tema),
					  UNIQUE KEY id_tema (id_tema,titulo_tema),
					  KEY id_tema_2 (id_tema))";
		if (consulta($sql))
			echo "<tr><td class=\"tabla_de_temas\">La tabla TEMAS se ha creado con &eacute;xito.</td></tr>";
		else {
			echo "<tr><td class=\"tabla_de_temas\">ERROR al crear la tabla TEMAS.</td></tr>";
			echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
		}
	}
		
	//CREACIÓN DE LA TABLA DE 'MENSAJES'
	$sql = "CREATE TABLE mensajes (
				  id_mensaje int(11) unsigned NOT NULL auto_increment,
				  id_tema int(7) unsigned NOT NULL default '0',
				  id_padre int(11) unsigned NOT NULL default '0',
				  asunto varchar(255) NOT NULL default '',";
	if ($autor_mensaje) {
		$sql .= " autor varchar(255) NOT NULL default '',";
	}
	if ($email_mensaje) {
		$sql .= " autor_email varchar(255) NOT NULL default '',";
	}
	$sql .= "	  autor_host varchar(255) NOT NULL default '',
				  descripcion text NOT NULL,
				  fecha_publicacion datetime NOT NULL default '0000-00-00 00:00:00',
				  num_respuestas int(7) unsigned NOT NULL default '0',
				  fecha_ultima_respuesta datetime NOT NULL default '0000-00-00 00:00:00',";
	if ($web_mensaje){
		$sql .= " web varchar(255) NOT NULL default '',";
	}
	$sql .= "	  lecturas int(7) unsigned NOT NULL default '0',";
	if ($imagen_mensaje) {
		$sql .= " imagen_data longblob NOT NULL,
				  imagen_name varchar(255) NOT NULL default '',
				  imagen_type varchar(50) NOT NULL default '',";
	}
	if ($permitir_valoraciones_foro) {
		$sql .= " valoracion_total double NOT NULL default '0',
				  num_valoraciones int(4) unsigned NOT NULL default '0',";
	}
	$sql .= "	  PRIMARY KEY  (id_mensaje),
				  UNIQUE KEY id_mensaje (id_mensaje),
				  KEY id_mensaje_2 (id_mensaje))";
	if (consulta($sql))
		echo "<tr><td class=\"tabla_de_temas\">La tabla MENSAJES se ha creado con &eacute;xito.</td></tr>";
	else {
		echo "<tr><td class=\"tabla_de_temas\">ERROR al crear la tabla MENSAJES.</td></tr>";
		echo "<tr><td class=\"fila_de_tabla\">".nl2br(htmlentities($sql))."</td></tr>";
	}
		
	imprime_pie_de_pagina();
	require("estiloForo.css.php");
?>