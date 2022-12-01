<?php
/**
 * @package   : module/Transport/src/Form/AnneeScolaireForm.php
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

use Laminas\Filter\ToInt;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\I18n\Validator\IsInt;

use Transport\Model\AnneeScolaire;


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
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use
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
    
    // Add "prenom" field
    $this->add([
      'name'       => 'ANNEEANNEESCOLAIRE',
      'type'       => 'Number',
      'options'    => [
        'label' => 'Année scolaire',
      ],
      'attributes' => [
        'min'   => '2021',
        'step'  => '1', // default step interval is 1
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

    $anneeScolaire = new AnneeScolaire();
    $anneeScolaire->fillInputFilter($inputFilter);
  }
}
