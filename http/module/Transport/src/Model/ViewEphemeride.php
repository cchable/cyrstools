<?php
/**
 * @package   : module/Transport/src/Model/ViewEphemeride.php
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
class ViewEphemeride implements InputFilterAwareInterface
{
   
  private $id;
  private $idAnneeScolaire;
  private $nomEphemeride;
  private $dateDebut;
  private $dateFin;
  private $anneeScolaire;

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
      $this->id            = !empty($data['IDX_EPHEMERIDE'])     ? $data['IDX_EPHEMERIDE']     : null;
    }
    $this->idAnneeScolaire = !empty($data['IDX_ANNEESCOLAIRE'])  ? $data['IDX_ANNEESCOLAIRE']  : null;
    $this->nomEphemeride   = !empty($data['NOMEPHEMERIDE'])      ? $data['NOMEPHEMERIDE']      : null;
    $this->dateDebut       = !empty($data['STARTDATEPHEMERIDE']) ? $data['STARTDATEPHEMERIDE'] : null;
    $this->dateFin         = !empty($data['ENDDATEPHEMERIDE'])   ? $data['ENDDATEPHEMERIDE']   : null;
    $this->anneeScolaire   = !empty($data['ANNEEANNEESCOLAIRE']) ? $data['ANNEEANNEESCOLAIRE']   : null;

  }

  //
  public function getArrayCopy(bool $bIdx=true)
  {
    
    if ($bIdx) {
      
      return [
        'IDX_EPHEMERIDE'     => $this->id,
        'IDX_ANNEESCOLAIRE'  => $this->idAnneeScolaire,
        'NOMEPHEMERIDE'      => $this->nomEphemeride,
        'STARTDATEPHEMERIDE' => $this->dateDebut,
        'ENDDATEPHEMERIDE'   => $this->dateFin,
        'ANNEEANNEESCOLAIRE' => $this->anneeScolaire 
      ];
    } else {
  
      return [
        'IDX_ANNEESCOLAIRE'  => $this->idAnneeScolaire,
        'NOMEPHEMERIDE'      => $this->nomEphemeride,
        'STARTDATEPHEMERIDE' => $this->dateDebut,
        'ENDDATEPHEMERIDE'   => $this->dateFin,
        'ANNEEANNEESCOLAIRE' => $this->anneeScolaire 
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
    
  //idAnneeScolaire
  public function getIdAnneeScolaire() 
  {
    
    return $this->idAnneeScolaire;
  }

  //
  public function setIdAnneeScolaire($id) 
  {
    
    $this->idAnneeScolaire = $id;
  }
  
  //anneeScolaire
  public function getAnneeScolaire() 
  {
    
    return $this->anneeScolaire;
  }

  //
  public function setAnneeScolaire($anneeScolaire) 
  {
    
    $this->anneeScolaire = $anneeScolaire;
  }
    
  
  //nomEpehmeride
  public function getNomEphemeride() 
  {
    
    return $this->nomEphemeride;
  }

  //
  public function setNomEphemeride($nomEphemeride) 
  {
    
    $this->nomEphemeride = $nomEphemeride;
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
}