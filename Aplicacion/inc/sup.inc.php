<? if(!isset($PHP_SELF)) $PHP_SELF = basename($_SERVER["PHP_SELF"]); ?>
<table width="800" border="0" cellspacing="0" cellpadding="0" class="tabla_de_cabecera">
  <tr> 
    <td width="560"><a href="index.php"><br>&nbsp;<img src="imagenes/encabezado.gif" border="0" alt="P&aacute;gina inicial"></a><br>
      <img src="imagenes/barra.gif" alt="Proyecto fin de carrera de Sixto y Talavera"></td>
	<form name="idiomas" method="post" action="<?=$PHP_SELF;?>">
    <td width="220" valign="bottom" align="right">
        <select name="idioma" onChange="document.idiomas.submit();" class="select_idioma">
<? 
$res = bd("SELECT * FROM idiomas");
while($row = mysql_fetch_array($res)) {
	echo "\t<OPTION ";
	if ($row['id'] == $idioma) {
		echo "SELECTED ";
	}	
	echo "name='".$row['id']."' value='".$row['id']."'>";
	echo strtoupper($row['nombre']);
	echo "\n";
}
mysql_free_result($res);
?>
        </select>
    </td>
	</form>
  </tr>
</table>
