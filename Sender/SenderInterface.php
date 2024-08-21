<?php

namespace SfCod\EmailEngineBundle\Sender;

use SfCod\EmailEngineBundle\Template\TemplateInterface;

/**
 * Sender interface
 *
 * Interface SenderInterface
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
interface SenderInterface
{
    /**
     * Set message options
     *
     * @param MessageOptionsInterface $options
     *
     * @return mixed
     */
    public function setOptions(MessageOptionsInterface $options);

    /**
     * Send email to receiver
     *
     * @param TemplateInterface $template
     * @param array|string $emails
     *
     * @return bool
     */
    public function send(TemplateInterface $template, $emails): bool;
}
