<?php
/**
 * This is the Trajet class for Trajet service.
 * 
 * @package   module/Transport/src/Model/Trajet.php
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
class Trajet implements InputFilterAwareInterface
{
  
  private $id;
  private $idEtapeDepart;
  private $idEtapeArrivee;
  private $nom;
  private $temps;
  private $km;


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
      $this->id           = !empty($data['IDX_TRAJET'])       ? $data['IDX_TRAJET']       : null;
    }

    $this->idEtapeDepart  = !empty($data['IDX_ETAPEDEPART'])  ? $data['IDX_ETAPEDEPART']  : null;
    $this->idEtapeArrivee = !empty($data['IDX_ETAPEARRIVEE']) ? $data['IDX_ETAPEARRIVEE'] : null;
    $this->nom            = !empty($data['NOMTRAJET'])        ? $data['NOMTRAJET']        : null;
    $this->temps          = !empty($data['TEMPSTRAJET'])      ? $data['TEMPSTRAJET']      : null;
    $this->km             = !empty($data['KMTRAJET'])         ? $data['KMTRAJET']         : null;
  }
  
  /**
   *
   */
  public function getArrayCopy(bool $bIdx=true)
  {

    $result = [
      'IDX_ETAPEDEPART'  => $this->idEtapeDepart,
      'IDX_ETAPEARRIVEE' => $this->idEtapeArrivee,
      'NOMTRAJET'        => $this->nom,
      'TEMPSTRAJET'      => $this->temps,
      'KMTRAJET'         => $this->km,
    ];

    if ($bIdx) $result['IDX_TRAJET'] = $this->id;

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

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
  
  //IDX_ETAPEDEPART
  public function getIdEtapeDepart() 
  {

    return (int) $this->idEtapeDepart;
  }

  //
  public function setIdEtapeDepart($idEtapeDepart) 
  {
  
    $this->idEtapeDepart = $idEtapeDepart;
  }

  //IDX_ETAPEARRIVEE
  public function getIdEtapeArrivee() 
  {

    return (int) $this->idEtapeArrivee;
  }

  //
  public function setIddEtapeArrivee($idEtapeArrivee) 
  {
  
    $this->idEtapeArrivee = $idEtapeArrivee;
  }
  
  //NOMTRAJET
  public function getNom() 
  {
    
    return $this->nom;
  }

  //
  public function setNom($nom) 
  {
    
    $this->nom = $nom;
  } 
  
  //TEMPSTRAJET
  public function getTemps() 
  {
    
    return $this->temps;
  }

  //
  public function setTemps($temps) 
  {
    
    $this->temps = $temps;
  }
  
  //KMTRAJET
  public function getKm() 
  {
    
    return $this->km;
  }

  //
  public function setKm($km) 
  {
    
    $this->km = $km;
  }
}  