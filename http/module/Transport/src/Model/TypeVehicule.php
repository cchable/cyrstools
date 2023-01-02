<?php
/**
 * This is the TypeVehicule class for TypeVehiculeTableGateway service.
 * 
 * @package   module/Transport/src/Model/TypeVehicule.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;


/*
 * 
 */
class TypeVehicule implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;

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
      $this->id = !empty($data['IDX_TYPEVEHICULE']) ? $data['IDX_TYPEVEHICULE'] : null;
    }

    $this->nom  = !empty($data['NOMTYPEVEHICULE'])  ? $data['NOMTYPEVEHICULE']  : null;
  }
  
  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_TYPEVEHICULE' => $this->id,
        'NOMTYPEVEHICULE'  => $this->nom,
      ];
    } else {
  
      return [
        'NOMTYPEVEHICULE'  => $this->nom,
      ];
    }
  }
  
  //
  public function fillInputFilter(InputFilterInterface $inputFilter)
  {

    //
    $inputFilter->add([
      'name' => 'NOMTYPEVEHICULE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 20,
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
  public function getName() 
  {
    
    return $this->nom;
  }

  //
  public function setName($nom) 
  {
    
    $this->nom = $nom;
  }
}  