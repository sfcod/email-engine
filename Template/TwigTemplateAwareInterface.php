<?php

namespace SfCod\EmailEngineBundle\Template;

/**
 * Interface TwigTemplateAwareInterface
 * @package SfCod\EmailEngineBundle\Template
 */
interface TwigTemplateAwareInterface
{
    /**
     * Get twig template
     *
     * @return string
     */
    public function getTwigTemplate(): string;
}