<?php
/**
 * @package   : module/PlanningBus/src/Service/GroupeManager.php
 *
 * @purpose   : This service is responsible for adding/editing Groupe. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Groupe;
use PlanningBus\Model\GroupeTable;


/*
 * 
 */
class GroupeManager
{
  
  /*
   * Groupe table manager.
   * @var Parling\Model\GroupeTable
   */
  private $groupeTable;
  
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
  public function __construct(GroupeTable $groupeTable, $viewRenderer, $config) 
  {
    
    $this->groupeTable   = $groupeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new Groupe.
   */
  public function addGroupe(array $data) 
  {
    
    // Create new Groupe entity.
    $groupe = new Groupe();
    $groupe->exchangeArray($data);
    
    //
    //if(!$this->groupeTable->findOneByName($groupe->getName())) {
    if(!$this->checkGroupeExists($data)) {
      $groupe = $this->groupeTable->saveGroupe($groupe);
      return $groupe;
    }
    
    return;
  }

  /*
   * This method updates data of an existing Groupe.
   */
  public function updateGroupe(Groupe $groupe, array $data) 
  {
    
    // Do not allow to change Groupe if another Groupe with such name value already exits
    if($groupe->getNom()!=$data['NOMGROUPE'] && $this->checkGroupeExists($data)) {
      
      return false;
    }
    
    $groupe->setNom($data['NOMGROUPE']);
    $groupe->setNombre($data['NOMBREGROUPE']);

    // Apply changes to database.
    $this->groupeTable->saveGroupe($groupe);
    
    return $groupe;
  }  
   
  /*
   * Deletes the given Groupe.
   */
  public function deleteGroupe($groupe)
  {
    
    $this->groupeTable->deleteGroupe($groupe->getId());
  }

  /*
   * Checks whether an active Groupe with given value already exists in the database.     
   */
  public function checkGroupeExists(array $data) {

    $nom = $data['NOMGROUPE'];
    $groupe = $this->groupeTable->findOneByNom($nom);
    
    return $groupe !== null;
  }
  
  /*
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

