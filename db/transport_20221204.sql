CREATE DATABASE 'srv-db-01.chablerie.net:transport'
USER 'SYSDBA' PASSWORD '**********'
PAGE_SIZE 16384
DEFAULT CHARACTER SET UTF8;

/* Tables */
CREATE TABLE T_ANNEESSCOLAIRES (
  IDX_ANNEESCOLAIRE   INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  ANNEEANNEESCOLAIRE  INTEGER NOT NULL
) ;

CREATE TABLE T_CHAUFFEURS (
  IDX_CHAUFFEUR       INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  PRENOMCHAUFFEUR     VARCHAR(20) NOT NULL,
  PRINCIPALCHAUFFEUR  BOOLEAN DEFAULT TRUE,
  ACTIFCHAUFFEUR      BOOLEAN DEFAULT TRUE
) ;

CREATE TABLE T_EPHEMERIDES (
  IDX_EPHEMERIDE      INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  IDX_ANNEESCOLAIRE   INTEGER NOT NULL,
  NOMEPHEMERIDE       VARCHAR(60) NOT NULL,
  STARTDATEPHEMERIDE  DATE NOT NULL,
  ENDDATEPHEMERIDE    DATE NOT NULL
) ;

CREATE TABLE T_INDISPONIBILITES (
  IDX_T_INDISPONIBILITE               INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  JOURENTIERINDISPONIBILITECHAUFFEUR  BOOLEAN DEFAULT FALSE
) ;

CREATE TABLE T_INDISPONIBILITESCHAUFFEURS (
  IDX_INDISPONIBILITECHAUFFEUR  INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  IDX_CHAUFFEUR                 INTEGER NOT NULL,
  STARTDATEINDISPONIBILITE      DATE NOT NULL,
  STARTTIMEINDISPONIBILITE      TIME,
  ENDDATEINDISPONIBILITE        DATE NOT NULL,
  ENDTIMEINDISPONIBILITE        TIME,
  ALLDAYINDISPONIBILITE         BOOLEAN
) ;

CREATE TABLE T_INDISPONIBILITESVEHICULES (
  IDX_INDISPONIBILITEVEHICULE       INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  IDX_VEHICULE                      INTEGER NOT NULL,
  DATEDEBUTINDISPONIBILITEVEHICULE  DATE NOT NULL,
  TIMEDEBUTINDISPONIBILITEVEHICULE  TIME NOT NULL,
  DATEFININDISPONIBILITEVEHICULE    DATE NOT NULL,
  TIMEFININDISPONIBILITEVEHICULE    TIME NOT NULL
) ;

CREATE TABLE T_MARQUES (
  IDX_MARQUE  INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  NOMMARQUE   VARCHAR(30) NOT NULL
) ;

CREATE TABLE T_TEST2 (
  IDX_CHAUFFEUR       INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  PRENOMCHAUFFEUR     VARCHAR(20) NOT NULL,
  PRINCIPALCHAUFFEUR  BOOLEAN DEFAULT TRUE,
  ACTIFCHAUFFEUR      BOOLEAN DEFAULT TRUE
) ;

CREATE TABLE T_TEST3 (
  IDX_INDISPONIBILITE  INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  JOURENTIER           BOOLEAN DEFAULT TRUE
) ;

CREATE TABLE T_TYPESVEHICULES (
  IDX_TYPEVEHICULE  INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  NOMTYPEVEHICULE   VARCHAR(20) NOT NULL
) ;

CREATE TABLE T_VEHICULES (
  IDX_VEHICULE      INTEGER GENERATED BY DEFAULT AS IDENTITY NOT NULL,
  IDX_MARQUE        INTEGER NOT NULL,
  IDX_TYPEVEHICULE  INTEGER NOT NULL,
  NOMVEHICULE       VARCHAR(30) NOT NULL,
  PLACESVEHICULES   INTEGER NOT NULL,
  NUMEROVEHICULE    SMALLINT NOT NULL,
  PLAQUEVEHICULE    VARCHAR(9) NOT NULL,
  MODELEVEHICULE    VARCHAR(30)
) ;
COMMIT;

