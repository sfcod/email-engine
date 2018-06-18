<?php

namespace SfCod\EmailEngineBundle\Sender;

use SfCod\EmailEngineBundle\Template\AttachmentsAwareInterface;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

/**
 * Class SwiftMailerSender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
class SwiftMailerSender extends AbstractSender
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * SwiftMailerSender constructor.
     *
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email to receiver
     *
     * @param TemplateInterface $template
     * @param array|string $emails
     *
     * @return bool
     */
    public function send(TemplateInterface $template, $emails): bool
    {
        $message = (new Swift_Message())
            ->setSubject($template->getSubject())
            ->setFrom($template->getSenderEmail())
            ->setTo($emails)
            ->setBody($template->getBody())
            ->setContentType('text/html');

        if ($template instanceof AttachmentsAwareInterface) {
            foreach ($template->getAttachments() as $templateAttachment) {
                $attachment = (new Swift_Attachment())
                    ->setFilename($templateAttachment->getFileName())
                    ->setBody($templateAttachment->getFileContent());

                $message->attach($attachment);
            }
        }

        return (bool)$this->mailer->send($message);
    }
}
