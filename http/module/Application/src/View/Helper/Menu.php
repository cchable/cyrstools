<?php
/**
 * @package   : module/Application/src/View/Helper/Menu.php
 *
 * @purpose   : This view helper class displays a menu bar.
 * 
 * 
 * @copyright : Copyright (C) 2018-21 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;


/*
 * 
 */
class Menu extends AbstractHelper
{
	
  /*
   * Menu items array.
   * @var array 
   */
  protected $items = [];
  
  /*
   * Active item's ID.
   * @var string  
   */
  protected $activeItemId = '';
  
  /*
   * Constructor.
   * @param array $items Menu items.
   */
  public function __construct($items=[])
  {
    
    $this->items = $items;
  }
    
  /*
   * Sets menu items.
   * @param array $items Menu items.
   */
  public function setItems($items) 
  {
		
    $this->items = $items;
  }
  
  /*
   * Sets ID of the active items.
   * @param string $activeItemId
   */
  public function setActiveItemId($activeItemId) 
  {
		
    $this->activeItemId = $activeItemId;
  }
    
	/*
	 * Renders the menu.
	 * @return string HTML code of the menu.
	 */
  public function render() 
  {
		
    if (count($this->items)==0)
      return ''; // Do nothing if there are no items.
    
    $resultLeft = '';
    $resultRight = '';
    
    // Render items   
    foreach ($this->items as $item) {
      
      if(!isset($item['float'])) {
        
        $resultLeft .= $this->renderItem($item);
      } else {
        
        if($item['float']=='left') {  
        
          $resultLeft .= $this->renderItem($item);
        } else {
          
          if($item['float']=='right') {
            
            $resultRight .= $this->renderItem($item);
          } 
        }
      }
    }
    
    $result = PHP_EOL;
    $result .= '<nav class="navbar navbar-expand-lg navbar-light bg-primary bg-opacity-25 rounded" role="navigation" aria-label="navigation">' . PHP_EOL;		
    $result .= '  <div class="container-fluid">' . PHP_EOL;
    $result .= '    <div class="collapse navbar-collapse" id="navbarsmain">' . PHP_EOL;
    
    if($resultLeft) {
      
      $result .= '      <ul class="navbar-nav me-auto mb-2 mb-lg-0">' . PHP_EOL;
      $result .= $resultLeft;
      $result .= '      </ul>' . PHP_EOL;
    }
      
    if($resultRight) {
      
      $result .= '      <div class="text-end">' . PHP_EOL;
      $result .= '        <ul class="navbar-nav me-auto mb-2 mb-lg-0">' . PHP_EOL;
      $result .= $resultRight;
      $result .= '        </ul>' . PHP_EOL;
      $result .= '      </div>' . PHP_EOL;
    } 
    
    
    $result .= '    </div>' . PHP_EOL;
    $result .= '  </div>' . PHP_EOL;
    $result .= '</nav>' . PHP_EOL . PHP_EOL;
    
    return $result;
  }
    
  /*
   * Renders an item.
   * @param array $item The menu item info.
   * @return string HTML code of the item.
   */
  protected function renderItem($item) 
  {
		
    $id             = isset($item['id'])             ? $item['id'] : '';
    $isActive       = ($id==$this->activeItemId);
    $label          = isset($item['label'])          ? $item['label'] : '';
    $link           = isset($item['link'])           ? $item['link'] : '#';
    $imgSVG         = isset($item['imgsvg'])         ? $item['imgsvg'] : '';
    $bImageAndLabel = isset($item['bImageAndLabel']) ? $item['bImageAndLabel'] : '';
         
    $result = ''; 
 
    $escapeHtml = $this->getView()->plugin('escapeHtml');
    
    if (isset($item['dropdown'])) {
        
      $dropdownItems = $item['dropdown'];
      $dropdownItemsResult = '';
      $itemActived = false;
      foreach ($dropdownItems as $dropdownItem) {
        
        if(isset($dropdownItem['divider'])) {    
          
          $dropdownItemsResult .= '              <li><hr class="dropdown-divider"></li>' . PHP_EOL;
        } else {

          $idDropdownItem       = isset($dropdownItem['id'])    ? $dropdownItem['id'] : '';
          $isActiveDropdownItem = ($idDropdownItem==$this->activeItemId);
          $linkDropdownItem     = isset($dropdownItem['link'])  ? $dropdownItem['link'] : '#';
          $labelDropdownItem    = isset($dropdownItem['label']) ? $dropdownItem['label'] : '';
          $imgSVGDropdownItem         = isset($dropdownItem['imgsvg'])         ? $dropdownItem['imgsvg'] : '';
          $bImageAndLabelDropdownItem = isset($dropdownItem['bImageAndLabel']) ? $dropdownItem['bImageAndLabel'] : '';

          if (!$itemActived && $isActiveDropdownItem) {
            $itemActived = $isActiveDropdownItem;
          }
          $dropdownItemsResult .= '              <li>'; 
          //$dropdownItemsResult .= '<a class="dropdown-item' . ($isActiveDropdownItem ? ' active' : '') . '" href="' . $escapeHtml($linkDropdownItem) . '">' . $escapeHtml($labelDropdownItem) . '</a>';
          $dropdownItemsResult .= '<a class="dropdown-item' . ($isActiveDropdownItem ? ' active' : '') . '" href="' . $escapeHtml($linkDropdownItem) . '">' . ($bImageAndLabelDropdownItem ? ($imgSVGDropdownItem ? $imgSVGDropdownItem . $escapeHtml($labelDropdownItem) : $escapeHtml($labelDropdownItem)) : ($imgSVGDropdownItem ? $imgSVGDropdownItem : $escapeHtml($labelDropdownItem))) . '</a>';
          //$dropdownItemsresult .= '<a class="dropdown-item" href="' . $escapeHtml($link) . '">' . $escapeHtml($label) . '</a>';
          $dropdownItemsResult .= '</li>' . PHP_EOL;
        }
      } 
  
      $isActiveItem = ($itemActived || $isActive);
      
      $result .= '          <li class="nav-item dropdown' . ($isActiveItem ? ' active' : '') . '">';
      //$result .= '<a class="nav-link dropdown-toggle' . ($isActiveItem ? ' active' : '') . '" href="' . $escapeHtml($link) . '" id="dropdown' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">';
      $result .= '<a class="nav-link dropdown-toggle link-dark text-decoration-none' . ($isActiveItem ? ' active' : '') . '" href="#" id="dropdown' . $id . '" data-bs-toggle="dropdown" aria-expanded="false">';
      $result .= ($bImageAndLabel ? ($imgSVG ? $imgSVG . ' ' . $escapeHtml($label) : $escapeHtml($label)) : ($imgSVG ? $imgSVG : $escapeHtml($label)));
     // $result .= ($imgSVG ? $imgSVG : $escapeHtml($label));
      $result .= '</a>' . PHP_EOL;
     
      $result .= '            <ul class="dropdown-menu text-small bg-light" aria-labelledby="dropdown' . $id . '">' . PHP_EOL;
      $result .= $dropdownItemsResult;
      $result .= '            </ul>' . PHP_EOL;
      $result .= '          </li>' . PHP_EOL;
        
    } else {        
           
      $result .= '        <li class="nav-item ' . ($isActive ? 'active' : '') . '">';
      $result .= '<a class="nav-link ' . ($isActive ? 'active' : '') . '" aria-current="page" href="' . $escapeHtml($link) . '">' . $escapeHtml($label) . '</a>';
      $result .= '</li>' . PHP_EOL;
    }

    return $result;
  }
}
