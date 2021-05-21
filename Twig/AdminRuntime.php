<?php

namespace fjerbi\AdminBundle\Twig;

use Parsedown;
use Twig\Extension\RuntimeExtensionInterface;

class AdminRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
    }

    public function markdownToHTML($text)
    {
        $parsedown = new Parsedown();
        return $parsedown->parse($text);
    }
}