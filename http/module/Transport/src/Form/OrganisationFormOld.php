<?php
/**
 * This is the form class for Organisation.
 *
 * @package   module/Transport/src/Form/OrganisationForm.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Form\Element\Time;
use Laminas\Form\Element\Checkbox;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
use Laminas\I18n\Validator\DateTime;

use Transport\Model\Organisation;


/**
 *
 */
class OrganisationForm extends Form
{

  /**
   * Scenario ('create' or 'update').
   * @var string
   */
  private $scenario;

  /**
   * $haystackGroupe for a groupes list
   * @var array
   */
  private $haystackGroupes;

  /**
   * Constructor
   */
  public function __construct(array $haystackGroupes, $scenario)
  {

    // Save parameters for internal use
    $this->haystackGroupes = $haystackGroupes;
    $this->scenario = $scenario;
      
    // Define form name
    parent::__construct('organisation-form');
    
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
    
    // Add "idxGrouper" field
    $this->add([
      'name' => 'IDX_GROUPE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackGroupes,
        'label'         => 'Groupes',
      ],
    ]);
    
    // Add "date" field
    $this->add([
      'name' => 'DATEORGANISATION',
      'type' => 'date',
      'options' => [
        'label' => 'Date',
      ],
    ]);

    // Add "time" field
    $this->add([
      'name'    => 'TIMEORGANISATION',
      'type'    => Time::class,
      'options' => [
        'label'  => 'Heure',
        'format' => 'H:i:s',
      ],
      'attributes' => [
        'min'  => '00:00:00',
        'max'  => '23:59:59',
        'step' => '1',
      ],
    ]);
    
    // Add "end date" field
    $this->add([
      'name' => 'enddateorganisation',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
      ],
    ]);
      
    // Add "multipledates" field
    $this->add([
      'name'  => 'multipledates',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'multipledates',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Multiple dates',
        'label_attributes'   => [
          'for' => 'multipledates',
        ],
      ],
    ]);
    
    // Add "1x/Sem" field
    $this->add([
      'name'  => 'onceaweek',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'onceaweek',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => '1x/Semaine',
        'label_attributes'   => [
          'for' => 'oneperweek',
        ],
      ],
    ]);
         
    // Add "1x/2Sem" field
    $this->add([
      'name'  => 'onceeverytwoweeks',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'onceeverytwoweeks',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => '1x/2 Semaines',
        'label_attributes'   => [
          'for' => 'onceeverytwoweeks',
        ],
      ],
    ]);
    
    // Add "1x/2Sem" field
    $this->add([
      'name'  => 'onceeverythreeweeks',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'onceeverythreeweeks',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => '1x/3 Semaines',
        'label_attributes'   => [
          'for' => 'onceeverythreeweeks',
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
     * Add input for DATEORGANISATION field
     */
    $inputFilter->add([
      'name'       => 'DATEORGANISATION',
      'required'   => true,
      'validators' => [
        [
          'name'    => DateTime::class,
          'options' => [
            'pattern' => 'Y-m-d',
            'message' => 'Invalid date format',
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
     * Add input for TIMEORGANISATION field
     */
    $inputFilter->add([
      'name'       => 'TIMEORGANISATION',
      'required'   => true,
      'validators' => [
        [
          'name'    => DateTime::class,
          'options' => [
            'pattern' => 'HH:mm:ss',
            'message' => 'Invalid time format',
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
  }
}