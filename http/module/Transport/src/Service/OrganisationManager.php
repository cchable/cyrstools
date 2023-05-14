<?php
/**
 * This service is responsible for add/edit/delete 'organisation'. 
 *
 * @package   module/Transport/src/Service/TypeOrganisationManager.php
 * @version   1.0.2
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Service;

use Transport\Model\Organisation;
use Transport\Model\OrganisationTable;
use Transport\Model\GroupeTable;


/**
 * 
 */
class OrganisationManager
{
  
  /**
   * Organisation table manager.
   * @var Parling\Model\OrganisationTable
   */
  private $organisationTable;
    
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
   * Builds the service.
   */
  public function __construct(
    OrganisationTable $organisationTable,
    GroupeTable       $groupeTable,
    $viewRenderer,
    $config)
  {
    
    $this->organisationTable  = $organisationTable;
    $this->groupeTable        = $groupeTable;
    $this->viewRenderer       = $viewRenderer;
    $this->config             = $config;
  }
 
  /**
   * This method adds a new organisation.
   */
  public function addOrganisation($data) 
  {
    
    // Create new Organisation entity.
    $organisation = new Organisation();
    $organisation->exchangeArray($data, false);
    
    //
    if(!$this->organisationTable->findOneByNom($organisation->getNom())) {

      $record = $this->organisationTable->saveOrganisation($organisation);
      return $record;
    }
    
    return 1;
  }
 
  /**
   * This method updates data of an existing organisation.
   */
  public function updateOrganisation($organisation, $data) 
  {
    
    // Do not allow to change organisation if another organisation with such value already exits
    if($this->checkOrganisationExists($organisation, $data)) {
      
      return false;
    }
    
    $organisation->exchangeArray($data, false);
    $this->organisationTable->saveOrganisation($organisation);
    
    return $organisation;
  }  
 
  /**
   * Deletes the given organisation
   */
  public function deleteOrganisation(Organisation $organisation)
  {
    
    $this->organisationTable->deleteOrganisation($organisation->getId());
  }
  
  /**
   * Checks whether an active organisation with given value already exists in the database.     
   */
  public function checkOrganisationExists(Organisation $organisation, array $newData) {

    if(   
      $newData['IDX_ETAPEDEPART']  != $organisation->getIdGroupeDepart() 
      ||
      $newData['IDX_ETAPEARRIVEE'] != $organisation->getIdGroupeArrivee()
      ||
      $newData['NOMTRAJET']        != $organisation->getNom()
      ||
      $newData['TEMPSTRAJET']      != $organisation->getTemps()
      ||
      $newData['KMTRAJET']         != $organisation->getKm()
    ) {
      
      $searh['IDX_ETAPEDEPART'] = $newData['IDX_ETAPEDEPART'];
      $searh['IDX_ETAPEARRIVEE'] = $newData['IDX_ETAPEARRIVEE'];
      
      //Clean $data from FROM
//      unset($newData['csrf']);
//      unset($newData['submit']);
        
      return ($this->organisationTable->findOneBy($searh));
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

