/*
Created		15-06-20
Modified	15-06-20
Project		
Model		
Company		
Author		
Version		
Database	Firebird 
*/


Drop Table "ALBUMS";


Create Table "ALBUMS"  (
	"ID" Integer NOT NULL,
	"ARTIST" Varchar(100) NOT NULL,
	"TITLE" Varchar(100) NOT NULL,
 Primary Key ("ID")
);


Create Exception "except_del_p" 'Children still exist in child table. Cannot delete parent.';
Create Exception "except_ins_ch" 'Parent does not exist. Cannot create child.';
Create Exception "except_upd_ch" 'Parent does not exist. Cannot update child.';
Create Exception "except_upd_p" 'Children still exist in child table. Cannot update parent.';
Create Exception "except_ins_ch_card" 'Maximum cardinality exceeded. Cannot insert into child.';
Create Exception "except_upd_ch_card" 'Maximum cardinality exceeded. Cannot update child.';


set term ^;


set term ;^


/* Roles permissions */


/* Users permissions */


/* "Autoinc" functionality for ALBUMS_ID*/ 
CREATE SEQUENCE "SEQ_ALBUMS_ID";
SET TERM ^ ;
CREATE TRIGGER "TR_BI_ALBUMS_ID" FOR "ALBUMS"
ACTIVE BEFORE INSERT POSITION 0
AS
BEGIN
  IF (NEW."ID" IS NULL) THEN NEW."ID" = GEN_ID("SEQ_ALBUMS_ID",1); 
END
^
CREATE PROCEDURE "P_ALBUMS_ID"
RETURNS (
    "AUTOINC" INTEGER)
AS
BEGIN  
	"AUTOINC" = GEN_ID("SEQ_ALBUMS_ID",1);
   SUSPEND;
END
^
SET TERM ; ^

