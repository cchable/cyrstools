<?php
/**
 * Form for the encoding vehicle
 * 
 * @package   module/Transport/src/Form/VehiculeForm.php
 * @version   1.0
 * @copyright 2018-22 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Transport\Form;

use DomainException;

use Laminas\Form\Form;
use Laminas\Form\Element;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;

use Laminas\Validator\StringLength;
use Laminas\I18n\Validator\IsInt;

/**
 * 
 */
class VehiculeForm extends Form
{

  /**
   * Scenario ('create' or 'update')
   * @var string 
   */
  private $scenario;

  /**
   * Table manager
   * @var Parking\Model\vehiculeTable
   */
  private $vehiculeTable;

  /**
   * Current Vehicule
   * @var Parking\Model\Vehicule 
   */
  private $vehicule;

  /**
   * 
   */
   private $haystackMarque;
   
  /**
   * 
   */
   private $haystackTypeVehicule;
   
   
  /**
   * Constructor
   */
  public function __construct(array $haystackMarque, array $haystackTypeVehicule, $scenario = 'create')
  {

    // Save parameters for internal use
    $this->haystackMarque = $haystackMarque;
    
    // Save parameters for internal use
    $this->haystackTypeVehicule = $haystackTypeVehicule;
    
    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('vehicule-form');
    
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
    
    // Add "IDX_MARQUE" field
    $this->add([
      'name' => 'IDX_MARQUE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackMarque,
        'label'         => 'Marque',
      ],
    ]);
    
    // Add "IDX_TYPEVEHICULE" field
    $this->add([
      'name' => 'IDX_TYPEVEHICULE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackTypeVehicule,
        'label'         => 'Type de véhicule',
      ],
    ]);
    
    // Add "NOMVEHICULE" field
    $this->add([
      'name' => 'NOMVEHICULE',
      'type' => Element\text::class,
      'options' => [
        'label' => 'Nom du véhicule',
      ],
    ]);
    
    // Add "PLACESVEHICULE" field
    $this->add([
      'name' => 'PLACESVEHICULE',
      'type' => Element\Number::class,
      'options' => [
        'label' => 'Places du véhicule',
      ],
    ]);
    
    // Add "NUMEROVEHICULE" field
    $this->add([
      'name' => 'NUMEROVEHICULE',
      'type' => Element\Number::class,
      'options' => [
        'label' => 'Numéro du véhicule',
      ],
      'attributes' => [
        'min'  => '1',
        'step' => '1', // default step interval is 1
      ],
    ]);    
     
    // Add "PLAQUEVEHICULE" field
    $this->add([
      'name' => 'PLAQUEVEHICULE',
      'type' => Element\text::class,
      'options'    => [
        'label' => 'Plaque du véhicule',
      ],
    ]);      
     
    // Add "MODELEVEHICULE" field
    $this->add([
      'name' => 'MODELEVEHICULE',
      'type' => Element\text::class,
      'options'    => [
        'label' => 'Modèle',
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
     * Add input for NOMVEHICULE field
     */
    $inputFilter->add([
      'name' => 'NOMVEHICULE',
      'required' => true,
      'validators' => [
        [
          'name' => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
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
     * Add input for PLACESVEHICULE field
     */
    $inputFilter->add([
      'name' => 'PLACESVEHICULE',
      'required' => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name' => IsInt::class,
        ],        
      ],
      'filters' => [
        [
          'name' => ToInt::class,
        ],
      ],
    ]);
 
    /**
     * Add input for NUMEROVEHICULE field
     */
    $inputFilter->add([
      'name' => 'NUMEROVEHICULE',
      'required' => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name' => IsInt::class,
        ],        
      ],
      'filters' => [
        [
          'name' => ToInt::class,
        ],
      ],
    ]);
 
    /**
     * Add input for PLAQUEVEHICULE field
     */
    $inputFilter->add([
      'name'        => 'PLAQUEVEHICULE',
      'required'    => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
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
     * Add input for MODELEVEHICULE field
     */
    $inputFilter->add([
      'name'        => 'MODELEVEHICULE',
      'required'    => true,
      'allow_empty' => false,
      'validators'  => [
        [
          'name'    => StringLength::class,
          'options' => [
            'encoding' => 'UTF-8',
            'min'      => 1,
            'max'      => 30,
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
