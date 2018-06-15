<?php

namespace SfCod\EmailEngineBundle\Repository;

use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Email engine repository interface
 *
 * Interface RepositoryInterface
 *
 * @package SfCod\EmailEngineBundle\Repository
 */
interface RepositoryInterface
{
    /**
     * Repository initialize
     *
     * @param TemplateInterface $template
     * @param array $arguments
     */
    public function initialize(TemplateInterface $template, array $arguments = []);

    /**
     * Get subject template
     *
     * @param array $data
     *
     * @return string
     */
    public function getSubjectTemplate(array $data): string;

    /**
     * Get body template
     *
     * @param array $data
     *
     * @return string
     */
    public function getBodyTemplate(array $data): string;

    /**
     * Get sender name template
     *
     * @param array $data
     *
     * @return string
     */
    public function getSenderNameTemplate(array $data): string;

    /**
     * Get sender email template
     *
     * @param array $data
     *
     * @return string
     */
    public function getSenderEmailTemplate(array $data): string;
}
