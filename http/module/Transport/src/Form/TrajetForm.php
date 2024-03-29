<?php
/**
 * Form for the encoding Trajet
 * 
 * @package   module/Transport/src/Form/TrajetForm.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Transport\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\Form\Element\Time;

use Laminas\Filter\DateTimeFormatter;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToFloat;
use Laminas\Filter\ToInt;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;

use Laminas\I18n\Validator\DateTime;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;

use Transport\Model\Trajet;

/**
 * 
 */
class TrajetForm extends Form
{

  /**
   * haystackVehicules : liste of etapesdepart
   * @var array
   */
  private $haystackEtapesDepart;

  /**
   * haystackVehicules : liste of etapesarrivee
   * @var array
   */
  private $haystackEtapesArrivee;

  /**
   * Scenario ('create' or 'update' or 'info').
   * @var string 
   */
  private $scenario;


  /**
   * Constructor
   */
  public function __construct(array $haystackEtapesDepart, array $haystackEtapesArrivee, $scenario = 'create')
  {

    // Save parameters for internal use
    $this->haystackEtapesDepart  = $haystackEtapesDepart;
    $this->haystackEtapesArrivee = $haystackEtapesArrivee;
    $this->scenario = $scenario;
      
    // Define form name
    parent::__construct('trajet-form');
    
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

    // Add "IDX_ETAPEDEPART" field
    $this->add([
      'name' => 'IDX_ETAPEDEPART',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackEtapesDepart,
        'label'         => 'Etapes départ',
      ],
    ]);

    // Add "IDX_ETAPEARRIVEE" field
    $this->add([
      'name' => 'IDX_ETAPEARRIVEE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackEtapesArrivee,
        'label'         => 'Etapes arrivée',
      ],
    ]);
    
    // Add "NOMTRAJET" field
    $this->add([
      'name'       => 'NOMTRAJET',
      'type'       => 'text',
      'options'    => [
        'label' => 'Nom du trajet',
      ],
    ]);
    
    // Add "TEMPSTRAJET" field
    $this->add([
      'name'    => 'TEMPSTRAJET',
      'type'    => Time::class,
      'options' => [
        'label'  => 'temps du trajet',
        'format' => 'H:i:s',
      ],
      'attributes' => [
        'min'  => '00:00:00',
        'max'  => '23:59:59',
        'step' => '1',
      ],
    ]);
    
    // Add "KMTRAJET" field
    $this->add([
      'name'       => 'KMTRAJET',
      'type'       => Element\Number::class,
      'options'    => [
        'label' => 'Kms du trajet',
      ],
      'attributes' => [
        'step'  => 0.1
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
      'name'           => 'NOMTRAJET',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'Nom du trajet',
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
     * Add input for TEMPSTRAJET field
     */
    $inputFilter->add([
      'name'       => 'TEMPSTRAJET',
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
     * Add input for KMTRAJET field
     */
    $inputFilter->add([
      'name'        => 'KMTRAJET',
      'allow_empty' => false,
      'required'    => true,
      'description' => 'Kms du trajet',

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