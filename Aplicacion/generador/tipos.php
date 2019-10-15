<?php include("_includes/generador.inc.php");
$res = bd("select * from tiposdatos order by id");
$nf = numreg($res);
postTipos($HTTP_POST_VARS);
cabHTML("Panel de control","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
var dato = false;
var opcion = false;
function realizarSubmit(accion) {
	form = document.form1;
	msg='';
	
	if (accion == 'creardato') {
		if(form.nombredato.value == '') {
			msg = 'Debe indicar un nombre para el tipo'
			form.nombredato.focus();
		}
	} else if(accion=='modificardato') { 
		if(form.nombredato.value == '') {
			msg = 'Debe indicar un nombre para el tipo'
			form.nombredato.focus();
		} else if (!dato) {
			msg = 'Debe seleccionar un tipo de dato'
		}
	} else if (accion == 'eliminardato') {
		if(!dato) {
			msg = 'Debe seleccionar un tipo de dato'
		}
	} 	if (accion == 'crearopcion') {
		if(form.nombreopcion.value == '') {
			msg = 'Debe indicar un nombre para el tipo'
			form.nombreopcion.focus();
		}
	} else if(accion=='modificaropcion') { 
		if(form.nombreopcion.value == '') {
			msg = 'Debe indicar un nombre para el tipo'
			form.nombreopcion.focus();
		} else if (!opcion) {
			msg = 'Debe seleccionar un tipo de opcion'
		}
	} else if (accion == 'eliminaropcion') {
		if(!opcion) {
			msg = 'Debe seleccionar un tipo de opcion'
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
colspan="2">&nbsp;TIPOS</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='panel' value='Panel de control' 
	onclick="javascipt:document.location.href='panelcontrol.php';"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='diccionario' value='Diccionario' 
	onclick="javascipt:document.location.href='diccionario.php';"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='reset' class='action' name='limpiar' value='Limpiar formulario' 
	onclick="javascript:dato=false;opcion=false;"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='datos&opciones' value='Datos & Opciones' 
	onclick="javascipt:document.location.href='datos.php';"></td>
</tr>
</table>
<!-- Fin de tabla de opciones generales-->
</td></tr>
<tr><td><!-- Tabla tipos de datos -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
TIPOS DE DATOS</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo">
<?=$nf;?> tipos	</td>
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
<? while($row=reg($res)){ ?>
</tr><tr>
<td bgcolor="#777777" align="right"><input type="radio" name="tipodato" 
value="<?=$row["id"];?>" onclick="javascript:dato=true;"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["nombre"];?></td>
<? } 
bdfree($res);
?></tr>
</table>
<!-- Fin de Tabla tipos de datos --></td>

<td valign="top"><!-- Tabla tipos de opciones -->
<? $res = bd("select * from tiposopciones order by id");
$nf = numreg($res); ?>
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" colspan="2">
TIPOS DE OPCIONES</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo">
<?=$nf;?> tipos</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='crearopcion' value='Crear nuevo' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificaropcion' value='Modificar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminaropcion' value='Eliminar' 
	onclick="javascript:realizarSubmit(this.name);"></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">Nombre:</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
<input type="text" name="nombreopcion" size="20" class="caja"></td>
<? while($row=reg($res)){ ?>
</tr><tr>
<td bgcolor="#777777" align="right"><input type="radio" name="tipoopcion" 
value="<?=$row["id"];?>" onclick="javascript:opcion=true;"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["nombre"];?></td>
<? } 
bdfree($res);
?></tr>
</table>
<!-- Fin de Tabla tipos de opciones --></td>

</tr>
</table>
</form>
</div>
</body>
</hml>
