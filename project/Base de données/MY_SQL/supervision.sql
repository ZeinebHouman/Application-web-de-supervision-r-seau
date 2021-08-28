/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  27/03/2020 15:33:06                      */
/*==============================================================*/


drop table if exists ADMINISTRATEUR;

drop table if exists DEPARTEMENT;

drop table if exists EQUIPEMENT;

drop table if exists LOCAL;

drop table if exists SUPERVISEUR;

drop table if exists UTILISATEUR;

/*==============================================================*/
/* Table : ADMINISTRATEUR                                       */
/*==============================================================*/
create table ADMINISTRATEUR
(
   ID_USER              numeric(8,0) not null,
   USERPSEUDO           char(15) not null,
   PASSWORD             char(15) not null,
   ROLE                 char(20) not null,
   primary key (ID_USER)
);

/*==============================================================*/
/* Table : DEPARTEMENT                                          */
/*==============================================================*/
create table DEPARTEMENT
(
   REF_DEP              numeric(8,0) not null,
   NOM_DEP              char(25) not null,
   primary key (REF_DEP)
);

/*==============================================================*/
/* Table : EQUIPEMENT                                           */
/*==============================================================*/
create table EQUIPEMENT
(
   ADRESSE_IP           char(20) not null,
   REF_LOC              numeric(8,0) not null,
   ID_USER              numeric(8,0) not null,
   MODELE_EQUIP         char(25) not null,
   REF_EQUIP            char(25) not null,
   ETAT                 char(15) not null,
   primary key (ADRESSE_IP)
);

/*==============================================================*/
/* Table : LOCAL                                                */
/*==============================================================*/
create table LOCAL
(
   REF_LOC              numeric(8,0) not null,
   REF_DEP              numeric(8,0) not null,
   ID_USER              numeric(8,0) not null,
   NOM_LOC              char(25) not null,
   primary key (REF_LOC)
);

/*==============================================================*/
/* Table : SUPERVISEUR                                          */
/*==============================================================*/
create table SUPERVISEUR
(
   ID_USER              numeric(8,0) not null,
   REF_LOC              numeric(8,0),
   ADM_ID_USER          numeric(8,0) not null,
   USERPSEUDO           char(15) not null,
   PASSWORD             char(15) not null,
   ROLE                 char(20) not null,
   primary key (ID_USER)
);

/*==============================================================*/
/* Table : UTILISATEUR                                          */
/*==============================================================*/
create table UTILISATEUR
(
   ID_USER              numeric(8,0) not null,
   USERPSEUDO           char(15) not null,
   PASSWORD             char(15) not null,
   ROLE                 char(20) not null,
   primary key (ID_USER)
);

alter table ADMINISTRATEUR add constraint FK_HERITAGE foreign key (ID_USER)
      references UTILISATEUR (ID_USER) on delete restrict on update restrict;

alter table EQUIPEMENT add constraint FK_APPARTENIR foreign key (REF_LOC)
      references LOCAL (REF_LOC) on delete restrict on update restrict;

alter table EQUIPEMENT add constraint FK_GERER foreign key (ID_USER)
      references ADMINISTRATEUR (ID_USER) on delete restrict on update restrict;

alter table LOCAL add constraint FK_ADMINISTRER foreign key (ID_USER)
      references ADMINISTRATEUR (ID_USER) on delete restrict on update restrict;

alter table LOCAL add constraint FK_SE_SITUER foreign key (REF_DEP)
      references DEPARTEMENT (REF_DEP) on delete restrict on update restrict;

alter table SUPERVISEUR add constraint FK_CONTROLER foreign key (ADM_ID_USER)
      references ADMINISTRATEUR (ID_USER) on delete restrict on update restrict;

alter table SUPERVISEUR add constraint FK_HERITAGE2 foreign key (ID_USER)
      references UTILISATEUR (ID_USER) on delete restrict on update restrict;

alter table SUPERVISEUR add constraint FK_SUPERVISER foreign key (REF_LOC)
      references LOCAL (REF_LOC) on delete restrict on update restrict;

