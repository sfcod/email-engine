<?php

namespace SfCod\EmailEngineBundle\Template\Params;

use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ParameterResolver
 *
 * @package SfCod\EmailEngineBundle\Template\Params
 */
class ParameterResolver implements ParameterResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ParameterResolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get parameter's value
     *
     * @param string $name
     * @param TemplateOptionsInterface $options
     *
     * @return mixed
     */
    public function getParameterValue(string $name, TemplateOptionsInterface $options)
    {
        return $this->container->get($name)->getValue($options);
    }
}
