@echo off

set puerto=8080
set maquina=localhost
set usuario=root
set password=cdnov02

java -classpath ".;..\mysql-connector-java-3.0.8-stable-bin.jar;" servidorchat.servidorChat %puerto% %maquina% %usuario% %password%
echo Servidor finalizado.
pause