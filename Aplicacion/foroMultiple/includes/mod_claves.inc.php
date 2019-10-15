<?php

/***********************************
 Módulo para la creación de claves
 aleatorias para usar para accesos.
 
 Recibe dos parámetros que indican
 el mínimo y el máximo de caracteres
 que puede tener la clave.
 ***********************************/
function crear_clave_aleatoria ($tam_key_min, $tam_key_max) {

	list($usec, $sec) = explode(' ', microtime());
	srand( (float) $sec + ((float) $usec * 100000) );

	// Generamos la clave
	$clave="";
	
	//La clave la definimos entre el tamaño mínimo y máximo
	$max_chars = round( rand($tam_key_min, $tam_key_max) );
	$chars = array();
	//Creamos un vector con letras
	for ($i="a"; $i<="z"; $i++)
		$chars[] = $i;
	
	//Generamos la clave de max_chars caracteres con valores aleatorios de letras y números
	for ($i=0; $i<$max_chars; $i++) {
	  //Elegimos aleatoriamente si escribimos un número o una letra en la posición del carácter actual
	  $letra = round(rand(0, 1));
	  
	  if ($letra) //Escogemos una letra
		$clave .= $chars[round(rand(0, count($chars)-1))];
	  else //Escogemos un número
		$clave .= round(rand(0, 9));
	}
	return $clave;
}
?>
