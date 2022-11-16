<?php
/**
 * @package   : module/Transport/src/Service/IndisponibiliteChauffeurManager.php
 *
 * @purpose   : This service is responsible for adding/editing chauffeur. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\IndisponibiliteChauffeur;
use Transport\Model\IndisponibiliteChauffeurTable;

/*
 * 
 */
class IndisponibiliteChauffeurManager
{
  
  /*
   * IndisponibiliteChauffeur table manager.
   * @var Parling\Model\ChauffeurTable
   */
  private $indisponibiliteChauffeurTable;
  
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
  public function __construct(IndisponibiliteChauffeurTable $indisponibiliteChauffeurTable, $viewRenderer, $config) 
  {
    
    $this->chauffeurTable = $indisponibiliteChauffeurTable;
    $this->viewRenderer   = $viewRenderer;
    $this->config         = $config;
  }
    
  /*
   * This method adds a new chauffeur.
   */
  public function addIndisponibiliteChauffeur($data) 
  {
    
    if(!$this->indisponibiliteChauffeurTable->findOneByDateDebut($data)) {
      
      // Create new IndisponibiliteChauffeur entiy.
      $indisponibiliteChauffeur= new IndisponibiliteChauffeur();
      $indisponibiliteChauffeur->exchangeArray($data);  
      $result = $this->chauffeurTable->saveIndisponibiliteChauffeur($indisponibiliteChauffeur);
      return $result;
    }
    
    return false;
  }
    
  /*
   * This method update datas of an existing chauffeur
   */
  public function updateIndisponibiliteChauffeur($indisponibiliteChauffeur, $data) 
  {
    
    // Do not allow to change chauffeur if another chauffeur with such data already exits
    //if($this->checkChauffeurExists($data)) {
    if($indisponibiliteChauffeur->getDateDebut()!=$data['DATEDEBUTINDISPONIBILITE'] && $this->checkIndisponibiliteChauffeurExists($data)) {  
      
      return false;
    }
    $indisponibiliteChauffeur->exchangeArray($data, false);

    // Apply changes to database.
    $this->indisponibiliteChauffeurTable->saveIndisponibiliteChauffeur($indisponibiliteChauffeur);
    
    return $indisponibiliteChauffeur;
  }
  
  /**
   * Deletes the given chauffeur.
   */
  public function deleteIndisponibiliteChauffeur($id)
  {
    
    $this->indisponibiliteChauffeurTable->deleteIndisponibiliteChauffeur($id);
  }

  /*
   *
   */
  public function checkIndisponibiliteChauffeurExists(array $data) {

    $search['DATEDEBUTINDISPONIBILITE'] = $data['DATEDEBUTINDISPONIBILITE'];
    $indisponibiliteChauffeur = $this->chauffeurTable->findOneBy($search);
    return $indisponibiliteChauffeur;
  }  
}

