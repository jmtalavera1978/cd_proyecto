<?php
//session_start();

 /************************************************************
   M�DULO GENERAL PARA CREACI�N DE CABECERAS Y PIES DE P�GINA 
   ----------------------------------------------------------
  Este m�dulo contiene funciones paar la creaci�n de cabeceras
 y pies de p�ginas. Las funciones se describen con mas detalle
 a continuaci�n.
 *************************************************************/

//Funciones:
 /************************************************************
  Esta funci�n recibe 7 par�metros para crear el encabezado de una p�gina en php:
  $titulo:			El t�tulo de la p�gina
  $dirPagEstilo:	La direcci�n de la hoja de estilos a utilizar, en su caso
  $javascript:		El c�digo javascript de la p�gina, si posee
  $claseTablaEncabezado:	La clase css de la tabla del encabezado, si posee
  $enlaceAtras:	El enlace o c�digo de la esquina superior izquierda de la p�gina
  $tituloCentral:	El t�tulo central del encabezado de la p�gina
  $enlaceAlante:	El enlace o c�digo de la esquina superior derecha de la p�gina
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
  Esta funci�n cierra el c�digo de la p�gina web
  **********************************/
function creaPieDePagina () {
	echo "</body>\n\n";
	echo "</html>\n";
}