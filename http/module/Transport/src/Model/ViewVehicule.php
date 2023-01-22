<?php
/**
 * This is the ViewVehicule class for ViewVehiculeTableGateway service.
 * 
 * @package   module/Transport/src/Model/ViewVehicule.php
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
class ViewVehicule implements InputFilterAwareInterface
{

  private $id;
  private $idTypeVehicule;
  private $idMarque;
  private $nomVehicule;
  private $places;
  private $numero;
  private $plaque;
  private $modele;
  private $nomTypeVehicule;
  private $nomMarque;

  private $inputFilter;

  public function setInputFilter(InputFilterInterface $inputFilter)
  {
    throw new DomainException(sprintf(
      '%s does not allow injection of an alternate input filter',
      __CLASS__
    ));
  }

  //
  public function getInputFilter()
  {
		
    if ($this->inputFilter) {
      return $this->inputFilter;
    }

    $inputFilter = new InputFilter();
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
    $this->idMarque        = !empty($data['IDX_MARQUE'])       ? $data['IDX_MARQUE']        : null;
    $this->nomVehicule     = !empty($data['NOMVEHICULE'])      ? $data['NOMVEHICULE']       : null;
    $this->places          = !empty($data['PLACESVEHICULE'])   ? $data['PLACESVEHICULE']    : null;
    $this->numero          = !empty($data['NUMEROVEHICULE'])   ? $data['NUMEROVEHICULE']    : null;
    $this->plaque          = !empty($data['PLAQUEVEHICULE'])   ? $data['PLAQUEVEHICULE']    : null;
    $this->modele          = !empty($data['MODELEVEHICULE'])   ? $data['MODELEVEHICULE']    : null;
    $this->nomTypeVehicule = !empty($data['NOMTYPEVEHICULE'])  ? $data['NOMTYPEVEHICULE']   : null;
    $this->nomMarque       = !empty($data['NOMMARQUE'])        ? $data['NOMMARQUE']         : null;
  }

  /**
   *
   */
  public function getArrayCopy(bool $bIdx=true)
  {
   
    $result = [
      'IDX_TYPEVEHICULE' => $this->idTypeVehicule,
      'IDX_MARQUE'       => $this->idMarque,
      'NOMVEHICULE'      => $this->nomVehicule,
      'PLACESVEHICULE'   => $this->places,
      'NUMEROVEHICULE'   => $this->numero,
      'PLAQUEVEHICULE'   => $this->plaque,
      'MODELEVEHICULE'   => $this->modele,
      'NOMTYPEVEHICULE'  => $this->nomTypeVehicule,
      'NOMMARQUE'        => $this->nomMarque,
    ];
//ICI
    if ($bIdx) $result['IDX_VEHICULE'] = $this->id;

    return $result;
/*
    if ($bIdx) {
      
      return [
        'IDX_VEHICULE'      => $this->id,
        'IDX_TYPEVEHICULE,' => $this->idTypeVehicule,
        'IDX_MARQUE,'       => $this->idMarque,
        'NOMVEHICULE'       => $this->nomVehicule,
        'PLACESVEHICULE'    => $this->place,
        'NUMEROVEHICULE'    => $this->numero,
        'PLAQUEVEHICULE'    => $this->plaque,
        'MODELEVEHICULE'    => $this->modele,
        'NOMTYPEVEHICULE'   => $this->nomTypeVehicule,
        'NOMMARQUE'         => $this->nomMarque,
      ];
    } else {
  
      return [
        'IDX_TYPEVEHICULE,' => $this->idTypeVehicule,
        'IDX_MARQUE,'       => $this->idMarque,
        'NOMVEHICULE'       => $this->nomVehicule,
        'PLACESVEHICULE'    => $this->places,
        'NUMEROVEHICULE'    => $this->numero,
        'PLAQUEVEHICULE'    => $this->plaque,
        'MODELEVEHICULE'    => $this->modele,
        'NOMTYPEVEHICULE'   => $this->nomTypeVehicule,
        'NOMMARQUE'         => $this->nomMarque,
      ];
    }
  */
  }
  
  // Setter
  //id
  public function getId() 
  {

    return $this->id;
  }

  //idTypeVehicule
  public function getIdTypeVehicule() 
  {
    
    return $this->idTypeVehicule;
  }

  //idMarque
  public function getIdMarque() 
  {
    
    return $this->idMarque;
  }
  
  //nomVehicule
  public function getNomVehicule() 
  {
    
    return $this->nomVehicule;
  }

  //place
  public function getPlaces() 
  {
    
    return $this->places;
  }

  //numero
  public function getNumero() 
  {
    
    return $this->numero;
  }

  //plaque
  public function getPlaque() 
  {
    
    return $this->plaque;
  }

  //modele
  public function getModele() 
  {
    
    return $this->modele;
  }
  
  //nomTypeVehicule
  public function getNomTypeVehicule() 
  {
    
    return $this->nomTypeVehicule;
  }

  //nomMarque
  public function getNomMarque() 
  {
    
    return $this->nomMarque;
  }
}