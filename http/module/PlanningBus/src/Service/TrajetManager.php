<?php
/**
 * @package   : module/PlanningBus/src/Service/TrajetManager.php
 *
 * @purpose   : This service is responsible for adding/editing trajet. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Trajet;
use PlanningBus\Model\TrajetTable;
use PlanningBus\Model\EtapeTable;


/*
 * 
 */
class TrajetManager
{
  
  /*
   * Trajet table manager.
   * @var Parling\Model\TrajetTable
   */
  private $trajetTable;
  
  
  /*
   * Trajet table manager.
   * @var Parling\Model\TrajetTable
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
  //public function __construct(TrajetTable $trajetTable, DepartementTable $departementTable, $viewRenderer, $config) 
  public function __construct(TrajetTable $trajetTable, EtapeTable $etapeTable, $viewRenderer, $config) 
  {
    
    $this->trajetTable  = $trajetTable;
    $this->etapeTable   = $etapeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new trajet.
   */
  public function addTrajet($data) 
  {
    
    // Create new Trajet entity.
    $trajet = new Trajet();
    $trajet->exchangeArray($data);   

    if(!$this->checkTrajetExists($data)) {
      
      // Find etapeDepart
      $idEtapeDepart = $data['IDX_ETAPEDEPART'];
      $etapeDepart = $this->etapeTable->findOneById($idEtapeDepart);
      if ($etapeDepart == null) {
        throw new \Exception('Etape not found');
      }
      $trajet->setIdEtapeDepart($etapeDepart->getId());
    
      // Find etapeArrivee
      $idEtapeArrivee = $data['IDX_ETAPEARRIVEE'];
      $etapeArrivee = $this->etapeTable->findOneById($idEtapeArrivee);
      if ($etapeArrivee == null) {
        throw new \Exception('Etape not found');
      }
      $trajet->setIdEtapeArrivee($etapeArrivee->getId());
      
      $trajet = $this->trajetTable->saveTrajet($trajet);
      return $trajet;
    }
    
    return false;
  }
    
  /*
   * This method updates data of an existing trajet
   */
  public function updateTrajet($trajet, $data) 
  {
    
    // Do not allow to change trajet if another trajet with such data already exits
    if($this->checkTrajetExists($data)) {
      return false;
    }

    //find etapedepart id
    $idEtapeDepart = $data['IDX_ETAPEDEPART']; 
    $etapeDepart = $this->etapeTable->findOneById($idEtapeDepart);
    if ($etapeDepart == null) {
      throw new \Exception('Etape not found');
    }
    
    //find etapearrivee id
    $idEtapeArrivee = $data['IDX_ETAPEARRIVEE']; 
    $etapeArrivee = $this->etapeTable->findOneById($idEtapeArrivee);
    if ($etapeArrivee == null) {
      throw new \Exception('Etape not found');
    }
    
    //fill record
    $trajet->setIdEtapeDepart($etapeDepart->getId());
    $trajet->setIdEtapeArrivee($etapeArrivee->getId());
    $trajet->setNom($data['NOMTRAJET']);
    $trajet->setTemps($data['TEMPSTRAJET']);
    $trajet->setKm($data['KMTRAJET']);

    // Apply changes to database.
    $this->trajetTable->saveTrajet($trajet);
    
    return $trajet;
  }
  
  /**
   * Deletes the given trajet.
   */
  public function deleteTrajet($id)
  {
    
    $this->trajetTable->deleteTrajet($id);
  }

  /*
   *
   */
  public function checkTrajetExists(array $data) {

    $nom = $data['NOMTRAJET'];
    $trajet = $this->etapeTable->findOneByNom($nom);
    
    return $trajet !== null;
  }  
  
  /*
   * 
   */
  public function getAllEtapes() {
    
    $etapesList = [];
    
    $etapes = $this->etapeTable->fetchAll();

    foreach ($etapes as $etape) {
      $etapesList[$etape->getId()] = $etape->getNom();
    }
    
    return $etapesList;
  }
    
  /*
   * 
   * @return int
   */
  public function getDefaultItemCountPerPage() {
    
    return $this->config['paginator']['options']['defaultItemCountPerPage'];
  }  
}

