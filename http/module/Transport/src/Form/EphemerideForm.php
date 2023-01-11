<?php
/**
 * Form for the encoding ephemeride
 * 
 * @package   module/Transport/src/Form/EphemerideForm.php
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
use Laminas\Form\Element\Checkbox;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;

use Transport\Model\Ephemeride;


/**
 * 
 */
class EphemerideForm extends Form
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
  private $haystackAnneesScolaires;

  /*
   * Constructor
   */
  public function __construct(array $haystackAnneesScolaires, $scenario = 'create')
  {

    // Save parameters for internal use
    $this->haystackAnneesScolaires = $haystackAnneesScolaires;
    
    // Save parameters for internal use.
    $this->scenario = $scenario;
    
    // Define form name
    parent::__construct('ephemeride-form');
    
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
    
    // Add "idxEphemeride" field
    $this->add([
      'name' => 'IDX_ANNEESCOLAIRE',
      'type' => 'select',
      'options' => [
        'value_options' => $this->haystackAnneesScolaires,
        'label'         => 'Année scolaire',
      ],
    ]);

    // Add "nom" field
    $this->add([
      'name' => 'NOMEPHEMERIDE',
      'type' => 'text',
      'options' => [
        'label' => 'Nom',
      ],
    ]);
    
    // Add "start date" field
    $this->add([
      'name' => 'STARTDATEPHEMERIDE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de début',
      ],
    ]);
    
    // Add "end date" field
    $this->add([
      'name' => 'ENDDATEPHEMERIDE',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
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

    $ephemeride = new Ephemeride();
    $ephemeride->fillInputFilter($inputFilter);
  }
}
