<?php

namespace SfCod\EmailEngineBundle\Sender;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AbstractSender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
abstract class AbstractSender implements SenderInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var MessageOptionsInterface|null
     */
    protected $options;

    /**
     * @param MessageOptionsInterface $options
     */
    public function setOptions(MessageOptionsInterface $options)
    {
        $this->options = $options;
    }
}
