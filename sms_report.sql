DROP TABLE IF EXISTS `sms_report`;
CREATE TABLE `sms_report` (
  `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL DEFAULT '',
  `id_code` char(10) NOT NULL DEFAULT '',
  `msg` varchar(90) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
