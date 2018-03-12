<?php

namespace SfCod\EmailEngineBundle\Template\Params;

use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;

/**
 * Template parameter interface
 *
 * Interface ParameterInterface
 *
 * @package SfCod\EmailEngineBundle\Template\Parameter
 */
interface ParameterInterface
{
    /**
     * Get parameter name
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Get parameter description
     *
     * @return string
     */
    public static function getDescription(): string;

    /**
     * Get parameter value
     *
     * @param TemplateOptionsInterface $options
     *
     * @return mixed
     */
    public function getValue(TemplateOptionsInterface $options);
}
