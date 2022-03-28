<?php
/**
 * @package   : module/PlanningBus/src/Model/ChauffeurFull.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 
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
use Laminas\Validator\InArray;
use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\DateTime;


/*
 * 
 */
class ChauffeurFull implements InputFilterAwareInterface
{
	
  /*
   *
   */
  protected $id;
  
  /*
   *
   */
  protected $idVehicule;
  
  /*
   *
   */
  protected $prenom;
  
  /*
   *
   */
  protected $marque;
  
  /*
   *
   */
  protected $modele;
  
  /*
   *
   */
  protected $nomVehicule;
  
  /*
   *
   */
  protected $places;

  /*
   *
   */
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
    return $inputFilter;
  }
  
	//
  public function exchangeArray(array $data)
  {
		
    $this->id          = !empty($data['IDX_CHAUFFEUR'])   ? $data['IDX_CHAUFFEUR']   : null;
    $this->idVehicule  = !empty($data['IDX_VEHICULE'])    ? $data['IDX_VEHICULE']    : null;
    $this->prenom      = !empty($data['PRENOMCHAUFFEUR']) ? $data['PRENOMCHAUFFEUR'] : null;
    $this->marque      = !empty($data['MARQUEVEHICULE'])  ? $data['MARQUEVEHICULE']  : null;
    $this->modele      = !empty($data['MODELEVEHICULE'])  ? $data['MODELEVEHICULE']  : null;
    $this->nomVehicule = !empty($data['NOMVEHICULE'])     ? $data['NOMVEHICULE']     : null;
    $this->places      = !empty($data['PLACESVEHICULE'])  ? $data['PLACESVEHICULE']  : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_CHAUFFEUR'   => $this->id,
      'IDX_VEHICULE'    => $this->idVehicule,
      'PRENOMCHAUFFEUR' => $this->prenom,
      'MARQUEVEHICULE'  => $this->marque,
      'MODELEVEHICULE'  => $this->modele,
      'NOMVEHICULE'     => $this->nomVehicule,
      'PLACESVEHICULE'  => $this->places,
    ];
  }

	//
  public function getId() 
  {
    
		return (int) $this->id;
  }

	//
  public function getIdVehicule() 
  {
    
		return (int) $this->idVehicule;
  }
	
  //
  public function getPrenom() 
  {
    
		return $this->prenom;
  }
	
  //
  public function getMarque() 
  {
    
    return $this->marque;
  }

	//
  public function getModele() 
  {
    
		return $this->modele;
  }

	//
  public function getNomVehicule() 
  {
    
		return $this->nomVehicule;
  }

	//
  public function getPlaces() 
  {
    
		return $this->places;
  }
}