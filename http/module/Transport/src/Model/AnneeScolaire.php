<?php
/**
 * This is the Vehicule class for AnneeScolaire service.
 * 
 * @package   module/Transport/src/Model/AnneeScolaire.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;


/**
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

  /**
   * 
   * @return InputFilter
   */
  public function getInputFilter()
  {
		
    if ($this->inputFilter) {
      return $this->inputFilter;
    }

    $inputFilter = new InputFilter();
    
    return $this->inputFilter;
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