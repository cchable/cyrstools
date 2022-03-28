<?php
/**
 * @package   : module/Application/src/View/Helper/Menu.php
 *
 * @purpose   : This view helper class displays a menu bar.
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
    
    $result  = '<nav class="navbar navbar-default" role="navigation">' . PHP_EOL;
    $result .= ' <div class="collapse navbar-collapse navbar-ex1-collapse">' . PHP_EOL;
    $result .= '  <ul class="nav navbar-nav">' . PHP_EOL;
    
    // Render items
    foreach ($this->items as $item) {
      if(!isset($item['float']) || $item['float']=='left')
        $result .= $this->renderItem($item);
    }
    
    $result .= '  </ul>' . PHP_EOL;
    $result .= '  <ul class="nav navbar-nav navbar-right">' . PHP_EOL;
    
    // Render items
    foreach ($this->items as $item) {
      if(isset($item['float']) && $item['float']=='right')
        $result .= $this->renderItem($item);
    }
    
    $result .= '  </ul>' . PHP_EOL;
    $result .= ' </div>' . PHP_EOL;
    $result .= '</nav>' . PHP_EOL;
    
    return $result;
  }
    
  /*
   * Renders an item.
   * @param array $item The menu item info.
   * @return string HTML code of the item.
   */
  protected function renderItem($item) 
  {
		
    $id = isset($item['id']) ? $item['id'] : '';
    $isActive = ($id==$this->activeItemId);
    $label = isset($item['label']) ? $item['label'] : '';
         
    $result = ''; 
 
    $escapeHtml = $this->getView()->plugin('escapeHtml');
    
    if (isset($item['dropdown'])) {
        
      $dropdownItems = $item['dropdown'];
      
      $result .= '   <li class="dropdown ' . ($isActive?'active':'') . '">';
      $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
      $result .= $escapeHtml($label) . ' <b class="caret"></b>';
      $result .= '</a>' . PHP_EOL;
     
      $result .= '    <ul class="dropdown-menu">' . PHP_EOL;
      foreach ($dropdownItems as $item) {
        $link = isset($item['link']) ? $item['link'] : '#';
        $label = isset($item['label']) ? $item['label'] : '';

        $result .= '     <li>'; 
        $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
        $result .= '</li>' . PHP_EOL;
      }
      $result .= '    </ul>' . PHP_EOL;
      $result .= '   </li>' . PHP_EOL;
        
    } else {        
      $link = isset($item['link']) ? $item['link'] : '#';
      
      $result .= $isActive ? '   <li class="active">':'<li>';
      $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
      $result .= '</li>' . PHP_EOL;
    }

    return $result;
  }
}
