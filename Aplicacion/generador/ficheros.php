<?php include("_includes/generador.inc.php");
$res = bd("select * from datos order by id");
$nf = numreg($res);
postFicheros($HTTP_POST_VARS,$HTTP_POST_FILES);
cabHTML("Gestión de ficheros","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
var selec = false;
function realizarSubmit(accion) {
	form = document.form1;
	msg='';
	
	if (accion == 'crear') {
		if(form.nombrefichero.value == '') {
			msg = 'Debe indicar un nombre';
			form.nombrefichero.focus();
		} else if (form.caminofichero.value == '') {
			msg = 'Debe indicar un camino';
			form.caminofichero.focus();
		}
	} else if (accion == 'modificar') {
		if(!selec) {
			msg = 'Debe seleccionar uno';
		}
	} else if (accion == 'eliminar') {
		if(!selec) {
			msg = 'Debe seleccionar uno';
		} else if(!confirm('¿Confirma que desea eliminar el fichero?')) {
			msg = 'Cancelado por el usuario';
		}
	}
	if (msg == '') {
		form.accion.value = accion;
		form.submit();
	} else {
		alert(msg);
	}
}

function copiarCamino() {
	var cad = document.form1.archivo.value.replace("\\","/");
	while (cad != cad.replace("\\","/")) {
		cad = cad.replace("\\","/");
	}
	document.form1.caminofichero.value = cad;
	document.form1.caminofichero.focus();
}
-->
</script>
</head>
<body class="mysql">
<div align="left">
<form method='post' name='form1' enctype='multipart/form-data'>
<input type='hidden' name="accion" value="">
<table border="0" align="center" cellspacing="2" cellpadding="0">
<tr><td align="center" colspan="2">
<!-- Tabla de acciones generales -->
<table bgcolor="#BBBBBB" border="0" cellspacing="2" cellpadding="1">
<tr>
<td bgcolor="#777777" valign="top" align="left" class="cajarotulo" 
colspan="2">&nbsp;Ficheros</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='panel' value='Panel de control' 
	onclick="javascipt:document.location.href='panelcontrol.php';"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='asociaciones' value='Asociaciones' 
	onclick="javascipt:document.location.href='asociaciones.php';"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='reset' class='action' name='limpiar' value='Limpiar formulario' 
	onclick="javascript:selec=false;"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='datos' value='Datos & opciones' 
	onclick="javascipt:document.location.href='datos.php';"></td>
</tr>
</table>
<!-- Fin de tabla de opciones generales-->
</td></tr>
<tr><td valign="top"><!-- Tabla de edición -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
Edición</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo">&nbsp;
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='crear' value='Crear nuevo' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificar' value='Modificar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminar' value='Eliminar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Nombre:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<input type="text" name="nombrefichero" size="30" class="caja" value=""></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Camino:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<input type="text" name="caminofichero" size="30" class="caja" value=""></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Lenguaje:&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" colspan="3">
<SELECT NAME="lenguaje" class='action'>
<? $resul = bd("select * from lenguajes order by id");
while ($row = reg($resul))
	echo "<OPTION VALUE=\"".$row["id"]."\">".strtoupper($row["nombre"])."</OPTION>\n";
bdfree($resul);
?></SELECT></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo" colspan="3">
Contenido:</td>
</tr><tr>
<td bgcolor="#777777" align="left" valign='middle' colspan="3">
<input type="radio" name="tipocontenido" id="radio1" value="archivo">
<input type="file" name="archivo" size="30" class="caja" value="" 
onclick="document.getElementById('radio1').checked = true;" onChange="javascript:copiarCamino();"></td>
</tr><tr>
<td bgcolor="#777777" align="left" valign='middle' colspan="3">
<input type="radio" name="tipocontenido" id="radio2" value="codigo">
<textarea name="codigo"cols="45" rows="10" class="caja" 
onclick="document.getElementById('radio2').checked = true;"></textarea></td>

</tr>
</table>
<!-- Fin de Tabla de edición --></td>

<td valign="top"><!-- Tabla de ficheros -->
<? $res = bd("select id, nombre, camino, lenguaje from ficheros order by nombre , camino");
$nf = numreg($res); ?>
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
Guardados</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="6">
<?=$nf;?> ficheros&nbsp;</td>
</tr>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo" 
colspan="2">&nbsp;Nombre&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo"
colspan="2">&nbsp;Camino&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo"
colspan="2">&nbsp;Lenguaje&nbsp;</td>
</tr>
<? while($row = reg($res)) { 
	?><tr>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="radio" name="fichero" value="<?=$row["id"];?>" 
	onclick="javascript:document.form1.lenguaje.value='<?=$row["lenguaje"];?>';selec=true;"></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo" colspan="2">
	<?=$row["nombre"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo" colspan="2">
	<?=$row["camino"];?></td>
	<td bgcolor="#777777" align="center" valign='middle' class="rotulo" colspan="2">
	<? $l = $row["lenguaje"];
	$resul = bd("select nombre from lenguajes where id=$l");
	$reg = reg($resul);
	echo $reg["nombre"];?></td>
	</tr><? 
}
bdfree($res); ?>
</table>
<!-- Fin de Tabla de ficheros --></td>

</tr>
</table>
</form>
</div>
</body>
</hml>