/* Views */
CREATE VIEW V_EPHEMERIDES
(
  IDX_EPHEMERIDE,
  IDX_ANNEESCOLAIRE,
  NOMEPHEMERIDE,
  STARTDATEPHEMERIDE,
  ENDDATEPHEMERIDE,
  ANNEEANNEESCOLAIRE
)
AS
SELECT 
  T_EPHEMERIDES.IDX_EPHEMERIDE,
  T_EPHEMERIDES.IDX_ANNEESCOLAIRE,
  T_EPHEMERIDES.NOMEPHEMERIDE,
  T_EPHEMERIDES.STARTDATEPHEMERIDE,
  T_EPHEMERIDES.ENDDATEPHEMERIDE,
  T_ANNEESSCOLAIRES.ANNEEANNEESCOLAIRE
FROM 
  T_EPHEMERIDES
  LEFT OUTER JOIN T_ANNEESSCOLAIRES ON (T_EPHEMERIDES.IDX_ANNEESCOLAIRE = T_ANNEESSCOLAIRES.IDX_ANNEESCOLAIRE)
ORDER BY
  T_ANNEESSCOLAIRES.ANNEEANNEESCOLAIRE DESC, T_EPHEMERIDES.STARTDATEPHEMERIDE DESC;
  
CREATE VIEW V_INDISPONIBILITESCHAUFFEURS
(
  IDX_INDISPONIBILITECHAUFFEUR,
  IDX_CHAUFFEUR,
  PRENOMCHAUFFEUR,
  STARTDATEINDISPONIBILITE,
  STARTTIMEINDISPONIBILITE,
  ENDDATEINDISPONIBILITE,
  ENDTIMEINDISPONIBILITE,
  ALLDAYINDISPONIBILITE
)
AS
SELECT 
  T_INDISPONIBILITESCHAUFFEURS.IDX_INDISPONIBILITECHAUFFEUR,
  T_INDISPONIBILITESCHAUFFEURS.IDX_CHAUFFEUR,
  T_CHAUFFEURS.PRENOMCHAUFFEUR,
  T_INDISPONIBILITESCHAUFFEURS.STARTDATEINDISPONIBILITE,
  T_INDISPONIBILITESCHAUFFEURS.STARTTIMEINDISPONIBILITE,
  T_INDISPONIBILITESCHAUFFEURS.ENDDATEINDISPONIBILITE,
  T_INDISPONIBILITESCHAUFFEURS.ENDTIMEINDISPONIBILITE,
  T_INDISPONIBILITESCHAUFFEURS.ALLDAYINDISPONIBILITE
FROM 
  T_INDISPONIBILITESCHAUFFEURS
  LEFT OUTER JOIN T_CHAUFFEURS ON (T_INDISPONIBILITESCHAUFFEURS.IDX_CHAUFFEUR = T_CHAUFFEURS.IDX_CHAUFFEUR)
ORDER BY
   T_INDISPONIBILITESCHAUFFEURS.STARTDATEINDISPONIBILITE DESC, T_CHAUFFEURS.PRENOMCHAUFFEUR;

/* Constraints */
ALTER TABLE T_ANNEESSCOLAIRES
  ADD PRIMARY KEY (IDX_ANNEESCOLAIRE);

ALTER TABLE T_CHAUFFEURS
  ADD PRIMARY KEY (IDX_CHAUFFEUR);

ALTER TABLE T_EPHEMERIDES
  ADD PRIMARY KEY (IDX_EPHEMERIDE);

ALTER TABLE T_INDISPONIBILITES
  ADD PRIMARY KEY (IDX_T_INDISPONIBILITE);

ALTER TABLE T_INDISPONIBILITESCHAUFFEURS
  ADD PRIMARY KEY (IDX_INDISPONIBILITECHAUFFEUR);

ALTER TABLE T_INDISPONIBILITESVEHICULES
  ADD PRIMARY KEY (IDX_INDISPONIBILITEVEHICULE);

ALTER TABLE T_MARQUES
  ADD PRIMARY KEY (IDX_MARQUE);

ALTER TABLE T_TEST2
  ADD PRIMARY KEY (IDX_CHAUFFEUR);

ALTER TABLE T_TEST3
  ADD PRIMARY KEY (IDX_INDISPONIBILITE);

ALTER TABLE T_TYPESVEHICULES
  ADD PRIMARY KEY (IDX_TYPEVEHICULE);

ALTER TABLE T_VEHICULES
  ADD PRIMARY KEY (IDX_VEHICULE);

