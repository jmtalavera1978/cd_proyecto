package accesoBD;

import java.util.*;
import java.io.*;

/*********************************************
 * ESTA CLASE REPRESENTA UN USUARIO REGISTRADO
 *********************************************/
public class Usuario implements Serializable{
  int id_usuario; //IDENTIFICADOR DEL USUARIO
  //String login_usuario;
  String nombre; //NOMBRE DEL USUARIO
  String apellidos; //APELLIDOS
  String nick; //NICK DEL USUARIO, NO SE USA
  //String password;
  String fecha_alta; //FECHA DE ALTA DEL USUARIO
  String fecha_ult_conexion; //FECHA DE LA ÚLTIMA CONEXIÓN

  /******************************************
   * CONSTRUCTOR QUE CREA AL USUARIO COMPLETO
   ******************************************/
  public Usuario(int id, String lu, String no, String a, String ni, String fa, String fuc) {
    id_usuario = id;
    //login_usuario = new String(lu);
    nombre = new String (no);
    apellidos = new String(a);
    nick = new String (ni);
    fecha_alta = new String(fa);
    fecha_ult_conexion = new String(fuc);
  }

  /* DEVUELVE EL IDENTIFICADOR DEL USUARIO */
  public int getId() {
    return id_usuario;
  }

  /* DEVUELVE EL NOMBRE COMPLETO DEL USUARIO */
  public String getNombreCompleto(){
    return (nombre + " "+ apellidos);
  }

  /* DEVUELVE EL NICK DEL USUARIO */
  public String getNick() {
    return nick;
  }

  /* MODIFICA EL NICK DEL USUARIO */
  public void setNick(String nick) {
    this.nick = nick;
  }

  /* OBTIENE LA FECHA DE ALTA DEL USUARIO, AL REVÉS O VOLTEADA */
  public String getFechaAlta(boolean voltear){
    if (voltear)
      return vueltaFecha(fecha_alta); //Da la vuelta a la fecha poniendola en formato: xx-xx-xxxx
    else
      return fecha_alta;
  }

  /* DEVUELVE LA FECHA DE LA ÚLTIMA CONEXIÓN VOLTEADA */
  public String getFechaUltConexion(){
    return vueltaFecha(fecha_ult_conexion);
  }

  /* FUNCIÓN PRIVADA QUE DA LA VUELTA A UNA FECHA */
  private String vueltaFecha(String fecha){
    int dia = Integer.parseInt(fecha.substring(8,10));
    int mes = Integer.parseInt(fecha.substring(5,7));
    int anyo = Integer.parseInt(fecha.substring(0,4));
    return (dia + "-" + mes + "-" + anyo);
  }
}