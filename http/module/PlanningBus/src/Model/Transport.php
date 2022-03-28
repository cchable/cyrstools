<?php
/**
 * @package   : module/PlanningBus/src/Model/Transport.php
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
class Transport implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idAnneeScolaire;
  protected $idPlanning;
  protected $idVehicule;
  protected $idChauffeur;
  protected $idGroupe;
  protected $idTrajet;

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
		
    $this->id              = !empty($data['IDX_TRANSPORT'])     ? $data['IDX_TRANSPORT'] : null;
    $this->idAnneeScolaire = !empty($data['IDX_ANNEESCOLAIRE']) ? $data['IDX_ANNEESCOLAIRE'] : null;
    $this->idPlanning      = !empty($data['IDX_PLANNING'])      ? $data['IDX_PLANNING'] : null;
    $this->idVehicule      = !empty($data['IDX_VEHICULE'])      ? $data['IDX_VEHICULE'] : null;
    $this->idChauffeur     = !empty($data['IDX_CHAUFFEUR'])     ? $data['IDX_CHAUFFEUR'] : null;
    $this->idGroupe        = !empty($data['IDX_GROUPE'])        ? $data['IDX_GROUPE'] : null;
    $this->idTrajet        = !empty($data['IDX_TRAJET'])        ? $data['IDX_TRAJET'] : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_TRANSPORT'     => $this->id,
      'IDX_ANNEESCOLAIRE' => $this->idAnneeScolaire,
      'IDX_PLANNING'      => $this->idPlanning,
      'IDX_VEHICULE'      => $this->idVehicule,
      'IDX_CHAUFFEUR'     => $this->idChauffeur,
      'IDX_GROUPE'        => $this->idGroupe,
      'IDX_TRAJET'        => $this->idTrajet,
    ];
  }

	//
  public function getId() 
  {
    
		return (int) $this->id;
  }

  public function setId($id) 
  {
    
    $this->id = $id;
  }
  
  //
  public function getIdAnneeScolaire() 
  {
    
		return (int) $this->idAnneeScolaire;
  }

  public function setIdAnneeScolaire($idAnneeScolaire) 
  {
    
    $this->idAnneeScolaire = $AnneeScolaire;
  }
  
  //
  public function getIdPlanning() 
  {
    
		return (int) $this->idPlanning;
  }

  public function setIdPlanning($idPlanning) 
  {
    
    $this->idPlanning = $idPlanning;
  }
  
  //
  public function getIdVehicule() 
  {
    
		return (int) $this->idVehicule;
  }

  public function setIdVehicule($idVehicule) 
  {
    
    $this->idVehicule = $idVehicule;
  }
  
  //
  public function getIdChauffeur() 
  {
    
		return (int) $this->idChauffeur;
  }

  public function setIdChauffeur($idChauffeur) 
  {
    
    $this->idChauffeur = $idChauffeur;
  }
  
  //
  public function getIdGroupe() 
  {
    
		return (int) $this->idGroupe;
  }

  public function setIdGroupe($idGroupe) 
  {
    
    $this->idGroupe = $idGroupe;
  }
  
  //
  public function getIdTrajet() 
  {
    
		return (int) $this->idTrajet;
  }

  public function setIdTrajet($idTrajet) 
  {
    
    $this->idTrajet = $idTrajet;
  }
}