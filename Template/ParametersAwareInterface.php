<?php

namespace SfCod\EmailEngineBundle\Template;

use SfCod\EmailEngineBundle\Template\Params\ParameterInterface;

/**
 * Interface ParameterizedInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface ParametersAwareInterface
{
    /**
     * List template parameters
     *
     * @return ParameterInterface[]
     */
    public static function listParameters(): array;
}
