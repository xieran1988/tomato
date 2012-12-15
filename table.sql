
SET NAMES utf8;

DROP TABLE IF EXISTS `entry`;
CREATE TABLE `entry` (
  `email` varchar(30) not null,
  `time` date not null,
  `val` text DEFAULT '',
	primary key (email, time)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `email` varchar(30) not null,
  `pass` varchar(30) not null,
	primary key (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

