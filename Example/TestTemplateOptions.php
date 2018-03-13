<?php

namespace SfCod\EmailEngineBundle\Example;

use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;

/**
 * Class TestArguments
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Example
 */
class TestTemplateOptions implements TemplateOptionsInterface
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $filePath;

    /**
     * TestArguments constructor.
     *
     * @param string $message
     * @param string $filePath
     */
    public function __construct(string $message, string $filePath)
    {
        $this->message = $message;
        $this->filePath = $filePath;
    }
}
