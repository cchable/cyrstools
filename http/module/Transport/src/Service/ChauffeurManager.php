<?php
/**
 * @package   : module/Transport/src/Service/ChauffeurManager.php
 *
 * @purpose   : This service is responsible for adding/editing chauffeur. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\Chauffeur;
use Transport\Model\ChauffeurTable;

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
  public function __construct(ChauffeurTable $chauffeurTable, $viewRenderer, $config) 
  {
    
    $this->chauffeurTable = $chauffeurTable;
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
      
      $chauffeur = $this->chauffeurTable->saveChauffeur($chauffeur);
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
    $chauffeur->exchangeArray($data, false);

    // Apply changes to database.
    $this->chauffeurTable->saveChauffeur($chauffeur);
    
    return $chauffeur;
  }
  
  /**
   * Deletes the given chauffeur.
   */
  public function deleteChauffeur($id)
  {
    
    $this->chauffeurTable->deleteChauffeur($id);
  }

  /*
   *
   */
  public function checkChauffeurExists(array $data) {

    $search['PRENOMCHAUFFEUR'] = $data['PRENOMCHAUFFEUR'];
    $chauffeur = $this->chauffeurTable->findOneBy($search);
    return $chauffeur;
  }  
}

