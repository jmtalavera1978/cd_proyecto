package clientechat;

import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import java.net.*;
import java.io.*;
import java.util.*;
import mensajes.*;
import accesoBD.*;
import javax.swing.border.*;
import java.beans.*;
import java.util.regex.*;
import javax.swing.event.*;
import javax.swing.text.*;

/********************************************
 * CLASE FramePrivados, implementa un JFrame
 * que representa una ventana de una
 * conversación privada con otro usuario y la
 * lleva a cabo mediante una comunicación
 * usuario a usuario.
 ********************************************/
public class FramePrivados extends JFrame {
  int puerto; //Puerto de conexión con el usuario remoto
  String usuario; //Nombre o nick del usuario
  public String usuarioRem; //Nombre o nick del usuario remoto
  String IPHostPrivado; //IP de la máquina privada
  Socket socketConexion = null; //Socket de conexión con el otro usuario
  ObjectOutputStream out; //Stream de envío de datos y mensajes
  threadReceptor receptorDatos; //Para recibir datos del otro usuario
  Vector palabras; //Palabras prohibidas
  MutableAttributeSet formato = new SimpleAttributeSet(); //Formato del texto del usuario pasado como parámetro
  boolean esta_moviendo = false; //Indica que se está moviendo el scroll del editor del chat

  //VARIABLES DE CONFIGURACIÓN DEL CHAT
  Color color_fuente, color_fondo, color_fuente_listas, color_fondo_listas;
  boolean mostrar_iconos_botones;

  //ATRIBUTOS DEL EDITOR DEL CHAT, SU ESTILO DE DOCUMENTO Y SU KIT DE EDITOR
  JEditorPane editorChat = new JEditorPane();
  protected DefaultStyledDocument doc = new DefaultStyledDocument();
  protected StyledEditorKit kit = new StyledEditorKit();

  //ATRIBUTOS DE CONTENIDO DE LA APLICACIÓN
  JPanel contentPane;
  FlowLayout flowLayout1 = new FlowLayout();
  JScrollPane scrollEditor = new JScrollPane(editorChat);
  JButton botonEnviar = new JButton();
  JTextField textoEscribir = new JTextField();
  TitledBorder titledBorder1;
  TitledBorder titledBorder2;
  JButton botonBorrarMensajes = new JButton();
  JButton botonSalir = new JButton();
  JPanel panelMenu = new JPanel();
  GridLayout gridLayout1 = new GridLayout();
  JLabel EtiquetaTitulo = new JLabel();


