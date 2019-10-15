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
			<td width="660" valign="top" class="titulo"><?=pal('asistenteForos',$idioma)?></td>
		  </tr>
		  <tr> 
			<td width="10">&nbsp;</td>
			<td width="660"><?php require "crearForo2.php"; ?>
			 </td>
		  </tr>
		</table>
      <?php require "inc/pie.inc.php"; ?>
    </td>
  </tr>
</table>
</center>
</body>
</html>
