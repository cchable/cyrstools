<?php
/**
 * This service is responsible for add/edit/delete 'groupe'. 
 *
 * @package   module/Transport/src/Service/TypeGroupeManager.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Service;

use Transport\Model\Groupe;
use Transport\Model\GroupeTable;


/**
 * 
 */
class GroupeManager
{
  
  /**
   * Groupe table manager.
   * @var Parling\Model\GroupeTable
   */
  private $groupeTable;

  /**
   * Constructs the service.
   */
  public function __construct(
    GroupeTable $groupeTable,
  ) 
  {
    
    $this->groupeTable  = $groupeTable;
  }
 
  /**
   * This method adds a new groupe.
   */
  public function addGroupe($data) 
  {
    
    // Create new Groupe entity.
    $groupe = new Groupe();
    $groupe->exchangeArray($data, false);
    
    //
    if(!$this->groupeTable->findOneByNom($groupe->getNom())) {

      $record = $this->groupeTable->saveGroupe($groupe);
      return $record;
    }
    
    return 1;
  }
 
  /**
   * This method updates data of an existing groupe.
   */
  public function updateGroupe($groupe, $data) 
  {
    
    // Do not allow to change groupe if another groupe with such value already exits
    if($this->checkGroupeExists($groupe, $data)) {
      
      return false;
    }
    
    $groupe->exchangeArray($data, false);
    $this->groupeTable->saveGroupe($groupe);
    
    return $groupe;
  }  
 
  /**
   * Deletes the given groupe
   */
  public function deleteGroupe(Groupe $groupe)
  {
    
    $this->groupeTable->deleteGroupe($groupe->getId());
  }
  
  /**
   * Checks whether an active groupe with given value already exists in the database.     
   */
  public function checkGroupeExists(Groupe $groupe, array $newData) {

    if($newData['NOMGROUPE'] == $groupe->getNom()) {
      
      return false;
    } else {
      
      return ($this->groupeTable->findOneByNom($newData['NOMGROUPE']));
    }
  }
}  

