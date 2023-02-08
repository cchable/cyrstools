<?php
/**
 * This is the Groupe class for Groupe service.
 * 
 * @package   module/Transport/src/Model/Groupe.php
 * @version   1.0
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
class Groupe implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;
  private $accompagnateur;
  private $nombre;


  /**
   *
   */
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

  /**
   *
   */
  public function exchangeArray(array $data, bool $bIdx=true)
  {
 
    if ($bIdx) {
      $this->id           = !empty($data['IDX_GROUPE'])                 ? $data['IDX_GROUPE']                 : null;
    }

    $this->nom            = !empty($data['NOMGROUPE'])                  ? $data['NOMGROUPE']                  : null;
    $this->accompagnateur = !empty($data['NOMBREACCOMPAGNATEURGROUPE']) ? $data['NOMBREACCOMPAGNATEURGROUPE'] : null;
    $this->nombre         = !empty($data['NOMBREGROUPE'])               ? $data['NOMBREGROUPE']               : null;
  }
  
  /**
   *
   */
  public function getArrayCopy(bool $bIdx=true)
  {

    $result = [
      'NOMGROUPE'                  => $this->nom,
      'NOMBREACCOMPAGNATEURGROUPE' => $this->accompagnateur,
      'NOMBREGROUPE'               => $this->nombre,
    ];

    if ($bIdx) $result['IDX_GROUPE'] = $this->id;

    return $result;
  }    
  
  /**
   * Setter
   */
  //IDX_GROUPE
  public function getId() 
  {

    return (int) $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
  
  //NOMGROUPE
  public function getNom() 
  {

    return $this->nom;
  }

  //
  public function setNom($nom) 
  {
  
    $this->nom = $nom;
  }

  //NOMBREACCOMPAGNATEURGROUPE
  public function getAccompagnateur() 
  {

    return (int) $this->accompagnateur;
  }

  //
  public function setAccompagnateur($accompagnateur) 
  {
  
    $this->accompagnateur = $accompagnateur;
  }
  
  //NOMBREGROUPE
  public function getNombre() 
  {
    
    return $this->nombre;
  }

  //
  public function setNombre($nombre) 
  {
    
    $this->nombre = $nombre;
  }
}  