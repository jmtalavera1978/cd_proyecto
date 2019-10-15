package clientechat;

import java.awt.*;
import accesoBD.*;
import javax.swing.*;

/********************************************
 * FrameInfo, ES UN JFRAME QUE SIRVE PARA
 * VISUALIZAR LOS DATOS DE UN USUARIO.
 ********************************************/
public class FrameInfo extends JFrame {
  Usuario usuario; //USUARIO A PRESENTAR SU INFO
  String nickUsuario; //NICK

  //Parámetros de cofiguración de color
  Color color_fuente, color_fondo;

  JPanel contentPane = new JPanel();
  JLabel etNombre = new JLabel();
  JLabel valorNombre = new JLabel();
  JLabel ValorAlta = new JLabel();
  JLabel etAlta = new JLabel();
  FlowLayout flowLayout1 = new FlowLayout();

  /**********************************
   * CONSTRUCTOR DEL JFRAME DE INFO.
   *  RECIBE:
   * -nick: nick del usuario.
   * -usuario: Usuario a presentar su
   * info.
   **********************************/
  public FrameInfo(String nick, Usuario usuario, Color color_fuente, Color color_fondo) {
    nickUsuario = nick;
    this.usuario = usuario;
    //Datos de configuración del chat
    this.color_fuente = color_fuente;
    this.color_fondo = color_fondo;
    try {
      jbInit();
    }
    catch(Exception e) {
      e.printStackTrace();
    }
  }

  //INICIALIZA LOS VALORES DE LOS COMPONENTES Y PRESENTA EL FORMULARIO
  private void jbInit() throws Exception {
    //INICIALIZACION
    this.setSize(new Dimension(350, 116));
    this.setContentPane(contentPane);
    this.setResizable(false);
    this.setTitle("Datos del usuario " + nickUsuario);
    //VALOR FECHA ALTA
    ValorAlta.setPreferredSize(new Dimension(215, 17));
    ValorAlta.setFont(new java.awt.Font("Dialog", 1, 14));
    ValorAlta.setForeground(color_fuente);
    ValorAlta.setText(usuario.getFechaAlta(true));
    //ETIQUETA FECHA ALTA
    etAlta.setText("Fecha de alta: ");
    etAlta.setPreferredSize(new Dimension(85, 17));
    etAlta.setFont(new java.awt.Font("Dialog", 1, 12));
    //VALOR NOMBRE
    valorNombre.setFont(new java.awt.Font("Dialog", 1, 14));
    valorNombre.setForeground(color_fuente);
    valorNombre.setText(usuario.getNombreCompleto());
    valorNombre.setPreferredSize(new Dimension(240, 17));
    //ETIQUETA NOMBRE
    etNombre.setFont(new java.awt.Font("Dialog", 1, 12));
    etNombre.setPreferredSize(new Dimension(60, 17));
    etNombre.setText("Nombre: ");
    //PRESENTACIÓN DE COMPONENTES
    contentPane.setLayout(flowLayout1);
    contentPane.setPreferredSize(new Dimension(300, 80));
    contentPane.setBackground(color_fondo);
    contentPane.add(etNombre, null);
    contentPane.add(valorNombre, null);
    contentPane.add(etAlta, null);
    contentPane.add(ValorAlta, null);
    contentPane.setSize(350,80);
  }
}