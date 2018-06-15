<?php

namespace SfCod\EmailEngineBundle\Template\Params;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ParameterResolver
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
     * Get parameter
     *
     * @param string $name
     * @param array $options
     *
     * @return mixed
     */
    public function getParameter(string $name, array $options = [])
    {
        return $this->container->get($name)->getValue($options);
    }
}