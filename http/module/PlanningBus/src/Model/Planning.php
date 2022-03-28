<?php
/**
 * @package   : module/PlanningBus/src/Model/Planning.php
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
class Planning implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idTypePlanning;
  protected $idDatePlanning;
  protected $idHeurePlanning;

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
		
    $this->id              = !empty($data['IDX_PLANNING'])      ? $data['IDX_PLANNING'] : null;
    $this->idTypePlanning  = !empty($data['IDX_TYPEPLANNING'])  ? $data['IDX_TYPEPLANNING'] : null;
    $this->idDatePlanning  = !empty($data['IDX_DATEPLANNING'])  ? $data['IDX_DATEPLANNING'] : null;
    $this->idHeurePlanning = !empty($data['IDX_HEUREPLANNING']) ? $data['IDX_HEUREPLANNING'] : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_PLANNING'      => $this->id,
      'IDX_TYPEPLANNING'  => $this->idTypePlanning,
      'IDX_DATEPLANNING'  => $this->idDatePlanning,
      'IDX_HEUREPLANNING' => $this->idHeurePlanning,
    ];
  }
    
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {
    
    // Add input for "IDX_TYPEPLANNING" field
    $inputFilter->add([
      'name' => 'IDX_TYPEPLANNING',
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
    
    // Add input for "IDX_DATEPLANNING" field
    $inputFilter->add([
      'name' => 'IDX_DATEPLANNING',
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

    // Add input for "IDX_HEUREPLANNING" field
    $inputFilter->add([
      'name' => 'IDX_HEUREPLANNING',
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

        
    //$this->inputFilter = $inputFilter;
    //return $this->inputFilter;
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
  public function getIdTypePlanning() 
  {
    
		return $this->idTypePlanning;
  }
	
  //
  public function setIdTypePlanning($idTypePlanning) 
  {
    
		$this->idTypePlanning = $idTypePlanning;
  }	
  
  //
  public function getIdDatePlanning() 
  {
    
		return $this->idDatePlanning;
  }
	
  //
  public function setIdDatePlanning($idDatePlanning) 
  {
    
		$this->idDatePlanning = $idDatePlanning;
  }	
   
  //
  public function getIdHeurePlanning() 
  {
    
		return $this->idHeurePlanning;
  }
	
  //
  public function setIdHeurePlanning($idHeurePlanning) 
  {
    
		$this->idHeurePlanning = $idHeurePlanning;
  }	   
}