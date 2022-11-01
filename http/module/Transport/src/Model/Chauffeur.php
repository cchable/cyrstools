<?php
/**
 * @package   : module/PlanningBus/src/Model/Chauffeur.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
//use Laminas\Filter\ToInt;
use Laminas\Filter\Boolean;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
//use Laminas\Validator\InArray;
//use Laminas\I18n\Validator\IsInt;
//use Laminas\I18n\Validator\DateTime;


/*
 * 
 */
class Chauffeur implements InputFilterAwareInterface
{
  
  private $id;
  private $prenom;
  private $principal;
  private $actif;

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

    $this->id        = !empty($data['IDX_CHAUFFEUR'])      ? $data['IDX_CHAUFFEUR']      : null;
    $this->prenom    = !empty($data['PRENOMCHAUFFEUR'])    ? $data['PRENOMCHAUFFEUR']    : null;
    $this->principal = !empty($data['PRINCIPALCHAUFFEUR']) ? $data['PRINCIPALCHAUFFEUR'] : null;
    $this->actif     = !empty($data['ACTIFCHAUFFEUR'])     ? $data['ACTIFCHAUFFEUR']     : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_CHAUFFEUR'      => $this->id,
      'PRENOMCHAUFFEUR'    => $this->prenom,
      'PRINCIPALCHAUFFEUR' => $this->principal,
      'ACTIFCHAUFFEUR'     => $this->actif,

    ];
  }    
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
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
    
    $inputFilter->add([
      'name' => 'PRINCIPALCHAUFFEUR',
      'required' => true,
      'filters' => [
        [
          'name' => Boolean::class,
          'options' => [
            'type' => Boolean::TYPE_INTEGER + Boolean::TYPE_ZERO_STRING,
          ]
        ],
      ],
    ]);
    
    $inputFilter->add([
      'name' => 'ACTIFCHAUFFEUR',
      'required' => true,
      'filters' => [
        [
          'name' => Boolean::class,
          'options' => [
            'type' => Boolean::TYPE_INTEGER + Boolean::TYPE_ZERO_STRING,
          ]
        ],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    return $this->inputFilter;
  }

  // Setter
  // id
  public function getId() 
  {

    return (int) $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
    
  // prenom
  public function getPrenom() 
  {
    
    return $this->prenom;
  }

  //
  public function setPrenom($prenom) 
  {
    
    $this->prenom = $prenom;
  }

  // principal
  public function getPrincipal() 
  {
    
    return $this->principal;
  }

  //
  public function setPrincipal($principal) 
  {
    
    $this->principal = $principal;
  }
  
   // actif
  public function getActif() 
  {
    
    return $this->actif;
  }

  //
  public function setActif($actif) 
  {
    
    $this->actif = $actif;
  } 
}