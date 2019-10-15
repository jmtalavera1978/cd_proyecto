<?php
@session_start();

//Comprobamos el lenguaje
include "generador/_includes/generador.inc.php";
$idioma = selecciona_lenguaje(@$HTTP_POST_VARS['idioma']);
if (!session_is_registered('idioma'))
    session_register('idioma');
?>
<html>
<head>
<title>Foros y Chats: Generador de c&oacute;digo mediante asistentes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="keywords" content="proyecto, foro, chat, generador, codigo, php"> 
<meta name="DC.Title" content="Foros y Chats: Generador de código para creaci&oacute;n de foros y chats"> 
<meta name="DC.Creator" content="Foros y Chats"> 
<meta name="description" content="Aplicación para la generaci&oacute;n, mediante asistentes, de c&oacute;digo para foros y chats">
<meta name="robots" content="all"> 
<meta name="distribution" content="global"> 
<meta name="copyright" content="Foros y Chats"> 
<meta name="resource-type" CONTENT="document"> 
<meta name="Revisit" content="30 days"> 
<meta name="language" content="Spanish"> 
<meta name="doc-type" content="Web page">
<link rel="stylesheet" href="foros_chats.css" type="text/css">
<script language="JavaScript" type="text/javascript">
<?php require "inc/menu.js"; ?>
</script>
</head>
<body>
<center>
<a name="arriba"></a>
<?php 
	require "inc/sup.inc.php"; 
	$activo=@$HTTP_GET_VARS["activo"];
?>
<table width="800" border="0" cellspacing="0" cellpadding="0" bgcolor="#EFF7EF">
  <tr>
    <?php require "inc/lat.inc.php"; ?>
        <td width="800" valign="top">
		<table width="670" border="0" cellspacing="0" cellpadding="0">
		  <tr> 
			<td width="10" class="titulo_vacio">&nbsp;</td>
			<td width="660" valign="top" class="titulo"><?=pal('enlaces',$idioma)?></td>
		  </tr>
		  <tr> 
			<td width="10">&nbsp;</td>
			<td width="660">	<br><u><?=pal('enlacesBiblio',$idioma)?></u><br><br>
						<blockquote>
						<ul>
							<li><a href="http://www.webestilo.com/php/">Manual de PHP de WebEstilo</a></li><br><br>
							<li><a href="http://www.desarrolloweb.com/manuales/12/">Manual de PHP de DesarrolloWeb</a></li><br><br>
							<li><a href="http://www.php.net/manual/es/">Documentación oficial completa de PHP</a></li><br><br>
							<li><a href="http://www.mysql.com/documentation/">Documentación oficial de MySQL</a></li><br><br>
							<li><a href="http://www.mysql.com/products/myodbc/manual_toc.html">Documentación oficial de MySQL Connector/ODBC</a></li><br><br>
							<li><a href="http://es.tldp.org/Manuales-LuCAS/manual_PHP/manual_PHP/">Tutorial de PHP y Mysql</a></li><br><br>
							<li><a href="http://es.sun.com/aprender_sobre/java/">Informaci&oacute;n oficial de Java en Espa&ntilde;ol</a></li><br><br>
							<li><a href="http://www.softdownload.com.ar/cursos/JavaFv.zip">Manual de Java</a></li><br><br>
							<li><a href="http://148.216.5.25/JavaScriptTut/indice.htm">Tutorial de JavaScript</a></li><br><br>							
							<li><a href="http://www.webestilo.com/php/php08a.phtml">Manual de instalaci&oacute;n de Apache con PHP y MySQL de WebEstilo</a></li><br><br>
							<li><a href="http://www.maestrosdelweb.com/editorial/phpmysqlap/">Manual de instalaci&oacute;n de Apache con PHP y MySQL de MaestrosDelWeb</a></li>
						</ul></blockquote>
						<br><u><?=pal('otrosEnlaces',$idioma)?></u><br><br>
						<blockquote>
						<ul>
							<li><a href="http://httpd.apache.org/download.cgi">P&aacute;gina de descarga del servidor Apache</a></li><br><br>
							<li><a href="http://www.mysql.com/downloads/mysql-4.0.html">P&aacute;gina de descarga del servidor Mysql</a></li><br><br>
							<li><a href="http://www.mysql.com/downloads/api-jdbc-stable.html">P&aacute;gina de descarga del conector Mysql-Java</a></li><br><br>
							<li><a href="http://www.php.net/downloads.php">P&aacute;gina de descarga de PHP</a></li><br><br>
							<li><a href="http://java.sun.com/j2se/downloads.html">P&aacute;gina de descarga de la máquina virtual de Java J2SE</a></li><br><br>
							<li><a href="http://www.hotscripts.com">Scripts gratuitos</a></li><br><br>
							<li><a href="http://www.itinformatica.net">Documentaci&oacute;n varia</a></li>
						</ul></blockquote>
			 </td>
		  </tr>
		</table>
      <? require "inc/pie.inc.php"; ?>
    </td>
  </tr>
</table>
</center>
</body>
</html>
