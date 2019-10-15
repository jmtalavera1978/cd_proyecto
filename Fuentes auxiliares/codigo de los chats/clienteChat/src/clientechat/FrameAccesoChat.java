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
 * FrameAccesoChat, ES UN JFrame QUE INCLUYE
 * TODO LO RELACIONADO CON EL CHAT, ACCESO Y
 * COMPROBACIÓN DE DATOS, CONEXIÓN,
 * CONVERSACIONES PÚBLICAS Y PRIVADAS, ECT.
 ********************************************/
public class FrameAccesoChat extends JFrame {
  String host; //Máquina de conexión al servidor
  int puerto; //Puerto de conexión al servidor
  String usuario; //Nick de usuario
  String canal; //Canal al que se encuentra conectado
  Socket socketConexionServidor; //Socket de conexión al servidor
  ObjectOutputStream out; //Stream de envío de datos y mensajes
  threadPrivados privados; //Para conexiones de privados
  threadReceptor receptorDatos; //Para recibir datos desde el servidor
  String BD; //Base de datos utilizada
  Vector palabras; //Palabras prohibidas para filtrado
  Vector usuarios; //Usuarios conectados al canal actual
  boolean esAdministrador = false; //Indica si es administrador
  boolean esta_moviendo = false; //Indica que se está moviendo el scroll del editor del chat

  //VARIABLES DE CONFIGURACIÓN DEL CHAT
  Color color_fuente, color_fondo, color_fuente_listas, color_fondo_listas;
  String nombreChat;
  boolean permitir_banear;
  boolean permitir_mostrar_info_usuario;
  boolean permitir_configurar_fuentes;
  boolean permitir_cambio_nick;
  boolean mostrar_iconos_botones;
  boolean permitir_palabras_prohibidas;
  boolean permitir_privados;

  //ATRIBUTOS PARA EL FORMATO DE LA FUENTE
  JFrame config; //Dialogo de configuración
  MutableAttributeSet formato = new SimpleAttributeSet(); //formato

  //ATRIBUTOS DEL EDITOR DEL CHAT, SU ESTILO DE DOCUMENTO Y SU KIT DE EDITOR
  JEditorPane editorChat = new JEditorPane();
  protected DefaultStyledDocument doc = new DefaultStyledDocument();
  protected StyledEditorKit kit = new StyledEditorKit();

