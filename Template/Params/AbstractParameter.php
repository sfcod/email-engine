<?php

namespace SfCod\EmailEngineBundle\Template\Params;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AbstractParameter
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Template\Parameter
 */
abstract class AbstractParameter implements ParameterInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
}
