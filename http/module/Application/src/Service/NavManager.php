<?php 
/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not. 
 * 
 * @package   module/Application/src/Service/NavManager.php
 * @version   1.0.2
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
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
/*        
    $planningTransportItems = [];
    $planningTransportItems[] = [
      'id'    => 'planningtransport',
      'label' => 'PlanningTransport',
      'link'  => $url('planningtransport')
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
*/    
    //
    $transportItems = [];
    $transportItems[] = [
      'id'    => 'dashboard',
      'label' => 'Dashboard',
      'imgsvg'         => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#dashboard"/></svg>',
      'bImageAndLabel' => true,
      'link'  => $url('dashboard')
    ]; 
    $transportItems[] = [
      'divider' => 'hr',
    ]; 
    $transportItems[] = [
      'id'             => 'chauffeur',
      'label'          => 'Chauffeurs',
      'imgsvg'         => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#blue_car_steering_whell"/></svg>',
      'bImageAndLabel' => true,
      'link'           => $url('chauffeur'),
    ];
    $transportItems[] = [
      'id'     => 'indisponibilitechauffeur',
      'label'  => 'Indisponibilités des chauffeurs',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#arrow_entrance_exit_internet_log_out_security_icon"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('indisponibilitechauffeur')
    ];
    $transportItems[] = [
      'divider' => 'hr',
    ];
    $transportItems[] = [
      'id'    => 'anneescolaire',
      'label' => 'Années scolaires',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#day_appointment_schedule_month_event_date_calendar"/></svg>',
      'bImageAndLabel' => true,
      'link'  => $url('anneescolaire')
    ];
    $transportItems[] = [
      'id'    => 'ephemeride',
      'label' => 'Ephémérides',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#if_weather"/></svg>',
      'bImageAndLabel' => true,
      'link'  => $url('ephemeride')
    ];
    $transportItems[] = [
      'divider' => 'hr',
    ];
    $transportItems[] = [
      'id'     => 'vehicule',
      'label'  => 'Véhicule',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#school-bus"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('vehicule')
    ];
    $transportItems[] = [
      'id'     => 'marque',
      'label'  => 'Marques',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#box_design_brand_icon"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('marque')
    ];
    $transportItems[] = [
      'id'     => 'typevehicule',
      'label'  => 'Types de véhicule',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#file_type_registry"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('typevehicule')
    ];
    $transportItems[] = [
      'id'     => 'indisponibilitevehicule',
      'label'  => 'Indisponibilités des véhicules',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#arrow_entrance_exit_internet_log_out_security_icon"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('indisponibilitevehicule')
    ];    
    $transportItems[] = [
      'divider' => 'hr',
    ];
    $transportItems[] = [
      'id'     => 'trajet',
      'label'  => 'Trajets',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#1-15"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('trajet')
    ];  
    $transportItems[] = [
      'id'     => 'etape',
      'label'  => 'Etapes',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#location_map_pin_mark"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('etape')
    ];
    $transportItems[] = [
      'divider' => 'hr',
    ];
    $transportItems[] = [
      'id'     => 'organisation',
      'label'  => 'Organisations',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#calendar-event-636"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('organisation')
    ];
    $transportItems[] = [
      'id'     => 'groupe',
      'label'  => 'Groupes',
      'imgsvg' => '<svg class="bi text-muted flex-shrink-0 me-1" width="1em" height="1em"><use xlink:href="#account_group_team_user"/></svg>',
      'bImageAndLabel' => true,
      'link'   => $url('groupe')
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
    
/*    
    $items[] = [
      'id'       => 'planningtransport',
      'label'    => 'Planning Transport',
      'link'     => $url('planningtransport'),
      'dropdown' => $planningTransportItems,
    ];
*/    
    $items[] = [
      'id'       => 'transport',
      'label'    => 'Transport',
      'link'     => $url('dashboard'),
      'dropdown' => $transportItems,
    ];
    $items[] = [
      'id'              => 'user',
      'label'           => 'User',
      'dropdown'        => $userItems,
      'float'           => 'right',
      'imgsvg'          => 
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">' . 
          '<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>'.
          '<path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>'.
        '</svg>',
       'bImageAndLabel' => true,
    ];
            
    return $items;
  }
}
