package accesoBD;

import java.util.*;
import java.io.*;

/*************************************
 * Implementa una clase que representa
 * el formato de texto de un usuario.
 *************************************/
public class FormatoTexto implements Serializable{
  String fuente; //nombre de la fuente
  int tam; //tama�o
  boolean negrita; //indica si est� en negrita
  boolean cursiva; //indica si est� en cursiva
  int color_r; //cantidad de rojo
  int color_g; //cantidad de verde
  int color_b; //cantidad de azul

  /* CONSTRUCTOR QUE RECIBE TODOS LOS PAR�METROS */
  public FormatoTexto (String f, int t, boolean n, boolean c, int r, int g, int b) {
    fuente = new String (f);
    tam = t;
    negrita = n;
    cursiva = c;
    color_r = r;
    color_g = g;
    color_b = b;
  }

  /* DEVUELVE EL NOMBRE DE LA FUENTE */
  public String getFuente () {
    return fuente;
  }

  /* DEVUELVE EL TAMA�O DE LA FUENTE */
  public int getTam () {
    return tam;
  }

  /* INDICA SI EST� EN NEGRITA */
  public boolean isNegrita () {
    return negrita;
  }

  /* INDICA SI EST� EN CURSIVA */
  public boolean isCursiva () {
    return cursiva;
  }

  /* DEVUELVE LA CANTIDAD DE ROJO */
  public int getColor_r() {
    return color_r;
  }

  /* DEVUELVE LA CANTIDAD DE VERDE */
  public int getColor_g() {
    return color_g;
  }

  /* DEVUELVE LA CANTIDAD DE AZUL */
  public int getColor_b() {
    return color_b;
  }
}