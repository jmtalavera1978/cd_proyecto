# MySQL-Front Dump 2.5
#
# Host: localhost   Database: ejemploDBchat
# --------------------------------------------------------
# Server version 3.23.52-nt


#
# Table structure for table 'administradores'
#

CREATE TABLE administradores (
  id_canal int(4) unsigned NOT NULL default '0',
  id_usuario int(7) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_canal,id_usuario),
  UNIQUE KEY id_canal (id_canal,id_usuario)
) TYPE=MyISAM;



#
# Dumping data for table 'administradores'
#

INSERT INTO administradores VALUES("1", "1");
INSERT INTO administradores VALUES("1", "2");


#
# Table structure for table 'canales'
#

CREATE TABLE canales (
  id_canal int(4) unsigned NOT NULL auto_increment,
  nombre_canal varchar(255) NOT NULL default '',
  PRIMARY KEY  (id_canal),
  UNIQUE KEY id_canal (id_canal)
) TYPE=MyISAM;



#
# Dumping data for table 'canales'
#

INSERT INTO canales VALUES("1", "PROYECTO - Canal de comunicación para los creadores de este proyecto");


#
# Table structure for table 'palabras_prohibidas'
#

CREATE TABLE palabras_prohibidas (
  id_palabra int(7) NOT NULL auto_increment,
  nombre_palabra varchar(255) NOT NULL default '',
  PRIMARY KEY  (id_palabra),
  UNIQUE KEY id_palabra (id_palabra,nombre_palabra)
) TYPE=MyISAM;



#
# Dumping data for table 'palabras_prohibidas'
#

INSERT INTO palabras_prohibidas VALUES("1", "puta");
INSERT INTO palabras_prohibidas VALUES("2", "guarra");
INSERT INTO palabras_prohibidas VALUES("3", "cabron");
INSERT INTO palabras_prohibidas VALUES("4", "nabo");
INSERT INTO palabras_prohibidas VALUES("5", "joder");
INSERT INTO palabras_prohibidas VALUES("6", "follar");
INSERT INTO palabras_prohibidas VALUES("7", "polla");
INSERT INTO palabras_prohibidas VALUES("8", "bastardo");
INSERT INTO palabras_prohibidas VALUES("9", "chupamela");


#
# Table structure for table 'usuarios'
#

CREATE TABLE usuarios (
  id_usuario int(7) unsigned NOT NULL auto_increment,
  login varchar(20) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  nombre varchar(255) NOT NULL default '',
  apellidos varchar(255) NOT NULL default '',
  nick varchar(15) NOT NULL default '',
  fecha_alta date NOT NULL default '0000-00-00',
  fecha_ult_conexion date NOT NULL default '0000-00-00',
  fuente varchar(20) NOT NULL default 'Dialog',
  tam_fuente tinyint(2) unsigned NOT NULL default '12',
  negrita char(2) NOT NULL default 'NO',
  cursiva char(2) NOT NULL default 'NO',
  color_r tinyint(3) unsigned NOT NULL default '0',
  color_g tinyint(3) unsigned NOT NULL default '0',
  color_b tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_usuario),
  UNIQUE KEY id_usuario (id_usuario)
) TYPE=MyISAM;



#
# Dumping data for table 'usuarios'
#

INSERT INTO usuarios VALUES("1", "tala", "tala", "José María", "Talavera Calzado", "Talavera", "2003-08-04", "2003-09-15", "SansSerif", "11", "SI", "NO", "149", "0", "98");
INSERT INTO usuarios VALUES("2", "sixto", "sixto", "Sixto", "Suaña Moreno", "Sixto", "2002-08-04", "2003-09-15", "Dialog", "14", "SI", "NO", "51", "51", "255");
INSERT INTO usuarios VALUES("3", "yoly", "yoly", "yoly", "barco", "yoly", "0000-00-00", "2003-09-08", "Dialog", "12", "NO", "NO", "0", "0", "0");
INSERT INTO usuarios VALUES("4", "juan", "juan", "juan", "ramirez forero", "juan", "2003-08-12", "2003-09-08", "Dialog", "12", "NO", "NO", "0", "0", "0");
INSERT INTO usuarios VALUES("5", "steele", "steele", "Juan", "PIZ", "juan_piz", "2003-08-12", "2003-08-12", "Dialog", "12", "NO", "NO", "0", "0", "0");
INSERT INTO usuarios VALUES("6", "ruzmelin", "ruzmelin", "fran", "ruz", "ruzmelin", "2003-08-12", "0000-00-00", "Dialog", "12", "NO", "NO", "0", "0", "0");
