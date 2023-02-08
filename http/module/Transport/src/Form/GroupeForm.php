<?php
/**
 * Form for the encoding Groupe
 * 
 * @package   module/Transport/src/Form/GroupeForm.php
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
use Laminas\Filter\ToInt;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\InArray;
use Laminas\Validator\StringLength;

use Laminas\I18n\Validator\IsInt;

use Transport\Model\Groupe;

/**
 * 
 */
class GroupeForm extends Form
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
    parent::__construct('groupe-form');
    
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
   
    // Add "NOMGROUPE" field
    $this->add([
      'name'       => 'NOMGROUPE',
      'type'       => 'text',
      'options'    => [
        'label' => 'Nom du groupe',
      ],
    ]);
    
    // Add "NOMBREACCOMPAGNATEURGROUPE" field
    $this->add([
      'name'       => 'NOMBREACCOMPAGNATEURGROUPE',
      'type'       => Element\Number::class,
      'options'    => [
        'label' => "Nombre d'accompagnateur(s)",
      ],
      'attributes' => [
        'min'   => 0,
        'step'  => 1,
      ],      
    ]);
    
    // Add "NOMBREGROUPE" field
    $this->add([
      'name'       => 'NOMBREGROUPE',
      'type'       => Element\Number::class,
      'options'    => [
        'label' => 'Nombre de personne(s)',
      ],
      'attributes' => [
        'min'   => 1,
        'step'  => 1,
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
      'name'           => 'NOMGROUPE',
      'allow_empty'    => false,
      'required'       => true,
      'description'    => 'Nom du groupe',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 20,
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
     * Add input for NOMBREACCOMPAGNATEURGROUPE field
     */
    $inputFilter->add([
      'name'        => 'NOMBREACCOMPAGNATEURGROUPE',
      'allow_empty' => false,
      'required'    => true,
      'description' => "Nombre d'accompagnateurs",

      'filters'     => [
        [
          'name' => ToInt::class,
        ],
      ],
      
      'validators'  => [
        [
          'name' => IsInt::class,
        ],
      ],
    ]);
    
    /**
     * Add input for NOMBREGROUPE field
     */
    $inputFilter->add([
      'name'        => 'NOMBREGROUPE',
      'allow_empty' => false,
      'required'    => true,
      'description' => 'Nombre de personnes',

      'filters'     => [
        [
          'name' => ToInt::class,
        ],
      ],
      
      'validators'  => [
        [
          'name' => IsInt::class,
        ],
      ],
    ]);
  }
}
