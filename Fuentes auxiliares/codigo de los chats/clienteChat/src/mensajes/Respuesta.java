package mensajes;
import java.io.*;

/***************************************
 * CLASE QUE REPRESENTA UNA RESPUESTA
 * REALIZADA POR EL SERVIDOR AL CLIENTE
 * OSEA, UN MENSAJE EN SENTIDO SERVIDOR->
 * CLIENTE.
 ***************************************/
public class Respuesta implements Serializable{
  String tipo; //indica el tipo de respuesta
  Object datos; //Contiene los datos de la respuesta

  /* CONSTRUCTOR */
  public Respuesta(String tipo, Object datos) {
    this.tipo = tipo;
    this.datos = datos;
  }

  /* DEVUELVE EL TIPO DE LA RESPUESTA */
  public String getTipo() {
    return this.tipo;
  }

  /* DEVUELVE LOS DATOS DE LA RESPUESTA */
  public Object getDatos() {
    return this.datos;
  }
}