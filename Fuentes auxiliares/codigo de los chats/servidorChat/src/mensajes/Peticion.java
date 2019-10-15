package mensajes;
import java.io.*;

/***************************************
 * CLASE QUE REPRESENTA UNA PETICI�N
 * REALIZADA POR UN CLIENTE AL SERVIDOR
 * OSEA, UN MENSAJE EN SENTIDO CLIENTE->
 * SERVIDOR.
 ***************************************/
public class Peticion implements Serializable{
  String bd; //base de datos de la peticion
  String tipo; //tipo de la petici�n
  Object datos; //datos de la petici�n

  /* CONSTRUCTOR */
  public Peticion(String bd, String tipo, Object datos) {
    this.bd = bd;
    this.tipo = tipo;
    this.datos = datos;
  }

  /* DEVUELVE LA BASE DE DATOS DE LA PETICI�N */
  public String getBaseDatos() {
    return this.bd;
  }

  /* DEVUELVE EL TIPO DE LA PETICI�N */
  public String getTipo() {
    return this.tipo;
  }

  /* DEVUELVE LOS DATOS DE LA PETICI�N */
  public Object getDatos() {
    return this.datos;
  }
}