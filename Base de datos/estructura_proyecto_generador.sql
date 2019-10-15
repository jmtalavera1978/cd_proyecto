# MySQL-Front Dump 2.5
#
# Host: localhost   Database: proyecto_generador
# --------------------------------------------------------
# Server version 4.0.13-nt


#
# Table structure for table 'asociaciones'
#

CREATE TABLE asociaciones (
  fichero int(10) unsigned NOT NULL default '0',
  dato int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (dato,fichero)
) TYPE=MyISAM;



#
# Table structure for table 'clases'
#

CREATE TABLE clases (
  id tinyint(3) unsigned NOT NULL auto_increment,
  nombre char(30) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'contenidos'
#

CREATE TABLE contenidos (
  objeto int(5) unsigned NOT NULL default '0',
  fichero int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (objeto,fichero)
) TYPE=MyISAM;



#
# Table structure for table 'datos'
#

CREATE TABLE datos (
  id tinyint(5) unsigned NOT NULL auto_increment,
  nombre char(50) default NULL,
  tipo int(10) unsigned default NULL,
  defecto char(50) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'ficheros'
#

CREATE TABLE ficheros (
  id int(10) unsigned NOT NULL auto_increment,
  nombre varchar(255) default NULL,
  codigo longtext,
  camino varchar(255) default NULL,
  lenguaje int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'idiomas'
#

CREATE TABLE idiomas (
  id tinyint(3) unsigned NOT NULL auto_increment,
  nombre char(30) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'lenguajes'
#

CREATE TABLE lenguajes (
  id int(10) unsigned NOT NULL auto_increment,
  nombre char(50) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'objetos'
#

CREATE TABLE objetos (
  id tinyint(3) unsigned NOT NULL auto_increment,
  nombre varchar(40) NOT NULL default '',
  clase tinyint(3) unsigned NOT NULL default '1',
  descripcion varchar(50) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'opciones'
#

CREATE TABLE opciones (
  id int(10) unsigned NOT NULL auto_increment,
  nombre varchar(50) default NULL,
  tipo int(10) unsigned default NULL,
  maxtam int(10) unsigned default NULL,
  valor varchar(100) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'opcionesdatos'
#

CREATE TABLE opcionesdatos (
  dato int(10) unsigned NOT NULL default '0',
  opcion int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (dato,opcion)
) TYPE=MyISAM;



#
# Table structure for table 'palabras'
#

CREATE TABLE palabras (
  id char(50) NOT NULL default '',
  idioma int(10) unsigned NOT NULL default '1',
  traduccion char(255) NOT NULL default '',
  PRIMARY KEY  (id,idioma)
) TYPE=MyISAM;



#
# Table structure for table 'tiposdatos'
#

CREATE TABLE tiposdatos (
  id tinyint(3) unsigned NOT NULL auto_increment,
  nombre char(30) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;



#
# Table structure for table 'tiposopciones'
#

CREATE TABLE tiposopciones (
  id tinyint(3) unsigned NOT NULL auto_increment,
  nombre char(30) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) TYPE=MyISAM;

