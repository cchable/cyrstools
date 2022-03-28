<?php
/**
 * @package   : module/PlanningBus/src/Service/EtapeManager.php
 *
 * @purpose   : This service is responsible for adding/editing Etape. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Etape;
use PlanningBus\Model\EtapeTable;


/*
 * 
 */
class EtapeManager
{
  
  /*
   * Etape table manager.
   * @var Parling\Model\EtapeTable
   */
  private $etapeTable;
  
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
  public function __construct(EtapeTable $etapeTable, $viewRenderer, $config) 
  {
    
    $this->etapeTable   = $etapeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new Etape.
   */
  public function addEtape(array $data) 
  {
    
    // Create new Etape entity.
    $etape = new Etape();
    $etape->exchangeArray($data);
    
    //
    //if(!$this->etapeTable->findOneByName($etape->getName())) {
    if(!$this->checkEtapeExists($data)) {
      $etape = $this->etapeTable->saveEtape($etape);
      return $etape;
    }
    
    return;
  }

  /*
   * This method updates data of an existing Etape.
   */
  public function updateEtape(Etape $etape, array $data) 
  {
    
    // Do not allow to change Etape if another Etape with such name value already exits
    if($etape->getNom()!=$data['NOMETAPE'] && $this->checkEtapeExists($data)) {
      
      return false;
    }
    
    $etape->setNom($data['NOMETAPE']);
    $etape->setAdresse($data['ADRESSEETAPE']);
    $etape->setPrinted($data['PRINTEDETAPE']);

    // Apply changes to database.
    $this->etapeTable->saveEtape($etape);
    
    return $etape;
  }  
   
  /*
   * Deletes the given Etape.
   */
  public function deleteEtape($etape)
  {
    
    $this->etapeTable->deleteEtape($etape->getId());
  }

  /*
   * Checks whether an active Etape with given value already exists in the database.     
   */
  public function checkEtapeExists(array $data) {

    $nom = $data['NOMETAPE'];
    $etape = $this->etapeTable->findOneByNom($nom);
    
    return $etape !== null;
  }
  
  /*
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

