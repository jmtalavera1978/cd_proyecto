package clientechat;

import javax.swing.UIManager;
import java.awt.*;

/*APLICACIÓN PRINCIPAL DEL CLIENTE DEL CHAT*/
public class ApplicationClienteChat {
  boolean packFrame = false;

  //CONTRUCTOR DE LA APLICACIÓN, RECIBE LA BASE DE DATOS, EL PUERTO Y EL HOST DEL SERVIDOR
  public ApplicationClienteChat(String DB, int puerto, String host,String nombreChat,Color color_fuente,Color color_fondo,Color color_fuente_listas,Color color_fondo_listas,boolean permitir_banear,boolean permitir_mostrar_info_usuario,boolean permitir_configurar_fuentes,boolean permitir_cambio_nick,boolean mostrar_iconos_botones,boolean permitir_palabras_prohibidas,boolean permitir_privados) {
    //CREA EL NUEVO CLIENTE DEL CHAT
    FrameAccesoChat frame = new FrameAccesoChat(DB,puerto,host,nombreChat,color_fuente,color_fondo,color_fuente_listas,color_fondo_listas,permitir_banear,permitir_mostrar_info_usuario,permitir_configurar_fuentes,permitir_cambio_nick,mostrar_iconos_botones,permitir_palabras_prohibidas,permitir_privados);
    if (packFrame) {
      frame.pack();
    }
    else {
      frame.validate();
    }
    //CENTRA LA VENTANA EN LA PANTALLA
    Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
    Dimension frameSize = frame.getSize();
    if (frameSize.height > screenSize.height) {
      frameSize.height = screenSize.height;
    }
    if (frameSize.width > screenSize.width) {
      frameSize.width = screenSize.width;
    }
    frame.setLocation((screenSize.width - frameSize.width) / 2, (screenSize.height - frameSize.height) / 2);
    frame.setVisible(true); //PRESENTAMOS EL CLIENTE DEL CHAT
  }

  //MÉTODO MAIN PRINCIPAL
  public static void main(String[] args) {
    try {
      UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    // LA APLICACIÓN DEBE RECIBIR COMO PARÁMETROS:
    //LA BASE DE DATOS, EL PUERTO Y EL HOST DE LA MÁQUINA SERVIDORA y los parámetros de configuración
    if (args.length<3) {
      System.out.println (" Uso: java -classpath \"[PATH_CLASES]\" clientechat.ApplicationClienteChat NOMBREBASEDEDATOS PUERTO HOSTSERVIDOR [Opciones]\n");
      System.out.println ("Entre las opciones se encuentran las siguientes:");
      System.out.println ("   -nombre:NOMBRECHAT              Selecciona un nombre distinto para el chat.");
      System.out.println ("   -color_fuente:R-G-B             Color de la fuente:3 números (indican cantidad de color 'rojo-verde-azul'), de 0 a 255.");
      System.out.println ("   -color_fondo:R-G-B              Igualmente pero para el color de fondo de la aplicación.");
      System.out.println ("   -color_fuente_listas:R-G-B      Igualmente pero para el color de la fuente de listas y cajas de texto.");
      System.out.println ("   -color_fondo_listas:R-G-B       Igualmente pero para el color de fondo de listas y cajas de texto.");
      System.out.println ("   -permitir_banear                Permite a los administradores echar a usuarios de un canal.");
      System.out.println ("   -permitir_mostrar_info_usuario  Permite visualizar información sobre los usuarios.");
      System.out.println ("   -permitir_configurar_fuentes    Permite configurar el estilo del texto del usuario.");
      System.out.println ("   -permitir_cambio_nick           Permite al usuario cambiar su nick (que es su login).");
      System.out.println ("   -mostrar_iconos_botones         Permite mostrar iconos en los botones.");
      System.out.println ("   -permitir_palabras_prohibidas   Permite filtrar las palabras prohibidas de los textos.");
      System.out.println ("   -permitir_privados              Permite dialogos privados en el chat.");
      System.exit(0);
    }
    else {//CREAMOS LA APLICACIÓN CON LOS PARÁMETROS RECIBIDOS O POR DEFECTO
      //Parámetros obligatorios
      String BD = args[0];
      int puerto = Integer.parseInt(args[1]);
      String servidorHost = args[2];
      //Inicializamos los parámetros opcionales por defecto
      String nombreChat = "Chat de Foros & Chats, por Sixto y Tala.";
      Color color_fuente = new Color(20,84,156);
      Color color_fondo = new Color(173,204,220);
      Color color_fuente_listas = new Color(20,84,156);
      Color color_fondo_listas = new Color(238,252,252);
      boolean permitir_banear = false;
      boolean permitir_mostrar_info_usuario = false;
      boolean permitir_configurar_fuentes = false;
      boolean permitir_cambio_nick = false;
      boolean mostrar_iconos_botones = false;
      boolean permitir_palabras_prohibidas = false;
      boolean permitir_privados = false;
      //Comprobamos los parámetros opcionales recibidos
      String param;
      for (int i=3;i<args.length;i++) {
        param = args[i];
        if (param.equalsIgnoreCase("-permitir_banear"))
          permitir_banear = true;
        else if (param.equalsIgnoreCase("-permitir_mostrar_info_usuario"))
          permitir_mostrar_info_usuario = true;
        else if (param.equalsIgnoreCase("-permitir_configurar_fuentes"))
          permitir_configurar_fuentes = true;
        else if (param.equalsIgnoreCase("-permitir_cambio_nick"))
          permitir_cambio_nick = true;
        else if (param.equalsIgnoreCase("-mostrar_iconos_botones"))
          mostrar_iconos_botones = true;
        else if (param.equalsIgnoreCase("-permitir_palabras_prohibidas"))
          permitir_palabras_prohibidas = true;
        else if (param.equalsIgnoreCase("-permitir_privados"))
          permitir_privados = true;
        else if (param.toLowerCase().startsWith("-nombre:"))
          nombreChat = param.substring(8,param.length());
        else if (param.toLowerCase().startsWith("-color_fuente:"))
          color_fuente = extraerColor (param.substring(14,param.length()));
        else if (param.toLowerCase().startsWith("-color_fondo:"))
          color_fondo = extraerColor (param.substring(13,param.length()));
        else if (param.toLowerCase().startsWith("-color_fuente_listas:"))
          color_fuente_listas = extraerColor (param.substring(21,param.length()));
        else if (param.toLowerCase().startsWith("-color_fondo_listas:"))
          color_fondo_listas = extraerColor (param.substring(20,param.length()));
      }
      new ApplicationClienteChat(BD,puerto,servidorHost,nombreChat,color_fuente,color_fondo,color_fuente_listas,color_fondo_listas,permitir_banear,permitir_mostrar_info_usuario,permitir_configurar_fuentes,permitir_cambio_nick,mostrar_iconos_botones,permitir_palabras_prohibidas,permitir_privados);
    }
  }

  //Devuelve el color recibido como parámetro
  private static Color extraerColor (String valores) {
    int i=0;
    String rojo = "", verde = "", azul = "";

    while (i<valores.length() && valores.charAt(i)!='-') {
      rojo = rojo + valores.charAt(i);
      i++;
    }
    i++;
    while (i<valores.length() && valores.charAt(i)!='-') {
      verde = verde + valores.charAt(i);
      i++;
    }
    i++;
    while (i<valores.length()) {
      azul = azul + valores.charAt(i);
      i++;
    }

    return new Color(Integer.parseInt(rojo), Integer.parseInt(verde), Integer.parseInt(azul));
  }
}