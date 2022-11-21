<?php
/**
 * @package   : module/Transport/src/Model/IndisponibiliteChauffeur.php
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
use Laminas\Filter\ToInt;
use Laminas\Filter\Boolean;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
use Laminas\Validator\InArray;
use Laminas\Validator\Regex;
use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\DateTime;


/*
 * 
 */
class IndisponibiliteChauffeur implements InputFilterAwareInterface
{
   
  private $id;
  private $idChauffeur;
  private $dateDebut;
  private $heureDebut;
  private $dateFin;
  private $heureFin;
  private $jourEntier;

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
  public function exchangeArray(array $data, bool $bIdx=true)
  {
    
    if ($bIdx) {
      $this->id        = !empty($data['IDX_INDISPONIBILITECHAUFFEUR']) ? $data['IDX_INDISPONIBILITECHAUFFEUR'] : null;
    }
    $this->idChauffeur = !empty($data['IDX_CHAUFFEUR'])                ? $data['IDX_CHAUFFEUR']                : null;
    $this->dateDebut   = !empty($data['STARTDATEINDISPONIBILITE'])     ? $data['STARTDATEINDISPONIBILITE']     : null;
    $this->heureDebut  = !empty($data['STARTTIMEINDISPONIBILITE'])     ? $data['STARTTIMEINDISPONIBILITE']     : null;
    $this->dateFin     = !empty($data['DATEFININDISPONIBILITE'])       ? $data['ENDDATEINDISPONIBILITE']       : null;
    $this->heureFin    = !empty($data['TIMEFININDISPONIBILITE'])       ? $data['ENDTIMEINDISPONIBILITE']       : null;
    $this->jourEntier  = !empty($data['ALLDAYINDISPONIBILITE'])        ? $data['ALLDAYINDISPONIBILITE']        : false;
  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_INDISPONIBILITECHAUFFEUR' => $this->id,
        'IDX_CHAUFFEUR'                => $this->idChauffeur,
        'STARTDATEINDISPONIBILITE'     => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'     => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'       => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'       => $this->heureFin,
        'ALLDAYINDISPONIBILITE'        => (bool) $this->jourEntier,
      ];
    } else {
  
      return [
        'IDX_CHAUFFEUR'             => $this->idChauffeur,
        'STARTDATEINDISPONIBILITE'  => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'  => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'    => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'    => $this->heureFin,
        'ALLDAYINDISPONIBILITE'     => (bool) $this->jourEntier,
      ];
    }
  }
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //STARTDATEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'STARTDATEINDISPONIBILITE',
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
     
    //ENDDATEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'ENDDATEINDISPONIBILITE',
      'required'   => false,
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
    
    //STARTTIMEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'STARTTIMEINDISPONIBILITE',
      'required'   => false,
      'validators' => [
        [
          'name'    => Regex::class,
          'options' => [
            'pattern' => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'message' => 'Invalid time format',
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
    
    //ENDTIMEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'ENDTIMEINDISPONIBILITE',
      'required'   => false,
      'validators' => [
        [
          'name'    => Regex::class,
          'options' => [
            'pattern' => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'message' => 'Invalid time format',
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

    //ALLDAYINDISPONIBILITE
    $inputFilter->add([
      'name' => 'ALLDAYINDISPONIBILITE',
      'required'          => true,
      'allow_empty'       => true,
      'continue_if_empty' => true,        
      'description'       => 'all day ?',
      'filters' => [
        [
          'name'    => Boolean::class,
          'options' => [
            'type' => [
              Boolean::TYPE_BOOLEAN,
              Boolean::TYPE_INTEGER,
              Boolean::TYPE_ZERO_STRING,
            ],  
          ]
        ],
      ],
    ]);
    
    $this->inputFilter = $inputFilter;
    return $this->inputFilter;
  }

  // Setter
  //id
  public function getId() 
  {

    return $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
    
  //idChauffeur
  public function getIdChauffeur() 
  {
    
    return $this->idChauffeur;
  }

  //
  public function setIdChauffeur($id) 
  {
    
    $this->idChauffeur = $id;
  }

  //dateDebut
  public function getDateDebut() 
  {
    
    return $this->dateDebut;
  }

  //
  public function setDateDebut($dateDebut) 
  {
    
    $this->dateDebut = $dateDebut;
  }
  
  //dateFin
  public function getDateFin() 
  {
    
    return $this->dateFin;
  }

  //
  public function setDateFin($dateFin) 
  {
    
    $this->dateFin = $dateFin;
  }

  //heureDebut
  public function getHeureDebut() 
  {
    
    return $this->heureDebut;
  }

  //
  public function setHeureDebut($heureDebut) 
  {
    
    $this->heureDebut = $heureDebut;
  }
  
  //heureFin
  public function getHeureFin() 
  {
    
    return $this->heureFin;
  }

  //
  public function setHeureFin($heureFin) 
  {
    
    $this->heureFin = $heureFin;
  }
  
  //jourEntier
  public function getJourEntier() 
  {
    
    return $this->jourEntier;
  }

  //
  public function setJourEntier(bool $jourEntier) 
  {
    
    $this->jourEntier = $jourEntier;
  }
  

}