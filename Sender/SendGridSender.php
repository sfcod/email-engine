<?php

namespace SfCod\EmailEngineBundle\Sender;

use SendGrid;
use SfCod\EmailEngineBundle\Template\AttachmentsAwareInterface;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SendGridSender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
class SendGridSender extends AbstractSender
{
    /**
     * Send email to receiver
     *
     * @param TemplateInterface $template
     * @param string $email
     *
     * @return bool
     */
    public function send(TemplateInterface $template, string $email): bool
    {
        $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));

        $from = new SendGrid\Email(null, $template->getSenderEmail());
        $subject = $template->getSubject();
        $to = new SendGrid\Email(null, $email);
        $content = new SendGrid\Content('text/html', $template->getBody());
        $email = new SendGrid\Mail($from, $subject, $to, $content);

        if ($template instanceof AttachmentsAwareInterface) {
            foreach ($template->getAttachments() as $templateAttachment) {
                $attachment = new SendGrid\Attachment();
                $attachment->setContent(base64_encode($templateAttachment->getFileContent()));
                $attachment->setFilename($templateAttachment->getFileName());

                $email->addAttachment($attachment);
            }
        }

        /** @var SendGrid\Response $response */
        $response = $sendgrid->client->mail()->send()->post($email);

        return Response::HTTP_ACCEPTED === $response->statusCode();
    }
}
