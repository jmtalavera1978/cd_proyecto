package mensajes;
import java.io.*;

/***************************************
 * CLASE QUE REPRESENTA UNA PETICIÓN
 * REALIZADA POR UN CLIENTE AL SERVIDOR
 * OSEA, UN MENSAJE EN SENTIDO CLIENTE->
 * SERVIDOR.
 ***************************************/
public class Peticion implements Serializable{
  String bd; //base de datos de la peticion
  String tipo; //tipo de la petición
  Object datos; //datos de la petición

  /* CONSTRUCTOR */
  public Peticion(String bd, String tipo, Object datos) {
    this.bd = bd;
    this.tipo = tipo;
    this.datos = datos;
  }

  /* DEVUELVE LA BASE DE DATOS DE LA PETICIÓN */
  public String getBaseDatos() {
    return this.bd;
  }

  /* DEVUELVE EL TIPO DE LA PETICIÓN */
  public String getTipo() {
    return this.tipo;
  }

  /* DEVUELVE LOS DATOS DE LA PETICIÓN */
  public Object getDatos() {
    return this.datos;
  }
}