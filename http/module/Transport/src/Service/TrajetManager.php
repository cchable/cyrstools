<?php
/**
 * This service is responsible for add/edit/delete 'type trajet'. 
 *
 * @package   module/Transport/src/Service/TypeTrajetManager.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Service;

use Transport\Model\Trajet;
use Transport\Model\TrajetTable;
use Transport\Model\EtapeTable;


/**
 * 
 */
class TrajetManager
{
  
  /**
   * Trajet table manager.
   * @var Parling\Model\TrajetTable
   */
  private $trajetTable;
    
  /**
   * Etape table manager.
   * @var Parling\Model\EtapeTable
   */
  private $etapeTable;
  
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

  /**
   * Constructs the service.
   */
  public function __construct(
    TrajetTable $trajetTable, 
    EtapeTable $etapeTable, 
    $viewRenderer, 
    $config) 
  {
    
    $this->trajetTable  = $trajetTable;
    $this->etapeTable   = $etapeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
 
  /**
   * This method adds a new trajet.
   */
  public function addTrajet($data) 
  {
    
    // Create new Trajet entity.
    $trajet = new Trajet();
    $trajet->exchangeArray($data, false);
    
    //
    if(!$this->trajetTable->findOneByNom($trajet->getNom())) {

      $record = $this->trajetTable->saveTrajet($trajet);
      return $record;
    }
    
    return 1;
  }
 
  /**
   * This method updates data of an existing trajet.
   */
  public function updateTrajet($trajet, $data) 
  {
    
    // Do not allow to change trajet if another trajet with such value already exits
    if($this->checkTrajetExists($trajet, $data)) {
      
      return false;
    }
    
    $trajet->exchangeArray($data, false);
    $this->trajetTable->saveTrajet($trajet);
    
    return $trajet;
  }  
 
  /**
   * Deletes the given trajet
   */
  public function deleteTrajet(Trajet $trajet)
  {
    
    $this->trajetTable->deleteTrajet($trajet->getId());
  }
  
  /**
   * Checks whether an active trajet with given value already exists in the database.     
   */
  public function checkTrajetExists(Trajet $trajet, array $newData) {

    if(   
      $newData['IDX_ETAPEDEPART']  != $trajet->getIdEtapeDepart() 
      ||
      $newData['IDX_ETAPEARRIVEE'] != $trajet->getIdEtapeArrivee()
      ||
      $newData['NOMTRAJET']        != $trajet->getNom()
      ||
      $newData['TEMPSTRAJET']      != $trajet->getTemps()
      ||
      $newData['KMTRAJET']         != $trajet->getKm()
    ) {
      
      $searh['IDX_ETAPEDEPART'] = $newData['IDX_ETAPEDEPART'];
      $searh['IDX_ETAPEARRIVEE'] = $newData['IDX_ETAPEARRIVEE'];
      
      //Clean $data from FROM
//      unset($newData['csrf']);
//      unset($newData['submit']);
        
      return ($this->trajetTable->findOneBy($searh));
    }   
      
    return true;
  }
  
  /**
   * 
   */
  public function getEtapes() 
  {
    
    $etapesList = [];
    
    $etapes = $this->etapeTable->fetchAll();

    foreach ($etapes as $etape) {
      $etapesList[$etape->getId()] = $etape->getNom();
    }
    
    return $etapesList;
  }  
}  

