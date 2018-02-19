<?php

namespace SfCod\EmailEngineBundle;

use SfCod\EmailEngineBundle\DependencyInjection\EmailEngineExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EmailEngineBundle
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle
 */
class SfCodEmailEngineBundle extends Bundle
{
    /**
     * Get container extension
     *
     * @return null|EmailEngineExtension|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new EmailEngineExtension();
    }
}
