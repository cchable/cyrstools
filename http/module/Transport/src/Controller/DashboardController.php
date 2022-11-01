<?php
/**
 * @package   : module/Transport/src/Controller/TransportController.php
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-22 H.P.B
 * 
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

//use Transport\Service\TransportManager;

use Transport\Model\Chauffeur;
use Transport\Model\ChauffeurTable;
use Transport\Model\Marque;
use Transport\Model\MarqueTable;
use Transport\Model\TypeVehicule;
use Transport\Model\TypeVehiculeTable;

use Transport\Form\DashboardForm;
//use Transport\Form\SearchForm;


/*
 * 
 */
class DashboardController extends AbstractActionController
{

  /*
   * Chauffeur table manager
   * @var Plannigbus\Model\ChauffeurTable
   */
  private $chauffeurTable; 
  
  /*
   * Marque table manager
   * @var Transport\Model\MarqueTable
   */
  private $marqueTable;
  
  /*
   * Marque table manager
   * @var Transport\Model\TypeVehiculeTable
   */
  private $typeVehiculeTable; 

  /*
   * Session container.
   * @var Laminas\Session\Container
   */
  private $sessionContainer;  

	/*
	 * 
	 */
	public function __construct(
		ChauffeurTable    $chauffeurTable,
		MarqueTable       $marqueTable,
		TypeVehiculeTable $typeVehiculeTable,
		$sessionContainer
    )
	{
  
    $this->chauffeurTable    = $chauffeurTable;
    $this->marqueTable       = $marqueTable;
    $this->typeVehiculeTable = $typeVehiculeTable;
    $this->sessionContainer  = $sessionContainer;
	}

	/*
	 * This is the default "index" action of the controller. 
	 * It displays the dashboard.
	 */
	public function indexAction()
	{

    $numberOfRowsChauffeur    = $this->chauffeurTable->getNumberOfRows();
    $numberOfRowsMarque       = $this->marqueTable->getNumberOfRows();
    $numberOfRowsTypeVehicule = $this->typeVehiculeTable->getNumberOfRows();
    
    return new ViewModel([
      'numberOfChauffeur'    => $numberOfRowsChauffeur['COUNT'],
      'numberOfMarque'       => $numberOfRowsMarque['COUNT'],
      'numberOfTypeVehicule' => $numberOfRowsTypeVehicule['COUNT'],
    ]); 
  }
}