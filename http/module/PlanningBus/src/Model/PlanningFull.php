<?php
/**
 * @package   : module/PlanningBus/src/Model/PlanningFull.php
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
class PlanningFull implements InputFilterAwareInterface
{
	
  protected $id;
  protected $idType;
  protected $nom;
  protected $idDate;
  protected $date;
  protected $code;
  protected $idHeure;
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
		
    $this->id      = !empty($data['IDX_PLANNING'])            ? $data['IDX_PLANNING'] : null;
    $this->idType  = !empty($data['IDX_TYPEPLANNING'])        ? $data['IDX_TYPEPLANNING'] : null;
    $this->nom     = !empty($data['NOMTYPEPLANNING'])         ? $data['NOMTYPEPLANNING'] : null;
    $this->idDate  = !empty($data['IDX_DATEPLANNING'])        ? $data['IDX_DATEPLANNING'] : null;
    $this->date    = !empty($data['DATEDATEPLANNING'])        ? $data['DATEDATEPLANNING'] : null;
    $this->code    = !empty($data['CODESEMAINEDATEPLANNING']) ? $data['CODESEMAINEDATEPLANNING'] : null;
    $this->idHeure = !empty($data['IDX_HEUREPLANNING'])       ? $data['IDX_TYPEPLANNING'] : null;
    $this->heure   = !empty($data['HEUREHEUREPLANNING'])      ? substr($data['HEUREHEUREPLANNING'], 0, 5) : null;
  }

  //
	public function getArrayCopy()
  {
		
    return [
      'IDX_PLANNING'            => $this->id,
      'IDX_TYPEPLANNING'        => $this->idType,
      'NOMTYPEPLANNING'         => $this->nom,
      'IDX_DATEPLANNING'        => $this->idDate,
      'DATEDATEPLANNING'        => $this->date,
      'CODESEMAINEDATEPLANNING' => $this->code,
      'IDX_HEUREPLANNING'       => $this->idDate,
      'HEUREHEUREPLANNING'      => $this->heure,
    ];
  }

	//
  public function getId() 
  {
    
		return $this->id;
  }

  //
  public function getIdTypePlanning() 
  {
    
		return $this->idType;
  }
  
	//
  public function getNom() 
  {
    
		return $this->nom;
  }
  
  //
  public function getIdDatePlanning() 
  {
    
		return $this->idDate;
  }
	
  //
  public function getDate() 
  {
    
		return $this->date;
  }
  
  //
  public function getCodeSemaine() 
  {
    
		return $this->code;
  }
	
  //
  public function getIdHeurePlanning() 
  {
    
		return $this->idHeure;
  }
  
  //
  public function getHeure() 
  {
    
    return $this->heure;
  }
}