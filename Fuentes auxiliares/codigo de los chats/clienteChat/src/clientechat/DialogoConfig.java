package clientechat;

import java.awt.*;
import java.io.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.border.*;
import javax.swing.event.*;
import javax.swing.text.*;
import java.beans.*;
import accesoBD.*;
import mensajes.*;

/********************************************
 * DialogoConfig, ES UN JDialog QUE MUESTRA Y
 * PERMITE EL CAMBIO DEL FORMATO DE TEXTO DEL
 * USUARIO.
 ********************************************/
public class DialogoConfig extends JFrame {
  //Fuentes disponibles para seleccionar
  String fuentes[] = {"Dialog","SansSerif","Serif","MonoSpaced","DialogInput"};
  MutableAttributeSet formato; //Formato actual del usuario
  ObjectOutputStream out; //Flujo de salida de información, para grabar la nueva configuración
  String BD; //Base de datos a la que está conectada el usuario

  //VARIABLES DE CONFIGURACIÓN DEL CHAT
  Color color_fuente, color_fondo, color_fuente_listas, color_fondo_listas;

  //ATRIBUTOS DE CONTENIDO DE LA APLICACIÓN
  JPanel contentPane = new JPanel();
  FlowLayout flowLayout1 = new FlowLayout();
  JList listaFuentes = new JList();
  TitledBorder titledBorder1;
  Border border1;
  TitledBorder titledBorder2;
  JCheckBox checkNegrita = new JCheckBox();
  JCheckBox checkCursiva = new JCheckBox();
  JPanel panelPropiedades = new JPanel();
  JLabel pruebaFuente = new JLabel();
  JPanel panelTamaño = new JPanel();
  Border border2;
  TitledBorder titledBorder3;
  JTextField textoTamaño = new JTextField();
  BorderLayout borderLayout1 = new BorderLayout();
  JLabel etVacia = new JLabel();
  JColorChooser selectorColor = new JColorChooser();
  JLabel etVacia1 = new JLabel();
  JButton botonGuardar = new JButton();
  JLabel jLabel1 = new JLabel();
  JButton botonCancelar = new JButton();

  /***************************************
   * CONSTRUCTOR DEL JDIALOG.
   *  RECIBE:
   * -frame: frame principal del q depende
   * -title: titulo de la ventana
   * -modal: modo de apertura
   * -formato: formato inicial del usuario
   * -out: flujo para peticiones al servidor
   * -BD: base de datos de conexión
   ***************************************/
  public DialogoConfig(String title, MutableAttributeSet formato, ObjectOutputStream out, String BD, Color color_fuente, Color color_fondo, Color color_fuente_listas, Color color_fondo_listas) {
    this.formato = formato;
    this.out = out;
    this.BD = new String(BD);
    //Datos de configuración del chat
    this.color_fuente = color_fuente;
    this.color_fondo = color_fondo;
    this.color_fuente_listas = color_fuente_listas;
    this.color_fondo_listas = color_fondo_listas;
    try {
      jbInit();
      pack();
    }
    catch(Exception ex) {
      ex.printStackTrace();
    }
  }

