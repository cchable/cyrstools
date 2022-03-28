<?php
/**
 * @package   : module/PlanningBus/src/Form/EtapeForm.php
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

use PlanningBus\Model\Etape;


/*
 * 
 */
class EtapeForm extends Form
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
  private $etapeTable;

  /*
   * Current Etape
   * @var Parking\Model\Etape 
   */
  private $etape;

  /*
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('etape-form');
    
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
      'name' => 'NOMETAPE',
      'type' => 'text',
      'options' => [
        'label' => 'Nom',
      ],
    ]);
    
    // Add "adrresse" field
    $this->add([
      'name' => 'ADRESSEETAPE',
      'type' => 'text',
      'options' => [
        'label' => 'Adresse',
      ],
    ]);
    
    // Add "printed" field
    $this->add([
      'name' => 'PRINTEDETAPE',
      'type' => 'checkbox',
      'options' => [
        'label'              => 'Imprimé',
        'use_hidden_element' => true,
        'checked_value'      => 1,
        'unchecked_value'    => 0,
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

    $etape = new Etape();
    $etape->fillInputFilter($inputFilter);
  }  
}
