<?php
/**
 * @package   : module/PlanningBus/src/Form/PlanningForm.php
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

use PlanningBus\Model\Planning;


/*
 * 
 */
class PlanningForm extends Form
{

  /*
   * Scenario ('create' or 'update').
   * @var string 
   */
  private $scenario;
  
  /*
   * 
   * @var array
   */
  private $haystackTypesPlannings;
  
  /*
   * 
   * @var array
   */
  private $haystackDatesPlannings;
  
  /*
   * 
   * @var array
   */
  private $haystackHeuresPlannings;


  /*
   * Constructor
   */
  public function __construct($scenario = 'create', array $haystackTypesPlannings, array $haystackDatesPlannings, array $haystackHeuresPlannings)
  {

    // Save parameters for internal use
    $this->scenario                = $scenario;
    $this->haystackTypesPlannings  = $haystackTypesPlannings;
    $this->haystackDatesPlannings  = $haystackDatesPlannings;
    $this->haystackHeuresPlannings = $haystackHeuresPlannings;
      
    // Define form name
    parent::__construct('planning-form');
    
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
    
    // Add "idx type planning" field
    $this->add([
      'name' => 'IDX_TYPEPLANNING',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackTypesPlannings,
        'label'         => 'Type de planning',
      ],
    ]);
    
    // Add "idx date planning" field
    $this->add([
      'name' => 'IDX_DATEPLANNING',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackDatesPlannings,
        'label'         => 'Date de planning',
      ],
    ]);
              
    // Add "idx heure planning" field
    $this->add([
      'name' => 'IDX_HEUREPLANNING',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackHeuresPlannings,
        'label'         => 'Heure de planning',
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

    $planning = new Planning();
    $planning->fillInputFilter($inputFilter);
  }
}
