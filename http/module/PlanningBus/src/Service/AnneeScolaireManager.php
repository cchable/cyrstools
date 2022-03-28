<?php
/**
 * @package   : module/PlanningBus/src/Service/AnneeScolaireManager.php
 *
 * @purpose   : This service is responsible for adding/editing anneescolaire. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Service;

use PlanningBus\Model\AnneeScolaire;
use PlanningBus\Model\AnneeScolaireTable;


/*
 * 
 */
class AnneeScolaireManager
{
  
  /*
   * AnneeScolaire table manager.
   * @var Parling\Model\AnneeScolaireTable
   */
  private $anneeScolaireTable;
  
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
  public function __construct(AnneeScolaireTable $anneeScolaireTable, $viewRenderer, $config) 
  {
    
    $this->anneeScolaireTable  = $anneeScolaireTable;
    $this->viewRenderer        = $viewRenderer;
    $this->config              = $config;
  }
    
  /*
   * This method adds a new anneescolaire.
   */
  public function addAnneeScolaire($data) 
  {
    
    // Create new AnneeScolaire entity.
    $anneeScolaire = new AnneeScolaire();
    $anneeScolaire->exchangeArray($data);
    
    //
    if(!$this->anneeScolaireTable->findOneByAnneeScolaire($anneeScolaire->getAnneeScolaire())) {
      $anneeScolaire = $this->anneeScolaireTable->saveAnneeScolaire($anneeScolaire);
      return $anneeScolaire;
    }
    
    return;
  }

  /*
   * This method updates data of an existing anneescolaire.
   */
  public function updateAnneeScolaire($anneeScolaire, $data) 
  {
    
    // Do not allow to change anneescolaire if another anneescolaire with such value already exits
    if($anneeScolaire->getAnneeScolaire()!=$data['ANNEEANNEESCOLAIRE'] && $this->checkAnneeScolaireExists($data['ANNEEANNEESCOLAIRE'])) {
      
      return false;
    }
    
    $anneeScolaire->setAnneeScolaire($data['ANNEEANNEESCOLAIRE']);

    // Apply changes to database.
    $this->anneeScolaireTable->saveAnneeScolaire($anneeScolaire);
    
    return $anneeScolaire;
  }  
   
  /*
   * Deletes the given anneescolaire.
   */
  public function deleteAnneescolaire($anneeScolaire)
  {
    
    $this->anneeScolaireTable->deleteAnneeScolaire($anneeScolaire->getId());
  }

  /*
   * Checks whether an active anneescolaire with given value already exists in the database.     
   */
  public function checkAnneeScolaireExists($annee) {

    $anneeScolaire = $this->anneeScolaireTable->findOneByAnneeScolaire($annee);
    
    return $anneeScolaire !== null;
  }
}

