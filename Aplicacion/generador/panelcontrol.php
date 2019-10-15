<?php include("_includes/generador.inc.php");
$idioma = 
$obj = 0;
if (isset($_GET["ob"])) {
	$obj = $_GET["ob"];
} else {
	$res = bd("select * from objetos order by id");
	$row = reg($res);
	$obj = $row["id"];
	bdfree($res);
	unset($row);
	header("Location: panelcontrol.php?ob=$obj");
	exit;
}
$res = bd("select * from objetos order by id");
$nf = numreg($res);
postObjeto($HTTP_POST_VARS);
cabHTML("Panel de control","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
function cambiarObjeto(menuob) {
	//window.location.href = 'diccionario.php?i=' + menuid.options[menuid.selectedIndex].text;
	window.location.href = 'panelcontrol.php?ob=' + menuob.value;
}
function realizarSubmit(accion) {
	form = document.form1;
	//form.submit();/*
	msg='';
	//alert(form.nuevoobjeto.name);/*
	
	if (accion == 'crearobjeto') { 
		if(document.getElementById('nuevoobjeto').innerHTML == '(seleccione un nombre)') {
			msg = 'No he indicado el nombre del nuevo objeto';
		}
	} else if (accion == 'eliminarobjeto') {
		if(!confirm('¿Confirma que desea eliminar el actual?')) {
			msg = 'Borrado cancelado por el usuario';
		}
	} else if (accion == 'modificarobjeto') {
		
	} else if (accion == 'insertar') {
		if(form.termino.value == '') {
			msg = 'No ha rellenado el campo \"término\"';
			form.termino.focus();
		} else if(form.traduccion.value == '') {
			msg = 'No ha rellenado el campo \"traducción\"';
			form.traduccion.focus();
			
		}
	} else if (accion == 'modificar') {
		if(form.traduccion.value == '') {
			msg = 'No ha rellenado el campo \"traducción\"';
			form.traduccion.focus();
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

<table border="0" align="center" cellspacing="1" cellpadding="0">
<tr>
<td rowspan="2" valign="top"><!-- Tabla Objeto -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo">OBJETO</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="2">
<?=$nf;?> objetos&nbsp;</td>
</tr><tr>
<!--td bgcolor="#777777" align="center" valign="middle" class="rotulo">
actual</td-->
<td bgcolor="#777777" align="center" valign="top">
<SELECT NAME="objeto" class='action' onchange="cambiarObjeto(this);">
<? $claseObj = "";
	while($row=reg($res)) {
	echo "\t<OPTION ";
	if ($row["id"] == $obj) {
		$claseObj = $row["clase"];
		echo "SELECTED ";
	}
	echo "name='".$row["id"]."' value=\"".$row["id"]."\">";
	echo strtoupper(pal($row["nombre"]));
	echo "\n";
}
bdfree($res);
?>
</SELECT></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminar' value='Eliminar' 
	onclick="javascript:realizarSubmit('eliminarobjeto');">
</td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificar' value='Modificar' 
	onclick="javascript:realizarSubmit('modificarobjeto');">
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='diccionario' value='Diccionario' 
	onclick="document.location.href='diccionario.php';">
</td>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
	<input type='reset' class='action' name='limpiar' value='Limpiar formulario' 
	onclick="document.getElementById('nuevoobjeto').innerHTML = '(seleccione un nombre)';">
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modClase' value='Editar clases' 
	onclick="document.location.href='clases.php';">
</td>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">
Clase</td>
<td bgcolor="#777777" align="center" valign="top">
<SELECT NAME="clase" class='action'>
<? $res = bd("select * from clases order by id");

	while($row=reg($res)) {
	echo "\t<OPTION ";
	if ($row["id"] == $claseObj) {
		echo "SELECTED ";
	}
	echo "name='".$row["id"]."' value=\"".$row["id"]."\">";
	echo strtoupper(pal($row["nombre"]));
	echo "</option>\n";
}
bdfree($res);
?>
</SELECT></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='crear' value='Crear objeto >' 
	onclick="javascript:realizarSubmit('crearobjeto');">
<td bgcolor="#777777" align="center" valign="middle" colspan="2" class="caja" 
onclick="alert('Debe selecionar una palabra de abajo');">
<!--input type="text" name="nuevoobjeto" size="20" class="caja"-->
<div id="nuevoobjeto">(seleccione un nombre)</div></td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle' class="rotulo">
Descripción: &nbsp;
<td bgcolor="#777777" align="center" valign="middle" colspan="2" class="caja">
<input type="text" name="descripcionobjeto" size="20" class="caja" value="<?
$resul = bd("select descripcion from objetos where id=$obj");
$reg = reg($resul);
echo $reg["descripcion"];
bdfree($resul);
?>"></td>
<? $res = bd("select distinct id from palabras");
while($row=reg($res)){ ?>
</tr><tr>
<td bgcolor="#777777" align="right"><input type="radio" name="nuevoobjeto" value="<?=$row["id"];?>" 
onclick="document.getElementById('nuevoobjeto').innerHTML=this.value;"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["id"];?></td>
<? } ?>
</tr>
</table>
<!-- Fin de Tabla Objeto --></td>

<td valign="top"><!-- Tabla de ficheros -->

<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr><? 
$res = bd("select id, nombre, camino, lenguaje from ficheros");
$nf = numreg($res);
?>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo" width="25%">Ficheros</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="3" width="75%">
<?=$nf;?> ficheros&nbsp;</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
	<input type='button' class='action' name='ficheros' value='Gestión de ficheros' 
	onclick="document.location.href = 'ficheros.php';">
<td bgcolor="#777777" align="center" valign='middle' colspan="2">
	<input type='button' class='action' name='asociaciones' value='Asociaciones' 
	onclick="document.location.href = 'asociaciones.php';">

</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">
<input type="checkbox" name="todosauna" onclick="<?
$resul = bd("select id from ficheros");
while($reg = reg($resul)) 
echo "document.getElementById('fichero_' + ".$reg["id"].").checked = this.checked;\n";
bdfree($resul);
?>"></td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Nombre&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Camino&nbsp;</td>
<td bgcolor="#777777" align="center" valign="middle" class="cajarotulo">&nbsp;Lenguaje&nbsp;</td>
</tr>
<? while($row = reg($res)) { 
	?><tr>
	<td bgcolor="#777777" align="center" valign='middle'>
	<input type="checkbox" name="<?=$row["id"];?>" id="fichero_<?=$row["id"];?>" value="__fichero-sel__"<?
	$resul = bd("select * from contenidos where objeto=$obj and fichero=".$row["id"]);
	if ($reg = reg($resul)) echo " CHECKED";?>></td>
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

</td><!-- Fin de la tabla de ficheros -->

</tr>
</table>
</form>
</div>
</body>
</hml>
