<?php include("_includes/generador.inc.php");
postAsoc($HTTP_POST_VARS);


// Preprocesamiento
$asociaciones = array();
$res = bd("select * from asociaciones");
while($row=reg($res)) {
	$asociaciones[$row["fichero"]][$row["dato"]] = true;
}
bdfree($res);

cabHTML("Asociacones de ficheros y datos","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
var selec = false;
function realizarSubmit(accion) {
	form = document.form1;
	msg='';
	
	if(!selec) {
		msg = 'Debe seleccionar un fichero';
	} else if(accion=='eliminar') { 
		if(!confirm('¿Confirma que desea eliminar las asociaciones?')) {
			msg = 'Cancelado por el usuario';
		}
	}
	if (msg == '') {
		form.accion.value = accion;
		form.submit();
	} else {
		alert(msg);
	}//*/
}
-->
</script>
</head>
<body class="mysql">
<div align="left">
<form method='post' name='form1'>
<input type='hidden' name="accion" value="">
<table border="0" align="center" cellspacing="2" cellpadding="0">
<tr><td align="center" colspan="2">
<!-- Tabla de acciones generales -->
<table bgcolor="#BBBBBB" border="0" cellspacing="2" cellpadding="1">
<tr>
<td bgcolor="#777777" valign="top" align="left" class="cajarotulo" 
colspan="2">&nbsp;Asociaciones</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='panel' value='Panel de control' 
	onclick="javascipt:document.location.href='panelcontrol.php';"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='tipos' value='Ficheros' 
	onclick="javascipt:document.location.href='ficheros.php';"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='reset' class='action' name='limpiar' value='Limpiar formulario' 
	onclick="javascript:selec=false;"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='diccionario' value='Datos' 
	onclick="javascipt:document.location.href='datos.php';"></td>
</tr>
</table>
<!-- Fin de tabla de opciones generales-->
</td></tr>
<tr><td valign="top"><!-- Tabla de ficheros -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
Ficheros</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="2">
<? $res = bd("select id, nombre, camino, lenguaje from ficheros order by nombre , camino");
$nf = numreg($res);
echo $nf;?> ficheros&nbsp;</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
	<input type='button' class='action' name='guardar' value='Guardar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
	<input type='button' class='action' name='eliminar' value='Eliminar asociaciones' 
	onclick="javascript:realizarSubmit(this.name);"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Nombre&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Camino&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Lenguaje&nbsp;</td>
</tr>
<? while($row = reg($res)) { 
	?><tr>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="radio" name="fichero" value="<?=$row["id"];?>" 
	onclick="javascript:selec=true;<? $resul = bd("select id from datos");
	while($reg = reg($resul)) {
		echo "\ndocument.getElementById('dato_' + ".$reg["id"].").checked = ";
		if(isset($asociaciones[$row["id"]][$reg["id"]])) echo "true;";
		else echo "false;";
	}
	?>"></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["nombre"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["camino"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<? $l = $row["lenguaje"];
	$resul = bd("select nombre from lenguajes where id=$l");
	$reg = reg($resul);
	echo $reg["nombre"];?></td>
	</tr><? 
}
bdfree($res); ?>
</table>
<!-- Fin de Tabla de ficheros --></td>

<td valign="top"><!-- Tabla de datos -->
<? $res = bd("select * from datos order by id");
$nf = numreg($res); ?>
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
Datos</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="3">
<?=$nf;?> datos&nbsp;</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&lt;&lt;&lt;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Nombre&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Tipo&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Por defecto&nbsp;</td>
</tr>
<? while($row = reg($res)) { 
	?><tr>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="checkbox" name="<?=$row["id"];?>" id="dato_<?=$row["id"];?>" value="__dato-sel__"></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["nombre"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<? $tipo = $row["tipo"];
		$resul = bd("select * from tiposdatos where id = '$tipo'");
		$tipo = ($reg = reg($resul))? $reg["nombre"] : "&nbsp;";
		echo $tipo; ?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["defecto"];?></td>
	</tr><? 
}
bdfree($res); ?>
</table>
<!-- Fin de Tabla de opciones --></td>

</tr>
</table>
</form>
</div>
</body>
</hml>
