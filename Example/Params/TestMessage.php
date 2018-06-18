<?php

namespace SfCod\EmailEngineBundle\Example\Params;

use SfCod\EmailEngineBundle\Example\TestTemplateOptions;
use SfCod\EmailEngineBundle\Template\Params\ParameterInterface;
use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;

/**
 * Class TestMessage
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Example\Params
 */
class TestMessage implements ParameterInterface
{
    /**
     * Get parameter name
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'message';
    }

    /**
     * Get parameter value
     *
     * @param TestTemplateOptions|TemplateOptionsInterface $options
     *
     * @return mixed
     */
    public function getValue(TemplateOptionsInterface $options)
    {
        return '<b>' . $options->message . '</b>';
    }

    /**
     * Get parameter description
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Test message.';
    }
}
