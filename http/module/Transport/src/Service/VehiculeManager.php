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

use Transport\Model\Vehicule;
use Transport\Model\VehiculeTable;
use Transport\Model\MarqueTable;
use Transport\Model\typeVehiculeTable;


/**
 * 
 */
class VehiculeManager
{
  
  /*
   * Vehicule table manager.
   * @var Parling\Model\VehiculeTable
   */
  private $vehiculeTable;
  
  /*
   * marque table manager.
   * @var Parling\Model\MarqueTable
   */
  private $marqueTable;
  
  /*
   * marque table typevehicule.
   * @var Parling\Model\TypeVehiculeTable
   */
  private $typeVehiculeTable;
  
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
  public function __construct(VehiculeTable $vehiculeTable, MarqueTable $marqueTable, TypeVehiculeTable $typeVehiculeTable, $viewRenderer, $config) 
  {
    
    $this->vehiculeTable     = $vehiculeTable;
    $this->marqueTable       = $marqueTable;
    $this->typeVehiculeTable = $typeVehiculeTable;
    $this->viewRenderer = $viewRenderer;
    $this->config       = $config;
  }
 
  /*
   * This method adds a new vehicule.
   */
  public function addVehicule($data) 
  {
    
    // Create new Vehicule entity.
    $vehicule = new Vehicule();
    $vehicule->exchangeArray($data, false);
    
    //
    if(!$this->vehiculeTable->findOneByRecord($vehicule)) {
      $vehicule = $this->vehiculeTable->saveVehicule($vehicule);
      return $vehicule;
    }
    
    return;
  }
 
  /*
   * This method updates data of an existing vehicule.
   */
  public function updateVehicule($vehicule, $data) 
  {
    
    // Do not allow to change vehicule if another vehicule with such value already exits
    if($this->checkVehiculeExists($vehicule, $data)) {
      
      return false;
    }
    
    $vehicule->exchangeArray($data, false);
    $this->vehiculeTable->saveVehicule($vehicule);
    
    return $vehicule;
  }  
 
  /*
   * Deletes the given vehicule
   */
  public function deleteVehicule(Vehicule $vehicule)
  {
    
    $this->vehiculeTable->deleteVehicule($vehicule->getId());
  }
  
  /*
   * Checks whether an active vehicule with given value already exists in the database.     
   */
  public function checkVehiculeExists(Vehicule $vehicule, array $newData) {

    if(   
      $newData['IDX_TYPEVEHICULE'] != $vehicule->getIdTypeVehicule() 
      ||
      $newData['IDX_MARQUE']       != $vehicule->getIdMarque()
      ||
      $newData['NOMVEHICULE']      != $vehicule->getNom()
      ||
      $newData['PLACESVEHICULE']   != $vehicule->getPlaces()
      ||
      $newData['NUMEROVEHICULE']   != $vehicule->getNumero()
      ||
      $newData['PLAQUEVEHICULE']   != $vehicule->getPlaque()
      ||
      $newData['MODELEVEHICULE']   != $vehicule->getModele()
    ) {
      $search['IDX_TYPEVEHICULE'] = $newData['IDX_TYPEVEHICULE'];
      $search['IDX_MARQUE']       = $newData['IDX_MARQUE'];
      $search['NOMVEHICULE']      = $newData['NOMVEHICULE'];
      $search['PLACESVEHICULE']   = $newData['PLACESVEHICULE'];
      $search['NUMEROVEHICULE']   = $newData['NUMEROVEHICULE'];
      $search['PLAQUEVEHICULE']   = $newData['PLAQUEVEHICULE'];
      $search['MODELEVEHICULE']   = $newData['MODELEVEHICULE'];
        
      return ($this->vehiculeTable->findOneByRecord($search));
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
  public function getTypeVehicule() 
  {
    
    $typeVehiculeList = [];
    
    $typesVehicules = $this->typeVehiculeTable->fetchAll();

    foreach ($typesVehicules as $typeVehicule) {
      $typeVehiculeList[$typeVehicule->getId()] = $typeVehicule->getName();
    }
    
    return $typeVehiculeList;
  }  
}  

