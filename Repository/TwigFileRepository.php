<?php

namespace SfCod\EmailEngineBundle\Repository;

use ReflectionClass;
use SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
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
     * Load template file
     *
     * @todo Remove hardcoded Data/template.html.twig path in 1.2
     *
     * @param string $directory
     *
     * @return \Twig_TemplateWrapper
     *
     * @throws RepositoryUnavailableException
     */
    protected function loadTemplate(string $directory)
    {
        try {
            $this->twig->setLoader(new \Twig_Loader_Chain([
                $this->twig->getLoader(),
                new \Twig_Loader_Filesystem('Data', $directory),
            ]));

            return $this->twig->load('template.html.twig');
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
     * @throws \ReflectionException
     */
    public function connect(TemplateInterface $template, array $arguments = [])
    {
        $filePath = (new ReflectionClass(get_class($template)))->getFileName();
        $directory = dirname($filePath);

        $this->template = $this->loadTemplate($directory);
    }
}
