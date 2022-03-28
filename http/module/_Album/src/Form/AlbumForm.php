<?php

/**
 * @package   : 
 *
 * @purpose   :
 * 
 * 
 * @copyright : Copyright (C) 2018-20 H.P.B
 * @license   : GNU General Public License version 2 or later; see LICENSE.txt
 **/

namespace Album\Form;

use Laminas\Form\Form;

class AlbumForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('album');

        $this->add([
            'name' => 'ID',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'TITLE',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name' => 'ARTIST',
            'type' => 'text',
            'options' => [
                'label' => 'Artist',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}