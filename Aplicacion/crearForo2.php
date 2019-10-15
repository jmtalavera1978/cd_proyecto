<?php
$objeto = (isset($_GET["objeto"]))? $_GET["objeto"] : 0;
$res = bd("select * from objetos where id=$objeto");
if (!$reg = reg($res)) {
	echo "Objeto no válido";
	exit;
}
$nombreobjeto = $reg["nombre"];
// Carga de ficheros
$ficheros = array();
$res = bd("select * from contenidos where objeto=$objeto order by fichero");
while($reg = reg($res)) $ficheros [] = $reg["fichero"];
bdfree($res);

// Carga de datos
$datos = array();
reset($ficheros);
while(list($k,$fichero) = each($ficheros)) {
$res = bd("select * from asociaciones where fichero=$fichero order by fichero");
while($reg = reg($res)) $datos [$fichero][] = $reg["dato"];
bdfree($res);
}

$xdatos = array();
// Función de creación de objetos
generarObjeto($HTTP_POST_VARS);
?><html>
<head>
<title>Proyecto generador de chats y foros</title>
<LINK href="estilos/principal.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
function visibilidad(id) {
	if (document.getElementById(id).style.visibility=='hidden')
		document.getElementById(id).style.visibility = 'visible';
	else
		document.getElementById(id).style.visibility = 'hidden';
}
//-->
</script>
</head>
<body>
<form name="form1" method="post"><br>
<input type="hidden" name="objeto" value="<?=$objeto;?>">
  <center>
    <table width="550" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td colspan="3"><h2><?=pal("asistente_para");?>: Paso 2 de 3 (<?=strtolower(pal($nombreobjeto));?>)</h2></td>
      </tr>
      <tr> 
        <td width="15"><img src="archivos/arizq.gif" width="15" height="15"></td>
        <td class="borde_de_tabla" width="535"></td>
        <td width="15"><img src="archivos/arder.gif" width="15" height="15"></td>
      </tr>
      <tr> 
        <td class="borde_de_tabla" width="15"></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="5" class="tabla_de_formulario">
<? 	$concolor = false;
	reset($datos);
while(list($fi,$datosfichero) = each($datos)) {
	reset($datosfichero);
	while(list($k,$dato) = each($datosfichero)) {
		if (!in_array($dato,$xdatos)) {
		$xdatos[] = $dato;
		$res = bd("select * from datos where id=$dato");
		if($reg = reg($res)){
			$opciones = array();
			$tipo = $reg["tipo"];
			$resul = bd("select * from tiposdatos where id=$tipo");
			$tipo = ($row = reg($resul))? $row["nombre"] : 0;
			$resul = bd("select * from opcionesdatos where dato=$dato"); ?>
            <tr class="tabla_de_menu"> 
              <td><div align="left"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="81%"><div align="right"><strong><?=pal($reg["nombre"]);?>: 
                          </strong></div></td>
                      <td width="19%"><div align="left"><strong><?
			switch($tipo) {
				case "text":
					  $ropciones = reg($resul);
					  $opcion = $ropciones["opcion"];
					  $resultado = bd("select * from opciones where id=$opcion");
					  $opcion = reg($resultado);
					  $tipoopcion = $opcion["tipo"];
					  $resultado = bd("select * from tiposopciones where id=$tipoopcion");
					  $tipoopcion = reg($resultado);
					  $tipoopcion = $tipoopcion["nombre"];
					  switch($tipoopcion) {
					  	case "text":
							echo "<input type=\"text\" name=\"".$opcion["id"].
								"\" value=\"".$opcion["valor"]."\">";
							break;
						case "color":
							$concolor = true;
							echo "<input type=\"text\" name=\"".$opcion["id"].
								"\" value=\"".$opcion["valor"]."\">";
							break;
					  }
                     break;
				case "checkbox":
					  $ropciones = reg($resul);
					  $opcion = $ropciones["opcion"];
					  $resultado = bd("select * from opciones where id=$opcion");
					  $opcion = reg($resultado);
					  $tipoopcion = $opcion["tipo"];
					  $resultado = bd("select * from tiposopciones where id=$tipoopcion");
					  $tipoopcion = reg($resultado);
					  $tipoopcion = $tipoopcion["nombre"];
					  switch($tipoopcion){
					  	case "checkbox":
							echo "<input type=\"checkbox\" name=\"".$opcion["id"].
								"\" value=\"".$opcion["valor"]."\"";
							echo (($opcion["valor"] == "1")? " CHECKED": "");
							echo ">";
							break;
					  }
					break;
				case "radio":
					  while ($ropciones = reg($resul)) {
						  $opcion = $ropciones["opcion"];
						  $resultado = bd("select * from opciones where id=$opcion");
						  $opcion = reg($resultado);
						  $tipoopcion = $opcion["tipo"];
						  $resultado = bd("select * from tiposopciones where id=$tipoopcion");
						  $tipoopcion = reg($resultado);
						  $tipoopcion = $tipoopcion["nombre"];
	  					  switch($tipoopcion){
						  	case "radio":
								echo "<input type=\"radio\" name=\"".$reg["nombre"].
									"\" value=\"".$opcion["valor"]."\"";
								echo (($opcion["nombre"] == $reg["defecto"] or $opcion["valor"] == $reg["defecto"])? " CHECKED": "");
								echo ">".$opcion["valor"]."&nbsp;\n";
							break;
					  }

					  }
					break;
			}?></strong></div></td>
                    </tr>
                  </table>
                  <strong> </strong></div></td></tr>
				<?
		}
		}
	}
}
?>
         </table></td>
        <td class="borde_de_tabla" width="15"></td>
      </tr>
      <tr> 
        <td width="15"><img src="archivos/abizq.gif" width="15" height="15"></td>
        <td class="borde_de_tabla" width="535"></td>
        <td width="15"><img src="archivos/abder.gif" width="15" height="15"></td>
      </tr>
    </table><br>
    <input type="submit" name="siguiente" value="Siguiente" class="boton">&nbsp;&nbsp;&nbsp;
    <input type="button" name="atras" value="Atr&aacute;s" class="boton" onClick="javascript:history.back();">
  </center>
  </form>
</body>
</html>