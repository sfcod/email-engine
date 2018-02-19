<?php

namespace SfCod\EmailEngineBundle\Template\Attachments;

use SfCod\EmailEngineBundle\Template\OptionsInterface;

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
     * @var OptionsInterface
     */
    protected $options;

    /**
     * AttachmentInterface constructor.
     *
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options)
    {
        $this->options = $options;
    }
}
