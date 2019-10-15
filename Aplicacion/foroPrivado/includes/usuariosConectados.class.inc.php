<?php
/*
    //CREACIÓN DE LA TABLA DE USUARIOS
    consulta ("CREATE TABLE usuariosConectados (
        tiempoConexion int(15) DEFAULT '0' NOT NULL,
        ip varchar(40) NOT NULL,
        archivo varchar(100) NOT NULL,
        INDEX (tiempoConexion),
        INDEX ip(ip),
        INDEX archivo(archivo)
       )");
*/
    
class usuariosConectados {

    var $tiempoEnSegundosSinConexion = 120;
    var $num_usuarios = 0;
    
    function usuariosConectados() {
        $this->refrescar();                                                                               
    }
    
    function get_num_usuarios() {
        return $this->num_usuarios;
    }
    
    function refrescar() {
        global $REMOTE_ADDR, $PHP_SELF;
        
        $tiempoActual = time();
        $tiempoSinConexion = $tiempoActual - $this->tiempoEnSegundosSinConexion;
        
        /*mysql_connect($this->host, $this->user, $this->password)
            or die('Error conecting to database');*/
            
        consulta(/*$this->database,*/
                       "INSERT INTO usuariosConectados VALUES ('$tiempoActual','$REMOTE_ADDR','$PHP_SELF')") 
            or die('Error writing to database');                       
            
        consulta(/*$this->database,*/
                       "DELETE FROM usuariosConectados WHERE tiempoConexion < $tiempoSinConexion")
            or die('Error deleting from database');
            
        $result = consulta(/*$this->database,*/
                                 "SELECT DISTINCT ip FROM usuariosConectados WHERE archivo='$PHP_SELF'")
            or die('Error reading from database');

        $this->num_usuarios = mysql_num_rows($result); 
                                                                                     
        /*mysql_close();*/
        @mysql_free_result($result);
    }
    
}

?>