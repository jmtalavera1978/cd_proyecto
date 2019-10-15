package accesoBD;

import java.sql.*;
import com.mysql.jdbc.*;
import java.util.*;

/*******************************************************
 * CLASE QUE SE ENCARGA DE LAS CONEXIONES CON LA BASE
 * DE DATOS Y DE REALIZAR LAS CONSULTAS CORRESPONDIENTES
 * PARA LA CORRECTA UTILIZACIÓN DEL CHAT.
 * - requiere el paquete mysql-connector-java-X-bin.jar -
 *******************************************************/
public class AccesoBD {
  java.sql.Connection conexionBD; //CONEXIÓN CON LA BASE DE DATOS
  String URL_BD, BD, usuarioBD, passwordBD; //DATOS DE ACCESO A LA BASE DE DATOS

  /*******************************************
   * CONSTRUCTOR, CREA UNA NUEVA CONEXIÓN DADO
   * LOS PARÁMETROS DE CONEXIÓN RECIBIDOS A LA
   * BASE DE DATOS EN CUESTIÓN.
   *******************************************/
  public AccesoBD(String BD, String host, String user, String password) throws Exception{
    URL_BD = host;
    usuarioBD = user;
    passwordBD = password;
    this.BD = BD;
    Class.forName("com.mysql.jdbc.Driver").newInstance(); //CREA UNA NUEVA INSTANCIA DEL DRIVER JDBC
    //CREAMOS LA CONEXIÓN
    conexionBD = DriverManager.getConnection ("jdbc:mysql://"+URL_BD+"/"+BD+"", usuarioBD, passwordBD);
  }


  /*Devuelve un Usuario dado su identificador, null si no existe*/
  public Usuario getUsuario(int id_usuario) {
    Usuario user = null;
    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT * FROM USUARIOS WHERE id_usuario='" + id_usuario + "'",com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        if (res.next())
          user = new Usuario(res.getInt("id_usuario"),res.getString("login"),res.getString("nombre"),res.getString("apellidos"),res.getString("nick"),res.getString("fecha_alta"),res.getString("fecha_ult_conexion"));
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return user;
  }

  /*Devuelve un Canal dado su nombre, null si no existe*/
  public Canal getCanal(String nombre_canal) {
    Canal canal = null;
    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT * FROM CANALES WHERE nombre_canal='" + nombre_canal + "'",com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        if (res.next())
          canal = new Canal(res.getInt("id_canal"),res.getString("nombre_canal"));
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return canal;
  }

  /*Devuelve un Usuario dado su login y password, null si no existe*/
  public Usuario login(String login, String password) {
    Usuario usuario = null;
    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT * FROM USUARIOS WHERE login='" + login + "' AND password='" + password + "'",com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        if (res.next())
          usuario = new Usuario(res.getInt("id_usuario"),res.getString("login"),res.getString("nombre"),res.getString("apellidos"),res.getString("nick"),res.getString("fecha_alta"),res.getString("fecha_ult_conexion"));
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return usuario;
  }

  /*Devuelve un vector con los canales disponibles (objetos de tipo canal)*/
  public Vector getCanales() {
    Vector canales = new Vector();

    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT * FROM CANALES",com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        while (res.next())
          canales.addElement(new Canal(res.getInt("id_canal"),res.getString("nombre_canal")));
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return canales;
  }

  /*Devuelve las palabras prohibidas*/
  public Vector getPalabrasProhibidas () {
    Vector palabras = new Vector();

    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT nombre_palabra FROM PALABRAS_PROHIBIDAS",
                                                           com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        while (res.next())
          palabras.addElement(res.getString("nombre_palabra"));

        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return palabras;
  }

  /*Comprueba si un usuario es administrador de cierto canal*/
  public boolean esAdministrador(int id_usuario, int id_canal) {
    boolean es_admin = false;

    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT * FROM ADMINISTRADORES WHERE id_usuario='"+id_usuario+"' AND id_canal='"+id_canal+"'",
                                                           com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        if (res.next())
          es_admin = true;
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return es_admin;
  }


  /*Refresca la fecha de ultima conexion de un usuario*/
  public int setFecha(String login, String fecha) {
    int res = -1;
    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("UPDATE USUARIOS SET fecha_ult_conexion='"+fecha+"' WHERE login='"+login+"'");
        res = st.executeUpdate();
        st.close();
      }
    } catch (SQLException e) {}
    return res;
  }

  /*Obtiene el formato de texto de un usuario*/
  public FormatoTexto getFormatoTexto(String id) {
    FormatoTexto ft = null;

    try {
      if (!conexionBD.isClosed()) {
        java.sql.PreparedStatement st = conexionBD.prepareStatement("SELECT fuente, tam_fuente, negrita, cursiva, color_r, color_g, color_b FROM USUARIOS WHERE id_usuario='"+id+"'",
                                                           com.mysql.jdbc.ResultSet.FETCH_FORWARD,
                                                           com.mysql.jdbc.ResultSet.CONCUR_READ_ONLY);
        java.sql.ResultSet res = st.executeQuery();
        if (res.next()) {
          boolean negrita, cursiva;
          if (res.getString("negrita").equals("SI"))
            negrita=true;
          else
            negrita=false;
          if (res.getString("cursiva").equals("SI"))
            cursiva=true;
          else
            cursiva=false;
          ft = new FormatoTexto(res.getString("fuente"),res.getInt("tam_fuente"),negrita,cursiva,
                                res.getInt("color_r"),res.getInt("color_g"),res.getInt("color_b"));
        }
        res.close();
        st.close();
      }
    } catch (SQLException e) {}

    return ft;
  }

  /*Refresca el formato de texto de un usuario*/
  public int setFormatoTexto(FormatoTexto ft, String id) {
    int res = -1;
    try {
      if (!conexionBD.isClosed()) {
        String fuente = ft.getFuente();
        String tam = String.valueOf(ft.getTam());
        String negrita, cursiva;
        if (ft.isNegrita())
          negrita="SI";
        else
          negrita="NO";
        if (ft.isCursiva())
          cursiva="SI";
        else
          cursiva="NO";
        String r = String.valueOf(ft.getColor_r());
        String g = String.valueOf(ft.getColor_g());
        String b = String.valueOf(ft.getColor_b());
        java.sql.PreparedStatement st = conexionBD.prepareStatement("UPDATE USUARIOS SET fuente='"+fuente+"', tam_fuente='"+tam+"', negrita='"+negrita+"', cursiva='"+cursiva+"', color_r='"+r+"', color_g='"+g+"', color_b='"+b+"' WHERE id_usuario='"+id+"'");
        res = st.executeUpdate();
        st.close();
      }
    } catch (SQLException e) {}
    return res;
  }

  /*Cierra la conexion a la base de datos y elimina el objeto*/
  public void cerrarAccesoBD() {
    try {
      conexionBD.close();
    } catch (SQLException e){}
    if (conexionBD!=null)
      conexionBD = null;
    if (URL_BD!=null)
      URL_BD = null;
    if (BD!=null)
      BD = null;
    if (usuarioBD!=null)
      usuarioBD = null;
    if (passwordBD!=null)
      passwordBD = null;
  }
}