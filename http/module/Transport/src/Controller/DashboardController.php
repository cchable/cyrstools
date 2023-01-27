<?php
/**
 * This controleur is responsible for the Dashboard. 
 * 
 * @package   module/Transport/src/Controller/DashboardController.php
 * @version   1.0.1
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/
 
namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use Transport\Model\Chauffeur;
use Transport\Model\ChauffeurTable;
use Transport\Model\Marque;
use Transport\Model\MarqueTable;
use Transport\Model\TypeVehicule;
use Transport\Model\TypeVehiculeTable;
use Transport\Model\Vehicule;
use Transport\Model\VehiculeTable;

use Transport\Form\DashboardForm;


/**
 * 
 */
class DashboardController extends AbstractActionController
{

  /**
   * Chauffeur table manager
   * @var Plannigbus\Model\ChauffeurTable
   */
  private $chauffeurTable; 
  
  /**
   * Marque table manager
   * @var Transport\Model\MarqueTable
   */
  private $marqueTable;
  
  /**
   * TypeVehicule table manager
   * @var Transport\Model\TypeVehiculeTable
   */
  private $typeVehiculeTable;
  
  /**
   * VehiculeTable table manager
   * @var Transport\Model\VehiculeTable
   */
  private $vehiculeTable; 

  /**
   * Session container.
   * @var Laminas\Session\Container
   */
  private $sessionContainer;  

	/**
	 * 
	 */
	public function __construct(
		ChauffeurTable    $chauffeurTable,
		MarqueTable       $marqueTable,
		TypeVehiculeTable $typeVehiculeTable,
		VehiculeTable     $vehiculeTable,
		$sessionContainer
    )
	{
  
    $this->chauffeurTable    = $chauffeurTable;
    $this->marqueTable       = $marqueTable;
    $this->typeVehiculeTable = $typeVehiculeTable;
    $this->vehiculeTable     = $vehiculeTable;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
	 * This is the default "index" action of the controller. 
	 * It displays the dashboard.
	 */
	public function indexAction()
	{
    
    return new ViewModel([
      'numberOfChauffeur'    => $this->chauffeurTable->getNumberOfRows(),
      'numberOfMarque'       => $this->marqueTable->getNumberOfRows(),
      'numberOfTypeVehicule' => $this->typeVehiculeTable->getNumberOfRows(),
      'numberOfVehicule'     => $this->vehiculeTable->getNumberOfRows(),
    ]); 
  }
}