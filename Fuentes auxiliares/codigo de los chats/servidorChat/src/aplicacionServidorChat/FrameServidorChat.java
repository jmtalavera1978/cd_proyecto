package aplicacionServidorChat;

import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import java.net.*;
import java.io.*;

/**
 * <p>Title: Servidor Chatr Forward</p>
 * <p>Description: Es un servidor chat tipo fordward para proyecto fin de carrera de Generación de foros y Chats</p>
 * <p>Copyright: Copyright (c) 2003</p>
 * @author Sixto Suaña Moreno y José María Talavera Calzado
 * @version 1.0
 */
public class FrameServidorChat extends JFrame {
  int puerto;
  String hostMysql;
  String userMysql;
  String passwordMysql;
  ConexionesThread conexionesUsuarios;
  ServerSocket socketServidor = null;
  boolean esta_moviendo = false;

  JPanel contentPane;
  JMenuBar menu = new JMenuBar();
  JMenu menuPrincipal = new JMenu();
  JMenuItem menuSalir = new JMenuItem();
  JToolBar barraTareas = new JToolBar();
  JButton botonEjecutar = new JButton();
  JButton botonParar = new JButton();
  JButton botonAcerca = new JButton();
  ImageIcon image1;
  ImageIcon image2;
  ImageIcon image3;
  JLabel barraEstado = new JLabel();
  BorderLayout borderLayout1 = new BorderLayout();
  JTextArea AreaInfo = new JTextArea();
  JScrollPane scrollArea = new JScrollPane(AreaInfo);
  JMenuItem menuEjecutar = new JMenuItem();
  JMenuItem menuParar = new JMenuItem();

