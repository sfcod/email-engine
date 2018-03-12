<?php

namespace SfCod\EmailEngineBundle\Template\Attachments;

use SfCod\EmailEngineBundle\Template\TemplateOptionsInterface;

/**
 * Class AbstractAttachment
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Template\Attachments
 */
abstract class AbstractAttachment implements AttachmentInterface
{
    /**
     * @var TemplateOptionsInterface
     */
    protected $options;

    /**
     * AttachmentInterface constructor.
     *
     * @param TemplateOptionsInterface $options
     */
    public function __construct(TemplateOptionsInterface $options)
    {
        $this->options = $options;
    }
}