  //ATRIBUTOS DE CONTENIDO DE LA APLICACIÓN
  JPanel contentPane;
  FlowLayout flowLayout1 = new FlowLayout();
  JTextField textoUsuario = new JTextField();
  JLabel etiquetaNick = new JLabel();
  JTextField textoNick = new JTextField();
  JLabel etiquetaPassword = new JLabel();
  JLabel etiquetaCanales = new JLabel();
  JPasswordField textoPassword = new JPasswordField();
  JList listaCanales = new JList();
  JScrollPane scrollCanales = new JScrollPane(listaCanales,JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED, JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
  JLabel jLabel3 = new JLabel();
  JLabel jLabel2 = new JLabel();
  JLabel jLabel1 = new JLabel();
  JLabel etiquetaLogin = new JLabel();
  JScrollPane scrollEditor = new JScrollPane(editorChat);
  JList listaUsuarios = new JList();
  JScrollPane scrollUsuarios = new JScrollPane(listaUsuarios,JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED,JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
  JButton botonEnviar = new JButton();
  JEditorPane PanelCabecera = new JEditorPane();
  JTextField textoEscribir = new JTextField();
  TitledBorder titledBorder1;
  TitledBorder titledBorder2;
  JButton botonConectar = new JButton();
  JButton botonDesconectar = new JButton();
  JButton botonBorrarMensajes = new JButton();
  JButton botonPrivado = new JButton();
  JButton botonSalir = new JButton();
  JPanel panelMenu = new JPanel();
  GridLayout gridLayout1 = new GridLayout();
  JTextArea textoInfo = new JTextArea();
  JButton botonInfo = new JButton();
  JButton botonConfig = new JButton();
  JButton botonBanear = new JButton();

  /**********************************
   * CONSTRUCTOR DEL JFRAME DEL CHAT.
   * RECIBE LA BASE DE DATOS, PUERTO,
   * MÁQUINA DEL SERVIDOR Y VARIABLES
   * DE CONFIGURACiÓN DEL CHAT.
   **********************************/
  public FrameAccesoChat(String BD, int puerto, String host, String nombreChat,Color color_fuente,Color color_fondo,Color color_fuente_listas,Color color_fondo_listas,boolean permitir_banear,boolean permitir_mostrar_info_usuario,boolean permitir_configurar_fuentes,boolean permitir_cambio_nick,boolean mostrar_iconos_botones,boolean permitir_palabras_prohibidas,boolean permitir_privados) {
    enableEvents(AWTEvent.WINDOW_EVENT_MASK);
      //Datos de acceso al servidor del chat
      this.BD = BD;
      this.puerto = puerto;
      this.host = host;
      //Datos de configuración del chat
      this.color_fuente = color_fuente;
      this.color_fondo = color_fondo;
      this.color_fuente_listas = color_fuente_listas;
      this.color_fondo_listas = color_fondo_listas;
      this.nombreChat = nombreChat;
      this.permitir_banear = permitir_banear;
      this.permitir_mostrar_info_usuario = permitir_mostrar_info_usuario;
      this.permitir_configurar_fuentes = permitir_configurar_fuentes;
      this.permitir_cambio_nick = permitir_cambio_nick;
      this.mostrar_iconos_botones = mostrar_iconos_botones;
      this.permitir_palabras_prohibidas = permitir_palabras_prohibidas;
      this.permitir_privados = permitir_privados;
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
    contentPane.setPreferredSize(new Dimension(2725, 300));
    this.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);
    this.setResizable(false);
    this.setSize(new Dimension(672, 437));
    this.setTitle(nombreChat);
    jLabel1.setPreferredSize(new Dimension(390, 27));
    jLabel1.setText("(*) Obligatorio");
    jLabel1.setFont(new java.awt.Font("Dialog", 1, 12));
    jLabel1.setForeground(color_fuente);
    jLabel2.setMaximumSize(new Dimension(76, 17));
    jLabel2.setMinimumSize(new Dimension(76, 17));
    jLabel2.setPreferredSize(new Dimension(390, 27));
    jLabel2.setToolTipText("");
    jLabel2.setText("Opcional (Sin espacios)");
    jLabel2.setFont(new java.awt.Font("Dialog", 1, 12));
    jLabel2.setForeground(color_fuente);
    jLabel3.setPreferredSize(new Dimension(390, 27));
    jLabel3.setText("(*) Obligatorio");
    jLabel3.setFont(new java.awt.Font("Dialog", 1, 12));
    jLabel3.setForeground(color_fuente);
    //ETIQUETA CANALES
    etiquetaCanales.setText("Lista de canales");
    etiquetaCanales.setPreferredSize(new Dimension(650, 17));
    etiquetaCanales.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaCanales.setForeground(color_fuente);
    //LISTA DE CANALES
    listaCanales.setFont(new java.awt.Font("Dialog", 1, 12));
    listaCanales.setSelectionBackground(color_fuente_listas);
    listaCanales.setSelectionForeground(color_fondo_listas);
    listaCanales.setForeground(color_fuente_listas);
    listaCanales.setBackground(color_fondo_listas);
    listaCanales.setPreferredSize(new Dimension(650, 18));
    listaCanales.setToolTipText("");
    listaCanales.setFixedCellHeight(18);
    listaCanales.setSelectionBackground(Color.blue);
    listaCanales.setSelectionForeground(Color.white);
    listaCanales.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
    //ETIQUETA LOGIN
    etiquetaLogin.setText("Login de usuario: ");
    etiquetaLogin.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaLogin.setForeground(color_fuente);
    //TEXTO PASSWORD
    textoPassword.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoPassword.setPreferredSize(new Dimension(150, 21));
    textoPassword.setForeground(color_fuente_listas);
    textoPassword.setBackground(color_fondo_listas);
    textoPassword.setFont(new java.awt.Font("Dialog", 1, 12));
    textoPassword.setSelectedTextColor(color_fondo_listas);
    textoPassword.setSelectionColor(color_fuente_listas);
    textoPassword.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoPassword_keyPressed(e);
      }
    });
    //ETIQUETA PASSWORD
    etiquetaPassword.setText("Clave de usuario: ");
    etiquetaPassword.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaPassword.setForeground(color_fuente);
    //TEXTO NICK
    textoNick.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoNick.setPreferredSize(new Dimension(150, 21));
    textoNick.setMargin(new Insets(5, 5, 5, 5));
    textoNick.setForeground(color_fuente_listas);
    textoNick.setBackground(color_fondo_listas);
    textoNick.setFont(new java.awt.Font("SansSerif", 1, 12));
    textoNick.setSelectedTextColor(color_fondo_listas);
    textoNick.setSelectionColor(color_fuente_listas);
    textoNick.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoNick_keyPressed(e);
      }
    });
    //ETIQUETA NICK
    etiquetaNick.setText("Elige un nick: ");
    etiquetaNick.setPreferredSize(new Dimension(100, 17));
    etiquetaNick.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaNick.setForeground(color_fuente);
    //TEXTO USUARIO
    textoUsuario.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoUsuario.setPreferredSize(new Dimension(150, 21));
    textoUsuario.setRequestFocusEnabled(true);
    textoUsuario.setForeground(color_fuente_listas);
    textoUsuario.setBackground(color_fondo_listas);
    textoUsuario.setFont(new java.awt.Font("SansSerif", 1, 12));
    textoUsuario.setSelectedTextColor(color_fondo_listas);
    textoUsuario.setSelectionColor(color_fuente_listas);
    textoUsuario.requestFocus();
    //EDITOR DEL CHAT
    editorChat.setBorder(null);
    editorChat.setPreferredSize(new Dimension(350, 250));
    editorChat.setEditorKit(kit);
    editorChat.setDocument(doc);
    editorChat.setEditable(false);
    editorChat.setBackground(color_fondo_listas);
    borrarEditor(); //Inicializamos el editor del chat
    //LISTA DE USUARIOS
    listaUsuarios.setPreferredSize(new Dimension(130, 16));
    listaUsuarios.setFixedCellHeight(20);
    listaUsuarios.setSelectionBackground(color_fuente_listas);
    listaUsuarios.setSelectionForeground(color_fondo_listas);
    listaUsuarios.setForeground(color_fuente_listas);
    listaUsuarios.setBackground(color_fondo_listas);
    listaUsuarios.setFont(new java.awt.Font("Dialog", 1, 12));
    listaUsuarios.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
    listaUsuarios.addMouseListener(new MouseListener() {
      public void mouseClicked(MouseEvent e) {
        listaUsuarios_mouseClicked(e);
      }
      public void mouseExited(MouseEvent e) {}
      public void mouseEntered (MouseEvent e) {}
      public void mouseReleased (MouseEvent e) {}
      public void mousePressed (MouseEvent e) {}
    });
    //BOTÓN ENVIAR
    botonEnviar.setPreferredSize(new Dimension(130, 21));
    botonEnviar.setText("Enviar datos");
    botonEnviar.setForeground(color_fuente);
    botonEnviar.setBackground(color_fondo);
    botonEnviar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonEnviar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonEnviar_actionPerformed(e);
      }
    });
    //PANEL DE CABECERA DEL CHAT
    PanelCabecera.setBorder(BorderFactory.createLineBorder(color_fuente));
    PanelCabecera.setDebugGraphicsOptions(DebugGraphics.BUFFERED_OPTION);
    PanelCabecera.setPreferredSize(new Dimension(650, 50));
    PanelCabecera.setToolTipText("CABECERA CHAT");
    PanelCabecera.setEditable(false);
    PanelCabecera.setContentType("text/html");
    PanelCabecera.setText("<html><head></head><body><img width=650 height=50 src='"+clientechat.FrameAccesoChat.class.getResource("../imagenes/titulo.gif")+"'></body></html>");
    //TEXTO ESCRIBIR EN EL CHAT
    textoEscribir.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoEscribir.setPreferredSize(new Dimension(515, 21));
    textoEscribir.setRequestFocusEnabled(true);
    textoEscribir.setForeground(color_fuente_listas);
    textoEscribir.setBackground(color_fondo_listas);
    textoEscribir.setFont(new java.awt.Font("SansSerif", 1, 12));
    textoEscribir.setSelectedTextColor(color_fondo_listas);
    textoEscribir.setSelectionColor(color_fuente_listas);
    textoEscribir.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoEscribir_keyPressed(e);
      }
    });
    //SCROLL DEL EDITOR DEL CHAT
    scrollEditor.setHorizontalScrollBarPolicy(JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
    scrollEditor.setBorder(BorderFactory.createLineBorder(color_fuente));
    scrollEditor.setPreferredSize(new Dimension(515, 250));
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
    //BOTON DESCONECTAR
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonDesconectar.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/desconectar.gif")));
    botonDesconectar.setEnabled(false);
    botonDesconectar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonDesconectar.setAlignmentY((float) 0.0);
    botonDesconectar.setBorder(BorderFactory.createEtchedBorder());
    botonDesconectar.setPreferredSize(new Dimension(75, 27));
    botonDesconectar.setToolTipText("Desconectarse del canal actual");
    botonDesconectar.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonDesconectar.setMargin(new Insets(0, 0, 0, 0));
    botonDesconectar.setText("Desconectar");
    botonDesconectar.setBackground(color_fondo);
    botonDesconectar.setForeground(color_fuente);
    botonDesconectar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonDesconectar_actionPerformed(e);
      }
    });
    //BOTON BORRAR MENSAJES DEL EDITOR
    botonBorrarMensajes.setText("Borrar");
    botonBorrarMensajes.setEnabled(false);
    botonBorrarMensajes.setFont(new java.awt.Font("Dialog", 1, 12));
    botonBorrarMensajes.setAlignmentY((float) 0.0);
    botonBorrarMensajes.setBorder(BorderFactory.createEtchedBorder());
    botonBorrarMensajes.setToolTipText("Borra la lista de mensajes");
    botonBorrarMensajes.setHorizontalTextPosition(SwingConstants.RIGHT);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonBorrarMensajes.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/borrar.gif")));
    botonBorrarMensajes.setForeground(color_fuente);
    botonBorrarMensajes.setBackground(color_fondo);
    botonBorrarMensajes.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonBorrarMensajes_actionPerformed(e);
      }
    });
    //BOTON SALIR DE LA APLICACIÓN
    botonSalir.setText("Salir");
    botonSalir.setAlignmentY((float) 0.0);
    botonSalir.setBorder(BorderFactory.createEtchedBorder());
    botonSalir.setToolTipText("Sale de la aplicación");
    botonSalir.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonSalir.setForeground(color_fuente);
    botonSalir.setBackground(color_fondo);
    botonSalir.setFont(new java.awt.Font("Dialog", 1, 12));
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonSalir.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/salir.gif")));
    botonSalir.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonSalir_actionPerformed(e);
      }
    });
    //PANEL DEL MENÚ
    panelMenu.setBorder(BorderFactory.createEtchedBorder());
    panelMenu.setPreferredSize(new Dimension(650, 30));
    panelMenu.setLayout(gridLayout1);
    panelMenu.setBackground(color_fondo);
    //BOTÓN CONECTAR A UN CANAL
    botonConectar.setEnabled(false);
    botonConectar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonConectar.setBorder(BorderFactory.createEtchedBorder());
    botonConectar.setToolTipText("Conectarse al canal seleccionado");
    botonConectar.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonConectar.setText("Conectar");
    botonConectar.setBackground(color_fondo);
    botonConectar.setForeground(color_fuente);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonConectar.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/conectar.gif")));
    botonConectar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonConectar_actionPerformed(e);
      }
    });
    //BOTÓN DE INICIAR CONVERSACIONES PRIVADAS
    botonPrivado.setEnabled(false);
    botonPrivado.setFont(new java.awt.Font("Dialog", 1, 12));
    botonPrivado.setBorder(BorderFactory.createEtchedBorder());
    botonPrivado.setToolTipText("Comienza una conversación privada con el usuario seleccionado");
    botonPrivado.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonPrivado.setText("Privado");
    botonPrivado.setForeground(color_fuente);
    botonPrivado.setBackground(color_fondo);
    botonPrivado.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonPrivado_actionPerformed(e);
      }
    });
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonPrivado.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/privado.gif")));
    //SCROLL DE LA LISTA DE CANALES
    scrollCanales.setBorder(BorderFactory.createLineBorder(color_fuente));
    scrollCanales.setPreferredSize(new Dimension(650, 165));
    //SCROLL DE LA LISTA DE USUARIOS
    scrollUsuarios.setFont(new java.awt.Font("Monospaced", 0, 12));
    scrollUsuarios.setBorder(BorderFactory.createLineBorder(color_fuente));
    scrollUsuarios.setPreferredSize(new Dimension(130, 250));
    //TEXTO INFO DE CARGA DE CANALES
    textoInfo.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoInfo.setPreferredSize(new Dimension(650, 165));
    textoInfo.setEditable(false);
    textoInfo.setText("Cargando la lista de canales...");
    textoInfo.setForeground(color_fuente_listas);
    textoInfo.setBackground(color_fondo_listas);
    textoInfo.setFont(new java.awt.Font("Dialog", 1, 12));
    textoInfo.setSelectedTextColor(color_fondo_listas);
    textoInfo.setSelectionColor(color_fuente_listas);
    //BOTÓN PARA MOSTRAR INFORMACIÓN DE UN USUARIO
    botonInfo.setEnabled(false);
    botonInfo.setFont(new java.awt.Font("Dialog", 1, 12));
    botonInfo.setBorder(BorderFactory.createEtchedBorder());
    botonInfo.setToolTipText("Muestra información del usuario seleccionado");
    botonInfo.setText("Info");
    botonInfo.setForeground(color_fuente);
    botonInfo.setBackground(color_fondo);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonInfo.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/info.gif")));
    botonInfo.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonInfo_actionPerformed(e);
      }
    });
    //BOTÓN PARA MOSTRAR EL DIALOGO DE CONFIGURACIÓN DEL CHAT
    botonConfig.setEnabled(false);
    botonConfig.setFont(new java.awt.Font("Dialog", 1, 12));
    botonConfig.setBorder(BorderFactory.createEtchedBorder());
    botonConfig.setToolTipText("Configura tu estilo fuentes y colores");
    botonConfig.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonConfig.setText("Configurar");
    botonConfig.setForeground(color_fuente);
    botonConfig.setBackground(color_fondo);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonConfig.setIcon(new ImageIcon(clientechat.FrameAccesoChat.class.getResource("../imagenes/config.gif")));
    botonConfig.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonConfig_actionPerformed(e);
      }
    });
    //BOTÓN PARA BANEAR USUARIOS, SOLO PARA ADMINISTRADORES
    botonBanear.setFont(new java.awt.Font("Dialog", 1, 10));
    botonBanear.setPreferredSize(new Dimension(650, 15));
    botonBanear.setText("Banear a un usuario");
    botonBanear.setForeground(color_fuente);
    botonBanear.setBackground(color_fondo);
    botonBanear.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonBanear_actionPerformed(e);
      }
    });
    //PRESENTACIÓN DE COMPONENTES DE INICIO DE SESIÓN
    contentPane.add(PanelCabecera, null);
    contentPane.add(panelMenu, null);
    panelMenu.add(botonConectar, null);
    panelMenu.add(botonDesconectar, null);
    panelMenu.add(botonBorrarMensajes, null);
    if (permitir_privados) //Si se permiten privados
      panelMenu.add(botonPrivado, null);
    if (permitir_mostrar_info_usuario) //info usuario o no
      panelMenu.add(botonInfo, null);
    if (permitir_configurar_fuentes) //configuración permitida o no
      panelMenu.add(botonConfig, null);
    panelMenu.add(botonSalir, null);
    contentPane.add(etiquetaCanales, null);
    contentPane.add(textoInfo, null);

    // AHORA INICIALIZAMOS LA CONEXIÓN CON EL SERVIDOR CREANDO EL SOCKET
    //Y EL STREAM PARA ENVIAR DATOS
    try{
	socketConexionServidor = new Socket(host, puerto);
        out = new ObjectOutputStream(socketConexionServidor.getOutputStream());
    }
    catch(Exception e){
      JOptionPane.showMessageDialog(null,"No se ha podido conectar con el servidor. \nEs posible que el servidor se encuentre caido.\nPóngase en contacto con el administrador del sistema.","No se ha podido realizar la conexion.", JOptionPane.INFORMATION_MESSAGE);
      System.exit(0); //SI NO PODEMOS INICIALIZAR EL SOCKET SALIMOS
    }

    //CREAMOS EL threadReceptor PARA RECIBIR DATOS DESDE EL SERVIDOR
    receptorDatos = new threadReceptor(socketConexionServidor);
    receptorDatos.start();
    synchronized(this){
      out.writeObject((Object)(new Peticion(BD,"getCanales",null))); //SOLICITAMOS LOS CANALES DISPONIBLES
      out.flush();
    }

    //CREAMOS EL HILO DE ESCUCHA PARA MENSAJES PRIVADOS
    if (permitir_privados) {
      privados = new threadPrivados();
      privados.start();
    }

    //INICIALIZAMOS EL FORMATO DE TEXTO AL ESTÁNDAR
    StyleConstants.setFontFamily(formato, "Serif");
    StyleConstants.setFontSize(formato, 12);
    StyleConstants.setItalic(formato, false);
    StyleConstants.setBold(formato, false);
    StyleConstants.setForeground(formato, Color.black);
    inicializarDialogoConfig (); //Inicializa el dialogo config
  }

  //PULSAR ENTER EN EL 'TEXTOPASSWORD'
  void textoPassword_keyPressed(KeyEvent e) {
    if (e.getKeyCode() == KeyEvent.VK_ENTER) {
      conectar();
    }
  }

  //PULSAR ENTER EN EL 'TEXTONICK'; ESCRIBIR EL NICK; LIMITA A 10 SU TAMAÑO
  void textoNick_keyPressed(KeyEvent e) {
    if (e.getKeyCode() == KeyEvent.VK_ENTER) {
      conectar();
    } else {
      if (textoNick.getText().length()>=12)
        textoNick.setText(textoNick.getText().substring(0,11));
    }
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

  //PULSAR EL BOTÓN SALIR; SALE DEL PROGRAMA
  void botonSalir_actionPerformed(ActionEvent e) {
    if (JOptionPane.showConfirmDialog(null,"¿Seguro que desea salir del chat?","Salir del chat",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION)
      salir();
  }

  //PULSAR EL BOTÓN 'DESCONECTAR'; SE DESCONECTA DE UN CANAL
  void botonDesconectar_actionPerformed(ActionEvent e) {
    if (JOptionPane.showConfirmDialog(null,"¿Seguro que desea salir del canal actual?","Salir del canal",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION)
      desconectar();
  }

  //PULSAR EL BOTÓN 'CONECTAR' A UN CANAL
  void botonConectar_actionPerformed(ActionEvent e) {
      conectar();
  }

  //PULSAR EL BOTÓN 'PRIVADO'
  void botonPrivado_actionPerformed(ActionEvent e) {
    if (permitir_privados)
      privado();
  }

  //PULSAR EL BOTÓN 'CONFIGURAR'
  void botonConfig_actionPerformed(ActionEvent e) {
    if (permitir_configurar_fuentes)
      config.setVisible(true);
  }

  //HACER DOBLECLICK SOBRE LA 'LISTAUSUARIOS'; CREA UN PRIVADO
  void listaUsuarios_mouseClicked(MouseEvent e) {
    if (permitir_privados) { //Si se permiten privados
      if (e.getClickCount()>=2)
        privado();
    }
  }

  //PULSAR EL BOTÓN 'INFO', CREA EL FRAME INFO
  void botonInfo_actionPerformed(ActionEvent e) {
    if (permitir_mostrar_info_usuario) {
      if (listaUsuarios.getSelectedIndex()<0)
        JOptionPane.showMessageDialog(null,"Debe seleccionar un usuario de la lista.","Usuario no seleccionado",JOptionPane.INFORMATION_MESSAGE);
      else {
        FrameInfo frame = new FrameInfo((String)listaUsuarios.getSelectedValue(),(Usuario)usuarios.get(listaUsuarios.getSelectedIndex()), color_fuente, color_fondo);

        Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
        Dimension frameSize = frame.getSize();
        if (frameSize.height > screenSize.height)
          frameSize.height = screenSize.height;
        if (frameSize.width > screenSize.width)
          frameSize.width = screenSize.width;
        frame.setLocation((screenSize.width - frameSize.width) / 2, (screenSize.height - frameSize.height) / 2);
        frame.setVisible(true);
      }
    }
  }

  //PULSAR EL BOTÓN BANEAR
  private void botonBanear_actionPerformed(ActionEvent e) {
    if (permitir_banear) {
      if (listaUsuarios.getSelectedIndex()<0)
        JOptionPane.showMessageDialog(null,"Debe seleccionar un usuario de la lista.","Usuario no seleccionado",JOptionPane.INFORMATION_MESSAGE);
      else {
        //Banea al usuario
        if (JOptionPane.showConfirmDialog(null,"¿Seguro que desea banear al usuario "+listaUsuarios.getSelectedValue()+"?","Banear a un usuario",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION) {
          try {
            synchronized (out) { //Manda un mensaje de baneo de dicho usuario
              out.writeObject((Object)(new Peticion(BD,"Banear",usuarios.get(listaUsuarios.getSelectedIndex()))));
              out.flush();
            }
          } catch (IOException IOex) {}
        }
      }
    }
  }

  //AJUSTE DEL SCROLL DEL EDITOR DEL CHAT
  void scrollEditor_adjustmentValueChanged(AdjustmentEvent e) {
    if (!esta_moviendo)
      scrollEditor.getVerticalScrollBar().setValue(scrollEditor.getVerticalScrollBar().getMaximum());
  }


  /*****************************************
   * CLASE 'threadPrivados' QUE SE ENCARGA
   * DE LA ESCUCHA DE CONEXIONES DE PRIVADOS.
   *****************************************/
   /*PRINCIPIO DE LA CLASE threadPrivados*/
  public class threadPrivados extends Thread{
    //Escucha por un puerto libre, nuevas conexiones de privados
    ServerSocket s=null;

    //Constructor que recibe el socket de conexión y crea el flujo de entrada
    threadPrivados(){
      try{
        s=new ServerSocket(0); //Buscamos un puerto libre para recibir las peticiones de privados
        int puertoPrivado = s.getLocalPort();

        // Mandamos el puerto para privados al servidor,
        //para que esté disponible para los demás usuarios
        synchronized(this){
          out.writeObject((Object)(new Peticion(BD,"setPuertoPrivado",String.valueOf(puertoPrivado))));
          out.flush();
        }
      } catch (IOException ioex) {}
    }

    //Ejecuta la escucha de dicho puerto para lectura de respuestas del servidor
    public void run(){
      Socket sc=null;
      while(true){
        try {
          try{
            sc=s.accept();
          } catch (NullPointerException npex){}
          //AL HABER UNA NUEVA CONEXIÓN, CREAMOS EL FRAME PRIVADO CORRESPONDIENTE
          FramePrivados frame=new FramePrivados(sc, palabras, usuario, formato, color_fuente, color_fondo, color_fuente_listas, color_fondo_listas, mostrar_iconos_botones);
          //Centramos la ventana
          Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
          Dimension frameSize = frame.getSize();
          if (frameSize.height > screenSize.height)
            frameSize.height = screenSize.height;
          if (frameSize.width > screenSize.width)
            frameSize.width = screenSize.width;
          frame.setLocation((100 + screenSize.width - frameSize.width) / 2, (100 +screenSize.height - frameSize.height) / 2);
          //Probamos la conexión con el usuario, si es correcta, visibilizamos el formulario
          if (frame.conectar()) //Crea la conexión y devuelve si ha sido correcta o no
            frame.setVisible(true);
          else
            frame=null;
        } catch (IOException ioex) {ioex.printStackTrace();}
      }
    }
  }
  /*FIN DE LA CLASE threadPrivados*/


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
      while(true){
	try{
          //Obtenemos el tipo de respuesta realizada por el servidor
          String tipo = null;
          Object datos = null;
          try{
            Respuesta res = (Respuesta)in.readObject();
            tipo = res.getTipo(); //Obtenemos el tipo
            datos = res.getDatos(); //Obtenemos los datos de la respuesta
          } catch (ClassNotFoundException cnfe){ cnfe.printStackTrace(); }

          //RESPUESTA TIPO 'GETCANALES'; SE OBTIENEN LOS CANALES DISPONIBLES
          if (tipo.equalsIgnoreCase("getCanales")){
            Vector canales = (Vector)datos;

            //Cargamos la lista de canales en 'listaCanales'
            Vector aux = new Vector();
            if (canales != null) {
              for (int i=0;i<canales.size();i++){
                aux.insertElementAt(((Canal)canales.get(i)).getNombre(),i);
              }
              listaCanales.setListData(aux); //Cargamos la lista de canales
              if (aux.size()>0) { //Mostramos 'listaCanales'
                listaCanales.setSelectedIndex(0);//Seleccionamos el primero de la lista
                botonConectar.setEnabled(true); //Abilitamos el botón conectar
                contentPane.remove(textoInfo); //Eliminamos el 'textoInfo'
                //Mostramos los datos de acceso
                contentPane.add(scrollCanales, null);
                contentPane.add(etiquetaLogin, null);
                contentPane.add(textoUsuario, null);
                contentPane.add(jLabel1, null);
                contentPane.add(etiquetaPassword, null);
                contentPane.add(textoPassword, null);
                contentPane.add(jLabel3, null);
                if (permitir_cambio_nick) { //se permite o no cambio de nick
                  contentPane.add(etiquetaNick, null);
                  contentPane.add(textoNick, null);
                  contentPane.add(jLabel2, null);
                }
                contentPane.validate();
              } else //Si no hay canales que mostrar se crea el mensaje correspondiente
                textoInfo.append("\n\nNo existe ningún canal disponible.");
              //Modificamos los datos del tamaño de 'listaCanales' y el foco.
              listaCanales.setPreferredSize(new Dimension(550,aux.size()*listaCanales.getFixedCellHeight()));
              textoUsuario.requestFocus();

            } else //NO SE HA PODIDO ESTABLECER UNA CONEXIÓN CON LA BASE_DE_DATOS
              textoInfo.append("\n\nSe ha producido un error al acceder a la base de datos.\nCompruebe que el nombre de la base de datos sea el correcto.");


          //RESPUESTA TIPO 'LOGIN'; SE OBTIENE SI EL INICIO DE SESIÓN ES CORRECTO O NO
          } else if (tipo.equalsIgnoreCase("login")){
            String loginCorrecto = (String)datos;

            //INICIO DE SESIÓN CORRECTO
            if (loginCorrecto.equalsIgnoreCase("correcto")){
              //Abilitamos los botones del menú correspondientes
              botonConectar.setEnabled(false);
              botonDesconectar.setEnabled(true);
              botonBorrarMensajes.setEnabled(true);
              botonPrivado.setEnabled(true);
              botonInfo.setEnabled(true);
              botonConfig.setEnabled(true);

              //Borramos todos los controles y mostramos los del chat en sí
              contentPane.removeAll();
              contentPane.repaint();
              contentPane.add(PanelCabecera, null);
              PanelCabecera.repaint();
              contentPane.add(panelMenu, null);
              panelMenu.repaint();
              contentPane.add(scrollEditor, null);
              scrollEditor.repaint();
              contentPane.add(scrollUsuarios, null);
              scrollUsuarios.repaint();
              contentPane.add(textoEscribir, null);
              textoEscribir.repaint();
              contentPane.add(botonEnviar, null);
              botonEnviar.repaint();
              botonSalir.repaint();
              contentPane.validate();
              textoEscribir.requestFocus(); //El foco lo obtiene el 'textoEscribir' para enviar mensajes

            } else //INICIO DE SESIÓN INCORRECTO
              JOptionPane.showMessageDialog(null,"Alguno de los datos introducidos es incorrecto.","Acceso denegado",JOptionPane.INFORMATION_MESSAGE);


          //RESPUESTA TIPO 'GETUSUARIOS'; SE OBTIENE LA LISTA DE USUARIOS CONECTADOS AL CANAL
          } else if (tipo.equalsIgnoreCase("getUsuarios")){
            usuarios = (Vector)datos;

            Vector aux = new Vector();
            //Mostramos los usuarios conectados
            for (int i=0;i<usuarios.size();i++)
              aux.addElement(((Usuario)usuarios.get(i)).getNick());
            //Redimensionamos la lista de usuarios y la rellenamos
            listaUsuarios.setPreferredSize(new Dimension(130,aux.size()*listaUsuarios.getFixedCellHeight()));
            listaUsuarios.setListData(aux);


          //RESPUESTA TIPO 'GETPALABRAS'; SE OBTIENE LA LISTA DE PALABRAS PROHIBIDAS
          } else if (tipo.equalsIgnoreCase("getPalabras")){
            if (permitir_palabras_prohibidas)
              palabras = (Vector)datos; //Palabras prohibidas, en su caso


          //RESPUESTA TIPO 'GETHOST'; SE OBTIENE LA DIRECCIÓN IP Y PUERTO DE ESCUCHA DEL USUARIO PARA PRIVADOS
          } else if (tipo.equalsIgnoreCase("getHost")){
            if (permitir_privados) {
              InetAddress IPprivado = (InetAddress)((Vector)datos).get(0); //Dirección IP
              int puertoPrivado = Integer.parseInt((String)((Vector)datos).get(1)); //Puerto remoto privado

              if (IPprivado!=null) {
                FramePrivados frame = new FramePrivados(IPprivado,puertoPrivado,palabras,usuario,(String)listaUsuarios.getSelectedValue(),formato, color_fuente, color_fondo, color_fuente_listas, color_fondo_listas, mostrar_iconos_botones);
                //CENTRAMOS LA VENTANA
                Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
                Dimension frameSize = frame.getSize();
                if (frameSize.height > screenSize.height)
                  frameSize.height = screenSize.height;
                if (frameSize.width > screenSize.width)
                  frameSize.width = screenSize.width;
                frame.setLocation((100 + screenSize.width - frameSize.width) / 2, (100 +screenSize.height - frameSize.height) / 2);
                //Probamos la conexión con el usuario, si es correcta, visibilizamos el formulario
                if (frame.conectar())
                  frame.setVisible(true);
                else { //SI NO HAY CONEXIÓN
                  JOptionPane.showMessageDialog(null,"No se ha podido conectar con dicho usuario. \nEs posible que el usuario se haya salido del canal.","No se ha podido realizar la conexion.", JOptionPane.INFORMATION_MESSAGE);
                  frame = null;
                }
              }
            }


          //RESPUESTA TIPO 'GETFORMATOTEXTO'; SE OBTIENE EL FORMATO DE TEXTO DEL USUARIO
          } else if (tipo.equalsIgnoreCase("getFormatoTexto")){
            if (permitir_configurar_fuentes) {
              FormatoTexto ft = (FormatoTexto)datos;//Recibimos una instancia de tipo 'FormatoTexto'
              if (ft!= null && ft.getFuente()!=null && !ft.getFuente().equals("")) { //SI NO ES VACÍO
                //INICIALIXZAMOS EL FORMATO DE TEXTO DEL USUARIO AL RECIBIDO
                StyleConstants.setFontFamily(formato, ft.getFuente());
                StyleConstants.setFontSize(formato, ft.getTam());
                StyleConstants.setItalic(formato, ft.isCursiva());
                StyleConstants.setBold(formato, ft.isNegrita());
                StyleConstants.setForeground(formato,new Color(ft.getColor_r(),ft.getColor_g(),ft.getColor_b()));
                inicializarDialogoConfig ();
              }
            }


          //RESPUESTA TIPO 'ESADMINITRADOR'; SE OBTIENE QUE EL USUARIO ES ADMINISTRADOR PARA EL CANAL ACTUAL
          } else if (tipo.equalsIgnoreCase("esAdministrador")){
            esAdministrador = true; //Si recibimos este tipo de mensaje es que somos un administrador
            if (permitir_banear) {
              contentPane.add(botonBanear,null); //Mostramos el botón para banear
              botonBanear.repaint();
              contentPane.validate();
            }


          //RESPUESTA TIPO 'BANEADO'; SE SALE DEL CANAL ACTUAL PORQUE EL ADMINISTRADOR TE HA BANEADO
          } else if (tipo.equalsIgnoreCase("Baneado")){
            JOptionPane.showMessageDialog(null,"Ha sido baneado de este canal y se desconectará.","Desconexión forzada.", JOptionPane.INFORMATION_MESSAGE);
            desconectar();


          //RESPUESTA TIPO 'MENSAJESISTEMA'; SE OBTIENE UN MENSAJE DE SISTEMA
          } else if (tipo.equalsIgnoreCase("mensajeSistema")){
            escribirSistema ((String)datos);


          //RESPUESTA TIPO 'MENSAJENORMAL'; SE OBTIENE UN MENSAJE DE UN USUARIO
          } else if (tipo.equalsIgnoreCase("mensajeNormal")){
            String mensaje = (String)((Vector)datos).get(0); //Mensaje
            FormatoTexto ftAux = (FormatoTexto)((Vector)datos).get(1); //Formato del mismo
            escribir(mensaje,ftAux);
          }
	} catch(IOException e){}
      }
    }
  }
  /*FIN DE LA CLASE threadReceptor*/


  //Funcion de conexion al servidor del chat
  private void conectar(){
    if (textoUsuario.getText().equals("") || textoPassword.getPassword().equals("") || listaCanales.getSelectedIndex()<0 || textoNick.getText().indexOf(" ")!=-1)
      JOptionPane.showMessageDialog(null,"Debe rellenar todos los datos obligatorios y seleccionar un canal.","No se han podido realizar el login.", JOptionPane.INFORMATION_MESSAGE);
    else { //Si hemos rellenado todos los datos
      try {
        if(textoNick.getText().equals(""))
          usuario = textoUsuario.getText();
        else
          usuario = textoNick.getText();

        //Creamos el vector con los datos a enviar al servidor para comprobación de sesión
        Vector datosLogin = new Vector();
        datosLogin.insertElementAt(usuario,0);
        datosLogin.insertElementAt(textoUsuario.getText(),1);
        datosLogin.insertElementAt(textoPassword.getText(),2);
        datosLogin.insertElementAt((String)listaCanales.getSelectedValue(),3);
        synchronized(out){
          out.writeObject((Object)(new Peticion(BD,"login",datosLogin)));
          out.flush();
        }
      } catch (IOException e) {}
    }
  }

  //Se desconecta del canal actual, cerrando la conexión
  private void desconectar (){
    try {
      synchronized(out){ //Enviamos la petición de desconexión al servidor
        out.writeObject((Object)(new Peticion(BD,"desconectar",null)));
        out.flush();
      }
      //Actualizamos el menú, abilitando los botones correspondientes
      botonConectar.setEnabled(true);
      botonDesconectar.setEnabled(false);
      botonBorrarMensajes.setEnabled(false);
      botonPrivado.setEnabled(false);
      botonInfo.setEnabled(false);
      botonConfig.setEnabled(false);
      //Borramos el editor e inicializamos los valores de nuevo
      borrarEditor();
      textoUsuario.setText("");
      textoPassword.setText("");
      textoNick.setText("");
      //Mostramos los datos de inicio de sesión
      contentPane.removeAll();
      contentPane.add(PanelCabecera, null);
      contentPane.add(panelMenu, null);
      contentPane.add(etiquetaCanales, null);
      contentPane.add(scrollCanales, null);
      contentPane.add(etiquetaLogin, null);
      contentPane.add(textoUsuario, null);
      contentPane.add(jLabel1, null);
      contentPane.add(etiquetaPassword, null);
      contentPane.add(textoPassword, null);
      contentPane.add(jLabel3, null);
      if (permitir_cambio_nick) {
        contentPane.add(etiquetaNick, null);
        contentPane.add(textoNick, null);
        contentPane.add(jLabel2, null);
      }
      textoUsuario.requestFocus();
      contentPane.repaint();
      contentPane.validate();
    } catch (IOException ex) {}
  }

  //Funcion que envia los datos de un nuevo mensaje filtrando las palabras prohibidas
  private void enviarDatos() {
    try {
      //Filtramos el texto a enviar pasándolo a minúsculas
      String textoFiltrado = filtrar(textoEscribir.getText());
      synchronized(this){ //Enviamos el mensaje
        out.writeObject((Object)(new Peticion(BD,"mensajeNormal","[" + usuario + "] " + textoFiltrado)));
        out.flush();
      }
      //Insertamos el texto en el editor del chat para mostrarlo
      insertarTexto ("[" + usuario + "] " + textoFiltrado);
    } catch (IOException ex){}
    textoEscribir.setText(""); //Borramos el texto para escribir mensajes
  }

  // Función para escribir en el 'editorChat' de mensajes del chat
  //con un formato especificado para MENSAJES DEL SISTEMA.
  private void escribirSistema (String texto){
    //Creamos el formato de texto de privado
    MutableAttributeSet attr = new SimpleAttributeSet();
    StyleConstants.setFontFamily(attr,"Serif");
    StyleConstants.setFontSize(attr,12);
    StyleConstants.setBold(attr, true);
    StyleConstants.setForeground(attr, Color.RED);
    try {
      doc.insertString(doc.getLength(), "\n", formato);
      doc.insertString(doc.getLength(), "- "+texto+" -", attr);
    } catch(BadLocationException ble) {}
  }

  //Función que inserta el texto enviado por el usuario directamente
  private void insertarTexto (String texto) {
    try {
      doc.insertString(doc.getLength(), "\n", formato);
      doc.insertString(doc.getLength(), texto, formato);
    } catch(BadLocationException ble) {}
  }

  // Función que escribe el texto recibido de otros usuarios
  //dado su formato de texto particular
  private void escribir (String texto, FormatoTexto ft) {
    MutableAttributeSet attr = new SimpleAttributeSet();
    if (ft!=null) { //Obtenemos el formato de texto
      StyleConstants.setFontFamily(attr,ft.getFuente());
      StyleConstants.setFontSize(attr,ft.getTam());
      StyleConstants.setItalic(attr,ft.isCursiva());
      StyleConstants.setBold(attr, ft.isNegrita());
      StyleConstants.setForeground(attr, new Color(ft.getColor_r(),ft.getColor_g(),ft.getColor_b()));
    }
    try { //Insertamos el texto con dicho formato
      doc.insertString(doc.getLength(), "\n", attr);
      doc.insertString(doc.getLength(), texto, attr);
    } catch(BadLocationException ble) {}
  }

  //Filtra las palabras prohibidas de la cadena de entrada
  private String filtrar (String entrada) {
    if (permitir_palabras_prohibidas) {
      Pattern patron; //patrón de la búsqueda
      Matcher encaja; //Encaje de la búsqueda

      for (int i=0; i<palabras.size(); i++) {
        patron = Pattern.compile(((String)palabras.get(i)).toLowerCase());
        encaja = patron.matcher(entrada.toLowerCase()); //Lo marcamos pasándolo a minúsculas
        entrada = encaja.replaceAll(""); //Eliminamos todos los patrones encontrados
      }
      return (entrada);
    } else
      return (entrada.toLowerCase());
  }

  //Función que borra e/u inicializa el editor
  private void borrarEditor() {
    editorChat.setText(""); //Eliminamos el contenido del editor con los mensajes
    MutableAttributeSet attr = new SimpleAttributeSet();
    StyleConstants.setFontFamily(attr,"Serif");
    StyleConstants.setFontSize(attr,12);
    StyleConstants.setBold(attr, true);
    StyleConstants.setForeground(attr, Color.RED);
    try {
      doc.insertString(doc.getLength(), "- Bienvenido al chat... -", attr);
    } catch(BadLocationException ble) {}
  }

  //Función para la desconexión y cierre de la aplicación
  private void salir() {
    try {
      //Enviamos la petición de salida
      synchronized(out){
        out.writeObject((Object)(new Peticion(BD,"salir",null)));
        out.flush();
      }
      //Cerramos todo
      out.close();
      socketConexionServidor.close();
      receptorDatos=null;
      if (permitir_privados)
        privados=null;
      System.exit(0);
    } catch (IOException e) {}
  }

  //Función para crear diálogos privados
  private void privado () {
    if (permitir_privados){
      //Comprobamos si hay usuario elegido
      if (listaUsuarios.getSelectedIndex()<0)
        JOptionPane.showMessageDialog(null,"Debe seleccionar un usuario de la lista.","Usuario no seleccionado",JOptionPane.INFORMATION_MESSAGE);
      else {
        //Enviamos la petición de privado para dicho usuario; solicitando su puerto y host de conexión
        try {
          synchronized (out) {
            out.writeObject((Object)(new Peticion(BD,"getHost",usuarios.get(listaUsuarios.getSelectedIndex()))));
            out.flush();
          }
        } catch (IOException IOex) {}
      }
    }
  }

  //Función para inicializar el dialogo de configuración
  private void inicializarDialogoConfig () {
    //Creamos el nuevo diálogo de configuración de formatodetexto y lo centramos
    config =  new DialogoConfig("Configuración de la fuente",formato, out, BD, color_fuente, color_fondo, color_fuente_listas, color_fondo_listas);
    Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
    Dimension frameSize = config.getSize();
    if (frameSize.height > screenSize.height)
      frameSize.height = screenSize.height;
    if (frameSize.width > screenSize.width)
      frameSize.width = screenSize.width;
    config.setLocation((100 + screenSize.width - frameSize.width) / 2, (100 +screenSize.height - frameSize.height) / 2);
  }

}