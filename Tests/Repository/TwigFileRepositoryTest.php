<?php

namespace SfCod\EmailEngineBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use SfCod\EmailEngineBundle\Example\TestEmailOptions;
use SfCod\EmailEngineBundle\Example\TestEmailTemplate;
use SfCod\EmailEngineBundle\Repository\TwigFileRepository;

/**
 * Class TwigFileRepositoryTest
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Tests\Repository
 */
class TwigFileRepositoryTest extends TestCase
{
    /**
     * Test get subject
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testSubject()
    {
        $text = uniqid('subject_');

        $repository = $this->mockRepository();

        $subject = $repository->getSubjectTemplate([
            'subject' => $text,
        ]);

        $this->assertEquals($text, $subject);
    }

    /**
     * Test get body
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testBody()
    {
        $text = uniqid('content_');

        $repository = $this->mockRepository();

        $content = $repository->getBodyTemplate([
            'message' => $text,
        ]);

        $this->assertEquals($text, $content);
    }

    /**
     * Test sender name
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testSenderName()
    {
        $text = uniqid('content_');

        $repository = $this->mockRepository();

        $senderName = $repository->getSenderNameTemplate([
            'sender_name' => $text,
        ]);

        $this->assertEquals($text, $senderName);
    }

    /**
     * Test sender email
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testSenderEmail()
    {
        $repository = $this->mockRepository();

        $senderEmail = $repository->getSenderEmailTemplate([]);

        $this->assertEquals('test@sender.com', $senderEmail);
    }

    /**
     * Mock repository
     *
     * @return TwigFileRepository
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     */
    private function mockRepository(): TwigFileRepository
    {
        $template = new TestEmailTemplate(new TestEmailOptions('message', 'filepath'));

        $filePath = (new \ReflectionClass(get_class($template)))->getFileName();
        $directory = dirname($filePath);

        $repository = new TwigFileRepository(new \Twig_Environment(new \Twig_Loader_Chain([
            new \Twig_Loader_Filesystem('Data', $directory),
        ])));
        $repository->connect($template);

        return $repository;
    }
}
