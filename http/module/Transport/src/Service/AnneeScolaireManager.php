<?php
/**
 * @package   : module/Transport/src/Service/AnneeScolaireManager.php
 *
 * @purpose   : This service is responsible for adding/editing anneeScolaire. 
 *
 *
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\AnneeScolaire;
use Transport\Model\AnneeScolaireTable;

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
    
    $this->anneeScolaireTable = $anneeScolaireTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
    
  /*
   * This method adds a new anneeScolaire.
   */
  public function addAnneeScolaire($data) 
  {
    
    if(!$this->anneeScolaireTable->findOneByAnneeScolaire($data['ANNEEANNEESCOLAIRE'])) {
      
      // Create new AnneeScolaire entiy.
      $anneeScolaire= new AnneeScolaire();
      $anneeScolaire->exchangeArray($data);  
      $result = $this->anneeScolaireTable->saveAnneeScolaire($anneeScolaire);
      return $result;
    }
    
    return false;
  }
    
  /*
   * This method update datas of an existing anneeScolaire
   */
  public function updateAnneeScolaire($anneeScolaire, $data) 
  {
    
    // Do not allow to change anneeScolaire if another anneeScolaire with such data already exits
    //if($this->checkAnneeScolaireExists($data)) {
    if($anneeScolaire->getPrenom()!=$data['PRENOMCHAUFFEUR'] && $this->checkAnneeScolaireExists($data)) {  
      
      return false;
    }
    $anneeScolaire->exchangeArray($data, false);

    // Apply changes to database.
    $this->anneeScolaireTable->saveAnneeScolaire($anneeScolaire);
    
    return $anneeScolaire;
  }
  
  /**
   * Deletes the given anneeScolaire.
   */
  public function deleteAnneeScolaire($id)
  {
    
    $this->anneeScolaireTable->deleteAnneeScolaire($id);
  }

  /*
   *
   */
  public function checkAnneeScolaireExists(array $data) {

    $search['ANNEEANNEESCOLAIRE'] = $data['ANNEEANNEESCOLAIRE'];
    $anneeScolaire = $this->anneeScolaireTable->findOneBy($search);
    return $anneeScolaire;
  }  
}

