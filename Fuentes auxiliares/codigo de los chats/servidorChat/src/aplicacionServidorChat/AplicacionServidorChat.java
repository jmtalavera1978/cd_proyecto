package aplicacionServidorChat;

import javax.swing.UIManager;

/**
 * <p>Title: </p>
 * <p>Description: </p>
 * <p>Copyright: Copyright (c) 2003</p>
 * <p>Company: </p>
 * @author unascribed
 * @version 1.0
 */

public class AplicacionServidorChat {
  boolean packFrame = false;
  static int puerto = -1;

  //Construct the application
  public AplicacionServidorChat(int puerto, String hostMysql, String userMysql, String passwordMysql) {
    FrameServidorChat frame = new FrameServidorChat(puerto, hostMysql, userMysql, passwordMysql);
    //Validate frames that have preset sizes
    //Pack frames that have useful preferred size info, e.g. from their layout
    if (packFrame) {
      frame.pack();
    }
    else {
      frame.validate();
    }
    frame.setVisible(true);
  }
  //Main method
  public static void main(String[] args) {
    String hostMysql = "";
    String userMysql = "";
    String passwordMysql = "";
    if(args.length<4) {
      System.out.println("No ha especificado algunos de los parámetros.");
      System.out.println("Debe ejecutar la aplicacion de la siguiente forma: java -classpath \"PATH_DE_LA_APLICACION;PATH_DE_mysql-connector-java-3.0.8-stable-bin.jar;\" servidorchat.servidorChat NUMPUERTO HOSTMySQL USUARIOMySQL PASSWORDMySQL");
      System.exit(0);
    } else {
      puerto = Integer.parseInt(args[0]);
      hostMysql = args[1];
      userMysql = args[2];
      passwordMysql = args[3];
    }
    try {
      UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    new AplicacionServidorChat(puerto, hostMysql, userMysql, passwordMysql);
  }
}