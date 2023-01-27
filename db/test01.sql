SELECT * FROM "T_EPHEMERIDES" WHERE "STARTDATEPHEMERIDE" >= '2021-09-27' AND "ENDDATEPHEMERIDE" <= '2021-09-27';

    /*
     * 2023-01-01 - 2023-31-01
     *  01/12/2022-01/01/2023 : Fail
     *  01/12/2022-15/01/2023 : Ok
     *  02/01/2023-15/01/2023 : Fail
     *  15/01/2023-31/01/2023 : Fail
     *  15/01/2023-01/02/2023 : Ok
     *  01/12/2022-01/02/2023 : Ok
     */

// 01/12/2022-01/01/2023     
SELECT * FROM "T_EPHEMERIDES" WHERE "STARTDATEPHEMERIDE" >= '2022-12-01' AND "ENDDATEPHEMERIDE" <= '2023-01-01';
SELECT * FROM "T_EPHEMERIDES" WHERE connect connect t'2022-12-01' <= "STARTDATEPHEMERIDE" AND "ENDDATEPHEMERIDE" <= '2023-01-01';

// 01/12/2022-15/01/2023 
SELECT * FROM "T_EPHEMERIDES" WHERE "STARTDATEPHEMERIDE" >= '2022-12-01' AND "ENDDATEPHEMERIDE" <= '2023-01-15';

// 02/01/2023-15/01/2023
SELECT * FROM "T_EPHEMERIDES" WHERE "STARTDATEPHEMERIDE" >= '2023-01-02' AND "ENDDATEPHEMERIDE" <= '2023-01-15';

/*
 tester si date unique déjà présente
 tester si date début n'est pas dans une plage
 tester si date fin n'est pas dans une plage
 tester si nouvelle éphéméride n'englobe pas une existante <> date unique

 retourner un code 
 0 pas trouvée
 1 date début trouvée
 2 date fin trouvée
 3 date unique trouvée
 4 nouvelle éphéméride englobant une autre
*/
// Test date unique déjà présente 2022-12-25
SELECT * FROM "T_EPHEMERIDES" WHERE '2022-12-25' = "STARTDATEPHEMERIDE" AND '2022-12-25' = "ENDDATEPHEMERIDE";

// Test date fin dans une plage 2023-01-01
SELECT * FROM "T_EPHEMERIDES" WHERE '2023-01-01' >= "STARTDATEPHEMERIDE" AND '2023-01-01' <= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";

// Test date début dans une plage 2023-01-31
SELECT * FROM "T_EPHEMERIDES" WHERE '2023-01-31' >= "STARTDATEPHEMERIDE" AND '2023-01-31' <= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";

// Test date englobe plage (qui n est pas une date unique)
SELECT * FROM "T_EPHEMERIDES" WHERE '2022-12-01' <= "STARTDATEPHEMERIDE" AND '2022-12-31' >= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";
