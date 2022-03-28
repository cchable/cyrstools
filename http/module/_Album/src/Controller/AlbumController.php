<?php //module/Album/src/Controller/AlbumController.php

/**
 * @package   : 
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-20 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Album\Controller;

use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Form\AlbumForm;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $table;

    // Add this constructor:
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }
  
    public function indexAction()
    {
        // Grab the paginator from the AlbumTable:
        $paginator = $this->table->fetchAll(true);  
 
        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(10);

    return new ViewModel(['paginator' => $paginator]);
        
        /*
        return new ViewModel([
            'albums' => $this->table->fetchAll(),
        ]);
        */
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album');
    }

    public function editAction()
    {
        $ID = (int) $this->params()->fromRoute('ID', 0);

        if (0 === $ID) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getAlbum($ID);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['ID' => $ID, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveAlbum($album);

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
       $ID = (int) $this->params()->fromRoute('ID', 0);
        if (!$ID) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $ID = (int) $request->getPost('ID');
                $this->table->deleteAlbum($ID);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return [
            'ID'    => $ID,
            'album' => $this->table->getAlbum($ID),
        ];
    }
}