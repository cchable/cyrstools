<?php
/**
 * @package   : module/PlanningBus/src/Form/TrajetForm.php
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
use Laminas\Form\Element;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;

use PlanningBus\Model\Trajet;


/*
 * 
 */
class TrajetForm extends Form
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
  private $haystackEtapes;
  

  /*
   * Constructor
   */
  public function __construct($scenario = 'create', array $haystackEtapes)
  {

    // Save parameters for internal use
    $this->scenario       = $scenario;
    $this->haystackEtapes = $haystackEtapes;
      
    // Define form name
    parent::__construct('trajet-form');
    
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
    // Add "idx etape depart" field
    $this->add([
      'name'    => 'IDX_ETAPEDEPART',
      'type'    => Element\Select::class,
      'options' => [
        'value_options' => $this->haystackEtapes,
        'label'         => 'Etape du départ',
      ],
    ]);
    
    // Add "idx etape arrivee" field
    $this->add([
      'name'    => 'IDX_ETAPEARRIVEE',
      'type'    => Element\Select::class,
      'options' => [
        'value_options' => $this->haystackEtapes,
        'label'         => "Etape de l'arrivée",
      ],
    ]);
    
    // Add "nom" field
    $this->add([
      'name'    => 'NOMTRAJET',
      'type'    => Element\Text::class,
      'options' => [
        'label' => 'Nom du trajet',
      ],
    ]);
    
    // Add "temps" field
    $this->add([
      'name'    => 'TEMPSTRAJET',
      'type'    => Element\Time::class,
      'options' => [
        'label'  => 'Temps du trajet',
        'format' => 'H:i',
      ],
      'attributes' => [
        'min'  => '00:00',
        'max'  => '23:59',
        'step' => '60',
      ],
    ]);
    
    // Add "km" field
    $this->add([
      'name'    => 'KMTRAJET',
      'type'    => Element\Text::class,
      'options' => [
        'label' => 'Kilométrage du trajet',
      ],
    ]);
        
    // Add the Submit button
    $this->add([
      'name'       => 'submit',
      'type'       => Element\Submit::class,
      'attributes' => [
        'value' => 'Sauver',
        'id'    => 'submit',
      ],
    ]);
    
    // Add the CSRF field
    $this->add([
      'name'    => 'csrf',
      'type'    => Element\Csrf::class,
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

    $trajet = new Trajet();
    $trajet->fillInputFilter($inputFilter);
  }
}
