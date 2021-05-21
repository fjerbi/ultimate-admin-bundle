<?php

namespace fjerbi\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AdminExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('markdown', [AdminRuntime::class, 'markdownToHTML'], ['is_safe'=> ['html']]),
        ];
    }
}