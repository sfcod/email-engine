<?php

namespace SfCod\EmailEngineBundle\Template\Params;

/**
 * Interface ParameterResolverInterface
 * @package SfCod\EmailEngineBundle\Template\Params
 */
interface ParameterResolverInterface
{
    /**
     * Get parameter
     *
     * @param string $name
     * @param array $options
     *
     * @return mixed
     */
    public function getParameter(string $name, array $options = []);
}