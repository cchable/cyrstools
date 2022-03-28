<?php
/**
 * @package   : module/PlanningBus/src/Form/ChauffeurForm.php
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

use PlanningBus\Model\Chauffeur;


/*
 * 
 */
class ChauffeurForm extends Form
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
  private $haystackVehicules;


  /*
   * Constructor
   */
  public function __construct($scenario = 'create', array $haystackVehicules)
  {

    // Save parameters for internal use
    $this->scenario          = $scenario;
    $this->haystackVehicules = $haystackVehicules;
      
    // Define form name
    parent::__construct('chauffeur-form');
    
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
    
    // Add "idx vehicule" field
    $this->add([
      'name' => 'IDX_VEHICULE',
      'type' => Element\Radio::class,
      'options' => [
        'value_options' => $this->haystackVehicules,
        'label'         => 'Véhicule',
      ],
    ]);
    
    // Add "intitule" field
    $this->add([
      'name' => 'PRENOMCHAUFFEUR',
      'type' => 'text',
      'options' => [
        'label' => 'Prénom',
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

    $chauffeur = new Chauffeur();
    $chauffeur->fillInputFilter($inputFilter);
  }
}
