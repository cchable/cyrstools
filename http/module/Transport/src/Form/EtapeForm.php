<?php
/**
 * Form for the encoding Etape
 * 
 * @package   module/Transport/src/Form/EtapeForm.php
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

use Laminas\Filter\Boolean;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\ToFloat;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\IsFloat;
use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;

use Transport\Model\Etape;

/**
 * 
 */
class EtapeForm extends Form
{

  /**
   * Scenario ('create' or 'update' or 'info').
   * @var string 
   */
  private $scenario;


  /**
   * Constructor
   */
  public function __construct($scenario = 'create')
  {

    // Save parameters for internal use
    $this->scenario = $scenario;
      
    // Define form name
    parent::__construct('etape-form');
    
    // Set POST method for this form
    $this->setAttribute('method', 'post');
    
    $this->addElements();
    $this->addInputFilter();
  }
  
  /**
   * This method adds elements to form (input fields and submit button).
   */
  protected function addElements() 
  {
    
    // Add "NOMETAPE" field
    $this->add([
      'name'       => 'NOMETAPE',
      'type'       => 'text',
      'options'    => [
        'label' => 'Etape',
      ],
    ]);
    
    // Add "ADRESSEETAPE" field
    $this->add([
      'name'       => 'ADRESSEETAPE',
      'type'       => 'text',
      'options'    => [
        'label' => 'Adresse',
      ],
    ]);

    // Add "PRINTEDETAPE" field
    $this->add([
      'name'  => 'PRINTEDETAPE',
      'type'  => Checkbox::class,
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Imprimé',
        'label_attributes'   => [
          'for' => 'PRINTEDETAPE',
        ],
      ],
        
      'attributes' => [
        'id'    => 'PRINTEDETAPE',
        'class' => 'form-check-input',
        'value' => '0',
      ],
    ]);
    
    // Add "LATITUDEETAPE" field
    $this->add([
      'name'       => 'LATITUDEETAPE',
      'type'       => Element\Number::class,
      'options'    => [
        'label' => 'Latitude',
      ],
      'attributes' => [
        'step'  => 0.000001
      ],
    ]);
    
    // Add "LONGITUDEETAPE" field
    $this->add([
      'name'       => 'LONGITUDEETAPE',
      'type'       => 'Number',
      'options'    => [
        'label' => 'Longitude',
      ],
      'attributes' => [
        'step'  => 0.000001
      ],
    ]);
    
    // Add "ALTITUDEETAPE" field
    $this->add([
      'name'       => 'ALTITUDEETAPE',
      'type'       => 'Number',
      'options'    => [
        'label' => 'Altitude',
      ],
      'attributes' => [
        'step'  => 0.5
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
  
  /**
   * This method createsinput filter (used for form filtering/validation).
   */
  private function addInputFilter() 
  {
    
    /**
     * Create input filter
     */
    $inputFilter = $this->getInputFilter();

    /**
     * Add input for NOMETAPE field
     */
    $inputFilter->add([
      'name'           => 'NOMETAPE',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'Nom',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 60,
          ],
        ],
      ],
      'filters' => [
        [
          'name' => StringTrim::class,
        ],
        [
          'name' => StripTags::class,
        ],
      ],
    ]);

    /**
     * Add input for ADRESSEETAPE field
     */
    $inputFilter->add([
      'name'           => 'ADRESSEETAPE',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'Nom',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 100,
          ],
        ],
      ],
      'filters' => [
        [
          'name' => StringTrim::class,
        ],
        [
          'name' => StripTags::class,
        ],
      ],
    ]);

    /**
     * Add input for PRINTEDETAPE field
     */
    $inputFilter->add([
      'name'              => 'PRINTEDETAPE',
      'required'          => true,
      'allow_empty'       => true,
      'continue_if_empty' => true,        
      'description'       => 'Imprimé ?',
      'filters' => [
        [
          'name' => Boolean::class,
          'options' => [
            'type' => [
              Boolean::TYPE_BOOLEAN,
              Boolean::TYPE_INTEGER,
              Boolean::TYPE_ZERO_STRING,
            ],  
          ]
        ],
      ],         
    ]);

    /**
     * Add input for LATITUDEETAPE field
     */
    $inputFilter->add([
      'name'        => 'LATITUDEETAPE',
      'allow_empty' => false,
      'required'    => true,
      'description' => 'Latitude',

      'filters'     => [
        [
          'name' => ToFloat::class,
        ],
      ],
      
      'validators'   => [
        [
          'name'  => IsFloat::class,
        ],
      ],
    ]);
    
    /**
     * Add input for LONGITUDEETAPE field
     */
    $inputFilter->add([
      'name'        => 'LONGITUDEETAPE',
      'allow_empty' => false,
      'required'    => true,
      'description' => 'Longitude',

      'filters'     => [
        [
          'name' => ToFloat::class,
        ],
      ],
      
      'validators'  => [
        [
          'name' => IsFloat::class,
        ],
      ],
    ]);
    
    /**
     * Add input for ALTITUDEETAPE field
     */
    $inputFilter->add([
      'name'        => 'ALTITUDEETAPE',
      'allow_empty' => false,
      'required'    => true,
      'description' => 'Altitude',

      'filters'     => [
        [
          'name' => ToFloat::class,
        ],
      ],
      
      'validators'  => [
        [
          'name' => IsFloat::class,
        ],
      ],
    ]);
  }
}
