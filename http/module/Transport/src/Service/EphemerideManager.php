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
    
    $search['IDX_ANNEESCOLAIRE'] = $data['IDX_ANNEESCOLAIRE'];
    $search['NOMEPHEMERIDE']     = $data['NOMEPHEMERIDE'];
    if(
      !$this->ephemerideTable->findOneByRecord($search)
      && 
      !$this->checkEphemerideDatesExists($data)
    ) {  
      
      // Create new Ephemeride entity.
      $ephemeride= new Ephemeride();
      $ephemeride->exchangeArray($data);  
      $result = $this->ephemerideTable->saveEphemeride($ephemeride);
      return $result;
    }
    
    return false;
  }
    
  /*
   * This method update datas of an existing ephemeride
   */
  public function updateEphemeride($ephemeride, $data) 
  {
     
    // Do not allow to change ephemeride if another ephemeride with such data already exists
    if($this->checkEphemerideExists($ephemeride, $data)) {
         
      return false;
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
  public function checkEphemerideExists(Ephemeride $ephemeride, array $newData) {
  
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

 /*
   *
   */
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

  /*
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
