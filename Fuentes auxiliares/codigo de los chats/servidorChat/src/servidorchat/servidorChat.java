package servidorchat;

import java.io.*;
import java.net.*;
import java.util.*;

/**
 * <p>Title: servidorChat</p>
 * <p>Description: Servidor del chat</p>
 * <p>Copyright: Copyright (c) 2003</p>
 * @author Sixto Suaña Moreno y José María Talavera Calzado
 * @version 1.0
 */
public class servidorChat{
  static int puerto;

  /* Método principal */
  public static void main(String args[]) throws Exception{
    if(args.length<4) {
      System.out.println("Uso: java -classpath \"PATH_DE_LA_APLICACION;PATH_DE_mysql-connector-java-3.0.8-stable-bin.jar;\" servidorchat.servidorChat NUMPUERTO HOSTMySQL USUARIOMySQL PASSWORDMySQL");
      System.exit(0);
    } else {
      puerto = Integer.parseInt(args[0]);
      String hostMysql = args[1];
      String userMysql = args[2];
      String passwordMysql = args[3];

      ServerSocket s=null;
      Socket sc=null;

      System.out.println("Servidor de chat\n----------------\nEscuchando por el puerto "+puerto+".\n");
      try{
        s=new ServerSocket(puerto);
        System.out.println("Esperando conexiones de nuevos clientes...");
        while(true){
          sc=s.accept();
          ThreadUsuario usuarioNuevo=new ThreadUsuario(sc, hostMysql, userMysql, passwordMysql);
          usuarioNuevo.start();
        }
      } catch ( IOException e ) {}

      //Cierre de los sockets
      finally{
        try{
          if(s!=null)
  	s.close();
        } catch(IOException ex){}
      }
      System.out.println("PROCESO TERMINADO.");
    }
  }
}