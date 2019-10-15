@echo off
@echo Ejecucion del cliente de chat...
set basedatos=ejemploDBchat
set puerto=8080
set maquina=150.214.141.71

java -classpath ".;" clientechat.ApplicationClienteChat %basedatos% %puerto% %maquina% -permitir_banear -permitir_mostrar_info_usuario -permitir_configurar_fuentes -permitir_cambio_nick -mostrar_iconos_botones -permitir_palabras_prohibidas -permitir_privados
@echo Cliente de chat ha finalizado...
pause