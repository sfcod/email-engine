<?php

namespace SfCod\EmailEngineBundle\Sender;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Sender with repository access
 *
 * Class AbstractRepositorySender
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
abstract class AbstractSender implements SenderInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
}
