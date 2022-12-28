<?php
/**
 * This is the Ephemeride class for EphemerideTableGateway service.
 * 
 * @package   module/Transport/src/Model/Ephemeride.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;

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
class Ephemeride implements InputFilterAwareInterface
{
   
  private $id;
  private $idAnneeScolaire;
  private $dateDebut;
  private $dateFin;

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
      $this->id            = !empty($data['IDX_EPHEMERIDE'])     ? $data['IDX_EPHEMERIDE']     : null;
    }
    
    $this->idAnneeScolaire = !empty($data['IDX_ANNEESCOLAIRE'])  ? $data['IDX_ANNEESCOLAIRE']  : null;
    $this->nomEphemeride   = !empty($data['NOMEPHEMERIDE'])      ? $data['NOMEPHEMERIDE']      : null;
    $this->dateDebut       = !empty($data['STARTDATEPHEMERIDE']) ? $data['STARTDATEPHEMERIDE'] : null;
    $this->dateFin         = !empty($data['ENDDATEPHEMERIDE'])   ? $data['ENDDATEPHEMERIDE']   : null;
  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_EPHEMERIDE'     => $this->id,
        'IDX_ANNEESCOLAIRE'  => $this->idAnneeScolaire,
        'NOMEPHEMERIDE'      => $this->nomEphemeride,
        'STARTDATEPHEMERIDE' => $this->dateDebut,
        'ENDDATEPHEMERIDE'   => $this->dateFin,
      ];
    } else {
  
      return [
        'IDX_ANNEESCOLAIRE'  => $this->idAnneeScolaire,
        'NOMEPHEMERIDE'      => $this->nomEphemeride,
        'STARTDATEPHEMERIDE' => $this->dateDebut,
        'ENDDATEPHEMERIDE'   => $this->dateFin,
      ];
    }
  }
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    // ANNEEANNEESCOLAIRE
    $inputFilter->add([
      'name'           => 'IDX_ANNEESCOLAIRE',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'ID Années scolaire',
      'fallback_value' => 1,

      'validators'     => [
        [
          'name' => IsInt::class,
        ],
      ],
      
      'filters'        => [
        [
          'name' => ToInt::class,
        ],
      ],
    ]);
    
    // NOMEPHEMERIDE
    $inputFilter->add([
      'name' => 'NOMEPHEMERIDE',
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
    
    //STARTDATEINDISPONIBILITE
    $inputFilter->add([
      'name'       => 'STARTDATEPHEMERIDE',
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
      'name'       => 'ENDDATEPHEMERIDE',
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
    
  //idAnneeScolaire
  public function getIdAnneeScolaire() 
  {
    
    return $this->idAnneeScolaire;
  }

  //
  public function setIdAnneeScolaire($id) 
  {
    
    $this->idAnneeScolaire = $id;
  }
    
  //nomEpehmeride
  public function getNomEphemeride() 
  {
    
    return $this->nomEphemeride;
  }

  //
  public function setNomEphemeride($nomEphemeride) 
  {
    
    $this->nomEphemeride = $nomEphemeride;
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
}
