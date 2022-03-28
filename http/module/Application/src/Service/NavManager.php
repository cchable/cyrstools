<?php
/**
 * @package   : module/Application/src/Service/NavManager.php
 *
 * @purpose   : This service is responsible for determining which items should be in the main menu.
 *              The items may be different depending on whether the user is authenticated or not.
 * 
 * @copyright : Copyright (C) 2018, 21 H.P.B
 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Application\Service;


/*
 * 
 */
class NavManager
{
  
  /*
   * Url view helper.
   * @var Zend\View\Helper\Url
   */
  private $urlHelper;
      
  /*
   * Constructs the service.
   */  
  public function __construct($urlHelper) 
  {
    
    $this->urlHelper   = $urlHelper;
  }
    
  /*
   * This method returns menu items depending on whether user has logged in or not.
   */
  public function getMenuItems() 
  {
    
    $url = $this->urlHelper;
        
    $planningTransportItems = [];
    $planningTransportItems[] = [
      'id'    => 'transport',
      'label' => 'Transport',
      'link'  => $url('transport')
    ]; 
    $planningTransportItems[] = [
      'divider' => 'hr',
    ]; 
    
    $planningTransportItems[] = [
      'id'    => 'planning',
      'label' => 'Plannings',
      'link'  => $url('planning')
    ];
    $planningTransportItems[] = [
      'id'    => 'typeplanning',
      'label' => '->Types Plannings',
      'link'  => $url('typeplanning')
    ];
    $planningTransportItems[] = [
      'id'    => 'dateplanning',
      'label' => '->Dates Plannings',
      'link'  => $url('dateplanning')
    ];
    $planningTransportItems[] = [
      'id'    => 'heureplanning',
      'label' => '->Heures Plannings',
      'link'  => $url('heureplanning')
    ];    
    $planningTransportItems[] = [
      'divider' => 'hr',
    ];
    
    $planningTransportItems[] = [
      'id'    => 'trajet',
      'label' => 'Trajets',
      'link'  => $url('trajet')
    ];
    $planningTransportItems[] = [
      'id'    => 'etape',
      'label' => '->Etapes',
      'link'  => $url('etape')
    ];
    $planningTransportItems[] = [
      'divider' => 'hr',
    ];
    
    $planningTransportItems[] = [
      'id'    => 'groupe',
      'label' => 'Groupes',
      'link'  => $url('groupe')
    ];
    $planningTransportItems[] = [
      'divider' => 'hr',
    ];
    
    $planningTransportItems[] = [
      'id'    => 'chauffeur',
      'label' => 'Chauffeurs',
      'link'  => $url('chauffeur')
    ];
    $planningTransportItems[] = [
      'id'    => 'vehicule',
      'label' => '->Véhicules',
      'link'  => $url('vehicule')
    ];
    $planningTransportItems[] = [
      'id'    => 'indisponibilite',
      'label' => '->Indisponibilités',
      'link'  => $url('chauffeur')
    ];
    $planningTransportItems[] = [
      'divider' => 'hr',
    ];
    
    $planningTransportItems[] = [
      'id'    => 'ephemeride',
      'label' => 'Ephémérides',
      'link'  => $url('ephemeride')
    ];
    $planningTransportItems[] = [
      'id'    => 'anneescolaire',
      'label' => '->Années Scolaires',
      'link'  => $url('anneescolaire')
    ];
    
    //
    $userItems = [];
    $userItems[] = [
      'id'    => 'login',
      'label' => 'Login',
      'link'  => $url('home'),
    ];
    $userItems[] = [
      'divider' => 'hr',
    ];
    $userItems[] = [
      'id'    => 'info',
      'label' => 'Informations',
      'link'  => $url('home'),
    ];
    
    //
    $items = [];
    $items[] = [
      'id'    => 'home',
      'label' => 'Accueil',
      'link'  => $url('home')
    ];
    $items[] = [
      'id'       => 'planningtransport',
      'label'    => 'Planning Transport',
      'link'     => $url('planningtransport'),
      'dropdown' => $planningTransportItems,
    ];    
    $items[] = [
      'id'       => 'user',
      'label'    => 'User',
      'dropdown' => $userItems,
      'float'    => 'right',
      'imgsvg'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                      <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                      <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>'
    ];
            
    return $items;
  }
}
