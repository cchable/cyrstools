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

namespace Album\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class Album
{
    public $ID;
    public $ARTIST;
    public $TITLE;

    private $inputFilter;
    
    public function exchangeArray(array $data)
    {
        $this->ID     = !empty($data['ID'])     ? $data['ID'] : null;
        $this->ARTIST = !empty($data['ARTIST']) ? $data['ARTIST'] : null;
        $this->TITLE  = !empty($data['TITLE'])  ? $data['TITLE'] : null;
    }
    
    public function getArrayCopy()
    {
        return [
            'ID'     => $this->ID,
            'ARTIST' => $this->ARTIST,
            'TITLE'  => $this->TITLE,
        ];
    }    
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'ID',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'ARTIST',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'TITLE',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}