<?php

namespace SfCod\EmailEngineBundle\Repository;

use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractRepository
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RepositoryInterface constructor.
     *
     * @param ContainerInterface $container
     * @param TemplateInterface $template
     * @param array $arguments
     */
    public function __construct(ContainerInterface $container, TemplateInterface $template, array $arguments)
    {
        $this->container = $container;
    }
}
