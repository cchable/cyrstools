<?php
/**
 * @package   : module/PlanningBus/src/Model/Trajet.php
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
use Laminas\I18n\Filter\NumberParse;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
use Laminas\Validator\Regex;

use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\IsFloat;


/*
 * 
 */
class Trajet implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idEtapeDepart;
  protected $idEtapeArrivee;
  protected $nom;
  protected $temps;
  protected $km;

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
		
    $this->id             = !empty($data['IDX_TRAJET'])       ? $data['IDX_TRAJET'] : null;
    $this->idEtapeDepart  = !empty($data['IDX_ETAPEDEPART'])  ? $data['IDX_ETAPEDEPART'] : null;
    $this->idEtapeArrivee = !empty($data['IDX_ETAPEARRIVEE']) ? $data['IDX_ETAPEARRIVEE'] : null;
    $this->nom            = !empty($data['NOMTRAJET'])        ? $data['NOMTRAJET'] : null;
    $this->temps          = !empty($data['TEMPSTRAJET'])      ? substr($data['TEMPSTRAJET'], 0, 5) : null;
    $this->km             = !empty($data['KMTRAJET'])         ? number_format($data['KMTRAJET'], 1, ',', ' ') : null;
   }

  //
	public function getArrayCopy($filter = true)
  {
		
    $filterNumberParse = new NumberParse();
    return [
      'IDX_TRAJET'       => $this->id,
      'IDX_ETAPEDEPART'  => $this->idEtapeDepart,
      'IDX_ETAPEARRIVEE' => $this->idEtapeArrivee,
      'NOMTRAJET'        => $this->nom,
      'TEMPSTRAJET'      => $this->temps,
      'KMTRAJET'         => ($filter ? $filterNumberParse->filter($this->km) : $this->km),
    ];
  }
    
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {
    
    // Add input for "IDX_ETAPEDEPART" field
    $inputFilter->add([
      'name'        => 'IDX_ETAPEDEPART',
      'required'    => true,
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
    
    // Add input for "IDX_ETAPEARRIVEE" field
    $inputFilter->add([
      'name'        => 'IDX_ETAPEARRIVEE',
      'required'    => true,
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

    // Add input for "NOMTRAJET" field
    $inputFilter->add([
      'name'       => 'NOMTRAJET',
      'required'   => true,
      'validators' => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 50,
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
    
    // Add input for "TEMPSTRAJET" field
    $inputFilter->add([
      'name'       => 'TEMPSTRAJET',
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
    
    // Add input for "KMTRAJET" field
    $inputFilter->add([
      'name'        => 'KMTRAJET',
      'required'    => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name'    => IsFloat::class,
          'options' => [
            'locale' => 'fr_FR',
          ],
        ],
      ],
      'filters' => [
        [
          'name'    => NumberParse::class,
          'options' => [
            'locale' => 'be_FR',
          ],
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
    
		return $this->id;
  }

	//
  public function setId($id) 
  {
    
		$this->id = $id;
  }
	
	//
  public function getIdEtapeDepart() 
  {
    
		return (int) $this->idEtapeDepart;
  }
	
	//
  public function setIdEtapeDepart($idEtapeDepart) 
  {
    
		$this->idEtapeDepart = $idEtapeDepart;
  }	
  
  //
  public function getIdEtapeArrivee() 
  {
    
		return (int) $this->idEtapeArrivee;
  }
	
	//
  public function setIdEtapeArrivee($idEtapeArrivee) 
  {
    
		$this->idEtapeArrivee = $idEtapeArrivee;
  }
  
  //
  public function getNom() 
  {
    
    return $this->nom;
  }

  public function setNom($nom) 
  {
    
    $this->nom = $nom;
  }
  
  //
  public function getTemps() 
  {
    
    return $this->temps;
  }

  public function setTemps($temps) 
  {
    
    $this->temps = substr($temps, 0, 5);
  }
  
  //
  public function getKm() 
  {
    
    return $this->km;
  }

  //
  public function setKm($km) 
  {
    
    $this->km = $km;
  }
}