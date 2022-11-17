<?php
/**
 * @package   : module/PlanningBus/src/Service/EphemerideManager.php
 *
 * @purpose   : This service is responsible for adding/editing ephemeride. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Ephemeride;
use PlanningBus\Model\EphemerideTable;
use PlanningBus\Model\AnneeScolaireTable;


/*
 * 
 */
class EphemerideManager
{
  
  /*
   * Ephemeride table manager.
   * @var Parking\Model\EphemerideTable
   */
  private $ephemerideTable;
  
  
  /*
   * Ephemeride table manager.
   * @var Parking\Model\EphemerideTable
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
  //public function __construct(EphemerideTable $ephemerideTable, DepartementTable $departementTable, $viewRenderer, $config) 
  public function __construct(EphemerideTable $ephemerideTable, AnneeScolaireTable $anneeScolaireTable, $viewRenderer, $config) 
  {
    
    $this->ephemerideTable    = $ephemerideTable;
    $this->anneeScolaireTable = $anneeScolaireTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
    
  /*
   * This method adds a new ephemeride.
   */
  public function addEphemeride($data) 
  {
    
    // Create new Ephemeride entity.
    $ephemeride = new Ephemeride();
    $ephemeride->exchangeArray($data);   

    if(!$this->ephemerideTable->findOneByRecord($ephemeride)) {
      
      // Find anneescolaire
      $idAnneeScolaire = $data['IDX_ANNEESCOLAIRE'];
      $anneeScolaire = $this->anneeScolaireTable->findOneById($idAnneeScolaire);
      if ($anneeScolaire == null) {
        throw new \Exception('Annnée scolaire not found');
      }
      $ephemeride->setIdAnneeScolaire($anneeScolaire->getId());
    
      $ephemeride = $this->ephemerideTable->saveEphemeride($ephemeride);
      return $ephemeride;
    }
    
    return false;
  }
    
  /*
   * This method updates data of an existing ephemeride
   */
  public function updateEphemeride($ephemeride, $data) 
  {
    
    // Do not allow to change ephemeride if another ephemeride with such data already exits
    //if($this->checkEphemerideExists($data)) {
    if($ephemeride->getIntitule()!=$data['INTITULEEPHEMERIDE'] && $this->checkEphemerideExists($data)) {  
      
      return false;
    }

    //find anneeScolaire id
    $idAnneeScolaire = $data['IDX_ANNEESCOLAIRE']; 
    $anneeScolaire = $this->anneeScolaireTable->findOneById($idAnneeScolaire);
    if ($anneeScolaire == null) {
      throw new \Exception('Année scolaire not found');
    }
    
    //fill record
    $ephemeride->setIdAnneeScolaire($anneeScolaire->getId());
    $ephemeride->setIntitule($data['INTITULEEPHEMERIDE']);
    $ephemeride->setDateDebut($data['DATEDEBUTEPHEMERIDE']);
    $ephemeride->setDateFin($data['DATEFINEPHEMERIDE']);

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

    $search['IDX_ANNEESCOLAIRE']   = $data['IDX_ANNEESCOLAIRE'];
    $search['INTITULEEPHEMERIDE']  = $data['INTITULEEPHEMERIDE'];
    //$search['DATEDEBUTEPHEMERIDE'] = $data['DATEDEBUTEPHEMERIDE'];
    //$search['DATEFINEPHEMERIDE']   = $data['DATEFINEPHEMERIDE'];
    $ephemeride = $this->ephemerideTable->findOneBy($search);
    return $ephemeride;
  }  
  
  /*
   * 
   */
  public function getAllAnneesScolaire() {
    
    $anneesScolairesList = [];
    
    $anneesScolaires = $this->anneeScolaireTable->fetchAll();

    foreach ($anneesScolaires as $anneeScolaire) {
      $anneesScolairesList[$anneeScolaire->getId()] = $anneeScolaire->getAnneeScolaire();
    }
    
    return $anneesScolairesList;
  }
}

