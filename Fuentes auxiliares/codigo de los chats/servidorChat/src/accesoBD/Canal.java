package accesoBD;

import java.util.*;
import java.io.*;

/****************************************
 * CLASE QUE REPRESENTA UN CANAL DEL CHAT
 ****************************************/
public class Canal implements Serializable{
  int id_canal; //IDENTIFICADOR DEL CANAL
  String nombre; //NOMBRE DEL CANAL

  /************************
   * CONTRUCTOR DE LA CLASE
   ************************/
  public Canal(int id, String nombre) {
    id_canal = id;
    this.nombre = new String (nombre);
  }

  /* DEVUELVE EL IDENTIFICADOR DEL CANAL EN CUESTIÓN */
  public int getId() {
    return this.id_canal;
  }

  /* DEVUELVE EL NOMBRE DEL CANAL */
  public String getNombre() {
    return this.nombre;
  }
}