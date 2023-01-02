<?php
/**
 * This service is responsible for add/edit/delete 'marque'. 
 *
 * @package   module/Transport/src/Service/MarqueManager.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\Marque;
use Transport\Model\MarqueTable;

/**
 * MarqueManager class
 */
class MarqueManager
{
  
  /*
   * Marque table manager.
   * @var class Transport\Model\MarqueTable
   * @access private
   */
  private $marqueTable;
  
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
   * @param class $marqueTable
   * @param array $viewRenderer
   * @param array $config
   * @access public
   */
  public function __construct(MarqueTable $marqueTable, $viewRenderer, $config) 
  {
    
    $this->marqueTable  = $marqueTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
    
  /*
   * This method adds a new marque.
   */
  public function addMarque($data) 
  {
    
    // Do not allow to add marque if another marque with such data already exists
    $result = $this->checkMarqueExists($data);
    if($result) {
         
      return false;
    }
      
    // Create new Marque entiy.
    $newMarque= new Marque();
    $newMarque->exchangeArray($data);  
    $marque = $this->marqueTable->saveMarque($newMarque);
    
    return $marque;
  }
    
  /**
   * Update datas of an existing marque
   *
   * @param class $marque
   * @param array $data
   * @return class $marque Transport\Model\Marque
   * @access public
   */
  public function updateMarque($marque, $data) 
  {
    
    // Do not allow to change marque if another marque with such data already exits
    if($marque->getName()!=$data['NOMMARQUE'] && $this->checkMarqueExists($data)) {  
      
      return false;
    }
    $marque->exchangeArray($data, false);

    // Apply changes to database.
    $this->marqueTable->saveMarque($marque);
    
    return $marque;
  }
  
  /**
   * Deletes the given marque.
   *
   * @param int $id
   * @access public
   */
  public function deleteMarque($id)
  {
    
    $this->marqueTable->deleteMarque($id);
  }

  /**
   * Check if a 'marque' exists
   *
   * @param array $data
   * @return class $marque Transport\Model\Marque
   * @access public
   */
  public function checkMarqueExists(array $data) {

    $search['NOMMARQUE'] = $data['NOMMARQUE'];
    $marque = $this->marqueTable->findOneBy($search);
    return $marque;
  }  
}

