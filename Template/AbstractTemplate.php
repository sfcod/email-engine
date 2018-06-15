<?php

namespace SfCod\EmailEngineBundle\Template;

use SfCod\EmailEngineBundle\Repository\RepositoryInterface;
use SfCod\EmailEngineBundle\Template\Attachments\AttachmentInterface;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolverInterface;

/**
 * Abstract argumentative, repository aware template
 *
 * Class AbstractTemplate
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Template
 */
abstract class AbstractTemplate implements TemplateInterface, RepositoryAwareInterface, ParametersAwareInterface, AttachmentsAwareInterface
{
    /**
     * Template data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Template options
     *
     * @var TemplateOptionsInterface
     */
    protected $options;

    /**
     * Template repository
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * Parameter resolver
     *
     * @var ParameterResolverInterface
     */
    protected $parameterResolver;

    /**
     * AbstractTemplate constructor.
     *
     * @param TemplateOptionsInterface $options
     */
    public function __construct(TemplateOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * Set parameter resolver
     *
     * @param ParameterResolverInterface $parameterResolver
     *
     * @return mixed|void
     */
    public function setParameterResolver(ParameterResolverInterface $parameterResolver)
    {
        $this->parameterResolver = $parameterResolver;
    }

    /**
     * Add data for template
     *
     * @param $key
     * @param $value
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Set repository
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Get email's subject
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->repository->getSubjectTemplate($this->getData());
    }

    /**
     * Get email's body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->repository->getBodyTemplate($this->getData());
    }

    /**
     * Get email's sender name
     *
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->repository->getSenderNameTemplate($this->getData());
    }

    /**
     * Get email's sender email
     *
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->repository->getSenderEmailTemplate($this->getData());
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return self::PRIORITY_NORMAL;
    }

    /**
     * Get template attachments
     *
     * @return AttachmentInterface[]
     */
    public function getAttachments(): array
    {
        $attachments = [];

        foreach (static::listAttachments() as $attachment) {
            /** @var AttachmentInterface $attachment */
            $attachment = new $attachment($this->options);

            $attachments[$attachment->getFileName()] = $attachment;
        }

        return $attachments;
    }

    /**
     * List template attachments
     *
     * @return AttachmentInterface[]
     */
    public static function listAttachments(): array
    {
        return [];
    }

    /**
     * Get arguments list
     *
     * @return array
     */
    protected function getData(): array
    {
        if (empty($this->data)) {
            foreach (static::listParameters() as $parameterClass) {
                $this->data[$parameterClass::getName()] = $this->parameterResolver->get($parameterClass, $this->options);
            }
        }

        return $this->data;
    }
}
