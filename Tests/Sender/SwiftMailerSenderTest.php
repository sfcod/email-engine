<?php

namespace SfCod\EmailEngineBundle\Tests\Sender;

use PHPUnit\Framework\TestCase;
use SfCod\EmailEngineBundle\Example\TestEmailOptions;
use SfCod\EmailEngineBundle\Example\TestEmailTemplate;
use SfCod\EmailEngineBundle\Mailer\Mailer;
use SfCod\EmailEngineBundle\Repository\TwigFileRepository;
use SfCod\EmailEngineBundle\Sender\SymfonyMailerSender;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolver;
use SfCod\EmailEngineBundle\Tests\Data\LoadTrait;

/**
 * Class SwiftMailerSenderTest
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Tests\Sender
 */
class SwiftMailerSenderTest extends TestCase
{
    use LoadTrait;

    /**
     * Test sending
     *
     * @throws \ReflectionException
     */
    public function testSend()
    {
        $this->configure(SymfonyMailerSender::class, TwigFileRepository::class);

        $mailer = $this->container->get(Mailer::class);

        $template = $this->mockTemplate();
        $sender = $this->mockSender();

        $result = $sender->send($template, 'test@test.com');

        $this->assertTrue($result);
    }

    /**
     * Mock sender
     *
     * @return SymfonyMailerSender
     */
    private function mockSender(): SymfonyMailerSender
    {
        $sender = new SymfonyMailerSender(new \Swift_Mailer(new \Swift_Transport_NullTransport(new \Swift_Events_SimpleEventDispatcher())));

        return $sender;
    }

    /**
     * Mock template
     *
     * @return TestEmailTemplate
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     */
    private function mockTemplate(): TestEmailTemplate
    {
        $template = new TestEmailTemplate(new TestEmailOptions('message', 'filepath'));

        $filePath = (new \ReflectionClass(get_class($template)))->getFileName();
        $directory = dirname($filePath);

        $repository = new TwigFileRepository(new \Twig_Environment(new \Twig_Loader_Chain([
            new \Twig_Loader_Filesystem('Data', $directory),
        ])));
        $repository->connect($template);

        $template->setParameterResolver(new ParameterResolver($this->container));
        $template->setRepository($repository);

        return $template;
    }
}
