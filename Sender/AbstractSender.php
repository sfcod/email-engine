<?php

namespace SfCod\EmailEngineBundle\Sender;

/**
 * Class AbstractSender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
abstract class AbstractSender implements SenderInterface
{
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
