<?php
/**
 * @package   : module/PlanningBus/src/Model/Ephemeride.php
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
class Chauffeur implements InputFilterAwareInterface
{
	
  /*
   *
   */
  protected $id;
  
  /*
   *
   */
  protected $idVehicule;
  
  /*
   *
   */
  protected $prenom;

  /*
   *
   */
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
		
    $this->id         = !empty($data['IDX_CHAUFFEUR'])   ? $data['IDX_CHAUFFEUR']   : null;
    $this->idVehicule = !empty($data['IDX_VEHICULE'])    ? $data['IDX_VEHICULE']    : null;
    $this->prenom     = !empty($data['PRENOMCHAUFFEUR']) ? $data['PRENOMCHAUFFEUR'] : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_CHAUFFEUR'   => $this->id,
      'IDX_VEHICULE'    => $this->idVehicule,
      'PRENOMCHAUFFEUR' => $this->prenom,
    ];
  }
    
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {
    
    // Add input for "IDX_VEHICULE" field
    $inputFilter->add([
      'name' => 'IDX_VEHICULE',
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
   
    // Add input for "PRENOMCHAUFFEUR" field
    $inputFilter->add([
      'name' => 'PRENOMCHAUFFEUR',
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
  public function getIdVehicule() 
  {
    
		return (int) $this->idVehicule;
  }
	
	//
  public function setIdVehicule($idVehicule) 
  {
    
		$this->idVehicule = $idVehicule;
  }
  
  //
  public function getPrenom() 
  {
    
		return $this->prenom;
  }

  //
  public function setPrenom($prenom) 
  {
    
    $this->prenom = $prenom;
  }
}