<?php
/**
 * @package   : module/Transport/src/Form/ChauffeurForm.php
 *
 * @purpose   : 
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Transport\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Form\Element\Time;
use Laminas\Form\Element\Checkbox;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;

use Transport\Model\IndisponibiliteChauffeur;


/*
 * 
 */
class IndisponibiliteChauffeurForm extends Form
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
  private $haystackChauffeurs;

  /*
   * Constructor
   */
  public function __construct(array $haystackChauffeurs)
  {

    // Save parameters for internal use
    $this->haystackChauffeurs = $haystackChauffeurs;
      
    // Define form name
    parent::__construct('indisponibilitechauffeur-form');
    
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
    
    // Add "idxChauffeur" field
    $this->add([
      'name' => 'IDX_CHAUFFEUR',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackChauffeurs,
        'label'         => 'Chauffeur',
      ],
    ]);
    
    // Add "start date" field
    $this->add([
      'name' => 'STARTDATEINDISPONIBILITE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de début',
      ],
    ]);

    // Add "start time" field
    $this->add([
      'name'    => 'STARTTIMEINDISPONIBILITE',
      'type'    => Time::class,
      'options' => [
        'label'  => 'Heure début',
        'format' => 'H:i:s',
      ],
      'attributes' => [
        'min'  => '00:00:00',
        'max'  => '23:59:59',
        'step' => '1',
      ],
    ]);
    
    // Add "end date" field
    $this->add([
      'name' => 'ENDDATEINDISPONIBILITE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
      ],
    ]);
    
    // Add "end time" field
    $this->add([
      'name'    => 'ENDTIMEINDISPONIBILITE',
      'type'    => Time::class,
      'options' => [
        'label'  => 'Heure fin',
        'format' => 'H:i:s',
      ],
      'attributes' => [
        'min'  => '00:00:00',
        'max'  => '23:59:59',
        'step' => '1',
      ],
    ]);
      
    // Add "all days" field
    $this->add([
      'name'  => 'ALLDAYINDISPONIBILITE',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'ALLDAYINDISPONIBILITE',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Toute la journée',
        'label_attributes'   => [
          'for' => 'ALLDAYINDISPONIBILITE',
        ],
      ],
    ]);

    // Add the Submit button
    $this->add([
      'name'       => 'submit',
      'type'       => 'submit',
      'attributes' => [
        'value' => 'Sauver',
        'id'    => 'submit',
      ],
    ]);
    
    // Add the CSRF field
    $this->add([
      'name'    => 'csrf',
      'type'    => 'csrf',
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

    $indisponibiliteChauffeur = new IndisponibiliteChauffeur();
    $indisponibiliteChauffeur->fillInputFilter($inputFilter);
  }
}
