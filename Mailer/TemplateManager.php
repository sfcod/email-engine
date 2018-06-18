<?php

namespace SfCod\EmailEngineBundle\Mailer;

use SfCod\EmailEngineBundle\Template\ParametersAwareInterface;
use SfCod\EmailEngineBundle\Template\TemplateInterface;

/**
 * Email templates manager
 *
 * Class TemplateManager
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Mailer
 */
class TemplateManager
{
    /**
     * List of templates
     *
     * @var array
     */
    protected $templates = [];

    /**
     * TemplateManager constructor.
     *
     * @param TemplateInterface[] $templates
     */
    public function __construct(array $templates)
    {
        foreach ($templates as $template) {
            $this->templates[$template::getSlug()] = $template;
        }
    }

    /**
     * List all available templates
     *
     * @return array
     */
    public function listTemplates(): array
    {
        return $this->templates;
    }

    /**
     * List all available templates for choice using names
     *
     * @return array
     */
    public function listTemplatesChoice(): array
    {
        $templates = [];

        foreach ($this->templates as $slug => $template) {
            $templates[$slug] = $this->getTemplateName($slug);
        }

        return $templates;
    }

    /**
     * Get template
     *
     * @param string $slug
     *
     * @return string|null
     */
    public function getTemplateClass(string $slug): string
    {
        if (isset($this->templates[$slug])) {
            return $this->templates[$slug];
        }

        return null;
    }

    /**
     * Get email template name
     *
     * @param string $slug
     *
     * @return string
     */
    public function getTemplateName(string $slug): string
    {
        if (isset($this->templates[$slug])) {
            /** @var TemplateInterface $template */
            $template = $this->templates[$slug];

            return $template::getName();
        }

        return $slug;
    }

    /**
     * Get template description
     *
     * @param string $slug
     *
     * @return string
     */
    public function getTemplateDescription(string $slug): string
    {
        /** @var TemplateInterface $template */
        $template = $this->templates[$slug];

        return $template::getDescription();
    }

    /**
     * Get template parameters
     *
     * @param string $slug
     *
     * @return string
     */
    public function getTemplateParameters(string $slug): string
    {
        /** @var TemplateInterface|ParametersAwareInterface $template */
        $template = $this->templates[$slug];

        if (in_array(ParametersAwareInterface::class, class_implements($template)) && false === empty($template::listParameters())) {
            $params = 'Available parameters are:';

            foreach ($template::listParameters() as $parameter) {
                $params .= sprintf('<br/>- <b>{{%s}}</b>: %s;', $parameter::getName(), $parameter::getDescription());
            }

            return $params;
        }

        return 'No parameters available.';
    }
}
