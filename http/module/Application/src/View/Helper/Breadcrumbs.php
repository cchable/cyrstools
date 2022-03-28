<?php
/**
 * @package   : module/Application/src/View/Helper/Breadcrumbs.php
 *
 * @purpose   : This view helper class displays breadcrumbs.
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;


/*
 * 
 */
class Breadcrumbs extends AbstractHelper 
{
  
  /*
   * Array of items.
   * @var array 
   */
  private $items = [];
    
  /*
   * Constructor.
   * @param array $items Array of items (optional).
   */
  public function __construct($items=[]) 
  {                
    
    $this->items = $items;
  }
  
  /*
   * Sets the items.
   * @param array $items Items.
   */
  public function setItems($items) 
  {
    
    $this->items = $items;
  }
    
  /*
   * Renders the breadcrumbs.
   * @return string HTML code of the breadcrumbs.
   */
  public function render() 
  {
    
    if (count($this->items)==0)
      return ''; // Do nothing if there are no items.
    
    // Resulting HTML code will be stored in this var
      
    //$result = '<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg ' . "xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;); " . 'aria-label="breadcrumb">';
    //$result =  '<nav style="--bs-breadcrumb-divider: url("data:image/svg+xml,<svg xmlns=' . "'http://www.w3.org/2000/svg' width='8' height='8'><path d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/></svg>" . '");" aria-label="breadcrumb">'; 
    
    //$result = '<nav class="navbar navbar-expand-lg navbar-light bg-light" style="--bs-breadcrumb-divider: ' . "'>'" . ';" aria-label="breadcrumb">';
    $result = '<nav class="navbar navbar-expand-lg rounded mt-0" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns=' . "'http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" . '" aria-label="breadcrumb">';
    $result .= '<div class="container-fluid">';  
    $result .= '<ol class="breadcrumb">';

    // Get item count
    $itemCount = count($this->items); 
    
    $itemNum = 1; // item counter
    
    // Walk through items
    foreach ($this->items as $label=>$link) {
        
      // Make the last item inactive
      $isActive = ($itemNum==$itemCount ? true : false);
                  
      // Render current item
      $result .= $this->renderItem($label, $link, $isActive);
                  
      // Increment item counter
      $itemNum++;
    }
    
    $result .= '</ol></div></nav>' . PHP_EOL;   
    return $result;
  }
    
  /*
   * @brief Renders an item.
   * @param string $label
   * @param string $link
   * @param boolean $isActive
   * @return string HTML code of the item.
   */
  protected function renderItem($label, $link, $isActive) 
  {
    
    $escapeHtml = $this->getView()->plugin('escapeHtml');
    
		$result = '<li class="breadcrumb-item' . ($isActive ? ' active' : '') . '">';
    //$result = $isActive ? '<li class="active">':'<li>';
    
    if (!$isActive)
      
      $result .= '<a href="' . $escapeHtml($link) . '">' . $escapeHtml($label) . '</a>';
    else
      $result .= $escapeHtml($label);
                
    $result .= '</li>';
  
    return $result;
  }
}
