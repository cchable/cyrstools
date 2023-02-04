<?php
/**
 * This service is responsible for add/edit/delete 'indisponibilite vehicule'. 
 *
 * @package   module/Transport/src/Service/IndisponibiliteVehiculeManager.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\IndisponibiliteVehicule;
use Transport\Model\IndisponibiliteVehiculeTable;
use Transport\Model\VehiculeTable;

use Laminas\I18n\Validator\DateTime;


/**
 * 
 */
class IndisponibiliteVehiculeManager
{
  
  /**
   * IndisponibiliteVehicule table manager.
   * @var Transport\Model\IndisponibiliteVehiculeTable
   */
  private $indisponibiliteVehiculeTable;
  
  /**
   * Vehicule table manager.
   * @var Transport\Model\VehiculeTable
   */
  private $vehiculeTable;
  
  /**
   * PHP template renderer.
   * @var type 
   */
  private $viewRenderer;

  /**
   * Application config.
   * @var type 
   */
  private $config;

  
  /*
   * Constructs the service.
   */
  public function __construct(IndisponibiliteVehiculeTable $indisponibiliteVehiculeTable, VehiculeTable $vehiculeTable, $viewRenderer, $config) 
  {
    
    $this->indisponibiliteVehiculeTable = $indisponibiliteVehiculeTable;
    $this->vehiculeTable                = $vehiculeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new vehicule.
   */
  public function addIndisponibiliteVehicule($data) 
  {
    
    //Clean $data from FROM
    unset($data['csrf']);
    unset($data['submit']);
    
    if(!$this->checkIndisponibiliteVehiculeExists($data)) {
    //if(!$this->indisponibiliteVehiculeTable->findOneByRecord($data)) {
      
      // Create new IndisponibiliteVehicule entity.
      $indisponibiliteVehicule= new IndisponibiliteVehicule();
      $indisponibiliteVehicule->exchangeArray($data);
      
      // Check if Vehicule exist
      $idVehicule = $data['IDX_VEHICULE'];
      $vehicule = $this->vehiculeTable->findOneById($idVehicule);
      if ($vehicule == null) {
        
        throw new \Exception('Vehicule not found');
      }
      
      return $this->indisponibiliteVehiculeTable->saveIndisponibiliteVehicule($indisponibiliteVehicule);
      }
    
    return false;
  }
    
  /*
   * This method update datas of an existing indisponibilitevehicule
   */
  public function updateIndisponibiliteVehicule($indisponibiliteVehicule, $data) 
  {
    
    // Do not allow to change indisponibilitevehicule if another indisponibilitevehicule with such data already exits
    if($this->checkIndisponibiliteVehiculeExists($data)) {
 
      return false;
    }
    
    //find vehicule id
    $idVehicule = $data['IDX_VEHICULE'];
    $vehicule = $this->vehiculeTable->findOneById($idVehicule);
    if($vehicule == null) {
      
      throw new \Exception('Vehicule not found');
    }
    
    $indisponibiliteVehicule->exchangeArray($data, false);

    // Apply changes to database.
    $this->indisponibiliteVehiculeTable->saveIndisponibiliteVehicule($indisponibiliteVehicule);
    
    return $indisponibiliteVehicule;
  }
  
  /**
   * Deletes the given vehicule.
   */
  public function deleteIndisponibiliteVehicule($id)
  {
    
    $this->indisponibiliteVehiculeTable->deleteIndisponibiliteVehicule($id);
  }

  /*
   *
   */
  public function checkIndisponibiliteVehiculeExists(array $data) 
  {

    $search['IDX_VEHICULE']             = $data['IDX_VEHICULE'];
    $search['STARTDATEINDISPONIBILITE'] = $data['STARTDATEINDISPONIBILITE'];
    $indisponibiliteVehicule = $this->indisponibiliteVehiculeTable->findOneBy($search);
    $bResult = false;
    if($indisponibiliteVehicule) {
      
      if($indisponibiliteVehicule->getJourEntier() || $data['ALLDAYINDISPONIBILITE']) {
        
        $bResult = true;
      } else {
        
        if($indisponibiliteVehicule->getDateFin() != $data['ENDDATEINDISPONIBILITE']) {
          
          $bResult = true;
        } else {
          
          if ($this->checkTimeBetween(
                $indisponibiliteVehicule->getHeureDebut(), 
                $data['STARTTIMEINDISPONIBILITE'], 
                $data['ENDTIMEINDISPONIBILITE']
                )
              ||
              $this->checkTimeBetween(
                $indisponibiliteVehicule->getHeurefin(),
                $data['STARTTIMEINDISPONIBILITE'], 
                $data['ENDTIMEINDISPONIBILITE']
                )
             ) {
                
            $bResult = true;
          } else {      
            $bResult = $this->checkRangeTimeBetween(
              $indisponibiliteVehicule->getHeureDebut(), 
              $indisponibiliteVehicule->getHeureFin(), 
              $data['STARTTIMEINDISPONIBILITE'], 
              $data['ENDTIMEINDISPONIBILITE']
            );
          }
        }
      }
    }
    
    return $bResult;
  }
  
  private function checkTimeBetween($value, $start, $end) 
  {
    
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
  
  /**
   * 
   */
  public function getVehicules() 
  {
    
    $vehiculesList = [];
    
    $vehicules = $this->vehiculeTable->fetchAll();

    foreach ($vehicules as $vehicule) {
      $vehiculesList[$vehicule->getId()] = $vehicule->getNom();
    }
    
    return $vehiculesList;
  }  
}

