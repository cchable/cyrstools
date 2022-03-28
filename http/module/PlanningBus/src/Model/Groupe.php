<?php
/**
 * @package   : module/PlanningBus/src/Model/Groupe.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model;

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
class Groupe implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;
  private $nombre;


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

    $this->id     = !empty($data['IDX_GROUPE'])   ? $data['IDX_GROUPE']   : null;
    $this->nom    = !empty($data['NOMGROUPE'])    ? $data['NOMGROUPE']    : null;
    $this->nombre = !empty($data['NOMBREGROUPE']) ? $data['NOMBREGROUPE'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_GROUPE'   => $this->id,
      'NOMGROUPE'    => $this->nom,
      'NOMBREGROUPE' => $this->nombre,
    ];
  }    
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
    $inputFilter->add([
      'name' => 'NOMGROUPE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 20,
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
      'name' => 'NOMBREGROUPE',
      'required' => true,
      'validators' => [
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
  public function getNombre() 
  {
    
    return $this->nombre;
  }

  //
  public function setNombre($nombre) 
  {
    
    $this->nombre = $nombre;
  }
}  