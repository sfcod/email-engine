<?php

namespace SfCod\EmailEngineBundle\Template;

/**
 * Email template interface
 *
 * Interface TemplateInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface TemplateInterface
{
    /**
     * Low priority
     */
    public const PRIORITY_LOW = 0;

    /**
     * Normal priority
     */
    public const PRIORITY_NORMAL = 10;

    /**
     * High priority
     */
    public const PRIORITY_HIGH = 20;

    /**
     * TemplateInterface constructor.
     *
     * @param TemplateOptionsInterface $options
     */
    public function __construct(TemplateOptionsInterface $options);

    /**
     * Get template priority
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Get email template slug
     *
     * @return string
     */
    public static function getSlug(): string;

    /**
     * Get email template name
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Get email template description
     *
     * @return string
     */
    public static function getDescription(): string;

    /**
     * Get email's subject
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get email's body
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Get email's sender name
     *
     * @return string
     */
    public function getSenderName(): string;

    /**
     * Get email's sender email
     *
     * @return string
     */
    public function getSenderEmail(): string;
}
