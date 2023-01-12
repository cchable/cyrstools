<?php
/**
 * @package   : module/Transport/src/Service/EphemerideManager.php
 *
 * @purpose   : This service is responsible for adding/editing ephemeride. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\Ephemeride;
use Transport\Model\EphemerideTable;
use Transport\Model\AnneeScolaireTable;


/*
 * 
 */
class EphemerideManager
{
  
  /*
   * Ephemeride table manager.
   * @var Parling\Model\EphemerideTable
   */
  private $ephemerideTable;
    
  /*
   * AnneeScolaire table manager.
   * @var Parling\Model\AnneeScolaireTable
   */
  private $anneeScolaireTable;
  
  /*
   * PHP template renderer.
   * @var type 
   */
  private $viewRenderer;

  /*
   * Application config.
   * @var type 
   */
  private $config;

  
  /*
   * Constructs the service.
   */
  public function __construct(EphemerideTable $ephemerideTable, AnneeScolaireTable $anneeScolaireTable, $viewRenderer, $config) 
  {
    
    $this->ephemerideTable    = $ephemerideTable;
    $this->anneeScolaireTable = $anneeScolaireTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new ephemeride.
   */
  public function addEphemeride($data) 
  {
    
    // Do not allow to add ephemeride if another ephemeride with such data already exists
    $result = $this->checkEphemerideExists($data);
    if($result) {
  
      return $result;
    }

    // Create new Ephemeride entity.
    $newEphemeride= new Ephemeride();
    $newEphemeride->exchangeArray($data);  
    $ephemeride = $this->ephemerideTable->saveEphemeride($newEphemeride);
    
    return $ephemeride;
  }
    
  /*
   * This method update datas of an existing ephemeride
   */
  public function updateEphemeride($ephemeride, $data) 
  {
     
    // Do not allow to change ephemeride if another ephemeride with such data already exists
    $code = $this->checkEphemerideExists($data);
    if($code) {
         
      return $code;
    }
    
    $ephemeride->exchangeArray($data, false);
    // Apply changes to database.
    $this->ephemerideTable->saveEphemeride($ephemeride);
    
    return $ephemeride;
  }
  
  /**
   * Deletes the given ephemeride.
   */
  public function deleteEphemeride($id)
  {
    
    $this->ephemerideTable->deleteEphemeride($id);
  }

    /*
   *
   */
  public function checkEphemerideExistsOld2(Ephemeride $ephemeride, array $newData) {
  
    if(   
      $newData['IDX_ANNEESCOLAIRE'] != $ephemeride->getIdAnneeScolaire() 
      ||
      $newData['NOMEPHEMERIDE']     != $ephemeride->getNomEphemeride()
    ) {
      
      $search['IDX_ANNEESCOLAIRE'] = $newData['IDX_ANNEESCOLAIRE'];
      $search['NOMEPHEMERIDE']     = $newData['NOMEPHEMERIDE'];
      if($this->ephemerideTable->findOneByRecord($search)) {
        
        return true;
      }
    } 
      
    if(   
      $newData['STARTDATEPHEMERIDE'] != $ephemeride->getDateDebut() 
      ||
      $newData['ENDDATEPHEMERIDE']   != $ephemeride->getDateFin()
    ) {

      $search2['STARTDATEPHEMERIDE'] = $newData['STARTDATEPHEMERIDE'];
      $search2['ENDDATEPHEMERIDE']   = $newData['ENDDATEPHEMERIDE'];
      if($this->checkEphemerideDatesExists($search2)) {

        return true;
      }     
    }
    
    return false;
  }  
  /*
   *
   */
  public function checkEphemerideExistsOld(Ephemeride $ephemeride, array $newData) {
    
    $search['IDX_ANNEESCOLAIRE'] = $newData['IDX_ANNEESCOLAIRE'];
    $search['NOMEPHEMERIDE']     = $newData['NOMEPHEMERIDE'];
    $search2['NOMEPHEMERIDE']    = $newData['NOMEPHEMERIDE'];
    
    return (
      (
        $this->ephemerideTable->findOneByRecord($search)    // check same name and AS
        &&
        $ephemeride->getDateDebut() == $newData['STARTDATEPHEMERIDE']
        &&
        $ephemeride->getDateFin() == $newData['ENDDATEPHEMERIDE']
      )   
      ||
      (
        !$this->ephemerideTable->findOneByRecord($search2) // check name <> and same date
        &&
        $this->checkEphemerideDatesExists($newData)
      )      
    );
  }
  
  /**
   * Recherche si une éphéméride existe déjà
   *
   * @param array $data
   * @return int $code 0 : pas trouvé
   *                   1 : éphéméride unique trouvée
   *                   2 : date de fin de l'éphéméride est comprise dans une autre éphéméride
   *                   3 : date de début de l'éphéméride est comprise dans une autre éphéméride
   *                   4 : éphéméride englobe une autre éphéméride (qui n'est pas de 1 jour)
   * @access public
   */  
  public function checkEphemerideExists(array $data) 
  {

    /**
     * Teste si une date unique 'X' est déjà présente
     * En SQL : SELECT * FROM "T_EPHEMERIDES" WHERE 'X' = "STARTDATEPHEMERIDE" AND 'X' = "ENDDATEPHEMERIDE";
     * Si trouvé, return 1. Sinon on continue la recherche avec d'autre critère
     */
    if($this->ephemerideTable->findOneByUniqueDate($data)){

      return 1;
    }
    
    /**
     * Teste si la date de fin d'éphéméride 'ENDDATEPHEMERIDE' est comprise dans une éphéméride
     * En SQL : SELECT * FROM "T_EPHEMERIDES" WHERE 'END' >= "STARTDATEPHEMERIDE" AND 'END' <= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";
     * Si trouvé, return 2. Sinon on continue la recherche avec d'autre critère
     */
    if($this->ephemerideTable->checkBetweenDates($data['ENDDATEPHEMERIDE'])){
      
      return 2;
    }
    
    /**
     * Teste si la date de début d'éphéméride 'STARTDATEPHEMERIDE' est comprise dans une éphéméride
     * En SQL : SELECT * FROM "T_EPHEMERIDES" WHERE 'BEGIN' >= "STARTDATEPHEMERIDE" AND 'BEGIN' <= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";
     * Si trouvé, return 3. Sinon on continue la recherche avec d'autre critère
     */
    if($this->ephemerideTable->checkBetweenDates($data['STARTDATEPHEMERIDE'])){
      
      return 3;
    }
 
    /**
     * Teste si une éphéméride n'englobe pas une autre éphéméride qui n'est pas une date unique
     * En SQL : SELECT * FROM "T_EPHEMERIDES" WHERE '2022-12-01' <= "STARTDATEPHEMERIDE" AND '2022-12-31' >= "ENDDATEPHEMERIDE" AND "STARTDATEPHEMERIDE" <> "ENDDATEPHEMERIDE";
     * Si trouvé, return 4. Sinon la recherche est terminée
     */ 
    if($this->ephemerideTable->checkEncloseDates($data)){
      
      return 4;
    }
  
    return 0;
  }
  
  /*
   *
   */
  /*
  public function checkEphemerideDatesExists(array $data) 
  {

    $search['STARTDATEPHEMERIDE'] = $data['STARTDATEPHEMERIDE'];
    $ephemeride = $this->ephemerideTable->findOneBy($search);
    $bResult = false;
    
    if($ephemeride) {
      
      $bResult = true;
    } else {
      
      $bResult = ($this->ephemerideTable->checkBetweenDates($data['STARTDATEPHEMERIDE']) 
                  ||
                  $this->ephemerideTable->checkBetweenDates($data['ENDDATEPHEMERIDE']));
    }
    
    return $bResult;
  }
*/
  /**
   * 
   */
  public function getAnneesScolaires() 
  {
    
    $anneesScolairesList = [];
    
    $anneesScolaires = $this->anneeScolaireTable->fetchAll();

    foreach ($anneesScolaires as $anneeScolaire) {
      $anneesScolairesList[$anneeScolaire->getId()] = $anneeScolaire->getAnneeScolaire();
    }
    
    return $anneesScolairesList;
  }  
}
