<!--
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