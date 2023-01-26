<?php
/**
 * This is the IndisponibiliteVehicule class for IndisponibiliteVehicule service.
 * 
 * @package   module/Transport/src/Model/IndisponibiliteVehicule.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
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
class IndisponibiliteVehicule implements InputFilterAwareInterface
{
   
  private $id;
  private $idVehicule;
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
      $this->id       = !empty($data['IDX_INDISPONIBILITEVEHICULE']) ? $data['IDX_INDISPONIBILITEVEHICULE'] : null;
    }
    $this->idVehicule = !empty($data['IDX_VEHICULE'])                ? $data['IDX_VEHICULE']                : null;
    $this->dateDebut  = !empty($data['STARTDATEINDISPONIBILITE'])    ? $data['STARTDATEINDISPONIBILITE']    : null;
    $this->heureDebut = !empty($data['STARTTIMEINDISPONIBILITE'])    ? $data['STARTTIMEINDISPONIBILITE']    : null;
    $this->dateFin    = !empty($data['ENDDATEINDISPONIBILITE'])      ? $data['ENDDATEINDISPONIBILITE']      : null;
    $this->heureFin   = !empty($data['ENDTIMEINDISPONIBILITE'])      ? $data['ENDTIMEINDISPONIBILITE']      : null;
    $this->jourEntier = !empty($data['ALLDAYINDISPONIBILITE'])       ? $data['ALLDAYINDISPONIBILITE']       : false;
  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_INDISPONIBILITEVEHICULE' => $this->id,
        'IDX_VEHICULE'                => $this->idVehicule,
        'STARTDATEINDISPONIBILITE'    => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'    => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'      => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'      => $this->heureFin,
        'ALLDAYINDISPONIBILITE'       => (bool) $this->jourEntier,
      ];
    } else {
  
      return [
        'IDX_VEHICULE'             => $this->idVehicule,
        'STARTDATEINDISPONIBILITE' => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE' => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'   => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'   => $this->heureFin,
        'ALLDAYINDISPONIBILITE'    => (bool) $this->jourEntier,
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
    
    //STARTTIMEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'STARTTIMEINDISPONIBILITE',
      'required'   => true,
      'validators' => [
        [
          'name'    => DateTime::class,
          'options' => [
            'pattern' => 'HH:mm:ss',
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
      'required'   => true,
      'validators' => [
        [
          'name'    => DateTime::class,
          'options' => [
            'pattern' => 'HH:mm:ss',
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
    
  //idVehicule
  public function getIdVehicule() 
  {
    
    return $this->idVehicule;
  }

  //
  public function setIdVehicule($id) 
  {
    
    $this->idVehicule = $id;
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