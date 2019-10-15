package appletclientechat;

import java.awt.*;
import java.awt.event.*;
import java.applet.*;
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
import clientechat.*;

/*********************************************
 * AppletClienteChat, ES UN Applet QUE INCLUYE
 * TODO LO RELACIONADO CON EL CHAT, ACCESO Y
 * COMPROBACIÓN DE DATOS, CONEXIÓN,
 * CONVERSACIONES PÚBLICAS Y PRIVADAS, ECT.
 *********************************************/
public class AppletClienteChat extends Applet {
  boolean isStandalone = false;
  String BD; //Base de datos utilizada para el chat
  int puerto; //puerto de conexión del servidor
  String host; //Máquina de conexión del chat
  String usuario; //Nick de usuario
  String canal; //Canal al que se encuentra conectado
  Socket socketConexionServidor; //Socket de conexión al servidor
  ObjectOutputStream out; //Stream de envío de datos y mensajes
  threadReceptor receptorDatos; //Para recibir datos desde el servidor
  Vector palabras; //Palabras prohibidas para filtrado
  Vector usuarios; //Usuarios conectados al canal actual
  boolean esAdministrador = false; //Indica si es administrador
  boolean esta_moviendo = false; //Indica que se está moviendo el scroll del editor del chat

  //VARIABLES DE CONFIGURACIÓN DEL CHAT
  int color_fuente_r;
  int color_fuente_g;
  int color_fuente_b;
  int color_fondo_r;
  int color_fondo_g;
  int color_fondo_b;
  int color_fuente_listas_r;
  int color_fuente_listas_g;
  int color_fuente_listas_b;
  int color_fondo_listas_r;
  int color_fondo_listas_g;
  int color_fondo_listas_b;
  Color color_fuente, color_fondo, color_fuente_listas, color_fondo_listas; //Obtenidos de los valores anteriores
  boolean permitir_banear;
  boolean permitir_mostrar_info_usuario;
  boolean permitir_configurar_fuentes;
  boolean permitir_cambio_nick;
  boolean mostrar_iconos_botones;
  boolean permitir_palabras_prohibidas;

  //ATRIBUTOS PARA EL FORMATO DE LA FUENTE
  JFrame config; //Dialogo de configuración
  MutableAttributeSet formato = new SimpleAttributeSet(); //formato

  //ATRIBUTOS DEL EDITOR DEL CHAT, SU ESTILO DE DOCUMENTO Y SU KIT DE EDITOR
  JEditorPane editorChat = new JEditorPane();
  protected DefaultStyledDocument doc = new DefaultStyledDocument();
  protected StyledEditorKit kit = new StyledEditorKit();

  //ATRIBUTOS DE CONTENIDO DE LA APLICACIÓN
  JPanel contentPane = new JPanel();
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
  JPanel panelMenu = new JPanel();
  GridLayout gridLayout1 = new GridLayout();
  JTextArea textoInfo = new JTextArea();
  JButton botonInfo = new JButton();
  JButton botonConfig = new JButton();
  JButton botonBanear = new JButton();

  //Obtiene un parámetro de entrada
  public String getParameter(String key, String def) {
    return isStandalone ? System.getProperty(key, def) :
      (getParameter(key) != null ? getParameter(key) : def);
  }

  /* CONSTRUCTOR DEL APPLET */
  public AppletClienteChat() {
  }

  /* PARADA O REINICIO DEL APPLET */
  public void stop() {
    this.desconectar();
  }

