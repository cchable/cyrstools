<?php
/**
 * @package   : module/PlanningBus/src/Model/AnneeScolaire.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.
 
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


/*
 * 
 */
class AnneeScolaire implements InputFilterAwareInterface
{
  
  private $id;
  private $anneeScolaire;

  private $inputFilter;

  //
  public function exchangeArray(array $data)
  {

    $this->id            = !empty($data['IDX_ANNEESCOLAIRE'])  ? $data['IDX_ANNEESCOLAIRE'] : null;
    $this->anneeScolaire = !empty($data['ANNEEANNEESCOLAIRE']) ? $data['ANNEEANNEESCOLAIRE'] : null;
  }
  
  //
  public function getArrayCopy()
  {

    return [
      'IDX_ANNEESCOLAIRE'  => $this->id,
      'ANNEEANNEESCOLAIRE' => $this->anneeScolaire,
    ];
  }    
  
  //
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
/*
    $inputFilter->add([
      'name' => 'IDX_ANNEESCOLAIRE',
      'required' => true,
      'filters' => [
        ['name' => ToInt::class],
      ],
    ]);
*/
    $inputFilter->add([
      'name' => 'ANNEEANNEESCOLAIRE',
      'required' => true,
      'filters' => [
        ['name' => ToInt::class],
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