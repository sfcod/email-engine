<?php

namespace SfCod\EmailEngineBundle\Template;

use SfCod\EmailEngineBundle\Repository\RepositoryInterface;

/**
 * Interface RepositoryAwareInterface
 *
 * @package SfCod\EmailEngineBundle\Template
 */
interface RepositoryAwareInterface
{
    /**
     * Set repository
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * Get repository
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;
}
