package servidorchat;

import java.io.*;
import java.net.*;
import java.util.*;
import accesoBD.*;
import servidorchat.*;
import mensajes.*;


class ThreadUsuario extends Thread {
  /* PARÁMETROS */
  static Vector usuariosConectados = new Vector();
  private Socket sock;
  private static String hostMysql;
  private static String userMysql;
  private static String passwordMysql;
  private ObjectInputStream in;
  private ObjectOutputStream out;
  private InetAddress IPusuario;
  private int numPuertoPrivado;
  private int puertoUsuario;
  private String bd;
  private FormatoTexto ft;

  //Datos del usuario
  public Usuario datosUsuario;

  //Datos del canal al que se encuentra conectado
  Canal canal;

  //La siquiente variable indica si se ha iniciado sesión
  boolean sesionIniciada = false;

  //Indica si el usuario es administrador del canal actual
  boolean esAdministrador = false;



  /* FUNCIONES */
  /*El contructor de la clase recibe el socket de conexión*/
  ThreadUsuario (Socket s, String host, String user, String password) throws IOException{
    //Creamos la conexión con el cliente
    this.sock = s;
    this.hostMysql = host;
    this.userMysql = user;
    this.passwordMysql = password;
    IPusuario=this.sock.getInetAddress();
    puertoUsuario=this.sock.getPort();

    in = new ObjectInputStream(this.sock.getInputStream());
    out = new ObjectOutputStream(this.sock.getOutputStream());
  }

  ThreadUsuario(){
  }

