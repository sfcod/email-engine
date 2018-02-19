<?php

namespace SfCod\EmailEngineBundle\Template\Attachments;

use SfCod\EmailEngineBundle\Template\OptionsInterface;

/**
 * Email attachment interface
 *
 * Interface AttachmentInterface
 *
 * @package SfCod\EmailEngineBundle\Template\Attachments
 */
interface AttachmentInterface
{
    /**
     * AttachmentInterface constructor.
     *
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options);

    /**
     * Get attachment name
     *
     * @return string
     */
    public function getFileName(): string;

    /**
     * Get attachment content
     *
     * @return string
     */
    public function getFileContent(): string;
}
