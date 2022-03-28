<?php
/**
 * @package   : module/PlanningBus/src/Model/TrajetFull.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Model;

use DomainException;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;


/*
 * 
 */
class TrajetFull implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idEtapeDepart;
  protected $nomEtapeDepart;
  protected $idEtapeArrivee;
  protected $nomEtapeArrivee;
  protected $nom;
  protected $temps;
  protected $km;

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
		
    $this->id              = !empty($data['IDX_TRAJET'])       ? $data['IDX_TRAJET'] : null;
    $this->idEtapeDepart   = !empty($data['IDX_ETAPEDEPART'])  ? $data['IDX_ETAPEDEPART'] : null;
    $this->nomEtapeDepart  = !empty($data['NOMETAPEDEPART'])   ? $data['NOMETAPEDEPART'] : null;
    $this->idEtapeArrivee  = !empty($data['IDX_ETAPEARRIVEE']) ? $data['IDX_ETAPEARRIVEE'] : null;
    $this->nomEtapeArrivee = !empty($data['NOMETAPEARRIVEE'])  ? $data['NOMETAPEARRIVEE'] : null;
    $this->nom             = !empty($data['NOMTRAJET'])        ? $data['NOMTRAJET'] : null;
    $this->temps           = !empty($data['TEMPSTRAJET'])      ? substr($data['TEMPSTRAJET'], 0, 5) : null;
    $this->km              = !empty($data['KMTRAJET'])         ? number_format($data['KMTRAJET'], 1, ',', ' ') : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_TRAJET'       => $this->id,
      'IDX_ETAPEDEPART'  => $this->idEtapeDepart,
      'NOMETAPEDEPART'   => $this->nomEtapeDepart,
      'IDX_ETAPEARRIVEE' => $this->idEtapeArrivee,
      'NOMETAPEARRIVEE'  => $this->nomEtapeArrivee,
      'NOMTRAJET'        => $this->nom,
      'TEMPSTRAJET'      => $this->temps,
      'KMTRAJET'         => $this->km,
    ];
  }

	//
  public function getId() 
  {
    
		return (int) $this->id;
  }

	//
  public function getNomEtapeDepart() 
  {
    
		return $this->nomEtapeDepart;
  }
  
  //
  public function getIdEtapeDepart() 
  {
    
		return (int) $this->idEtapeDepart;
  }
	
  //
  public function getNomEtapeArrivee() 
  {
    
		return $this->nomEtapeArrivee;
  }
  
  //
  public function getIdEtapeArrivee() 
  {
    
		return (int) $this->idEtapeArrivee;
  }
	   
  //
  public function getNom()
  {
    
    return $this->nom;
  }

  //
  public function getTemps() 
  {
    
    return $this->temps;
  }
  
  //
  public function getKm()
  {
    
    return $this->km;
  } 
}