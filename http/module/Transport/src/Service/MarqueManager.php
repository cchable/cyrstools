<?php
/**
 * This service is responsible for add/edit/delete marque. 
 *
 * @package   module/Transport/src/Service/MarqueManager.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Service;

use Transport\Model\Marque;
use Transport\Model\MarqueTable;

/*
 * 
 */
class MarqueManager
{
  
  /*
   * Marque table manager.
   * @var Parling\Model\MarqueTable
   */
  private $marqueTable;
  
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
    
  /*
   * This method update datas of an existing marque
   */
  public function updateMarque($marque, $data) 
  {
    
    // Do not allow to change marque if another marque with such data already exits
    //if($this->checkMarqueExists($data)) {
    if($marque->getMarque()!=$data['NOMMARQUE'] && $this->checkMarqueExists($data)) {  
      
      return false;
    }
    $marque->exchangeArray($data, false);

    // Apply changes to database.
    $this->marqueTable->saveMarque($marque);
    
    return $marque;
  }
  
  /**
   * Deletes the given marque.
   */
  public function deleteMarque($id)
  {
    
    $this->marqueTable->deleteMarque($id);
  }

  /*
   *
   */
  public function checkMarqueExists(array $data) {

    $search['NOMMARQUE'] = $data['NOMMARQUE'];
    $marque = $this->marqueTable->findOneBy($search);
    return $marque;
  }  
}

