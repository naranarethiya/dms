CREATE TABLE dms_permission (
  dms_pid INT  NOT NULL   AUTO_INCREMENT,
  dms_pname VARCHAR(255)  NULL  ,
  dms_description TEXT  NULL    ,
PRIMARY KEY(dms_pid));



CREATE TABLE dms_tag (
  dms_tagid INT  NOT NULL   AUTO_INCREMENT,
  dms_tagname VARCHAR(255)  NULL  ,
  dms_description TEXT  NULL    ,
PRIMARY KEY(dms_tagid));



CREATE TABLE dms_company (
  dms_companyid INT  NOT NULL   AUTO_INCREMENT,
  dms_companyname VARCHAR(255)  NULL  ,
  created_at DATETIME  NULL    ,
PRIMARY KEY(dms_companyid));



CREATE TABLE dms_usergroup (
  dms_ugid INT  NOT NULL   AUTO_INCREMENT,
  dms_companyid INT  NOT NULL  ,
  dms_uid INT  NULL    ,
PRIMARY KEY(dms_ugid)  ,
INDEX dms_usergroup_FKIndex1(dms_companyid),
  FOREIGN KEY(dms_companyid)
    REFERENCES dms_company(dms_companyid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE dms_folder (
  dms_foid INT  NOT NULL   AUTO_INCREMENT,
  dms_foid_2 INT  NOT NULL  ,
  dms_companyid INT  NOT NULL  ,
  dms_foldername VARCHAR(255)  NULL    ,
PRIMARY KEY(dms_foid)  ,
INDEX dms_folder_FKIndex1(dms_companyid)  ,
INDEX dms_folder_FKIndex2(dms_foid_2),
  FOREIGN KEY(dms_companyid)
    REFERENCES dms_company(dms_companyid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_foid_2)
    REFERENCES dms_folder(dms_foid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE dms_setting (
  dms_companyid INT  NOT NULL  ,
  dms_ugid INT  NOT NULL  ,
  dms INT  NOT NULL  ,
  uid INT  NULL    ,
INDEX dms_setting_FKIndex1(dms_ugid)  ,
INDEX dms_setting_FKIndex2(dms_companyid),
  FOREIGN KEY(dms_ugid)
    REFERENCES dms_usergroup(dms_ugid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_companyid)
    REFERENCES dms_company(dms_companyid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE dms_file (
  dms_fid INT  NOT NULL   AUTO_INCREMENT,
  dms_companyid INT  NOT NULL  ,
  dms_foid INT  NOT NULL  ,
  dms_tagid INT  NOT NULL  ,
  dms_fname VARCHAR(255)  NULL  ,
  created_at INTEGER UNSIGNED  NULL    ,
PRIMARY KEY(dms_fid)  ,
INDEX dms_file_FKIndex1(dms_tagid)  ,
INDEX dms_file_FKIndex2(dms_foid)  ,
INDEX dms_file_FKIndex3(dms_companyid),
  FOREIGN KEY(dms_tagid)
    REFERENCES dms_tag(dms_tagid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_foid)
    REFERENCES dms_folder(dms_foid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_companyid)
    REFERENCES dms_company(dms_companyid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE dms_permissionset (
  dms_permissionsetid INT  NOT NULL   AUTO_INCREMENT,
  dms_ugid INT  NOT NULL  ,
  dms_pid INT  NOT NULL  ,
  dms_fid INT  NOT NULL  ,
  dms_foid INT  NOT NULL    ,
PRIMARY KEY(dms_permissionsetid)  ,
INDEX dms_permissionset_FKIndex1(dms_foid)  ,
INDEX dms_permissionset_FKIndex2(dms_fid)  ,
INDEX dms_permissionset_FKIndex3(dms_pid)  ,
INDEX dms_permissionset_FKIndex4(dms_ugid),
  FOREIGN KEY(dms_foid)
    REFERENCES dms_folder(dms_foid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_fid)
    REFERENCES dms_file(dms_fid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_pid)
    REFERENCES dms_permission(dms_pid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_ugid)
    REFERENCES dms_usergroup(dms_ugid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);



CREATE TABLE dms_lock (
  dms_lockid INT  NOT NULL   AUTO_INCREMENT,
  dms_fid INT  NOT NULL  ,
  dms_foid INT  NOT NULL  ,
  dms_companyid INT  NOT NULL    ,
PRIMARY KEY(dms_lockid)  ,
INDEX dms_lock_FKIndex1(dms_companyid)  ,
INDEX dms_lock_FKIndex2(dms_foid)  ,
INDEX dms_lock_FKIndex3(dms_fid),
  FOREIGN KEY(dms_companyid)
    REFERENCES dms_company(dms_companyid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_foid)
    REFERENCES dms_folder(dms_foid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(dms_fid)
    REFERENCES dms_file(dms_fid)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);




