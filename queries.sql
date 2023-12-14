
-- The queries bellow will tell the time the row was updated at in syncronization
 -- UPDATE THE PASSOWRD
UPDATE users SET `password` = CONCAT(sha2(`password`, 512), '') WHERE id > 0;
-- Setting the password to default 123456 to allusers
UPDATE users SET password = '123456' where id > 0; 

-- Create the table for audits
CREATE TABLE IF NOT EXISTS `auditlogs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` TEXT NULL,
  `subject_id` BIGINT(20) UNSIGNED NULL,
  `subject_type` VARCHAR(50) NULL,
  `old_properties` LONGTEXT NULL, -- JSON DATA
  `properties` LONGTEXT NULL, -- JSON DATA
  `user_id` BIGINT(20) UNSIGNED NULL,
  `platform` VARCHAR(45) NULL, -- Web / Mobile
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `profiles` ADD COLUMN `deleted_at` DATETIME NULL ;
ALTER TABLE `auditlogs` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `auditlogs` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `chemical_acquisition` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `chemical_application` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `equipments` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `expenses_incomes` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `payments_aggreement` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `plots` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `preparatory_activity` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `users` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `payments` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;
ALTER TABLE `expenses_incomes` CHANGE `description` `description` TEXT NULL;
-- ALTER TABLE `auditlogs` CHANGE `description` `description` TEXT NULL;

-- ALTER TABLE `profiles` ADD COLUMN `deleted_at` DATETIME NULL AFTER `updated_at`;

-- updating the provinces and district of the lot
UPDATE plots
INNER JOIN farmers ON farmers.farmer_uid = plots.farmer_uid
SET plots.province = farmers.province, plots.district = farmers.district
WHERE plots.id > 0;


CREATE TABLE `dropdowns` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `value` TEXT NULL,
  `type` VARCHAR(45) NULL DEFAULT 'string',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),

CREATE TABLE `locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `post` VARCHAR(100) UNIQUE NULL,
  `latitude` VARCHAR(45) NULL,
  `longitude` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));
  UNIQUE INDEX `key_UNIQUE` (`name` ASC) VISIBLE);
-- Sprint 5

CREATE TABLE IF NOT EXISTS `requests` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` TEXT NULL,
  `mobile_number` VARCHAR(45) NOT NULL ,
  `province` VARCHAR(45) NOT NULL ,
  `district` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(10) NOT NULL,
  `approved` SMALLINT DEFAULT 0 NOT NULL,
  `approved_by` VARCHAR(100)  NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`)
  );



CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  `last_sync_at` DATETIME NULL,
  PRIMARY KEY (`id`)
  );

CREATE TABLE IF NOT EXISTS `campains` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `opening` DATE  NULL,
  `clossing` DATE  NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL,
  `last_sync_at` DATETIME NULL,
  PRIMARY KEY (`id`)
  );

  -- Cleaning the database
  DELETE FROM users WHERE profile_id IN (4, 5, 6) AND id > 0;
  DELETE FROM profiles WHERE id IN (4, 5, 6) AND id > 0;
-- Adding columns to accomodte password reset
ALTER TABLE users
  ADD `reset_token_hash` VARCHAR(64) NULL DEFAULT NULL,
  ADD `reset_token_expires_at` DATETIME NULL DEFAULT NULL,
  ADD UNIQUE (`reset_token_hash`);


ALTER TABLE campains ADD `closed_date` DATE  NULL DEFAULT NULL;

--SPRINT 10 CHANGES
 ALTER TABLE `campains` RENAME `campaigns`;

 ALTER TABLE `campaigns` CHANGE `clossing` `closing` DATE NULL;

 UPDATE auditlogs set subject_type='campaigns' WHERE subject_type = 'campains' AND id > 0;

 UPDATE auditlogs set description=REPLACE(description, 'campain', 'campaign') WHERE subject_type = 'campaigns' AND id > 0;


ALTER TABLE `users` ADD COLUMN `gender` ENUM('Female', 'Male') DEFAULT 'Male' ;

