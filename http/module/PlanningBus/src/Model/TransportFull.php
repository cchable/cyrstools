<?php
/**
 * @package   : module/PlanningBus/src/Model/TransportFull.php
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
class TransportFull implements InputFilterAwareInterface
{
	
  protected $id;
  protected $date;
  protected $chauffeur;
  protected $vehicule;
  protected $trajet;
  protected $heure;

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
		
    $this->id        = !empty($data['IDX_TRANSPORT'])      ? $data['IDX_TRANSPORT'] : null;
    $this->date      = !empty($data['DATEDATEPLANNING'])   ? $data['DATEDATEPLANNING'] : null;
    $this->heure     = !empty($data['HEUREHEUREPLANNING']) ? $data['HEUREHEUREPLANNING'] : null;
    $this->chauffeur = !empty($data['PRENOMCHAUFFEUR'])    ? $data['PRENOMCHAUFFEUR'] : null;
    $this->vehicule  = !empty($data['NOMVEHICULE'])        ? $data['NOMVEHICULE'] : null;
    $this->trajet    = !empty($data['NOMTRAJET'])          ? $data['NOMTRAJET'] : null;
    $this->groupe    = !empty($data['NOMGROUPE'])          ? $data['NOMGROUPE'] : null;
    $this->nombre    = !empty($data['NOMBREGROUPE'])       ? $data['NOMBREGROUPE'] : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_TRANSPORT'      => $this->id,
      'DATEDATEPLANNING'   => $this->date,
      'HEUREHEUREPLANNING' => $this->heure,
      'PRENOMCHAUFFEUR'    => $this->chauffeur,
      'NOMVEHICULE'        => $this->vehicule,
      'NOMTRAJET'          => $this->trajet,
      'NOMGROUPE'          => $this->groupe,
      'NOMBREGROUPE'       => $this->nombre,
    ];
  }

	//
  public function getId() 
  {
    
		return (int) $this->id;
  }

	//
  public function getDate() 
  {
    
		return $this->date;
  }
  
  //
  public function getHeure() 
  {
    
		return $this->heure;
  }
  
  //
  public function getChauffeur() 
  {
    
		return $this->chauffeur;
  }
  
  //
  public function getVehicule() 
  {
    
		return $this->vehicule;
  }
  
  //
  public function getTrajet() 
  {
    
		return $this->trajet;
  }
  
  //
  public function getGroupe() 
  {
    
		return $this->groupe;
  } 
  
  //
  public function getNombre() 
  {
    
		return $this->nombre;
  }
  
}