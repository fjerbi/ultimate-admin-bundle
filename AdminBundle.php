<?php



namespace fjerbi\AdminBundle;


use fjerbi\AdminBundle\DependencyInjection\fjerbiAdminExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class AdminBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new fjerbiAdminExtension();
    }
}
