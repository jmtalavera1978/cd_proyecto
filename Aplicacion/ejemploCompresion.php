<?php
	/*INCLUSION DE LA LIBRERIA DE COMPRESION*/
	include "inc/pclzip.lib.php";

	
	/******************************
	 COMPRESION  DEL  FORO  PUBLICO
	 ******************************/
	$nombre_fich = "descargas/foroPublico.zip";


	//Creamos el fichero comprimido mediante la clase dada
	 $zipfile = new PclZIP($nombre_fich);
	
	echo "Creando el fichero...";

	$ficheros = array("foroPublico/buscar.php","foroPublico/consultaImagen.php"
			,"foroPublico/escribir.php","foroPublico/foro.php"
			,"foroPublico/index.php","foroPublico/mensaje.php","foroPublico/tema.php");
	
	$zipfile->create($ficheros,'','foroPublico');
	
	echo "<br><br>A&ntilde;adiendo archivos...";
	
	$carpeta_admin = array("foroPublico/admin/index.php");
	$zipfile->add($carpeta_admin,'','foroPublico');

	$carpeta_img = array("foroPublico/img/arrriba.gif","foroPublico/img/barra-.gif","foroPublico/img/barraI.gif"
			,"foroPublico/img/barraL.gif","foroPublico/img/barraT.gif","foroPublico/img/blank.gif"
			,"foroPublico/img/busca.gif","foroPublico/img/buscar.gif","foroPublico/img/carpeta.gif"
			,"foroPublico/img/email.gif","foroPublico/img/estrella.gif","foroPublico/img/folder.gif"
			,"foroPublico/img/nuevo.gif","foroPublico/img/responder.gif","foroPublico/img/volver1.gif"
			,"foroPublico/img/volver2.gif","foroPublico/img/web.gif","foroPublico/img/admin.gif");
	$zipfile->add($carpeta_img,'','foroPublico');
	

	$carpeta_includes = array("foroPublico/includes/funciones.inc.php","foroPublico/includes/funciones.js","foroPublico/includes/usuariosConectados.class.inc.php");
	$zipfile->add($carpeta_includes,'','foroPublico');
	
	echo "<br><br>Fichero <a href=\"$nombre_fich\">$nombre_fich</a> creado con &eacute;xito";



	/******************************
	 COMPRESION  DEL  FORO  PRIVADO
	 ******************************/
	$nombre_fich = "descargas/foroPrivado.zip";


	//Creamos el fichero comprimido mediante la clase dada
	$zipfile = new PclZIP($nombre_fich);
	
	echo "<br><br>Creando el fichero...";

	$ficheros = array("foroPrivado/buscar.php","foroPrivado/consultaImagen.php","foroPrivado/recuerdaClave.php"
			,"foroPrivado/escribir.php","foroPrivado/foro.php","foroPrivado/logout.php"
			,"foroPrivado/index.php","foroPrivado/mensaje.php","foroPrivado/tema.php"
			,"foroPrivado/registrar.php","foroPrivado/usuarios.php");
	
	$zipfile->create($ficheros,'','foroPrivado');
	
	echo "<br><br>A&ntilde;adiendo archivos...";
	
	$carpeta_admin = array("foroPrivado/admin/index.php");
	$zipfile->add($carpeta_admin,'','foroPrivado');

	$carpeta_img = array("foroPrivado/img/arrriba.gif","foroPrivado/img/barra-.gif","foroPrivado/img/barraI.gif"
			,"foroPrivado/img/barraL.gif","foroPrivado/img/barraT.gif","foroPrivado/img/blank.gif"
			,"foroPrivado/img/busca.gif","foroPrivado/img/buscar.gif","foroPrivado/img/carpeta.gif"
			,"foroPrivado/img/email.gif","foroPrivado/img/estrella.gif","foroPrivado/img/folder.gif"
			,"foroPrivado/img/nuevo.gif","foroPrivado/img/responder.gif","foroPrivado/img/volver1.gif"
			,"foroPrivado/img/volver2.gif","foroPrivado/img/web.gif","foroPrivado/img/admin.gif"
			,"foroPrivado/img/registrar.gif","foroPrivado/img/logout.gif");
	$zipfile->add($carpeta_img,'','foroPrivado');

	$carpeta_includes = array("foroPrivado/includes/funciones.inc.php","foroPrivado/includes/funciones.js"
			,"foroPrivado/includes/usuariosConectados.class.inc.php","foroPrivado/includes/mod_claves.inc.php");
	$zipfile->add($carpeta_includes,'','foroPrivado');
	
	echo "<br><br>Fichero <a href=\"$nombre_fich\">$nombre_fich</a> creado con &eacute;xito";



	/********************************
	 COMPRESION  DEL  FORO  PROTEGIDO
	 ********************************/
	$nombre_fich = "descargas/foroProtegido.zip";


	//Creamos el fichero comprimido mediante la clase dada
	$zipfile = new PclZIP($nombre_fich);
	
	echo "<br><br>Creando el fichero...";

	$ficheros = array("foroProtegido/buscar.php","foroProtegido/consultaImagen.php","foroProtegido/recuerdaClave.php"
			,"foroProtegido/escribir.php","foroProtegido/foro.php","foroProtegido/logout.php"
			,"foroProtegido/index.php","foroProtegido/mensaje.php","foroProtegido/tema.php"
			,"foroProtegido/registrar.php","foroProtegido/usuarios.php");
	
	$zipfile->create($ficheros,'','foroProtegido');
	
	echo "<br><br>A&ntilde;adiendo archivos...";
	
	$carpeta_admin = array("foroProtegido/admin/index.php");
	$zipfile->add($carpeta_admin,'','foroProtegido');

	$carpeta_img = array("foroProtegido/img/arrriba.gif","foroProtegido/img/barra-.gif","foroProtegido/img/barraI.gif"
			,"foroProtegido/img/barraL.gif","foroProtegido/img/barraT.gif","foroProtegido/img/blank.gif"
			,"foroProtegido/img/busca.gif","foroProtegido/img/buscar.gif","foroProtegido/img/carpeta.gif"
			,"foroProtegido/img/email.gif","foroProtegido/img/estrella.gif","foroProtegido/img/folder.gif"
			,"foroProtegido/img/nuevo.gif","foroProtegido/img/responder.gif","foroProtegido/img/volver1.gif"
			,"foroProtegido/img/volver2.gif","foroProtegido/img/web.gif","foroProtegido/img/admin.gif"
			,"foroProtegido/img/registrar.gif","foroProtegido/img/logout.gif");
	$zipfile->add($carpeta_img,'','foroProtegido');

	$carpeta_includes = array("foroProtegido/includes/funciones.inc.php","foroProtegido/includes/funciones.js"
			,"foroProtegido/includes/usuariosConectados.class.inc.php","foroProtegido/includes/mod_claves.inc.php");
	$zipfile->add($carpeta_includes,'','foroProtegido');
	
	echo "<br><br>Fichero <a href=\"$nombre_fich\">$nombre_fich</a> creado con &eacute;xito";



	/*******************************
	 COMPRESION  DEL  FORO  MULTIPLE
	 *******************************/
	$nombre_fich = "descargas/foroMultiple.zip";


	//Creamos el fichero comprimido mediante la clase dada
	$zipfile = new PclZIP($nombre_fich);
	
	echo "<br><br>Creando el fichero...";

	$ficheros = array("foroMultiple/buscar.php","foroMultiple/consultaImagen.php","foroMultiple/recuerdaClave.php"
			,"foroMultiple/escribir.php","foroMultiple/escribir_privado.php","foroMultiple/escribir_protegido.php"
			,"foroMultiple/foro.php","foroMultiple/foro_privado.php","foroMultiple/foro_protegido.php","foroMultiple/logout.php"
			,"foroMultiple/index.php","foroMultiple/mensaje.php","foroMultiple/mensaje_privado.php","foroMultiple/mensaje_protegido.php"
			,"foroMultiple/tema.php","foroMultiple/tema_privado.php","foroMultiple/tema_protegido.php"
			,"foroMultiple/registrar.php","foroMultiple/usuarios.php");
	
	$zipfile->create($ficheros,'','foroMultiple');
	
	echo "<br><br>A&ntilde;adiendo archivos...";
	
	$carpeta_admin = array("foroMultiple/admin/index.php");
	$zipfile->add($carpeta_admin,'','foroMultiple');

	$carpeta_img = array("foroMultiple/img/arrriba.gif","foroMultiple/img/barra-.gif","foroMultiple/img/barraI.gif"
			,"foroMultiple/img/barraL.gif","foroMultiple/img/barraT.gif","foroMultiple/img/blank.gif"
			,"foroMultiple/img/busca.gif","foroMultiple/img/buscar.gif","foroMultiple/img/carpeta.gif"
			,"foroMultiple/img/email.gif","foroMultiple/img/estrella.gif","foroMultiple/img/folder.gif"
			,"foroMultiple/img/nuevo.gif","foroMultiple/img/responder.gif","foroMultiple/img/volver1.gif"
			,"foroMultiple/img/volver2.gif","foroMultiple/img/web.gif","foroMultiple/img/admin.gif"
			,"foroMultiple/img/registrar.gif","foroMultiple/img/logout.gif");
	$zipfile->add($carpeta_img,'','foroMultiple');

	$carpeta_includes = array("foroMultiple/includes/funciones.inc.php","foroMultiple/includes/funciones.js"
			,"foroMultiple/includes/usuariosConectados.class.inc.php","foroMultiple/includes/mod_claves.inc.php");
	$zipfile->add($carpeta_includes,'','foroMultiple');
	
	echo "<br><br>Fichero <a href=\"$nombre_fich\">$nombre_fich</a> creado con &eacute;xito";
?>