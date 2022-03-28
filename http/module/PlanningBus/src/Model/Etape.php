<?php
/**
 * @package   : module/PlanningBus/src/Model/Etape.php
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
class Etape implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;
  private $adresse;
  private $printed;


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

    $this->id      = !empty($data['IDX_ETAPE'])    ? $data['IDX_ETAPE']    : null;
    $this->nom     = !empty($data['NOMETAPE'])     ? $data['NOMETAPE']     : null;
    $this->adresse = !empty($data['ADRESSEETAPE']) ? $data['ADRESSEETAPE'] : null;
    $this->printed = !empty($data['PRINTEDETAPE']) ? $data['PRINTEDETAPE'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_ETAPE'    => $this->id,
      'NOMETAPE'     => $this->nom,
      'ADRESSEETAPE' => $this->adresse,
      'PRINTEDETAPE' => $this->printed,
    ];
  }    
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
    $inputFilter->add([
      'name' => 'NOMETAPE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 60,
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
      'name' => 'ADRESSEETAPE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 100,
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
  public function getAdresse() 
  {
    
    return $this->adresse;
  }

  //
  public function setAdresse($adresse) 
  {
    
    $this->adresse = $adresse;
  } 
   
  //
  public function getPrinted() 
  {
    
    return $this->printed;
  }

  //
  public function setPrinted($printed) 
  {
    
    $this->printed = $printed;
  } 
}  