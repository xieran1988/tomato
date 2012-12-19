
use tomato;

SET NAMES utf8;

DROP TABLE IF EXISTS `entry`;
CREATE TABLE `entry` (
  `email` varchar(30) not null,
  `time` date not null,
  `val` text DEFAULT '',
	`prize` text default '',
	primary key (email, time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `email` varchar(30) not null,
  `pass` varchar(30) not null,
	`prize` text default '',
	primary key (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log`;
Create table `log` (
	`email` varchar(30) not null,
  `time` date not null,
  `act` varchar(20) DEFAULT '',
	`val` varchar(100) default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

