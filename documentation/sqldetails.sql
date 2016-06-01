create table users (
    id VARCHAR2(100),
    key VARCHAR2(200),
    ip VARCHAR2(100),
    email VARCHAR2(100),
    confirmed INT
);
alter table users
    add constraint users_pk PRIMARY KEY(id);
alter table users
    add constraint users_nn check(key is not null);
alter table users
	add constraint users_ue unique(email);
alter table users
	add constraint users_nn1 check(email is not null);
alter table users
	add constraint users_dc check(confirmed = 0 or confirmed = 1);
alter table users
	add constraint users_nn2 check(confirmed is not null);
----------------------------------------------------------------------
create table profile (
	id VARCHAR2(100),
	fname VARCHAR2(100),
	lname VARCHAR2(100)
);
alter table profile
	add constraint profile_pk PRIMARY KEY(id);
alter table profile
	add constraint profile_fk FOREIGN KEY(id) references users(id) on delete cascade;
----------------------------------
create table verification(
    email VARCHAR2(100),
    vhash VARCHAR2(100)
);
alter table verification
    add constraint verification_pk PRIMARY KEY(email);
alter table verification
    add constraint verification_fk foreign key(email) references users(email) on delete cascade;
alter table verification
    add constraint verification_nn check(vhash is not null);
---------------------------------------------------------
alter table profile
    add (dob date,
         city VARCHAR2(100),
         country VARCHAR2(100),
         phone VARCHAR2(100),
         gender INT
    );
alter table profile
    add constraint profile_dc CHECK(gender = 1 or gender = 0);
alter table profile
    add fpublic INT;
alter table profile
   add constraint profile_dc1 check(fpublic = 0 or fpublic = 1);
---------------------------------------------------------
create table friends(
    id VARCHAR2(100),
    fid VARCHAR2(100)
);
alter table friends
    add constraint friends_fk FOREIGN KEY(id) references users(id) on delete cascade;
alter table friends
    add constraint friends_fk1 FOREIGN KEY(fid) references users(id) on delete cascade;
alter table friends
    add constraint friends_pk PRIMARY KEY(id,fid);
------------------------------------------------------------------------------------
alter table profile
	add dpname VARCHAR2(100);
alter table profile
    add constraint profile_nn check(fpublic is not null);
------------------------------------------------------------------------------------
create table messages(
   sid VARCHAR2(100),
   rid VARCHAR2(100),
   dos date
);
alter table messages
    add constraint messages_pk PRIMARY KEY(sid,rid,dos);
alter table messages
    add constraint messages_fk FOREIGN KEY(sid) references users(id) on delete cascade;
alter table messages
    add constraint messages_fk1 FOREIGN KEY(rid) references users(id) on delete cascade;
alter table messages
    add mes VARCHAR2(4000);
alter table messages
	add constraint messages_nn check(mes is not null);
truncate table messages;
alter table messages
    add mid VARCHAR2(100);
alter table messages
    drop constraint messages_pk;
alter table messages
	add constraint messages_pk primary key(mid);
alter table messages
	add constraint messages_uq unique(sid,rid,dos);
----------------------------------------------------------------------------------------
create table unread(
    sid VARCHAR2(100),
    rid VARCHAR2(100),
    dos date
);
alter table unread
    add constraint unread_pk PRIMARY KEY(sid,rid,dos);
alter table unread
    add constraint unread_fk FOREIGN KEY(sid) references users(id) on delete cascade;
alter table unread
    add constraint unread_fk1 FOREIGN KEY(rid) references users(id) on delete cascade;
alter table unread
    add mes VARCHAR2(4000);
alter table unread
	add constraint unread_nn check(mes is not null);
alter table unread
    drop constraint unread_pk;
alter table unread
   add mid VARCHAR2(100);
truncate table unread;
alter table unread
   add constraint unread_pk primary key(mid);
alter table unread
	add constraint unread_uq unique(sid,rid,dos);
------------------------------------------------------------------------------------------
create table posts(
    pid VARCHAR2(100),
    puid VARCHAR2(100),
    pdata VARCHAR2(100),
    dop date
);
alter table posts
    add constraint posts_pk primary key(pid);
alter table posts
    add constraint posts_fk foreign key(puid) references users(id) on delete cascade;
alter table posts
    add constraint posts_nn check(pdata is not null);
alter table posts
    modify pdata varchar2(4000);