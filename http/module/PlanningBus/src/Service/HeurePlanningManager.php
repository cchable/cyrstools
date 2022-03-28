<?php
/**
 * @package   : module/PlanningBus/src/Service/HeurePlanningManager.php
 *
 * @purpose   : This service is responsible for adding/editing HeurePlanning. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\HeurePlanning;
use PlanningBus\Model\HeurePlanningTable;


/*
 * 
 */
class HeurePlanningManager
{
  
  /*
   * HeurePlanning table manager.
   * @var Parling\Model\HeurePlanningTable
   */
  private $heurePlanningTable;
  
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
  public function __construct(HeurePlanningTable $heurePlanningTable, $viewRenderer, $config) 
  {
    
    $this->heurePlanningTable = $heurePlanningTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
    
  /*
   * This method adds a new HeurePlanning.
   */
  public function addHeurePlanning(array $data) 
  {
    
    // Create new HeurePlanning entity.
    $heurePlanning = new HeurePlanning();
    $heurePlanning->exchangeArray($data);
    
    //
    if(!$this->checkHeurePlanningExists($data)) {
      $heurePlanning = $this->heurePlanningTable->save($heurePlanning);
      return $heurePlanning;
    }
    
    return;
  }

  /*
   * This method upheures data of an existing HeurePlanning.
   */
  public function upheureHeurePlanning(HeurePlanning $heurePlanning, array $data) 
  {
    
    // Do not allow to change HeurePlanning if another HeurePlanning with such name value already exits
    if($heurePlanning->getHeure()!=$data['HEUREHEUREPLANNING'] && $this->checkHeurePlanningExists($data)) {
      
      return false;
    }
    
    $heurePlanning->setHeure($data['HEUREHEUREPLANNING']);

    // Apply changes to database.
    $this->heurePlanningTable->save($heurePlanning);
    
    return $heurePlanning;
  }  
   
  /*
   * Deletes the given HeurePlanning.
   */
  public function deleteHeurePlanning($heurePlanning)
  {
    
    $this->heurePlanningTable->delete($heurePlanning->getId());
  }

  /*
   * Checks whether an active HeurePlanning with given value already exists in the database.     
   */
  public function checkHeurePlanningExists(array $data) {

    $search['HEUREHEUREPLANNING'] = $data['HEUREHEUREPLANNING'];
    $heurePlanning = $this->heurePlanningTable->findOneBy($search);
    return $heurePlanning !== null;
  }
  
  /*
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

