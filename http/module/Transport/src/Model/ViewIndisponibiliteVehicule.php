<?php
/**
 * This is the ViewVehicule class for ViewIndisponibiliteVehiculeTableGateway service.
 * 
 * @package   module/Transport/src/Model/ViewIndisponibiliteVehicule.php
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


/*
 * 
 */
class ViewIndisponibiliteVehicule implements InputFilterAwareInterface
{
   
  private $id;
  private $idVehicule;
  private $nomVehicule;
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
      $this->id        = !empty($data['IDX_INDISPONIBILITEVEHICULE']) ? $data['IDX_INDISPONIBILITEVEHICULE'] : null;
    }
    $this->idVehicule  = !empty($data['IDX_VEHICULE'])                ? $data['IDX_VEHICULE']                : null;
    $this->nomVehicule = !empty($data['NOMVEHICULE'])                 ? $data['NOMVEHICULE']                 : null;
    $this->dateDebut   = !empty($data['STARTDATEINDISPONIBILITE'])    ? $data['STARTDATEINDISPONIBILITE']    : null;
    $this->heureDebut  = !empty($data['STARTTIMEINDISPONIBILITE'])    ? $data['STARTTIMEINDISPONIBILITE']    : null;
    $this->dateFin     = !empty($data['ENDDATEINDISPONIBILITE'])      ? $data['ENDDATEINDISPONIBILITE']      : null;
    $this->heureFin    = !empty($data['ENDTIMEINDISPONIBILITE'])      ? $data['ENDTIMEINDISPONIBILITE']      : null;
    $this->jourEntier  = !empty($data['ALLDAYINDISPONIBILITE'])       ? $data['ALLDAYINDISPONIBILITE']       : false;
  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_INDISPONIBILITEVEHICULE' => $this->id,
        'IDX_VEHICULE'                => $this->idVehicule,
        'NOMVEHICULE'                 => $this->nomVehicule,
        'STARTDATEINDISPONIBILITE'    => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE'    => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'      => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'      => $this->heureFin,
        'ALLDAYINDISPONIBILITE'       => (bool) $this->jourEntier,
      ];
    } else {
  
      return [
        'IDX_VEHICULE'             => $this->idVehicule,
        'NOMVEHICULE'              => $this->nomVehicule,
        'STARTDATEINDISPONIBILITE' => $this->dateDebut,
        'STARTTIMEINDISPONIBILITE' => $this->heureDebut,
        'ENDDATEINDISPONIBILITE'   => $this->dateFin,
        'ENDTIMEINDISPONIBILITE'   => $this->heureFin,
        'ALLDAYINDISPONIBILITE'    => (bool) $this->jourEntier,
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
    
  //idVehicule
  public function getIdVehicule() 
  {
    
    return $this->idVehicule;
  }

  //
  public function setIdVehicule($id) 
  {
    
    $this->idVehicule = $id;
  }
  
  //nomVehicule
  public function getNomVehicule() 
  {
    
    return $this->nomVehicule;
  }

  //
  public function setNomVehicule($nomVehicule) 
  {
    
    $this->nomVehicule = $nomVehicule;
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