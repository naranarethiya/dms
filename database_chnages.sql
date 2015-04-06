ALTER TABLE  `users` CHANGE  `role`  `role` ENUM(  'admin',  'superadmin',  'user',  'manager' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE  `dms_usergroup` ADD  `dms_puid` INT NOT NULL AFTER  `dms_uid`

ALTER TABLE  `users` ADD  `dms_companyid` INT NOT NULL AFTER  `company_name`

ALTER TABLE  `users` ADD  `deleted_at` DATETIME NULL AFTER  `update_on`

ALTER TABLE  `dms_folder` DROP FOREIGN KEY  `dms_folder_ibfk_2` ;

ALTER TABLE  `dms_folder` DROP FOREIGN KEY  `dms_folder_ibfk_1` ;

ALTER TABLE  `dms_file` DROP FOREIGN KEY  `dms_file_ibfk_3` ;

ALTER TABLE  `dms_file` DROP FOREIGN KEY  `dms_file_ibfk_2` ;

ALTER TABLE  `dms_file` DROP FOREIGN KEY  `dms_file_ibfk_1` ;