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


/*
 * 
 */
class Vehicule implements InputFilterAwareInterface
{
  
  private $id;
  private $marque;
  private $modele;
  private $nom;
  private $places;

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

  //
  public function exchangeArray(array $data)
  {

    $this->id     = !empty($data['IDX_VEHICULE'])   ? $data['IDX_VEHICULE']   : null;
    $this->marque = !empty($data['MARQUEVEHICULE']) ? $data['MARQUEVEHICULE'] : null;
    $this->modele = !empty($data['MODELEVEHICULE']) ? $data['MODELEVEHICULE'] : null;
    $this->nom    = !empty($data['NOMVEHICULE'])    ? $data['NOMVEHICULE']    : null;
    $this->places = !empty($data['PLACESVEHICULE']) ? $data['PLACESVEHICULE'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_VEHICULE'   => $this->id,
      'MARQUEVEHICULE' => $this->marque,
      'MODELEVEHICULE' => $this->modele,
      'NOMVEHICULE'    => $this->nom,
      'PLACESVEHICULE' => $this->places,
    ];
  }    
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
    $inputFilter->add([
      'name' => 'MARQUEVEHICULE',
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
      'name' => 'MODELEVEHICULE',
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
    
    $this->inputFilter = $inputFilter;
    return $this->inputFilter;
  }

  //
  public function getId() 
  {

    return (int) $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
  
  //
  public function getMarque() 
  {
    
    return $this->marque;
  }

  //
  public function setMarque($marque) 
  {
    
    $this->marque = $marque;
  } 
   
  //
  public function getModele() 
  {
    
    return $this->modele;
  }

  //
  public function setModele($modele) 
  {
    
    $this->modele = $modele;
  } 
  
  //
  public function getNom() 
  {
    
    return $this->nom;
  }

  //
  public function setNom($nom) 
  {
    
    $this->nom = $nom;
  }
  
  //
  public function getPlaces()
  {
    
    return $this->places;
  }

  //
  public function setPlaces($places) 
  {
    $this->places = $places;
  }
}  