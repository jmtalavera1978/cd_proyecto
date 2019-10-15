    <td width="130" valign="top" align="left">
    <table width="130" class="tabla_de_menu" cellpadding="0" cellspacing="0" onClick="cambia(this,'menu');" onMouseOver="mano(this);">
	<!-- onMouseOut="restaura(this,'menu');" -->
      <tr class="titulo"> 
        <td width="15" height="20">&nbsp;</td>
        <td width="220" height="20" align="left"><img id="imagen" src="imagenes/abajoM.gif"> <font class="boton_menu" id="menuT"><?=pal('verMenu',$idioma)?></font></td>
      </tr>
    </table>
	
  <div id="menu" style="position:relative; visibility:hidden; z-index:2;"> 
    <table width="130" class="tabla_de_menu" cellpadding="0" cellspacing="0">
      <!-- onmouseover="cambia(this,'menu');" onmouseout="restaura(this,'menu');" -->
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo!="foros" && @$activo!="chats" && @$activo!="autores" && @$activo!="enlaces" && @$activo!="mapa" && @$activo!="forosPrueba" && @$activo!="chatPrueba") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="index.php">
          <?=pal('presentacion',$idioma)?>
          </a></td>
      </tr>
	  <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="autores") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="autores.php?activo=autores">
          <?=pal('autores',$idioma)?>
          </a></td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if(@$activo=="enlaces") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="enlaces.php?activo=enlaces">
          <?=pal('enlaces',$idioma)?>
          </a></td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="mapa") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="mapa.php?activo=mapa">
          <?=pal('mapaWeb',$idioma)?>
          </a></td>
      </tr>
	  <tr> 
        <td width="4" height="20">&nbsp;</td>
        <td width="11" height="20">&nbsp;</td>
        <td width="110" height="20">&nbsp;</td>
      </tr>
	  <tr> 
        <td width="4" height="20">&nbsp;</td>
        <td width="11" height="20">&nbsp;</td>
        <td width="110" height="20">::<u>FOROS</u>::</td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="foros") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="crear1.php?activo=foros&clase=1">
          <?=pal('foros',$idioma)?>
          </a></td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="forosPrueba") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="forosPrueba.php?activo=forosPrueba">
          <?=pal('forosEjemplo',$idioma)?>
          </a></td>
      </tr>
	  <tr> 
        <td width="4" height="20">&nbsp;</td>
        <td width="11" height="20">&nbsp;</td>
        <td width="110" height="20">&nbsp;</td>
      </tr>
	  <tr> 
        <td width="4" height="20">&nbsp;</td>
        <td width="11" height="20">&nbsp;</td>
        <td width="110" height="20">::<u>CHATS</u>::</td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="chats") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="crear1.php?activo=chats&clase=2">
          <?=pal('chats',$idioma)?>
          </a></td>
      </tr>
      <tr> 
        <td width="4" height="20">&nbsp;</td>
        <?php if (@$activo=="chatPrueba") { ?>
        <td width="11" height="20"><img src="imagenes/cuadrado.gif" width="16" height="8"></td>
        <?php } else { ?>
        <td width="11" height="20">&nbsp;</td>
        <?php } ?>
        <td width="110" height="20"><a href="chatPrueba.php?activo=chatPrueba">
          <?=pal('chatPrueba',$idioma)?>
          </a></td>
      </tr>

      <tr class="borde_de_tabla"> 
        <td width="4" height="20">&nbsp;</td>
        <td width="11" height="20">&nbsp;</td>
        <td width="110" height="20">&nbsp;</td>
      </tr>
    </table>
  </div>
      </td>