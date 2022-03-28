<?php
/**
 * @package   : module/PlanningBus/src/Model/DatePlanning.php
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
use Laminas\Validator\InArray;

use Laminas\I18n\Validator\DateTime;


/*
 * 
 */
class DatePlanning implements InputFilterAwareInterface
{
  
  private $id;
  private $date;
  private $codeSemaine;

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

    $this->id          = !empty($data['IDX_DATEPLANNING'])        ? $data['IDX_DATEPLANNING'] : null;
    $this->date        = !empty($data['DATEDATEPLANNING'])        ? $data['DATEDATEPLANNING'] : null;
    $this->codeSemaine = !empty($data['CODESEMAINEDATEPLANNING']) ? $data['CODESEMAINEDATEPLANNING'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_DATEPLANNING'        => $this->id,
      'DATEDATEPLANNING'        => $this->date,
      'CODESEMAINEDATEPLANNING' => $this->codeSemaine,
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
   
    // Add input for "DATEDATEPLANNING" field   
    $inputFilter->add([
      'name'       => 'DATEDATEPLANNING',
      'required'   => true,
      'validators' => [
        [
          'name'    => DateTime::class,
          'options' => [
            'pattern' => 'Y-m-d',
            'message' => 'Invalid date format',
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
    
    // Add input for "CODESEMAINEDATEPLANNING" field
    $inputFilter->add([
      'name' => 'CODESEMAINEDATEPLANNING',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 0,
            'max'      => 1,
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
  public function getDate() 
  {
    return $this->date;
  }

  //
  public function setDate($date) 
  {
    $this->date = $date;
  }
  
  //
  public function getCode() 
  {
    return $this->codeSemaine;
  }

  //
  public function setCode($code) 
  {
    $this->codeSemaine = $code;
  }
}  