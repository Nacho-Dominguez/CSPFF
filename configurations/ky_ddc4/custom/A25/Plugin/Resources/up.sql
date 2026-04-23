CREATE TABLE resources (
    `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `uri` varchar(255) NOT NULL,
    `type` varchar(10) NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE=MyISAM DEFAULT CHARSET=latin1;