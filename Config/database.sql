
-- CREATE DATABASE data_test;

-- USE data_test ;

-- table ACCES --

 DROP Table
     IF EXISTS `acces` ;

 create table `acces` (
`id` int(11) PRIMARY KEY  NOT NULL auto_increment,
`guild_id` int(11) NOT NULL ,
`member_id` int(11) NOT NULL ,
`val` int(11) NOT NULL ,
`role` varchar(255) NOT NULL ,
`createAt` datetime NOT NULL  DEFAULT current_timestamp(),
`updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
`deleteAt` datetime NOT NULL
 ) ;

-- table ARTICLE --

 DROP Table
     IF EXISTS `article` ;

 create table `article` (
`id` int(11) PRIMARY KEY  NOT NULL auto_increment,
`type` varchar(255) NOT NULL ,
`author_id` int(11) NOT NULL  DEFAULT '0',
`titre` varchar(255) NOT NULL ,
`menu` varchar(255) NOT NULL ,
`description` text NOT NULL ,
`default` tinyint(1) NOT NULL  DEFAULT '0',
`contenu` text NOT NULL ,
`position` int(11) NOT NULL ,
`date` date NOT NULL ,
`parent_id` int(11) NOT NULL ,
`slug` varchar(255) NOT NULL ,
`createAt` datetime NOT NULL  DEFAULT current_timestamp(),
`updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
`deleteAt` datetime NOT NULL
 ) ;

-- table CATEGORIE --

 DROP Table
     IF EXISTS `categorie` ;

 create table `categorie` (
`id` int(11) PRIMARY KEY  NOT NULL auto_increment,
`nom` varchar(255) NOT NULL ,
`createAt` datetime NOT NULL  DEFAULT current_timestamp(),
`updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
`deleteAt` datetime NOT NULL
 ) ;

-- table FILE --

 DROP Table
     IF EXISTS `file` ;

 create table `file` (
`id` int(11) PRIMARY KEY  NOT NULL auto_increment,
`type` varchar(255) NOT NULL ,
`nom` varchar(255) NOT NULL ,
`src` varchar(255) NOT NULL ,
`createAt` datetime NOT NULL  DEFAULT current_timestamp(),
`updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
`deleteAt` datetime NOT NULL
 ) ;

-- table GUILD --

 DROP Table
     IF EXISTS `guild` ;

 create table `guild` (
  `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
  `name` varchar(255) NOT NULL ,
  `createur_id` char(36) NOT NULL ,
  `presente` text NOT NULL ,
  `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
  `deleteAt` datetime NOT NULL
 ) ;

-- table INDEXION --

 DROP Table
     IF EXISTS `indexion` ;

 create table `indexion` (
 `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
 `article_id` int(11) NOT NULL ,
 `keyword_id` int(11) NOT NULL ,
 `createAt` datetime NOT NULL ,
 `updateAt` datetime NOT NULL  DEFAULT '0000-00-00 00:00:00'on update current_timestamp(),
 `deleteAt` datetime NOT NULL
 ) ;

-- table INVENTAIRE --

 DROP Table
     IF EXISTS `inventaire` ;

 create table `inventaire` (
  `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL ,
  `child_id` int(11) NOT NULL ,
  `val` varchar(255) NOT NULL ,
  `rubrique` varchar(255) NOT NULL ,
  `type` varchar(255) NOT NULL ,
  `caract` longtext NOT NULL ,
  `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
  `deleteAt` datetime NOT NULL
 ) ;

-- table ITEM --

 DROP Table
     IF EXISTS `item` ;

create table `item` (
  `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
  `name` varchar(255) NOT NULL ,
  `description` text NOT NULL ,
  `vie` int(11) NOT NULL ,
  `type` varchar(255) NOT NULL ,
  `objet` varchar(255) NOT NULL ,
  `img` varchar(255) NOT NULL ,
  `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
  `deleteAt` datetime NOT NULL
 ) ;

-- table KEYWORD --

 DROP Table
     IF EXISTS `keyword` ;

create table `keyword` (
  `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
  `mot` varchar(255) NOT NULL ,
  `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
  `deleteAt` datetime NOT NULL
 ) ;

-- table MAP --

 DROP Table
     IF EXISTS `map` ;

 create table `map` (
   `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
   `x` int(11) NOT NULL ,
   `y` int(11) NOT NULL ,
   `terrain_id` int(11) NOT NULL ,
   `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
   `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
   `deleteAt` datetime NOT NULL
 ) ;

