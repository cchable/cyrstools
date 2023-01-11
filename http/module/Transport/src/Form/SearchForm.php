<?php
/**
 * @package   : module/Transport/src/Form/SearchForm.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 *
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

use Laminas\Validator\StringLength;


/*
 * 
 */
class SearchForm extends Form
{

  /*
   * Constructor
   */
  public function __construct($url = false)
  {
   
    // Define form name
    parent::__construct('search-form');
    
    // Set POST method for this form
    $this->setAttribute('method', 'post');
    if ($url) {
      $this->setAttribute('action', $url);
    }
    
    $this->addElements();
    $this->addInputFilter();   
  }
    
  /*
   * This method adds elements to form (input fields and submit button).
   */
  protected function addElements() 
  {

    // Add "search" field
    $this->add([
      'name' => 'search',
      'type' => 'search',
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

    // Add input for "nom" field
    $inputFilter->add([
      'name' => 'search',
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
    
  }
}
