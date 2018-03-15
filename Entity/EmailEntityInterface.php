<?php

namespace SfCod\EmailEngineBundle\Entity;

/**
 * Interface EmailEntityInterface
 * @package SfCod\EmailEngineBundle\Entity
 */
interface EmailEntityInterface
{
    /**
     * Get email title (subject)
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get email body
     *
     * @return string
     */
    public function getBody(): string;
}