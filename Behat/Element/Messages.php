<?php

namespace FSi\Bundle\AdminBundle\Behat\Element;

use Exception;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Messages extends Element
{
    protected $selector = '#messages';

    public function getMessageText($type)
    {
        $alerts = $this->findAll('css', sprintf('.alert-%s', $type));
        if (count($alerts) < 1) {
            throw new Exception(sprintf("Unable to find any alert with type '%s'", $type));
        }

        return implode("\n", array_map(function ($alert) {
            return $alert->getText();
        }, $alerts));
    }
}
