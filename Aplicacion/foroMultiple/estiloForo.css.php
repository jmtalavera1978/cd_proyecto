<?php
/***********************************
 CREACIÓN DE LA HOJA DE ESTILOS PPAL
 ***********************************/

//PARÁMETROS A PEDIR MEDIANTE FORMULARIO PARA EL ESTILO DEL FORO: SE GENERARÁ EL ARCHIVO estiloForo.css

$font_family = "Arial, Helvetica, sans-serif";

$font_family_forms = "Verdana, Arial, Helvetica, sans-serif";

$font_color = "#14549C";

$font_titulos_color = "#111177";

$font_tam = "12";

$color_fondo = "#EEFCFC";

$bordes_forms_tam = "2";

$celda_encabezado_1_color = "#ADCCDC";

$celda_encabezado_2_color = "#ACC8D5";

$celda_encabezado_2_color = "#C1D9E6";

//uSA ESTA VARIABLE PARA GENERAR EL ARCHIVO
//Variable que contendrá el archivo de la hoja de estilo
$hoja_de_estilo = "
body {      
	font-family: $font_family;      
	font-size: ".$font_tam."px; 
	color: $font_color;      
	font-style: normal;      
	font-weight: normal;      
	margin-top: 10px;      
	margin-right: 10px;      
	margin-bottom: 1px;      
	margin-left: 10px;      
	scrollbar-face-color: $color_fondo;      
	scrollbar-shadow-color: $font_color;      
	scrollbar-highlight-color: $font_color;      
	scrollbar-3dlight-color: gray;      
	scrollbar-darkshadow-color: $color_fondo;
	scrollbar-track-color: $color_fondo;      
	scrollbar-arrow-color: $font_color;      
	background-color: $color_fondo; 
}  

a:link { 
	color: $font_color; 	
	text-decoration: none; 	
	font-size: ".($font_tam-1)."px; 	
	font-weight:bold;
}  

a:visited { 	
	color: $font_color; 	
	text-decoration: none; 	
	font-size: ".($font_tam-1)."px; 	
	font-weight:bold; 
}  

a:hover { 	
	color: $font_color; 	
	text-decoration: none; 	
	font-size: ".($font_tam-1)."px; 	
	font-weight: bold; 
	background-color: $color_fondo;
}  

a:visited { 	
	color: $font_color; 	
	text-decoration: none; 
	font-size: ".($font_tam-1)."px; 	
	font-weight:bold; 
}  

img {     border:0px none; }

.datos_form {
	font-family: $font_family_forms; 
	font-size: ".($font_tam-1)."px;
	color: $font_color;
	border-color: $font_color;       
	border-width: ".$bordes_forms_tam."px;       
	border-left-width: ".$bordes_forms_tam."px;       
	border-right-width: ".$bordes_forms_tam."px;       
	border-style: solid;
	background-color:$color_fondo;	
}

.boton {       
	font-family: $font_family_forms;       
	font-size: ".($font_tam-2)."px;
	color: $color_fondo;       
	border-color: $font_color;       
	border-width: ".$bordes_forms_tam."px;       
	border-left-width: ".$bordes_forms_tam."px;       
	border-right-width: ".$bordes_forms_tam."px;       
	border-style: solid;       
	background-color: $font_color; 
}

.check {       
	font-family: $font_family_forms;       
	font-size: ".($font_tam-2)."px;       
	border-color: $font_color;       
	border-style: none;       
	background-color: transparent; 
}  

hr {   
	width:100%;    
	height:1px;    
	border-style:solid;    
	border-color:$font_color; 
}  

h1 {   
	font-family: $font_family;    
	font-size: ".($font_tam+12)."px;    
	color: $font_titulos_color
} 

h3 {    
	font-family: $font_family;    
	font-size: ".($font_tam+6)."px;    
	color: $font_titulos_color 
} 

h5 {    
	font-family: $font_family;    
	font-size: ".($font_tam-1)."px;    
	color: $font_titulos_color 
}  

.tabla_de_foro {
	border:1px;
	border-style:solid;
	border-color: $color_fondo;
	border-spacing:0px;
	background-color:transparent;
}

.tabla_de_temas {
	border:1px;
	border-style:solid;
	border-color: $celda_encabezado_1_color;
	background-color:transparent;
}

.tabla_de_temas_lista {
	border:0px;
	border-style:solid;
	border-color: $celda_encabezado_1_color;
	background-color:transparent;
}

.encabezado_de_foro {
	font-family: $font_family;
	font-size: ".($font_tam+8)."px;
	color: black;
	background-color:$celda_encabezado_1_color;
}

.encabezado_de_temas {
	font-family: $font_family;
	font-size: ".($font_tam+2)."px;
	font-weight:bold;
	color: black;
	background-color:$celda_encabezado_2_color;
}

.fila_de_tabla {
	font-family: $font_family;
	font-size: ".($font_tam-1)."px;
	color: $font_titulos_color;
	background-color:$celda_encabezado_3_color;
}

.campo {
	font-family: $font_family;
	font-size: ".$font_tam."px;
	color: black;
	background-color:transparent;	
}

.campo_oblig {
	font-family: $font_family;
	font-size: ".$font_tam."px;
	font-weight: bold;
	color: black;
	background-color:transparent;	
}

.valor {
	font-family: $font_family;
	font-size: ".($font_tam+2)."px;
	text-decoration: bold;
	color: $font_titulos_color;
	background-color:transparent;
}
";
?>