-- table MEMBER --

 DROP Table
     IF EXISTS `member` ;

 create table `member` (
    `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
    `guild_id` int(11) NOT NULL ,
    `user_id` int(11) NOT NULL ,
    `is_manager` tinyint(1) NOT NULL  DEFAULT '0',
    `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
    `updateAt` datetime NOT NULL  DEFAULT current_timestamp(),
    `deleteAt` datetime NOT NULL
 ) ;

-- table PERSONNAGE --

 DROP Table
     IF EXISTS `personnage` ;

create table `personnage` (
  `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
  `name` varchar(255) NOT NULL ,
  `sexe` int(11) NOT NULL  DEFAULT '1',
  `description` text NOT NULL ,
  `vie` int(11) NOT NULL  DEFAULT '100',
  `type` varchar(255) NOT NULL ,
  `img` varchar(255) NOT NULL ,
  `status` varchar(255) NOT NULL  DEFAULT 'normal',
  `position_id` int(11) NOT NULL ,
  `user_id` int(11) NOT NULL ,
  `faction_id` int(11) NOT NULL ,
  `race_id` int(11) NOT NULL ,
  `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
  `deleteAt` datetime NOT NULL
 ) ;

-- table USER --

DROP Table
     IF EXISTS `user` ;

create table `user` (
   `id` int(11) PRIMARY KEY  NOT NULL auto_increment,
   `login` varchar(255) NOT NULL ,
   `mail` varchar(255) NOT NULL ,
   `pswd` varchar(255) NOT NULL ,
   `createAt` datetime NOT NULL  DEFAULT current_timestamp(),
   `updateAt` datetime NOT NULL  DEFAULT current_timestamp()on update current_timestamp(),
   `deleteAt` datetime NOT NULL
) ;

-- foreign keys & unicité

ALTER TABLE `personnage`
 ADD CONSTRAINT `FK_PersonnageRace` FOREIGN KEY (`race_id`) REFERENCES `item`(`id`) ON DELETE SET NULL ON UPDATE CASCADE ,
 ADD CONSTRAINT `FK_PersonnageUser` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `FK_PersonnageMap` FOREIGN KEY (`position_id`) REFERENCES `map`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `FK_PersonnageFaction` FOREIGN KEY (`faction_id`) REFERENCES `item`(`id`) ON DELETE SET NULL ON UPDATE CASCADE ;

ALTER TABLE `member`
 ADD CONSTRAINT `FK_MemberUser` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `FK_MemberGuild` FOREIGN KEY (`guild_id`) REFERENCES `guild`(`id`) ON DELETE SET NULL ON UPDATE CASCADE ,
 ADD CONSTRAINT `UC_Member` UNIQUE (`guild_id`,`member_id`);

ALTER TABLE `map`
 ADD CONSTRAINT FK_MapTerrain FOREIGN KEY (`terrain_id`) REFERENCES `item`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `indexion`
 ADD CONSTRAINT `FK_IndexArticle` FOREIGN KEY (`article_id`) REFERENCES `article`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `FK_IndexKeyword` FOREIGN KEY (`keyword_id`) REFERENCES `keyword`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `UC_Index` UNIQUE (`article_id`,`keyword_id`);

ALTER TABLE `guild`
 ADD CONSTRAINT `FK_GuildCreateur` FOREIGN KEY (`createur_id`) REFERENCES `user`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `acces`
 ADD CONSTRAINT `FK_AccesMember` FOREIGN KEY (`member_id`) REFERENCES `member`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `FK_AccesGuild` FOREIGN KEY (`guild_id`) REFERENCES `guild`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 ADD CONSTRAINT `UC_Acces` UNIQUE (`article_id`,`keyword_id`);