  //Construct the frame
  public FrameServidorChat(int port, String h, String u, String p) {
    puerto = port;
    hostMysql = h;
    userMysql = u;
    passwordMysql = p;
    enableEvents(AWTEvent.WINDOW_EVENT_MASK);
    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }
  }
  //Component initialization
  private void jbInit() throws Exception  {
    image1 = new ImageIcon(aplicacionServidorChat.FrameServidorChat.class.getResource("conectar.gif"));
    image2 = new ImageIcon(aplicacionServidorChat.FrameServidorChat.class.getResource("desconectar.gif"));
    image3 = new ImageIcon(aplicacionServidorChat.FrameServidorChat.class.getResource("salir.gif"));
    //setIconImage(Toolkit.getDefaultToolkit().createImage(FrameServidorChat.class.getResource("[Your Icon]")));
    contentPane = (JPanel) this.getContentPane();
    contentPane.setLayout(borderLayout1);
    this.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);
    this.setResizable(false);
    this.setSize(new Dimension(525, 343));
    this.setTitle("Servidor CHAT");
    barraEstado.setText(" ");
    menuPrincipal.setText("Menú");
    menuSalir.setText("Salir");
    menuSalir.addActionListener(new ActionListener()  {
      public void actionPerformed(ActionEvent e) {
        menuSalir_actionPerformed(e);
      }
    });
    botonEjecutar.setIcon(image1);
    botonEjecutar.setText("Ejecutar");
    botonEjecutar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonEjecutar_actionPerformed(e);
      }
    });
    botonEjecutar.setEnabled(false);
    botonEjecutar.setToolTipText("Ejecuta el servidor chat");
    botonParar.setIcon(image2);
    botonParar.setText("Parar");
    botonParar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonParar_actionPerformed(e);
      }
    });
    botonParar.setToolTipText("Para el servidor chat");
    botonAcerca.setIcon(image3);
    botonAcerca.setText("Salir");
    botonAcerca.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonAcerca_actionPerformed(e);
      }
    });
    botonAcerca.setToolTipText("Sale de la aplicación");
    AreaInfo.setBorder(null);
    AreaInfo.setEditable(false);
    menuEjecutar.setText("Ejecutar");
    menuEjecutar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        menuEjecutar_actionPerformed(e);
      }
    });
    menuParar.setText("Parar");
    menuParar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        menuParar_actionPerformed(e);
      }
    });
    scrollArea.setHorizontalScrollBarPolicy(JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
    scrollArea.setAutoscrolls(true);
    scrollArea.getVerticalScrollBar().addAdjustmentListener(new AdjustmentListener(){
      public void adjustmentValueChanged(AdjustmentEvent e) {
        scrollArea_adjustmentValueChanged(e);
      }
    });
    //Permite ver el texto anterior moviendo el scroll del editor con el ratón, anulando el ajuste hacia abajo
    scrollArea.getVerticalScrollBar().addMouseListener(new MouseListener(){
      public void mousePressed(MouseEvent e) { esta_moviendo = true; } //No permite enviar abajo el scroll
      public void mouseExited(MouseEvent e) {}
      public void mouseEntered (MouseEvent e) {}
      public void mouseReleased (MouseEvent e) { //Permite mover abajo el scroll y lo mueve
        esta_moviendo = false;
        scrollArea.getVerticalScrollBar().setValue(scrollArea.getVerticalScrollBar().getMaximum());
      }
      public void mouseClicked (MouseEvent e) {}
    });
    barraTareas.add(botonEjecutar);
    barraTareas.add(botonParar);
    barraTareas.add(botonAcerca);
    menuPrincipal.add(menuEjecutar);
    menuPrincipal.add(menuParar);
    menuPrincipal.add(menuSalir);
    menu.add(menuPrincipal);
    this.setJMenuBar(menu);
    contentPane.add(barraTareas, BorderLayout.NORTH);
    contentPane.add(barraEstado, BorderLayout.SOUTH);
    contentPane.add(scrollArea, BorderLayout.CENTER);

    AreaInfo.append("Servidor de chat\n----------------\nEscuchando por el puerto "+puerto+".\n");

    if(puerto==-1) {
      JOptionPane.showMessageDialog(null,"No ha especificado el numero de puerto de escucha.\nDebe ejecutar la aplicacion de la siguiente forma: java -classpath \"PATH_DE_LA_APLICACION;PATH_DE_mysql-connector-java-3.0.8-stable-bin.jar;\" servidorchat.servidorChat NUMPUERTO HOSTMySQL USUARIOMySQL PASSWORDMySQL\n","Error",JOptionPane.ERROR_MESSAGE);
      System.exit(0);
    } else {
      try {
        socketServidor = new ServerSocket(puerto);
        conexionesUsuarios = new ConexionesThread(socketServidor);
        conexionesUsuarios.start();
        barraEstado.setText("Servidor ejecutándose en el puerto "+puerto);
      } catch (BindException be){
        JOptionPane.showMessageDialog(null,"El puerto seleccionado se encuentra en uso.","No se ha podido ejecutar el servidor", JOptionPane.INFORMATION_MESSAGE);
        System.exit(0);
      }
    }
  }
  //File | Exit action performed
  public void menuSalir_actionPerformed(ActionEvent e) {
    salir();
  }

  //Overridden so we can exit when window is closed
  protected void processWindowEvent(WindowEvent e) {
    super.processWindowEvent(e);
    if (e.getID() == WindowEvent.WINDOW_CLOSING) {
      menuSalir_actionPerformed(null);
    }
  }

  class ConexionesThread extends Thread {
    /* PARÁMETROS */
    ServerSocket socketServidor;

    /* FUNCIONES */
    /*El contructor de la clase*/
    ConexionesThread (ServerSocket s){
      socketServidor = s;
    }

    //HILO DE EJECUCIÓN
    public void run(){
      try{
        AreaInfo.append("Esperando conexiones de nuevos clientes...\n\n");
        Socket sc=null;
        while(true){
          sc=socketServidor.accept();
          ThreadUsuario usuarioNuevo=new ThreadUsuario(sc, hostMysql, userMysql, passwordMysql, AreaInfo);
          usuarioNuevo.start();
        }
      } catch ( IOException e ) {}
    }

  }

  void botonAcerca_actionPerformed(ActionEvent e) {
    salir();
  }

  void botonParar_actionPerformed(ActionEvent e) {
    parar();
  }

  void botonEjecutar_actionPerformed(ActionEvent e) {
    ejecutar();
  }

  void menuEjecutar_actionPerformed(ActionEvent e) {
    ejecutar();
  }

  void menuParar_actionPerformed(ActionEvent e) {
    parar();
  }

  void scrollArea_adjustmentValueChanged(AdjustmentEvent e) {
    if (!esta_moviendo)
      scrollArea.getVerticalScrollBar().setValue(scrollArea.getVerticalScrollBar().getMaximum());
  }

  private void salir() {
    if (JOptionPane.showConfirmDialog(null,"¿Desea salir del programa?","Salir del servidor",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION) {
      parar();
      System.exit(0);
    }
  }

  private void ejecutar() {
    botonEjecutar.setEnabled(false);
    botonParar.setEnabled(true);
    if (conexionesUsuarios!=null) {
      try {
        socketServidor = new ServerSocket(puerto);
        conexionesUsuarios = new ConexionesThread(socketServidor);
        conexionesUsuarios.start();
        AreaInfo.append("Servidor ejecutándose...\n");
        barraEstado.setText("Servidor ejecutándose en el puerto "+puerto);
      }catch (IOException ioe){}
    }
  }

  private void parar() {
    botonEjecutar.setEnabled(true);
    botonParar.setEnabled(false);
    if (conexionesUsuarios!=null) {
      try {
        socketServidor.close();
        conexionesUsuarios.stop();
        AreaInfo.append("Servidor parado.\n");
        barraEstado.setText("Servidor parado.");
      }catch (IOException ioe){}
    }
  }
}