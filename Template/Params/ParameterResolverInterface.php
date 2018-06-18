<?php

namespace SfCod\EmailEngineBundle\Template\Params;

use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;

/**
 * Interface ParameterResolverInterface
 *
 * @package SfCod\EmailEngineBundle\Template\Params
 */
interface ParameterResolverInterface
{
    /**
     * Get parameter's value
     *
     * @param string $name
     * @param TemplateOptionsInterface $options
     *
     * @return mixed
     */
    public function getParameterValue(string $name, TemplateOptionsInterface $options);
}
