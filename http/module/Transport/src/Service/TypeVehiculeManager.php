<?php
/**
 * This service is responsible for add/edit/delete 'type vehicule'. 
 *
 * @package   module/Transport/src/Service/TypeVehiculeManager.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\TypeVehicule;
use Transport\Model\TypeVehiculeTable;

/**
 * TypeVehiculeManager class
 */
class TypeVehiculeManager
{
  
  /*
   * TypeVehicule table manager.
   * @var class Transport\Model\TypeVehiculeTable
   * @access private
   */
  private $typeVehiculeTable;
  
  /**
   * PHP template renderer.
   * @var array
   * @access private   
   */
  private $viewRenderer;

  /**
   * Application config.
   * @var array 
   * @access private
   */
  private $config;

  
  /**
   * Constructs the service.
   *
   * @param class $typeVehiculeTable
   * @param array $viewRenderer
   * @param array $config
   * @access public
   */
  public function __construct(TypeVehiculeTable $typeVehiculeTable, $viewRenderer, $config) 
  {
    
    $this->typeVehiculeTable = $typeVehiculeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new typeVehicule.
   */
  public function addTypeVehicule($data) 
  {
    
    // Do not allow to add typeVehicule if another typeVehicule with such data already exists
    $result = $this->checkTypeVehiculeExists($data);
    if($result) {
         
      return false;
    }
      
    // Create new TypeVehicule entiy.
    $newTypeVehicule= new TypeVehicule();
    $newTypeVehicule->exchangeArray($data);  
    $typeVehicule = $this->typeVehiculeTable->saveTypeVehicule($newTypeVehicule);
    
    return $typeVehicule;
  }
    
  /**
   * Update datas of an existing typeVehicule
   *
   * @param class $typeVehicule
   * @param array $data
   * @return class $typeVehicule Transport\Model\TypeVehicule
   * @access public
   */
  public function updateTypeVehicule($typeVehicule, $data) 
  {
    
    // Do not allow to change typeVehicule if another typeVehicule with such data already exits
    if($typeVehicule->getName()!=$data['NOMTYPEVEHICULE'] && $this->checkTypeVehiculeExists($data)) {  
      
      return false;
    }
    $typeVehicule->exchangeArray($data, false);

    // Apply changes to database.
    $this->typeVehiculeTable->saveTypeVehicule($typeVehicule);
    
    return $typeVehicule;
  }
  
  /**
   * Deletes the given typeVehicule.
   *
   * @param int $id
   * @access public
   */
  public function deleteTypeVehicule($id)
  {
    
    $this->typeVehiculeTable->deleteTypeVehicule($id);
  }

  /**
   * Check if a 'typeVehicule' exists
   *
   * @param array $data
   * @return class $typeVehicule Transport\Model\TypeVehicule
   * @access public
   */
  public function checkTypeVehiculeExists(array $data) {

    $search['NOMTYPEVEHICULE'] = $data['NOMTYPEVEHICULE'];
    $typeVehicule = $this->typeVehiculeTable->findOneBy($search);
    return $typeVehicule;
  }  
}

