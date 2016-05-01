--2016/4/30
alter table wsg_official_number add access_token varchar(128) null;
alter table wsg_official_number add expire_time int null;
alter table wsg_official_number add original_id varchar(32) NULL;

--2016/5/1
alter table wsg_keywords modify column media_id varchar(64) null;
alter table wsg_user modify column user_open_id varchar(32) not null;
alter table wsg_official_number add ticket varchar(64) null;
alter table wsg_official_number add ticket_expire int null;