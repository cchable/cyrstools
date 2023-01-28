<?php
/**
 * This service is responsible for add/edit/delete 'type etape'. 
 *
 * @package   module/Transport/src/Service/TypeEtapeManager.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Service;

use Transport\Model\Etape;
use Transport\Model\EtapeTable;


/**
 * 
 */
class EtapeManager
{
  
  /*
   * Etape table manager.
   * @var Parling\Model\EtapeTable
   */
  private $etapeTable;
  
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
  public function __construct(EtapeTable $etapeTable, $viewRenderer, $config) 
  {
    
    $this->etapeTable   = $etapeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
 
  /*
   * This method adds a new etape.
   */
  public function addEtape($data) 
  {
    // Create new Etape entity.
    $etape = new Etape();
    $etape->exchangeArray($data, false);
    
    //
    if(!$this->etapeTable->findOneByNom($etape->getNom())) {

      $record = $this->etapeTable->saveEtape($etape);
      return $record;
    }
    
    return 1;
  }
 
  /*
   * This method updates data of an existing etape.
   */
  public function updateEtape($etape, $data) 
  {
    
    // Do not allow to change etape if another etape with such value already exits
    if($this->checkEtapeExists($etape, $data)) {
      
      return false;
    }
    
    $etape->exchangeArray($data, false);
    $this->etapeTable->saveEtape($etape);
    
    return $etape;
  }  
 
  /*
   * Deletes the given etape
   */
  public function deleteEtape(Etape $etape)
  {
    
    $this->etapeTable->deleteEtape($etape->getId());
  }
  
  /*
   * Checks whether an active etape with given value already exists in the database.     
   */
  public function checkEtapeExists(Etape $etape, array $newData) {

    if(   
      $newData['NOMETAPE']       != $etape->getNom() 
      ||
      $newData['ADRESSEETAPE']   != $etape->getAdresse()
      ||
      $newData['PRINTEDETAPE']   != $etape->getPrinted()
      ||
      $newData['LATITUDEETAPE']  != $etape->getLatitude()
      ||
      $newData['LONGITUDEETAPE'] != $etape->getLongitude()
      ||
      $newData['ALTITUDEETAPE']  != $etape->getAltitude()
    ) {
      
      //Clean $data from FROM
      unset($newData['csrf']);
      unset($newData['submit']);
        
      return ($this->etapeTable->findOneBy($newData));
    }   
      
    return true;
  }  
}  

