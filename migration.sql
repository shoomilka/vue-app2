DROP TABLE IF EXISTS `rules`;;
CREATE TABLE `rules` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(20) unsigned NOT NULL,
  `symbol` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;

INSERT INTO `rules` (`id`, `type`, `position`, `symbol`) VALUES (NULL, 'minimum', '0', '');;
INSERT INTO `rules` (`id`, `type`, `position`, `symbol`) VALUES (NULL, 'maximum', '0', '');;
