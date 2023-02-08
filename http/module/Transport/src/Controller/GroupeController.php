<?php
/**
 * This controleur is responsible for add/edit/delete 'groupe'. 
 * 
 * @package   module/Transport/src/Controller/GroupeController.php
 * @version   1.0
 * @copyright 2018-23 H.P.B
 * @author    Marsh <cyril.chable@outlook.be>
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Transport\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;
use Laminas\View\Model\ViewModel;

use Transport\Service\GroupeManager;

use Transport\Model\Groupe;
use Transport\Model\GroupeTable;
use Transport\Model\ViewGroupeTable;

use Transport\Form\GroupeForm;
use Transport\Form\SearchForm;


/**
 * 
 */
class GroupeController extends AbstractActionController
{
  
	/**
	 * Groupe table manager
	 * @var Transport\Model\GroupeTable
	 */
	private $groupeTable;

	/**
   * Groupe manager
   * @var Transport\Service\GroupeManager
   */
	private $groupeManager;

  /**
   * Application config.
   * @var type 
   */
  private $defaultRowPerPage;

  /**
   * Application config.
   * @var type 
   */
  private $stepRowPerPage;

  /**
   * Session container.
   * @var Laminas\Session\Container
   */
  private $sessionContainer;  

  /**
   * 
   */
	public function __construct(
		GroupeTable $groupeTable,
		GroupeManager $groupeManager,
    $defaultRowPerPage,
    $stepRowPerPage,
    $sessionContainer)
	{
    
		$this->groupeTable       = $groupeTable;
		$this->groupeManager     = $groupeManager;
    $this->defaultRowPerPage = $defaultRowPerPage;
    $this->stepRowPerPage    = $stepRowPerPage;
    $this->sessionContainer  = $sessionContainer;
	}

	/**
   * This is the default "index" action of the controller. 
   * It displays the list of groupe.
   */
	public function indexAction()
	{
 
    // Getting requested page number from the query
    // or to 1 if none is set, or the page is invalid
    $pageNumber = (int) $this->params()->fromQuery('page', 1);
    $pageNumber = ($pageNumber < 1) ? 1 : $pageNumber;
    
    // Getting requested number per page from the query
    // or to default if none is set, or the number per page is invalid
    $rowPerPage = (int) $this->params()->fromQuery('rowPerPage', 0);
    $rowPerPage = ($rowPerPage < 1) ? 0 : $rowPerPage;
   
    if ($rowPerPage) {
      
      $this->sessionContainer->rowPerPage = $rowPerPage;
    } else {
      if (isset($this->sessionContainer->rowPerPage)) {
        
        $rowPerPage = $this->sessionContainer->rowPerPage;
      } else {
        
        $this->sessionContainer->rowPerPage = $rowPerPage = $this->defaultRowPerPage;
      }
    }
    
    // Getting requested seach from the query
    // or to null if none is set
    $search = $this->params()->fromQuery('search', '');
    
    // Create search Form
    $formSearch = new SearchForm();

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $formSearch->setData($data);
      
      // Validate form
      if($formSearch->isValid()) {

        // Get filtered and validated data
        $data = $formSearch->getData();  
        $search = $data['search'];
      }
    }
    
    // Fill form with data from url
    if ($search) 
      $formSearch->setData(['search' => $search]);

    return new ViewModel([
      'formSearch'     => $formSearch,
      'module'         => 'groupe',
      'search'         => $search,
      'rowPerPage'     => $rowPerPage,
      'stepRowPerPage' => $this->stepRowPerPage,
      'groupes'        => $this->groupeTable->fetchAllPaginator($pageNumber, $rowPerPage, $search),
    ]); 
  }
  
  /*
   * This action displays a page allowing to add a new groupe
   */
  public function addAction()
  {

    // Create Form
    $form = new GroupeForm('create');

    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {
      
      // Fill in the form with POST data
      $data = $this->params()->fromPost();
      $form->setData($data);

      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Add groupe
        $result = $this->groupeManager->addGroupe($data);
        if ($result instanceof Groupe) {
          
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage("Le groupe '" . $data['NOMGROUPE'] . "' a été ajouté");          
          // Redirect to "index" page
          return $this->redirect()->toRoute('groupe', ['action'=>'index']); 
        } else {
          
          // Add a flash message Error
          switch($result){

            case 1:
              $this->flashMessenger()->addErrorMessage("Le groupe '" . $data['NOMGROUPE'] . "' existe déjà");
              break;
            
            default:
            $this->flashMessenger()->addErrorMessage("Erreur dans la sauvegarde du groupe '" . $data['NOMGROUPE'] . "'");
          }
        }
      } else {
        
        // Add a flash message Error
        $this->flashMessenger()->addMessage("Des données dans le formulaire sont erronées", 'error', 0);
      }
    } 
    
    return new ViewModel([
      'form' => $form,
    ]);  
  }
  
  /**
   * This action displays a page allowing to edit an existing groupe
   */
  public function editAction()
  {
    
		$id = (int)$this->params()->fromRoute('id', -1);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
    if ($id<1) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    $groupe = $this->groupeTable->findOneById($id);

    if ($groupe == null) {
      $this->getResponse()->setStatusCode(404);
      return;
    }
    
    // Create groupe form
    $form = new GroupeForm('update');
  
    // Check if user has submitted the form
    if ($this->getRequest()->isPost()) {

      // Fill in the form with POST data
      $data = $this->params()->fromPost();            
      $form->setData($data);
    
      // Validate form
      if($form->isValid()) {

        // Get filtered and validated data
        $data = $form->getData();

        // Update groupe
        if ($this->groupeManager->updateGroupe($groupe, $data)) {
				
          // Add a flash message Success
          $this->flashMessenger()->addSuccessMessage('Groupe modifié');
        } else {
				
          // Add a flash message Error
          $this->flashMessenger()->addErrorMessage("Un groupe '" . $data['NOMGROUPE'] . "' existe déjà");
        }
				
        // Redirect to "index" page
        return $this->redirect()->toRoute('groupe', ['action'=>'index']);
      }               
    } else {
		
      $form->setData($groupe->getArrayCopy());
    }

    return new ViewModel([
      'form' => $form,
    ]); 
  }
  
  /*
   * This action delete a groupe
   */
  public function deleteAction()
  {
	
    $id = (int)$this->params()->fromRoute('id', -1);
    if ($id<1) {
		
      $this->getResponse()->setStatusCode(404);
      return;
    }

    $groupe = $this->groupeTable->findOneById($id);
    if ($groupe == null) {
  
			$this->getResponse()->setStatusCode(404);
      return;
    }

    $nom = $groupe->getNom();
    
    // Delete véhicule
    $this->groupeManager->deleteGroupe($groupe);

    // Add a flash message
    $this->flashMessenger()->addWarningMessage("Le groupe '$nom' a été supprimé");

    // Redirect to "index" page
    return $this->redirect()->toRoute('groupe', ['action'=>'index']);      
  }
}