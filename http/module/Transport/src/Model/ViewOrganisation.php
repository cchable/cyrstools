<?php
/**
 * This is the ViewOrganisation class for ViewOrganisation service.
 *
 * @package   module/Transport/src/Model/ViewOrganisation.php
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
class ViewOrganisation implements InputFilterAwareInterface
{
  
  private $id;
  private $idGroupe;
  private $nom;
  private $date;
  private $heure;
  

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
      $this->id     = !empty($data['IDX_ORGANISATION']) ? $data['IDX_ORGANISATION'] : null;
    }

    $this->idGroupe = !empty($data['IDX_GROUPE'])       ? $data['IDX_GROUPE']       : null;
    $this->nom      = !empty($data['NOMGROUPE'])        ? $data['NOMGROUPE']        : null;
    $this->date     = !empty($data['DATEORGANISATION']) ? $data['DATEORGANISATION'] : null;
    $this->heure    = !empty($data['TIMEORGANISATION']) ? $data['TIMEORGANISATION'] : null;
  }
  
  /**
   *
   */
  public function getArrayCopy(bool $bIdx=true)
  {

    $result = [
      'IDX_GROUPE'       => $this->idGroupe,
      'NOMGROUPE'        => $this->nom,
      'DATEORGANISATION' => $this->date,
      'TIMEORGANISATION' => $this->heure,
    ];

    if ($bIdx) $result['IDX_ORGANISATION'] = $this->id;

    return $result;
  }
  
  /**
   * Setter
   */
  
  //IDX_TRAJET
  public function getId()
  {

    return (int) $this->id;
  }

  //IDX_GROUPE
  public function getIdgroupe()
  {

    return (int) $this->idGroupe;
  }

  //NOMGROUPE
  public function getNom()
  {

    return $this->nom;
  }
  
  //DATEORGANISATION
  public function getDate()
  {
    
    return $this->date;
  }

  //TIMEORGANISATION
  public function getHeure()
  {
    
    return $this->heure;
  }
}  