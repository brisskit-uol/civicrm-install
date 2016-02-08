SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `existing_brisskit_id` (
  `bid` varchar(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS=1;




DELETE FROM `civicrm_word_replacement`;

INSERT INTO `civicrm_word_replacement` VALUES (0,'Case','Recruitment',1,'exactMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'case','recruitment',1,'exactMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'Client','Patient',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'cases','recruitments',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'Cases','Recruitments',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'CiviCase','Study Recruitments',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'Case Types','Studies',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'Case Statuses','Recruitment Statuses',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'New Case','Add new recruitment',1,'wildcardMatch',1);
INSERT INTO `civicrm_word_replacement` VALUES (0,'Add Case','Add new recruitment',1,'wildcardMatch',1);


