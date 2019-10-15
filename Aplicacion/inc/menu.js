<!--

/*PRINCIPIO DE FUNCIONES PARA MENÚS*/
function cambia(src,menu) {
		if (menu!=null) {
			if (document.getElementById(menu).style.visibility =="hidden") {
				document.getElementById(menu).style.visibility ="visible";
				<?php $pal = pal('ocultar',$idioma); ?>
				document.getElementById('menuT').innerHTML = '<?=$pal?>';
				document.getElementById('imagen').src = "imagenes/arribaM.gif";
			}
			else {
				document.getElementById(menu).style.visibility ="hidden";
				<?php $pal = pal('verMenu',$idioma); ?>
				document.getElementById('menuT').innerHTML = "<?=$pal?>";
				document.getElementById('imagen').src = "imagenes/abajoM.gif";
			}
		}
}

function mano(src) {
	src.style.cursor = "hand";
}

/*function restaura(src,menu) {
		src.style.cursor = "default";
		if (menu!=null)
			document.getElementById(menu).style.visibility ="hidden";
}*/
/*FIN DE FUNCIONES PARA MENÚS*/
//-->