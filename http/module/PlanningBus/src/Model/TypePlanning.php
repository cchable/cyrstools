<?php
/**
 * @package   : module/PlanningBus/src/Model/TypePlanning.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model;

use DomainException;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;


/*
 * 
 */
class TypePlanning implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;

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

    $this->id  = !empty($data['IDX_TYPEPLANNING']) ? $data['IDX_TYPEPLANNING'] : null;
    $this->nom = !empty($data['NOMTYPEPLANNING'])  ? $data['NOMTYPEPLANNING'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_TYPEPLANNING' => $this->id,
      'NOMTYPEPLANNING'  => $this->nom,
    ];
  }    

  //
  /*
  public function getInputFilter()
  {
    
    if ($this->inputFilter) {
      return $this->inputFilter;
    }

    $inputFilter = new InputFilter();
    
    $inputFilter->add([
      'name' => 'IDX_ANNEESCOLAIRE',
      'required' => true,
      'filters' => [
        ['name' => ToInt::class],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    return $this->inputFilter;
*/

  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {
   
    // Add input for "NOMTYPEPLANNING" field
    $inputFilter->add([
      'name'       => 'NOMTYPEPLANNING',
      'required'   => true,
      'validators' => [
        [
          'name'    => StringLength::class,
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
    
    return $inputFilter;
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
}  