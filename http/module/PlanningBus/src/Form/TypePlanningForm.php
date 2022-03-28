<?php
/**
 * @package   : module/PlanningBus/src/Form/TypePlanningForm.php
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

use PlanningBus\Model\TypePlanning;


/*
 * 
 */
class TypePlanningForm extends Form
{

  /*
   * Scenario ('create' or 'update')
   * @var string 
   */
  private $scenario;

  /*
   * Table manager
   * @var Parking\Model\BusTable
   */
  private $typePlanningTable;

  /*
   * Current TypePlanning
   * @var Parking\Model\TypePlanning 
   */
  private $typePlanning;

  /*
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('typeplanning-form');
    
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

    // Add "nom" field
    $this->add([
      'name' => 'NOMTYPEPLANNING',
      'type' => 'text',
      'options' => [
        'label' => 'Nom',
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

    $typePlanning = new TypePlanning();
    $typePlanning->fillInputFilter($inputFilter);
  }  
}