  //Termina el proceso de lectura/escritura del puerto del cliente
  private void terminar() throws IOException {
    //Eliminamos este usuario de la lista de usuarios conectados
    synchronized(this){
      usuariosConectados.removeElement(this);
    }

    Vector usuarios = calculaUsuariosConectados(); //Calculamos usuarios conectados

    //Informamos a los demás usuarios que se ha dejado el chat
    for(int i=0;i<usuariosConectados.size();i++) {
      ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
      if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd)) {
        synchronized(this){
          tmpClient.out.writeObject(new Respuesta("getUsuarios",usuarios)); //DATOS DE LA NUEVA LISTA DE CONECTADOS
          //tmpClient.out.flush(); //Envio de datos
          tmpClient.out.writeObject(new Respuesta("mensajeSistema",datosUsuario.getNick() + " ha dejado el chat."));
          tmpClient.out.flush();
        }
      }
    }
    if (datosUsuario!=null)
      System.out.println("El usuario '"+datosUsuario.getNombreCompleto()+"' se ha desconectado.");

    //Eliminamos este objeto
    sesionIniciada = false;
    canal = null;
    datosUsuario = null;
    bd = null;
  }




  //Envia los datos de los usuarios conectados
  private Vector calculaUsuariosConectados() {
    Vector usuarios = new Vector();

    for(int i=0;i<usuariosConectados.size();i++){
      ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
      if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd)){
        usuarios.addElement(tmpClient.datosUsuario);
      }
    }
    return usuarios;
  }


  //HILO DE EJECUCIÓN, comprobación de peticiones recibidas y envío de datos
  public void run(){
    boolean hayConexion = true;
    while(hayConexion){
      try{
        //Obtenemos el tipo de peticion realizada por el cliente
        String tipo = null;
        String basedatos = null;
        Object datosPet = null;
        try{
          Peticion peticion = (Peticion)in.readObject();
          tipo = peticion.getTipo();
          basedatos = peticion.getBaseDatos();
          datosPet = peticion.getDatos();
        } catch (ClassNotFoundException cnfe) {}

        /*PROCESAMOS LA PETICIÓN SEGÚN EL TIPO DE LA MISMA*/
        if (tipo != null){
          if (tipo.equalsIgnoreCase("login")){
            //Obtenemos los datos de login
            Vector datosLogin = (Vector)datosPet;
            String nick = (String)datosLogin.get(0);
            String login = (String)datosLogin.get(1);
            String password = (String)datosLogin.get(2);
            String canalIn = (String)datosLogin.get(3);

            //ABRIMOS UNA CONEXION CON LA BASE DE DATOS
            try {
              AccesoBD conBD= new AccesoBD(basedatos, hostMysql, userMysql, passwordMysql);
              if ((datosUsuario=conBD.login(login, password))!=null) { //Comprobamos si existe el usuario, login correcto
                datosUsuario.setNick(nick);
                synchronized (this) {
                  out.writeObject(new Respuesta("login","correcto")); //RESPUESTA AFIRMATIVA
                  out.flush();
                }

                //Comprobamos la fecha de hoy y la guardamos como fecha de último acceso del usuario
                Calendar cal = Calendar.getInstance();
                conBD.setFecha(login, cal.get(Calendar.YEAR)+"-"+(cal.get(Calendar.MONTH)+1)+"-"+cal.get(Calendar.DAY_OF_MONTH));

                //Consultamos las palabras prohibidas
                Vector palabras = conBD.getPalabrasProhibidas();
                //Enviamos las palabras prohibidas
                synchronized (this) {
                  out.writeObject(new Respuesta("getPalabras",palabras));
                  out.flush();
                }

                //Enviamos el formato de texto y lo guardamos
                ft = conBD.getFormatoTexto(String.valueOf(datosUsuario.getId()));
                synchronized(this){
                  out.writeObject((Object)(new Respuesta("getFormatoTexto",ft))); //RESPUESTA
                  out.flush(); //Envio de datos
                }

                canal = conBD.getCanal(canalIn);
                bd = basedatos;
                sesionIniciada = true;
                //Comprobamos si es administrador
                if ((esAdministrador = conBD.esAdministrador(datosUsuario.getId(),canal.getId()))) {
                  out.writeObject(new Respuesta("esAdministrador","correcto")); //RESPUESTA AFIRMATIVA
                  out.flush();
                }
                synchronized(this){ //Insertamos el nuevo usuario
                  usuariosConectados.addElement(this);
                }
                Vector usuarios = calculaUsuariosConectados(); //Usuarios conectados
                for (int i=0; i<usuariosConectados.size(); i++) {
                  ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
                  if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd)) {
                    synchronized(tmpClient){
                      tmpClient.out.writeObject(new Respuesta("mensajeSistema",datosUsuario.getNick() + " ha entrado en el chat."));
                      //tmpClient.out.flush();
                      tmpClient.out.writeObject(new Respuesta("getUsuarios",usuarios)); //RESPUESTA
                      tmpClient.out.flush(); //Envio de datos
                    }
                  }
                }
                System.out.println("El usuario '"+datosUsuario.getNombreCompleto()+"' se ha conectado al canal '"+canal.getNombre()+"'");
              } else
                out.writeObject(new Respuesta("login","incorrecto")); //RESPUESTA NEGATIVA
              out.flush(); //Envio de datos
              //CERRAMOS LA CONEXION A LA BASE DE DATOS
              conBD.cerrarAccesoBD();
            } catch (Exception e) {}

          //EL USUARIO SE DESCONECTA
          } else if (tipo.equalsIgnoreCase("desconectar")){
            terminar();

          //EL USUARIO MANDA SU PUERTO LOCAL PARA RECIBIR PRIVADOS
          } else if (tipo.equalsIgnoreCase("setPuertoPrivado")){
            numPuertoPrivado = Integer.parseInt((String)datosPet);

          } else if (tipo.equalsIgnoreCase("getUsuarios")){
            Vector usuarios = calculaUsuariosConectados();
            out.writeObject(new Respuesta("getUsuarios",usuarios)); //RESPUESTA
            out.flush(); //Envio de datos

          } else if (tipo.equalsIgnoreCase("getCanales")){
            //ABRIMOS UNA CONEXION CON LA BASE DE DATOS
            AccesoBD conBD;
            try{
              conBD= new AccesoBD(basedatos, hostMysql, userMysql, passwordMysql);

              Vector canales = conBD.getCanales();
              synchronized(this){
                out.writeObject((Object)(new Respuesta("getCanales",canales))); //RESPUESTA
                out.flush(); //Envio de datos
              }
              //CERRAMOS LA CONEXION A LA BASE DE DATOS
              conBD.cerrarAccesoBD();
            } catch (Exception e) {
              synchronized(this){
                out.writeObject((Object)(new Respuesta("getCanales",null))); //RESPUESTA
                out.flush(); //Envio de datos
              }
            }

          //GRABAR UN NUEVO FORMATO DE TEXTO DE USUARIO
          } else if (tipo.equalsIgnoreCase("setFormatoTexto")){
            ft = (FormatoTexto) datosPet;

            try{
              AccesoBD conBD; //ABRIMOS UNA CONEXION CON LA BASE DE DATOS
              conBD= new AccesoBD(basedatos, hostMysql, userMysql, passwordMysql);
              conBD.setFormatoTexto(ft,String.valueOf(datosUsuario.getId()));
              conBD.cerrarAccesoBD(); //CERRAMOS LA CONEXION A LA BASE DE DATOS
            } catch (Exception e) {}

          //SALIR DEL CHAT
          } else if (tipo.equalsIgnoreCase("salir")){
            if (sesionIniciada)
              terminar();
            hayConexion = false;
            //Cerramos los Stream de comunicación
            this.in.close();
            this.out.close();


          //OBTIENE LA DIRECCIÓN DE UNA MÁQUiNA CLIENTE Y SU PUERTO PARA PRIVADO
          } else if (tipo.equalsIgnoreCase("getHost")){
            Usuario usuarioPriv = (Usuario)datosPet;
            boolean encontrado = false;
            int i=0;
            while (!encontrado && i<usuariosConectados.size()) {
              ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
              if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd) && tmpClient.datosUsuario.getId()==usuarioPriv.getId() && tmpClient.datosUsuario.getId()!=datosUsuario.getId()) {
                encontrado = true;
                Vector dir = new Vector();
                dir.add(0,tmpClient.IPusuario);
                dir.add(1,String.valueOf(tmpClient.numPuertoPrivado));
                synchronized(this){
                  out.writeObject((Object)(new Respuesta("getHost",dir))); //RESPUESTA
                  out.flush(); //Envio de datos
                }
              }
              i++;
            }

          }else if (tipo.equalsIgnoreCase("Banear")){ //banear un usuario
            Usuario usuarioBaneado = (Usuario)datosPet;
            boolean encontrado = false;
            int i=0;
            while (!encontrado && i<usuariosConectados.size()) {
              ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
              if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd) && tmpClient.datosUsuario.getId()==usuarioBaneado.getId() && tmpClient.datosUsuario.getId()!=datosUsuario.getId()) {
                encontrado = true;
                synchronized(tmpClient){
                  tmpClient.out.writeObject((Object)(new Respuesta("Baneado",null))); //RESPUESTA
                  tmpClient.out.flush(); //Envio de datos
                }
              }
              i++;
            }


          }else if (tipo.equalsIgnoreCase("mensajeNormal")){ //escribir en el chat
            //Obtenemos el mensaje
            String mensaje = (String)datosPet;

            for(int i=0;i<usuariosConectados.size();i++) {
              ThreadUsuario tmpClient=(ThreadUsuario)usuariosConectados.get(i);
              if (tmpClient.sesionIniciada && tmpClient.canal.getId()==this.canal.getId() && tmpClient.bd.equals(this.bd) && tmpClient.datosUsuario.getId()!=datosUsuario.getId()) {
                Vector mens = new Vector();
                mens.add(0,mensaje);
                mens.add(1,ft);
                synchronized(tmpClient){
                  tmpClient.out.writeObject(new Respuesta("mensajeNormal",mens)); //ENVIO DE MENSAJES
                  tmpClient.out.flush();
                }
              }
            }
          }
        }
      } catch (IOException ioe){
        try {
          terminar(); //Borramos el objeto si se pierde la comunicación
          hayConexion = false;
        } catch (IOException ioex){ ioex.printStackTrace(); }
      }
    }
  }

}