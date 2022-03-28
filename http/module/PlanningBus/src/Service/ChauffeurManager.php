<?php
/**
 * @package   : module/PlanningBus/src/Service/ChauffeurManager.php
 *
 * @purpose   : This service is responsible for adding/editing chauffeur. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Chauffeur;
use PlanningBus\Model\ChauffeurTable;
use PlanningBus\Model\VehiculeTable;


/*
 * 
 */
class ChauffeurManager
{
  
  /*
   * Chauffeur table manager.
   * @var Parling\Model\ChauffeurTable
   */
  private $chauffeurTable;
  
  
  /*
   * Chauffeur table manager.
   * @var Parling\Model\ChauffeurTable
   */
  private $vehiculeTable; 
  
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
  public function __construct(ChauffeurTable $chauffeurTable, VehiculeTable $vehiculeTable, $viewRenderer, $config) 
  {
    
    $this->chauffeurTable = $chauffeurTable;
    $this->vehiculeTable  = $vehiculeTable;
    $this->viewRenderer   = $viewRenderer;
    $this->config         = $config;
  }
    
  /*
   * This method adds a new chauffeur.
   */
  public function addChauffeur($data) 
  {
    
    // Create new Chauffeur entity.
    $chauffeur = new Chauffeur();
    $chauffeur->exchangeArray($data);   

    if(!$this->chauffeurTable->findOneByRecord($chauffeur)) {
      
      // Find vehicule
      $idVehicule = $data['IDX_VEHICULE'];
      $vehicule = $this->vehiculeTable->findOneById($idVehicule);
      if ($vehicule == null) {
        throw new \Exception('Véhicule not found');
      }
      //$chauffeur->setIdVehicule($idVehicule);
    
      $chauffeur = $this->chauffeurTable->save($chauffeur);
      return $chauffeur;
    }
    
    return false;
  }
    
  /*
   * This method updates data of an existing chauffeur
   */
  public function updateChauffeur($chauffeur, $data) 
  {
    
    // Do not allow to change chauffeur if another chauffeur with such data already exits
    //if($this->checkChauffeurExists($data)) {
    if($chauffeur->getPrenom()!=$data['PRENOMCHAUFFEUR'] && $this->checkChauffeurExists($data)) {  
      
      return false;
    }

    //find vehicule id
    $idVehicule = $data['IDX_VEHICULE']; 
    $vehicule = $this->vehiculeTable->findOneById($idVehicule);
    if ($vehicule == null) {
      throw new \Exception('Véhicule not found');
    }
    
    //fill record
    $chauffeur->setIdVehicule($vehicule->getId());
    $chauffeur->setPrenom($data['PRENOMCHAUFFEUR']);

    // Apply changes to database.
    $this->chauffeurTable->save($chauffeur);
    
    return $chauffeur;
  }
  
  /**
   * Deletes the given chauffeur.
   */
  public function deleteChauffeur($id)
  {
    
    $this->chauffeurTable->delete($id);
  }

  /*
   *
   */
  public function checkChauffeurExists(array $data) {

    $search['PRENOMCHAUFFEUR'] = $data['PRENOMCHAUFFEUR'];
    $chauffeur = $this->chauffeurTable->findOneBy($search);
    return $chauffeur;
  }  
  
  /*
   * 
   */
  public function getAllVehicule() {
    
    $vehiculesList = [];
    
    $vehicules = $this->vehiculeTable->fetchAll();

    foreach ($vehicules as $vehicule) {
      $vehiculesList[$vehicule->getId()] = $vehicule->getNom();
    }
    
    return $vehiculesList;
  }
}

