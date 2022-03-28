<?php
/**
 * @package   : module/PlanningBus/src/Service/TransportManager.php
 *
 * @purpose   : This service is responsible for adding/editing trajet. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\Transport;
use PlanningBus\Model\TransportTable;


/*
 * 
 */
class TransportManager
{
  
  /*
   * Transport table manager.
   * @var Parling\Model\TransportTable
   */
  private $trajetTable;
  
  
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
  public function __construct(TransportTable $trajetTable, $viewRenderer, $config) 
  {
    
    $this->trajetTable  = $trajetTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new trajet.
   */
  public function addTransport($data) 
  {
    
    // Create new Transport entity.
    $trajet = new Transport();
    $trajet->exchangeArray($data);   

    if(!$this->checkTransportExists($data)) {
      
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
      
      $trajet = $this->trajetTable->saveTransport($trajet);
      return $trajet;
    }
    
    return false;
  }
    
  /*
   * This method updates data of an existing trajet
   */
  public function updateTransport($trajet, $data) 
  {
    
    // Do not allow to change trajet if another trajet with such data already exits
    if($this->checkTransportExists($data)) {
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
    $this->trajetTable->saveTransport($trajet);
    
    return $trajet;
  }
  
  /**
   * Deletes the given trajet.
   */
  public function deleteTransport($id)
  {
    
    $this->trajetTable->deleteTransport($id);
  }

  /*
   *
   */
  public function checkTransportExists(array $data) {

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

