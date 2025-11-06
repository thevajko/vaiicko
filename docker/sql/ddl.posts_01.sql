SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts`
(
    `id`      int(11)      NOT NULL AUTO_INCREMENT,
    `text`    text DEFAULT NULL,
    `picture` varchar(300) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `posts` (`id`, `text`, `picture`)
VALUES (1, 'Biely kostolík pod horami v hmle', '37129207054184-free-photo-of-vrch-hora-dom-hmla.jpeg'),
       (2, 'Cesta kľukatiaca sa krásnym údolím', '35981156452928-pexels-photo-13149220.jpeg'),
       (3, 'Veža v diaľke medzi medzi stromami', '36020276268180-free-photo-of-zelena-veza-kostol-mestsky.jpeg');
