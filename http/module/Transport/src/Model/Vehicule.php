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

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
use Laminas\Validator\InArray;
use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\DateTime;


/**
 * 
 */
class Vehicule implements InputFilterAwareInterface
{
  
  private $id;
  private $idTypeVehicule;
  private $idMarque;
  private $nomVehicule;
  private $places;
  private $numero;
  private $plaque;
  private $modele;

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
    $this->inputFilter = fillInputFilter($inputFilter);
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
    $this->nomVehicule     = !empty($data['NOMVEHICULE'])      ? $data['NOMVEHICULE']      : null;
    $this->places          = !empty($data['PLACESVEHICULE'])   ? $data['PLACESVEHICULE']   : null;
    $this->numero          = !empty($data['NUMEROVEHICULE'])   ? $data['NUMEROVEHICULE']   : null;
    $this->plaque          = !empty($data['PLAQUEVEHICULE'])   ? $data['PLAQUEVEHICULE']   : null;
    $this->nomMarque       = !empty($data['NOMMARQUE'])        ? $data['NOMMARQUE']        : null;
  }
  
  //
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

    ];

    if ($bIdx) $result['IDX_VEHICULE'] = $this->id;

    return $result;
  }    
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
    $inputFilter->add([
      'name' => 'NOMVEHICULE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
          ],
        ],
      ],
      'filters' => [
        [
          'name' => StringTrim::class,
        ],
        [
          'name' => StripTags::class,
        ],
      ],
    ]);

    //
    $inputFilter->add([
      'name' => 'PLACESVEHICULE',
      'required' => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name' => IsInt::class,
        ],        
      ],
      'filters' => [
        [
          'name' => ToInt::class,
        ],
      ],
    ]);
 
    //
    $inputFilter->add([
      'name' => 'NUMEROVEHICULE',
      'required' => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name' => IsInt::class,
        ],        
      ],
      'filters' => [
        [
          'name' => ToInt::class,
        ],
      ],
    ]);
 
     //
    $inputFilter->add([
      'name'        => 'PLAQUEVEHICULE',
      'required'    => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
          ],
        ],        
      ],
      'filters' => [
        [
          'name' => StringTrim::class,
        ],
        [
          'name' => StripTags::class,
        ],
      ],
    ]);
 
    //
    $inputFilter->add([
      'name'        => 'MODELEVEHICULE',
      'required'    => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
          ],
        ],
      ],
      'filters' => [
        [
          'name' => StringTrim::class,
        ],
        [
          'name' => StripTags::class,
        ],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    return $this->inputFilter;
  }

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