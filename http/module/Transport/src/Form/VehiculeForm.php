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

use Transport\Model\Vehicule;


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
   * @var Parking\Model\BusTable
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
    $this->haystackMarque = $haystackMarque
    
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

//ici
    // Add "marque" field
    $this->add([
      'name' => 'MARQUEVEHICULE',
      'type' => 'text',
      'options' => [
        'label' => 'Marque',
      ],
    ]);
    
    // Add "modele" field
    $this->add([
      'name' => 'MODELEVEHICULE',
      'type' => 'text',
      'options' => [
        'label' => 'Modèle',
      ],
    ]);
    
    // Add "nom" field
    $this->add([
      'name' => 'NOMVEHICULE',
      'type' => 'text',
      'options' => [
        'label' => 'Nom',
      ],
    ]);    
     
    // Add "places" field
    $this->add([
      'name' => 'PLACESVEHICULE',
      'type' => Element\Number::class,
      'options'    => [
        'label' => 'Places',
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

    $vehicule = new Vehicule();
    $vehicule->fillInputFilter($inputFilter);
  }  
}
