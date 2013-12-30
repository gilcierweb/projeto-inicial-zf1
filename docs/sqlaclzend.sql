CREATE TABLE `roles` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO roles (id, role) VALUES (1, 'Anonymous');
INSERT INTO roles (id, role) VALUES (2, 'Registered');
INSERT INTO roles (id, role) VALUES (3, 'Admin');

CREATE TABLE `acl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller` (`controller`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
  CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

  CREATE TABLE `acl_to_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `acl_id` int(10) NOT NULL,
  `role_id` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_id` (`acl_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `acl_to_roles_ibfk_1` FOREIGN KEY (`acl_id`)
     REFERENCES `acl` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acl_to_roles_ibfk_2` FOREIGN KEY (`role_id`)
     REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(1) DEFAULT '1',
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_index` (`login`),
  KEY `password_index` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
  CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
-----------------------------------
--http://blog.davidjclarke.co.uk/database-driven-zend-acl-tutorial-part-one.html

  CREATE TABLE `Roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Roles` VALUES (NULL, 'admin', NULL, '2011-02-03 00:00:00', '2011-02-03 00:00:00');
INSERT INTO `Roles` VALUES (NULL, 'staff', NULL, '2011-02-03 00:00:00', '2011-02-03 00:00:00');


  CREATE TABLE `Resources` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `module` VARCHAR(255) NOT NULL,
  `controller` VARCHAR(255) NOT NULL,
  `action` VARCHAR(45) NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `routeName` VARCHAR(255) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

  CREATE TABLE `RoleResources` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `roleId` INT(11) NOT NULL,
  `resourceId` INT(11) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `role_idx` (`roleId`),
  KEY `resources_idx` (`resourceId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

  CREATE TABLE `RolesInheritances` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parentRoleId` INT(11) NOT NULL,
  `roleId` INT(11) NOT NULL,
  `position` INT(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_inheritance_1` (`parentRoleId`),
  KEY `fk_inheritance_2` (`roleId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `AllowResources` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `accountId` INT(11) NOT NULL,
  `userId` INT(11) NOT NULL,
  `resourceId` INT(11) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `account` (`accountId`),
  KEY `resource` (`resourceId`),
  KEY `user` (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8

CREATE TABLE `DenyResources` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `accountId` INT(11) NOT NULL,
  `userId` INT(11) NOT NULL,
  `resourceId` INT(11) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `account` (`accountId`),
  KEY `resource` (`resourceId`),
  KEY `user` (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;