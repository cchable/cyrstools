<?php
/**
 * @package   : module/PlanningBus/src/Service/DatePlanningManager.php
 *
 * @purpose   : This service is responsible for adding/editing DatePlanning. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\DatePlanning;
use PlanningBus\Model\DatePlanningTable;


/*
 * 
 */
class DatePlanningManager
{
  
  /*
   * DatePlanning table manager.
   * @var Parling\Model\DatePlanningTable
   */
  private $datePlanningTable;
  
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
  public function __construct(DatePlanningTable $datePlanningTable, $viewRenderer, $config) 
  {
    
    $this->datePlanningTable = $datePlanningTable;
    $this->viewRenderer      = $viewRenderer;
    $this->config            = $config;
  }
    
  /*
   * This method adds a new DatePlanning.
   */
  public function addDatePlanning(array $data) 
  {
    
    // Create new DatePlanning entity.
    $datePlanning = new DatePlanning();
    $datePlanning->exchangeArray($data);
    
    //
    if(!$this->checkDatePlanningExists($data)) {
      $datePlanning = $this->datePlanningTable->save($datePlanning);
      return $datePlanning;
    }
    
    return;
  }

  /*
   * This method updates data of an existing DatePlanning.
   */
  public function updateDatePlanning(DatePlanning $datePlanning, array $data) 
  {
    
    // Do not allow to change DatePlanning if another DatePlanning with such name value already exits
    if($datePlanning->getDate()!=$data['DATEDATEPLANNING'] && $this->checkDatePlanningExists($data)) {
      
      return false;
    }
    
    $datePlanning->setDate($data['DATEDATEPLANNING']);
    $datePlanning->setCode($data['CODESEMAINEDATEPLANNING']);

    // Apply changes to database.
    $this->datePlanningTable->save($datePlanning);
    
    return $datePlanning;
  }  
   
  /*
   * Deletes the given DatePlanning.
   */
  public function deleteDatePlanning($datePlanning)
  {
    
    $this->datePlanningTable->delete($datePlanning->getId());
  }

  /*
   * Checks whether an active DatePlanning with given value already exists in the database.     
   */
  public function checkDatePlanningExists(array $data) {

    $search['DATEDATEPLANNING']        = $data['DATEDATEPLANNING'];
    //$search['CODESEMAINEDATEPLANNING'] = $data['CODESEMAINEDATEPLANNING'];
    $datePlanning = $this->datePlanningTable->findOneBy($search);
    return $datePlanning !== null;
  }
  
  /*
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

