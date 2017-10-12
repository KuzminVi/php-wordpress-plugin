<?php


function setup_db() 
{
    global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //для dbDelta

	$sql = 'CREATE TABLE `kv_answers` (
	`Id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`Test` int(11) NOT NULL,
	`Question` int(11) NOT NULL,
	`Answer` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
	) ENGINE=InnoDB;';
	dbDelta($sql);
	
	$sql = 'CREATE TABLE `kv_answers_categories` (
	`Id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`Answer id` int(11) NOT NULL,
	`Category` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`Points` int(11) NOT NULL
	) ENGINE=InnoDB;';
	dbDelta($sql);
	
	$sql = 'CREATE TABLE `kv_questions` (
  `Test` int(11) NOT NULL,
  `Id` int(11) NOT NULL,
  `name` varchar(1023) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
   PRIMARY KEY (`Id`,`Test`)
   ) ENGINE=InnoDB;';
   dbDelta($sql);
   
   $sql= 'CREATE TABLE `kv_tests` (
  `Id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL UNIQUE,
  `IsCompleted` tinyint(1) NOT NULL,
  `Html Display Code` text CHARACTER SET utf8 COLLATE utf8_bin
  ) ENGINE=InnoDB;';
   dbDelta($sql);
   
   $sql='CREATE TABLE `kv_users_results` (
  `UserId` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Test` int(11) NOT NULL,
  `FinalResults` text CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`UserId`,`Date`,`Test`)
  ) ENGINE=InnoDB;';
  dbDelta($sql);
  
  $sql = 'CREATE TABLE `kv_results` (
	`Id` int(11) NOT NULL,
	`Test` int(11) NOT NULL,
	`Result` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`Description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`Formula` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	 UNIQUE (`Test`,`Result`),
	 PRIMARY KEY (`Id`,`Test`)
	) ENGINE=InnoDB;';
	dbDelta($sql);
  
$wpdb->query('ALTER TABLE `kv_answers`
ADD CONSTRAINT `question` FOREIGN KEY (`Question`,`Test`) REFERENCES `kv_questions` (`Id`,`Test`) ON DELETE CASCADE ON UPDATE CASCADE;');
$wpdb->query('ALTER TABLE `kv_answers_categories`
ADD CONSTRAINT `ansid` FOREIGN KEY (`Answer id`) REFERENCES `kv_answers` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;');
$wpdb->query('ALTER TABLE `kv_questions`
ADD CONSTRAINT `qtest` FOREIGN KEY (`Test`) REFERENCES `kv_tests` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;');
$wpdb->query('ALTER TABLE `kv_results`
ADD CONSTRAINT `rtest` FOREIGN KEY (`Test`) REFERENCES `kv_tests` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;');
$wpdb->query('ALTER TABLE `kv_users_results`
ADD CONSTRAINT `test` FOREIGN KEY (`Test`) REFERENCES `kv_tests` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;');
}
?>