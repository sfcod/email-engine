<?php

namespace SfCod\EmailEngineBundle\Repository;

use SfCod\EmailEngineBundle\Entity\EmailEntityInterface;
use SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DbRepository
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Repository
 */
class DbRepository implements RepositoryInterface
{
    /**
     * @var EmailEntityInterface
     */
    protected $email;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Twig
     */
    protected $twig;

    /**
     * DbRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Twig $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }

    /**
     * Repository initialize
     *
     * @param TemplateInterface $template
     * @param array $arguments
     *
     * @throws RepositoryUnavailableException
     */
    public function initialize(TemplateInterface $template, array $arguments = [])
    {
        if (false === isset($arguments['entity'], $arguments['attribute'])) {
            throw new RepositoryUnavailableException('DbRepository configuration incorrect, "entity" and "attribute" must be configured.');
        }

        $this->email = $this->em
            ->getRepository($arguments['entity'])
            ->findOneBy([$arguments['attribute'] => get_class($template)::getSlug()]);

        if (is_null($this->email)) {
            throw new RepositoryUnavailableException(sprintf('Record with slug "%s" does not exists.', get_class($template)::getSlug()));
        }
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
        return $this->applyArguments($this->email->getTitle(), $data);
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
        return $this->applyArguments($this->email->getBody(), $data);
    }

    /**
     * Get sender name template
     *
     * @param array $data
     *
     * @return string
     */
    public function getSenderNameTemplate(array $data): string
    {
        return getenv('SENDER_NAME');
    }

    /**
     * Get sender email template
     *
     * @param array $data
     *
     * @return string
     */
    public function getSenderEmailTemplate(array $data): string
    {
        return getenv('SENDER_EMAIL');
    }

    /**
     * Apply arguments to string
     *
     * @param string $str
     * @param array $args
     *
     * @return string
     *
     * @throws \Throwable
     */
    protected function applyArguments(string $str, array $args): string
    {
        try {
            return $this->twig
                ->createTemplate('{% autoescape false %}' . $str . '{% endautoescape %}')
                ->render($args);
        } catch (\Throwable $e) {
            throw new RepositoryUnavailableException($e->getMessage());
        }
    }
}
