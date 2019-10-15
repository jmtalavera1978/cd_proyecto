<?php
//session_start();

 /************************************************************
   MÓDULO GENERAL PARA CREACIÓN DE CABECERAS Y PIES DE PÁGINA 
   ----------------------------------------------------------
  Este módulo contiene funciones paar la creación de cabeceras
 y pies de páginas. Las funciones se describen con mas detalle
 a continuación.
 *************************************************************/

//Funciones:
 /************************************************************
  Esta función recibe 7 parámetros para crear el encabezado de una página en php:
  $titulo:			El título de la página
  $dirPagEstilo:	La dirección de la hoja de estilos a utilizar, en su caso
  $javascript:		El código javascript de la página, si posee
  $claseTablaEncabezado:	La clase css de la tabla del encabezado, si posee
  $enlaceAtras:	El enlace o código de la esquina superior izquierda de la página
  $tituloCentral:	El título central del encabezado de la página
  $enlaceAlante:	El enlace o código de la esquina superior derecha de la página
  *************************************************************/
function creaEncabezadoPagina ($titulo, $dirPagEstilo, $javascript, $claseTablaEncabezado, $enlaceAtras, $tituloCentral, $enlaceAlante) {
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>$titulo</title>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
	echo "<link href=\"$dirPagEstilo\" rel=\"stylesheet\" type=\"text/css\">\n";
	
	if ($javascript!="" && $javascript!=null) {
		echo "<script language=\"JavaScript\">\n";
		echo "<!--\n";
		echo $javascript;
		echo "\n//-->\n";
		echo "</script>\n";
	}
	echo "</head>\n";
	echo "\n<body>\n";
?>
<center>
<table bgcolor="#000066" border="0" cellpadding="5" cellspacing="2" width="100%" align="center">
<tr><td align="center" bgcolor="#E5FFFF" valign="middle">
<?php
	if ($enlaceAtras!="" || $tituloCentral!="" || $enlaceAlante!="") {
		echo "\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"$claseTablaEncabezado\">\n";
		echo "  <tr>\n";
		if ($enlaceAtras=="") $enlaceAtras="&nbsp;";
		echo "    <td width=\"10%\" align=\"left\" valign=\"top\">$enlaceAtras</td>\n";
		if ($tituloCentral=="") $tituloCentral="&nbsp;";
		echo "    <td width=\"80%\" align=\"center\">$tituloCentral</td>\n";
		if ($enlaceAlante=="") $enlaceAlante="&nbsp;";
		echo "    <td width=\"10%\" align=\"right\" valign=\"top\">$enlaceAlante</td>\n";
		echo "  </tr>\n";
		echo "</table>\n\n";
	}
?>
</td></tr></table>
<?php
}

 /**********************************
  Esta función cierra el código de la página web
  **********************************/
function creaPieDePagina () {
	echo "</body>\n\n";
	echo "</html>\n";
}