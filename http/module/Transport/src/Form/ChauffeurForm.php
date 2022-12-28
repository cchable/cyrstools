<?php
/**
 * Form for the encoding of a Chauffeur
 * 
 * @package   : module/Transport/src/Form/ChauffeurForm.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Transport\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Form\Element\Checkbox;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;

use Transport\Model\Chauffeur;


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
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use
    $this->scenario = $scenario;
      
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
    
    // Add "prenom" field
    $this->add([
      'name' => 'PRENOMCHAUFFEUR',
      'type' => 'text',
      'options' => [
        'label' => 'Prénom',
      ],
    ]);
    
    // Add "principal" field
    $this->add([
      'name'  => 'PRINCIPALCHAUFFEUR',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'PRINCIPALCHAUFFEUR',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Ce chauffeur est principal',
        'label_attributes'   => [
          'for' => 'PRINCIPALCHAUFFEUR',
        ],
      ],
    ]);
    
    // Add "actif" field
    $this->add([
      'name' => 'ACTIFCHAUFFEUR',
      'type' => Checkbox::class,
        
      'attributes' => [
        'id'    => 'ACTIFCHAUFFEUR',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Ce chauffeur est actif',
        'label_attributes'   => [
          'for' => 'ACTIFCHAUFFEUR',
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

    $chauffeur = new Chauffeur();
    $chauffeur->fillInputFilter($inputFilter);
  }
}
