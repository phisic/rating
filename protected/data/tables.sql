CREATE TABLE `category` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PId` int(10) unsigned NOT NULL,
  `Level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf;

CREATE TABLE `rating` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(400) DEFAULT NULL,
  `Description` text,
  `CategoryId` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `rating_item` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RatingId` int(10) unsigned NOT NULL,
  `Keyword` varchar(128) DEFAULT NULL,
  `Attributes` text,
  `Description` text,
  `Image` varchar(255) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `rating_item_history` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RatingId` int(11) DEFAULT NULL,
  `Date` datetime DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `ratingid` (`RatingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;