  /* INICIALIZADO DEL APPLET */
  public void init() {
    try {
      //OBTENEMOS LOS PARÁMETROS DE CONFIGURACIÓN
      BD = this.getParameter("BD","ejemploDBchat");
      puerto = Integer.parseInt(this.getParameter("puerto","8008"));
      host = this.getParameter("host","localhost");
      color_fuente_r = Integer.parseInt(this.getParameter("color_fuente_r","020"));
      color_fuente_g = Integer.parseInt(this.getParameter("color_fuente_g","084"));
      color_fuente_b = Integer.parseInt(this.getParameter("color_fuente_b","156"));
      color_fuente = new Color (color_fuente_r, color_fuente_g, color_fuente_b); //color de las fuentes
      color_fondo_r = Integer.parseInt(this.getParameter("color_fondo_r","173"));
      color_fondo_g = Integer.parseInt(this.getParameter("color_fondo_g","204"));
      color_fondo_b = Integer.parseInt(this.getParameter("color_fondo_b","220"));
      color_fondo = new Color (color_fondo_r, color_fondo_g, color_fondo_b); //color del fondo
      color_fuente_listas_r = Integer.parseInt(this.getParameter("color_fuente_listas_r","020"));
      color_fuente_listas_g = Integer.parseInt(this.getParameter("color_fuente_listas_g","084"));
      color_fuente_listas_b = Integer.parseInt(this.getParameter("color_fuente_listas_b","156"));
      color_fuente_listas = new Color (color_fuente_listas_r, color_fuente_listas_g, color_fuente_listas_b); //color de fuente de las listas
      color_fondo_listas_r = Integer.parseInt(this.getParameter("color_fondo_listas_r","238"));
      color_fondo_listas_g = Integer.parseInt(this.getParameter("color_fondo_listas_g","252"));
      color_fondo_listas_b = Integer.parseInt(this.getParameter("color_fondo_listas_b","252"));
      color_fondo_listas = new Color (color_fondo_listas_r, color_fondo_listas_g, color_fondo_listas_b); //color de las listas
      if (this.getParameter("permitir_banear","NO").equalsIgnoreCase("SI"))
        permitir_banear = true;
      else
        permitir_banear = false;
      if (this.getParameter("permitir_mostrar_info_usuario","NO").equalsIgnoreCase("SI"))
        permitir_mostrar_info_usuario = true;
      else
        permitir_mostrar_info_usuario = false;
      if (this.getParameter("permitir_configurar_fuentes","NO").equalsIgnoreCase("SI"))
        permitir_configurar_fuentes = true;
      else
        permitir_configurar_fuentes = false;
      if (this.getParameter("permitir_cambio_nick","NO").equalsIgnoreCase("SI"))
        permitir_cambio_nick = true;
      else
        permitir_cambio_nick = false;
      if (this.getParameter("mostrar_iconos_botones","NO").equalsIgnoreCase("SI"))
        mostrar_iconos_botones = true;
      else
        mostrar_iconos_botones = false;
      if (this.getParameter("permitir_palabras_prohibidas","NO").equalsIgnoreCase("SI"))
        permitir_palabras_prohibidas = true;
      else
        permitir_palabras_prohibidas = false;
    }
    catch(Exception e) {
      e.printStackTrace();
    }
    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }
  }

  //INICIALIZACIÓN DE COMPONENTES, PRESENTA LOS COMPONENTE DE ACCESO AL CHAT
  private void jbInit() throws Exception {
    //INICIALIZACIÓN
    contentPane.setLayout(flowLayout1);
    contentPane.setPreferredSize(new Dimension(670, 435));
    contentPane.setBackground(color_fondo);
    this.setSize(new Dimension(672, 437));
    jLabel1.setPreferredSize(new Dimension(390, 27));
    jLabel1.setText("(*) Obligatorio");
    jLabel1.setForeground(color_fuente);
    jLabel2.setMaximumSize(new Dimension(76, 17));
    jLabel2.setMinimumSize(new Dimension(76, 17));
    jLabel2.setPreferredSize(new Dimension(390, 27));
    jLabel2.setForeground(color_fuente);
    jLabel2.setToolTipText("");
    jLabel2.setText("Opcional (Sin espacios)");
    jLabel3.setPreferredSize(new Dimension(390, 27));
    jLabel3.setText("(*) Obligatorio");
    jLabel3.setForeground(color_fuente);
    //ETIQUETA CANALES
    etiquetaCanales.setText("Lista de canales");
    etiquetaCanales.setPreferredSize(new Dimension(650, 17));
    etiquetaCanales.setMinimumSize(new Dimension(47, 17));
    etiquetaCanales.setMaximumSize(new Dimension(4700, 170));
    etiquetaCanales.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaCanales.setForeground(color_fuente);
    //LISTA DE CANALES
    listaCanales.setFont(new java.awt.Font("Dialog", 1, 12));
    listaCanales.setPreferredSize(new Dimension(650, 18));
    listaCanales.setToolTipText("");
    listaCanales.setFixedCellHeight(18);
    listaCanales.setSelectionBackground(color_fuente_listas);
    listaCanales.setSelectionForeground(color_fondo_listas);
    listaCanales.setForeground(color_fuente_listas);
    listaCanales.setBackground(color_fondo_listas);
    listaCanales.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
    //listaCanales.setBackground(color_fondo_listas);
    //ETIQUETA LOGIN
    etiquetaLogin.setText("Login de usuario: ");
    etiquetaLogin.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaLogin.setForeground(color_fuente);
    //TEXTO PASSWORD
    textoPassword.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoPassword.setPreferredSize(new Dimension(150, 21));
    textoPassword.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoPassword_keyPressed(e);
      }
    });
    textoPassword.setForeground(color_fuente_listas);
    textoPassword.setBackground(color_fondo_listas);
    textoPassword.setSelectedTextColor(color_fondo_listas);
    textoPassword.setSelectionColor(color_fuente_listas);
    //ETIQUETA PASSWORD
    etiquetaPassword.setText("Clave de usuario: ");
    etiquetaPassword.setForeground(color_fuente);
    //TEXTO NICK
    textoNick.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoNick.setPreferredSize(new Dimension(150, 21));
    textoNick.setMargin(new Insets(5, 5, 5, 5));
    textoNick.setForeground(color_fuente_listas);
    textoNick.setBackground(color_fondo_listas);
    textoNick.setSelectedTextColor(color_fondo_listas);
    textoNick.setSelectionColor(color_fuente_listas);
    textoNick.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoNick_keyPressed(e);
      }
    });
    //ETIQUETA NICK
    etiquetaNick.setText("Elige un nick: ");
    etiquetaNick.setPreferredSize(new Dimension(99, 17));
    etiquetaNick.setFont(new java.awt.Font("Dialog", 1, 12));
    etiquetaNick.setForeground(color_fuente);
    //TEXTO USUARIO
    textoUsuario.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoUsuario.setPreferredSize(new Dimension(150, 21));
    textoUsuario.setRequestFocusEnabled(true);
    textoUsuario.setForeground(color_fuente_listas);
    textoUsuario.setBackground(color_fondo_listas);
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
    PanelCabecera.setText("<html><head></head><body><img width=650 height=50 src='"+appletclientechat.AppletClienteChat.class.getResource("../imagenes/titulo.gif")+"'></body></html>");
    //TEXTO ESCRIBIR EN EL CHAT
    textoEscribir.setBorder(BorderFactory.createLineBorder(color_fuente));
    textoEscribir.setPreferredSize(new Dimension(515, 21));
    textoEscribir.setRequestFocusEnabled(true);
    textoEscribir.setForeground(color_fuente_listas);
    textoEscribir.setBackground(color_fondo_listas);
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
      botonDesconectar.setIcon(new ImageIcon(appletclientechat.AppletClienteChat.class.getResource("../imagenes/desconectar.gif")));
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
    botonBorrarMensajes.setForeground(color_fuente);
    botonBorrarMensajes.setBackground(color_fondo);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonBorrarMensajes.setIcon(new ImageIcon(appletclientechat.AppletClienteChat.class.getResource("../imagenes/borrar.gif")));
    botonBorrarMensajes.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonBorrarMensajes_actionPerformed(e);
      }
    });
    //PANEL DEL MENÚ
    panelMenu.setBorder(BorderFactory.createEtchedBorder());
    panelMenu.setPreferredSize(new Dimension(650, 30));
    panelMenu.setLayout(gridLayout1);
    //BOTÓN CONECTAR A UN CANAL
    botonConectar.setEnabled(false);
    botonConectar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonConectar.setBorder(BorderFactory.createEtchedBorder());
    botonConectar.setToolTipText("Conectarse al canal seleccionado");
    botonConectar.setHorizontalTextPosition(SwingConstants.RIGHT);
    botonConectar.setText("Conectar");
    botonConectar.setForeground(color_fuente);
    botonConectar.setBackground(color_fondo);
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonConectar.setIcon(new ImageIcon(appletclientechat.AppletClienteChat.class.getResource("../imagenes/conectar.gif")));
    botonConectar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonConectar_actionPerformed(e);
      }
    });
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
      botonInfo.setIcon(new ImageIcon(appletclientechat.AppletClienteChat.class.getResource("../imagenes/info.gif")));
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
    if (mostrar_iconos_botones) //Muestra el icono si se permite
      botonConfig.setIcon(new ImageIcon(appletclientechat.AppletClienteChat.class.getResource("../imagenes/config.gif")));
    botonConfig.setForeground(color_fuente);
    botonConfig.setBackground(color_fondo);
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
    if (permitir_mostrar_info_usuario)
      panelMenu.add(botonInfo, null);
    if (permitir_configurar_fuentes)
      panelMenu.add(botonConfig, null);
    panelMenu.setBackground(color_fondo);
    contentPane.add(etiquetaCanales, null);
    contentPane.add(textoInfo, null);
    this.add(contentPane);

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

    //INICIALIZAMOS EL FORMATO DE TEXTO AL ESTÁNDAR
    StyleConstants.setFontFamily(formato, "Serif");
    StyleConstants.setFontSize(formato, 12);
    StyleConstants.setItalic(formato, false);
    StyleConstants.setBold(formato, false);
    StyleConstants.setForeground(formato, color_fuente_listas);
    if (permitir_configurar_fuentes)
      inicializarDialogoConfig (); //Inicializa el dialogo config, en su caso
  }

  /* OBTIENE INFORMACIÓN DEL APPLET */
  public String getAppletInfo() {
    return "Este applet es un cliente de chat para un servidor FordWard ejecutado en el mismo servidor.";
  }

  /* OBTIENE INFORMACIÓN DE LOS PARÁMETROS */
  public String[][] getParameterInfo() {
    String[][] pinfo =
      {
      {"BD", "String", "Base de datos del chat"},
      {"puerto", "int", "Puerto de conexión al servidor"},
      {"host", "String", "Máquina de conexión"},
      };
    return pinfo;
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

  //PULSAR EL BOTÓN 'CONFIGURAR'
  void botonConfig_actionPerformed(ActionEvent e) {
    //DialogoConfig frame = new DialogoConfig(this,"Configuración",true);
    config.setVisible(true);
  }

  //HACER DOBLECLICK SOBRE LA 'LISTAUSUARIOS'; CREA UN PRIVADO
  void listaUsuarios_mouseClicked(MouseEvent e) {
    if (permitir_mostrar_info_usuario) { //Si se permite ver la información de usuario
      if (e.getClickCount()>=2)
        botonInfo_actionPerformed(null);
    }
  }

  //PULSAR EL BOTÓN 'INFO', CREA EL FRAME INFO
  void botonInfo_actionPerformed(ActionEvent e) {
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

  //PULSAR EL BOTÓN BANEAR
  private void botonBanear_actionPerformed(ActionEvent e) {
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
      if (permitir_cambio_nick) { //se permite o no cambio de nick
        contentPane.add(etiquetaNick, null);
        contentPane.add(textoNick, null);
        contentPane.add(jLabel2, null);
      }
      textoUsuario.requestFocus();
      contentPane.repaint();
      contentPane.validate();
    } catch (IOException ex) {}
  }

  //Funcion que envia los datos de un nuevo mensaje filtrando las palabras prohibidas, en su caso
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

  // Filtra las palabras prohibidas de la cadena de entrada si está permitida esta opción,
  //en caso contrario la devuelve en minúculas
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
      System.exit(0);
    } catch (IOException e) {}
  }

  //Función para inicializar el dialogo de configuración
  private void inicializarDialogoConfig () {
    //Creamos el nuevo diálogo de configuración de formatodetexto y lo centramos
    config =  new DialogoConfig("Configuración de la fuente", formato, out, BD, color_fuente, color_fondo, color_fuente_listas, color_fondo_listas);
    Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
    Dimension frameSize = config.getSize();
    if (frameSize.height > screenSize.height)
      frameSize.height = screenSize.height;
    if (frameSize.width > screenSize.width)
      frameSize.width = screenSize.width;
    config.setLocation((100 + screenSize.width - frameSize.width) / 2, (100 +screenSize.height - frameSize.height) / 2);
  }
}