<?php include_once("generador/_includes/generador.inc.php");
define("PHP_SELF",$_SERVER["PHP_SELF"],1);
/*
@session_start();
$idioma = $_SESSION['idioma'];
if(!session_is_registered('idioma')) session_register('idioma');
else echo "no";
//*/
$clase = (isset($_GET["clase"]))? (int)$_GET["clase"] : 1;
$objeto = (isset($_GET["objeto"]))? (int)$_GET["objeto"] : 0;
cabHTML("Generador de objetos");
?><body>
<div align="center"><form name="form1">
<table border = "0">
<tr><td><?=pal("eleccionclase");?></td>
<td><select name="clase" onchange="document.location.href = '<?=PHP_SELF;?>?clase=' + this.value;">
<? $resul = bd("select * from clases");
while($reg = reg($resul)) {
	echo "<option value =".$reg["id"];
	echo (($reg["id"] == $clase)? " SELECTED" : "");
	echo ">".pal($reg["nombre"])."</option>\n";
}
bdfree($resul);
?></select></td>
</tr><tr>
<? $res = bd("select * from objetos where clase = $clase");
while($row = reg($res)) { ?>
	<tr><td><a href="<?=PHP_SELF;?>?objeto=<?=$row["id"];?>">
	<?=pal($row["nombre"]);?></a>
	&lt;<?=pal($row["descripcion"]);?>&gt;</td></tr>
<? }
bdfree($res);
?>

</tr>
<? 
if ($objeto != 0) {
	$res = bd("select * from objetos where id=$objeto");
	if($row = reg($res)) {
		$ficheros = bd("select * from contenidos where objeto = $objeto");
		while($rfch = reg($ficheros)) {

			$datfich = bd("select id, nombre, camino, lenguaje from ficheros where id=".$rfich["fichero"]);
			$registrofichero = reg($datfich);
			$lenguaje = $registrofichero["lenguaje"];

			$datos = bd("select * from asociaciones where fichero = ".$rfich["fichero"].
			"order by dato");
			$tdat = array();
			while($rdat = reg($datos)) {
				$iddato = $rdat["dato"];
				$r = bd("select * from datos where id = $iddato");
				$t = reg($r);
				$tipo = $t["tipo"];
				$restipo = bd("select * from tiposdatos where id=$tipo");
				$regtipo = reg($restipo);
				$tipodato = $regtipo["nombre"];
				$resopciones = bd("select * from opcionesdatos where dato=$iddato");
				$regopciones = reg($resopciones);

				switch($tipodato) {
					case "text":
						$nombre = pal($t["nombre"]);
						$resopcion = bd("select * from opciones where id=".$regopciones["opcion"]);
						$regopcion = reg($resopcion);
						$name = $regopcion["nombre"];
						$size = (((int)$regopcion["maxtam"]) > 0)? " size=\"".((int)$regopcion["maxtam"])."\"" : "";
						$value = $t["defecto"];
						$html = "";
						$html .= $nombre."&nbsp;\n";
						$html .= "<input type=\"text\" name=\"$name\" value=\"$value\"".$size."><br>\n";
						echo $html;
						break;
					case "estilo":
						
						break;
					case "select":
						break;
					case "radio":
						break;
					case "checkbox":
						break;
					case "check-text":
						break;
				}
			}
			
		}
	}
}
?>
</table>
</form>
</div>