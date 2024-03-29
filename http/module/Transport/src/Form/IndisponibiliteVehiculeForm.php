<?php
/**
 * Form for the encoding an IndisponibiliteVehicule
 * 
 * @package   module/Transport/src/Form/IndisponibiliteVehicule.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

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
use Laminas\Filter\Boolean;


use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;
use Laminas\Validator\InArray;
use Laminas\Validator\Regex;

use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\DateTime;

use Transport\Model\IndisponibiliteVehicule;


/**
 * 
 */
class IndisponibiliteVehiculeForm extends Form
{

  /*
   * Scenario ('create' or 'update').
   * @var string 
   */
  private $scenario;

  /**
   * haystackVehicules : liste of vehicules
   * @var array
   */
  private $haystackVehicules;

  /**
   * Constructor
   */
  public function __construct(array $haystackVehicules)
  {

    // Save parameters for internal use
    $this->haystackVehicules = $haystackVehicules;
      
    // Define form name
    parent::__construct('indisponibilitechauffeur-form');
    
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
    
    // Add "idxVehicule" field
    $this->add([
      'name' => 'IDX_VEHICULE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackVehicules,
        'label'         => 'Vehicule',
      ],
    ]);
    
    // Add "start date" field
    $this->add([
      'name' => 'STARTDATEINDISPONIBILITE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de début',
      ],
    ]);

    // Add "start time" field
    $this->add([
      'name'    => 'STARTTIMEINDISPONIBILITE',
      'type'    => Time::class,
      'options' => [
        'label'  => 'Heure début',
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
      'name' => 'ENDDATEINDISPONIBILITE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
      ],
    ]);
    
    // Add "end time" field
    $this->add([
      'name'    => 'ENDTIMEINDISPONIBILITE',
      'type'    => Time::class,
      'options' => [
        'label'  => 'Heure fin',
        'format' => 'H:i:s',
      ],
      'attributes' => [
        'min'  => '00:00:00',
        'max'  => '23:59:59',
        'step' => '1',
      ],
    ]);
      
    // Add "all days" field
    $this->add([
      'name'  => 'ALLDAYINDISPONIBILITE',
      'type'  => Checkbox::class,
        
      'attributes' => [
        'id'    => 'ALLDAYINDISPONIBILITE',
        'class' => 'form-check-input',
        'value' => '0',
      ],
        
      'options' => [
        'use_hidden_element' => true,
        'checked_value'      => '1',
        'unchecked_value'    => '0',
        'label'              => 'Toute la journée',
        'label_attributes'   => [
          'for' => 'ALLDAYINDISPONIBILITE',
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
   * This method creates input filter (used for form filtering/validation).
   */
  private function addInputFilter() 
  {
    
    /**
     * Create input filter
     */
    $inputFilter = $this->getInputFilter();

    /**
     * Add input for STARTDATEINDISPONIBILITE field
     */
    $inputFilter->add([
      'name'       => 'STARTDATEINDISPONIBILITE',
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
     * Add input for ENDDATEINDISPONIBILITE field
     */
    $inputFilter->add([
      'name'       => 'ENDDATEINDISPONIBILITE',
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
     * Add input for STARTTIMEINDISPONIBILITE field
     */
    $inputFilter->add([
      'name'       => 'STARTTIMEINDISPONIBILITE',
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
    
    /**
     * Add input for ENDTIMEINDISPONIBILITE field
     */
    $inputFilter->add([
      'name'       => 'ENDTIMEINDISPONIBILITE',
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