COMMIT;
/* Foreign Keys */
ALTER TABLE T_EPHEMERIDES
  ADD CONSTRAINT T_EPHEMERIDES_FOREIGN_KEY01
  FOREIGN KEY (IDX_ANNEESCOLAIRE)
    REFERENCES T_ANNEESSCOLAIRES(IDX_ANNEESCOLAIRE)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE T_INDISPONIBILITESCHAUFFEURS
  ADD CONSTRAINT T_INDISPONIBILITESCHAUFFEURS_FOREIGN_KEY01
  FOREIGN KEY (IDX_CHAUFFEUR)
    REFERENCES T_CHAUFFEURS(IDX_CHAUFFEUR)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE T_INDISPONIBILITESVEHICULES
  ADD CONSTRAINT T_INDISPONIBILITESVEHICULES_FOREIGN_KEY01
  FOREIGN KEY (IDX_VEHICULE)
    REFERENCES T_VEHICULES(IDX_VEHICULE)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE T_VEHICULES
  ADD CONSTRAINT T_VEHICULES_FOREIGN_KEY01
  FOREIGN KEY (IDX_MARQUE)
    REFERENCES T_MARQUES(IDX_MARQUE)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE T_VEHICULES
  ADD CONSTRAINT T_VEHICULES_FOREIGN_KEY02
  FOREIGN KEY (IDX_TYPEVEHICULE)
    REFERENCES T_TYPESVEHICULES(IDX_TYPEVEHICULE)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE T_VEHICULES
  ADD CONSTRAINT T_VEHICULES_FOREIGN_KEY03
  FOREIGN KEY (IDX_MARQUE)
    REFERENCES T_MARQUES(IDX_MARQUE)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

/* Data for table  */
INSERT INTO T_ANNEESSCOLAIRES (IDX_ANNEESCOLAIRE, ANNEEANNEESCOLAIRE) VALUES (1, 2021);
COMMIT;

/* Data for table  */
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (1, 'Thierry', FALSE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (2, 'Karim', TRUE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (22, 'Yasser', TRUE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (3, 'Miguel', TRUE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (4, 'Ali', FALSE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (23, 'Idriz', TRUE, TRUE);
INSERT INTO T_CHAUFFEURS (IDX_CHAUFFEUR, PRENOMCHAUFFEUR, PRINCIPALCHAUFFEUR, ACTIFCHAUFFEUR) VALUES (6, 'Magalie', FALSE, TRUE);
COMMIT;

/* Data for table  */
INSERT INTO T_EPHEMERIDES (IDX_EPHEMERIDE, IDX_ANNEESCOLAIRE, NOMEPHEMERIDE, STARTDATEPHEMERIDE, ENDDATEPHEMERIDE) VALUES (1, 1, 'CFWB', '2021-09-27', '2021-09-27');
COMMIT;

/* Data for table  */

/* Data for table  */
INSERT INTO T_INDISPONIBILITESCHAUFFEURS (IDX_INDISPONIBILITECHAUFFEUR, IDX_CHAUFFEUR, STARTDATEINDISPONIBILITE, STARTTIMEINDISPONIBILITE, ENDDATEINDISPONIBILITE, ENDTIMEINDISPONIBILITE, ALLDAYINDISPONIBILITE) VALUES (19, 4, '2022-11-29', '00:00:00', '2022-11-29', '23:59:00', TRUE);
INSERT INTO T_INDISPONIBILITESCHAUFFEURS (IDX_INDISPONIBILITECHAUFFEUR, IDX_CHAUFFEUR, STARTDATEINDISPONIBILITE, STARTTIMEINDISPONIBILITE, ENDDATEINDISPONIBILITE, ENDTIMEINDISPONIBILITE, ALLDAYINDISPONIBILITE) VALUES (21, 4, '2022-11-30', '10:00:00', '2022-11-29', '10:20:00', FALSE);
COMMIT;

/* Data for table  */

/* Data for table  */
INSERT INTO T_MARQUES (IDX_MARQUE, NOMMARQUE) VALUES (1, 'Mercedes');
COMMIT;

/* Data for table  */
INSERT INTO T_TYPESVEHICULES (IDX_TYPEVEHICULE, NOMTYPEVEHICULE) VALUES (1, 'Autocar');
INSERT INTO T_TYPESVEHICULES (IDX_TYPEVEHICULE, NOMTYPEVEHICULE) VALUES (2, 'Camionette');
COMMIT;

/* Data for table  */

