<?php
/**
 * @package   : module/PlanningBus/src/Service/TypePlanningManager.php
 *
 * @purpose   : This service is responsible for adding/editing TypePlanning. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\TypePlanning;
use PlanningBus\Model\TypePlanningTable;


/*
 * 
 */
class TypePlanningManager
{
  
  /*
   * TypePlanning table manager.
   * @var Parling\Model\TypePlanningTable
   */
  private $typePlanningTable;
  
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
  public function __construct(TypePlanningTable $typePlanningTable, $viewRenderer, $config) 
  {
    
    $this->typePlanningTable = $typePlanningTable;
    $this->viewRenderer      = $viewRenderer;
    $this->config            = $config;
  }
    
  /*
   * This method adds a new TypePlanning.
   */
  public function addTypePlanning(array $data) 
  {
    
    // Create new TypePlanning entity.
    $typePlanning = new TypePlanning();
    $typePlanning->exchangeArray($data);
    
    //
    if(!$this->checkTypePlanningExists($data)) {
      $typePlanning = $this->typePlanningTable->save($typePlanning);
      return $typePlanning;
    }
    
    return;
  }

  /*
   * This method updates data of an existing TypePlanning.
   */
  public function updateTypePlanning(TypePlanning $typePlanning, array $data) 
  {
    
    // Do not allow to change TypePlanning if another TypePlanning with such name value already exits
    if($typePlanning->getNom()!=$data['NOMTYPEPLANNING'] && $this->checkTypePlanningExists($data)) {
      
      return false;
    }
    
    $typePlanning->setNom($data['NOMTYPEPLANNING']);

    // Apply changes to database.
    $this->typePlanningTable->save($typePlanning);
    
    return $typePlanning;
  }  
   
  /*
   * Deletes the given TypePlanning.
   */
  public function deleteTypePlanning($typePlanning)
  {
    
    $this->typePlanningTable->delete($typePlanning->getId());
  }

  /*
   * Checks whether an active TypePlanning with given value already exists in the database.     
   */
  public function checkTypePlanningExists(array $data) {

    $nom = $data['NOMTYPEPLANNING'];
    $typePlanning = $this->typePlanningTable->findOneByNom($nom);
    
    return $typePlanning !== null;
  }
  
  /*
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

