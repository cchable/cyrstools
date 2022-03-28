<?php
/**
 * @package   : module/PlanningBus/src/Form/EphemerideForm.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace PlanningBus\Form;

use DomainException;

use Laminas\Form\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;

use PlanningBus\Model\Ephemeride;


/*
 * 
 */
class EphemerideForm extends Form
{

  /*
   * Scenario ('create' or 'update').
   * @var string 
   */
  private $scenario;
  
  /*
   * 
   * @var 
   */
  private $haystackAnneesScolaires;


  /*
   * Constructor
   */
  public function __construct($scenario = 'create', array $haystackAnneesScolaires)
  {

    // Save parameters for internal use
    $this->scenario                = $scenario;
    $this->haystackAnneesScolaires = $haystackAnneesScolaires;
      
    // Define form name
    parent::__construct('ephemeride-form');
    
    // Set POST method for this form
    $this->setAttribute('method', 'post');
    
    $this->addElements();
    $this->addInputFilter();
  }
  
  /*
   * This method adds elements to form (input fields and submit button).
   */
  protected function addElements() 
  {
    // Add "idx annee scolaire" field
    $this->add([
      'name' => 'IDX_ANNEESCOLAIRE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackAnneesScolaires,
//        'value'         => ($this->scenario == 'update') ? $this->idAnneeScolaire : 0,
        'label'         => 'Année scolaire',
      ],
    ]);
    
    // Add "intitule" field
    $this->add([
      'name' => 'INTITULEEPHEMERIDE',
      'type' => 'text',
      'options' => [
        'label' => 'Intitulé',
      ],
    ]);
    
    // Add "datedebut" field
    $this->add([
      'name' => 'DATEDEBUTEPHEMERIDE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de début',
      ],
    ]);
    
    // Add "datefin" field
    $this->add([
      'name' => 'DATEFINEPHEMERIDE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
      ],
    ]);
        
    // Add the Submit button
    $this->add([
      'name' => 'submit',
      'type' => 'submit',
      'attributes' => [
        'value' => 'Sauver',
        'id'    => 'submit',
      ],
    ]);
    
    // Add the CSRF field
    $this->add([
      'name' => 'csrf',
      'type' => 'csrf',
      'options' => [
        'csrf_options' => [
          'timeout' => 600
        ]
      ],
    ]);
  }
  
  /*
   * This method creates input filter (used for form filtering/validation).
   */
  private function addInputFilter() 
  {
    
    // Create input filter
    $inputFilter = $this->getInputFilter();

    $ephemeride = new Ephemeride();
    $ephemeride->fillInputFilter($inputFilter);
  }  
  
  /*
   * This method creates input filter (used for form filtering/validation).
   */
  private function addInputFilter2() 
  {
    
    // Create input filter
    $inputFilter = $this->getInputFilter();

    // Add input for "idx anneescolaire" field
    $inputFilter->add([
      'name' => 'IDX_ANNEESCOLAIRE',
      'required' => true,
      'filters' => [
        ['name' => ToInt::class],
      ],
    ]);
    
    /*
    $inputFilter->add([
      'name'  => 'IDX_ANNEESCOLAIRE',
      //'class' => ArrayInput::class,
      'required' => true,
      'filters'  => [                    
        ['name' => 'ToInt'],
      ],                
      'validators' => [
        [
          'name'=>'GreaterThan', 
          'options' => [
            'min' => 0,
          ]
        ]
      ],
    ]); 
    */
    //
    $inputFilter->add([
      'name'       => 'DATEDEBUTEPHEMERIDE',
      'required'   => true,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => 'Date',
          'options' => [
            'format' => 'Y-m-d',
          ],
        ],
      ],
    ]);
    
    //
    $inputFilter->add([
      'name'       => 'DATEFINEPHEMERIDE',
      'required'   => true,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => 'Date',
          'options' => [
            'format' => 'Y-m-d',
          ],
        ], 
      ],
    ]);    
    
    // Add input for "intitule" field
    $inputFilter->add([
      'name'     => 'INTITULEEPHEMERIDE',
      'required' => true,
      'filters'  => [
        ['name' => StringTrim::class],
        ['name' => StripTags::class],        
      ],                
      'validators' => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
          ],
        ],
      ],
    ]);
  }
}
