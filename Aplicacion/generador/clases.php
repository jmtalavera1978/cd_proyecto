<?php include("_includes/generador.inc.php");
$cl = 0;
if (isset($_GET["cl"])) {
	$cl = $_GET["cl"];
} else {
	$res = bd("select * from clases order by id");
	$row = reg($res);
	$cl = $row["id"];
	bdfree($res);
	unset($row);
	header("Location: clases.php?cl=$cl");
	exit;
}
$res = bd("select * from clases order by id");
$nf = numreg($res);
postClase($HTTP_POST_VARS);
cabHTML("Clases de objetos","estilos.css"); ?>
<link rel="stylesheet" type="text/css" href="_archivos/estilos.css">
<script languaje="javascript" type="text/javascript">
<!--
function cambiarObjeto(menuob) {
	window.location.href = 'clases.php?cl=' + menuob.value;
}
function realizarSubmit(accion) {
	form = document.form1;
	msg='';
	
	if (accion == 'crear' || accion == 'modificar') { 
		if(document.getElementById('nombre').innerHTML == '(seleccione un nombre)') {
			msg = 'No he indicado el nombre de la clase';
		}
	} else if (accion == 'eliminar') {
		if(!confirm('¿Confirma que desea eliminar el actual?')) {
			msg = 'Borrado cancelado por el usuario';
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
<table bgcolor="#BBBBBB" border="0" align="center" cellspacing="02" cellpadding="01">
<tr>
<td bgcolor="#777777" valign="top" align="center" class="cajarotulo">Clase</td>
<td bgcolor="#777777" valign="top" align="right" class="rotulo" colspan="2">
existen <?=$nf;?> en la base de datos</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign="top">
<SELECT NAME="clase" class='action' onchange="cambiarObjeto(this);">
<? while($row = reg($res)) {
	echo "\t<OPTION ";
	if ($row["id"] == $cl) echo "SELECTED ";	
	echo "name='".$row["id"]."' value=\"".$row["id"]."\">";
	echo strtoupper(pal($row["nombre"]));
	echo "</OPTION>\n";
}
bdfree($res);
?>
</SELECT></td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='eliminar' value='Eliminar' 
	onclick="javascript:realizarSubmit('eliminar');">
</td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='modificar' value='Modificar' 
	onclick="javascript:realizarSubmit('modificar');">
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='diccionario' value='Diccionario' 
	onclick="document.location.href='diccionario.php';">
</td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='reset' class='action' name='limpiar' value='Limpiar' 
	onclick="document.getElementById('nombre').innerHTML = '(seleccione un nombre)';">
</td>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='volver' value='Panel de control' 
	onclick="document.location.href='panelcontrol.php';">
</td>
</tr><tr>
<td bgcolor="#777777" align="center" valign='middle'>
	<input type='button' class='action' name='crear' value='Crear objeto' 
	onclick="javascript:realizarSubmit('crear');">
<td bgcolor="#777777" align="center" valign="middle" colspan="2" class="caja" 
onclick="alert('Debe selecionar una palabra de abajo');">
<div id="nombre">(seleccione un nombre)</div></td>
<? $res = bd("select distinct id from palabras");
while($row=reg($res)){ ?>
</tr><tr>
<td bgcolor="#777777" align="right"><input type="radio" name="nombre" value="<?=$row["id"];?>" 
onclick="document.getElementById('nombre').innerHTML=this.value;"></td>
<td bgcolor="#777777" class="rotulo" colspan="2"><?=$row["id"];?></td>
<? } ?>
</tr>
</table>
</form>
</div>
</body>
</hml>
