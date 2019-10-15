# MySQL-Front Dump 2.4
#
# Host: localhost   Database: ejemploDBchat
#--------------------------------------------------------
# Server version 3.23.52-nt


#
# Table structure for table 'administradores'
#

CREATE TABLE `administradores` (
  `id_canal` int(4) unsigned NOT NULL default '0',
  `id_usuario` int(7) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_canal`,`id_usuario`),
  UNIQUE KEY `id_canal` (`id_canal`,`id_usuario`)
) TYPE=MyISAM;



#
# Table structure for table 'canales'
#

CREATE TABLE `canales` (
  `id_canal` int(4) unsigned NOT NULL auto_increment,
  `nombre_canal` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_canal`),
  UNIQUE KEY `id_canal` (`id_canal`)
) TYPE=MyISAM;



#
# Table structure for table 'palabras_prohibidas'
#

CREATE TABLE `palabras_prohibidas` (
  `id_palabra` int(7) NOT NULL auto_increment,
  `nombre_palabra` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_palabra`),
  UNIQUE KEY `id_palabra` (`id_palabra`,`nombre_palabra`)
) TYPE=MyISAM;



#
# Table structure for table 'usuarios'
#

CREATE TABLE `usuarios` (
  `id_usuario` int(7) unsigned NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `nombre` varchar(255) NOT NULL default '',
  `apellidos` varchar(255) NOT NULL default '',
  `nick` varchar(15) NOT NULL default '',
  `fecha_alta` date NOT NULL default '0000-00-00',
  `fecha_ult_conexion` date NOT NULL default '0000-00-00',
  `fuente` varchar(20) default NULL,
  `tam_fuente` tinyint(2) unsigned NOT NULL default '12',
  `negrita` char(2) default 'NO',
  `cursiva` char(2) default 'NO',
  `color_r` tinyint(3) unsigned default NULL,
  `color_g` tinyint(3) unsigned default NULL,
  `color_b` tinyint(3) unsigned default NULL,
  PRIMARY KEY  (`id_usuario`),
  UNIQUE KEY `id_usuario` (`id_usuario`)
) TYPE=MyISAM;

