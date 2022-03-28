<?php
/**
 * @package   : module/PlanningBus/src/Form/AnneeScolaireForm.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace PlanningBus\Form;

use Laminas\Form\Form;

use Laminas\Form\Element;

use PlanningBus\Model\Groupe;

/*
 * 
 */
class AnneeScolaireForm extends Form
{

  /*
   * Scenario ('create' or 'update').
   * @var string 
   */
  private $scenario;

  /*
   * Table manager.
   * @var Parking\Model\AnneeScolaireTable
   */
  private $anneeScolaireTable;

  /*
   * Current AnneeScolaire.
   * @var Parking\Model\AnneeScolaire 
   */
  private $anneeScolaire;

  /*
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('anneescolaire-form');
    
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

    // Add "annee scolaire" field
    $this->add([
      'name' => 'ANNEEANNEESCOLAIRE',
      'type' => Element\Number::class,
      'options'    => [
        'label' => 'Année scolaire',
      ],
      'attributes' => [
        'min'  => '1',
        'step' => '1', // default step interval is 1
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

    // Add input for "nom" field
    $inputFilter->add([
      'name' => 'ANNEEANNEESCOLAIRE',
      'required' => true,
      'filters'  => [                    
        ['name' => 'ToInt'],
      ],                
      'validators' => [
        ['name'    => 'GreaterThan', 
				 'options' => ['min'=>0]]
      ],
    ]);
  }
}
