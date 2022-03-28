<?php
/**
 * @package   : module/PlanningBus/src/Form/HeurePlanningForm.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;

use PlanningBus\Model\HeurePlanning;


/*
 * 
 */
class HeurePlanningForm extends Form
{

  /*
   * Scenario ('create' or 'upheure')
   * @var string 
   */
  private $scenario;

  /*
   * Table manager
   * @var Parking\Model\BusTable
   */
  private $heurePlanningTable;

  /*
   * Current HeurePlanning
   * @var Parking\Model\HeurePlanning 
   */
  private $heurePlanning;

  /*
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('heureplanning-form');
    
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

    // Add "HEUREHEUREPLANNING" field
    $this->add([
      'name'    => 'HEUREHEUREPLANNING',
      'type'    => Element\Time::class,
      'options' => [
        'label'  => 'Heure',
        'format' => 'H:i',
      ],
      'attributes' => [
        'min'  => '00:00',
        'max'  => '23:59',
        'step' => '60',
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

    $heurePlanning = new HeurePlanning();
    $heurePlanning->fillInputFilter($inputFilter);
  }  
}
