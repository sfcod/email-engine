<?php

namespace SfCod\EmailEngineBundle\Template;

use SfCod\EmailEngineBundle\Template\Attachments\AttachmentInterface;

/**
 * Interface AttachmentsAwareInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface AttachmentsAwareInterface
{
    /**
     * Get attachments array
     *
     * @return AttachmentInterface[]
     */
    public function getAttachments(): array;
}
