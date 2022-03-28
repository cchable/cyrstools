<?php
/**
 * @package   : module/PlanningBus/src/Form/TransportToExcelForm.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace PlanningBus\Form;

use DomainException;

use Laminas\Form\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\DateTimeFormatter;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

use Laminas\Validator\StringLength;


/*
 * 
 */
class TransportToExcelForm extends Form
{

  /*
   * Constructor
   */
  public function __construct()
  {
      
    // Define form name
    parent::__construct('transporttoexcel-form');
    
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
    
    // Add "datedebutplage" field
    $this->add([
      'name' => 'datedebutplage',
      'type' => 'date',
      'options' => [
        'label' => 'Date de début',
      ],
    ]);
    
    // Add "datefin" field
    $this->add([
      'name' => 'datefinplage',
      'type' => 'date',
      'options' => [
        'label' => 'Date de fin',
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

    //
    $inputFilter->add([
      'name'       => 'datedebutplage',
      'required'   => true,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => 'Date',
          'options' => [
            'format' => 'Y-m-d',
          ],
        ],
      ],
    ]);
    
    //
    $inputFilter->add([
      'name'       => 'datefinplage',
      'required'   => true,
      'filters'    => [
        ['name' => StripTags::class],
        ['name' => StringTrim::class],
      ],
      'validators' => [
        [
          'name'    => 'Date',
          'options' => [
            'format' => 'Y-m-d',
          ],
        ], 
      ],
    ]);
  }
}
