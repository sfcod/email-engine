<?php

namespace SfCod\EmailEngineBundle\Example;

use SfCod\EmailEngineBundle\Example\Attachments\TestImage;
use SfCod\EmailEngineBundle\Example\Params\TestMessage;
use SfCod\EmailEngineBundle\Template\AbstractTemplate;
use SfCod\EmailEngineBundle\Template\Params\ParameterInterface;

/**
 * Class TestEmailTemplate
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Example
 */
class TestEmailTemplate extends AbstractTemplate
{
    /**
     * Get email template slug
     *
     * @return string
     */
    public static function getSlug(): string
    {
        return 'test_email_slug';
    }

    /**
     * Get email template name
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'Test email template';
    }

    /**
     * Get email template description
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Email template for example';
    }

    /**
     * List parameters
     *
     * @return ParameterInterface[]
     */
    public static function listParameters(): array
    {
        return [
            TestMessage::class, // {message}
        ];
    }

    /**
     * List attachments
     *
     * @return array
     */
    public static function listAttachments(): array
    {
        return [
            TestImage::class,
        ];
    }
}
