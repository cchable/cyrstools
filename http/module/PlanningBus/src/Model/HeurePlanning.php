<?php
/**
 * @package   : module/PlanningBus/src/Model/HeurePlanning.php
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
use Laminas\Validator\Regex;


/*
 * 
 */
class HeurePlanning implements InputFilterAwareInterface
{
  
  private $id;
  private $heure;

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

    $this->id    = !empty($data['IDX_HEUREPLANNING'])  ? $data['IDX_HEUREPLANNING'] : null;
    $this->heure = !empty($data['HEUREHEUREPLANNING']) ? substr($data['HEUREHEUREPLANNING'], 0, 5) : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_HEUREPLANNING'  => $this->id,
      'HEUREHEUREPLANNING' => $this->heure,
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
   
    // Add input for "HEUREHEUREPLANNING" field   
    $inputFilter->add([
      'name'       => 'HEUREHEUREPLANNING',
      'required'   => true,
      'validators' => [
        [
          'name'    => Regex::class,
          'options' => [
            'pattern' => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
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
  public function getHeure() 
  {
    
    return $this->heure;
  }

  //
  public function setHeure($heure) 
  {
    
    $this->heure = substr($heure, 0, 5);
  }
}  