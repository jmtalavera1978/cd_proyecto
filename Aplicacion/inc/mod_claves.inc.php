<?php

/***********************************
 M�dulo para la creaci�n de claves
 aleatorias para usar para accesos.
 
 Recibe dos par�metros que indican
 el m�nimo y el m�ximo de caracteres
 que puede tener la clave.
 ***********************************/
function crear_clave_aleatoria ($tam_key_min, $tam_key_max) {

	list($usec, $sec) = explode(' ', microtime());
	srand( (float) $sec + ((float) $usec * 100000) );

	// Generamos la clave
	$clave="";
	
	//La clave la definimos entre el tama�o m�nimo y m�ximo
	$max_chars = round( rand($tam_key_min, $tam_key_max) );
	$chars = array();
	//Creamos un vector con letras
	for ($i="a"; $i<="z"; $i++)
		$chars[] = $i;
	
	//Generamos la clave de max_chars caracteres con valores aleatorios de letras y n�meros
	for ($i=0; $i<$max_chars; $i++) {
	  //Elegimos aleatoriamente si escribimos un n�mero o una letra en la posici�n del car�cter actual
	  $letra = round(rand(0, 1));
	  
	  if ($letra) //Escogemos una letra
		$clave .= $chars[round(rand(0, count($chars)-1))];
	  else //Escogemos un n�mero
		$clave .= round(rand(0, 9));
	}
	return $clave;
}
?>
