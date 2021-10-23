<?php

$dbh->exec("create database `$db`");

$dbh->query("use $db");

$dbh->query('create table UTENTE (
	Username varchar(30) not null,
	Email varchar(50) not null,
	Password varchar(255) not null,
	primary key (Username));
');

$dbh->query('create table RISTORANTE (
	NomeRistorante varchar(50) not null,
	Indirizzo varchar(50) not null,
	Username varchar(30) not null,
	primary key (NomeRistorante));
');

$dbh->query('create table PRANZO (
	NomeRistorante varchar(50) not null,
	Id_pranzo int(11) not null auto_increment,
	NumPartecipanti numeric(2) not null,
	Giorno char(10) not null,
	Orario varchar(15) not null,
	primary key (Id_pranzo));
');

$dbh->query('create table INVITO (
	Id_pranzo int(11) not null,
	NomeRistorante varchar(50) not null,
	Username varchar(30) not null,
	Id_invito int(11) not null auto_increment,
	primary key (Id_invito));
');

$dbh->query('
  alter table RISTORANTE add constraint fk_proposta
  foreign key (Username)
  references UTENTE(Username);
');

$dbh->query('
  alter table PRANZO add constraint fk_da
  foreign key (NomeRistorante)
  references RISTORANTE(NomeRistorante);
');

$dbh->query('
  alter table INVITO add constraint fk_a
	foreign key (NomeRistorante)
	references PRANZO(NomeRistorante);
');

$dbh->query('
  alter table INVITO add constraint fk_rel
  foreign key (Id_pranzo)
  references PRANZO(Id_pranzo);
');

$dbh->query('
  alter table INVITO add constraint fk_per
	foreign key (Username)
	references UTENTE(Username);
');

?>
