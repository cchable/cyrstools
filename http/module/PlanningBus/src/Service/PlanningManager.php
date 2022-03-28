<?php
/**
 * @package   : module/PlanningBus/src/Service/PlanningManager.php
 *
 * @purpose   : This service is responsible for adding/editing planning. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Planning;
use PlanningBus\Model\PlanningTable;
use PlanningBus\Model\TypePlanningTable;
use PlanningBus\Model\DatePlanningTable;
use PlanningBus\Model\HeurePlanningTable;


/*
 * 
 */
class PlanningManager
{
  
  /*
   * Planning table manager.
   * @var Parling\Model\PlanningTable
   */
  private $planningTable;
  
  /*
   * Planning table manager.
   * @var Parling\Model\PlanningTable
   */
  private $typePlanningTable; 
  
  /*
   * Planning table manager.
   * @var Parling\Model\PlanningTable
   */
  private $datePlanningTable; 
  
  /*
   * Planning table manager.
   * @var Parling\Model\PlanningTable
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
  public function __construct(
    PlanningTable $planningTable, 
    TypePlanningTable $typePlanningTable, 
    DatePlanningTable $datePlanningTable, 
    HeurePlanningTable $heurePlanningTable, 
    $viewRenderer, 
    $config
  ) 
  {
    
    $this->planningTable      = $planningTable;
    $this->typePlanningTable  = $typePlanningTable;
    $this->datePlanningTable  = $datePlanningTable;
    $this->heurePlanningTable = $heurePlanningTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
    
  /*
   * This method adds a new planning.
   */
  public function addPlanning($data) 
  {
    
    // Create new Planning entity.
    $planning = new Planning();
    $planning->exchangeArray($data);   

    if(!$this->planningTable->findOneByRecord($planning)) {
      
      // Find typeplanning
      $idTypePlanning = $data['IDX_TYPEPLANNING'];
      $typePlanning = $this->typePlanningTable->findOneById($idTypePlanning);
      if ($typePlanning == null) {
        throw new \Exception('type planning not found');
      }
      $planning->setIdTypePlanning($idTypePlanning);
      
      // Find dateplanning
      $idDatePlanning = $data['IDX_DATEPLANNING'];
      $datePlanning = $this->datePlanningTable->findOneById($idDatePlanning);
      if ($datePlanning == null) {
        throw new \Exception('date planning not found');
      }
      $planning->setIdDatePlanning($idDatePlanning);
      
      // Find heureplanning
      $idHeurePlanning = $data['IDX_HEUREPLANNING'];
      $heurePlanning = $this->heurePlanningTable->findOneById($idHeurePlanning);
      if ($heurePlanning == null) {
        throw new \Exception('heure planning not found');
      }
      $planning->setIdHeurePlanning($idHeurePlanning);
    
      $planning = $this->planningTable->save($planning);
      return $planning;
    }
    
    return false;
  }
    
  /*
   * This method updates data of an existing planning
   */
  public function updatePlanning($planning, $data) 
  {
    
    // Do not allow to change planning if another planning with such data already exits
    if($this->checkPlanningExists($data)) {
      return false;
    }

    //find typeplanning id
    $idTypePlanning = $data['IDX_TYPEPLANNING']; 
    $typePlanning = $this->typePlanningTable->findOneById($idTypePlanning);
    if ($typePlanning == null) {
      throw new \Exception('Type planning not found');
    }
    
    //find dateplanning id
    $idDatePlanning = $data['IDX_DATEPLANNING']; 
    $datePlanning = $this->datePlanningTable->findOneById($idDatePlanning);
    if ($datePlanning == null) {
      throw new \Exception('Date planning not found');
    }
        
    //find heureplanning id
    $idHeurePlanning = $data['IDX_HEUREPLANNING']; 
    $heurePlanning = $this->heurePlanningTable->findOneById($idHeurePlanning);
    if ($heurePlanning == null) {
      throw new \Exception('Heure planning not found');
    }
    
    //fill record
    $planning->setIdTypePlanning($idTypePlanning);
    $planning->setIdDatePlanning($idDatePlanning);
    $planning->setIdHeurePlanning($idHeurePlanning);

    // Apply changes to database.
    $this->planningTable->save($planning);
    
    return $planning;
  }
  
  /**
   * Deletes the given planning.
   */
  public function deletePlanning($id)
  {
    
    $this->planningTable->delete($id);
  }

  /*
   *
   */
  public function checkPlanningExists(array $data) {

    $search['IDX_TYPEPLANNING']  = $data['IDX_TYPEPLANNING'];
    $search['IDX_DATEPLANNING']  = $data['IDX_DATEPLANNING'];
    $search['IDX_HEUREPLANNING'] = $data['IDX_HEUREPLANNING'];
    $planning = $this->planningTable->findOneBy($search);
    return $planning !== null;
  } 
  
  /*
   * 
   */
  public function getAllTypePlanning() {
    
    $typesPlanningsList = [];
    
    $typesPlannings = $this->typePlanningTable->fetchAll();

    foreach ($typesPlannings as $typePlanning) {
      $typesPlanningsList[$typePlanning->getId()] = $typePlanning->getNom();
    }
    
    return $typesPlanningsList;
  } 
  
  /*
   * 
   */
  public function getAllDatePlanning() {
    
    $datesPlanningsList = [];
    
    $datesPlannings = $this->datePlanningTable->fetchAll();

    foreach ($datesPlannings as $datePlanning) {
      $datesPlanningsList[$datePlanning->getId()] = $datePlanning->getDate();
    }
    
    return $datesPlanningsList;
  }
     
  /*
   * 
   */
  public function getAllHeurePlanning() {
    
    $heuresPlanningsList = [];
    
    $heuresPlannings = $this->heurePlanningTable->fetchAll();

    foreach ($heuresPlannings as $heurePlanning) {
      $heuresPlanningsList[$heurePlanning->getId()] = $heurePlanning->getHeure();
    }
    
    return $heuresPlanningsList;
  }
  
  /*
   * 
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

