-- Обновление с версии 4.0.0 до версии 4.1.0
CREATE TABLE powercounter_ip_unique ( 
  id_position int(11) NOT NULL auto_increment, 
  ip bigint(20) NOT NULL, 
  total bigint(20) NOT NULL, 
  putdate datetime NOT NULL, 
  PRIMARY KEY  (id_position) 
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;