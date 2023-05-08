<?php
/**
 * This service is responsible for add/edit/delete 'organisarion'. 
 *
 * @package   module/Transport/src/Service/TypeOrganisarionManager.php
 * @version   1.0.2
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Service;

use Transport\Model\Organisarion;
use Transport\Model\OrganisarionTable;
use Transport\Model\GroupeTable;


/**
 * 
 */
class OrganisarionManager
{
  
  /**
   * Organisarion table manager.
   * @var Parling\Model\OrganisarionTable
   */
  private $organisarionTable;
    
  /**
   * Groupe table manager.
   * @var Parling\Model\GroupeTable
   */
  private $groupeTable;
  
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
    OrganisarionTable $organisarionTable, 
    GroupeTable       $groupeTable, 
    $viewRenderer, 
    $config) 
  {
    
    $this->organisarionTable  = $organisarionTable;
    $this->groupeTable        = $groupeTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
 
  /**
   * This method adds a new organisarion.
   */
  public function addOrganisarion($data) 
  {
    
    // Create new Organisarion entity.
    $organisarion = new Organisarion();
    $organisarion->exchangeArray($data, false);
    
    //
    if(!$this->organisarionTable->findOneByNom($organisarion->getNom())) {

      $record = $this->organisarionTable->saveOrganisarion($organisarion);
      return $record;
    }
    
    return 1;
  }
 
  /**
   * This method updates data of an existing organisarion.
   */
  public function updateOrganisarion($organisarion, $data) 
  {
    
    // Do not allow to change organisarion if another organisarion with such value already exits
    if($this->checkOrganisarionExists($organisarion, $data)) {
      
      return false;
    }
    
    $organisarion->exchangeArray($data, false);
    $this->organisarionTable->saveOrganisarion($organisarion);
    
    return $organisarion;
  }  
 
  /**
   * Deletes the given organisarion
   */
  public function deleteOrganisarion(Organisarion $organisarion)
  {
    
    $this->organisarionTable->deleteOrganisarion($organisarion->getId());
  }
  
  /**
   * Checks whether an active organisarion with given value already exists in the database.     
   */
  public function checkOrganisarionExists(Organisarion $organisarion, array $newData) {

    if(   
      $newData['IDX_ETAPEDEPART']  != $organisarion->getIdGroupeDepart() 
      ||
      $newData['IDX_ETAPEARRIVEE'] != $organisarion->getIdGroupeArrivee()
      ||
      $newData['NOMTRAJET']        != $organisarion->getNom()
      ||
      $newData['TEMPSTRAJET']      != $organisarion->getTemps()
      ||
      $newData['KMTRAJET']         != $organisarion->getKm()
    ) {
      
      $searh['IDX_ETAPEDEPART'] = $newData['IDX_ETAPEDEPART'];
      $searh['IDX_ETAPEARRIVEE'] = $newData['IDX_ETAPEARRIVEE'];
      
      //Clean $data from FROM
//      unset($newData['csrf']);
//      unset($newData['submit']);
        
      return ($this->organisarionTable->findOneBy($searh));
    }   
      
    return true;
  }
  
  /**
   * 
   */
  public function getGroupes() 
  {
    
    $groupesList = [];
    
    $groupes = $this->groupeTable->fetchAll();

    foreach ($groupes as $groupe) {
      $groupesList[$groupe->getId()] = $groupe->getNom();
    }
    
    return $groupesList;
  }  
}  

