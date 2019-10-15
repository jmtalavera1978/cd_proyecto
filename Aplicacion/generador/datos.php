<?php include("_includes/generador.inc.php");
postDatos($HTTP_POST_VARS);

// Preprocesamiento
$opcionesdatos = array();
$res = bd("select * from opcionesdatos");
while($row=reg($res)) {
	$opcionesdatos[$row["dato"]][$row["opcion"]] = true;
}
bdfree($res);
cabHTML("Gestión de datos y opciones","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
var dato = false;
function realizarSubmit(accion) {
	form = document.form1;
	msg='';
	
	if (accion == 'creardato') {
		if(form.nombredato.value == '') {
			msg = 'Debe indicar un nombre para el dato';
			form.nombredato.focus();
		}
	} else if(accion=='modificardato') { 
		if(form.nombredato.value == '') {
			msg = 'Debe indicar un nombre dato';
			form.nombredato.focus();
		} else if (!dato) {
			msg = 'Debe seleccionar un dato';
		}
	} else if (accion == 'eliminardato') {
		if(!dato) {
			msg = 'Debe seleccionar un tipo de dato';
		}
	} 	if (accion == 'crearopcion') {
		if(form.nombreopcion.value == '') {
			msg = 'Debe indicar un nombre para la opción';
			form.nombreopcion.focus();
		}
	} else if(accion=='modificaropcion') { 
		if(form.nombreopcion.value == '') {
			msg = 'Debe indicar un nombre para la opción';
			form.nombreopcion.focus();
		}
	} else if (accion == 'eliminaropcion') {
		if(form.nombreopcion.value == '') {
			msg = 'Debe indicar un nombre para la opción';
			form.nombreopcion.focus();
		} else if(!confirm('¿Confirma que desea eliminar la opción?')) {
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
colspan="2">&nbsp;Datos & opciones</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='panel' value='Panel de control' 
	onclick="javascipt:document.location.href='panelcontrol.php';"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='tipos' value='Tipos de datos y opciones' 
	onclick="javascipt:document.location.href='tipos.php';"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='Ficheros' value='Ficheros' 
	onclick="javascipt:document.location.href='ficheros.php';"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='asoc' value='Asociaciones' 
	onclick="javascipt:document.location.href='asociaciones.php';"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='reset' class='action' name='limpiar' value='Limpiar formulario' 
	onclick="javascript:dato=false;"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='diccionario' value='Diccionario' 
	onclick="javascipt:document.location.href='diccionario.php';"></td>
</tr>
</table>
<!-- Fin de tabla de opciones generales-->
</td></tr>
<tr><td valign="top"><!-- Tabla de datos -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
DATOS</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo">
<? $res = bd("select * from datos order by nombre, id");
$nf = numreg($res);
echo $nf;?> datos&nbsp;</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='creardato' value='Crear nuevo' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificardato' value='Modificar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminardato' value='Eliminar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Nombre:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<input type="text" name="nombredato" size="20" class="caja" value=""></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Tipo:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<SELECT NAME="tipodato" class='action'>
<? $resul = bd("select * from tiposdatos order by id");
while ($row = reg($resul))
	echo "<OPTION VALUE=\"".$row["id"]."\">".strtoupper($row["nombre"])."</OPTION>\n";
bdfree($resul);
?></SELECT></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Por defecto:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<input type="text" name="defecto" size="20" class="caja" value=""></td>
<? while($row=reg($res)){ ?>
</tr><tr>
<td bgcolor="#777777" align="right"><input type="radio" name="__dato-sel__" 
value="<?=$row["id"];?>" onclick="javascript:dato=true;
document.form1.nombredato.value='<?=$row["nombre"];?>';
document.form1.tipodato.value = '<?=$row["tipo"];?>';
document.form1.defecto.value = '<?=$row["defecto"];?>';
<? $resul = bd("select id from opciones ORDER BY id");
while($reg = reg($resul)) {
	$chk = (isset($opcionesdatos[$row["id"]][$reg["id"]]))? true : false;
	echo "document.getElementById('opcion_".$reg["id"]."').checked = ";
	if ($chk) echo "true";
	else echo "false";
	echo ";\n";
}
?>"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["nombre"];?></td>
<? } 
bdfree($res);
?></tr>
<tr><td height="1"></td></tr>
<tr><td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="3">
PALABRAS</td></tr>
<? $res = bd("select distinct id from palabras");
while($row=reg($res)){ ?>
<tr>
<td bgcolor="#777777" align="right"><input type="radio" name="nombre" value="<?=$row["id"];?>" 
onclick="document.form1.nombredato.value=this.value;"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["id"];?></td></tr>
<? } ?>
</table>
<!-- Fin de Tabla de datos --></td>

<td valign="top"><!-- Tabla de opciones -->
<? $res = bd("select * from opciones order by nombre, id");
$nf = numreg($res); ?>
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
OPCIONES</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="4">
<?=$nf;?> opciones&nbsp;</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='crearopcion' value='Crear nueva' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="right" valign="middle" class="rotulo">Nombre:&nbsp;</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="4">
<input type="text" name="nombreopcion" size="20" class="caja"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificaropcion' value='Modificar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="right" valign="middle" class="rotulo">Tipo:&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" colspan="4">
<SELECT NAME="tipoopcion" class='action'>
<? $resul = bd("select * from tiposopciones order by id");
while ($row = reg($resul))
	echo "<OPTION VALUE=\"".$row["id"]."\">".strtoupper($row["nombre"])."</OPTION>\n";
bdfree($resul);
?></SELECT></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminaropcion' value='Eliminar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="right" valign="middle" class="rotulo">Tamaño:&nbsp;</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="4">
<input type="text" name="tamopcion" size="20" class="caja"></td>
</tr><tr>
<td bgcolor="#777777" align="right" valign="middle" class="rotulo" colspan="2">
Valor predeterminado:&nbsp;</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="4">
<input type="text" name="valoropcion" size="20" class="caja"></td>
</tr>
<tr><td height="1"></td></tr>
<tr>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&lt;&lt;&lt;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Nombre&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Tipo&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Tamaño&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Valor&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">^</td>
</tr>
<? while($row = reg($res)) { 
	?><tr>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="checkbox" name="<?=$row["id"];?>" id="opcion_<?=$row["id"];?>" value="__opcion-sel__"></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["nombre"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<? $tipo = $row["tipo"];
		$resul = bd("select * from tiposopciones where id = '$tipo'");
		$tipo = ($reg = reg($resul))? $reg["nombre"] : "&nbsp;";
		echo $tipo; ?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["maxtam"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
	<?=$row["valor"];?></td>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="radio" name="opcion" value="<?=$row["id"];?>" 
	onclick="document.form1.nombreopcion.value='<?=$row["nombre"];?>';
		document.form1.tipoopcion.value = <?=$row["tipo"];?>;// - 1;
		document.form1.tamopcion.value = '<?=$row["maxtam"];?>';
		document.form1.valoropcion.value = '<?=$row["valor"];?>';"></td>
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