  void jbInit() throws Exception {
    selectorColor.setFont(new java.awt.Font("Dialog", 1, 12));
    //INICIALIZAMOS LOS COMPONENTES
    titledBorder1 = new TitledBorder("");
    border1 = BorderFactory.createLineBorder(color_fuente,1);
    titledBorder2 = new TitledBorder(BorderFactory.createLineBorder(color_fuente,2),"Fuentes");
    border2 = BorderFactory.createLineBorder(color_fuente,1);
    titledBorder3 = new TitledBorder(border2,"Tamaño");
    this.setContentPane(contentPane);
    this.setResizable(false);
    this.setTitle("Configuración");
    contentPane.setLayout(flowLayout1);
    contentPane.setBackground(color_fondo);
    contentPane.setPreferredSize(new Dimension(450, 570));
    //LISTA DE FUENTES
    listaFuentes.setFont(new java.awt.Font("Monospaced", 1, 12));
    listaFuentes.setBorder(titledBorder2);
    listaFuentes.setPreferredSize(new Dimension(100, 150));
    listaFuentes.setToolTipText("");
    listaFuentes.setSelectionBackground(color_fuente_listas);
    listaFuentes.setSelectionForeground(color_fondo_listas);
    listaFuentes.setBackground(color_fondo_listas);
    listaFuentes.setForeground(color_fuente_listas);
    listaFuentes.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
    listaFuentes.setVisibleRowCount(5);
    listaFuentes.addListSelectionListener(new javax.swing.event.ListSelectionListener() {
      public void valueChanged(ListSelectionEvent e) {
        listaFuentes_valueChanged(e);
      }
    });
    listaFuentes.setListData(fuentes);
    //CHECK NEGRITA
    checkNegrita.setFont(new java.awt.Font("Dialog", 1, 12));
    checkNegrita.setBorder(null);
    checkNegrita.setPreferredSize(new Dimension(150, 25));
    checkNegrita.setText("Negrita");
    checkNegrita.setForeground(color_fuente);
    checkNegrita.setBackground(color_fondo);
    checkNegrita.addChangeListener(new javax.swing.event.ChangeListener() {
      public void stateChanged(ChangeEvent e) {
        checkNegrita_stateChanged(e);
      }
    });
    //CHECK CURSIVA
    checkCursiva.setFont(new java.awt.Font("Dialog", 1, 12));
    checkCursiva.setPreferredSize(new Dimension(150, 25));
    checkCursiva.setText("Cursiva");
    checkCursiva.setForeground(color_fuente);
    checkCursiva.setBackground(color_fondo);
    checkCursiva.addChangeListener(new javax.swing.event.ChangeListener() {
      public void stateChanged(ChangeEvent e) {
        checkCursiva_stateChanged(e);
      }
    });
    //PANEL PROPIEDADES
    panelPropiedades.setPreferredSize(new Dimension(270, 150));
    panelPropiedades.setBackground(color_fondo);
    //PRUEBA DE FUENTE
    pruebaFuente.setPreferredSize(new Dimension(370, 17));
    pruebaFuente.setText("Texto de prueba... aAbBcCdDeEfFgGHhiIjJkKlL... 1234567890...");
    //PANEL TAMAÑO
    panelTamaño.setBorder(titledBorder3);
    panelTamaño.setPreferredSize(new Dimension(150, 50));
    panelTamaño.setToolTipText("");
    panelTamaño.setLayout(borderLayout1);
    panelTamaño.setBackground(color_fondo);
    //TEXTO TAMAÑO DE LA FUENTE
    textoTamaño.setFont(new java.awt.Font("SansSerif", 1, 12));
    textoTamaño.setPreferredSize(new Dimension(100, 21));
    textoTamaño.setText("12");
    textoTamaño.setForeground(color_fuente_listas);
    textoTamaño.setBackground(color_fondo_listas);
    textoTamaño.setSelectedTextColor(color_fondo_listas);
    textoTamaño.setSelectionColor(color_fuente_listas);
    textoTamaño.addKeyListener(new KeyAdapter() {
      public void keyPressed(KeyEvent e) {
        textoTamaño_keyPressed(e);
      }
    });
    //ETIQUETA VACÍA
    etVacia.setFont(new java.awt.Font("Dialog", 1, 12));
    etVacia.setPreferredSize(new Dimension(90, 17));
    etVacia.setToolTipText("");
    etVacia.setText("   (entre 10 y 14)");
    etVacia.addInputMethodListener(new java.awt.event.InputMethodListener() {
      public void inputMethodTextChanged(InputMethodEvent e) {
      }
      public void caretPositionChanged(InputMethodEvent e) {
        etVacia_caretPositionChanged(e);
      }
    });
    etVacia.setBackground(color_fondo);
    etVacia.setForeground(color_fuente);
    etVacia1.setToolTipText("");
    etVacia1.setPreferredSize(new Dimension(270, 21));
    //SELECTOR DE COLOR
    selectorColor.setBackground(color_fondo);
    selectorColor.setForeground(color_fuente);
    selectorColor.setToolTipText("Selecciona el color de fuente");
    for (int i=0;i<selectorColor.getComponentCount();i++) {
      selectorColor.getComponent(i).setBackground(color_fondo);
      selectorColor.getComponent(i).setForeground(color_fuente);
    }
    //BOTON GUARDAR
    botonGuardar.setText("Guardar valores");
    botonGuardar.setBackground(color_fondo);
    botonGuardar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonGuardar.setForeground(color_fuente);
    botonGuardar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonGuardar_actionPerformed(e);
      }
    });
    jLabel1.setPreferredSize(new Dimension(50, 17));
    //BOTON CANCELAR
    botonCancelar.setText("Cerrar");
    botonCancelar.setBackground(color_fondo);
    botonCancelar.setFont(new java.awt.Font("Dialog", 1, 12));
    botonCancelar.setForeground(color_fuente);
    botonCancelar.addActionListener(new java.awt.event.ActionListener() {
      public void actionPerformed(ActionEvent e) {
        botonCancelar_actionPerformed(e);
      }
    });
    //INICIALIZA LOS COMPONENTES A MOSTRAR
    contentPane.add(listaFuentes, null);
    contentPane.add(panelPropiedades, null);
    panelPropiedades.add(checkNegrita, null);
    panelPropiedades.add(checkCursiva, null);
    panelPropiedades.add(etVacia1, null);
    panelPropiedades.add(panelTamaño, null);
    contentPane.add(pruebaFuente, null);
    panelTamaño.add(textoTamaño, BorderLayout.CENTER);
    panelTamaño.add(etVacia, BorderLayout.EAST);
    contentPane.add(selectorColor, null);
    contentPane.add(botonGuardar, null);
    contentPane.add(jLabel1, null);
    contentPane.add(botonCancelar,null);
    inicializar (); //INICIALIZAMOS SEGÚN LOS VALORES DE FORMATO RECIBIDO
  }

  //CAMBIAR EL VALOR DE LA FUENTE SELECCIONADA
  void listaFuentes_valueChanged(ListSelectionEvent e) {
    pruebaFuente.setFont(new Font((String)listaFuentes.getSelectedValue(),pruebaFuente.getFont().getStyle(),pruebaFuente.getFont().getSize()));
  }

  //CAMBIAR EL ESTADO DE LA CASILLA NEGRITA
  void checkNegrita_stateChanged(ChangeEvent e) {
    compruebaChecks();
  }

  //CAMBIAR EL ESTADO DE LA CASILLA CURSIVA
  void checkCursiva_stateChanged(ChangeEvent e) {
    compruebaChecks();
  }

  //COMPRUEBA LOS CHECKBOXES DE NEGRITA Y CURSIVA CAMBIANDO EL FORMATO DEL TEXTO DE PRUEBA, EN SU CASO
  private void compruebaChecks () {
    pruebaFuente.setFont(new Font(pruebaFuente.getFont().getFamily(),Font.PLAIN,pruebaFuente.getFont().getSize()));
    if (checkNegrita.isSelected())
      pruebaFuente.setFont(new Font(pruebaFuente.getFont().getFamily(),pruebaFuente.getFont().getStyle()+Font.BOLD,pruebaFuente.getFont().getSize()));
    if (checkCursiva.isSelected())
      pruebaFuente.setFont(new Font(pruebaFuente.getFont().getFamily(),pruebaFuente.getFont().getStyle()+Font.ITALIC,pruebaFuente.getFont().getSize()));
  }

  //ESCRIBIR EN LSA CASILLA DE TAMAÑO DE TEXTO
  void textoTamaño_keyPressed(KeyEvent e) {
    //COMPROBAMOS QUE SE HALLA PULSADO UN NÚMERO VÁLIDO
    if (textoTamaño.getText().length()>0 && e.getKeyCode()>=e.VK_0 && e.getKeyCode()<=e.VK_9) {
      //COMPROBAMOS QUE EL TAMAÑO SE ENCUENTRE ENTRE 10 Y 14
      if (Integer.parseInt(textoTamaño.getText()+e.getKeyChar())>=10 && Integer.parseInt(textoTamaño.getText()+e.getKeyChar())<=14)
        //CAMBIAMOS EL TAMMAÑO DE LA FUENTE DE PRUEBA
        pruebaFuente.setFont(new Font(pruebaFuente.getFont().getFamily(),pruebaFuente.getFont().getStyle(),Integer.parseInt(textoTamaño.getText()+e.getKeyChar())));
    }
  }

  //INICIALIZA LA VENTANA DE CONFIGURACIÓN CON LOS VALORES DEL FORMATO DE TEXTO RECIBIDO
  private void inicializar () {
    String fuente = StyleConstants.getFontFamily(formato);
    //SELECCIONAMOS LA FUENTE PRESELECCIONADA
    if (fuente.equals("Dialog"))
      listaFuentes.setSelectedIndex(0);
    else if (fuente.equals("SansSerif"))
      listaFuentes.setSelectedIndex(1);
    else if (fuente.equals("Serif"))
      listaFuentes.setSelectedIndex(2);
    else if (fuente.equals("MonoSpaced"))
      listaFuentes.setSelectedIndex(3);
    else if (fuente.equals("DialogInput"))
      listaFuentes.setSelectedIndex(4);
    //CHECKEAMOS LAS CASILLAS NEGRITA Y CURSIVA, EN SU CASO
    checkNegrita.setSelected(StyleConstants.isBold(formato));
    checkCursiva.setSelected(StyleConstants.isItalic(formato));
    //RELLENAMOS EL TAMAÑO DEL TEXTO
    //SELECCIONAMOS EL COLOR RECIBIDO
    selectorColor.setColor(StyleConstants.getForeground(formato));
    pruebaFuente.setForeground(StyleConstants.getForeground(formato));
  }

  //PULSAR EL BOTÓN CANCELAR; CANCELA LA CONFIGURACIÓN, INICIALIZA Y ESCONDE LA VENTANA
  void botonCancelar_actionPerformed(ActionEvent e) {
    inicializar();
    hide();
  }

  //PULSAR BOTÓN GUARDAR; GUARDA LA CONFIGURACIÓN ACTUAL
  void botonGuardar_actionPerformed(ActionEvent e) {
    if (JOptionPane.showConfirmDialog(null,"¿Desea guardar la configuración actual?","Guardar configuración",JOptionPane.YES_NO_OPTION)==JOptionPane.YES_OPTION) {
      try {
        if (Integer.parseInt(textoTamaño.getText())>=10 && Integer.parseInt(textoTamaño.getText())<=14) {
        //Grabamos los nuevos valores de formato de texto
        StyleConstants.setFontFamily(formato,(String)listaFuentes.getSelectedValue());
        StyleConstants.setFontSize(formato,Integer.parseInt(textoTamaño.getText()));
        StyleConstants.setItalic(formato,checkCursiva.isSelected());
        StyleConstants.setBold(formato, checkNegrita.isSelected());
        Color color = selectorColor.getColor();
        StyleConstants.setForeground(formato, color);
        //Lo siguiente es para grabar la nueva configuraión en la base de datos del servidor
        FormatoTexto ft = new FormatoTexto((String)listaFuentes.getSelectedValue(), Integer.parseInt(textoTamaño.getText()),checkNegrita.isSelected(),checkCursiva.isSelected(),color.getRed(),color.getGreen(),color.getBlue());
        try {
          synchronized(this){
            out.writeObject((Object)(new Peticion(BD,"setFormatoTexto",ft)));
            out.flush();
          }
        } catch (IOException ioex) {}
        pruebaFuente.setForeground(selectorColor.getColor());
      } else //MENSAJES DE ERROR
        JOptionPane.showMessageDialog(null,"El tamaño del texto debe estar entre 10 y 14","Imposible guardar los datos",JOptionPane.INFORMATION_MESSAGE);
      } catch (NumberFormatException nfe) {
        JOptionPane.showMessageDialog(null,"El formato de tamaño del texto introducido no es correcto","ERROR",JOptionPane.INFORMATION_MESSAGE);
      }
    }
  }

  void etVacia_caretPositionChanged(InputMethodEvent e) {
    pruebaFuente.setForeground(selectorColor.getColor());
  }

}