<?php
/**
 * @package   : module/Transport/src/Model/AnneeScolaire.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\Filter\ToInt;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\I18n\Validator\IsInt;


/*
 * 
 */
class AnneeScolaire implements InputFilterAwareInterface
{
  
  private $id;
  private $anneeScolaire;

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
    
    if($bIdx){
      $this->id          = !empty($data['IDX_ANNEESCOLAIRE'])  ? $data['IDX_ANNEESCOLAIRE']  : null;
    }
    $this->anneeScolaire = !empty($data['ANNEEANNEESCOLAIRE']) ? $data['ANNEEANNEESCOLAIRE'] : null;

  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if($bIdx){
      
      return [
        'IDX_ANNEESCOLAIRE'  => $this->id,
        'ANNEEANNEESCOLAIRE' => $this->anneeScolaire,
      ];
    } else {
  
      return [
        'ANNEEANNEESCOLAIRE' => $this->anneeScolaire,
      ];
    }
  }
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    // ANNEEANNEESCOLAIRE
    $inputFilter->add([
      'name'           => 'ANNEEANNEESCOLAIRE',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'Années scolaire',
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
    
  // anneeScolaire
  public function getAnneeScolaire() 
  {
    
    return $this->anneeScolaire;
  }

  //
  public function setAnneeScolaire($anneeScolaire) 
  {
    
    $this->anneeScolaire = $anneeScolaire;
  }
}