<?php
/**
 * @package   : module/Transport/src/Model/ViewIndisponibiliteChauffeur.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Model;

use DomainException;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;


/*
 * 
 */
class ViewIndisponibiliteChauffeur implements InputFilterAwareInterface
{
   
  private $id;
  private $idChauffeur;
  private $prenomChauffeur;
  private $dateDebut;
  private $heureDebut;
  private $dateFin;
  private $heureFin;
  private $jourEntier;

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
  }  
  
  //
  public function exchangeArray(array $data, bool $bIdx=true)
  {
    
    if ($bIdx) {
      $this->id            = !empty($data['IDX_INDISPONIBILITECHAUFFEUR']) ? $data['IDX_INDISPONIBILITECHAUFFEUR'] : null;
    }
    $this->idChauffeur     = !empty($data['IDX_CHAUFFEUR'])                ? $data['IDX_CHAUFFEUR']                : null;
    $this->prenomChauffeur = !empty($data['PRENOMCHAUFFEUR'])              ? $data['PRENOMCHAUFFEUR']              : null;
    $this->dateDebut       = !empty($data['STARTDATEINDISPONIBILITE'])     ? $data['STARTDATEINDISPONIBILITE']     : null;
    $this->heureDebut      = !empty($data['STARTTIMEINDISPONIBILITE'])     ? $data['STARTTIMEINDISPONIBILITE']     : null;
    $this->dateFin         = !empty($data['ENDDATEINDISPONIBILITE'])       ? $data['ENDDATEINDISPONIBILITE']       : null;
    $this->heureFin        = !empty($data['ENDTIMEINDISPONIBILITE'])       ? $data['ENDTIMEINDISPONIBILITE']       : null;
    $this->jourEntier      = !empty($data['ALLDAYINDISPONIBILITE'])        ? $data['ALLDAYINDISPONIBILITE']        : false;
  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_INDISPONIBILITECHAUFFEUR' => $this->id,
        'IDX_CHAUFFEUR'                => $this->idChauffeur,
        'PRENOMCHAUFFEUR'              => $this->prenomChauffeur,
        'STARTDATEINDISPONIBILITE'     => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'     => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'       => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'       => $this->heureFin,
        'ALLDAYINDISPONIBILITE'        => (bool) $this->jourEntier,
      ];
    } else {
  
      return [
        'IDX_CHAUFFEUR'             => $this->idChauffeur,
        'PRENOMCHAUFFEUR'           => $this->prenomChauffeur,
        'STARTDATEINDISPONIBILITE'  => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'  => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'    => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'    => $this->heureFin,
        'ALLDAYINDISPONIBILITE'     => (bool) $this->jourEntier,
      ];
    }
  }
  
  // Setter
  //id
  public function getId() 
  {

    return $this->id;
  }

  //
  public function setId($id) 
  {
  
    $this->id = $id;
  }
    
  //idChauffeur
  public function getIdChauffeur() 
  {
    
    return $this->idChauffeur;
  }

  //
  public function setIdChauffeur($id) 
  {
    
    $this->idChauffeur = $id;
  }
  
  //prenomChauffeur
  public function getPrenomChauffeur() 
  {
    
    return $this->prenomChauffeur;
  }

  //
  public function setPrenomChauffeur($prenomChauffeur) 
  {
    
    $this->prenomChauffeur = $prenomChauffeur;
  }

  //dateDebut
  public function getDateDebut() 
  {
    
    return $this->dateDebut;
  }

  //
  public function setDateDebut($dateDebut) 
  {
    
    $this->dateDebut = $dateDebut;
  }
  
  //dateFin
  public function getDateFin() 
  {
    
    return $this->dateFin;
  }

  //
  public function setDateFin($dateFin) 
  {
    
    $this->dateFin = $dateFin;
  }

  //heureDebut
  public function getHeureDebut() 
  {
    
    return $this->heureDebut;
  }

  //
  public function setHeureDebut($heureDebut) 
  {
    
    $this->heureDebut = $heureDebut;
  }
  
  //heureFin
  public function getHeureFin() 
  {
    
    return $this->heureFin;
  }

  //
  public function setHeureFin($heureFin) 
  {
    
    $this->heureFin = $heureFin;
  }
  
  //jourEntier
  public function getJourEntier() 
  {
    
    return $this->jourEntier;
  }

  //
  public function setJourEntier(bool $jourEntier) 
  {
    
    $this->jourEntier = $jourEntier;
  }
}