  /************************************
   * CONSTRUCTOR DEL FRAME EN EL LADO
   * DEL SERVIDOR DEL PRIVADO DEL CHAT.
   *  RECIBE:
   * -sc: socket de conexión creado
   * -palabras: palabras prohibidas
   * -usuario: nick de usuario
   * -formato: formato del texto
   * -Colores de formato de ventana
   ************************************/
  public FramePrivados(Socket sc, Vector palabras, String usuario, MutableAttributeSet formato, Color color_fuente, Color color_fondo, Color color_fuente_listas, Color color_fondo_listas, boolean mostrar_iconos_botones) {
    //Creamos la conexión con el cliente
    socketConexion = sc;
    IPHostPrivado = sc.getInetAddress().getHostName();
    this.puerto = sc.getPort();
    //Demás parámetros
    this.palabras = palabras;
    this.usuario = usuario;
    this.formato = formato;
    //Datos de configuración del chat
    this.color_fuente = color_fuente;
    this.color_fondo = color_fondo;
    this.color_fuente_listas = color_fuente_listas;
    this.color_fondo_listas = color_fondo_listas;
    this.mostrar_iconos_botones = mostrar_iconos_botones;
    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }
  }


  /**********************************
   * CONSTRUCTOR DEL FRAME EN EL LADO
   * DEL CLIENTE DEL PRIVADO.
   *  RECIBE:
   * -IPprivado: IP del host remoto
   * -puerto: puerto del host remoto
   * -palabras: palabras prohibidas
   * -usuario: nick de usuario
   * -usuarioRem: nick usuario remoto
   * -formato: formato del texto
   **********************************/
  public FramePrivados(InetAddress IPprivado,int puerto,Vector palabras,String usuario, String usuarioRem, MutableAttributeSet formato, Color color_fuente, Color color_fondo, Color color_fuente_listas, Color color_fondo_listas, boolean mostrar_iconos_botones) {
    enableEvents(AWTEvent.WINDOW_EVENT_MASK);
    IPHostPrivado = IPprivado.getHostName();
    this.puerto = puerto;
    this.palabras = palabras;
    this.usuario = usuario;
    this.usuarioRem = usuarioRem;
    this.formato = formato;
    //Datos de configuración del chat
    this.color_fuente = color_fuente;
    this.color_fondo = color_fondo;
    this.color_fuente_listas = color_fuente_listas;
    this.color_fondo_listas = color_fondo_listas;
    this.mostrar_iconos_botones = mostrar_iconos_botones;
    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }
  }

  //INICIALIZACIÓN DE COMPONENTES, PRESENTA LOS COMPONENTE DE ACCESO AL CHAT
  private void jbInit() throws Exception  {
    //INICIALIZACIÓN
    contentPane = (JPanel) this.getContentPane();
    contentPane.setLayout(flowLayout1);
    contentPane.setBackground(color_fondo);
    this.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);
    this.setResizable(false);
    this.setSize(new Dimension(449, 416));
    if (usuarioRem != null)
      this.setTitle(usuarioRem+": Conversación privada.");
    contentPane.setBorder(BorderFactory.createLineBorder(color_fuente));
    contentPane.setPreferredSize(new Dimension(2725, 300));
    //EDITOR CHAT
    editorChat.setPreferredSize(new Dimension(350, 250));
    editorChat.setEditorKit(kit);
    editorChat.setDocument(doc);
    editorChat.setEditable(false);
    editorChat.setForeground(color_fuente_listas);
    editorChat.setBackground(color_fondo_listas);
    editorChat.setSelectedTextColor(color_fondo_listas);
    editorChat.setSelectionColor(color_fuente_listas);
    borrarEditor(); //Inicializamos el editor del chat
    //BOTON ENVIAR
    botonEnviar.setPreferredSize(new Dimension(130, 21));
    botonEnviar.setText("Enviar datos");
    botonEnviar.setBackground(color_fondo);
    botonEnviar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonEnviar.setForeground(color_fuente);
    botonEnviar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonEnviar_actionPerformed(e);
      }
    });
    //TEXTO ESCRIBIR
    textoEscribir.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoEscribir.setPreferredSize(new Dimension(280, 21));
    textoEscribir.setRequestFocusEnabled(true);
    textoEscribir.setBackground(color_fondo_listas);
    textoEscribir.setForeground(color_fuente_listas);
    textoEscribir.setSelectedTextColor(color_fondo_listas);
    textoEscribir.setSelectionColor(color_fuente_listas);
    textoEscribir.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoEscribir_keyPressed(e);
      }
    });
    //SCROLL DEL EDITOR DEL CHAT PRIVADO
    scrollEditor.setHorizontalScrollBarPolicy(JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
    scrollEditor.setBorder(BorderFactory.createLineBorder(color_fuente));
    scrollEditor.setPreferredSize(new Dimension(415, 250));
    //Ajuste hacia abajo de la barra vertical del scroll del editor
    scrollEditor.getVerticalScrollBar().addAdjustmentListener(new AdjustmentListener(){
      public void adjustmentValueChanged(AdjustmentEvent e) {
        scrollEditor_adjustmentValueChanged(e);
      }
    });
    //Permite ver el texto anterior moviendo el scroll del editor con el ratón, anulando el ajuste hacia abajo
    scrollEditor.getVerticalScrollBar().addMouseListener(new MouseListener(){
      public void mousePressed(MouseEvent e) { esta_moviendo = true; } //No permite enviar abajo el scroll
      public void mouseExited(MouseEvent e) {}
      public void mouseEntered (MouseEvent e) {}
      public void mouseReleased (MouseEvent e) { //Permite mover abajo el scroll y lo mueve
        esta_moviendo = false;
        scrollEditor.getVerticalScrollBar().setValue(scrollEditor.getVerticalScrollBar().getMaximum());
      }
      public void mouseClicked (MouseEvent e) {}
    });
    //BOTÓN BORRAR MENSAJES
    botonBorrarMensajes.setText("Borrar");
    botonBorrarMensajes.setBorder(BorderFactory.createEtchedBorder());
    botonBorrarMensajes.setToolTipText("Borra la lista de mensajes");
    botonBorrarMensajes.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonBorrarMensajes.setBackground(color_fondo);
    botonBorrarMensajes.setFont(new java.awt.Font("Dialog", 1, 12));
    botonBorrarMensajes.setForeground(color_fuente);
    if (mostrar_iconos_botones)
      botonBorrarMensajes.setIcon(new ImageIcon(clientechat.FramePrivados.class.getResource("../imagenes/borrar.gif")));
    botonBorrarMensajes.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonBorrarMensajes_actionPerformed(e);
      }
    });
    //BOTÓN SALIR
    botonSalir.setText("Cerrar conversación");
    botonSalir.setBorder(BorderFactory.createEtchedBorder());
    botonSalir.setToolTipText("Cierra la conexión con el usuario remoto");
    botonSalir.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonSalir.setForeground(color_fuente);
    botonSalir.setBackground(color_fondo);
    botonSalir.setFont(new java.awt.Font("Dialog", 1, 12));
    if (mostrar_iconos_botones)
      botonSalir.setIcon(new ImageIcon(clientechat.FramePrivados.class.getResource("../imagenes/salir.gif")));
    botonSalir.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonSalir_actionPerformed(e);
      }
    });
    //PANEL DEL MENÚ
    panelMenu.setBorder(BorderFactory.createEtchedBorder());
    panelMenu.setBackground(color_fondo);
    panelMenu.setPreferredSize(new Dimension(415, 30));
    panelMenu.setLayout(gridLayout1);
    //ETIQUETA TÍTULO
    EtiquetaTitulo.setFont(new java.awt.Font("Monospaced", 1, 20));
    EtiquetaTitulo.setBorder(BorderFactory.createLineBorder(color_fuente_listas));
    EtiquetaTitulo.setBackground(color_fondo_listas);
    EtiquetaTitulo.setForeground(color_fuente_listas);
    EtiquetaTitulo.setPreferredSize(new Dimension(415, 30));
    EtiquetaTitulo.setHorizontalAlignment(SwingConstants.CENTER);
    EtiquetaTitulo.setText("Conversación privada");
    //PRESENTACIÓN DE COMPONENTES
    contentPane.add(EtiquetaTitulo, null);
    contentPane.add(panelMenu, null);
    panelMenu.add(botonBorrarMensajes, null);
    panelMenu.add(botonSalir, null);
    contentPane.add(scrollEditor, null);
    contentPane.add(textoEscribir, null);
    contentPane.add(botonEnviar, null);
  }


  //PULSAR EL BOTÓN 'ENVIAR' MENSAJE
  void botonEnviar_actionPerformed(ActionEvent e) {
    if (!textoEscribir.getText().equals("")) {
      enviarDatos();
    }
  }

  //PULSAR ENTER EN EL 'TEXTOESCRIBIR' DE ESCRITURA DE MENSAJES
  void textoEscribir_keyPressed(KeyEvent e) {
    if (!textoEscribir.getText().equals("") && e.getKeyCode() == KeyEvent.VK_ENTER) {
      enviarDatos();
    }
  }

  //PULSAR EL BOTÓN 'BORRARMENSAJES'
  void botonBorrarMensajes_actionPerformed(ActionEvent e) {
    borrarEditor();
    textoEscribir.requestFocus();
  }

  //PULSAR EL BOTÓN 'CERRARCONVERSACIÓN'
  void botonSalir_actionPerformed(ActionEvent e) {
    if (JOptionPane.showConfirmDialog(null,"Se cerrará también la ventana del usuario remoto.\n¿Seguro que desea salir de la conversación?","Salir del privado",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION)
      salir();
  }

  //PULSAR BOTÓN 'OCULTARCONVERSACIÓN'
  void botonOcultar_actionPerformed(ActionEvent e) {
    hide();
  }

  //AJUSTE DEL SCROLL DEL EDITOR DEL CHAT
  void scrollEditor_adjustmentValueChanged(AdjustmentEvent e) {
    if (!esta_moviendo)
      scrollEditor.getVerticalScrollBar().setValue(scrollEditor.getVerticalScrollBar().getMaximum());
  }


  /*****************************************
   * CLASE 'threadReceptor' QUE SE ENCARGA
   * DE RECIBIR LAS RESPUESTAS DEL SERVIDOR
   * A LAS PETICIONES REALIZADAS POR LOS
   * USUARIOS.
   *****************************************/
   /*PRINCIPIO DE LA CLASE threadReceptor*/
  public class threadReceptor extends Thread{
    Socket socketConexion; //socket de conexión con el servidor
    ObjectInputStream in;  //Stream de entrada de datos desde el servidor

    //Constructor que recibe el socket de conexión y crea el flujo de entrada
    threadReceptor(Socket s){
	try{
		socketConexion = s;
		in = new ObjectInputStream(socketConexion.getInputStream());
	}
	catch (IOException e){ }
    }

    //Ejecuta la lectura de dicho puerto para lectura de respuestas del servidor
    public void run(){
      boolean hayConexion=true;
      while(hayConexion){
	try{
          //Obtenemos el tipo de respuesta realizada por el servidor
          String tipo = null;
          Object datos = null;
          try{
            Respuesta res = (Respuesta)in.readObject();
            tipo = res.getTipo();
            datos = res.getDatos();
          } catch (ClassNotFoundException cnfe){ cnfe.printStackTrace(); }


          //RESPUESTA TIPO 'MENSAJENORMAL'; SE OBTIENE UN MENSAJE DE UN USUARIO
          if (tipo.equalsIgnoreCase("mensajeNormal")){
            String mensaje = (String)((Vector)datos).get(0);
            MutableAttributeSet attr = (MutableAttributeSet)((Vector)datos).get(1);
            escribir(mensaje,attr);
            if (!isVisible())
              setVisible(true);


          //RESPUESTA DE TIPO 'GETNICK'; SE OBTIENE EL NICK DEL USUARIO REMOTO
          } else if (tipo.equalsIgnoreCase("getNick")){
            usuarioRem = (String)datos;
            inicializar();


          //RESPUESTA DE TIPO 'TERMINAR'; SE TERMINA LA CONVERSACIÓN PRIVADA
          } else if (tipo.equalsIgnoreCase("terminar")){
            JOptionPane.showMessageDialog(null,usuarioRem+" ha cerrado la conexión.","Sin conexión",JOptionPane.INFORMATION_MESSAGE);
            try {
              in.close();
              out.close();
              socketConexion.close();
              hide();
              receptorDatos.stop();
              receptorDatos = null;
            } catch (IOException e) {}
          }


	} catch(IOException e){ //EN CASO DE ERROR DE CONEXIÓN, SE CIERRA LA CONVERSACIÓN
          JOptionPane.showMessageDialog(null,usuarioRem+" ha cerrado la conexión.","Sin conexión",JOptionPane.INFORMATION_MESSAGE);
          try {
            in.close();
            out.close();
            socketConexion.close();
            hide();
            receptorDatos.stop();
            receptorDatos = null;
          } catch (IOException ioex) {}
        }
      }
    }
  }
  /*FIN DE LA CLASE threadReceptor*/

  //Realiza la conexión
  public boolean conectar () {
    try{
      if (socketConexion==null)
        socketConexion = new Socket(IPHostPrivado, puerto);
      out = new ObjectOutputStream(socketConexion.getOutputStream());
      //Abrimos el threadReceptor de datos
      synchronized(this){
        out.writeObject((Object)(new Respuesta("getNick",usuario)));
        out.flush();
      }
      receptorDatos = new threadReceptor(socketConexion);
      receptorDatos.start();
    }
    catch(Exception e){
      synchronized(this){
        return (false);
      }
    }
    return (true);
  }

  //Funcion que envia los datos de un nuevo mensaje filtrando las palabras prohibidas
  private void enviarDatos() {
    try {
      String textoFiltrado = filtrar(textoEscribir.getText());
      escribir ("[" + usuario + "] " + textoFiltrado, formato);
      Vector mens = new Vector();
      mens.add(0,"[" + usuario + "] " + textoFiltrado);
      mens.add(1,formato);
      synchronized(this){
        out.writeObject(new Respuesta("mensajeNormal",mens)); //ENVIO DE MENSAJES
        out.flush();
      }
    } catch (IOException ex){}
    textoEscribir.setText("");
  }

  // Función para escribir en el 'editorChat' de mensajes del chat
  //con un formato especificado.
  //Función que escribe el texto recibido de otros usuarios
  private void escribir (String texto, MutableAttributeSet attr) {
    try {
      doc.insertString(doc.getLength(), "\n", attr);
      doc.insertString(doc.getLength(), texto, attr);
    } catch(BadLocationException ble) {}
  }

  //Filtra las palabras prohibidas de la cadena de entrada
  private String filtrar (String entrada) {
    Pattern patron;
    Matcher encaja;

    if (palabras!=null) {
      for (int i=0; i<palabras.size(); i++) {
        patron = Pattern.compile(((String)palabras.get(i)));
        encaja = patron.matcher(entrada);
        entrada = encaja.replaceAll("");
      }
    }
    return (new String(entrada));
  }

  //Función que borra e/u inicializa el editor
  private void borrarEditor() {
    if (usuarioRem != null) {
      editorChat.setText("");
      MutableAttributeSet attr = new SimpleAttributeSet();
      StyleConstants.setBold(attr, true);
      StyleConstants.setForeground(attr, Color.red);
      try {
        doc.insertString(doc.getLength(), usuarioRem+" le ha invitado a un diálogo privado.\nPulse cerrar para cerrar la conversación.", attr);
      } catch(BadLocationException ble) {}
    }
  }

  //Función para la desconexión y cierre de la aplicación
  private void salir() {
    try {
      synchronized(this){
        out.writeObject((Object)(new Respuesta("terminar",null)));
        out.flush();
      }
      out.close();
      socketConexion.close();
      receptorDatos.stop();
      receptorDatos = null;
      hide();
    } catch (IOException e) {}
  }

  //Función que inicializa los valores de la aplicación
  private void inicializar() {
    this.setTitle(usuarioRem+": Conversación privada.");
    borrarEditor();
  }

}