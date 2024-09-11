<?php

namespace SfCod\EmailEngineBundle\Sender;

use SfCod\EmailEngineBundle\Template\AttachmentsAwareInterface;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class SwiftMailerSender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
class SymfonyMailerSender extends AbstractSender
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * SwiftMailerSender constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email to receiver
     *
     * @param TemplateInterface $template
     * @param array|string $emails
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function send(TemplateInterface $template, $emails): bool
    {
        $message = (new Email())
            ->subject($template->getSubject())
            ->from($template->getSenderEmail())
            ->to($emails)
            ->html($template->getBody());

        if ($template instanceof AttachmentsAwareInterface) {
            foreach ($template->getAttachments() as $templateAttachment) {
                $message->attach($templateAttachment->getFileContent(), $templateAttachment->getFileName());
            }
        }

        $this->mailer->send($message);

        return true;
    }
}
