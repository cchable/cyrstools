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
    
    if(!$this->ephemerideTable->findOneByEphemeride($data['ANNEEANNEESCOLAIRE'])) {
      
      // Create new Ephemeride entiy.
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
    
    // Do not allow to change ephemeride if another ephemeride with such data already exits
    //if($this->checkEphemerideExists($data)) {
    if($ephemeride->getEphemeride()!=$data['ANNEEANNEESCOLAIRE'] && $this->checkEphemerideExists($data)) {  
      
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
  public function checkEphemerideExists(array $data) {

    $search['ANNEEANNEESCOLAIRE'] = $data['ANNEEANNEESCOLAIRE'];
    $ephemeride = $this->ephemerideTable->findOneBy($search);
    return $ephemeride;
  }  

  /*
   * 
   */
  public function getAnneesScolaires() {
    
    $anneesScolairesList = [];
    
    $anneesScolaires = $this->anneeScolaireTable->fetchAll();

    foreach ($anneesScolaires as $anneeScolaire) {
      $anneesScolairesList[$anneeScolaire->getId()] = $anneeScolaire->getAnneeScolaire();
    }
    
    return $anneesScolairesList;
  }  
}
