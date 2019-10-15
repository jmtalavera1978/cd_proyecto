<form name="tipoForo">
<input type="hidden" name="objeto_seleccionado" value="">
  <br>
  <center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td colspan="3"><h2>Asistente para foros: Paso 1 de 3</h2></td>
      </tr>
      <tr> 
        <td width="15"><img src="archivos/arizq.gif" width="15" height="15"></td>
        <td class="borde_de_tabla"></td>
        <td width="15"><img src="archivos/arder.gif" width="15" height="15"></td>
      </tr>
      <tr> 
        <td class="borde_de_tabla"></td>
        <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="tabla_de_formulario">
<? $clase = (isset($_GET["clase"]))? (int) $_GET["clase"] : 1;
if ($clase == 0) {
	echo "Clase desconocida.";
	exit;
}
$res = bd("select * from objetos where clase = $clase;");
if(numreg($res) == 0) {
	echo pal("sin_objeto_alguno");
}
while($row = reg($res)) { ?>
            <tr> 
              <td width="78%"><input type="radio" name="tipo" value="<?=$row["id"];?>" class="check" 
			  onclick="document.tipoForo.objeto_seleccionado.value='<?=$row["id"];?>';"> 
                <strong><?=strtoupper(pal($row["nombre"]));?></strong> 
				(<?=htmlentities(pal($row["descripcion"]));?>)</td>
            </tr>	
<? }
bdfree($res);
?>
<!--            <tr> 
              <td width="22%" rowspan="4"><strong>Tipo de foro:</strong><img src="archivos/llave.gif" height="200" width="25" align="absmiddle"></td>
              <td width="78%"><input type="radio" name="tipo" value="publico" class="check" checked> 
                <strong>PUBLICO</strong> (El foro p&uacute;blico es aqu&eacute;l 
                donde todos pueden participar si tener que registrase. Todos pueden 
                leer y enviar mensajes)</td>
            </tr>
            <tr> 
              <td><input type="radio" name="tipo" value="protegido" class="check"> 
                <strong>PROTEGIDO</strong> (El foro protegido es inalterable para 
                usuarios no registrados. Es decir, si usted quiere enviar mensajes, 
                primero debe registrase)</td>
            </tr>
            <tr> 
              <td><input type="radio" name="tipo" value="privado" class="check"> 
                <strong>PRIVADO</strong> (El foro privado es solo accesible para 
                usuarios registrados en uno de los siguientes grupos: admin, moderador, 
                y miembro. Para tener acceso a este tipo de foro, no obstante, 
                el administrador debe aun permitirle la entrada)</td>
            </tr>
            <tr> 
              <td><input type="radio" name="tipo" value="multiple" class="check"> 
                <strong>M&Uacute;LTIPLE</strong> (El foro est&aacute; compuesto 
                por una divisi&oacute;n de los tres anteriores)</td>
            </tr>
-->          </table></td>
        <td class="borde_de_tabla"></td>
      </tr>
      <tr> 
        <td><img src="archivos/abizq.gif" width="15" height="15"></td>
        <td class="borde_de_tabla"></td>
        <td><img src="archivos/abder.gif" width="15" height="15"></td>
      </tr>
    </table>
  </center>
  <p align="center"> 
    <input type="button" name="siguiente" value="Siguiente" class="boton" 
	onclick="javascript:if(document.tipoForo.objeto_seleccionado.value != '') { document.location.href ='crear2.php?objeto=' + document.tipoForo.objeto_seleccionado.value;}">&nbsp;&nbsp;&nbsp;
    <input type="button" name="cancelar" value="Cancelar" class="boton" onClick="javascript:history.back();"></p>
</form>
