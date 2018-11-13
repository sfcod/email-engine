<?php

namespace SfCod\EmailEngineBundle\Repository;

use SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use SfCod\EmailEngineBundle\Template\TwigTemplateAwareInterface;
use Twig_Environment;

/**
 * Class TwigFileRepository
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Repository
 */
class TwigFileRepository implements RepositoryInterface
{
    /**
     * Twig template
     *
     * @var string
     */
    protected $template;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * TwigFileRepository constructor.
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get subject template
     *
     * @param array $data
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function getSubjectTemplate(array $data): string
    {
        return $this->renderBlock('subject', $data);
    }

    /**
     * Get body template
     *
     * @param array $data
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function getBodyTemplate(array $data): string
    {
        return $this->renderBlock('content', $data);
    }

    /**
     * Get sender name template
     *
     * @param array $data
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function getSenderNameTemplate(array $data): string
    {
        if ($this->template->hasBlock('sender_name')) {
            return $this->renderBlock('sender_name', $data);
        }

        return getenv('SENDER_NAME');
    }

    /**
     * Get sender email template
     *
     * @param array $data
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function getSenderEmailTemplate(array $data): string
    {
        if ($this->template->hasBlock('sender_email')) {
            return $this->renderBlock('sender_email', $data);
        }

        return getenv('SENDER_EMAIL');
    }

    /**
     * Render twig template
     *
     * @param string $block
     * @param array $data
     *
     * @return string
     *
     * @throws \Throwable
     */
    protected function renderBlock(string $block, array $data): string
    {
        try {
            return $this->template->renderBlock($block, $data);
        } catch (\Throwable $e) {
            throw new RepositoryUnavailableException($e->getMessage());
        }
    }

    /**
     * Repository initialize
     *
     * @param TemplateInterface $template
     * @param array $arguments
     *
     * @throws RepositoryUnavailableException
     */
    public function connect(TemplateInterface $template, array $arguments = [])
    {
        if (false === $template instanceof TwigTemplateAwareInterface) {
            throw new RepositoryUnavailableException('Template should implement TwigTemplateAwareInterface to work with TwigFileRepository.');
        }

        try {
            if (file_exists($template->getTwigTemplate())) {
                $directory = dirname($template->getTwigTemplate());

                $this->twig->setLoader(new \Twig_Loader_Chain([
                    $this->twig->getLoader(),
                    new \Twig_Loader_Filesystem(basename($directory), dirname($directory)),
                ]));

                $templateName = basename($template->getTwigTemplate());
            } else {
                $templateName = $template->getTwigTemplate();
            }

            $this->twig->setCache(false);

            $this->template = $this->twig->load($templateName);
        } catch (\Throwable $e) {
            throw new RepositoryUnavailableException($e->getMessage());
        }
    }
}
