<?php include("_includes/generador.inc.php");
$idioma = 0;
	if (isset($_GET["i"])) {
		$idioma = $_GET["i"];
	} else {
		$res = bd("select * from idiomas");
		$row = reg($res);
		$idioma = $row["id"];
		bdfree($res);
		unset($row);
		header("Location: diccionario.php?i=$idioma");
		exit;
	}
	$res = bd("select * from idiomas order by id");
	$nf = numreg($res);
	postDiccionario($HTTP_POST_VARS);
	cabHTML("Diccionario del proyecto"); ?>	
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
function visibilidad(id) {
	if (document.getElementById(id).style.visibility=='hidden')
		document.getElementById(id).style.visibility = 'visible';
	else
		document.getElementById(id).style.visibility = 'hidden';
}
function activarTodos(id0,id) {
	for(i=0;i<<?=$nf;?>;i++) {
		document.getElementById(id+i).checked = document.getElementById(id0).checked;
	}
}
function realizarSubmit(form,accion) {
	
	msg='';

	if (accion == 'crear') { 
		if(form.nuevoidioma.value == '') {
			msg = 'No he indicado el nombre del nuevo idioma';
			form.nuevoidioma.focus();
		}
	} else if (accion == 'eliminar') {
		if(!confirm('¿Desea eliminar el idioma actual?')) {
			msg = 'Borrado cancelado por el usuario';
		}
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
	}
}
function cambiarIdioma(menuid) {
	//window.location.href = 'diccionario.php?i=' + menuid.options[menuid.selectedIndex].text;
	window.location.href = 'diccionario.php?i=' + menuid.value;
}
function visi(estado){
	document.getElementById('b1').style.visibility = estado;
	document.getElementById('b2').style.visibility = estado;
}
-->
</script>
</head>
<body class="mysql">
<div align="left">
<form method='post' name='formidioma'>
<input type='hidden' name='idioma' value='<?=$idioma?>'>
<input type='hidden' name='accion' value=''>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td align="center" valign="top">

<table border="0" align="center" cellspacing="0" cellpadding="0">
<tr>

<td valign="top">

<!-- Idiomas -->
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo">IDIOMAS</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="2">
existen <?=$nf;?> en la base de datos</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="middle" class="rotulo">
actual</td>
<td bgcolor="#777777" align="center" valign="top">
<SELECT NAME="idiomas" class='action' onchange="cambiarIdioma(this);">
<? 
while($row=reg($res)) {
	echo "\t<OPTION ";
	if ($row["id"] == $idioma) {
		echo "SELECTED ";
	}	
	echo "name='".$row["id"]."' value=\"".$row["id"]."\">";
	echo strtoupper($row["nombre"]);
	echo "\n";
}
bdfree($res);
?>
</SELECT></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='volver' value='Eliminar' 
	onclick="javascript:realizarSubmit(document.formidioma,'eliminar');">
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='volver' value='Crear idioma' 
	onclick="javascript:realizarSubmit(document.formidioma,'crear');">
<td bgcolor="#777777" align="center" valign="middle" colspan="2">
<input type="text" name="nuevoidioma" size="20" class="caja"></td>
</td>
</tr>
</table>
<!-- Fin de idiomas -->

</td>
</tr><tr>

<td height="3"></td>

</tr><tr>
<td align="center" valign="top">

<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01" width="100%">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo">PALABRAS</td>
<td bgcolor="#777777" valign="top" align="center" class="rotulo" colspan="2">
<? $res0 = bd("select DISTINCT id from palabras");
	$np = reg($res0);
	echo $np;?> en la base de datos</td>
</tr><tr>

<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='insertar' value='Insertar' 
	onclick="realizarSubmit(document.formidioma,'insertar');"></td>


<td bgcolor="#777777" align="center" colspan="2" rowspan="3">

<table border="0" cellspacing="02" cellpadding="03">
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">t&eacute;rmino</td>
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">traducci&oacute;n</td>
</tr><tr>
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">
<input type="text" name="termino" size="15" class="caja"></td>
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">
<input type="text" name="traduccion" size="15" class="caja"></td>
</tr></table>

</td>

</tr><tr>

<td bgcolor="#777777" id="b1" align="center" valign='middle' class="oculto">
	<input type='button' class='action' name='modificar' value='Modificar' 
	onclick="realizarSubmit(document.formidioma,'modificar');"></td>

</tr><tr>

<td bgcolor="#777777" id="b2" align="center" valign='middle' class="oculto">
	<input type='button' class='action' name='borrar' value='Borrar' 
	onclick="realizarSubmit(document.formidioma,'borrar');"></td>

</tr></table>

</td>

</tr><tr>

<td height="3"></td>

</tr><tr>
<td>

<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01" width="100%">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo">GENERAL</td>
</tr><tr>

<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='volver' value='Volver' 
	onclick="history.back();"></td>
<td bgcolor="#777777" align="center" valign="middle">
	<input type='button' class='action' name='recargar' value='Recargar' 
	onclick="document.location.href='diccionario.php'"></td>
<td bgcolor="#777777" align="center" valign="middle">
	<input type='reset' class='action' name='limpiar' value='Limpiar campos' 
	onclick="javascript:visi('hidden');"></td>

</tr><tr>

<td bgcolor="#777777" align="center" valign="middle" colspan="2">
	<input type='button' class='action' name='datos' value='Datos & opciones' 
	onclick="document.location.href='datos.php'"></td>
<td bgcolor="#777777" align="center" valign="middle">
	<input type='button' class='action' name='panel' value='Panel de control' 
	onclick="document.location.href='panelcontrol.php'"></td>

</tr></table>

</td>

</tr></table>

</td>
<!-- Centro de la página -->
<td align="center" valign="top">

<table border="0" align="center" cellspacing="02" cellpadding="01">
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">t&eacute;rmino</td>
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle">traducci&oacute;n</td>
<td class="cajarotulo" bgcolor="#777777" align="center" valign="middle"><b>SELECCIONAR</b></td>
<? while($row = reg($res0)){ 
		$id = $row["id"]; ?>
</tr><tr>
	<td class="cajarotulo" bgcolor="#777777" align="center" 
	valign="middle"><?=$id;?></td>
	<td class="rotulo" bgcolor="#777777" align="center" 
	valign="middle"><? 
			$res = bd("select * from palabras where id='$id' and idioma='$idioma'");
			$trad = "&nbsp;";
			if(numreg($res) > 0) {
				$pal = reg($res);
				if ($pal["traduccion"] != null) $trad = $pal["traduccion"];
				bdfree($res);
			} 
			echo $trad;?></td>
	<td bgcolor="#777777" align="center" valign="middle" class="rotulo">
	<input type="radio" name="palabra" id='' value="<?=$id;?>" onclick="javascript:visi('visible');"></td>
<? } ?>
</tr></table>

</td>
</tr></table>
</form>
</div> 
</body>
</html>
