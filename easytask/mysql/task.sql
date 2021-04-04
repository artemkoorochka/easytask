create table if not exists beegee_task
(
	ID int(11) not null auto_increment,
	STATUS char(1) not null,
	NAME varchar(50) not null,
	EMAIL varchar(255) not null,
	TEXT text not null,
	PRIMARY KEY(ID)
);