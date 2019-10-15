<!--
//Recibe el estilo del cursor, el color de fondo y el de texto de un recurso src y se lo cambia
function formatear_src (src, cursor, colorFondo, colorEstilo) {
		src.style.cursor = cursor;
		src.bgColor = colorFondo;
		src.style.color = colorEstilo;
}

//Recibe dos fechas indicando si la de inicio es anterior a la de fin
function fechaAnterior(dia_inic,mes_inic, a_inic,dia_fin, mes_fin, a_fin)
{
	var m_inic=numMes(mes_inic);
	var m_fin=numMes(mes_fin);

	if (a_inic>a_fin || (a_inic==a_fin && m_inic>m_fin)||(a_inic==a_fin && m_inic==m_fin && dia_inic>dia_fin))
		return true;
	return false;
}

//Pasa un mes en formato cadena al número de mes que le corresponde
function numMes(mes)
{
         var num=0;
         switch (mes)
         {
                case "Enero" : num=0; break;
                case "Febrero" : num=1; break;
                case "Marzo" : num=2; break;
                case "Abril" : num=3; break;
                case "Mayo" : num=4; break;
                case "Junio" : num=5; break;
                case "Julio" : num=6; break;
                case "Agosto" : num=7; break;
                case "Septiembre" : num=8; break;
                case "Octubre" : num=9; break;
                case "Noviembre" : num=10; break;
                case "Diciembre" : num=11; break;
         }
         return num;
}

//Valida un mail, comprobando que posea los caracteres necesarios
function validarEmail(mail)
{
	if (mail=="" || ( mail.indexOf ('@', 0) != -1 && mail.indexOf ('.', 0) != -1 ))
		return (true);
	else {
		alert("Escriba una dirección de correo válida."); 
    	return (false);
	}
}

//-->