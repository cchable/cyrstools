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
class Ephemeride implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idAnneeScolaire;
  protected $intitule;
  protected $dateDebut;
  protected $dateFin;

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
		
    $this->id              = !empty($data['IDX_EPHEMERIDE'])      ? $data['IDX_EPHEMERIDE'] : null;
    $this->idAnneeScolaire = !empty($data['IDX_ANNEESCOLAIRE'])   ? $data['IDX_ANNEESCOLAIRE'] : null;
    $this->intitule        = !empty($data['INTITULEEPHEMERIDE'])  ? $data['INTITULEEPHEMERIDE'] : null;
    $this->dateDebut       = !empty($data['DATEDEBUTEPHEMERIDE']) ? $data['DATEDEBUTEPHEMERIDE'] : null;
    $this->dateFin         = !empty($data['DATEFINEPHEMERIDE'])   ? $data['DATEFINEPHEMERIDE'] : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_EPHEMERIDE'      => $this->id,
      'IDX_ANNEESCOLAIRE'   => $this->idAnneeScolaire,
      'INTITULEEPHEMERIDE'  => $this->intitule,
      'DATEDEBUTEPHEMERIDE' => $this->dateDebut,
      'DATEFINEPHEMERIDE'   => $this->dateFin,
    ];
  }
    
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {
    
    // Add input for "IDX_ANNEESCOLAIRE" field
    $inputFilter->add([
      'name' => 'IDX_ANNEESCOLAIRE',
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

    // Add input for "INTITULEEPHEMERIDE" field
    $inputFilter->add([
      'name' => 'INTITULEEPHEMERIDE',
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
    
    $inputFilter->add([
      'name'       => 'DATEDEBUTEPHEMERIDE',
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
    
    //
    $inputFilter->add([
      'name'       => 'DATEFINEPHEMERIDE',
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
  public function getIdAnneeScolaire() 
  {
    
		return (int) $this->idAnneeScolaire;
  }
	
	//
  public function setIdAnneeScolaire($idAnneeScolaire) 
  {
    
		$this->idAnneeScolaire = $idAnneeScolaire;
  }
  
  public function getIntitule() 
  {
    return $this->intitule;
  }

  public function setIntitule($intitule) 
  {
    $this->intitule = $intitule;
  }

	//
  public function getDateDebut() 
  {
    
		return $this->dateDebut;
  }

	//
  public function setDateDebut($dateDebut) 
  {
    
		$this->dateDebut = $dateDebut;
  }

	//
  public function getDateFin() 
  {
    
		return $this->dateFin;
  }

	//
  public function setDateFin($dateFin) 
  {
    
		$this->dateFin = $dateFin;
  }
}