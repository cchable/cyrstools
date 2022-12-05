<?php
/**
 * @package   : module/Transport/src/Service/IndisponibiliteChauffeurManager.php
 *
 * @purpose   : This service is responsible for adding/editing chauffeur. 
 *
 *
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\IndisponibiliteChauffeur;
use Transport\Model\IndisponibiliteChauffeurTable;
use Transport\Model\ChauffeurTable;


/*
 * 
 */
class IndisponibiliteChauffeurManager
{
  
  /*
   * IndisponibiliteChauffeur table manager.
   * @var Transport\Model\INdisponibiliteChauffeurTable
   */
  private $indisponibiliteChauffeurTable;
  
  /*
   * Chauffeur table manager.
   * @var Transport\Model\ChauffeurTable
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
  public function __construct(IndisponibiliteChauffeurTable $indisponibiliteChauffeurTable, ChauffeurTable $chauffeurTable, $viewRenderer, $config) 
  {
    
    $this->indisponibiliteChauffeurTable = $indisponibiliteChauffeurTable;
    $this->chauffeurTable                = $chauffeurTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new chauffeur.
   */
  public function addIndisponibiliteChauffeur($data) 
  {
    
    //Clean $data from FROM
    unset($data['csrf']);
    unset($data['submit']);
    
    if(!$this->checkIndisponibiliteChauffeurExists($data)) {
    //if(!$this->indisponibiliteChauffeurTable->findOneByRecord($data)) {
      
      // Create new IndisponibiliteChauffeur entity.
      $indisponibiliteChauffeur= new IndisponibiliteChauffeur();
      $indisponibiliteChauffeur->exchangeArray($data);
      
      // Check if Chauffeur exist
      $idChauffeur = $data['IDX_CHAUFFEUR'];
      $chauffeur = $this->chauffeurTable->findOneById($idChauffeur);
      if ($chauffeur == null) {
        
        throw new \Exception('Chauffeur not found');
      }
      
      return $this->indisponibiliteChauffeurTable->saveIndisponibiliteChauffeur($indisponibiliteChauffeur);
      }
    
    return false;
  }
    
  /*
   * This method update datas of an existing indisponibilitechauffeur
   */
  public function updateIndisponibiliteChauffeur($indisponibiliteChauffeur, $data) 
  {
    
    // Do not allow to change indisponibilitechauffeur if another indisponibilitechauffeur with such data already exits
    if($this->checkIndisponibiliteChauffeurExists($data)) {
 
      return false;
    }
    
    //find chauffeur id
    $idChauffeur = $data['IDX_CHAUFFEUR'];
    $chauffeur = $this->chauffeurTable->findOneById($idChauffeur);
    if ($chauffeur == null) {
      
      throw new \Exception('Chauffeur not found');
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

    $search['IDX_CHAUFFEUR']            = $data['IDX_CHAUFFEUR'];
    $search['STARTDATEINDISPONIBILITE'] = $data['STARTDATEINDISPONIBILITE'];
    $indisponibiliteChauffeur = $this->indisponibiliteChauffeurTable->findOneBy($search);
    $bResult = false;
    if ($indisponibiliteChauffeur) {
      
      if ($indisponibiliteChauffeur->getJourEntier() || $data['ALLDAYINDISPONIBILITE']) {
        
        $bResult = true;
      } else {
        
        if ($indisponibiliteChauffeur->getDateFin() != $data['ENDDATEINDISPONIBILITE']) {
          
          $bResult = true;
        } else {
          
          if (checkTimeBetween(
                $indisponibiliteChauffeur->getHeureDebut(), 
                $data['STARTTIMEINDISPONIBILITE'], 
                $data['ENDTIMEINDISPONIBILITE']
                )
              ||
              checkTimeBetween(
                $indisponibiliteChauffeur->getHeurefin(),
                $data['STARTTIMEINDISPONIBILITE'], 
                $data['ENDTIMEINDISPONIBILITE']
                )
             ) {
                
            $bResult = true;
          } else {      
            $bResult = checkRangeTimeBetween(
              $indisponibiliteChauffeur->getHeureDebut(), 
              $indisponibiliteChauffeur->getHeureFin(), 
              $data['STARTTIMEINDISPONIBILITE'], 
              $data['ENDTIMEINDISPONIBILITE']
            );
          }
        }
      }
    }
    
    return $bResult;
  }
  
  private function checkTimeBetween($value, $start, $end) {
    
    $timeValue = new DateTime($value);
    $timeStart = new DateTime($start);
    $timeEnd   = new DateTime($end);
    
    return (($timeValue > $timeStart) && ($timeValue < $timeEnd));  
  }
  
  private function checkRangeTimeBetween($start1, $end1, $start2, $end2) {
    
    $timeStart1 = new DateTime($start1);
    $timeEnd1   = new DateTime($end1);
    $timeStart2 = new DateTime($start2);
    $timeEnd2   = new DateTime($end2);

    return (($timeStart1 > $timeStart2) && ($timeEnd1 < $timeEnd2) || ($timeStart1 < $timeStart2) && ($timeEnd1 > $timeEnd2));
  }
  
  /*
   * 
   */
  public function getChauffeurs() {
    
    $chauffeursList = [];
    
    $chauffeurs = $this->chauffeurTable->fetchAll();

    foreach ($chauffeurs as $chauffeur) {
      $chauffeursList[$chauffeur->getId()] = $chauffeur->getPrenom();
    }
    
    return $chauffeursList;
  }  
}

