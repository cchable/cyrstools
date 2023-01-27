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
    if(!$this->etapeTable->findOneByRecord($etape)) {
      $etape = $this->etapeTable->saveEtape($etape);
      return $etape;
    }
    
    return;
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
      $newData['IDX_TYPEVEHICULE'] != $etape->getIdTypeEtape() 
      ||
      $newData['IDX_MARQUE']       != $etape->getIdMarque()
      ||
      $newData['NOMVEHICULE']      != $etape->getNom()
      ||
      $newData['PLACESVEHICULE']   != $etape->getPlaces()
      ||
      $newData['NUMEROVEHICULE']   != $etape->getNumero()
      ||
      $newData['PLAQUEVEHICULE']   != $etape->getPlaque()
      ||
      $newData['MODELEVEHICULE']   != $etape->getModele()
    ) {
      $search['IDX_TYPEVEHICULE'] = $newData['IDX_TYPEVEHICULE'];
      $search['IDX_MARQUE']       = $newData['IDX_MARQUE'];
      $search['NOMVEHICULE']      = $newData['NOMVEHICULE'];
      $search['PLACESVEHICULE']   = $newData['PLACESVEHICULE'];
      $search['NUMEROVEHICULE']   = $newData['NUMEROVEHICULE'];
      $search['PLAQUEVEHICULE']   = $newData['PLAQUEVEHICULE'];
      $search['MODELEVEHICULE']   = $newData['MODELEVEHICULE'];
        
      return ($this->etapeTable->findOneByRecord($search));
    }   
      
     return false;
  }  
  
  /**
   * 
   */
  public function getMarque() 
  {
    
    $marqueList = [];
    
    $marques = $this->marqueTable->fetchAll();

    foreach ($marques as $marque) {
      $marqueList[$marque->getId()] = $marque->getName();
    }
    
    return $marqueList;
  }
  
  /**
   * 
   */
  public function getTypeEtape() 
  {
    
    $typeEtapeList = [];
    
    $typesEtapes = $this->typeEtapeTable->fetchAll();

    foreach ($typesEtapes as $typeEtape) {
      $typeEtapeList[$typeEtape->getId()] = $typeEtape->getName();
    }
    
    return $typeEtapeList;
  }  
}  

