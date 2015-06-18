ALTER TABLE `accounts` 
CHANGE COLUMN `id` `id` int(10) unsigned NOT NULL auto_increment;

ALTER TABLE `files` 
CHANGE COLUMN `id` `id` int(10) unsigned NOT NULL auto_increment,
CHANGE COLUMN `originalname` `originalname` varchar(255) NULL default NULL,
CHANGE COLUMN `size` `size` int(10) NULL default NULL,
CHANGE COLUMN `user` `user` int(10) NULL default '0';

ALTER TABLE `invites` 
CHANGE COLUMN `id` `id` int(10) unsigned NOT NULL auto_increment;

ALTER TABLE `reports` 
CHANGE COLUMN `id` `id` int(10) unsigned NOT NULL auto_increment,
CHANGE COLUMN `file` `file` varchar(255) NOT NULL,
CHANGE COLUMN `reporter` `reporter` varchar(255) NOT NULL;
