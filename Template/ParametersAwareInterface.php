<?php

namespace SfCod\EmailEngineBundle\Template;

use SfCod\EmailEngineBundle\Template\Params\ParameterInterface;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolverInterface;

/**
 * Interface ParameterizedInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface ParametersAwareInterface
{
    /**
     * Set parameter resolver
     *
     * @param ParameterResolverInterface $parameterResolver
     *
     * @return mixed
     */
    public function setParameterResolver(ParameterResolverInterface $parameterResolver);

    /**
     * List template parameters
     *
     * @return ParameterInterface[]
     */
    public static function listParameters(): array;
}
