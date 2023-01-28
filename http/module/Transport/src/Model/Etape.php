<?php
/**
 * This is the Etape class for Etape service.
 * 
 * @package   module/Transport/src/Model/Etape.php
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
class Etape implements InputFilterAwareInterface
{
  
  private $id;
  private $nom;
  private $adresse;
  private $printed;
  private $latitude;
  private $longitude;
  private $altitude;


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
      $this->id    = !empty($data['IDX_ETAPE'])        ? $data['IDX_ETAPE']       : null;
    }

    $this->nom       = !empty($data['NOMETAPE'])       ? $data['NOMETAPE']       : null;
    $this->adresse   = !empty($data['ADRESSEETAPE'])   ? $data['ADRESSEETAPE']   : null;
    $this->printed   = !empty($data['PRINTEDETAPE'])   ? $data['PRINTEDETAPE']   : (BOOL) false;
    $this->latitude  = !empty($data['LATITUDEETAPE'])  ? $data['LATITUDEETAPE']  : null;
    $this->longitude = !empty($data['LONGITUDEETAPE']) ? $data['LONGITUDEETAPE'] : null;
    $this->altitude  = !empty($data['ALTITUDEETAPE'])  ? $data['ALTITUDEETAPE']  : null;
  }
  
  //
  public function getArrayCopy(bool $bIdx=true)
  {

    $result = [
      'NOMETAPE'       => $this->nom,
      'ADRESSEETAPE'   => $this->adresse,
      'PRINTEDETAPE'   => (BOOL) $this->printed,
      'LATITUDEETAPE'  => $this->latitude,
      'LONGITUDEETAPE' => $this->longitude,
      'ALTITUDEETAPE'  => $this->altitude,
    ];

    if ($bIdx) $result['IDX_ETAPE'] = $this->id;

    return $result;
  }    
  
  /**
   * Setter
   */
  //IDX_ETAPE
  public function getId() 
  {

    return (int) $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
  
  //PRINTEDETAPE
  public function getPrinted() 
  {
    
    return $this->printed;
  }

  //
  public function setPrinted($printed) 
  {
    
    $this->printed = $printed;
  } 
  
  //NOMETAPE
  public function getNom() 
  {
    
    return $this->nom;
  }

  //
  public function setNom($nom) 
  {
    
    $this->nom = $nom;
  }
  
  //ADRESSETAPE
  public function getAdresse() 
  {
    
    return $this->adresse;
  }

  //
  public function setAdresse($adresse) 
  {
    
    $this->adresse = $adresse;
  }
  
  //LATITUDEETAPE
  public function getLatitude() 
  {
    
    return $this->latitude;
  }

  //
  public function setLatitude($latitude) 
  {
    
    $this->latitude = $latitude;
  }
  
  //LONGITUDEETAPE
  public function getLongitude() 
  {
    
    return $this->longitude;
  }

  //
  public function setLongitude($longitude) 
  {
    
    $this->longitude = $longitude;
  }
  
  //ALTITUDEETAPE
  public function getAltitude()
  {
    
    return $this->altitude;
  }

  //
  public function setAltitude($altitude)
  {
    
    $this->altitude = $altitude;
  }
}  