<?php

namespace SfCod\EmailEngineBundle\Template;

/**
 * Interface TwigTemplateAwareInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface TwigTemplateAwareInterface
{
    /**
     * Get twig template.
     * Set twig tempale path. For example, your templates are into folder "templates"(Default templates folder for SF4).
     * Then you should return "my_twig_template.html.twig". Template example:
     *  {% block subject %}Email subject{% endblock subject %}
     *  {% block content %}Email content{% endblock content %}.
     * Here SfCod\EmailEngineBundle\Repository\TwigFileRepository you can find all available twig blocks.
     *
     * @return string
     */
    public function getTwigTemplate(): string;
}
