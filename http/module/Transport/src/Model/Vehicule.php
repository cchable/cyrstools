<?php
/**
 * This is the Vehicule class for Vehicule service.
 * 
 * @package   module/Transport/src/Model/Vehicule.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;


/**
 * 
 */
class Vehicule implements InputFilterAwareInterface
{
  
  private $id;
  private $idTypeVehicule;
  private $idMarque;
  private $nom;
  private $places;
  private $numero;
  private $plaque;
  private $modele;

  private $inputFilter;


  /**
   *
   */
  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    
    throw new DomainException(sprintf(
      '%s does not allow injection of an alternate input filter',
      __CLASS__
    ));
  }

  /**
   * 
   * @return InputFilter
   */
  public function getInputFilter()
  {
		
    if ($this->inputFilter) {
      return $this->inputFilter;
    }

    $inputFilter = new InputFilter();
    
    return $this->inputFilter;
  }

  /**
   *
   */
  public function exchangeArray(array $data, bool $bIdx=true)
  {
 
    if ($bIdx) {
      $this->id            = !empty($data['IDX_VEHICULE'])     ? $data['IDX_VEHICULE']      : null;
    }
    $this->idTypeVehicule  = !empty($data['IDX_TYPEVEHICULE']) ? $data['IDX_TYPEVEHICULE'] : null;
    $this->idMarque        = !empty($data['IDX_MARQUE'])       ? $data['IDX_MARQUE']       : null;
    $this->nom             = !empty($data['NOMVEHICULE'])      ? $data['NOMVEHICULE']      : null;
    $this->places          = !empty($data['PLACESVEHICULE'])   ? $data['PLACESVEHICULE']   : null;
    $this->numero          = !empty($data['NUMEROVEHICULE'])   ? $data['NUMEROVEHICULE']   : null;
    $this->plaque          = !empty($data['PLAQUEVEHICULE'])   ? $data['PLAQUEVEHICULE']   : null;
    $this->modele          = !empty($data['MODELEVEHICULE'])   ? $data['MODELEVEHICULE']   : null;
  }
  
  //
  public function getArrayCopy(bool $bIdx=true)
  {

    $result = [
      'IDX_TYPEVEHICULE' => $this->idTypeVehicule,
      'IDX_MARQUE'       => $this->idMarque,
      'NOMVEHICULE'      => $this->nom,
      'PLACESVEHICULE'   => $this->places,
      'NUMEROVEHICULE'   => $this->numero,
      'PLAQUEVEHICULE'   => $this->plaque,
      'MODELEVEHICULE'   => $this->modele,

    ];

    if ($bIdx) $result['IDX_VEHICULE'] = $this->id;

    return $result;
  }    
  
  /**
   * Setter
   */
  //IDX_VEHICULE
  public function getId() 
  {

    return (int) $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
  
  //IDX_TYPEVEHICULE
  public function getIdTypeVehicule() 
  {

    return (int) $this->idTypeVehicule;
  }

  //
  public function setIdTypeVehicule($idTypeVehicule) 
  {
  
    $this->idTypeVehicule = $idTypeVehicule;
  }
  
  //IDX_MARQUE
  public function getIdMarque() 
  {

    return (int) $this->idMarque;
  }

  //
  public function setIdMarque($idMarque) 
  {
  
    $this->idMarque = $idMarque;
  }
   
  //MODELEVEHICULE
  public function getModele() 
  {
    
    return $this->modele;
  }

  //
  public function setModele($modele) 
  {
    
    $this->modele = $modele;
  } 
  
  //NOMVEHICULE
  public function getNom() 
  {
    
    return $this->nom;
  }

  //
  public function setNom($nom) 
  {
    
    $this->nom = $nom;
  }
  
  //PLACESVEHICULE
  public function getPlaces()
  {
    
    return $this->places;
  }

  //
  public function setPlaces($places) 
  {
    $this->places = $places;
  }  
  
  //NUMEROVEHICULE
  public function getNumero()
  {
    
    return $this->numero;
  }

  //
  public function setNumero($numero) 
  {
    $this->numero = $numero;
  } 
  
  //PLAQUEVEHICULE
  public function getPlaque()
  {
    
    return $this->plaque;
  }

  //
  public function setPlaque($plaque) 
  {
    $this->plaque = $plaque;
  }
}  