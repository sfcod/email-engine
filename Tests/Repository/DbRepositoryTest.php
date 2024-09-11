<?php

namespace SfCod\EmailEngineBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use SfCod\EmailEngineBundle\Entity\EmailEntityInterface;
use SfCod\EmailEngineBundle\Example\TestEmailOptions;
use SfCod\EmailEngineBundle\Example\TestEmailTemplate;
use SfCod\EmailEngineBundle\Repository\DbRepository;

/**
 * Class DbRepositoryTest
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Tests\Repository
 */
class DbRepositoryTest extends TestCase
{
    /**
     * Test subject
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testSubject()
    {
        $title = uniqid('title_');
        $body = uniqid('body_');

        $email = $this->mockEmailEntity($title, $body);
        $repository = $this->mockRepository($email);

        $subject = $repository->getSubjectTemplate([]);

        $this->assertEquals($title, $subject);
    }

    /**
     * Test body
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     * @throws \Throwable
     */
    public function testBody()
    {
        $title = uniqid('title_');
        $body = uniqid('body_');

        $email = $this->mockEmailEntity($title, $body);
        $repository = $this->mockRepository($email);

        $subject = $repository->getBodyTemplate([]);

        $this->assertEquals($body, $subject);
    }

    /**
     * Test sender name
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     */
    public function testSenderName()
    {
        $title = uniqid('title_');
        $body = uniqid('body_');

        $email = $this->mockEmailEntity($title, $body);
        $repository = $this->mockRepository($email);

        $subject = $repository->getSenderNameTemplate([]);

        $this->assertEquals($_ENV['SENDER_NAME'], $subject);
    }

    /**
     * Test sender email
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     */
    public function testSenderEmail()
    {
        $title = uniqid('title_');
        $body = uniqid('body_');

        $email = $this->mockEmailEntity($title, $body);
        $repository = $this->mockRepository($email);

        $subject = $repository->getSenderEmailTemplate([]);

        $this->assertEquals($_ENV['SENDER_EMAIL'], $subject);
    }

    /**
     * Mock email entity
     *
     * @param string $title
     * @param string $body
     *
     * @return EmailEntityInterface
     */
    private function mockEmailEntity(string $title, string $body): EmailEntityInterface
    {
        $email = $this->createMock(EmailEntityInterface::class);
        $email
            ->expects($this->any())
            ->method('getTitle')
            ->willReturn($title);
        $email
            ->expects($this->any())
            ->method('getBody')
            ->willReturn($body);

        return $email;
    }

    /**
     * Mock repository
     *
     * @return DbRepository
     *
     * @throws \ReflectionException
     * @throws \SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException
     */
    private function mockRepository(EmailEntityInterface $email): DbRepository
    {
        $template = new TestEmailTemplate(new TestEmailOptions('message', 'filepath'));

        $filePath = (new \ReflectionClass(get_class($template)))->getFileName();
        $directory = dirname($filePath);
        $entity = uniqid('entity_');
        $attribute = uniqid('attribute_');

        $emRepository = $this->createMock(EntityRepository::class);
        $emRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([$attribute => get_class($template)::getSlug()])
            ->willReturn($email);

        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('getRepository')
            ->with($entity)
            ->willReturn($emRepository);

        $twig = new \Twig_Environment(new \Twig_Loader_Chain([
            new \Twig_Loader_Filesystem('Data', $directory),
        ]));

        $repository = new DbRepository($em, $twig);
        $repository->connect($template, [
            'entity' => $entity,
            'attribute' => $attribute,
        ]);

        return $repository;
    }
}
